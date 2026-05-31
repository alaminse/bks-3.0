<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add adsterra ad code column
            $table->string('slug')->nullable()->after('title');
            $table->text('adsterra_ad_code')->nullable()->after('task_url');
            // Add skip delay column (seconds before skip button appears)
            $table->integer('ad_skip_delay')->nullable()->default(5)->after('adsterra_ad_code');
        });

        // Modify task_type enum to include 'adsterra'
        // MySQL requires this approach for modifying enums
        DB::statement("ALTER TABLE tasks MODIFY COLUMN task_type ENUM('youtube', 'visit', 'custom', 'adsterra') NOT NULL");
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'adsterra_ad_code')) {
                $table->dropColumn('adsterra_ad_code');
            }
            if (Schema::hasColumn('tasks', 'ad_skip_delay')) {
                $table->dropColumn('ad_skip_delay');
            }
            if (Schema::hasColumn('tasks', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        // Make sure any existing 'adsterra' values are safe
        DB::statement("UPDATE tasks SET task_type='custom' WHERE task_type='adsterra'");

        // Modify ENUM safely
        DB::statement("ALTER TABLE tasks MODIFY COLUMN task_type ENUM('youtube', 'visit', 'custom') NOT NULL");
    }
};
