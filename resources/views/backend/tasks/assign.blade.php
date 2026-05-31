@extends('layouts.backend')

@section('title') Assign Tasks @endsection

@section('content')
<div class="container-fluid">
    @include('includes.header', [
        'pageTitle'      => 'Assign Tasks to Packages',
        'backRoute'      => route('backend.tasks.index'),
        'backText'       => 'Back to Tasks',
        'backPermission' => 'task-list',
    ])

    <div class="card shadow border-0 mb-7">
        <div class="card-header">
            <h5 class="mb-0">Select a Package to Assign Tasks</h5>
            <p class="text-muted small mb-0">Click on a package to manage its tasks</p>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th width="80px">ID</th>
                        <th>Package Name</th>
                        <th>Daily Tasks</th>
                        <th>Daily Earning</th>
                        <th>Per Task Reward</th>
                        <th>Assigned Tasks</th>
                        <th>Ad Tasks</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                    <tr>
                        <td>{{ $package->id }}</td>
                        <td><strong>{{ $package->name }}</strong></td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $package->daily_tasks }} tasks
                            </span>
                        </td>
                        <td><strong class="text-success">$ {{ $package->daily_earning }}</strong></td>
                        <td>
                            <strong class="text-info">
                                $ {{ number_format($package->daily_earning / max($package->daily_tasks, 1), 2) }}
                            </strong>
                        </td>
                        <td>
                            @if($packageTasks[$package->id]->count() > 0)
                                <span class="badge bg-success">
                                    {{ $packageTasks[$package->id]->count() }} Assigned
                                </span>
                            @else
                                <span class="badge bg-secondary">Not Assigned</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $adCount = $packageTasks[$package->id]->where('task_type', 'adsterra')->count();
                            @endphp
                            @if($adCount > 0)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-megaphone-fill me-1"></i>{{ $adCount }} Ad{{ $adCount > 1 ? 's' : '' }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('backend.tasks.assign.edit', $package->id) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-gear"></i> Manage Tasks
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="bi bi-inbox fs-1"></i>
                            </div>
                            <p class="text-muted">No packages found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
