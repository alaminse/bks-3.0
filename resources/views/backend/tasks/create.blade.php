@extends('layouts.backend')

@section('title') Create Task @endsection

@section('content')
<div class="container-fluid">
    @include('includes.header', [
        'pageTitle'  => 'Create New Task',
        'backRoute'  => route('backend.tasks.index'),
        'backText'   => 'Back to Tasks',
        'backPermission' => 'task-list',
    ])

    <div class="row">
        <div class="col mx-auto">
            <div class="card shadow border-0">
                <div class="card-body">

                    <form id="task-create-form"
                          action="{{ route('backend.tasks.store') }}"
                          method="POST">
                        @csrf

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label">Task Title <span class="text-danger">*</span></label>
                            <input type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="e.g., Watch Ad & Earn" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Describe what the user needs to do...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            {{-- Task Type --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Task Type <span class="text-danger">*</span></label>
                                <select name="task_type" id="task_type"
                                        class="form-select @error('task_type') is-invalid @enderror"
                                        required onchange="toggleTaskTypeFields(this.value)">
                                    <option value="">Select Task Type</option>
                                    <option value="youtube"  {{ old('task_type') === 'youtube'  ? 'selected' : '' }}>YouTube</option>
                                    <option value="visit"    {{ old('task_type') === 'visit'    ? 'selected' : '' }}>Social Media / Visit</option>
                                    <option value="custom"   {{ old('task_type') === 'custom'   ? 'selected' : '' }}>Custom</option>
                                    <option value="adsterra" {{ old('task_type') === 'adsterra' ? 'selected' : '' }}>🔥 Adsterra Ad</option>
                                </select>
                                @error('task_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Task URL (hidden for adsterra) --}}
                            <div class="col-md-6 mb-3" id="task-url-field">
                                <label class="form-label">Task URL <span class="text-danger">*</span></label>
                                <input type="url" name="task_url"
                                       class="form-control @error('task_url') is-invalid @enderror"
                                       value="{{ old('task_url') }}"
                                       placeholder="https://example.com">
                                @error('task_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Adsterra Ad Code (shown only for adsterra type) --}}
                        <div class="mb-3 d-none" id="adsterra-fields">
                            <div class="alert alert-warning d-flex align-items-start gap-2 mb-3">
                                <i class="bi bi-megaphone-fill fs-5 mt-1"></i>
                                <div>
                                    <strong>Adsterra Ad Task</strong><br>
                                    <small>Paste your Adsterra ad script/code below. Users will see this ad in a modal overlay. After the skip delay, a "Skip & Claim Reward" button appears. The reward is credited automatically.</small>
                                </div>
                            </div>

                            <label class="form-label">Adsterra Ad Code <span class="text-danger">*</span></label>
                            <textarea name="adsterra_ad_code" id="adsterra_ad_code" rows="6"
                                      class="form-control font-monospace @error('adsterra_ad_code') is-invalid @enderror"
                                      placeholder="Paste your Adsterra &lt;script&gt; or banner code here...">{{ old('adsterra_ad_code') }}</textarea>
                            <small class="text-muted">Paste the full script tag from your Adsterra publisher dashboard.</small>
                            @error('adsterra_ad_code')<div class="invalid-feedback">{{ $message }}</div>@enderror

                            <div class="mt-3">
                                <label class="form-label">Skip Button Delay (seconds) <span class="text-danger">*</span></label>
                                <input type="number" name="ad_skip_delay"
                                       class="form-control @error('ad_skip_delay') is-invalid @enderror"
                                       value="{{ old('ad_skip_delay', 5) }}"
                                       min="1" max="300" style="max-width:200px;">
                                <small class="text-muted">How many seconds before the "Skip & Claim" button appears.</small>
                                @error('ad_skip_delay')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Required Duration --}}
                            <div class="col-md-6 mb-3" id="duration-field">
                                <label class="form-label">Required Duration (seconds)</label>
                                <input type="number" name="required_duration"
                                       class="form-control @error('required_duration') is-invalid @enderror"
                                       value="{{ old('required_duration', 0) }}"
                                       min="0" placeholder="0">
                                <small class="text-muted">Minimum time to complete task</small>
                                @error('required_duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Estimated Time --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estimated Time (minutes)</label>
                                <input type="number" name="estimated_time"
                                       class="form-control @error('estimated_time') is-invalid @enderror"
                                       value="{{ old('estimated_time') }}"
                                       min="0" placeholder="5">
                                <small class="text-muted">Approximate completion time</small>
                                @error('estimated_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active"   {{ old('status', 'active') === 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending"  {{ old('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                                <option value="completed"{{ old('status') === 'completed'? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary"
                                    onclick="confirmFormSubmit('task-create-form', {
                                        title: 'Create Task?',
                                        text: 'Are you sure you want to create this task?',
                                        confirmButtonText: 'Yes, create it!'
                                    })">
                                <i class="bi bi-check-circle"></i> Create Task
                            </button>
                            <a href="{{ route('backend.tasks.index') }}" class="btn btn-secondary">Cancel</a>
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
function toggleTaskTypeFields(type) {
    const urlField      = document.getElementById('task-url-field');
    const adsterraFields = document.getElementById('adsterra-fields');
    const durationField = document.getElementById('duration-field');
    const urlInput      = urlField.querySelector('input');

    if (type === 'adsterra') {
        urlField.classList.add('d-none');
        urlInput.removeAttribute('required');
        adsterraFields.classList.remove('d-none');
        durationField.classList.add('d-none');
    } else {
        urlField.classList.remove('d-none');
        urlInput.setAttribute('required', 'required');
        adsterraFields.classList.add('d-none');
        durationField.classList.remove('d-none');
    }
}

// Init on page load (for old() repopulation)
document.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('task_type');
    if (sel.value) toggleTaskTypeFields(sel.value);
});
</script>
@endpush
