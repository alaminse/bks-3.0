@extends('layouts.backend')

@section('title')
    Create Package
@endsection

@section('content')
    <div class="container-fluid">

        @include('includes.header', [
            'pageTitle' => 'Create New Package',
            'backRoute' => route('backend.packages.index'),
            'backText' => 'Back to Packages',
        ])

        <div class="row">
            <div class="col mx-auto">
                <div class="card shadow border-0">
                    <div class="card-body">

                        <form action="{{ route('backend.packages.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Package Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                                <small class="text-muted">Leave empty to auto-generate from package name</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}"
                                        required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Daily Tasks</label>
                                    <input type="number" name="daily_tasks" class="form-control"
                                        value="{{ old('daily_tasks') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Daily Earning</label>
                                    <input type="number" step="0.01" name="daily_earning" class="form-control"
                                        value="{{ old('daily_earning') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Per Task Earning</label>
                                    <input type="number" step="0.01" name="per_task_earning" class="form-control"
                                        value="{{ old('per_task_earning') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Duration (Days)</label>
                                    <input type="number" name="duration_days" class="form-control"
                                        value="{{ old('duration_days') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control"
                                        value="{{ old('sort_order', 0) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Features</label>
                                <textarea name="features" rows="3" class="form-control" placeholder="Enter features (one per line or comma-separated)">{{ old('features') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Create Package
                                </button>
                                <a href="{{ route('backend.packages.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
