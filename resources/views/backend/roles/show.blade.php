@extends('layouts.backend')

@section('title') Show Role @endsection

@section('content')
@include('includes.header', [
    'pageTitle' => 'Role Details',
    'backRoute' => route('backend.roles.index'),
    'backText' => 'Back to Roles',
    'backPermission' => 'role-list'
])

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fa-solid fa-eye"></i> Role Information</h5>
                <div>
                    @can('role-edit')
                        <a href="{{ route('backend.roles.edit', $role->id) }}" class="btn btn-sm btn-light">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Role
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <!-- Role Name Section -->
                <div class="mb-4 pb-4 border-bottom">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-muted mb-0">
                                <i class="fa-solid fa-tag"></i> Role Name
                            </h6>
                        </div>
                        <div class="col-md-9">
                            <h4 class="mb-0 text-dark">{{ $role->name }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-muted mb-0">
                            <i class="fa-solid fa-key"></i> Assigned Permissions
                        </h6>
                        @if(!empty($rolePermissions))
                            <span class="badge bg-primary rounded-pill">
                                {{ count($rolePermissions) }} Permission(s)
                            </span>
                        @endif
                    </div>

                    @if(!empty($rolePermissions))
                        @php
                            $groupedPermissions = [];
                            foreach($rolePermissions as $perm) {
                                $parts = explode('-', $perm->name);
                                $group = ucfirst($parts[0]);
                                if (!isset($groupedPermissions[$group])) {
                                    $groupedPermissions[$group] = [];
                                }
                                $groupedPermissions[$group][] = $perm;
                            }
                        @endphp

                        @foreach($groupedPermissions as $groupName => $permissions)
                            <div class="permission-group mb-3">
                                <div class="group-header mb-2">
                                    <h6 class="text-primary mb-2">
                                        <i class="fa-solid fa-folder-open"></i> {{ $groupName }} Permissions
                                    </h6>
                                </div>
                                <div class="permission-badges">
                                    @foreach($permissions as $permission)
                                        <span class="badge bg-success me-2 mb-2 px-3 py-2">
                                            <i class="fa-solid fa-check-circle"></i> {{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="my-3">
                            @endif
                        @endforeach
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="fa-solid fa-exclamation-triangle"></i> No permissions assigned to this role yet.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fa-solid fa-clock"></i> Created: {{ $role->created_at ? $role->created_at->format('M d, Y') : 'N/A' }}
                    </small>
                    <div>
                        <a href="{{ route('backend.roles.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </a>
                        @can('role-edit')
                            <a href="{{ route('backend.roles.edit', $role->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                        @endcan
                        @can('role-delete')
                            <form method="POST" action="{{ route('backend.roles.destroy', $role->id) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.permission-group {
    background: #f8f9fa;
    padding: 1.25rem;
    border-radius: 0.5rem;
    border-left: 4px solid #0d6efd;
}

.permission-group .group-header h6 {
    font-weight: 600;
    margin-bottom: 0;
}

.permission-badges .badge {
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.permission-badges .badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header h5 {
    font-weight: 600;
}

.border-bottom {
    border-color: #dee2e6 !important;
}
</style>

@endsection
