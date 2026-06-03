@extends('layouts.app')
@section('title') Available Companies @endsection
@section('content')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Investment Opportunities</h2>
            <p class="text-muted">Browse available companies and become a partner</p>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($companies as $company)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm hover-shadow">
                @if($company->logo)
                    <img src="{{ asset('storage/'.$company->logo) }}" class="card-img-top" alt="{{ $company->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-building text-white" style="font-size: 4rem;"></i>
                    </div>
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $company->name }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($company->description, 100) }}</p>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Share Price</span>
                            <span class="fw-bold">${{ number_format($company->share_price, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Available Shares</span>
                            <span class="text-success">{{ number_format($company->available_shares, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Total Partners</span>
                            <span class="badge bg-primary">{{ $company->total_partners }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-grid gap-2">
                        <a href="{{ route('companies.show', $company->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                        <a href="{{ route('companies.invest', $company->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-cash-stack"></i> Invest Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> No companies available for investment at the moment.
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {!! $companies->links('pagination::bootstrap-5') !!}
    </div>
</div>

<style>
.hover-shadow {
    transition: box-shadow 0.3s ease-in-out;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>

@endsection
