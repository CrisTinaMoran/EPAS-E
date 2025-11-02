<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\InformationSheet;
use App\Models\Course;
use App\Models\Topic;
use App\Models\UserProgress;
use App\Models\SelfCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('modules.index', compact('modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id', // Add this
            'qualification_title' => 'required|string|max:255',
            'unit_of_competency' => 'required|string|max:255',
            'module_title' => 'required|string|max:255',
            'module_number' => 'required|string|max:50',
            'module_name' => 'required|string|max:255',
            'table_of_contents' => 'nullable|string',
            'how_to_use_cblm' => 'nullable|string',
            'introduction' => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
        ]);

        try {
            $module = Module::create([
                'course_id' => $validated['course_id'], // Add this
                'qualification_title' => $validated['qualification_title'],
                'unit_of_competency' => $validated['unit_of_competency'],
                'module_title' => $validated['module_title'],
                'module_number' => $validated['module_number'],
                'module_name' => $validated['module_name'],
                'table_of_contents' => $validated['table_of_contents'],
                'how_to_use_cblm' => $validated['how_to_use_cblm'],
                'introduction' => $validated['introduction'],
                'learning_outcomes' => $validated['learning_outcomes'],
                'is_active' => true,
                'order' => Module::where('course_id', $validated['course_id'])->max('order') + 1,
            ]);

            return redirect()->route('courses.show', $module->course_id)
                ->with('success', 'Module created successfully!');

        } catch (\Exception $e) {
            Log::error('Module creation failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to create module. Please try again.');
        }
    }


    public function show(Module $module)
    {
        $module->load([
            'informationSheets.selfChecks',
            'informationSheets.topics'
        ]);

        return view('modules.show', compact('module'));
    }

    public function showInformationSheet(Module $module, InformationSheet $informationSheet)
    {
        // Get all sheets for navigation
        $allSheets = $module->informationSheets()->orderBy('sheet_number')->get();
        $currentIndex = $allSheets->search(function($sheet) use ($informationSheet) {
            return $sheet->id === $informationSheet->id;
        });
        
        $prevSheet = $currentIndex > 0 ? $allSheets[$currentIndex - 1] : null;
        $nextSheet = $currentIndex < $allSheets->count() - 1 ? $allSheets[$currentIndex + 1] : null;

        return view('modules.information-sheets.show', [
            'module' => $module,
            'informationSheet' => $informationSheet,
            'prevSheet' => $prevSheet,
            'nextSheet' => $nextSheet,
            'currentSheetNumber' => $currentIndex + 1,
            'totalSheets' => $allSheets->count()
        ]);
    }

    public function getContent(Module $module, $contentType)
    {
        try {
            // Return specific module content based on type
            $viewMap = [
                'introduction' => 'modules.content.introduction',
                'electric-history' => 'modules.content.electric-history',
                'static-electricity' => 'modules.content.static-electricity',
                'free-electrons' => 'modules.content.free-electrons',
                'alternative-energy' => 'modules.content.alternative-energy',
                'electric-energy' => 'modules.content.electric-energy',
                'materials' => 'modules.content.materials',
                'self-check' => 'modules.content.self-check'
            ];

            if (!array_key_exists($contentType, $viewMap)) {
                return response()->json(['error' => 'Content type not found'], 404);
            }

            return response()->json([
                'html' => view($viewMap[$contentType], compact('module'))->render()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading module content: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load module content'
            ], 500);
        }
    }

    public function showTopic(Module $module, InformationSheet $informationSheet, Topic $topic)
    {
        // Verify relationships
        if ($topic->information_sheet_id !== $informationSheet->id || 
            $informationSheet->module_id !== $module->id) {
            abort(404);
        }

        // Track progress
        $this->trackTopicProgress($topic);

        return view('modules.topics.show', [
            'module' => $module,
            'informationSheet' => $informationSheet,
            'topic' => $topic,
            'nextTopic' => $topic->getNextTopic(),
            'prevTopic' => $topic->getPreviousTopic()
        ]);
    }

    public function submitSelfCheck(Request $request, SelfCheck $selfCheck)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'time_spent' => 'required|integer'
        ]);

        $score = $this->calculateScore($selfCheck, $validated['answers']);
        $maxScore = $this->getMaxScore($selfCheck);
        $minScore = $selfCheck->min_score ?? ($maxScore * 0.7);
        $passed = $score >= $minScore;

        // Update user progress
        UserProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'module_id' => $selfCheck->informationSheet->module_id,
                'progressable_type' => SelfCheck::class,
                'progressable_id' => $selfCheck->id
            ],
            [
                'status' => $passed ? 'passed' : 'failed',
                'score' => $score,
                'max_score' => $maxScore,
                'time_spent' => $validated['time_spent'],
                'answers' => $validated['answers'],
                'completed_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'score' => $score,
            'max_score' => $maxScore,
            'min_score_required' => $minScore,
            'passed' => $passed
        ]);
    }
    
    public function create()
    {
        $courses = Course::where('is_active', true)->orderBy('course_name')->get();
        return view('modules.create', compact('courses'));
    }

    public function getModuleProgress(Module $module)
    {
        $userId = auth()->id();
        
        $progress = UserProgress::where('user_id', $userId)
            ->where('module_id', $module->id)
            ->get();

        $completedSheets = $progress->where('progressable_type', InformationSheet::class)
            ->where('status', 'completed')->count();

        $totalSheets = $module->informationSheets->count();
        $progressPercentage = $totalSheets > 0 ? ($completedSheets / $totalSheets) * 100 : 0;

        return response()->json([
            'overall_progress' => $progressPercentage,
            'completed_sheets' => $completedSheets,
            'total_sheets' => $totalSheets
        ]);
    }

    // Private helper methods
    private function trackTopicProgress(Topic $topic)
    {
        if (!$topic->informationSheet || !$topic->informationSheet->module_id) {
            return;
        }

        // Track topic completion
        UserProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'module_id' => $topic->informationSheet->module_id,
                'progressable_type' => Topic::class,
                'progressable_id' => $topic->id
            ],
            [
                'status' => 'completed',
                'completed_at' => now()
            ]
        );

        // Track information sheet progress
        UserProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'module_id' => $topic->informationSheet->module_id,
                'progressable_type' => InformationSheet::class,
                'progressable_id' => $topic->informationSheet->id
            ],
            [
                'status' => 'in_progress',
                'started_at' => now()
            ]
        );
    }

    private function calculateScore(SelfCheck $selfCheck, array $answers)
    {
        $correctAnswers = json_decode($selfCheck->answer_key, true) ?? [];
        $score = 0;

        foreach ($answers as $questionId => $userAnswer) {
            if (isset($correctAnswers[$questionId])) {
                $correctAnswer = $correctAnswers[$questionId];
                
                if (is_array($correctAnswer)) {
                    if (in_array($userAnswer, $correctAnswer)) {
                        $score++;
                    }
                } else {
                    if ($correctAnswer === $userAnswer) {
                        $score++;
                    }
                }
            }
        }

        return $score;
    }

    private function getMaxScore(SelfCheck $selfCheck)
    {
        $correctAnswers = json_decode($selfCheck->answer_key, true) ?? [];
        return count($correctAnswers);
    }

    private function getSimpleContent($module, $contentType)
    {
        $titles = [
            'introduction' => 'Introduction to Electronics and Electricity',
            'electric-history' => 'Electric History',
            'static-electricity' => 'Static Electricity',
            'free-electrons' => 'Free Electrons and Sources of Electricity',
            'alternative-energy' => 'Alternative Energy',
            'electric-energy' => 'Types of Electric Energy and Current',
            'materials' => 'Types of Materials',
            'self-check' => 'Self Check No. 1.1.1'
        ];
        
        $title = $titles[$contentType] ?? $contentType;
        
        return "
            <div class='section-header'>
                <h2>{$title}</h2>
                <p>Content loading for {$title}</p>
            </div>
            <div class='content-display'>
                <p>This is the content for <strong>{$title}</strong>.</p>
                <p>Module: {$module->module_name}</p>
                <p>Content type: {$contentType}</p>
                <p><em>Note: The actual view file could not be loaded. Please check the file path.</em></p>
            </div>
        ";
    }

    public function edit(Module $module)
    {
        $courses = Course::where('is_active', true)->orderBy('course_name')->get();
        return view('modules.edit', compact('module', 'courses'));
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'qualification_title' => 'required|string|max:255',
            'unit_of_competency' => 'required|string|max:255',
            'module_title' => 'required|string|max:255',
            'module_number' => 'required|string|max:50',
            'module_name' => 'required|string|max:255',
            'table_of_contents' => 'nullable|string',
            'how_to_use_cblm' => 'nullable|string',
            'introduction' => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $module->update($validated);

            return redirect()->route('courses.show', $module->course_id)
                ->with('success', 'Module updated successfully!');

        } catch (\Exception $e) {
            Log::error('Module update failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to update module. Please try again.');
        }
    }

    public function destroy(Module $module)
    {
        try {
            // Check if module has information sheets
            if ($module->informationSheets()->count() > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'Cannot delete module that has information sheets. Please delete the information sheets first.'
                    ], 422);
                }
                return back()->with('error', 'Cannot delete module that has information sheets. Please delete the information sheets first.');
            }

            $courseId = $module->course_id;
            $module->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Module deleted successfully!'
                ]);
            }

            return redirect()->route('courses.show', $courseId)
                ->with('success', 'Module deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Module deletion failed: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete module. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete module. Please try again.');
        }
    }
}