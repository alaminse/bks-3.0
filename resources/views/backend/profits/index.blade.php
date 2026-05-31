@extends('layouts.backend')
@section('title') Profit Management @endsection
@section('content')
    <!-- Header -->
    @include('includes.header', [
        'pageTitle' => 'Profit Management',
        'createRoute' => route('backend.profits.create'),
        'createText' => 'Add Profit',
        'createPermission' => 'profit-create'
    ])

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p class="mb-0">{{ $message }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <p class="mb-0">{{ $message }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Profits</p>
                            <h3 class="mb-0">{{ $data->total() }}</h3>
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
                            <p class="text-muted mb-1 small">Distributed</p>
                            <h3 class="mb-0">{{ $data->where('distribution_status', 'distributed')->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pending</p>
                            <h3 class="mb-0">{{ $data->where('distribution_status', 'pending')->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
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
                            <p class="text-muted mb-1 small">Total Amount</p>
                            <h3 class="mb-0">৳{{ number_format($data->sum('profit_amount'), 2) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-currency-dollar text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit Table -->
    <div class="card shadow border-0 mb-7">
        <div class="card-header">
            <h5 class="mb-0">Company Profits</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Company</th>
                        <th>Profit Amount</th>
                        <th>Profit Date</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th width="25%" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $key => $profit)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($profit->company->logo)
                                    <img src="{{ asset('storage/'.$profit->company->logo) }}"
                                         alt="{{ $profit->company->name }}"
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
                                    <div class="fw-semibold">{{ $profit->company->name }}</div>
                                    <small class="text-muted">{{ $profit->company->total_partners }} partners</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-success">৳{{ number_format($profit->profit_amount, 2) }}</div>
                            @if($profit->distribution_status == 'distributed')
                                <small class="text-muted">
                                    Distributed: ৳{{ number_format($profit->total_distributed, 2) }}
                                </small>
                            @endif
                        </td>
                        <td>
                            <div>{{ $profit->profit_date->format('d M Y') }}</div>
                            <small class="text-muted">{{ $profit->profit_date->diffForHumans() }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($profit->profit_type) }}</span>
                        </td>
                        <td>
                            @if($profit->distribution_status == 'distributed')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Distributed
                                </span>
                                <div class="small text-muted mt-1">
                                    {{ $profit->distributed_at->format('d M Y, h:i A') }}
                                </div>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $profit->creator->name ?? 'System' }}</div>
                            <small class="text-muted">{{ $profit->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-end">
                            @can('profit-show')
                                <a class="btn btn-sm btn-neutral" href="{{ route('backend.profits.show', $profit->id) }}" title="Show Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endcan

                            @if($profit->distribution_status == 'pending')
                                @can('profit-distribute')
                                    <form method="POST" action="{{ route('backend.profits.distribute', $profit->id) }}" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Are you sure you want to distribute ৳{{ number_format($profit->profit_amount, 2) }} to all {{ $profit->company->total_partners }} partners?')"
                                                title="Distribute Profit">
                                            <i class="bi bi-cash-stack"></i> Distribute
                                        </button>
                                    </form>
                                @endcan

                                @can('profit-edit')
                                    <a class="btn btn-neutral btn-sm" href="{{ route('backend.profits.edit', $profit->id) }}" title="Edit">
                                        <i class="bi bi-pen"></i>
                                    </a>
                                @endcan

                                @can('profit-delete')
                                    <form method="POST" action="{{ route('backend.profits.destroy', $profit->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-square btn-neutral text-danger-hover"
                                                onclick="return confirm('Are you sure you want to delete this profit entry?')"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            @else
                                <span class="badge bg-light text-muted">
                                    <i class="bi bi-lock"></i> Completed
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">No profits found</p>
                                @can('profit-create')
                                    <a href="{{ route('backend.profits.create') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle"></i> Add First Profit
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($data->hasPages())
            <div class="card-footer">
                {!! $data->links('pagination::bootstrap-5') !!}
            </div>
        @endif
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2" style="font-size: 1.5rem;"></i>
        <div>
            <strong>How it works:</strong>
            <ul class="mb-0 mt-2">
                <li>Add company profit entries with amount and date</li>
                <li>Click "Distribute" to automatically share profit with all active partners</li>
                <li>Profit will be distributed based on each partner's ownership percentage</li>
                <li>Partners will receive profit directly in their wallet</li>
                <li>Once distributed, the profit entry cannot be edited or deleted</li>
            </ul>
        </div>
    </div>

@endsection

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .text-danger-hover:hover {
        color: #dc3545 !important;
    }
</style>
