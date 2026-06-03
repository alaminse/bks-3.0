@extends('layouts.backend')
@section('title') Manage Deposits @endsection
@section('content')
    <!-- Header -->
    @include('includes.header', ['pageTitle' => 'Deposit Management'])

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
            <div class="card shadow border-0 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-warning text-white rounded-circle me-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <small class="text-muted">Pending Requests</small>
                            <h3 class="mb-0">{{ $stats['pending_count'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card shadow border-0 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-primary text-white rounded-circle me-3">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div>
                            <small class="text-muted">Pending Amount</small>
                            <h3 class="mb-0">{{ number_format($stats['pending_amount'], 2) }}</h3>
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
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <small class="text-muted">Approved Today</small>
                            <h3 class="mb-0">{{ $stats['approved_today'] }}</h3>
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
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div>
                            <small class="text-muted">Total Approved</small>
                            <h3 class="mb-0">{{ number_format($stats['total_approved'], 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.deposits.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Payment Method</label>
                    <select name="payment_method" class="form-select form-select-sm">
                        <option value="all">All Methods</option>
                        <option value="binance_pay" {{ request('payment_method') === 'binance_pay' ? 'selected' : '' }}>Binance Pay</option>
                        <option value="binance_p2p" {{ request('payment_method') === 'binance_p2p' ? 'selected' : '' }}>Binance P2P</option>
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

                <div class="col-md-3">
                    <label class="form-label small">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Reference, TrxID, User..." value="{{ request('search') }}">
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

    <!-- Deposits Table -->
    <div class="card shadow border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Deposit Requests</h5>
            <div>
                <button class="btn btn-sm btn-success" onclick="autoRefresh()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Reference</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                        <th>Proof</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deposits as $deposit)
                    <tr class="{{ $deposit->status === 'pending' ? 'table-warning' : '' }}">
                        <td><strong>#{{ $deposit->id }}</strong></td>
                        <td>
                            <div>
                                <strong>{{ $deposit->user->name }}</strong>
                                <span class="badge text-bg-warning ms-2">{{ $deposit->user->isDemo == 1 ? 'Demo' : '' }}</span>
                                <br>
                                <small class="text-muted">{{ $deposit->user->email }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $deposit->reference_number }}</span>
                        </td>
                        <td>
                            <strong class="text-primary">{{ number_format($deposit->amount, 2) }} USDT</strong>
                        </td>
                        <td>
                            @if($deposit->payment_method === 'binance_pay')
                                <span class="badge bg-success"><i class="bi bi-lightning-charge-fill"></i> Pay</span>
                            @else
                                <span class="badge bg-info"><i class="bi bi-people-fill"></i> P2P</span>
                            @endif
                        </td>
                        <td>
                            <code class="small">{{ Str::limit($deposit->transaction_id, 20) }}</code>
                            @if($deposit->sender_info)
                                <br><small class="text-muted">From: {{ $deposit->sender_info }}</small>
                            @endif
                        </td>
                        <td>
                            @if($deposit->payment_proof)
                                <a href="{{ asset('storage/'.$deposit->payment_proof) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-image"></i> View
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $deposit->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $deposit->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            @if($deposit->status === 'pending')
                                <span class="badge bg-warning"><i class="bi bi-clock"></i> Pending</span>
                            @elseif($deposit->status === 'approved')
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Approved</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-lg"></i> Rejected</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-sm btn-neutral" href="{{ route('backend.deposits.show',$deposit->id) }}" title="Show"><i class="bi bi-eye"></i></a>

                            @if($deposit->status === 'pending')
                                <div class="btn-group btn-group-sm">
                                    <form action="{{ route('backend.deposits.approve', $deposit->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Approve this deposit of {{ $deposit->amount }} USDT?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $deposit->id }}">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $deposit->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h6 class="modal-title">Reject Deposit</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('backend.deposits.reject', $deposit->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p><strong>Reference:</strong> {{ $deposit->reference_number }}</p>
                                                    <p><strong>Amount:</strong> {{ $deposit->amount }} USDT</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                        <textarea name="reject_reason" class="form-control" rows="3" required
                                                            placeholder="Please provide a clear reason for rejection..."></textarea>
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
                                <span class="text-muted small">
                                    {{ $deposit->approved_at ? $deposit->approved_at->format('d M Y') : '—' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                            <p class="text-muted">No deposits found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($deposits->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $deposits->firstItem() }} to {{ $deposits->lastItem() }} of {{ $deposits->total() }}
                </div>
                {{ $deposits->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
    // Auto refresh every 30 seconds for pending deposits
    function autoRefresh() {
        location.reload();
    }

    // Optional: Auto refresh if there are pending items
    @if($stats['pending_count'] > 0)
        setTimeout(autoRefresh, 30000); // 30 seconds
    @endif
</script>
@endpush
