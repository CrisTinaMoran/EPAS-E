<?php

namespace App\Http\Controllers;

use App\Models\InformationSheet;
use App\Models\JobSheet;
use App\Models\JobSheetStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobSheetController extends Controller
{
    public function create(InformationSheet $informationSheet)
    {
        return view('job-sheets.create', compact('informationSheet'));
    }

    public function store(Request $request, InformationSheet $informationSheet)
    {
        $request->validate([
            'job_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectives' => 'required|array|min:1',
            'tools_required' => 'required|array|min:1',
            'safety_requirements' => 'required|array|min:1',
            'reference_materials' => 'nullable|array',
            'steps' => 'required|array|min:1',
            'steps.*.step_number' => 'required|integer',
            'steps.*.instruction' => 'required|string',
            'steps.*.expected_outcome' => 'required|string',
            'steps.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $jobSheet = JobSheet::create([
            'information_sheet_id' => $informationSheet->id,
            'job_number' => $request->job_number,
            'title' => $request->title,
            'description' => $request->description,
            'objectives' => json_encode($request->objectives),
            'tools_required' => json_encode($request->tools_required),
            'safety_requirements' => json_encode($request->safety_requirements),
            'reference_materials' => json_encode($request->reference_materials ?? []),
        ]);

        foreach ($request->steps as $stepData) {
            $imagePath = null;
            if (isset($stepData['image']) && $stepData['image']->isValid()) {
                $imagePath = $stepData['image']->store('job-sheet-steps', 'public');
            }

            JobSheetStep::create([
                'job_sheet_id' => $jobSheet->id,
                'step_number' => $stepData['step_number'],
                'instruction' => $stepData['instruction'],
                'expected_outcome' => $stepData['expected_outcome'],
                'image_path' => $imagePath,
            ]);
        }

        return redirect()->route('courses.index')
            ->with('success', 'Job sheet created successfully!');
    }

    public function edit(InformationSheet $informationSheet, JobSheet $jobSheet)
    {
        $jobSheet->load('steps');
        return view('job-sheets.edit', compact('informationSheet', 'jobSheet'));
    }

    public function update(Request $request, InformationSheet $informationSheet, JobSheet $jobSheet)
    {
        $request->validate([
            'job_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectives' => 'required|array|min:1',
            'tools_required' => 'required|array|min:1',
            'safety_requirements' => 'required|array|min:1',
            'reference_materials' => 'nullable|array',
        ]);

        $jobSheet->update([
            'job_number' => $request->job_number,
            'title' => $request->title,
            'description' => $request->description,
            'objectives' => json_encode($request->objectives),
            'tools_required' => json_encode($request->tools_required),
            'safety_requirements' => json_encode($request->safety_requirements),
            'reference_materials' => json_encode($request->reference_materials ?? []),
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Job sheet updated successfully!');
    }

    public function destroy(InformationSheet $informationSheet, JobSheet $jobSheet)
    {
        // Delete step images
        foreach ($jobSheet->steps as $step) {
            if ($step->image_path) {
                Storage::disk('public')->delete($step->image_path);
            }
        }
        
        $jobSheet->steps()->delete();
        $jobSheet->delete();

        return response()->json(['success' => 'Job sheet deleted successfully!']);
    }

    public function show(JobSheet $jobSheet)
    {
        $jobSheet->load(['steps', 'informationSheet.module.course']);
        return view('job-sheets.show', compact('jobSheet'));
    }

    public function submit(Request $request, JobSheet $jobSheet)
    {
        $request->validate([
            'completed_steps' => 'required|array',
            'observations' => 'required|string',
            'challenges' => 'nullable|string',
            'solutions' => 'nullable|string',
        ]);

        $submission = $jobSheet->submissions()->create([
            'user_id' => auth()->id(),
            'completed_steps' => json_encode($request->completed_steps),
            'observations' => $request->observations,
            'challenges' => $request->challenges,
            'solutions' => $request->solutions,
            'submitted_at' => now(),
        ]);

        return redirect()->route('performance-criteria.create', ['jobSheet' => $jobSheet->id])
            ->with('success', 'Job sheet submitted successfully! Please complete the performance criteria.');
    }
}