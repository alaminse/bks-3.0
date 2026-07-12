@extends('layouts.backend')
@section('title', 'Admin Dashboard')

@section('content')

@include('includes.header', ['pageTitle' => 'Admin Dashboard'])

{{-- Calculation Info --}}
<div class="calculation-info">
    <h6><i class="bi bi-info-circle me-2"></i>Financial Calculations</h6>
    <p>All calculations below exclude demo users to provide accurate business metrics.</p>
</div>

{{-- Stats --}}
<div class="row g-3 g-sm-4 mb-4 stats-row">
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="d-block mb-2 earning-title">Total Users</span>
                        <span class="earning-amount">{{ number_format($totalUsers) }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon-shape bg-primary text-white">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-muted" style="font-size:0.78rem;">{{ number_format($activeUsers) }} active</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="d-block mb-2 earning-title">Total Deposits</span>
                        <span class="earning-amount">${{ number_format($totalDeposits, 2) }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon-shape bg-success text-white">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="badge bg-soft-success">
                        <i class="bi bi-check-circle me-1"></i>{{ number_format($approvedDeposits) }} approved
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="d-block mb-2 earning-title">Total Withdrawals</span>
                        <span class="earning-amount">${{ number_format($totalWithdrawals, 2) }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon-shape bg-warning text-white">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="badge bg-soft-warning">
                        <i class="bi bi-check-circle me-1"></i>{{ number_format($approvedWithdrawals) }} approved
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="d-block mb-2 earning-title">Net Revenue</span>
                        <span class="earning-amount">${{ number_format($netRevenue, 2) }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon-shape bg-info text-white">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-muted" style="font-size:0.78rem;">Excluding demo users</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('backend.users.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus me-1"></i> Add User
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('backend.deposits.index') }}" class="btn btn-success w-100">
                            <i class="bi bi-arrow-down-circle me-1"></i> Deposits
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('backend.withdrawals.index') }}" class="btn btn-warning w-100">
                            <i class="bi bi-arrow-up-circle me-1"></i> Withdrawals
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pending Withdrawals & Deposits --}}
<div class="row g-3 mb-4">

    {{-- Pending Withdrawals --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Pending Withdrawals</h5>
                    <a href="{{ route('backend.withdrawals.index') }}" class="btn btn-sm btn-link">View All</a>
                </div>
            </div>
            <div class="card-body" style="padding:0 !important;">
                @if(count($pendingWithdrawals) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingWithdrawals as $withdrawal)
                            @php $initials = strtoupper(substr($withdrawal->user->name ?? '', 0, 2)); @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white fw-bold" style="width:28px;height:28px;font-size:0.68rem;flex-shrink:0;">
                                            @if(!empty($withdrawal->user->avatar))
                                            <img src="{{ asset($withdrawal->user->avatar) }}" class="rounded-circle" style="width:28px;height:28px;object-fit:cover;" onerror="this.style.display='none'">
                                            @else
                                            {{ $initials }}
                                            @endif
                                        </div>
                                        <span style="font-weight:600;font-size:0.85rem;">{{ $withdrawal->user->name }}</span>
                                    </div>
                                </td>
                                <td style="font-weight:600;">${{ number_format($withdrawal->amount, 2) }}</td>
                                <td style="font-size:0.82rem;color:var(--ad-muted);">{{ $withdrawal->payment_method }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-success btn-action approve-withdrawal" data-id="{{ $withdrawal->id }}">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-action reject-withdrawal" data-id="{{ $withdrawal->id }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-check-circle" style="font-size:2.5rem;color:var(--ad-green);opacity:0.5;display:block;margin-bottom:8px;"></i>
                    <p class="text-muted mb-0">No pending withdrawals</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Pending Deposits --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Pending Deposits</h5>
                    <a href="{{ route('backend.deposits.index') }}" class="btn btn-sm btn-link">View All</a>
                </div>
            </div>
            <div class="card-body" style="padding:0 !important;">
                @if(count($pendingDeposits) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingDeposits as $deposit)
                            @php $initials = strtoupper(substr($deposit->user->name ?? '', 0, 2)); @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white fw-bold" style="width:28px;height:28px;font-size:0.68rem;flex-shrink:0;">
                                            @if(!empty($deposit->user->avatar))
                                            <img src="{{ asset($deposit->user->avatar) }}" class="rounded-circle" style="width:28px;height:28px;object-fit:cover;" onerror="this.style.display='none'">
                                            @else
                                            {{ $initials }}
                                            @endif
                                        </div>
                                        <span style="font-weight:600;font-size:0.85rem;">{{ $deposit->user->name }}</span>
                                    </div>
                                </td>
                                <td style="font-weight:600;">${{ number_format($deposit->amount, 2) }}</td>
                                <td style="font-size:0.82rem;color:var(--ad-muted);">{{ $deposit->payment_method }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-success btn-action approve-deposit" data-id="{{ $deposit->id }}">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-action reject-deposit" data-id="{{ $deposit->id }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-check-circle" style="font-size:2.5rem;color:var(--ad-green);opacity:0.5;display:block;margin-bottom:8px;"></i>
                    <p class="text-muted mb-0">No pending deposits</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Recent Users --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Recent Users</h5>
                    <a href="{{ route('backend.users.index') }}" class="btn btn-sm btn-link">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="filter-tabs">
                    <div class="filter-tab active" data-filter="all">All Users</div>
                    <div class="filter-tab" data-filter="active">Active</div>
                    <div class="filter-tab" data-filter="inactive">Inactive</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Investment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            @php $initials = strtoupper(substr($user->name ?? '', 0, 2)); @endphp
                            <tr class="user-row" data-status="{{ $user->status }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white fw-bold" style="width:32px;height:32px;font-size:0.72rem;flex-shrink:0;background:var(--ad-accent) !important;">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div style="font-weight:600;font-size:0.85rem;">{{ $user->name }} {{ $user->last_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:0.82rem;color:var(--ad-muted);">{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td style="font-size:0.82rem;">{{ $user->created_at->format('M d, Y') }}</td>
                                <td style="font-weight:600;">${{ number_format($user->total_investment ?? 0, 2) }}</td>
                                <td>
                                    <div class="user-actions">
                                        <a href="{{ route('backend.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary btn-action">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary btn-action">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input user-status-toggle" type="checkbox"
                                                id="userStatus{{ $user->id }}"
                                                {{ $user->status === 'active' ? 'checked' : '' }}
                                                data-id="{{ $user->id }}">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function() {
    $('.filter-tab').on('click', function() {
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        const filter = $(this).data('filter');
        if (filter === 'all') {
            $('.user-row').show();
        } else {
            $('.user-row').hide();
            $(`.user-row[data-status="${filter}"]`).show();
        }
    });

    $('.user-status-toggle').on('change', function() {
        const userId = $(this).data('id');
        const status = $(this).is(':checked') ? 'active' : 'inactive';
        $.post(`/bks/users/${userId}/status`, {
            _token: $('meta[name="csrf-token"]').attr('content'),
            status: status
        }).fail(() => { $(this).prop('checked', !$(this).is(':checked')); });
    });

    function ajaxAction(url, data, successMsg, errorMsg) {
        $.post(url, { _token: $('meta[name="csrf-token"]').attr('content'), ...data })
            .done(() => { Swal.fire({ toast:true, position:'top-end', icon:'success', title:successMsg, showConfirmButton:false, timer:2000 }); setTimeout(()=>location.reload(),1500); })
            .fail(() => { Swal.fire({ icon:'error', title:'Error', text:errorMsg }); });
    }

    $('.approve-withdrawal').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({ title:'Approve withdrawal?', icon:'question', showCancelButton:true, confirmButtonText:'Yes, Approve' })
            .then(r => { if(r.isConfirmed) ajaxAction(`/bks/withdrawals/${id}/approve`, {}, 'Withdrawal approved!', 'Error approving withdrawal'); });
    });

    $('.reject-withdrawal').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({ title:'Reject withdrawal?', input:'text', inputLabel:'Reason for rejection', icon:'warning', showCancelButton:true, confirmButtonText:'Reject' })
            .then(r => { if(r.isConfirmed && r.value) ajaxAction(`/bks/withdrawals/${id}/reject`, {reason:r.value}, 'Withdrawal rejected', 'Error rejecting withdrawal'); });
    });

    $('.approve-deposit').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({ title:'Approve deposit?', icon:'question', showCancelButton:true, confirmButtonText:'Yes, Approve' })
            .then(r => { if(r.isConfirmed) ajaxAction(`/bks/deposits/${id}/approve`, {}, 'Deposit approved!', 'Error approving deposit'); });
    });

    $('.reject-deposit').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({ title:'Reject deposit?', input:'text', inputLabel:'Reason for rejection', icon:'warning', showCancelButton:true, confirmButtonText:'Reject' })
            .then(r => { if(r.isConfirmed && r.value) ajaxAction(`/bks/deposits/${id}/reject`, {reason:r.value}, 'Deposit rejected', 'Error rejecting deposit'); });
    });
});
</script>
@endpush
