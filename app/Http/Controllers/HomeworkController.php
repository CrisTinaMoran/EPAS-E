<?php

namespace App\Http\Controllers;

use App\Models\InformationSheet;
use App\Models\Homework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeworkController extends Controller
{
    public function create(InformationSheet $informationSheet)
    {
        return view('homeworks.create', compact('informationSheet'));
    }

    public function store(Request $request, InformationSheet $informationSheet)
    {
        $request->validate([
            'homework_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'requirements' => 'required|array|min:1',
            'submission_guidelines' => 'required|array|min:1',
            'due_date' => 'required|date',
            'max_points' => 'required|integer|min:1',
            'reference_images' => 'nullable|array',
            'reference_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $referenceImagePaths = [];
        if ($request->hasFile('reference_images')) {
            foreach ($request->file('reference_images') as $image) {
                $referenceImagePaths[] = $image->store('homework-references', 'public');
            }
        }

        $homework = Homework::create([
            'information_sheet_id' => $informationSheet->id,
            'homework_number' => $request->homework_number,
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'requirements' => json_encode($request->requirements),
            'submission_guidelines' => json_encode($request->submission_guidelines),
            'reference_images' => json_encode($referenceImagePaths),
            'due_date' => $request->due_date,
            'max_points' => $request->max_points,
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Homework created successfully!');
    }

    public function edit(InformationSheet $informationSheet, Homework $homework)
    {
        return view('homeworks.edit', compact('informationSheet', 'homework'));
    }

    public function update(Request $request, InformationSheet $informationSheet, Homework $homework)
    {
        $request->validate([
            'homework_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'requirements' => 'required|array|min:1',
            'submission_guidelines' => 'required|array|min:1',
            'due_date' => 'required|date',
            'max_points' => 'required|integer|min:1',
            'reference_images' => 'nullable|array',
            'reference_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $referenceImagePaths = json_decode($homework->reference_images, true) ?? [];
        
        if ($request->hasFile('reference_images')) {
            // Delete old reference images
            foreach ($referenceImagePaths as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            
            $referenceImagePaths = [];
            foreach ($request->file('reference_images') as $image) {
                $referenceImagePaths[] = $image->store('homework-references', 'public');
            }
        }

        $homework->update([
            'homework_number' => $request->homework_number,
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'requirements' => json_encode($request->requirements),
            'submission_guidelines' => json_encode($request->submission_guidelines),
            'reference_images' => json_encode($referenceImagePaths),
            'due_date' => $request->due_date,
            'max_points' => $request->max_points,
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Homework updated successfully!');
    }

    public function destroy(InformationSheet $informationSheet, Homework $homework)
    {
        // Delete reference images
        $referenceImages = json_decode($homework->reference_images, true) ?? [];
        foreach ($referenceImages as $image) {
            Storage::disk('public')->delete($image);
        }
        
        $homework->delete();

        return response()->json(['success' => 'Homework deleted successfully!']);
    }

    public function show(Homework $homework)
    {
        $homework->load(['informationSheet.module.course']);
        return view('homeworks.show', compact('homework'));
    }

    public function submit(Request $request, Homework $homework)
    {
        $request->validate([
            'submission_file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:10240',
            'description' => 'nullable|string',
            'work_hours' => 'nullable|numeric|min:0',
        ]);

        $filePath = $request->file('submission_file')->store('homework-submissions', 'public');

        $submission = $homework->submissions()->create([
            'user_id' => auth()->id(),
            'file_path' => $filePath,
            'description' => $request->description,
            'work_hours' => $request->work_hours,
            'submitted_at' => now(),
        ]);

        return redirect()->route('homeworks.show', $homework)
            ->with('success', 'Homework submitted successfully!');
    }
}