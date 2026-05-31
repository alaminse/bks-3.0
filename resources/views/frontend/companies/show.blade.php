@extends('layouts.app')
@section('title') {{ $company->name }} @endsection
@section('content')

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                @if($company->logo)
                    <img src="{{ asset('storage/'.$company->logo) }}" class="card-img-top" alt="{{ $company->name }}" style="max-height: 400px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <h2 class="card-title">{{ $company->name }}</h2>
                    <p class="text-muted" style="white-space: pre-line;">
                        {{ $company->description }}
                    </p>
                    <hr>

                    <h5 class="mb-3">Company Statistics</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <div class="text-muted small mb-1">Total Value</div>
                                <div class="h4 mb-0">${{ number_format($company->total_value, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <div class="text-muted small mb-1">Share Price</div>
                                <div class="h4 mb-0">${{ number_format($company->share_price, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <div class="text-muted small mb-1">Available Shares</div>
                                <div class="h4 mb-0 text-success">{{ number_format($company->available_shares, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <div class="text-muted small mb-1">Total Partners</div>
                                <div class="h4 mb-0">{{ $company->total_partners }}</div>
                            </div>
                        </div>
                    </div>

                    @if($userShare)
                    <div class="alert alert-success mt-4">
                        <h6><i class="bi bi-check-circle"></i> You are a partner of this company!</h6>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <strong>Your Investment:</strong><br>
                                ${{ number_format($userShare->invested_amount, 2) }}
                            </div>
                            <div class="col-md-4">
                                <strong>Your Shares:</strong><br>
                                {{ number_format($userShare->share_quantity, 2) }}
                            </div>
                            <div class="col-md-4">
                                <strong>Ownership:</strong><br>
                                <span class="badge bg-success">{{ number_format($userShare->share_percentage, 2) }}%</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Investment Action</h5>
                </div>
                <div class="card-body">
                    {{-- <div class="mb-3">
                        <div class="text-muted small">Minimum Investment</div>
                        <div class="h5">${{ number_format($company->share_price, 2) }}</div>
                    </div> --}}

                    <div class="mb-3">
                        <div class="text-muted small">Your Balance</div>
                        <div class="h5 text-success">${{ Auth::user()->wallet_balance }}</div>
                    </div>

                    @if($company->available_shares > 0)
                        <a href="{{ route('companies.invest', $company->id) }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-cash-stack"></i> Invest Now
                        </a>
                    @else
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="bi bi-x-circle"></i> No Shares Available
                        </button>
                    @endif

                    <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Back to Companies
                    </a>
                </div>
            </div>

            @if($userShare)
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Purchase Date</small>
                        <div>{{ $userShare->purchase_date->format('d M Y') }}</div>
                    </div>
                    <div>
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-success">{{ ucfirst($userShare->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
