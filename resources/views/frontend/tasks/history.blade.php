@extends('layouts.app')
@section('title') Task History @endsection
@section('content')
    <!-- Header -->
    @include('includes.header', [
        'pageTitle' => 'My Task History',
        'backRoute' => route('tasks.index'),
        'backText' => 'Back to Available Tasks',
        'backPermission' => 'user-list'
        ])

    <!-- Task History Table -->
    <div class="card shadow border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>My Task Submissions</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Task</th>
                        <th>Package</th>
                        <th>Reward</th>
                        <th>Proof</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                    <tr>
                        <td>
                            {{ $submission->submitted_at->format('d M Y') }}<br>
                            <small class="text-muted">{{ $submission->submitted_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <strong>{{ $submission->task->title }}</strong><br>
                            <span class="badge bg-{{ $submission->task->type_badge_color }} small">
                                <i class="{{ $submission->task->type_icon }}"></i>
                                {{ ucfirst($submission->task->task_type) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $submission->userPackage->package->name }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-success">{{ $submission->formatted_reward }}</strong>
                        </td>
                        <td>
                            @if($submission->hasProof())
                                <a href="{{ $submission->proof_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-image"></i> View
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif

                            @if($submission->proof_text)
                                <button class="btn btn-sm btn-link p-0" data-bs-toggle="tooltip"
                                    title="{{ $submission->proof_text }}">
                                    <i class="bi bi-chat-text"></i>
                                </button>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $submission->status_badge }}">
                                <i class="{{ $submission->status_icon }}"></i>
                                {{ ucfirst($submission->status) }}
                            </span>
                            <br>
                            <small class="text-muted">{{ $submission->time_elapsed }}</small>
                        </td>
                        <td>
                            @if($submission->isApproved())
                                <small class="text-success">
                                    <i class="bi bi-check-circle"></i> Credited to wallet
                                </small>
                            @elseif($submission->isRejected())
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#reasonModal{{ $submission->id }}">
                                    <i class="bi bi-info-circle"></i> View Reason
                                </button>

                                <!-- Rejection Reason Modal -->
                                <div class="modal fade" id="reasonModal{{ $submission->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h6 class="modal-title">Rejection Reason</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Task:</strong> {{ $submission->task->title }}</p>
                                                <p><strong>Submitted:</strong> {{ $submission->submitted_at->format('d M Y h:i A') }}</p>
                                                <hr>
                                                <p class="mb-0">{{ $submission->rejection_reason }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> Under review
                                </small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                            <h5 class="text-muted mb-3">No Task History</h5>
                            <p class="text-muted mb-4">You haven't submitted any tasks yet.</p>
                            <a href="{{ route('tasks.index') }}" class="btn btn-primary">
                                <i class="bi bi-list-check me-2"></i>View Available Tasks
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($submissions->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $submissions->firstItem() }} to {{ $submissions->lastItem() }} of {{ $submissions->total() }}
                </div>
                {{ $submissions->links() }}
            </div>
        </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="row g-4 mt-4">
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history text-warning display-6"></i>
                    <h4 class="mt-2">{{ $submissions->where('status', 'pending')->count() }}</h4>
                    <small class="text-muted">Pending Review</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle text-success display-6"></i>
                    <h4 class="mt-2">{{ $submissions->where('status', 'approved')->count() }}</h4>
                    <small class="text-muted">Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle text-danger display-6"></i>
                    <h4 class="mt-2">{{ $submissions->where('status', 'rejected')->count() }}</h4>
                    <small class="text-muted">Rejected</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-cash-coin text-primary display-6"></i>
                    <h4 class="mt-2">${{ number_format($submissions->where('status', 'approved')->sum('reward_amount'), 2) }}</h4>
                    <small class="text-muted">Total Earned</small>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush
