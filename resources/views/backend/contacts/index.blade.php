@extends('layouts.backend')
@section('title') Manage Contact Messages @endsection
@section('content')
    <!-- Header -->
    @include('includes.header', ['pageTitle' => 'Contact Messages Management'])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-4 mb-6">
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow border-0 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-primary text-white rounded-circle me-3">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <small class="text-muted">Total Messages</small>
                            <h3 class="mb-0">{{ $stats['total_messages'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card shadow border-0 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-warning text-white rounded-circle me-3">
                            <i class="bi bi-envelope-open"></i>
                        </div>
                        <div>
                            <small class="text-muted">Unread Messages</small>
                            <h3 class="mb-0">{{ $stats['unread_messages'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card shadow border-0 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-success text-white rounded-circle me-3">
                            <i class="bi bi-reply-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted">Replied Messages</small>
                            <h3 class="mb-0">{{ $stats['replied_messages'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card shadow border-0 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-info text-white rounded-circle me-3">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <small class="text-muted">Today's Messages</small>
                            <h3 class="mb-0">{{ $stats['today_messages'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.contacts.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                        <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small">From Date</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small">To Date</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-5">
                    <label class="form-label small">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Name, Email, Message..." value="{{ request('search') }}">
                </div>

                <div class="col-md-1">
                    <label class="form-label small d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contact Messages Table -->
    <div class="card shadow border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Contact Messages</h5>
            <div>
                <button class="btn btn-sm btn-success" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr class="{{ $message->is_read ? '' : 'table-warning' }}">
                        <td><strong>#{{ $message->id }}</strong></td>
                        <td>
                            <div>
                                <strong>{{ $message->name }}</strong>
                                @if(!$message->is_read)
                                    <span class="badge bg-warning ms-1">New</span>
                                @endif
                                @if($message->user)
                                    <br><small class="text-success"><i class="bi bi-person-check"></i> Registered User</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $message->email }}</small>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 300px;">
                                {{ $message->message }}
                            </div>
                        </td>
                        <td>
                            <div>{{ $message->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $message->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a class="btn-sm btn btn-neutral"
                                   href="{{ route('backend.contacts.show', $message->id) }}"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if(!$message->replied_at)
                                    <a class="btn-sm btn btn-primary"
                                       href="{{ route('backend.contacts.reply', $message->id) }}"
                                       title="Reply">
                                        <i class="bi bi-reply"></i>
                                    </a>
                                @endif

                                <form id="delete-message-form-{{ $message->id }}"
                                    action="{{ route('backend.contacts.destroy', $message->id) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            title="Delete"
                                            onclick="confirmDelete('delete-message-form-{{ $message->id }}', '{{ $message->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                            <p class="text-muted">No contact messages found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $messages->firstItem() }} to {{ $messages->lastItem() }} of {{ $messages->total() }}
                </div>
                {{ $messages->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
    // Auto refresh every 60 seconds for new messages
    @if(isset($stats['unread_messages']) && $stats['unread_messages'] > 0)
        setTimeout(function() {
            location.reload();
        }, 60000); // 60 seconds
    @endif
</script>
@endpush
