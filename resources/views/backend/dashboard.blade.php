@extends('layouts.backend')
@section('title')
    Admin Dashboard
@endsection
@section('css')
    <style>
        /* Global Improvements */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
        }

        /* Mobile responsive styles */
        @media (max-width: 575px) {
            .card-body {
                padding: 1rem;
            }

            /* Stats cards - 2 cards in one row */
            .stats-row {
                margin-bottom: 1rem !important;
            }

            .stats-row .col-6 {
                padding: 0.25rem !important;
            }

            .stats-row .card {
                border-radius: 12px;
                background: white;
                border: none !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
            }

            .stats-row .card-body {
                padding: 0.85rem 0.6rem !important;
                text-align: center;
            }

            .stats-row .earning-title {
                font-size: 0.7rem !important;
                font-weight: 600 !important;
                color: #64748b !important;
                margin-bottom: 0.4rem !important;
                line-height: 1.2;
            }

            .stats-row .earning-amount {
                font-size: 1.2rem !important;
                font-weight: 800 !important;
                color: #1e293b !important;
                display: block;
            }

            /* Hide icons on mobile */
            .stats-row .col-auto {
                display: none !important;
            }
        }

        /* Desktop Stats Cards */
        @media (min-width: 576px) {
            .stats-row .card {
                transition: all 0.3s ease;
                border: 1px solid #e2e8f0;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .stats-row .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            }
        }

        /* Icon Shapes */
        .icon-shape {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
        }

        /* Card headers improvement */
        .card-header {
            border-bottom: 2px solid #f1f5f9;
            padding: 1rem 1.5rem;
            background: white;
        }

        .card-header h5 {
            color: #1e293b;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Badge improvements */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 600;
            border-radius: 8px;
        }

        /* Table improvements */
        .table {
            font-size: 0.9rem;
        }

        .table td, .table th {
            vertical-align: middle;
            padding: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .table thead th {
            background: #f8fafc;
            font-weight: 700;
            color: #475569;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 575px) {
            .table {
                font-size: 0.75rem;
            }

            .table td, .table th {
                padding: 0.5rem 0.25rem;
            }

            .badge {
                font-size: 0.65rem;
                padding: 0.3rem 0.5rem;
            }

            .card-header h5 {
                font-size: 0.9rem;
            }
        }

        /* Action Buttons */
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 6px;
        }

        /* Status Indicators */
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Demo User Badge */
        .demo-badge {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 700;
            margin-left: 0.5rem;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
            border-radius: 8px;
            background: #f1f5f9;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
        }

        /* Calculation Info Box */
        .calculation-info {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-left: 4px solid #0ea5e9;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .calculation-info h6 {
            color: #0c4a6e;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .calculation-info p {
            color: #075985;
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        /* User Actions */
        .user-actions {
            display: flex;
            gap: 0.25rem;
        }

        /* Toggle Switch */
        .form-switch .form-check-input {
            width: 2.5rem;
            height: 1.25rem;
            cursor: pointer;
        }

        /* Modal Styles */
        .modal-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }
    </style>
@endsection
@section('content')
    <!-- Header -->
    @include('includes.header', ['pageTitle' => 'Admin Dashboard'])

    <!-- Calculation Info -->
    <div class="calculation-info">
        <h6><i class="bi bi-info-circle me-2"></i>Financial Calculations</h6>
        <p>All calculations below exclude demo users to provide accurate business metrics.</p>
    </div>

    <!-- Stats Cards Row - 2 columns on mobile -->
    <div class="row g-3 g-sm-4 mb-4 mb-sm-6 stats-row">
        <!-- Total Users -->
        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2 earning-title">
                                Total Users
                            </span>
                            <span class="h3 font-bold mb-0 earning-amount">{{ number_format($totalUsers) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-primary text-white text-lg">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <span class="text-nowrap text-xs text-muted">{{ number_format($activeUsers) }} active</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Deposits -->
        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2 earning-title">
                                Total Deposits
                            </span>
                            <span class="h3 font-bold mb-0 earning-amount">${{ number_format($totalDeposits, 2) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-success text-white text-lg">
                                <i class="bi bi-arrow-down-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <span class="badge bg-soft-success text-success">
                            <i class="bi bi-check-circle me-1"></i>{{ number_format($approvedDeposits) }} approved
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Withdrawals -->
        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2 earning-title">
                                Total Withdrawals
                            </span>
                            <span class="h3 font-bold mb-0 earning-amount">${{ number_format($totalWithdrawals, 2) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-warning text-white text-lg">
                                <i class="bi bi-arrow-up-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <span class="badge bg-soft-warning text-warning">
                            <i class="bi bi-check-circle me-1"></i>{{ number_format($approvedWithdrawals) }} approved
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Revenue -->
        <div class="col-6 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2 earning-title">
                                Net Revenue
                            </span>
                            <span class="h3 font-bold mb-0 earning-amount">${{ number_format($netRevenue, 2) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-info text-white text-lg">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <span class="text-nowrap text-xs text-muted">Excluding demo users</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 g-sm-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('backend.users.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-person-plus me-2"></i>Add User
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('backend.deposits.index') }}" class="btn btn-success w-100">
                                <i class="bi bi-arrow-down-circle me-2"></i>Deposits
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('backend.withdrawals.index') }}" class="btn btn-warning w-100">
                                <i class="bi bi-arrow-up-circle me-2"></i>Withdrawals
                            </a>
                        </div>
                        {{-- <div class="col-6 col-md-3">
                            <a href="{{ route('backend.reports.index') }}" class="btn btn-info w-100">
                                <i class="bi bi-graph-up me-2"></i>Reports
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Withdrawals & Deposits -->
    <div class="row g-3 g-sm-4 mb-4">
        <!-- Pending Withdrawals -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Pending Withdrawals</h5>
                        <a href="{{ route('backend.withdrawals.index') }}" class="btn btn-sm btn-link">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($pendingWithdrawals) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap mb-0">
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
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xs me-2">
                                                    @php
                                                        $name = $withdrawal->user->name ?? '';
                                                        $initials = strtoupper(substr($name, 0, 2));
                                                    @endphp

                                                    <div class="avatar avatar-xs me-2 d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white fw-bold">
                                                        @if(!empty($withdrawal->user->avatar))
                                                            <img class="avatar-img rounded-circle"
                                                                src="{{ asset($withdrawal->user->avatar) }}"
                                                                alt=""
                                                                onerror="this.style.display='none'">
                                                        @else
                                                            {{ $initials }}
                                                        @endif
                                                    </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $withdrawal->user->name }}</div>
                                                        {{-- @if($withdrawal->user->isDemo ?? false)
                                                            <span class="demo-badge">DEMO</span>
                                                        @endif --}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td><strong>${{ number_format($withdrawal->amount, 2) }}</strong></td>
                                            <td>{{ $withdrawal->payment_method }}</td>
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
                            <i class="bi bi-check-circle display-4 text-success mb-2"></i>
                            <p class="text-muted mb-0">No pending withdrawals</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Deposits -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Pending Deposits</h5>
                        <a href="{{ route('backend.deposits.index') }}" class="btn btn-sm btn-link">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($pendingDeposits) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap mb-0">
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
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $name = $deposit->user->name ?? '';
                                                        $initials = strtoupper(substr($name, 0, 2));
                                                    @endphp

                                                    <div class="avatar avatar-xs me-2 d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white fw-bold">
                                                        @if(!empty($deposit->user->avatar))
                                                            <img class="avatar-img rounded-circle"
                                                                src="{{ asset($deposit->user->avatar) }}"
                                                                alt=""
                                                                onerror="this.style.display='none'">
                                                        @else
                                                            {{ $initials }}
                                                        @endif
                                                    </div>

                                                    <div>
                                                        <div class="fw-bold">{{ $deposit->user->name }}</div>
                                                        {{-- @if($deposit->user->isDemo ?? false)
                                                            <span class="demo-badge">DEMO</span>
                                                        @endif --}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td><strong>${{ number_format($deposit->amount, 2) }}</strong></td>
                                            <td>{{ $deposit->payment_method }}</td>
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
                            <i class="bi bi-check-circle display-4 text-success mb-2"></i>
                            <p class="text-muted mb-0">No pending deposits</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="row g-3 g-sm-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Recent Users</h5>
                        <a href="{{ route('backend.users.index') }}" class="btn btn-sm btn-link">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Tabs -->
                    <div class="filter-tabs">
                        <div class="filter-tab active" data-filter="all">All Users</div>
                        <div class="filter-tab" data-filter="active">Active</div>
                        <div class="filter-tab" data-filter="inactive">Inactive</div>
                        <div class="filter-tab" data-filter="demo">Demo Users</div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap mb-0">
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
                                    <tr class="user-row" data-status="{{ $user->status }}" data-demo="{{ $user->isDemo ?? false ? 'true' : 'false' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    @php
                                                        $name = $deposit->user->name ?? '';
                                                        $initials = strtoupper(substr($name, 0, 2));
                                                    @endphp

                                                    <div class="avatar avatar-xs me-2 d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white fw-bold">
                                                        @if(!empty($deposit->user->avatar))
                                                            <img class="avatar-img rounded-circle"
                                                                src="{{ asset($deposit->user->avatar) }}"
                                                                alt=""
                                                                onerror="this.style.display='none'">
                                                        @else
                                                            {{ $initials }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->name }} {{ $user->last_name }}</div>
                                                    {{-- @if($user->isDemo ?? false)
                                                        <span class="demo-badge">DEMO</span>
                                                    @endif --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <strong>${{ number_format($user->total_investment ?? 0, 2) }}</strong>
                                        </td>
                                        <td>
                                            <div class="user-actions">
                                                <a href="{{ route('backend.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary btn-action">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('backend.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary btn-action">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <div class="form-check form-switch">
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
            // Filter tabs functionality
            $('.filter-tab').on('click', function() {
                $('.filter-tab').removeClass('active');
                $(this).addClass('active');

                const filter = $(this).data('filter');

                if (filter === 'all') {
                    $('.user-row').show();
                } else if (filter === 'demo') {
                    $('.user-row').hide();
                    $('.user-row[data-demo="true"]').show();
                } else {
                    $('.user-row').hide();
                    $('.user-row[data-status="' + filter + '"]').show();
                }
            });

            // User status toggle
            $('.user-status-toggle').on('change', function() {
                const userId = $(this).data('id');
                const isActive = $(this).is(':checked');
                const status = isActive ? 'active' : 'inactive';

                $.ajax({
                    url: `/admin/users/${userId}/status`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        status: status
                    },
                    success: function(response) {
                        // Show success notification
                        showNotification('User status updated successfully', 'success');
                    },
                    error: function(xhr) {
                        // Show error notification
                        showNotification('Error updating user status', 'danger');
                        // Revert the toggle
                        $(this).prop('checked', !isActive);
                    }
                });
            });

            // Approve withdrawal
            $('.approve-withdrawal').on('click', function() {
                const withdrawalId = $(this).data('id');

                if (confirm('Are you sure you want to approve this withdrawal?')) {
                    $.ajax({
                        url: `/admin/withdrawals/${withdrawalId}/approve`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            showNotification('Withdrawal approved successfully', 'success');
                            location.reload();
                        },
                        error: function(xhr) {
                            showNotification('Error approving withdrawal', 'danger');
                        }
                    });
                }
            });

            // Reject withdrawal
            $('.reject-withdrawal').on('click', function() {
                const withdrawalId = $(this).data('id');
                const reason = prompt('Please provide a reason for rejection:');

                if (reason !== null && reason !== '') {
                    $.ajax({
                        url: `/admin/withdrawals/${withdrawalId}/reject`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            reason: reason
                        },
                        success: function(response) {
                            showNotification('Withdrawal rejected successfully', 'success');
                            location.reload();
                        },
                        error: function(xhr) {
                            showNotification('Error rejecting withdrawal', 'danger');
                        }
                    });
                }
            });

            // Approve deposit
            $('.approve-deposit').on('click', function() {
                const depositId = $(this).data('id');

                if (confirm('Are you sure you want to approve this deposit?')) {
                    $.ajax({
                        url: `/admin/deposits/${depositId}/approve`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            showNotification('Deposit approved successfully', 'success');
                            location.reload();
                        },
                        error: function(xhr) {
                            showNotification('Error approving deposit', 'danger');
                        }
                    });
                }
            });

            // Reject deposit
            $('.reject-deposit').on('click', function() {
                const depositId = $(this).data('id');
                const reason = prompt('Please provide a reason for rejection:');

                if (reason !== null && reason !== '') {
                    $.ajax({
                        url: `/admin/deposits/${depositId}/reject`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            reason: reason
                        },
                        success: function(response) {
                            showNotification('Deposit rejected successfully', 'success');
                            location.reload();
                        },
                        error: function(xhr) {
                            showNotification('Error rejecting deposit', 'danger');
                        }
                    });
                }
            });

            // Notification function
            function showNotification(message, type) {
                const notification = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                $('.container-fluid').prepend(notification);

                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }
        });
    </script>
@endpush
