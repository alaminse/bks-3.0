@extends('layouts.backend')

@section('title')
    Assign Tasks - {{ $package->name }}
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.header', [
            'pageTitle' => "Assign tasks to {$package->name} package",
            'backRoute' => route('backend.tasks.assign'),
            'backText' => 'Back to Packages',
            'backPermission' => 'task-assign',
        ])

        <div class="row">
            {{-- Task Selection --}}
            <div class="col-lg-8">
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Available Tasks</h5>
                                <p class="text-muted small mb-0">Select tasks to assign to this package</p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="selectAllTasks()">
                                    <i class="bi bi-check-square"></i> Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="deselectAllTasks()">
                                    <i class="bi bi-square"></i> Deselect All
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="assign-form" action="{{ route('backend.tasks.assign.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">

                            <div style="max-height: 600px; overflow-y: auto; padding-right: 5px;">
                                @foreach ($tasks as $task)
                                    <div
                                        class="mb-2 p-3 border rounded task-item {{ $packageTasks->contains($task->id) ? 'bg-primary bg-opacity-10 border-primary' : '' }}">
                                        <div class="d-flex align-items-start">
                                            <input type="checkbox" name="task_ids[]" value="{{ $task->id }}"
                                                class="form-check-input task-checkbox me-3 mt-1"
                                                id="task_{{ $task->id }}"
                                                {{ $packageTasks->contains($task->id) ? 'checked' : '' }}>
                                            <label class="flex-grow-1 cursor-pointer" for="task_{{ $task->id }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <strong>{{ $task->title }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ Str::limit($task->description, 80) }}
                                                        </small>
                                                    </div>
                                                    <div class="text-end ms-3">
                                                        <span class="badge bg-info bg-opacity-10 text-info">
                                                            {{ ucfirst($task->task_type) }}
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="bi bi-clock"></i> {{ $task->estimated_time }} min
                                                        </small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary"
                                    onclick="confirmFormSubmit('assign-form', {
                                        title: 'Save Task Assignment?',
                                        text: 'Tasks will be assigned to {{ $package->name }}',
                                        confirmButtonText: 'Yes, save it!'
                                    })">
                                    <i class="bi bi-check-circle"></i> Save Task Assignment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar: Package Info & Currently Assigned Tasks --}}
            <div class="col-lg-4">
                {{-- Package Info --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">{{ $package->name }}</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span class="text-muted small">Daily Tasks</span>
                                    <strong>{{ $package->daily_tasks }}</strong>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span class="text-muted small">Daily Earning</span>
                                    <strong class="text-success">$ {{ number_format($package->daily_earning, 2) }}</strong>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span class="text-muted small">Per Task</span>
                                    <strong class="text-info">$
                                        {{ number_format($package->daily_earning / $package->daily_tasks, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Currently Assigned Tasks --}}
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Currently Assigned</h6>
                            <span class="badge bg-success rounded-pill">
                                {{ $packageTasks->count() }} tasks
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($packageTasks->count() > 0)
                            <div style="max-height: 489px; overflow-y: auto;">
                                @foreach ($packageTasks as $task)
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span
                                                        class="badge bg-secondary me-2">#{{ $task->pivot->sort_order }}</span>
                                                    <strong class="text-dark">{{ $task->title }}</strong>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        {{ ucfirst($task->task_type) }}
                                                    </span>
                                                    <span class="badge bg-success fw-semibold" style="font-size: 0.875rem;">
                                                        $ {{ number_format($task->pivot->reward_amount, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ms-2">
                                                <form id="remove-form-{{ $task->id }}"
                                                    action="{{ route('backend.tasks.remove') }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete('remove-form-{{ $task->id }}', '{{ $task->title }}')">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mb-0 mt-3">No tasks assigned yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function selectAllTasks() {
                document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                    checkbox.checked = true;
                    updateTaskItemStyle(checkbox);
                });
            }

            function deselectAllTasks() {
                document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                    updateTaskItemStyle(checkbox);
                });
            }

            function updateTaskItemStyle(checkbox) {
                const taskItem = checkbox.closest('.task-item');
                if (checkbox.checked) {
                    taskItem.classList.add('bg-primary', 'bg-opacity-10', 'border-primary');
                } else {
                    taskItem.classList.remove('bg-primary', 'bg-opacity-10', 'border-primary');
                }
            }

            // Add event listener to all checkboxes to update styling on change
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateTaskItemStyle(this);
                    });
                });
            });
        </script>
    @endpush
@endsection
