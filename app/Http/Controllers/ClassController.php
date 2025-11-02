<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sectionFilter = $request->get('section');

        // Get all unique sections from students
        $allSections = User::where('role', 'student')
            ->whereNotNull('section')
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section')
            ->filter();

        // Get instructors for adviser assignment
        $instructors = User::where('role', 'instructor')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Get current advisers for each section
        $advisersBySection = [];
        foreach ($allSections as $section) {
            $adviser = User::where('role', 'instructor')
                ->where('advisory_section', $section)
                ->first();
            if ($adviser) {
                $advisersBySection[$section] = $adviser;
            }
        }

        // Get students grouped by section for the grid view
        $studentsBySection = [];
        foreach ($allSections as $section) {
            $query = User::where('role', 'student')->where('section', $section);
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', '%'.$search.'%')
                      ->orWhere('last_name', 'like', '%'.$search.'%')
                      ->orWhere('student_id', 'like', '%'.$search.'%');
                });
            }
            
            $studentsBySection[$section] = $query->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
        }

        // Get current adviser for the filtered section
        $currentAdviser = null;
        if ($sectionFilter) {
            $currentAdviser = User::where('role', 'instructor')
                ->where('advisory_section', $sectionFilter)
                ->first();
        }

        // If section filter is applied, show table view instead of grid
        if ($sectionFilter) {
            $students = User::where('role', 'student')
                ->where('section', $sectionFilter)
                ->when($search, function($query, $search) {
                    return $query->where(function($q) use ($search) {
                        $q->where('first_name', 'like', '%'.$search.'%')
                          ->orWhere('last_name', 'like', '%'.$search.'%')
                          ->orWhere('student_id', 'like', '%'.$search.'%');
                    });
                })
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->paginate(20);

            return view('private.class-management.index', compact(
                'allSections', 
                'studentsBySection', 
                'students',
                'sectionFilter',
                'search',
                'instructors',
                'currentAdviser',
                'advisersBySection'
            ));
        }

        return view('private.class-management.index', compact(
            'allSections', 
            'studentsBySection',
            'search',
            'sectionFilter',
            'instructors',
            'advisersBySection'
        ));
    }

    public function show($section)
    {
        // Get section data for show view
        $allSections = User::where('role', 'student')
            ->whereNotNull('section')
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section')
            ->filter();

        $instructors = User::where('role', 'instructor')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $currentAdviser = User::where('role', 'instructor')
            ->where('advisory_section', $section)
            ->first();

        $advisersBySection = [];
        foreach ($allSections as $sec) {
            $adviser = User::where('role', 'instructor')
                ->where('advisory_section', $sec)
                ->first();
            if ($adviser) {
                $advisersBySection[$sec] = $adviser;
            }
        }

        $students = User::where('role', 'student')
            ->where('section', $section)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(20);

        return view('private.class-management.show', compact(
            'section',
            'allSections',
            'students',
            'instructors',
            'currentAdviser',
            'advisersBySection'
        ));
    }

    public function assignAdviser(Request $request, $section)
    {
        $request->validate([
            'adviser_id' => 'required|exists:users,id'
        ]);

        // Remove current adviser from this section
        User::where('role', 'instructor')
            ->where('advisory_section', $section)
            ->update(['advisory_section' => null]);

        // Also remove the new adviser from any other sections they might be assigned to
        User::where('role', 'instructor')
            ->where('id', $request->adviser_id)
            ->update(['advisory_section' => null]);

        // Assign new adviser
        $adviser = User::where('role', 'instructor')
            ->where('id', $request->adviser_id)
            ->first();

        if ($adviser) {
            $adviser->advisory_section = $section;
            $adviser->save();
            
            return redirect()->back()
                ->with('success', "{$adviser->full_name} has been assigned as adviser for {$section}");
        }

        return redirect()->back()->with('error', 'Adviser not found.');
    }

    public function removeAdviser($section)
    {
        // Remove current adviser from this section
        $adviser = User::where('role', 'instructor')
            ->where('advisory_section', $section)
            ->first();

        if ($adviser) {
            $adviserName = $adviser->full_name;
            $adviser->advisory_section = null;
            $adviser->save();
            
            return redirect()->back()
                ->with('success', "{$adviserName} has been removed as adviser for {$section}");
        }

        return redirect()->back()->with('error', 'No adviser found for this section.');
    }
}