@extends('layouts.backend')
@section('title') Share Price History - {{ $company->name }} @endsection
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Share Price History</h2>
        <p class="text-muted mb-0">{{ $company->name }}</p>
    </div>
    <a class="btn btn-secondary" href="{{ route('backend.companies.show', $company->id) }}">
        <i class="bi bi-arrow-left"></i> Back to Company
    </a>
</div>

<div class="card shadow border-0 mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Current Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <small class="text-muted d-block">Current Share Price</small>
                <h4 class="mb-0">৳{{ number_format($company->share_price, 2) }}</h4>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Total Changes</small>
                <h4 class="mb-0">{{ $histories->total() }}</h4>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Active Partners</small>
                <h4 class="mb-0">{{ $company->total_partners }}</h4>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Available Shares</small>
                <h4 class="mb-0">{{ number_format($company->available_shares, 2) }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-header">
        <h5 class="mb-0">Price Change History</h5>
    </div>
    <div class="card-body p-0">
        @if($histories->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Old Price</th>
                        <th>New Price</th>
                        <th>Change</th>
                        <th>Reason</th>
                        <th>Changed By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                    <tr>
                        <td>
                            <div>{{ $history->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $history->created_at->format('h:i A') }}</small>
                        </td>
                        <td>৳{{ number_format($history->old_price, 2) }}</td>
                        <td>৳{{ number_format($history->new_price, 2) }}</td>
                        <td>
                            <span class="badge {{ $history->change_percentage >= 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $history->change_percentage >= 0 ? '+' : '' }}{{ number_format($history->change_percentage, 2) }}%
                            </span>
                            <div class="small {{ $history->change_percentage >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $history->change_percentage >= 0 ? '+' : '' }}৳{{ number_format($history->new_price - $history->old_price, 2) }}
                            </div>
                        </td>
                        <td>{{ $history->reason ?? 'N/A' }}</td>
                        <td>{{ $history->changedBy->name ?? 'System' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {!! $histories->links('pagination::bootstrap-5') !!}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3">No price changes yet</p>
        </div>
        @endif
    </div>
</div>

@endsection
