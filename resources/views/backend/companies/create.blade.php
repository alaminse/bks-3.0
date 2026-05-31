@extends('layouts.backend')
@section('title') Add Company @endsection
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add New Company</h2>
        <a class="btn btn-secondary" href="{{ route('backend.companies.index') }}">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body">
            <form action="{{ route('backend.companies.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="total_value" class="form-label">Total Company Value ($) <span class="text-danger">*</span></label>
                        <input type="number" name="total_value" class="form-control" id="total_value" value="{{ old('total_value') }}" step="0.01" min="0" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="share_price" class="form-label">Price Per Share ($) <span class="text-danger">*</span></label>
                        <input type="number" name="share_price" class="form-control" id="share_price" value="{{ old('share_price') }}" step="0.01" min="0" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="logo" class="form-label">Company Logo</label>
                        <input type="file" name="logo" class="form-control" id="logo" accept="image/*">
                        <small class="text-muted">Allowed: JPG, JPEG, PNG, GIF (Max: 2MB)</small>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Create Company
                    </button>
                    <a href="{{ route('backend.companies.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
