<?php

namespace App\Services;

use App\Models\DailyPackageEarning;
use App\Models\Task;
use App\Models\UserPackage;
use App\Models\UserTaskSubmission;
use App\Services\WalletService;

class TaskService
{
    protected $walletService;

    public function __construct(WalletService $walletService = null)
    {
        $this->walletService = $walletService;
    }

    /**
     * Get today's available tasks for user's active packages
     */
    public function getAvailableTasks(int $userId)
    {
        $activePackages = UserPackage::where('user_id', $userId)
            ->where('status', 'active')
            ->with(['package.tasks', 'todayEarning'])
            ->get();

        $availableTasks = [];

        foreach ($activePackages as $userPackage) {
            // Get or create today's earning record
            $todayEarning = $this->getTodayEarning($userPackage->id);

            // Check if limits reached
            if ($todayEarning->hasReachedLimit() || $todayEarning->hasReachedTaskLimit()) {
                continue;
            }

            // Get already submitted tasks today for this package
            $submittedTaskIds = UserTaskSubmission::where('user_package_id', $userPackage->id)
                ->whereDate('submitted_at', today())
                ->pluck('task_id')
                ->toArray();

            // Calculate how many tasks can still be done today for this package
            $remainingTasksAllowed = $todayEarning->remaining_tasks;
            $tasksAddedForThisPackage = 0;

            // Get all available tasks from this package
            $packageAvailableTasks = [];
            foreach ($userPackage->package->tasks as $task) {
                if (! in_array($task->id, $submittedTaskIds) && $task->status === 'active') {
                    $packageAvailableTasks[] = [
                        'task' => $task,
                        'package' => $userPackage->package,
                        'user_package_id' => $userPackage->id,
                        'reward' => $task->pivot->reward_amount,
                        'remaining_tasks' => $todayEarning->remaining_tasks,
                        'remaining_earning' => $todayEarning->remaining_earning,
                        'sort_order' => $task->pivot->sort_order ?? 999,
                    ];
                }
            }

            // Shuffle tasks from this package for random order
            shuffle($packageAvailableTasks);

            // Take only the number of tasks allowed for today
            $limitedTasks = array_slice($packageAvailableTasks, 0, $remainingTasksAllowed);

            // Add to main array
            $availableTasks = array_merge($availableTasks, $limitedTasks);
        }

        // Shuffle all tasks from all packages together for mixed display
        shuffle($availableTasks);

        return $availableTasks;
    }

    /**
     * Get or create today's earning record
     */
    protected function getTodayEarning(int $userPackageId)
    {
        return DailyPackageEarning::firstOrCreate(
            [
                'user_package_id' => $userPackageId,
                'earning_date' => today(),
            ],
            [
                'total_earned' => 0,
                'task_count' => 0,
            ]
        );
    }

    /**
     * Get user's task statistics
     */
    public function getUserStats(int $userId)
    {
        $activePackages = UserPackage::where('user_id', $userId)
            ->where('status', 'active')
            ->get();

        $stats = [
            'total_earned_today' => 0,
            'tasks_completed_today' => 0,
            'available_tasks' => 0,
            'active_packages' => $activePackages->count(),
        ];

        foreach ($activePackages as $package) {
            $todayEarning = $this->getTodayEarning($package->id);
            $stats['total_earned_today'] += $todayEarning->total_earned;
            $stats['tasks_completed_today'] += $todayEarning->task_count;
        }

        $stats['available_tasks'] = count($this->getAvailableTasks($userId));

        return $stats;
    }
}
