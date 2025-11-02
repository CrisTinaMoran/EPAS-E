@extends('layouts.app')

@section('title', 'Create Self Check')

@section('content')
<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Create Self Check</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('content.management') }}">Content Management</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses.show', $informationSheet->module->course_id) }}">{{ $informationSheet->module->course->course_name ?? 'Course' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('modules.show', $informationSheet->module_id) }}">{{ $informationSheet->module->module_name }}</a></li>
                <li class="breadcrumb-item active">Create Self Check</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Self Check for: {{ $informationSheet->title }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('self-checks.store', $informationSheet) }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Self Check Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="min_score" class="form-label">Minimum Passing Score (%) *</label>
                            <input type="number" class="form-control @error('min_score') is-invalid @enderror" 
                                   id="min_score" name="min_score" value="{{ old('min_score', 70) }}" 
                                   min="0" max="100" required>
                            @error('min_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Questions Section -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Questions</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-question">
                            <i class="fas fa-plus me-1"></i> Add Question
                        </button>
                    </div>

                    <div id="questions-container">
                        <!-- Questions will be added here dynamically -->
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('content.management', ['module' => $informationSheet->module_id, 'informationSheet' => $informationSheet->id]) }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Content Management
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Self Check
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Question Template (Hidden) -->
<template id="question-template">
    <div class="question-item card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Question <span class="question-number">1</span></h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-question">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Question Text *</label>
                        <input type="text" class="form-control question-text" name="questions[0][question]" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Question Type *</label>
                        <select class="form-select question-type" name="questions[0][type]" required>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                            <option value="short_answer">Short Answer</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Options for Multiple Choice -->
            <div class="question-options multiple-choice-options">
                <div class="mb-2">
                    <label class="form-label">Options (one per line) *</label>
                    <textarea class="form-control options-text" name="questions[0][options]" rows="3" placeholder="Enter each option on a new line"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correct Answer *</label>
                    <select class="form-control correct-answer" name="questions[0][correct_answer]" required>
                        <option value="">Select correct option</option>
                    </select>
                </div>
            </div>

            <!-- Options for True/False -->
            <div class="question-options true-false-options" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">Correct Answer *</label>
                    <select class="form-control correct-answer" name="questions[0][correct_answer]" required>
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </div>
            </div>

            <!-- Options for Short Answer -->
            <div class="question-options short-answer-options" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">Correct Answer *</label>
                    <input type="text" class="form-control correct-answer" name="questions[0][correct_answer]" placeholder="Enter the correct answer">
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.question-item {
    border-left: 4px solid #007bff;
}

.question-options {
    transition: all 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionCount = 0;
    const questionsContainer = document.getElementById('questions-container');
    const questionTemplate = document.getElementById('question-template');
    const addQuestionBtn = document.getElementById('add-question');

    // Add first question by default
    addQuestion();

    addQuestionBtn.addEventListener('click', addQuestion);

    function addQuestion() {
        const questionClone = document.importNode(questionTemplate.content, true);
        const questionElement = questionClone.querySelector('.question-item');
        
        // Update question number
        questionCount++;
        questionElement.querySelector('.question-number').textContent = questionCount;
        
        // Update all input names with current index
        const inputs = questionElement.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('[0]', `[${questionCount}]`));
            }
        });

        // Add remove functionality
        questionElement.querySelector('.remove-question').addEventListener('click', function() {
            questionElement.remove();
            updateQuestionNumbers();
        });

        // Add type change functionality
        const typeSelect = questionElement.querySelector('.question-type');
        typeSelect.addEventListener('change', function() {
            updateQuestionOptions(questionElement, this.value);
        });

        // Initialize options for multiple choice
        updateQuestionOptions(questionElement, 'multiple_choice');

        questionsContainer.appendChild(questionElement);
    }

    function updateQuestionOptions(questionElement, type) {
        // Hide all options first
        const allOptions = questionElement.querySelectorAll('.question-options');
        allOptions.forEach(option => option.style.display = 'none');

        // Show relevant options based on type
        if (type === 'multiple_choice') {
            const optionsDiv = questionElement.querySelector('.multiple-choice-options');
            optionsDiv.style.display = 'block';
            
            // Update options when text changes
            const optionsText = questionElement.querySelector('.options-text');
            const correctAnswerSelect = questionElement.querySelector('.correct-answer');
            
            optionsText.addEventListener('input', function() {
                updateMultipleChoiceOptions(optionsText, correctAnswerSelect);
            });
            
            updateMultipleChoiceOptions(optionsText, correctAnswerSelect);
            
        } else if (type === 'true_false') {
            questionElement.querySelector('.true-false-options').style.display = 'block';
        } else if (type === 'short_answer') {
            questionElement.querySelector('.short-answer-options').style.display = 'block';
        }
    }

    function updateMultipleChoiceOptions(optionsText, correctAnswerSelect) {
        const options = optionsText.value.split('\n').filter(opt => opt.trim() !== '');
        correctAnswerSelect.innerHTML = '<option value="">Select correct option</option>';
        
        options.forEach((option, index) => {
            const optionElement = document.createElement('option');
            optionElement.value = option.trim();
            optionElement.textContent = option.trim();
            correctAnswerSelect.appendChild(optionElement);
        });
    }

    function updateQuestionNumbers() {
        const questions = questionsContainer.querySelectorAll('.question-item');
        questionCount = 0;
        
        questions.forEach((question, index) => {
            questionCount++;
            question.querySelector('.question-number').textContent = questionCount;
            
            // Update input names
            const inputs = question.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                const oldName = input.getAttribute('name');
                if (oldName) {
                    const newName = oldName.replace(/\[\d+\]/, `[${questionCount}]`);
                    input.setAttribute('name', newName);
                }
            });
        });
    }
});
</script>
@endsection