<?php

namespace App\Http\Controllers;

use App\Models\InformationSheet;
use App\Models\TaskSheet;
use App\Models\TaskSheetItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskSheetController extends Controller
{
    public function create(InformationSheet $informationSheet)
    {
        return view('task-sheets.create', compact('informationSheet'));
    }

    public function store(Request $request, InformationSheet $informationSheet)
    {
        $request->validate([
            'task_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'objectives' => 'required|array|min:1',
            'materials' => 'required|array|min:1',
            'safety_precautions' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'items' => 'required|array|min:1',
            'items.*.part_name' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.expected_finding' => 'required|string',
            'items.*.acceptable_range' => 'required|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('task-sheets', 'public');
        }

        $taskSheet = TaskSheet::create([
            'information_sheet_id' => $informationSheet->id,
            'task_number' => $request->task_number,
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'objectives' => json_encode($request->objectives),
            'materials' => json_encode($request->materials),
            'safety_precautions' => json_encode($request->safety_precautions ?? []),
            'image_path' => $imagePath,
        ]);

        foreach ($request->items as $itemData) {
            TaskSheetItem::create([
                'task_sheet_id' => $taskSheet->id,
                'part_name' => $itemData['part_name'],
                'description' => $itemData['description'],
                'expected_finding' => $itemData['expected_finding'],
                'acceptable_range' => $itemData['acceptable_range'],
                'order' => $itemData['order'] ?? 0,
            ]);
        }

        return redirect()->route('courses.index')
            ->with('success', 'Task sheet created successfully!');
    }

    public function edit(InformationSheet $informationSheet, TaskSheet $taskSheet)
    {
        $taskSheet->load('items');
        return view('task-sheets.edit', compact('informationSheet', 'taskSheet'));
    }

    public function update(Request $request, InformationSheet $informationSheet, TaskSheet $taskSheet)
    {
        $request->validate([
            'task_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'objectives' => 'required|array|min:1',
            'materials' => 'required|array|min:1',
            'safety_precautions' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $taskSheet->image_path;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('task-sheets', 'public');
        }

        $taskSheet->update([
            'task_number' => $request->task_number,
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'objectives' => json_encode($request->objectives),
            'materials' => json_encode($request->materials),
            'safety_precautions' => json_encode($request->safety_precautions ?? []),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Task sheet updated successfully!');
    }

    public function destroy(InformationSheet $informationSheet, TaskSheet $taskSheet)
    {
        if ($taskSheet->image_path) {
            Storage::disk('public')->delete($taskSheet->image_path);
        }
        $taskSheet->items()->delete();
        $taskSheet->delete();

        return response()->json(['success' => 'Task sheet deleted successfully!']);
    }

    public function show(TaskSheet $taskSheet)
    {
        $taskSheet->load(['items', 'informationSheet.module.course']);
        return view('task-sheets.show', compact('taskSheet'));
    }

    public function submit(Request $request, TaskSheet $taskSheet)
    {
        $request->validate([
            'findings' => 'required|array',
            'findings.*' => 'required|string',
        ]);

        $submission = $taskSheet->submissions()->create([
            'user_id' => auth()->id(),
            'findings' => json_encode($request->findings),
            'submitted_at' => now(),
        ]);

        return redirect()->route('performance-criteria.create', ['taskSheet' => $taskSheet->id])
            ->with('success', 'Task sheet submitted successfully! Please complete the performance criteria.');
    }
}