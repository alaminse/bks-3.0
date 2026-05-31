@extends('layouts.app')
@section('title') My Investments @endsection
@section('content')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-briefcase"></i> My Investments</h2>
            <p class="text-muted">Track and manage your company partnerships</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('companies.index') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Investment
            </a>
            <a href="{{ route('companies.profit-history') }}" class="btn btn-outline-success">
                <i class="bi bi-graph-up"></i> Profit History
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Invested</p>
                            <h3 class="mb-0">${{ number_format($totalInvested, 2) }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-cash-stack text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Shares</p>
                            <h3 class="mb-0">{{ number_format($totalShares, 2) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Active Partnerships</p>
                            <h3 class="mb-0">{{ $investments->where('status', 'active')->count() }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-building text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Investments List -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Investment Portfolio</h5>
        </div>
        <div class="card-body p-0">
            @if($investments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Company</th>
                                <th>Investment</th>
                                <th>Shares</th>
                                <th>Ownership</th>
                                <th>Purchase Date</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($investments as $investment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($investment->company->logo)
                                            <img src="{{ asset('storage/'.$investment->company->logo) }}"
                                                alt="{{ $investment->company->name }}"
                                                class="rounded me-2"
                                                width="40"
                                                height="40"
                                                style="object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-building text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $investment->company->name }}</div>
                                            <small class="text-muted">Share Price: ৳{{ number_format($investment->company->share_price, 2) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-primary">৳{{ number_format($investment->invested_amount, 2) }}</div>
                                    @if($investment->status == 'active')
                                        <small class="text-muted">Current: ৳{{ number_format($investment->current_value, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ number_format($investment->share_quantity, 2) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 20px; max-width: 100px;">
                                            <div class="progress-bar bg-success"
                                                role="progressbar"
                                                style="width: {{ min($investment->share_percentage, 100) }}%"
                                                aria-valuenow="{{ $investment->share_percentage }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="fw-semibold">{{ number_format($investment->share_percentage, 2) }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if($investment->status == 'active')
                                        <div class="text-{{ $investment->unrealized_profit_loss >= 0 ? 'success' : 'danger' }} fw-semibold">
                                            {{ $investment->unrealized_profit_loss >= 0 ? '+' : '' }}৳{{ number_format($investment->unrealized_profit_loss, 2) }}
                                        </div>
                                        <small class="text-{{ $investment->unrealized_profit_loss >= 0 ? 'success' : 'danger' }}">
                                            {{ $investment->unrealized_profit_loss >= 0 ? '+' : '' }}{{ number_format($investment->unrealized_profit_loss_percentage, 2) }}%
                                        </small>
                                    @elseif($investment->status == 'sold')
                                        <div class="text-{{ $investment->profit_loss >= 0 ? 'success' : 'danger' }} fw-semibold">
                                            {{ $investment->profit_loss >= 0 ? '+' : '' }}৳{{ number_format($investment->profit_loss, 2) }}
                                        </div>
                                        <small class="text-muted">Realized</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $investment->purchase_date->format('d M Y') }}</small>
                                </td>
                                <td>
                                    @if($investment->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($investment->status == 'sold')
                                        <span class="badge bg-warning">Sold</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($investment->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('companies.show', $investment->company->id) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="View Company">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-light">
                    {!! $investments->links('pagination::bootstrap-5') !!}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Investments Yet</h5>
                    <p class="text-muted">Start investing in companies to become a partner and earn profits.</p>
                    <a href="{{ route('companies.index') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Browse Companies
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Investment Tips -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                    <h6 class="mt-2">Secure Investment</h6>
                    <small class="text-muted">Your investments are protected</small>
                </div>
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <i class="bi bi-graph-up-arrow text-success" style="font-size: 3rem;"></i>
                    <h6 class="mt-2">Track Performance</h6>
                    <small class="text-muted">Monitor your portfolio growth</small>
                </div>
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <i class="bi bi-cash-coin text-warning" style="font-size: 3rem;"></i>
                    <h6 class="mt-2">Earn Profits</h6>
                    <small class="text-muted">Receive regular distributions</small>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bi bi-people text-info" style="font-size: 3rem;"></i>
                    <h6 class="mt-2">Partner Network</h6>
                    <small class="text-muted">Connect with other investors</small>
                </div>
            </div>
        </div>
    </div>


    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Invested</p>
                            <h3 class="mb-0">৳{{ number_format($totalInvested, 2) }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-cash-stack text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Current Value</p>
                            <h3 class="mb-0">৳{{ number_format($currentValue, 2) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-{{ $totalProfitLoss >= 0 ? 'success' : 'danger' }} border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total P/L</p>
                            <h3 class="mb-0 text-{{ $totalProfitLoss >= 0 ? 'success' : 'danger' }}">
                                {{ $totalProfitLoss >= 0 ? '+' : '' }}৳{{ number_format($totalProfitLoss, 2) }}
                            </h3>
                            <small class="text-{{ $totalProfitLoss >= 0 ? 'success' : 'danger' }}">
                                {{ $totalInvested > 0 ? number_format(($totalProfitLoss / $totalInvested) * 100, 2) : 0 }}%
                            </small>
                        </div>
                        <div class="bg-{{ $totalProfitLoss >= 0 ? 'success' : 'danger' }} bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-{{ $totalProfitLoss >= 0 ? 'arrow-up-circle' : 'arrow-down-circle' }} text-{{ $totalProfitLoss >= 0 ? 'success' : 'danger' }}" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Shares</p>
                            <h3 class="mb-0">{{ number_format($totalShares, 2) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-pie-chart text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
