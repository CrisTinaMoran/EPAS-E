<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check and fix target_roles if needed
        if (Schema::hasColumn('announcements', 'target_roles')) {
            // If it exists but might need fixing, we can modify it
            // But since it exists, we don't need to add it
            echo "target_roles column already exists\n";
        } else {
            Schema::table('announcements', function (Blueprint $table) {
                $table->string('target_roles')->default('all')->after('is_urgent');
            });
        }

        // Add deadline if missing
        if (!Schema::hasColumn('announcements', 'deadline')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->timestamp('deadline')->nullable()->after('publish_at');
            });
            echo "Added deadline column\n";
        } else {
            echo "deadline column already exists\n";
        }
    }

    public function down()
    {
        // Safe rollback - only drop if we added them
        if (Schema::hasColumn('announcements', 'deadline')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->dropColumn('deadline');
            });
        }
    }
};