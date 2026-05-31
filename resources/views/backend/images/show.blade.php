@extends('layouts.backend')

@section('title') View Featured Image @endsection

@section('content')
<div class="container-fluid">
    @include('includes.header', [
        'pageTitle' => 'Featured Image Details',
        'backRoute' => route('backend.images.index'),
        'backText' => 'Back to Images',
    ])

    <div class="row">
        <div class="col-lg-8 mx-auto">

            {{-- Image Details Card --}}
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-image me-2"></i>{{ $image->title }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('backend.images.edit', $image) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pen"></i> Edit
                        </a>
                        <form id="delete-form-{{ $image->id }}"
                              action="{{ route('backend.images.destroy', $image) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="confirmDelete('delete-form-{{ $image->id }}', '{{ $image->title }}')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Featured Image --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Image Preview</label>
                        <div class="text-center">
                            <img src="{{ asset($image->image_path) }}"
                                 alt="{{ $image->title }}"
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 400px;">
                        </div>
                    </div>

                    <hr>

                    {{-- Image Information --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Title</label>
                            <p class="mb-0">{{ $image->title }}</p>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">Status</label>
                            <p class="mb-0">
                                @if($image->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">Display Order</label>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $image->order }}</span>
                            </p>
                        </div>

                        @if($image->description)
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small">Description</label>
                            <p class="mb-0">{{ $image->description }}</p>
                        </div>
                        @endif

                        @if($image->link_url)
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small">Link URL</label>
                            <p class="mb-0">
                                <a href="{{ $image->link_url }}" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-link-45deg"></i> {{ $image->link_url }}
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </p>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Image Path</label>
                            <p class="mb-0">
                                <code>{{ $image->image_path }}</code>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Image Size</label>
                            <p class="mb-0">
                                @php
                                    $filePath = public_path($image->image_path);
                                    if (file_exists($filePath)) {
                                        $fileSize = filesize($filePath);
                                        echo number_format($fileSize / 1024, 2) . ' KB';
                                    } else {
                                        echo 'File not found';
                                    }
                                @endphp
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Created At</label>
                            <p class="mb-0">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $image->created_at->format('M d, Y h:i A') }}
                                <small class="text-muted">({{ $image->created_at->diffForHumans() }})</small>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Last Updated</label>
                            <p class="mb-0">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ $image->updated_at->format('M d, Y h:i A') }}
                                <small class="text-muted">({{ $image->updated_at->diffForHumans() }})</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card shadow border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('backend.images.edit', $image) }}" class="btn btn-primary">
                            <i class="bi bi-pen"></i> Edit Image
                        </a>

                        @if($image->status === 'inactive')
                            <form action="{{ route('backend.images.update', $image) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $image->title }}">
                                <input type="hidden" name="order" value="{{ $image->order }}">
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Activate
                                </button>
                            </form>
                        @else
                            <form action="{{ route('backend.images.update', $image) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $image->title }}">
                                <input type="hidden" name="order" value="{{ $image->order }}">
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-x-circle"></i> Deactivate
                                </button>
                            </form>
                        @endif

                        <a href="{{ asset($image->image_path) }}" download class="btn btn-info">
                            <i class="bi bi-download"></i> Download Image
                        </a>

                        <button type="button" class="btn btn-secondary" onclick="copyToClipboard('{{ asset($image->image_path) }}')">
                            <i class="bi bi-clipboard"></i> Copy Image URL
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Image URL copied to clipboard!');
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endpush
