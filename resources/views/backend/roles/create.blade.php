@extends('layouts.backend')

@section('title') Create New Role @endsection

@section('content')
@include('includes.header', [
    'pageTitle' => 'Create New Role',
    'backRoute' => route('backend.roles.index'),
    'backText' => 'Back to Roles',
    'backPermission' => 'role-list'
])

<div class="row justify-content-center">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa-solid fa-shield-halved"></i> Role Information</h5>
            </div>
            <div class="card-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa-solid fa-triangle-exclamation"></i> Whoops!</strong> There were some problems with your input.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('backend.roles.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="role-name" class="form-label fw-semibold">
                            <i class="fa-solid fa-tag"></i> Role Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="role-name"
                            placeholder="Enter role name (e.g., Admin, Manager, Editor)"
                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Choose a descriptive name for this role.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-3">
                            <i class="fa-solid fa-key"></i> Permissions <span class="text-danger">*</span>
                        </label>

                        <div class="card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <span class="text-muted">Select permissions for this role</span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="select-all">
                                        <i class="fa-solid fa-check-double"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all">
                                        <i class="fa-solid fa-xmark"></i> Deselect All
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @php
                                    $groupedPermissions = [];
                                    foreach($permission as $perm) {
                                        $parts = explode('-', $perm->name);
                                        $group = ucfirst($parts[0]);
                                        if (!isset($groupedPermissions[$group])) {
                                            $groupedPermissions[$group] = [];
                                        }
                                        $groupedPermissions[$group][] = $perm;
                                    }
                                @endphp

                                @foreach($groupedPermissions as $groupName => $permissions)
                                    <div class="permission-group mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <h6 class="mb-0 text-primary">
                                                <i class="fa-solid fa-folder"></i> {{ $groupName }} Permissions
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-link ms-2 select-group" data-group="{{ $groupName }}">
                                                Select All
                                            </button>
                                        </div>
                                        <div class="row g-3">
                                            @foreach($permissions as $value)
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="form-check p-3 border rounded hover-shadow">
                                                        <input
                                                            class="form-check-input permission-checkbox group-{{ Str::slug($groupName) }}"
                                                            type="checkbox"
                                                            name="permission[{{$value->id}}]"
                                                            value="{{$value->id}}"
                                                            id="permission-{{$value->id}}"
                                                            {{ in_array($value->id, old('permission', [])) ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label w-100 cursor-pointer" for="permission-{{$value->id}}">
                                                            <i class="fa-solid fa-lock"></i> {{ $value->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <hr class="my-4">
                                    @endif
                                @endforeach

                                @error('permission')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <small class="form-text text-muted mt-2 d-block">
                            <i class="fa-solid fa-circle-info"></i> Select the permissions that users with this role should have.
                        </small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('backend.roles.index') }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fa-solid fa-floppy-disk"></i> Create Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    background-color: #f8f9fa;
}

.cursor-pointer {
    cursor: pointer;
}

.form-check-input:checked ~ .form-check-label {
    color: #0d6efd;
    font-weight: 500;
}

.permission-group {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
}

.permission-group h6 {
    font-weight: 600;
}

.select-group {
    font-size: 0.875rem;
    text-decoration: none;
    padding: 0.25rem 0.5rem;
}

.select-group:hover {
    text-decoration: underline;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('select-all');
    const deselectAllBtn = document.getElementById('deselect-all');
    const checkboxes = document.querySelectorAll('.permission-checkbox');

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(checkbox => checkbox.checked = true);
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(checkbox => checkbox.checked = false);
        });
    }

    // Group select functionality
    const groupSelectBtns = document.querySelectorAll('.select-group');
    groupSelectBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const groupName = this.getAttribute('data-group');
            const slugifiedGroup = groupName.toLowerCase().replace(/\s+/g, '-');
            const groupCheckboxes = document.querySelectorAll('.group-' + slugifiedGroup);

            const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);

            groupCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });

            this.textContent = allChecked ? 'Select All' : 'Deselect All';
        });
    });
});
</script>

@endsection
