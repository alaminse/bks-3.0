@extends('layouts.app')
@section('title') Profit History @endsection
@section('content')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-graph-up"></i> Profit History</h2>
            <p class="text-muted">Track your earnings from company investments</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('companies.my-investments') }}" class="btn btn-outline-primary">
                <i class="bi bi-briefcase"></i> My Investments
            </a>
        </div>
    </div>

    <!-- Total Profit Summary -->
    <div class="card shadow-sm mb-4 bg-gradient-success text-white">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="text-white-50 mb-2">Total Profit Earned</h6>
                    <h2 class="mb-0 fw-bold">${{ number_format($totalProfit, 2) }}</h2>
                    <small class="text-white-50">From all investments since you became a partner</small>
                </div>
                <div class="col-md-4 text-end">
                    <i class="bi bi-trophy" style="font-size: 5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Profit Distributions List -->
    <div class="card shadow-sm">
        <!-- Profit Stats -->
@if($profitDistributions->count() > 0)
<div class="row g-3 mt-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check text-primary" style="font-size: 2.5rem;"></i>
                <h6 class="mt-2 text-muted">Total Distributions</h6>
                <h4 class="mb-0">{{ $profitDistributions->total() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-cash-stack text-success" style="font-size: 2.5rem;"></i>
                <h6 class="mt-2 text-muted">Total Earned</h6>
                <h4 class="mb-0 text-success">৳{{ number_format($totalProfit, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-calendar-month text-info" style="font-size: 2.5rem;"></i>
                <h6 class="mt-2 text-muted">This Month</h6>
                <h4 class="mb-0">৳{{ number_format($monthlyProfit, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-calendar-year text-warning" style="font-size: 2.5rem;"></i>
                <h6 class="mt-2 text-muted">This Year</h6>
                <h4 class="mb-0">৳{{ number_format($yearlyProfit, 2) }}</h4>
            </div>
        </div>
    </div>
</div>
@endif
        <div class="card-header bg-light">
            <h5 class="mb-0">Profit Distributions</h5>
        </div>
        <div class="card-body p-0">
            @if($profitDistributions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Company</th>
                                <th>Profit Type</th>
                                <th>Your Share %</th>
                                <th>Total Profit</th>
                                <th>Your Earnings</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($profitDistributions as $distribution)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $distribution->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $distribution->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($distribution->company->logo)
                                            <img src="{{ asset('storage/'.$distribution->company->logo) }}"
                                                alt="{{ $distribution->company->name }}"
                                                class="rounded me-2"
                                                width="32"
                                                height="32"
                                                style="object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="bi bi-building text-white small"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $distribution->company->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($distribution->companyProfit->profit_type) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ number_format($distribution->share_percentage, 2) }}%</span>
                                </td>
                                <td>
                                    <div class="text-muted">৳{{ number_format($distribution->companyProfit->profit_amount, 2) }}</div>
                                    <small class="text-muted">Total Profit</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-success">+৳{{ number_format($distribution->profit_amount, 2) }}</div>
                                    <small class="text-muted">Your Share</small>
                                </td>
                                <td>
                                    @if($distribution->status == 'paid')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Received
                                        </span>
                                        <div class="small text-muted mt-1">
                                            {{ $distribution->paid_at->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total Earnings:</td>
                                <td colspan="2" class="fw-bold text-success">${{ number_format($totalProfit, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="card-footer bg-light">
                    {!! $profitDistributions->links('pagination::bootstrap-5') !!}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-graph-down text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Profit Distributions Yet</h5>
                    <p class="text-muted">When companies distribute profits, they will appear here.</p>
                    <a href="{{ route('companies.my-investments') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-briefcase"></i> View My Investments
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Profit Stats -->
    @if($profitDistributions->count() > 0)
    <div class="row g-3 mt-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check text-primary" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2 text-muted">Total Distributions</h6>
                    <h4 class="mb-0">{{ $profitDistributions->total() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack text-success" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2 text-muted">Average Profit</h6>
                    <h4 class="mb-0">${{ number_format($profitDistributions->avg('profit_amount'), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-trophy text-warning" style="font-size: 2.5rem;"></i>
                    <h6 class="mt-2 text-muted">Highest Profit</h6>
                    <h4 class="mb-0">${{ number_format($profitDistributions->max('profit_amount'), 2) }}</h4>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}
</style>

@endsection
