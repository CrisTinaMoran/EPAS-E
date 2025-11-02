<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Course;
use App\Models\Module;

return new class extends Migration
{
    public function up()
    {
        // First, create the EPAS course if it doesn't exist
        $course = Course::firstOrCreate(
            ['course_code' => 'EPAS-NCII'],
            [
                'course_name' => 'Electronic Products Assembly and Servicing NCII',
                'description' => 'This course covers the competencies required to assemble and service electronic products according to industry standards.',
                'sector' => 'Electronics',
                'is_active' => true,
                'order' => 1
            ]
        );

        // Update existing modules to belong to this course
        Module::whereNull('course_id')->update(['course_id' => $course->id]);
    }

    public function down()
    {
        // This migration cannot be reversed safely
    }
};