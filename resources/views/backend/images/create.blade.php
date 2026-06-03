@extends('layouts.backend')

@section('title') create Featured Image @endsection

@section('content')
<div class="container-fluid">
    @include('includes.header', [
        'pageTitle' => 'create Featured Image',
        'backRoute' => route('backend.images.index'),
        'backText' => 'Back to Images',
    ])

    <div class="row">
        <div class="col-lg-8 mx-auto">

            <div class="card shadow border-0">
                <div class="card-body">

                    <form id="image-edit-form"
                          action="{{ route('backend.images.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        {{-- Upload New Image --}}
                        <div class="mb-4">
                            <label class="form-label">Upload New Image <small class="text-muted">(Optional - Leave empty to keep current)</small></label>
                            <input type="file"
                                   name="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   id="imageInput">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Recommended size: 1200x400px. Max: 2MB</small>

                            {{-- New Image Preview --}}
                            <div class="mt-3" id="imagePreview" style="display: none;">
                                <label class="form-label">New Image Preview</label>
                                <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="e.g., Special Offer Banner"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label">Description <small class="text-muted">(Optional)</small></label>
                            <textarea name="description"
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Brief description about this banner...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Link URL --}}
                        <div class="mb-3">
                            <label class="form-label">Link URL <small class="text-muted">(Optional)</small></label>
                            <input type="url"
                                   name="link_url"
                                   class="form-control @error('link_url') is-invalid @enderror"
                                   value="{{ old('link_url') }}"
                                   placeholder="https://example.com/offer">
                            @error('link_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Users will be redirected to this URL when clicking the banner</small>
                        </div>

                        <div class="row">
                            {{-- Order --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Display Order <span class="text-danger">*</span></label>
                                <input type="number"
                                       name="order"
                                       class="form-control @error('order') is-invalid @enderror"
                                       value="{{ old('order') }}"
                                       min="0"
                                       required>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Lower numbers appear first in the slider</small>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2 pt-3">
                            <button type="button"
                                    class="btn btn-primary"
                                    onclick="confirmFormSubmit('image-edit-form', {
                                        title: 'Update Image?',
                                        text: 'Are you sure you want to update this featured image?',
                                        confirmButtonText: 'Yes, update it!'
                                    })">
                                <i class="bi bi-check-circle"></i> Update Image
                            </button>
                            <a href="{{ route('backend.images.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Image Preview for new upload
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').style.display = 'none';
        }
    });
</script>
@endpush
