<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\Package;

class CheckTaskSystemCommand extends Command
{
    protected $signature = 'task:check-system';
    protected $description = 'Check if Task System is properly installed';

    public function handle()
    {
        $this->info('🔍 Checking Task System Installation...');
        $this->newLine();

        // Check 1: Tables
        $this->checkTables();
        $this->newLine();

        // Check 2: Models
        $this->checkModels();
        $this->newLine();

        // Check 3: Routes
        $this->checkRoutes();
        $this->newLine();

        // Check 4: Views
        $this->checkViews();
        $this->newLine();

        $this->info('✅ System check completed!');
    }

    protected function checkTables()
    {
        $this->info('📊 Checking Database Tables:');

        $tables = [
            'tasks',
            'package_tasks',
            'user_task_submissions',
            'daily_package_earnings'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->line("   ✅ {$table} - {$count} records");
            } else {
                $this->error("   ❌ {$table} - NOT FOUND");
            }
        }
    }

    protected function checkModels()
    {
        $this->info('🔧 Checking Models:');

        $models = [
            'App\Models\Task',
            'App\Models\UserTaskSubmission',
            'App\Models\DailyPackageEarning',
            'App\Models\PackageTask'
        ];

        foreach ($models as $model) {
            if (class_exists($model)) {
                $this->line("   ✅ {$model}");
            } else {
                $this->error("   ❌ {$model} - NOT FOUND");
            }
        }
    }

    protected function checkRoutes()
    {
        $this->info('🛣️ Checking Routes:');

        $routes = [
            'tasks.index',
            'tasks.submit',
            'tasks.history',
            'backend.tasks.submissions',
            'backend.tasks.approve',
            'backend.tasks.reject'
        ];

        $allRoutes = collect(\Route::getRoutes())->pluck('action.as')->filter();

        foreach ($routes as $route) {
            if ($allRoutes->contains($route)) {
                $this->line("   ✅ {$route}");
            } else {
                $this->error("   ❌ {$route} - NOT FOUND");
            }
        }
    }

    protected function checkViews()
    {
        $this->info('👀 Checking Views:');

        $views = [
            'backend.tasks.submissions',
            'backend.tasks.submissions'
        ];

        foreach ($views as $view) {
            if (view()->exists($view)) {
                $this->line("   ✅ {$view}");
            } else {
                $this->error("   ❌ {$view} - NOT FOUND");
            }
        }
    }
    
}
