@extends('layouts.backend')
@section('title') Partner Shares Management @endsection
@section('content')
    <!-- Header -->
    @include('includes.header', [
        'pageTitle' => 'Partner Shares Management',
        'createRoute' => route('backend.partner-shares.create'),
        'createText' => 'Add Partner Share',
        'createPermission' => 'partner-share-create'
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

    <!-- Filters -->
    <div class="card shadow border-0 mb-3">
        <div class="card-body">
            <form action="{{ route('backend.partner-shares.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Company</label>
                        <select name="company_id" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Partner</label>
                        <select name="user_id" class="form-select">
                            <option value="">All Partners</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}" {{ request('user_id') == $partner->id ? 'selected' : '' }}>
                                    {{ $partner->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow border-0 mb-7">
        <div class="card-header">
            <h5 class="mb-0">Partner Shares</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Partner</th>
                        <th>Company</th>
                        <th>Invested Amount</th>
                        <th>Share Quantity</th>
                        <th>Share %</th>
                        <th>Purchase Date</th>
                        <th>Status</th>
                        <th width="20%" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $share)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-secondary rounded-circle text-white me-2">
                                    {{ substr($share->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $share->user->name }}</div>
                                    <small class="text-muted">{{ $share->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $share->company->name }}</td>
                        <td>${{ number_format($share->invested_amount, 2) }}</td>
                        <td>{{ number_format($share->share_quantity, 2) }}</td>
                        <td>
                            <span class="badge bg-primary">{{ number_format($share->share_percentage, 2) }}%</span>
                        </td>
                        <td>{{ $share->purchase_date->format('d M Y') }}</td>
                        <td>
                            @if($share->status == 'active')
                                <label class="badge bg-success">Active</label>
                            @elseif($share->status == 'sold')
                                <label class="badge bg-warning">Sold</label>
                            @else
                                <label class="badge bg-info">Transferred</label>
                            @endif
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-success" href="{{ route('backend.profits.partner-distributions', $share->user_id) }}" title="Profit History">
                                <i class="bi bi-cash-stack"></i>
                            </a>

                            @can('partner-share-show')
                                <a class="btn btn-sm btn-neutral" href="{{ route('backend.partner-shares.show', $share->id) }}" title="Show">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endcan
                            @can('partner-share-edit')
                                <a class="btn btn-neutral btn-sm" href="{{ route('backend.partner-shares.edit', $share->id) }}">
                                    <i class="bi bi-pen"></i>
                                </a>
                            @endcan
                            @can('partner-share-delete')
                                <form method="POST" action="{{ route('backend.partner-shares.destroy', $share->id) }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-square btn-neutral text-danger-hover"
                                            onclick="return confirm('Are you sure? This will refund the investment to the partner.')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! $data->links('pagination::bootstrap-5') !!}
    </div>
@endsection
