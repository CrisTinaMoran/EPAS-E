<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\InformationSheet;
use App\Models\TaskSheet;
use App\Models\JobSheet;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function create(InformationSheet $informationSheet)
    {
        return view('checklists.create', compact('informationSheet'));
    }

    public function store(Request $request, InformationSheet $informationSheet)
    {
        $request->validate([
            'checklist_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.rating' => 'required|integer|min:1|max:5',
            'items.*.remarks' => 'nullable|string',
        ]);

        $checklist = Checklist::create([
            'information_sheet_id' => $informationSheet->id,
            'checklist_number' => $request->checklist_number,
            'title' => $request->title,
            'description' => $request->description,
            'items' => json_encode($request->items),
            'total_score' => array_sum(array_column($request->items, 'rating')),
            'max_score' => count($request->items) * 5,
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Checklist created successfully!');
    }

    public function edit(InformationSheet $informationSheet, Checklist $checklist)
    {
        return view('checklists.edit', compact('informationSheet', 'checklist'));
    }

    public function update(Request $request, InformationSheet $informationSheet, Checklist $checklist)
    {
        $request->validate([
            'checklist_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.rating' => 'required|integer|min:1|max:5',
            'items.*.remarks' => 'nullable|string',
        ]);

        $checklist->update([
            'checklist_number' => $request->checklist_number,
            'title' => $request->title,
            'description' => $request->description,
            'items' => json_encode($request->items),
            'total_score' => array_sum(array_column($request->items, 'rating')),
            'max_score' => count($request->items) * 5,
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Checklist updated successfully!');
    }

    public function destroy(InformationSheet $informationSheet, Checklist $checklist)
    {
        $checklist->delete();
        return response()->json(['success' => 'Checklist deleted successfully!']);
    }

    public function show(Checklist $checklist)
    {
        $checklist->load(['informationSheet.module.course']);
        return view('checklists.show', compact('checklist'));
    }

    public function evaluate(Request $request, Checklist $checklist)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.rating' => 'required|integer|min:1|max:5',
            'items.*.remarks' => 'nullable|string',
        ]);

        $items = json_decode($checklist->items, true);
        
        foreach ($items as $index => &$item) {
            if (isset($request->items[$index])) {
                $item['rating'] = $request->items[$index]['rating'];
                $item['remarks'] = $request->items[$index]['remarks'] ?? '';
            }
        }

        $checklist->update([
            'items' => json_encode($items),
            'total_score' => array_sum(array_column($items, 'rating')),
            'evaluated_by' => auth()->id(),
            'evaluated_at' => now(),
        ]);

        return redirect()->route('checklists.show', $checklist)
            ->with('success', 'Checklist evaluation submitted successfully!');
    }
}