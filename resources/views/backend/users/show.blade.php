@extends('layouts.backend')
@section('title')
    User Details
@endsection
@section('content')
    @include('includes.header', [
        'pageTitle' => 'Show User',
        'createRoute' => route('backend.users.index'),
        'createText' => 'Back',
        'createPermission' => 'user-list',
    ])

    <style>
        .nav-pills .nav-link {
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            background: transparent;
            color: #6c757d;
            border: 2px solid transparent;
        }

        .nav-pills .nav-link:hover {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Table Enhancements */
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
            transform: translateX(5px);
        }

        /* Card Hover Effects */
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Progress Bar */
        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .progress-bar {
            border-radius: 10px;
        }

        /* Code Styling */
        code {
            padding: 2px 6px;
            border-radius: 4px;
            background: rgba(13, 110, 253, 0.1);
            font-size: 0.875rem;
        }

        /* Badge Enhancements */
        .badge {
            padding: 5px 10px;
            font-weight: 500;
        }

        /* Empty State */
        .bi-inbox {
            opacity: 0.3;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        }

        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 10px;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .rounded-pill {
            border-radius: 50px !important;
        }
    </style>

    <div class="card p-3">
        <div class="row">

            <div class="col-md-3">
                <!-- User Profile Card -->
                <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px; overflow: hidden;">
                    <!-- Cover Image -->
                    <div class="bg-gradient-primary p-4 text-center position-relative" style="height: 120px;">
                        <div class="position-absolute" style="bottom: -50px; left: 50%; transform: translateX(-50%);">
                            <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=120&background=random' }}"
                                alt="{{ $user->name }}" class="rounded-circle border border-5 border-white shadow-lg"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="card-body text-center pt-5 mt-4">
                        <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                        <p class="text-muted small mb-2">{{ $user->email }}</p>

                        <!-- Roles -->
                        @if (!empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $role)
                                <span
                                    class="badge rounded-pill bg-{{ $role == 'Super Admin' ? 'danger' : ($role == 'Admin' ? 'primary' : ($role == 'partner' ? 'warning' : 'info')) }} px-3 py-1 me-1">
                                    {{ ucfirst($role) }}
                                </span>
                            @endforeach
                        @endif

                        <!-- Stats Row -->
                        <div class="row g-2 mt-4">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <h6 class="mb-0 fw-bold text-primary">{{ $user->created_at->format('Y') }}</h6>
                                    <small class="text-muted">Joined</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <h6 class="mb-0 fw-bold text-success">Active</h6>
                                    <small class="text-muted">Status</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wallet Card -->
                <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="bi bi-wallet2 text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Wallet</h5>
                                <small class="text-muted">Balance Overview</small>
                            </div>
                        </div>

                        <!-- Available Balance -->
                        <div class="bg-success bg-opacity-10 rounded-3 p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block mb-1">Available</small>
                                    <h4 class="mb-0 text-success fw-bold">
                                        ৳{{ number_format($user->wallet->balance ?? 0, 2) }}</h4>
                                </div>
                                <i class="bi bi-arrow-up-circle text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>

                        <!-- Locked Balance -->
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block mb-1">Locked</small>
                                    <h5 class="mb-0 text-warning fw-bold">
                                        ৳{{ number_format($user->wallet->locked_balance ?? 0, 2) }}</h5>
                                </div>
                                <i class="bi bi-lock text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-3">

                        <!-- Total -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold">Total Balance</span>
                            <h4 class="mb-0 text-primary fw-bold">
                                ৳{{ number_format(($user->wallet->balance ?? 0) + ($user->wallet->locked_balance ?? 0), 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Investment Card (If Partner) -->
                @if ($user->hasRole('partner'))
                    <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-briefcase text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Investments</h5>
                                    <small class="text-muted">Portfolio Summary</small>
                                </div>
                            </div>

                            @php
                                $activeInvestments = \App\Models\PartnerShare::where('user_id', $user->id)
                                    ->where('status', 'active')
                                    ->get();
                                $totalInvested = $activeInvestments->sum('invested_amount');
                                $totalProfit = \App\Models\ProfitDistribution::where('user_id', $user->id)->sum(
                                    'profit_amount',
                                );
                            @endphp

                            <!-- Investment Stats -->
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded-3">
                                        <h6 class="mb-1 fw-bold text-primary">৳{{ number_format($totalInvested, 0) }}</h6>
                                        <small class="text-muted">Invested</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded-3">
                                        <h6 class="mb-1 fw-bold text-success">৳{{ number_format($totalProfit, 0) }}</h6>
                                        <small class="text-muted">Profit</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Companies Count -->
                            <div class="bg-info bg-opacity-10 rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Active Companies</span>
                                    <h5 class="mb-0 text-info fw-bold">
                                        {{ $activeInvestments->unique('company_id')->count() }}</h5>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('backend.partner-shares.index', ['user_id' => $user->id]) }}"
                                    class="btn btn-outline-success btn-sm rounded-pill">
                                    <i class="bi bi-bar-chart"></i> View Investments
                                </a>
                                <a href="{{ route('backend.profits.partner-distributions', $user->id) }}"
                                    class="btn btn-outline-info btn-sm rounded-pill">
                                    <i class="bi bi-graph-up"></i> Profit History
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-9">
                <!-- Tabs Card -->
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <!-- Nav Tabs -->
                    <div class="card-header bg-white border-0 pt-4">
                        <ul class="nav nav-pills nav-fill gap-2" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview"
                                    type="button">
                                    <i class="bi bi-grid-fill d-block mb-1" style="font-size: 1.2rem;"></i>
                                    <span class="d-none d-md-inline">Overview</span>
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#deposits" type="button">
                                    <i class="bi bi-arrow-down-circle-fill d-block mb-1" style="font-size: 1.2rem;"></i>
                                    <span class="d-none d-md-inline">Deposits</span>
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#withdrawals"
                                    type="button">
                                    <i class="bi bi-arrow-up-circle-fill d-block mb-1" style="font-size: 1.2rem;"></i>
                                    <span class="d-none d-md-inline">Withdrawals</span>
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transactions"
                                    type="button">
                                    <i class="bi bi-clock-history d-block mb-1" style="font-size: 1.2rem;"></i>
                                    <span class="d-none d-md-inline">Transactions</span>
                                </button>
                            </li>

                            @if ($user->hasRole('partner'))
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#partners"
                                        type="button">
                                        <i class="bi bi-briefcase-fill d-block mb-1" style="font-size: 1.2rem;"></i>
                                        <span class="d-none d-md-inline">Investments</span>
                                    </button>
                                </li>
                            @endif

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#referrals"
                                    type="button">
                                    <i class="bi bi-people-fill d-block mb-1" style="font-size: 1.2rem;"></i>
                                    <span class="d-none d-md-inline">Referrals</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <!-- Overview Tab -->
                            <div class="tab-pane fade show active" id="overview">
                                <h5 class="mb-4">
                                    <i class="bi bi-graph-up text-primary"></i> Account Overview
                                </h5>

                                <div class="row g-3">
                                    <!-- Total Deposits -->
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-primary bg-opacity-10 h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-25 rounded-circle p-3 me-3">
                                                        <i class="bi bi-arrow-down-circle text-primary"
                                                            style="font-size: 1.5rem;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted mb-1 small">Total Deposits</p>
                                                        <h4 class="mb-0 text-primary">
                                                            ৳{{ number_format($user->deposits()->sum('amount'), 2) }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Total Withdrawals -->
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-danger bg-opacity-10 h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-danger bg-opacity-25 rounded-circle p-3 me-3">
                                                        <i class="bi bi-arrow-up-circle text-danger"
                                                            style="font-size: 1.5rem;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted mb-1 small">Total Withdrawals</p>
                                                        <h4 class="mb-0 text-danger">
                                                            ৳{{ number_format($user->withdrawals()->sum('amount'), 2) }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($user->hasRole('partner'))
                                        <!-- Total Investment -->
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-warning bg-opacity-10 h-100">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-warning bg-opacity-25 rounded-circle p-3 me-3">
                                                            <i class="bi bi-briefcase text-warning"
                                                                style="font-size: 1.5rem;"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <p class="text-muted mb-1 small">Total Investment</p>
                                                            <h4 class="mb-0 text-warning">
                                                                ৳{{ number_format($user->partnerShares()->sum('invested_amount'), 2) }}
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Total Share % -->
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-success bg-opacity-10 h-100">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-25 rounded-circle p-3 me-3">
                                                            <i class="bi bi-pie-chart text-success"
                                                                style="font-size: 1.5rem;"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <p class="text-muted mb-1 small">Portfolio Ownership</p>
                                                            <h4 class="mb-0 text-success">
                                                                {{ number_format($user->partnerShares()->sum('share_percentage'), 2) }}%
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Total Referrals -->
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-info bg-opacity-10 h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-opacity-25 rounded-circle p-3 me-3">
                                                        <i class="bi bi-people text-info" style="font-size: 1.5rem;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted mb-1 small">Total Referrals</p>
                                                        <h4 class="mb-0 text-info">{{ $user->referrals()->count() }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Referral Earnings -->
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-secondary bg-opacity-10 h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-secondary bg-opacity-25 rounded-circle p-3 me-3">
                                                        <i class="bi bi-gift text-secondary"
                                                            style="font-size: 1.5rem;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted mb-1 small">Referral Earnings</p>
                                                        <h4 class="mb-0 text-secondary">
                                                            ৳{{ number_format($user->referralEarnings()->sum('amount'), 2) }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deposits Tab -->
                            <div class="tab-pane fade" id="deposits">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">
                                        <i class="bi bi-arrow-down-circle text-primary"></i> Deposit History
                                    </h5>
                                    <span class="badge bg-primary">{{ $user->deposits->count() }} Total</span>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($user->deposits as $deposit)
                                                <tr>
                                                    <td>
                                                        <code class="text-primary">{{ $deposit->transaction_id }}</code>
                                                    </td>
                                                    <td class="fw-bold text-success">
                                                        ৳{{ number_format($deposit->amount, 2) }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-light text-dark">{{ $deposit->payment_method }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $deposit->status == 'approved' ? 'success' : ($deposit->status == 'rejected' ? 'danger' : 'warning') }}">
                                                            {{ ucfirst($deposit->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small
                                                            class="text-muted">{{ $deposit->created_at->format('d M Y, h:i A') }}</small>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                                        <p class="text-muted mt-2">No deposits found</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Withdrawals Tab -->
                            <div class="tab-pane fade" id="withdrawals">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">
                                        <i class="bi bi-arrow-up-circle text-danger"></i> Withdrawal History
                                    </h5>
                                    <span class="badge bg-danger">{{ $user->withdrawals->count() }} Total</span>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Amount</th>
                                                <th>Account Name</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($user->withdrawals as $withdrawal)
                                                <tr>
                                                    <td>
                                                        <code class="text-danger">{{ $withdrawal->transaction_id }}</code>
                                                    </td>
                                                    <td class="fw-bold text-danger">
                                                        ৳{{ number_format($withdrawal->amount, 2) }}</td>
                                                    <td>{{ $withdrawal->account_name }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $withdrawal->status == 'approved' ? 'success' : ($withdrawal->status == 'rejected' ? 'danger' : 'warning') }}">
                                                            {{ ucfirst($withdrawal->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small
                                                            class="text-muted">{{ $withdrawal->created_at->format('d M Y, h:i A') }}</small>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                                        <p class="text-muted mt-2">No withdrawals found</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Transactions Tab -->
                            <div class="tab-pane fade" id="transactions">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">
                                        <i class="bi bi-clock-history text-info"></i> Wallet Transactions
                                    </h5>
                                    <span class="badge bg-info">{{ $user->wallet?->transactions->count() ?? 0 }}
                                        Total</span>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Type</th>
                                                <th>Direction</th>
                                                <th>Amount</th>
                                                <th>Description</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($user->wallet && $user->wallet->transactions->count() > 0)
                                                @foreach ($user->wallet->transactions as $transaction)
                                                    <tr>
                                                        <td>
                                                            <span
                                                                class="badge bg-light text-dark">{{ ucfirst($transaction->type) }}</span>
                                                        </td>
                                                        <td>
                                                            @if ($transaction->direction == 'credit')
                                                                <span class="badge bg-success">
                                                                    <i class="bi bi-arrow-down"></i> Credit
                                                                </span>
                                                            @else
                                                                <span class="badge bg-danger">
                                                                    <i class="bi bi-arrow-up"></i> Debit
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td
                                                            class="fw-bold text-{{ $transaction->direction == 'credit' ? 'success' : 'danger' }}">
                                                            {{ $transaction->direction == 'credit' ? '+' : '-' }}৳{{ number_format($transaction->amount, 2) }}
                                                        </td>
                                                        <td>
                                                            <small
                                                                class="text-muted">{{ $transaction->description }}</small>
                                                        </td>
                                                        <td>
                                                            <small
                                                                class="text-muted">{{ $transaction->created_at->format('d M Y, h:i A') }}</small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                                        <p class="text-muted mt-2">No transactions found</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Partner Shares Tab -->
                            @if ($user->hasRole('partner'))
                                <div class="tab-pane fade" id="partners">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5 class="mb-0">
                                            <i class="bi bi-briefcase text-warning"></i> Investment Portfolio
                                        </h5>
                                        <span class="badge bg-warning">{{ $user->partnerShares->count() }}
                                            Companies</span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Company</th>
                                                    <th>Invested</th>
                                                    <th>Shares</th>
                                                    <th>Ownership</th>
                                                    <th>Purchase Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($user->partnerShares as $share)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if ($share->company->logo)
                                                                    <img src="{{ asset('storage/' . $share->company->logo) }}"
                                                                        alt="{{ $share->company->name }}"
                                                                        class="rounded me-2" width="32"
                                                                        height="32" style="object-fit: cover;">
                                                                @else
                                                                    <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center"
                                                                        style="width: 32px; height: 32px;">
                                                                        <i class="bi bi-building text-white small"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div class="fw-semibold">
                                                                        {{ $share->company->name ?? 'N/A' }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="fw-bold text-primary">
                                                            ৳{{ number_format($share->invested_amount, 2) }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-info">{{ number_format($share->share_quantity, 2) }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="progress flex-grow-1 me-2"
                                                                    style="height: 20px; max-width: 80px;">
                                                                    <div class="progress-bar bg-success"
                                                                        role="progressbar"
                                                                        style="width: {{ min($share->share_percentage, 100) }}%">
                                                                    </div>
                                                                </div>
                                                                <span
                                                                    class="fw-semibold">{{ number_format($share->share_percentage, 2) }}%</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <small
                                                                class="text-muted">{{ $share->purchase_date->format('d M Y') }}</small>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $share->status == 'active' ? 'success' : ($share->status == 'sold' ? 'info' : 'warning') }}">
                                                                {{ ucfirst($share->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-5">
                                                            <i class="bi bi-inbox text-muted"
                                                                style="font-size: 3rem;"></i>
                                                            <p class="text-muted mt-2">No investments found</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            <!-- Referrals Tab -->
                            <div class="tab-pane fade" id="referrals">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">
                                        <i class="bi bi-people text-info"></i> Referral Network
                                    </h5>
                                    <span class="badge bg-info">{{ $user->referrals->count() }} Referrals</span>
                                </div>

                                <!-- Referred Users -->
                                <div class="card border-0 bg-light mb-4">
                                    <div class="card-body">
                                        <h6 class="mb-3">
                                            <i class="bi bi-person-plus"></i> Referred Users
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Referral Code</th>
                                                        <th>User</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($user->referrals as $referral)
                                                        <tr>
                                                            <td>
                                                                <code
                                                                    class="text-primary">{{ $referral->referral_code }}</code>
                                                            </td>
                                                            <td>{{ $referral->referredUser->name ?? 'N/A' }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $referral->status == 'completed' ? 'success' : ($referral->status == 'activated' ? 'info' : 'warning') }}">
                                                                    {{ ucfirst($referral->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <small
                                                                    class="text-muted">{{ $referral->created_at->format('d M Y') }}</small>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center py-3 text-muted">No
                                                                referrals found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Referral Earnings -->
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3">
                                            <i class="bi bi-cash-stack"></i> Referral Earnings
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Amount</th>
                                                        <th>Commission</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($user->referralEarnings as $earning)
                                                        <tr>
                                                            <td>
                                                                <span
                                                                    class="badge bg-light text-dark">{{ ucfirst($earning->type) }}</span>
                                                            </td>
                                                            <td class="fw-bold text-success">
                                                                ৳{{ number_format($earning->amount, 2) }}</td>
                                                            <td>{{ number_format($earning->commission_rate, 2) }}%</td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $earning->status == 'paid' ? 'success' : 'warning' }}">
                                                                    {{ ucfirst($earning->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <small
                                                                    class="text-muted">{{ $earning->created_at->format('d M Y') }}</small>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center py-3 text-muted">No
                                                                earnings found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Packages Tab -->
        <div class="card border-0 shadow-sm mb-3 overflow-hidden px-4">
            <div class="d-flex justify-content-between align-items-center my-4">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam text-success"></i> Active Packages
                </h5>
                <span class="badge bg-success">{{ $user->activePackages->count() }} Active</span>
            </div>

            @forelse ($user->activePackages as $package)
                <div class="row g-0">
                    <!-- Package Icon/Image -->
                    <div class="col-md-2 bg-gradient-primary d-flex align-items-center justify-content-center">
                        <div class="text-center text-white p-3">
                            <i class="bi bi-trophy-fill" style="font-size: 3rem;"></i>
                            <div class="mt-2 fw-bold">{{ $package->package->name }}</div>
                        </div>
                    </div>

                    <!-- Package Details -->
                    <div class="col-md-7">
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Amount -->
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-cash"></i> Package Amount
                                    </small>
                                    <h5 class="mb-0 text-success">৳{{ number_format($package->amount, 2) }}</h5>
                                </div>

                                <!-- Daily Return -->
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-graph-up-arrow"></i> Daily Return
                                    </small>
                                    <h5 class="mb-0 text-primary">৳{{ number_format($package->daily_return, 2) }}</h5>
                                </div>

                                <!-- Total Return -->
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-wallet2"></i> Total Return
                                    </small>
                                    <h5 class="mb-0 text-info">৳{{ number_format($package->total_return, 2) }}</h5>
                                </div>

                                <!-- Duration -->
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-calendar-check"></i> Duration
                                    </small>
                                    <div class="fw-semibold">{{ $package->duration }} Days</div>
                                </div>

                                <!-- Days Remaining -->
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-hourglass-split"></i> Remaining
                                    </small>
                                    <div class="fw-semibold text-warning">{{ $package->days_remaining }} Days</div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-check-circle"></i> Status
                                    </small>
                                    <span
                                        class="badge bg-{{ $package->status == 'active' ? 'success' : ($package->status == 'completed' ? 'info' : 'warning') }}">
                                        {{ ucfirst($package->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-3">
                                @php
                                    $progress =
                                        $package->duration > 0
                                            ? (($package->duration - $package->days_remaining) / $package->duration) *
                                                100
                                            : 0;
                                @endphp
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="fw-semibold">{{ number_format($progress, 0) }}%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dates & Actions -->
                    <div class="col-md-3 bg-light">
                        <div class="card-body h-100 d-flex flex-column">
                            <!-- Purchase Date -->
                            <div class="mb-3">
                                <small class="text-muted d-block">Purchase Date</small>
                                <div class="fw-semibold">{{ $package->purchase_date?->format('d M Y') }}</div>
                            </div>

                            <!-- Expiry Date -->
                            <div class="mb-3">
                                <small class="text-muted d-block">Expiry Date</small>
                                <div class="fw-semibold">{{ $package->expiry_date?->format('d M Y') }}</div>
                            </div>

                            <!-- Return Collected -->
                            <div class="mb-3">
                                <small class="text-muted d-block">Collected</small>
                                <div class="text-success fw-bold">
                                    ৳{{ number_format($package->return_collected ?? 0, 2) }}</div>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-auto">
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-info-circle"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="text-muted mt-3">No Active Packages</h5>
                    <p class="text-muted">User hasn't purchased any packages yet.</p>
                </div>
            @endforelse

        </div>
    </div>
@endsection
