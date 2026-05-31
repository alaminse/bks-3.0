@extends('layouts.backend')
@section('title') Profit Distributions - {{ $user->name }} @endsection
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Profit Distribution History</h2>
        <p class="text-muted mb-0">{{ $user->name }} ({{ $user->email }})</p>
    </div>
    <a class="btn btn-secondary" href="{{ route('backend.partner-shares.index') }}">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card shadow border-0 mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Total Profit Received</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-4 text-success">৳{{ number_format($totalProfit, 2) }}</h1>
                <p class="text-muted">From {{ $distributions->total() }} distributions</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-header">
        <h5 class="mb-0">Distribution History</h5>
    </div>
    <div class="card-body p-0">
        @if($distributions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Company</th>
                        <th>Profit Type</th>
                        <th>Share %</th>
                        <th>Total Profit</th>
                        <th>Received Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($distributions as $key => $distribution)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <div>{{ $distribution->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $distribution->created_at->format('h:i A') }}</small>
                        </td>
                        <td>{{ $distribution->company->name }}</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($distribution->companyProfit->profit_type) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ number_format($distribution->share_percentage, 2) }}%</span>
                        </td>
                        <td>৳{{ number_format($distribution->companyProfit->profit_amount, 2) }}</td>
                        <td>
                            <strong class="text-success">৳{{ number_format($distribution->profit_amount, 2) }}</strong>
                        </td>
                        <td>
                            @if($distribution->status == 'paid')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Paid
                                </span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {!! $distributions->links('pagination::bootstrap-5') !!}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3">No profit distributions yet</p>
        </div>
        @endif
    </div>
</div>

@endsection
