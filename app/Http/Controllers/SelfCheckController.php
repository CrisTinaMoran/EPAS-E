<?php

namespace App\Http\Controllers;

use App\Models\InformationSheet;
use App\Models\SelfCheck;
use App\Models\SelfCheckQuestion;
use Illuminate\Http\Request;

class SelfCheckController extends Controller
{
    public function create(InformationSheet $informationSheet)
    {
        return view('modules.self-checks.create', compact('informationSheet'));
    }

    public function store(Request $request, InformationSheet $informationSheet)
    {
        $request->validate([
            'check_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:multiple_choice,true_false,identification,essay,matching,enumeration',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'required_if:questions.*.question_type,multiple_choice,matching|array',
            'questions.*.correct_answer' => 'required_if:questions.*.question_type,multiple_choice,true_false,identification,enumeration',
            'questions.*.explanation' => 'nullable|string',
        ]);

        $selfCheck = SelfCheck::create([
            'information_sheet_id' => $informationSheet->id,
            'check_number' => $request->check_number,
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'time_limit' => $request->time_limit,
            'passing_score' => $request->passing_score,
            'total_points' => array_sum(array_column($request->questions, 'points')),
        ]);

        foreach ($request->questions as $questionData) {
            $question = SelfCheckQuestion::create([
                'self_check_id' => $selfCheck->id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'points' => $questionData['points'],
                'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                'correct_answer' => $questionData['correct_answer'] ?? null,
                'explanation' => $questionData['explanation'] ?? null,
            ]);
        }

        return redirect()->route('courses.index')
            ->with('success', 'Self-check created successfully!');
    }

    public function edit(InformationSheet $informationSheet, SelfCheck $selfCheck)
    {
        $selfCheck->load('questions');
        return view('modules.self-checks.edit', compact('informationSheet', 'selfCheck'));
    }

    public function update(Request $request, InformationSheet $informationSheet, SelfCheck $selfCheck)
    {
        $request->validate([
            'check_number' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
        ]);

        $selfCheck->update([
            'check_number' => $request->check_number,
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'time_limit' => $request->time_limit,
            'passing_score' => $request->passing_score,
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Self-check updated successfully!');
    }

    public function destroy(InformationSheet $informationSheet, SelfCheck $selfCheck)
    {
        $selfCheck->questions()->delete();
        $selfCheck->delete();

        return response()->json(['success' => 'Self-check deleted successfully!']);
    }

    public function show(SelfCheck $selfCheck)
    {
        $selfCheck->load(['questions', 'informationSheet.module.course']);
        return view('modules.self-checks.show', compact('selfCheck'));
    }

    public function submit(Request $request, SelfCheck $selfCheck)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required',
        ]);

        $score = 0;
        $totalPoints = $selfCheck->total_points;
        $results = [];

        foreach ($selfCheck->questions as $question) {
            $userAnswer = $request->answers[$question->id] ?? '';
            $isCorrect = false;

            switch ($question->question_type) {
                case 'multiple_choice':
                case 'true_false':
                case 'identification':
                    $isCorrect = strtolower(trim($userAnswer)) === strtolower(trim($question->correct_answer));
                    break;
                case 'enumeration':
                    $userAnswers = array_map('trim', explode(',', $userAnswer));
                    $correctAnswers = array_map('trim', explode(',', $question->correct_answer));
                    $isCorrect = empty(array_diff($correctAnswers, $userAnswers));
                    break;
                case 'matching':
                    // Handle matching questions
                    break;
                case 'essay':
                    // Essay questions are manually graded
                    $isCorrect = null;
                    break;
            }

            if ($isCorrect) {
                $score += $question->points;
            }

            $results[] = [
                'question' => $question,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'points_earned' => $isCorrect ? $question->points : 0,
            ];
        }

        $percentage = ($score / $totalPoints) * 100;
        $passed = $percentage >= $selfCheck->passing_score;

        // Save submission to database
        $submission = $selfCheck->submissions()->create([
            'user_id' => auth()->id(),
            'score' => $score,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
            'passed' => $passed,
            'answers' => json_encode($request->answers),
            'completed_at' => now(),
        ]);

        return view('modules.self-checks.results', compact('selfCheck', 'submission', 'results', 'score', 'totalPoints', 'percentage', 'passed'));
    }
}