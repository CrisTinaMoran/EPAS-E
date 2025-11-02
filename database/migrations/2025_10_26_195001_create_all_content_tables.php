<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Self Checks and related tables
        if (!Schema::hasTable('self_checks')) {
            Schema::create('self_checks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('information_sheet_id')->constrained()->onDelete('cascade');
                $table->string('check_number');
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('instructions');
                $table->integer('time_limit')->nullable()->comment('Time limit in minutes');
                $table->integer('passing_score')->default(70);
                $table->integer('total_points')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['information_sheet_id', 'check_number']);
            });
        }

        if (!Schema::hasTable('self_check_questions')) {
            Schema::create('self_check_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('self_check_id')->constrained()->onDelete('cascade');
                $table->text('question_text');
                $table->enum('question_type', [
                    'multiple_choice', 
                    'true_false', 
                    'identification', 
                    'essay', 
                    'matching', 
                    'enumeration'
                ]);
                $table->integer('points')->default(1);
                $table->json('options')->nullable()->comment('JSON array of options for multiple choice/matching');
                $table->text('correct_answer')->nullable()->comment('Correct answer based on question type');
                $table->text('explanation')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();

                $table->index(['self_check_id', 'order']);
            });
        }

        if (!Schema::hasTable('self_check_submissions')) {
            Schema::create('self_check_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('self_check_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('score')->default(0);
                $table->integer('total_points');
                $table->decimal('percentage', 5, 2)->default(0);
                $table->boolean('passed')->default(false);
                $table->json('answers')->comment('JSON object of user answers');
                $table->integer('time_taken')->nullable()->comment('Time taken in seconds');
                $table->timestamp('completed_at');
                $table->timestamps();

                $table->index(['self_check_id', 'user_id']);
                $table->index(['user_id', 'completed_at']);
            });
        }

        // Task Sheets and related tables
        if (!Schema::hasTable('task_sheets')) {
            Schema::create('task_sheets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('information_sheet_id')->constrained()->onDelete('cascade');
                $table->string('task_number');
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('instructions');
                $table->json('objectives')->nullable();
                $table->json('materials')->nullable();
                $table->json('safety_precautions')->nullable();
                $table->string('image_path')->nullable();
                $table->integer('estimated_duration')->nullable()->comment('Estimated duration in minutes');
                $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
                $table->timestamps();

                $table->index(['information_sheet_id', 'task_number']);
            });
        }

        if (!Schema::hasTable('task_sheet_items')) {
            Schema::create('task_sheet_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_sheet_id')->constrained()->onDelete('cascade');
                $table->string('part_name');
                $table->text('description');
                $table->text('expected_finding');
                $table->string('acceptable_range');
                $table->string('image_path')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();

                $table->index(['task_sheet_id', 'order']);
            });
        }

        if (!Schema::hasTable('task_sheet_submissions')) {
            Schema::create('task_sheet_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_sheet_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->json('findings')->comment('JSON object of user findings for each item');
                $table->text('observations')->nullable();
                $table->text('challenges')->nullable();
                $table->integer('time_taken')->nullable()->comment('Time taken in minutes');
                $table->timestamp('submitted_at');
                $table->timestamps();

                $table->index(['task_sheet_id', 'user_id']);
                $table->index(['user_id', 'submitted_at']);
            });
        }

        // Performance Criteria
        if (!Schema::hasTable('performance_criteria')) {
            Schema::create('performance_criteria', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // task_sheet, job_sheet
                $table->unsignedBigInteger('related_id'); // ID of task_sheet or job_sheet
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->json('criteria')->comment('JSON array of criteria with observed status and remarks');
                $table->decimal('score', 5, 2)->default(0);
                $table->text('evaluator_notes')->nullable();
                $table->foreignId('evaluated_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('completed_at');
                $table->timestamps();

                $table->index(['type', 'related_id']);
                $table->index(['user_id', 'completed_at']);
            });
        }

        // Checklists
        if (!Schema::hasTable('checklists')) {
            Schema::create('checklists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('information_sheet_id')->constrained()->onDelete('cascade');
                $table->string('checklist_number');
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('items')->comment('JSON array of checklist items with ratings and remarks');
                $table->integer('total_score')->default(0);
                $table->integer('max_score')->default(0);
                $table->foreignId('completed_by')->constrained('users')->onDelete('cascade');
                $table->timestamp('completed_at');
                $table->foreignId('evaluated_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('evaluated_at')->nullable();
                $table->text('evaluator_notes')->nullable();
                $table->timestamps();

                $table->index(['information_sheet_id', 'checklist_number']);
                $table->index(['completed_by', 'completed_at']);
            });
        }

        // Job Sheets and related tables
        if (!Schema::hasTable('job_sheets')) {
            Schema::create('job_sheets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('information_sheet_id')->constrained()->onDelete('cascade');
                $table->string('job_number');
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('objectives')->nullable();
                $table->json('tools_required')->nullable();
                $table->json('safety_requirements')->nullable();
                $table->json('reference_materials')->nullable();
                $table->integer('estimated_duration')->nullable()->comment('Estimated duration in minutes');
                $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
                $table->timestamps();

                $table->index(['information_sheet_id', 'job_number']);
            });
        }

        if (!Schema::hasTable('job_sheet_steps')) {
            Schema::create('job_sheet_steps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_sheet_id')->constrained()->onDelete('cascade');
                $table->integer('step_number');
                $table->text('instruction');
                $table->text('expected_outcome');
                $table->string('image_path')->nullable();
                $table->json('warnings')->nullable()->comment('JSON array of safety warnings');
                $table->json('tips')->nullable()->comment('JSON array of helpful tips');
                $table->timestamps();

                $table->index(['job_sheet_id', 'step_number']);
            });
        }

        if (!Schema::hasTable('job_sheet_submissions')) {
            Schema::create('job_sheet_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_sheet_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->json('completed_steps')->comment('JSON array of completed step numbers');
                $table->text('observations');
                $table->text('challenges')->nullable();
                $table->text('solutions')->nullable();
                $table->integer('time_taken')->nullable()->comment('Time taken in minutes');
                $table->timestamp('submitted_at');
                $table->text('evaluator_notes')->nullable();
                $table->foreignId('evaluated_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('evaluated_at')->nullable();
                $table->timestamps();

                $table->index(['job_sheet_id', 'user_id']);
                $table->index(['user_id', 'submitted_at']);
            });
        }

        // Homeworks and related tables
        if (!Schema::hasTable('homeworks')) {
            Schema::create('homeworks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('information_sheet_id')->constrained()->onDelete('cascade');
                $table->string('homework_number');
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('instructions');
                $table->json('requirements')->nullable();
                $table->json('submission_guidelines')->nullable();
                $table->json('reference_images')->nullable();
                $table->timestamp('due_date');
                $table->integer('max_points')->default(100);
                $table->boolean('allow_late_submission')->default(false);
                $table->integer('late_penalty')->default(0)->comment('Penalty percentage per day late');
                $table->timestamps();

                $table->index(['information_sheet_id', 'homework_number']);
                $table->index('due_date');
            });
        }

        if (!Schema::hasTable('homework_submissions')) {
            Schema::create('homework_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('homework_id')->constrained('homeworks')->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('file_path');
                $table->text('description')->nullable();
                $table->decimal('work_hours', 4, 2)->nullable()->comment('Hours spent on the homework');
                $table->timestamp('submitted_at');
                $table->integer('score')->nullable();
                $table->integer('max_points')->default(100);
                $table->text('evaluator_notes')->nullable();
                $table->foreignId('evaluated_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('evaluated_at')->nullable();
                $table->boolean('is_late')->default(false);
                $table->timestamps();

                $table->index(['homework_id', 'user_id']);
                $table->index(['user_id', 'submitted_at']);
            });
        }
    }

    public function down()
    {
        // Drop tables in reverse order to handle foreign key constraints
        Schema::dropIfExists('homework_submissions');
        Schema::dropIfExists('homeworks');
        Schema::dropIfExists('job_sheet_submissions');
        Schema::dropIfExists('job_sheet_steps');
        Schema::dropIfExists('job_sheets');
        Schema::dropIfExists('checklists');
        Schema::dropIfExists('performance_criteria');
        Schema::dropIfExists('task_sheet_submissions');
        Schema::dropIfExists('task_sheet_items');
        Schema::dropIfExists('task_sheets');
        Schema::dropIfExists('self_check_submissions');
        Schema::dropIfExists('self_check_questions');
        Schema::dropIfExists('self_checks');
    }
};