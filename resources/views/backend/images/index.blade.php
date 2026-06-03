@extends('layouts.backend')

@section('title') Featured Images Management @endsection

@section('content')
@include('includes.header', [
    'pageTitle' => 'Featured Images Management',
    'createRoute' => route('backend.images.create'),
    'createText' => 'Upload New Image',
    'createPermission' => 'featured-create'
])

{{-- Statistics Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="bi bi-image fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Images</h6>
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
                        <h6 class="text-muted mb-1">Active Images</h6>
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
                        <h6 class="text-muted mb-1">Inactive Images</h6>
                        <h3 class="mb-0">{{ $stats['inactive'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Images Grid --}}
<div class="card shadow border-0 mb-7">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Featured Images</h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href=this.value">
                <option value="{{ route('backend.images.index') }}">All Status</option>
                <option value="{{ route('backend.images.index', ['status' => 'active']) }}"
                    {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="{{ route('backend.images.index', ['status' => 'inactive']) }}"
                    {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            @forelse($images as $image)
            <div class="col-md-4 col-lg-3">
                <div class="card border h-100">
                    <div class="position-relative">
                        <img src="{{ asset($image->image_path) }}"
                             class="card-img-top"
                             alt="{{ $image->title }}"
                             style="height: 200px; object-fit: cover;">

                        {{-- Status Badge --}}
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($image->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>

                        {{-- Order Badge --}}
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-primary">Order: {{ $image->order }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <h6 class="card-title mb-2">{{ $image->title }}</h6>
                        @if($image->description)
                            <p class="card-text text-muted small mb-2">
                                {{ Str::limit($image->description, 60) }}
                            </p>
                        @endif
                        @if($image->link_url)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-link-45deg"></i>
                                    <a href="{{ $image->link_url }}" target="_blank" class="text-decoration-none">
                                        Link
                                    </a>
                                </small>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white border-top d-flex justify-content-between">
                        <a href="{{ route('backend.images.edit', $image) }}"
                           class="btn btn-sm btn-outline-primary"
                           title="Edit">
                            <i class="bi bi-pen"></i> Edit
                        </a>

                        <form id="delete-form-{{ $image->id }}"
                              action="{{ route('backend.images.destroy', $image) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('delete-form-{{ $image->id }}', '{{ $image->title }}')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="text-muted mb-3">
                        <i class="bi bi-image fs-1"></i>
                    </div>
                    <p class="text-muted mb-3">No images found</p>
                    <a href="{{ route('backend.images.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Upload First Image
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer border-0 py-3">
        {!! $images->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection
