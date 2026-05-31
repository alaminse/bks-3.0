@extends('layouts.backend')
@section('title')
    Withdrawal Details
@endsection
@section('content')
    <!-- Header -->
    @include('includes.header', ['pageTitle' => 'Withdrawal Details'])

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-6">
        <!-- Left: Withdrawal Information -->
        <div class="col-lg-8">
            <!-- Main Details Card -->
            <div class="card shadow border-0 mb-4">
                <div
                    class="card-header {{ $withdrawal->status === 'pending' ? 'bg-warning' : ($withdrawal->status === 'approved' ? 'bg-success' : 'bg-danger') }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-arrow-up-circle me-2"></i>Withdrawal #{{ $withdrawal->id }}
                        </h5>
                        <span class="badge bg-white text-dark">{{ $withdrawal->reference_number }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- User Information -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-person-circle me-2"></i>User Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted">Name</small>
                                    <p class="mb-0"><strong>{{ $withdrawal->user->name }}</strong></p>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Email</small>
                                    <p class="mb-0">{{ $withdrawal->user->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted">User ID</small>
                                    <p class="mb-0">#{{ $withdrawal->user->id }}</p>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Phone</small>
                                    <p class="mb-0">{{ $withdrawal->user->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Withdrawal Details -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-cash-coin me-2"></i>Withdrawal Details
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 bg-light rounded text-center">
                                    <small class="text-muted d-block">Withdrawal Amount</small>
                                    <h3 class="mb-0 text-danger">{{ number_format($withdrawal->amount, 2) }} USDT</h3>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 bg-light rounded text-center">
                                    <small class="text-muted d-block">Payment Method</small>
                                    <h5 class="mb-0">
                                        @if ($withdrawal->payment_method === 'binance_pay')
                                            <span class="badge bg-success">
                                                <i class="bi bi-lightning-charge-fill"></i> Binance Pay
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="bi bi-people-fill"></i> Binance P2P
                                            </span>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Details -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-credit-card me-2"></i>Recipient Account Details
                        </h6>
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <small class="text-muted d-block mb-1">Account Holder Name</small>
                                        <strong>{{ $withdrawal->account_name }}</strong>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <small class="text-muted d-block mb-1">Account Number / Email / Phone</small>
                                        <code class="fs-6">{{ $withdrawal->account_number }}</code>
                                        <button class="btn btn-sm btn-link p-0 ms-2" onclick="
                                            navigator.clipboard.writeText('{{ $withdrawal->account_number }}');
                                            this.innerHTML = '<i class=\'bi bi-check\'></i> Copied!';
                                            setTimeout(() => this.innerHTML = '<i class=\'bi bi-clipboard\'></i> Copy', 2000);
                                        ">
                                            <i class="bi bi-clipboard"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    @if ($withdrawal->status !== 'pending')
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-receipt me-2"></i>Transaction Details
                            </h6>
                            <div class="row">
                                @if ($withdrawal->transaction_id)
                                    <div class="col-md-6 mb-3">
                                        <small class="text-muted d-block mb-1">Transaction ID</small>
                                        <code>{{ $withdrawal->transaction_id }}</code>
                                        <button class="btn btn-sm btn-link p-0 ms-2" onclick="
                                            navigator.clipboard.writeText('{{ $withdrawal->transaction_id }}');
                                            this.innerHTML = '<i class=\'bi bi-check\'></i> Copied!';
                                            setTimeout(() => this.innerHTML = '<i class=\'bi bi-clipboard\'></i> Copy', 2000);
                                        ">
                                            <i class="bi bi-clipboard"></i> Copy
                                        </button>
                                    </div>
                                @endif
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block mb-1">Processed By</small>
                                    @if ($withdrawal->approver)
                                        <strong>{{ $withdrawal->approver->name }}</strong>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Timeline -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-clock-history me-2"></i>Timeline
                        </h6>
                        <div class="timeline">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <span class="badge rounded-circle bg-primary p-2">1</span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <strong>Withdrawal Requested</strong>
                                    <p class="mb-0 text-muted small">{{ $withdrawal->created_at->format('d M Y, h:i A') }}
                                    </p>
                                    <p class="mb-0 text-muted small">Amount locked in wallet</p>
                                </div>
                            </div>

                            @if ($withdrawal->status !== 'pending')
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="badge rounded-circle {{ $withdrawal->status === 'approved' ? 'bg-success' : 'bg-danger' }} p-2">2</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <strong>{{ $withdrawal->status === 'approved' ? 'Approved & Processed' : 'Rejected' }}</strong>
                                        <p class="mb-0 text-muted small">
                                            {{ $withdrawal->approved_at->format('d M Y, h:i A') }}
                                            @if ($withdrawal->approver)
                                                by {{ $withdrawal->approver->name }}
                                            @endif
                                        </p>
                                        @if ($withdrawal->status === 'approved')
                                            <p class="mb-0 text-success small">Money sent to user's Binance account</p>
                                        @else
                                            <p class="mb-0 text-danger small">Amount returned to user's wallet</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Rejection Reason -->
                    @if ($withdrawal->status === 'rejected' && $withdrawal->reject_reason)
                        <div class="alert alert-danger mb-0">
                            <strong><i class="bi bi-exclamation-triangle me-2"></i>Rejection Reason:</strong>
                            <p class="mb-0 mt-2">{{ $withdrawal->reject_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Wallet Info -->
            <div class="card shadow border-0">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-wallet2 me-2"></i>User Wallet Information</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3">
                                <small class="text-muted d-block">Current Balance</small>
                                <h4 class="mb-0">${{ number_format($withdrawal->user->wallet->balance ?? 0, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <small class="text-muted d-block">Available Balance</small>
                                <h4 class="mb-0 text-success">
                                    ${{ number_format($withdrawal->user->wallet->available_balance ?? 0, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <small class="text-muted d-block">Locked Balance</small>
                                <h4 class="mb-0 text-warning">
                                    ${{ number_format($withdrawal->user->wallet->locked_balance ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Status & Actions -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow border-0 mb-4">
                <div
                    class="card-header {{ $withdrawal->status === 'pending' ? 'bg-warning' : ($withdrawal->status === 'approved' ? 'bg-success' : 'bg-danger') }} text-white">
                    <h6 class="mb-0">Current Status</h6>
                </div>
                <div class="card-body text-center">
                    @if ($withdrawal->status === 'pending')
                        <i class="bi bi-clock-history text-warning display-1 mb-3"></i>
                        <h4 class="text-warning mb-2">Pending Review</h4>
                        <p class="text-muted mb-0">Waiting for admin approval</p>
                    @elseif($withdrawal->status === 'approved')
                        <i class="bi bi-check-circle text-success display-1 mb-3"></i>
                        <h4 class="text-success mb-2">Approved</h4>
                        <p class="text-muted mb-3">Money sent successfully</p>
                        @if ($withdrawal->transaction_id)
                            <div class="alert alert-success small">
                                <strong>Transaction ID:</strong><br>
                                <code>{{ $withdrawal->transaction_id }}</code>
                            </div>
                        @endif
                    @else
                        <i class="bi bi-x-circle text-danger display-1 mb-3"></i>
                        <h4 class="text-danger mb-2">Rejected</h4>
                        <p class="text-muted mb-0">Amount returned to wallet</p>
                    @endif

                    @if ($withdrawal->approved_at)
                        <hr>
                        <small class="text-muted">
                            Processed on:<br>
                            <strong>{{ $withdrawal->approved_at->format('d M Y, h:i A') }}</strong>
                        </small>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            @if ($withdrawal->status === 'pending')
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h6>
                    </div>
                    <div class="card-body">
                        <!-- Approve Button -->
                        <button type="button" class="btn btn-success w-100 mb-3" data-bs-toggle="modal"
                            data-bs-target="#approveModal">
                            <i class="bi bi-check-circle me-2"></i>Approve Withdrawal
                        </button>

                        <!-- Reject Button -->
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-2"></i>Reject Withdrawal
                        </button>
                    </div>
                </div>

                <!-- Approve Modal -->
                <div class="modal fade" id="approveModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h6 class="modal-title">Approve Withdrawal</h6>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('backend.withdrawals.approve', $withdrawal->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="alert alert-warning">
                                        <strong>⚠️ Important:</strong> Make sure you have sent the money before approving!
                                    </div>

                                    <div class="mb-3">
                                        <strong>Withdrawal Details:</strong>
                                        <ul class="list-unstyled mt-2">
                                            <li><strong>Amount:</strong> {{ $withdrawal->amount }} USDT</li>
                                            <li><strong>Method:</strong> {{ $withdrawal->payment_method_name }}</li>
                                            <li><strong>To:</strong> {{ $withdrawal->account_name }}</li>
                                            <li><strong>Account:</strong> {{ $withdrawal->account_number }}</li>
                                        </ul>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Your Transaction ID <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="transaction_id" class="form-control" required
                                            placeholder="Enter Binance transfer ID">
                                        <small class="text-muted">The transaction ID from your Binance payment</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-2"></i>Confirm & Approve
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h6 class="modal-title">Reject Withdrawal</h6>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('backend.withdrawals.reject', $withdrawal->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="alert alert-info">
                                        <strong>Note:</strong> The locked amount will be returned to user's available
                                        balance.
                                    </div>

                                    <div class="mb-3">
                                        <strong>Reference:</strong> {{ $withdrawal->reference_number }}<br>
                                        <strong>Amount:</strong> {{ $withdrawal->amount }} USDT
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Rejection Reason <span
                                                class="text-danger">*</span></label>
                                        <textarea name="reject_reason" class="form-control" rows="4" required
                                            placeholder="Provide a clear reason for rejection (e.g., Invalid account details, Suspicious activity, etc.)"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-x-circle me-2"></i>Confirm Rejection
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Info Card -->
            <div class="card shadow border-0">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Quick Info</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-calendar3 text-primary me-2"></i>
                            <strong>Requested:</strong><br>
                            <small class="ms-4">{{ $withdrawal->created_at->format('d M Y, h:i A') }}</small>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-clock text-info me-2"></i>
                            <strong>Time Elapsed:</strong><br>
                            <small class="ms-4">{{ $withdrawal->created_at->diffForHumans() }}</small>
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-hash text-secondary me-2"></i>
                            <strong>Reference:</strong><br>
                            <small class="ms-4">{{ $withdrawal->reference_number }}</small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('backend.withdrawals.index') }}" class="btn btn-outline-secondary w-100 mt-4">
                <i class="bi bi-arrow-left me-2"></i>Back to Withdrawals List
            </a>
        </div>
    </div>

@endsection
