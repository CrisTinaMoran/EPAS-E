<?php

namespace App\Http\Controllers;

use App\Models\PerformanceCriteria;
use App\Models\TaskSheet;
use App\Models\JobSheet;
use Illuminate\Http\Request;

class PerformanceCriteriaController extends Controller
{
    public function create(Request $request)
    {
        $taskSheet = null;
        $jobSheet = null;
        $type = null;
        $relatedId = null;

        if ($request->has('taskSheet')) {
            $taskSheet = TaskSheet::findOrFail($request->taskSheet);
            $type = 'task_sheet';
            $relatedId = $taskSheet->id;
        } elseif ($request->has('jobSheet')) {
            $jobSheet = JobSheet::findOrFail($request->jobSheet);
            $type = 'job_sheet';
            $relatedId = $jobSheet->id;
        }

        // Check if performance criteria already exists
        $performanceCriteria = PerformanceCriteria::where('type', $type)
            ->where('related_id', $relatedId)
            ->first();

        if ($performanceCriteria) {
            return view('performance-criteria.edit', compact('performanceCriteria', 'taskSheet', 'jobSheet'));
        }

        return view('performance-criteria.create', compact('taskSheet', 'jobSheet', 'type', 'relatedId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:task_sheet,job_sheet',
            'related_id' => 'required|integer',
            'criteria' => 'required|array|min:1',
            'criteria.*.description' => 'required|string',
            'criteria.*.observed' => 'required|boolean',
            'criteria.*.remarks' => 'nullable|string',
        ]);

        $performanceCriteria = PerformanceCriteria::create([
            'type' => $request->type,
            'related_id' => $request->related_id,
            'user_id' => auth()->id(),
            'criteria' => json_encode($request->criteria),
            'completed_at' => now(),
        ]);

        // Calculate score based on observed criteria
        $totalCriteria = count($request->criteria);
        $observedCriteria = count(array_filter($request->criteria, function($criterion) {
            return $criterion['observed'] == true;
        }));
        $score = $totalCriteria > 0 ? ($observedCriteria / $totalCriteria) * 100 : 0;

        $performanceCriteria->update(['score' => $score]);

        return redirect()->route('courses.index')
            ->with('success', 'Performance criteria submitted successfully!');
    }

    public function edit(PerformanceCriteria $performanceCriteria)
    {
        $taskSheet = null;
        $jobSheet = null;

        if ($performanceCriteria->type === 'task_sheet') {
            $taskSheet = TaskSheet::find($performanceCriteria->related_id);
        } elseif ($performanceCriteria->type === 'job_sheet') {
            $jobSheet = JobSheet::find($performanceCriteria->related_id);
        }

        return view('performance-criteria.edit', compact('performanceCriteria', 'taskSheet', 'jobSheet'));
    }

    public function update(Request $request, PerformanceCriteria $performanceCriteria)
    {
        $request->validate([
            'criteria' => 'required|array|min:1',
            'criteria.*.description' => 'required|string',
            'criteria.*.observed' => 'required|boolean',
            'criteria.*.remarks' => 'nullable|string',
        ]);

        $performanceCriteria->update([
            'criteria' => json_encode($request->criteria),
            'completed_at' => now(),
        ]);

        // Recalculate score
        $totalCriteria = count($request->criteria);
        $observedCriteria = count(array_filter($request->criteria, function($criterion) {
            return $criterion['observed'] == true;
        }));
        $score = $totalCriteria > 0 ? ($observedCriteria / $totalCriteria) * 100 : 0;

        $performanceCriteria->update(['score' => $score]);

        return redirect()->route('courses.index')
            ->with('success', 'Performance criteria updated successfully!');
    }

    public function destroy(PerformanceCriteria $performanceCriteria)
    {
        $performanceCriteria->delete();
        return response()->json(['success' => 'Performance criteria deleted successfully!']);
    }
}