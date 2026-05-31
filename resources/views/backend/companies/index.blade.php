@extends('layouts.backend')
@section('title') Companies Management @endsection
@section('content')
    <!-- Header -->
    @include('includes.header', [
        'pageTitle' => 'Companies Management',
        'createRoute' => route('backend.companies.create'),
        'createText' => 'Add Company',
        'createPermission' => 'company-create'
    ])

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p class="mb-0">{{ $message }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow border-0 mb-7">
        <div class="card-header">
            <h5 class="mb-0">Companies</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Total Value</th>
                        <th>Share Price</th>
                        <th>Available Shares</th>
                        <th>Total Partners</th>
                        <th>Status</th>
                        <th width="20%" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $company)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            @if($company->logo)
                                <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" width="40" height="40" class="rounded">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-building text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $company->name }}</td>
                        <td>${{ number_format($company->total_value, 2) }}</td>
                        <td>${{ number_format($company->share_price, 2) }}</td>
                        <td>{{ number_format($company->available_shares, 2) }}</td>
                        <td>{{ $company->total_partners }}</td>
                        <td>
                            @if($company->status == 'active')
                                <label class="badge bg-success">Active</label>
                            @else
                                <label class="badge bg-danger">Inactive</label>
                            @endif
                        </td>
                        <td class="text-end">
                            @can('company-show')
                                <a class="btn btn-sm btn-neutral" href="{{ route('backend.companies.show', $company->id) }}" title="Show">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endcan
                            @can('company-edit')
                                <a class="btn btn-neutral btn-sm" href="{{ route('backend.companies.edit', $company->id) }}">
                                    <i class="bi bi-pen"></i>
                                </a>
                            @endcan
                            @can('company-delete')
                                <form method="POST" action="{{ route('backend.companies.destroy', $company->id) }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-square btn-neutral text-danger-hover" onclick="return confirm('Are you sure you want to delete this company?')">
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
