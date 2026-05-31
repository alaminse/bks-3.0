<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Task;
use App\Models\UserTaskSubmission;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->middleware(['auth', 'admin']);
        $this->taskService = $taskService;

        $this->middleware('permission:task-list|task-create|task-edit|task-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:task-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:task-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:task-delete', ['only' => ['destroy']]);
        $this->middleware('permission:task-assign', ['only' => ['assignPage', 'assignToPackage', 'removeFromPackage', 'assignEdit']]);
    }

    // ─── Task CRUD ────────────────────────────────────────────

    public function index()
    {
        $tasks = Task::withCount('packages')->latest()->paginate(15);

        $stats = [
            'total'    => Task::count(),
            'active'   => Task::active()->count(),
            'inactive' => Task::inactive()->count(),
        ];

        return view('backend.tasks.index', compact('tasks', 'stats'));
    }

    public function create()
    {
        return view('backend.tasks.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'             => 'required|string|max:255',
                'description'       => 'nullable|string',
                'task_type'         => 'required|in:youtube,visit,custom,adsterra',
                'task_url'          => 'nullable|url|max:500',
                'adsterra_ad_code'  => 'nullable|string',
                'ad_skip_delay'     => 'nullable|integer|min:1|max:300',
                'required_duration' => 'nullable|integer|min:0',
                'status'            => 'required|in:active,inactive,pending,completed',
                'estimated_time'    => 'nullable|integer|min:0',
            ]);


            $validated['slug'] = Str::slug($validated['title']);
            // Adsterra tasks: task_url not required, but ad code is
            if ($validated['task_type'] === 'adsterra') {
                if (empty($validated['adsterra_ad_code'])) {
                    return back()->withInput()->with('error', 'Ad code is required for Adsterra tasks.');
                }
                $validated['auto_verify'] = 1;
                $validated['task_url']    = $validated['task_url'] ?? null;
            } else {
                if (empty($validated['task_url'])) {
                    return back()->withInput()->with('error', 'Task URL is required for this task type.');
                }
                $validated['auto_verify'] = $request->input('auto_verify', 1);
            }

            $validated['required_duration'] = $validated['required_duration'] ?? 0;
            $validated['ad_skip_delay']     = $validated['ad_skip_delay'] ?? 5;

            Task::create($validated);

            return redirect()->route('backend.tasks.index')->with('success', 'Task created successfully!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Task $task)
    {
        return view('backend.tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        try {
            $validated = $request->validate([
                'title'             => 'required|string|max:255',
                'description'       => 'nullable|string',
                'task_type'         => 'required|in:youtube,visit,custom,adsterra',
                'task_url'          => 'nullable|url|max:500',
                'adsterra_ad_code'  => 'nullable|string',
                'ad_skip_delay'     => 'nullable|integer|min:1|max:300',
                'required_duration' => 'nullable|integer|min:0',
                'estimated_time'    => 'nullable|integer|min:0',
                'status'            => 'required|in:active,inactive,pending,completed',
            ]);

            if ($validated['task_type'] === 'adsterra') {
                if (empty($validated['adsterra_ad_code'])) {
                    return back()->withInput()->with('error', 'Ad code is required for Adsterra tasks.');
                }
            } else {
                if (empty($validated['task_url'])) {
                    return back()->withInput()->with('error', 'Task URL is required for this task type.');
                }
            }

            $validated['required_duration'] = $validated['required_duration'] ?? 0;
            $validated['ad_skip_delay']     = $validated['ad_skip_delay'] ?? 5;

            $task->update($validated);

            return redirect()->route('backend.tasks.index')->with('success', 'Task updated successfully!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Task $task)
    {
        if ($task->packages()->count() > 0) {
            return back()->with('error', 'This task is assigned to a package. Please unassign it first.');
        }

        $task->delete();

        return back()->with('success', 'Task deleted successfully!');
    }

    // ─── Assignment ───────────────────────────────────────────

    public function assignPage()
    {
        $packages = Package::active()->get();
        $tasks    = Task::active()->get();

        $packageTasks = [];
        foreach ($packages as $package) {
            $packageTasks[$package->id] = $package->tasks()
                ->withPivot('reward_amount', 'sort_order')
                ->orderBy('sort_order')
                ->get();
        }

        return view('backend.tasks.assign', compact('packages', 'tasks', 'packageTasks'));
    }

    public function assignToPackage(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'task_ids'   => 'nullable|array',
            'task_ids.*' => 'exists:tasks,id',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        if (empty($validated['task_ids'])) {
            $package->tasks()->detach();
            return back()->with('success', 'All tasks have been removed from the package.');
        }

        if ($package->daily_tasks <= 0) {
            return back()->with('error', 'Daily tasks must be greater than zero.');
        }

        $rewardPerTask = $package->daily_earning / $package->daily_tasks;

        $syncData = [];
        foreach ($validated['task_ids'] as $index => $taskId) {
            $syncData[$taskId] = [
                'reward_amount' => round($rewardPerTask, 2),
                'sort_order'    => $index + 1,
            ];
        }

        $package->tasks()->sync($syncData);

        return back()->with('success', count($validated['task_ids']) . ' tasks assigned successfully!');
    }

    public function removeFromPackage(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'task_id'    => 'required|exists:tasks,id',
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $package->tasks()->detach($validated['task_id']);

        return back()->with('success', 'Task removed!');
    }

    public function assignEdit(Package $package)
    {
        $tasks = Task::active()->get();
        $packageTasks = $package->tasks()
            ->withPivot('reward_amount', 'sort_order')
            ->orderBy('sort_order')
            ->get();

        return view('backend.tasks.assign-edit', compact('package', 'tasks', 'packageTasks'));
    }

    // ─── Submissions ──────────────────────────────────────────

    public function submissions(Request $request)
    {
        $query = UserTaskSubmission::with(['user', 'task', 'userPackage.package'])
            ->latest('submitted_at');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $submissions = $query->paginate(20);

        $stats = [
            'pending_count'  => UserTaskSubmission::pending()->count(),
            'approved_today' => UserTaskSubmission::approved()->today()->count(),
            'rejected_today' => UserTaskSubmission::rejected()->today()->count(),
            'pending_amount' => UserTaskSubmission::pending()->sum('reward_amount'),
        ];

        return view('backend.tasks.submissions', compact('submissions', 'stats'));
    }
}
