@extends('layouts.backend')
@section('title')
    Company Details
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Company Details</h2>
        <a class="btn btn-secondary btn-sm" href="{{ route('backend.companies.index') }}">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    @if ($company->logo)
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="img-fluid rounded"
                            style="max-width: 200px;">
                    @else
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center mx-auto"
                            style="width: 200px; height: 200px;">
                            <i class="bi bi-building text-white" style="font-size: 80px;"></i>
                        </div>
                    @endif
                </div>

                <div class="col-md-9">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Company Name:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $company->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($company->status == 'active')
                                <label class="badge bg-success">Active</label>
                            @else
                                <label class="badge bg-danger">Inactive</label>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $company->description ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Total Company Value:</strong>
                        </div>
                        <div class="col-md-8">
                            ${{ number_format($company->total_value, 2) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Price Per Share:</strong>
                        </div>
                        <div class="col-md-8">
                            ${{ number_format($company->share_price, 2) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Total Shares:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ number_format($company->total_value / $company->share_price, 2) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Available Shares:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ number_format($company->available_shares, 2) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Shares Issued:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ number_format($company->total_shares_issued, 2) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Total Invested:</strong>
                        </div>
                        <div class="col-md-8">
                            ${{ number_format($company->total_invested, 2) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Total Partners:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $company->total_partners }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Created At:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $company->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Share Price Management Section --}}
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Share Price Management</h6>
                    <a href="{{ route('backend.companies.price-history', $company->id) }}" class="btn btn-sm btn-outline-dark">
                        <i class="bi bi-clock-history"></i> Price History
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.companies.update-share-price', $company->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label for="new_share_price" class="form-label">New Share Price (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="new_share_price" id="new_share_price"
                                    class="form-control" value="{{ $company->share_price }}"
                                    step="0.01" min="0.01" required>
                                <small class="text-muted">Current: ৳{{ number_format($company->share_price, 2) }}</small>
                            </div>
                            <div class="col-md-6">
                                <label for="reason" class="form-label">Reason for Change <span class="text-danger">*</span></label>
                                <input type="text" name="reason" id="reason"
                                    class="form-control" placeholder="e.g., Quarterly performance improvement"
                                    maxlength="500" required>
                            </div>
                            <div class="col-md-2 mt-5">
                                <button type="submit" class="btn btn-warning w-100 mt-3">
                                    <i class="bi bi-graph-up-arrow"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-info-circle"></i>
                        <strong>Note:</strong> Changing share price will affect all partners' portfolio values.
                        Current total partners: <strong>{{ $company->total_partners }}</strong>
                    </div>
                </div>
            </div>


            <div class="mt-4">
                @can('company-edit')
                    <a href="{{ route('backend.companies.edit', $company->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pen"></i> Edit
                    </a>
                @endcan
                <a href="{{ route('backend.companies.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
@endsection
