<?php
// [file name]: 2024_01_15_000000_create_modules_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('sector')->default('Electronics');
            $table->string('qualification_title');
            $table->string('unit_of_competency');
            $table->string('module_title');
            $table->string('module_number'); // e.g., "Module 1"
            $table->string('module_name'); // e.g., "Competency based learning material"
            $table->text('table_of_contents')->nullable();
            $table->text('how_to_use_cblm')->nullable();
            $table->text('introduction')->nullable();
            $table->text('learning_outcomes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('information_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('sheet_number'); // e.g., "1.1", "1.2"
            $table->string('title');
            $table->text('content');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('self_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('information_sheet_id')->constrained()->onDelete('cascade');
            $table->string('check_number'); // e.g., "1.1.1", "1.2.1"
            $table->text('content');
            $table->text('answer_key')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('task_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('information_sheet_id')->constrained()->onDelete('cascade');
            $table->string('sheet_number'); // e.g., "1.2.1", "1.3.1"
            $table->string('title');
            $table->text('objective');
            $table->text('instructions');
            $table->text('materials_needed')->nullable();
            $table->text('performance_criteria')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('job_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('information_sheet_id')->constrained()->onDelete('cascade');
            $table->string('sheet_number'); // e.g., "1.6.1", "1.6.2"
            $table->string('title');
            $table->text('objective');
            $table->text('procedures');
            $table->text('safety_precautions')->nullable();
            $table->text('performance_criteria')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_sheets');
        Schema::dropIfExists('task_sheets');
        Schema::dropIfExists('self_checks');
        Schema::dropIfExists('information_sheets');
        Schema::dropIfExists('modules');
    }
};