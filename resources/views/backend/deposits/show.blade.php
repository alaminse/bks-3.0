@extends('layouts.backend')
@section('title') Deposit Details @endsection
@section('content')
    <!-- Header -->
    @include('includes.header', ['pageTitle' => 'Deposit Details'])

    <div class="row g-6">
        <!-- Left: Deposit Information -->
        <div class="col-lg-8">
            <div class="card shadow border-0 mb-4">
                <div class="card-header {{ $deposit->status === 'pending' ? 'bg-warning' : ($deposit->status === 'approved' ? 'bg-success' : 'bg-danger') }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Deposit #{{ $deposit->id }}</h5>
                        <span class="badge bg-white text-dark">{{ $deposit->reference_number }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- User Info -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">User Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $deposit->user->name }}</p>
                                <p><strong>Email:</strong> {{ $deposit->user->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>User ID:</strong> #{{ $deposit->user->id }}</p>
                                <p><strong>Phone:</strong> {{ $deposit->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Deposit Details -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Deposit Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted">Amount</small>
                                    <h4 class="mb-0 text-primary">{{ number_format($deposit->amount, 2) }} USDT</h4>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted">Payment Method</small>
                                    <h5 class="mb-0">{{ $deposit->payment_method_name }}</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Transaction ID:</strong><br>
                                <code>{{ $deposit->transaction_id }}</code></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Sender Info:</strong><br>
                                {{ $deposit->sender_info ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Timeline</h6>
                        <div class="timeline">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <span class="badge rounded-circle bg-primary p-2">1</span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <strong>Request Submitted</strong>
                                    <p class="mb-0 text-muted">{{ $deposit->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                            @if($deposit->status !== 'pending')
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="badge rounded-circle {{ $deposit->status === 'approved' ? 'bg-success' : 'bg-danger' }} p-2">2</span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <strong>{{ ucfirst($deposit->status) }}</strong>
                                    <p class="mb-0 text-muted">
                                        {{ $deposit->approved_at->format('d M Y, h:i A') }}
                                        @if($deposit->approver)
                                            by {{ $deposit->approver->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Rejection Reason -->
                    @if($deposit->status === 'rejected' && $deposit->reject_reason)
                    <div class="alert alert-danger">
                        <strong><i class="bi bi-exclamation-triangle me-2"></i>Rejection Reason:</strong><br>
                        {{ $deposit->reject_reason }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Payment Proof & Actions -->
        <div class="col-lg-4">
            <!-- Payment Proof -->
            @if($deposit->payment_proof)
            <div class="card shadow border-0 mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Payment Proof</h6>
                </div>
                <div class="card-body p-0">
                    <img src="{{ asset('storage/'.$deposit->payment_proof) }}"
                        class="img-fluid w-100"
                        alt="Payment Proof"
                        onclick="window.open(this.src, '_blank')">
                </div>
                <div class="card-footer text-center">
                    <a href="{{ asset('storage/'.$deposit->payment_proof) }}"
                        target="_blank"
                        class="btn btn-sm btn-primary">
                        <i class="bi bi-zoom-in"></i> View Full Size
                    </a>
                </div>
            </div>
            @endif

            <!-- Actions -->
            @if($deposit->status === 'pending')
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <!-- Approve -->
                    <form action="{{ route('backend.deposits.approve', $deposit->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100"
                            onclick="return confirm('Approve this deposit of {{ $deposit->amount }} USDT?')">
                            <i class="bi bi-check-circle me-2"></i>Approve Deposit
                        </button>
                    </form>

                    <!-- Reject -->
                    <button type="button" class="btn btn-danger w-100"
                        data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle me-2"></i>Reject Deposit
                    </button>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h6 class="modal-title">Reject Deposit</h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('backend.deposits.reject', $deposit->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <strong>Warning:</strong> This action cannot be undone.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                    <textarea name="reject_reason" class="form-control" rows="4" required
                                        placeholder="Provide a clear reason..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Reject Deposit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <div class="card shadow border-0">
                <div class="card-header">
                    <h6 class="mb-0">Status</h6>
                </div>
                <div class="card-body text-center">
                    @if($deposit->status === 'approved')
                        <i class="bi bi-check-circle text-success display-1"></i>
                        <h5 class="text-success mt-2">Approved</h5>
                    @else
                        <i class="bi bi-x-circle text-danger display-1"></i>
                        <h5 class="text-danger mt-2">Rejected</h5>
                    @endif
                    <p class="text-muted small mb-0">
                        {{ $deposit->approved_at->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Back Button -->
            <a href="{{ route('backend.deposits.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

@endsection
