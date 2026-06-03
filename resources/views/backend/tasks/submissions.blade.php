@extends('layouts.backend')

@section('title')
    Task Submissions
@endsection

@section('content')
    @include('includes.header', [
        'pageTitle' => 'Task Submissions',
    ])

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="mb-0">{{ $stats['pending_count'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Approved Today</h6>
                            <h3 class="mb-0">{{ $stats['approved_today'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                                <i class="bi bi-x-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Rejected Today</h6>
                            <h3 class="mb-0">{{ $stats['rejected_today'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-currency-dollar fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pending Amount</h6>
                            <h3 class="mb-0">$ {{ number_format($stats['pending_amount'], 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Filter Section --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.tasks.submissions') }}" class="row g-3">
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by user name or email..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- Submissions Table --}}
    <div class="card shadow border-0 mb-7">
        <div class="card-header">
            <h5 class="mb-0">Task Submissions</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Task</th>
                        <th>Package</th>
                        <th>Reward</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr>
                            <td>{{ $submission->id }}</td>
                            <td>
                                <strong>{{ $submission->user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $submission->user->email }}</small>
                            </td>
                            <td>{{ $submission->task->title }}</td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $submission->userPackage->package->name }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">$ {{ $submission->reward_amount }}</strong>
                            </td>
                            <td>
                                @if ($submission->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                                @elseif($submission->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success">Approved</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $submission->submitted_at->format('d M Y') }}</small>
                                <br>
                                <small class="text-muted">{{ $submission->submitted_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                @if ($submission->status === 'pending')
                                    <form id="approve-form-{{ $submission->id }}"
                                        action="{{ route('backend.tasks.approve', $submission->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-success"
                                            onclick="confirmFormSubmit('approve-form-{{ $submission->id }}', {
                                            title: 'Approve Task?',
                                            text: 'User will receive $ {{ $submission->reward_amount }}',
                                            confirmButtonText: 'Yes, approve it!',
                                            icon: 'success'
                                        })">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $submission->id }}">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>

                                    {{-- Reject Modal --}}
                                    <div class="modal fade" id="rejectModal{{ $submission->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form id="reject-form-{{ $submission->id }}"
                                                    action="{{ route('backend.tasks.reject', $submission->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Task Submission</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Rejection Reason <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea name="rejection_reason" rows="4" class="form-control"
                                                                placeholder="Explain why this task is rejected..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-x-circle"></i> Reject Task
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">Already reviewed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted mb-3">
                                    <i class="bi bi-inbox fs-1"></i>
                                </div>
                                <p class="text-muted">No submissions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 py-3">
            {!! $submissions->links('pagination::bootstrap-5') !!}
        </div>
    </div>
@endsection
