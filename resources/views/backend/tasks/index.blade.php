@extends('layouts.backend')

@section('title') Task Management @endsection

@section('content')
@include('includes.header', [
    'pageTitle' => 'Task Management',
    'createRoute' => route('backend.tasks.create'),
    'createText' => 'Create New Task',
    'createPermission' => 'task-create'
])

{{-- Statistics Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="bi bi-list-task fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Tasks</h6>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Active Tasks</h6>
                        <h3 class="mb-0">{{ $stats['active'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-3 p-3">
                            <i class="bi bi-x-circle fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Inactive Tasks</h6>
                        <h3 class="mb-0">{{ $stats['inactive'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tasks Table --}}
<div class="card shadow border-0 mb-7">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Tasks</h5>
        <a href="{{ route('backend.tasks.assign') }}" class="btn btn-sm btn-info">
            <i class="bi bi-link-45deg"></i> Assign to Packages
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-nowrap">
            <thead class="thead-light">
                <tr>
                    <th width="80px">ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Time</th>
                    <th>Packages</th>
                    <th>Status</th>
                    <th width="200px">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>
                        <strong>{{ $task->title }}</strong>
                        <br>
                        <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                    </td>
                    <td>
                        @if($task->task_url)
                            <a href="{{ $task->task_url }}" target="_blank" class="text-decoration-none">
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ ucfirst($task->task_type) }}
                                </span>
                            </a>
                        @else
                            <span class="badge bg-secondary">No Video</span>
                        @endif
                    </td>

                    <td>{{ $task->estimated_time }} min</td>
                    <td>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                            {{ $task->packages_count }} packages
                        </span>
                    </td>
                    <td>
                        @if($task->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('backend.tasks.edit', $task) }}"
                           class="btn btn-sm btn-neutral"
                           title="Edit">
                            <i class="bi bi-pen"></i>
                        </a>

                        <form id="delete-form-{{ $task->id }}"
                              action="{{ route('backend.tasks.destroy', $task) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-neutral text-danger"
                                    onclick="confirmDelete('delete-form-{{ $task->id }}', '{{ $task->title }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted mb-3">
                            <i class="bi bi-inbox fs-1"></i>
                        </div>
                        <p class="text-muted mb-3">No tasks found</p>
                        <a href="{{ route('backend.tasks.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Create First Task
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer border-0 py-3">
        {!! $tasks->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection
