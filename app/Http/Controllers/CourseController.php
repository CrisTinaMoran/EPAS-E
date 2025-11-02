<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\InformationSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_active', true)
            ->withCount(['modules' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('order')
            ->get();

        return view('courses.index', compact('courses'));
    }

    public function contentManagement()
    {
        $courses = Course::with([
            'modules' => function($query) {
                $query->orderBy('order');
            },
            'modules.informationSheets' => function($query) {
                $query->orderBy('sheet_number')
                    ->with(['topics', 'selfChecks', 'taskSheets', 'jobSheets']);
            }
        ])->orderBy('order')->get();

        return view('content-management.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses',
            'description' => 'nullable|string',
            'sector' => 'nullable|string|max:255',
        ]);

        try {
            $course = Course::create([
                'course_name' => $validated['course_name'],
                'course_code' => $validated['course_code'],
                'description' => $validated['description'],
                'sector' => $validated['sector'],
                'is_active' => true,
                'order' => Course::max('order') + 1,
            ]);

            return redirect()->route('courses.show', $course->id)
                ->with('success', 'Course created successfully!');

        } catch (\Exception $e) {
            Log::error('Course creation failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to create course. Please try again.');
        }
    }

    public function show(Course $course)
    {
        $course->load(['modules' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }]);

        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses,course_code,' . $course->id,
            'description' => 'nullable|string',
            'sector' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        try {
            $course->update($validated);

            return redirect()->route('courses.show', $course->id)
                ->with('success', 'Course updated successfully!');

        } catch (\Exception $e) {
            Log::error('Course update failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to update course. Please try again.');
        }
    }

    public function destroy(Course $course)
    {
        try {
            DB::transaction(function () use ($course) {
                // Delete all related content recursively
                foreach ($course->modules as $module) {
                    // Delete information sheets and their content
                    foreach ($module->informationSheets as $infoSheet) {
                        // Delete related content (adjust these based on your actual relationships)
                        $infoSheet->topics()->delete();
                        $infoSheet->selfChecks()->delete();
                        $infoSheet->taskSheets()->delete();
                        $infoSheet->jobSheets()->delete();
                        // Add other content types as needed
                        
                        $infoSheet->delete();
                    }
                    
                    $module->delete();
                }
                
                $course->delete();
            });

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Course and all associated content deleted successfully!'
                ]);
            }

            return redirect()->route('content.management')
                ->with('success', 'Course and all associated content deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Course deletion failed: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete course. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete course. Please try again.');
        }
    }
}