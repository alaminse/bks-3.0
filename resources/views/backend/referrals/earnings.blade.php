@extends('layouts.backend')

@section('title') Referral Earnings @endsection

@section('content')
<div class="container-fluid">

    @include('includes.header', [
        'pageTitle' => 'Referral Earnings',
        'backRoute' => route('backend.referrals.settings'),
        'backText'  => 'Back to Settings',
    ])

    {{-- Filter --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.referrals.earnings') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="generation" class="form-select form-select-sm">
                        <option value="">All Levels</option>
                        @for($i = 1; $i <= $maxGen; $i++)
                            <option value="{{ $i }}" {{ request('generation') == $i ? 'selected' : '' }}>
                                Level {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="paid"     {{ request('status') === 'paid'     ? 'selected' : '' }}>Paid</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Search referrer name or email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow border-0">
        <div class="card-header">
            <h5 class="mb-0">All Referral Earnings</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Referrer (Earner)</th>
                        <th>From (Buyer)</th>
                        <th>Level</th>
                        <th>Package Amount</th>
                        <th>Rate</th>
                        <th>Commission</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($earnings as $earning)
                    <tr>
                        <td>{{ $earning->id }}</td>
                        <td>
                            <strong>{{ $earning->referrer->name ?? '—' }}</strong>
                            <br>
                            <small class="text-muted">{{ $earning->referrer->email ?? '' }}</small>
                        </td>
                        <td>
                            <strong>{{ $earning->referred->name ?? '—' }}</strong>
                            <br>
                            <small class="text-muted">{{ $earning->referred->email ?? '' }}</small>
                        </td>
                        <td>
                            @php
                                $colors = [1=>'primary', 2=>'success', 3=>'warning', 4=>'info'];
                                $color  = $colors[$earning->generation] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">
                                Level {{ $earning->generation }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $earning->description }}</small>
                        </td>
                        <td>{{ $earning->commission_rate }}%</td>
                        <td><strong class="text-success">${{ number_format($earning->amount, 2) }}</strong></td>
                        <td>
                            @if($earning->status === 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success">Paid</span>
                            @elseif($earning->status === 'approved')
                                <span class="badge bg-primary bg-opacity-10 text-primary">Approved</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $earning->created_at->format('d M Y') }}</small>
                            <br>
                            <small class="text-muted">{{ $earning->created_at->format('h:i A') }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No earnings found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 py-3">
            {!! $earnings->links('pagination::bootstrap-5') !!}
        </div>
    </div>

</div>
@endsection
