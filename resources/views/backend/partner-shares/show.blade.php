@extends('layouts.backend')
@section('title') Partner Share Details @endsection
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Partner Share Details</h2>
        <a class="btn btn-secondary" href="{{ route('backend.partner-shares.index') }}">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Partner Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <strong>Partner Name:</strong>
                        </div>
                        <div class="col-md-7">
                            {{ $partnerShare->user->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-5">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-md-7">
                            {{ $partnerShare->user->email }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-5">
                            <strong>Current Balance:</strong>
                        </div>
                        <div class="col-md-7">
                            ${{ number_format($partnerShare->user->balance, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Company Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <strong>Company Name:</strong>
                        </div>
                        <div class="col-md-7">
                            {{ $partnerShare->company->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-5">
                            <strong>Share Price:</strong>
                        </div>
                        <div class="col-md-7">
                            ${{ number_format($partnerShare->company->share_price, 2) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-5">
                            <strong>Total Partners:</strong>
                        </div>
                        <div class="col-md-7">
                            {{ $partnerShare->company->total_partners }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Investment Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Invested Amount:</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="text-success fw-bold">${{ number_format($partnerShare->invested_amount, 2) }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Share Quantity:</strong>
                        </div>
                        <div class="col-md-3">
                            {{ number_format($partnerShare->share_quantity, 2) }} shares
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Share Percentage:</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="badge bg-primary" style="font-size: 1rem;">{{ number_format($partnerShare->share_percentage, 2) }}%</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Purchase Date:</strong>
                        </div>
                        <div class="col-md-3">
                            {{ $partnerShare->purchase_date->format('d M Y') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-3">
                            @if($partnerShare->status == 'active')
                                <label class="badge bg-success">Active</label>
                            @elseif($partnerShare->status == 'sold')
                                <label class="badge bg-warning">Sold</label>
                            @else
                                <label class="badge bg-info">Transferred</label>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Created At:</strong>
                        </div>
                        <div class="col-md-3">
                            {{ $partnerShare->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        @can('partner-share-edit')
            <a href="{{ route('backend.partner-shares.edit', $partnerShare->id) }}" class="btn btn-primary">
                <i class="bi bi-pen"></i> Edit
            </a>
        @endcan
        <a href="{{ route('backend.partner-shares.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

@endsection
