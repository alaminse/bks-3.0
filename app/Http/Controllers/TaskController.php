<?php

namespace App\Http\Controllers;

use App\Models\DailyPackageEarning;
use App\Models\PackageTask;
use App\Models\Task;
use App\Models\UserPackage;
use App\Models\UserTaskSubmission;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->middleware('auth');
        $this->taskService = $taskService;
    }

    /**
     * Show available tasks for user
     */
    public function index()
    {
        $userId = Auth::id();
        $tasks = $this->taskService->getAvailableTasks($userId);
        $stats = $this->taskService->getUserStats($userId);

        return view('frontend.tasks.index', compact('tasks', 'stats'));
    }

    /**
     * My task submissions history
     */
    public function history()
    {
        $submissions = Auth::user()->taskSubmissions()
            ->with(['task', 'userPackage.package'])
            ->latest('submitted_at')
            ->paginate(20);

        return view('frontend.tasks.history', compact('submissions'));
    }

    /**
     * Auto-verify task submission (standard tasks + adsterra ad tasks)
     * FIXED: wallet transaction relationship + proper response
     */
    public function autoVerify(Request $request)
    {
        Log::info('Auto-verify request received', [
            'user_id'      => Auth::id(),
            'request_data' => $request->all(),
        ]);

        try {
            $validated = $request->validate([
                'user_package_id' => 'required|exists:user_packages,id',
                'task_id'         => 'required|exists:tasks,id',
                'duration'        => 'required|integer|min:0',
                'ad_token'        => 'nullable|string',
            ]);

            $userPackage = UserPackage::findOrFail($request->user_package_id);
            $task        = Task::findOrFail($request->task_id);

            if ((int)$userPackage->user_id !== (int)Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            if ($userPackage->status !== 'active') {
                return response()->json(['success' => false, 'message' => 'Your package is not active'], 400);
            }

            // Task type checks
            if ($task->task_type === 'adsterra') {
                $minDuration = (int)$task->effective_skip_delay;
                if ($minDuration > 0 && (int)$request->duration < $minDuration) {
                    return response()->json([
                        'success' => false,
                        'message' => "You must watch the ad for at least {$minDuration} seconds",
                    ], 400);
                }
            } else {
                if (!$task->auto_verify) {
                    return response()->json(['success' => false, 'message' => 'This task does not support auto-verification'], 400);
                }
                $requiredDuration = (int)$task->required_duration;
                if ($requiredDuration > 0 && (int)$request->duration < $requiredDuration) {
                    return response()->json([
                        'success' => false,
                        'message' => "You need to stay for at least {$requiredDuration} seconds",
                    ], 400);
                }
            }

            // Daily limit checks
            $todayTaskCount = UserTaskSubmission::where('user_id', Auth::id())
                ->where('user_package_id', $userPackage->id)
                ->where('status', 'approved')
                ->whereDate('submitted_at', today())
                ->count();

            if ($todayTaskCount >= (int)$userPackage->daily_task_limit) {
                return response()->json(['success' => false, 'message' => 'You have reached your daily task limit'], 400);
            }

            $alreadySubmitted = UserTaskSubmission::where('user_id', Auth::id())
                ->where('task_id', $task->id)
                ->where('user_package_id', $userPackage->id)
                ->whereDate('submitted_at', today())
                ->exists();

            if ($alreadySubmitted) {
                return response()->json(['success' => false, 'message' => 'You have already completed this task today'], 400);
            }

            // Reward from pivot
            $packageTask = PackageTask::where('package_id', $userPackage->package_id)
                ->where('task_id', $task->id)
                ->first();

            if (!$packageTask) {
                return response()->json(['success' => false, 'message' => 'Task not found in your package'], 400);
            }

            $reward = (float)($packageTask->reward_amount ?? 0);

            $todayEarning = UserTaskSubmission::where('user_id', Auth::id())
                ->where('user_package_id', $userPackage->id)
                ->where('status', 'approved')
                ->whereDate('submitted_at', today())
                ->sum('reward_amount');

            if (($todayEarning + $reward) > (float)$userPackage->daily_earning_limit) {
                return response()->json(['success' => false, 'message' => 'Completing this task would exceed your daily earning limit'], 400);
            }

            // All checks passed — now do DB work
            DB::transaction(function () use ($request, $task, $userPackage, $reward, $todayTaskCount, $todayEarning) {

                $proofText = $task->task_type === 'adsterra'
                    ? "Adsterra ad viewed - Duration: {$request->duration}s - Completed at " . now()->format('Y-m-d H:i:s')
                    : "Auto-verified - Duration: {$request->duration}s - Completed at " . now()->format('Y-m-d H:i:s');

                $submission = UserTaskSubmission::create([
                    'user_id'         => Auth::id(),
                    'user_package_id' => $userPackage->id,
                    'task_id'         => $task->id,
                    'proof_text'      => $proofText,
                    'reward_amount'   => $reward,
                    'status'          => 'approved',
                    'submitted_at'    => now(),
                    'approved_at'     => now(),
                    'approved_by'     => null,
                ]);

                Log::info('Submission created', ['id' => $submission->id, 'reward' => $reward]);

                // Direct wallet update — avoids nested transaction issue
                $wallet = \App\Models\Wallet::firstOrCreate(
                    ['user_id' => Auth::id()],
                    ['balance' => 0, 'locked_balance' => 0]
                );
                $wallet->increment('balance', $reward);
                $wallet->refresh();

                \App\Models\WalletTransaction::create([
                    'wallet_id'      => $wallet->id,
                    'type'           => 'task',
                    'amount'         => $reward,
                    'direction'      => 'credit',
                    'reference_type' => get_class($submission),
                    'reference_id'   => $submission->id,
                    'description'    => "Task completed: {$task->title}",
                ]);

                Log::info('Wallet credited', ['user_id' => Auth::id(), 'amount' => $reward, 'balance' => $wallet->balance]);

                $userPackage->increment('completed_tasks');
                $userPackage->increment('total_earning', $reward);

                $dailyEarning = DailyPackageEarning::firstOrCreate(
                    ['user_package_id' => $userPackage->id, 'earning_date' => today()],
                    ['total_earned' => 0, 'task_count' => 0]
                );
                $dailyEarning->increment('total_earned', $reward);
                $dailyEarning->increment('task_count');
            });

            return response()->json([
                'success'               => true,
                'message'               => 'Task completed successfully!',
                'reward'                => number_format($reward, 2),
                'today_tasks_completed' => $todayTaskCount + 1,
                'today_earning'         => number_format((float)$todayEarning + $reward, 2),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Auto-verify failed', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function viewAd($task)
    {
        $task = Task::where('slug', $task)->firstOrFail();
        // Only allow adsterra type tasks
        if ($task->task_type !== 'adsterra') {
            abort(404);
        }

        // Only allow active tasks
        if ($task->status !== 'active') {
            abort(404);
        }

        return view('frontend.tasks.ad_view', compact('task'));
    }
}
