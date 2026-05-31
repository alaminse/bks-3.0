@extends('layouts.app')
@section('title') My Portfolio @endsection
@section('content')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-pie-chart-fill"></i> My Investment Portfolio</h2>
            <p class="text-muted">Overview of your investments and performance</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('companies.index') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Investment
            </a>
        </div>
    </div>

    <!-- Portfolio Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-wallet2 text-primary" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2 text-muted">Total Invested</h6>
                    <h3 class="mb-0">৳{{ number_format($totalInvested, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up text-success" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2 text-muted">Current Value</h6>
                    <h3 class="mb-0">৳{{ number_format($currentValue, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-{{ $totalProfitLoss >= 0 ? 'success' : 'danger' }}">
                <div class="card-body text-center">
                    <i class="bi bi-{{ $totalProfitLoss >= 0 ? 'arrow-up-circle' : 'arrow-down-circle' }} text-{{ $totalProfitLoss >= 0 ? 'success' : 'danger' }}" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2 text-muted">Unrealized P/L</h6>
                    <h3 class="mb-0 text-{{ $totalProfitLoss >= 0 ? 'success' : 'danger' }}">
                        {{ $totalProfitLoss >= 0 ? '+' : '' }}৳{{ number_format($totalProfitLoss, 2) }}
                    </h3>
                    <small class="text-{{ $totalProfitLoss >= 0 ? 'success' : 'danger' }}">
                        {{ number_format($totalProfitLossPercentage, 2) }}%
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack text-warning" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2 text-muted">Total Profit Received</h6>
                    <h3 class="mb-0 text-success">৳{{ number_format($totalProfitReceived, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Breakdown -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Company-wise Breakdown</h5>
        </div>
        <div class="card-body">
            @if($companiesData->count() > 0)
                @foreach($companiesData as $data)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <h6 class="mb-0">{{ $data['company']->name }}</h6>
                                <small class="text-muted">{{ number_format($data['ownership'], 2) }}% ownership</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Investment</small>
                                <strong>৳{{ number_format($data['investment'], 2) }}</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Current Value</small>
                                <strong>৳{{ number_format($data['current_value'], 2) }}</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Shares</small>
                                <strong>{{ number_format($data['shares'], 2) }}</strong>
                            </div>
                            <div class="col-md-3 text-center">
                                <small class="text-muted d-block">P/L</small>
                                <strong class="text-{{ $data['profit_loss'] >= 0 ? 'success' : 'danger' }}">
                                    {{ $data['profit_loss'] >= 0 ? '+' : '' }}৳{{ number_format($data['profit_loss'], 2) }}
                                    ({{ number_format($data['profit_loss_percentage'], 2) }}%)
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Active Investments</h5>
                    <p class="text-muted">Start investing to see your portfolio here.</p>
                    <a href="{{ route('companies.index') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Browse Companies
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
