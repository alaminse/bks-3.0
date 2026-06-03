@extends('layouts.backend')
@section('title') View Contact Message @endsection
@section('content')
    <!-- Header -->
    @include('includes.header', ['pageTitle' => 'Contact Message Details'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Message Details -->
        <div class="col-lg-8">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope-open me-2"></i>Message Details
                    </h5>
                    <div>
                        @if(!$message->replied_at)
                            <a href="{{ route('backend.contacts.reply', $message->id) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-reply"></i> Reply
                            </a>
                        @endif
                        <a href="{{ route('backend.contacts.index') }}"
                           class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted">Message ID</label>
                                <div class="fw-bold">#{{ $message->id }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Name</label>
                                <div class="fw-bold">
                                    {{ $message->name }}
                                    @if($message->user)
                                        <span class="badge bg-success ms-2">
                                            <i class="bi bi-person-check"></i> Registered User
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Email</label>
                                <div>
                                    <a href="mailto:{{ $message->email }}" class="text-primary">
                                        <i class="bi bi-envelope me-1"></i>{{ $message->email }}
                                    </a>
                                    @if($message->user)
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i>
                                            This email matches a registered user in the system
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted">Received At</label>
                                <div>{{ $message->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Status</label>
                                <div>
                                    @if($message->replied_at)
                                        <span class="badge bg-success">
                                            <i class="bi bi-reply-fill"></i> Replied
                                        </span>
                                    @elseif($message->is_read)
                                        <span class="badge bg-info">
                                            <i class="bi bi-envelope-open"></i> Read
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bi bi-envelope"></i> Unread
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($message->replied_at)
                                <div class="mb-3">
                                    <label class="small text-muted">Replied At</label>
                                    <div>{{ $message->replied_at->format('d M Y, h:i A') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="small text-muted mb-2">Message</label>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
                        </div>
                    </div>

                    @if($message->reply_message)
                        <div class="mt-4">
                            <label class="small text-muted mb-2">Your Reply</label>
                            <div class="p-3 bg-success bg-opacity-10 border border-success rounded">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $message->reply_message }}</p>
                                @if($message->replied_by)
                                    <small class="text-muted d-block mt-2">
                                        Replied by: {{ $message->repliedBy->name ?? 'Admin' }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Reply Form (if not replied yet) -->
            @if(!$message->replied_at)
                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-reply me-2"></i>Quick Reply
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('backend.contacts.send-reply', $message->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Reply Message <span class="text-danger">*</span></label>
                                <textarea name="reply_message"
                                          class="form-control @error('reply_message') is-invalid @enderror"
                                          rows="6"
                                          required
                                          placeholder="Type your reply here...">{{ old('reply_message') }}</textarea>
                                @error('reply_message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_email" id="sendEmail" checked>
                                    <label class="form-check-label" for="sendEmail">
                                        Send reply via email to <strong>{{ $message->email }}</strong>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(!$message->replied_at)
                            <a href="{{ route('backend.contacts.reply', $message->id) }}"
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-reply-fill"></i> Reply to Message
                            </a>
                        @endif

                        <a href="mailto:{{ $message->email }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-envelope"></i> Email {{ $message->name }}
                        </a>

                        @if($message->user)
                            <a href="{{ route('backend.users.show', $message->user->id) }}"
                               class="btn btn-outline-info btn-sm">
                                <i class="bi bi-person"></i> View User Profile
                            </a>
                        @endif

                        @if(!$message->is_read)
                            <form action="{{ route('backend.contacts.mark-read', $message->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-info btn-sm w-100">
                                    <i class="bi bi-envelope-open"></i> Mark as Read
                                </button>
                            </form>
                        @endif

                        <hr>

                        <form action="{{ route('backend.contacts.destroy', $message->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this message?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-trash"></i> Delete Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card shadow border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">Message Received</small>
                                <div class="small">{{ $message->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>

                        @if($message->is_read)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">Message Read</small>
                                    <div class="small">{{ $message->read_at ? $message->read_at->format('d M Y, h:i A') : 'Read' }}</div>
                                </div>
                            </div>
                        @endif

                        @if($message->replied_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">Reply Sent</small>
                                    <div class="small">{{ $message->replied_at->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: -26px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 3px solid #fff;
    }
    .timeline-content {
        padding-left: 10px;
    }
</style>
@endpush
