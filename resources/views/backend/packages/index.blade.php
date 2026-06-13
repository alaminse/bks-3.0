@extends('layouts.backend')

@section('title','Package Management')

@section('content')
@include('includes.header', [
    'pageTitle' => 'Packages',
    'createRoute' => route('backend.packages.create'),
    'createText' => 'Create Package',
    'createPermission' => 'package-create',
    'backRoute' => route('backend.packages.index'),
    'backText' => 'Back to Package',
    'backPermission' => 'package-list'
])

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Status</th>
                <th width="150">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($packages as $package)
                <tr>
                    <td>{{ $package->id }}</td>
                    <td>{{ $package->name }}</td>
                    <td>{{ $package->price }}</td>
                    <td>{{ $package->duration_days === 0 ? 'Unlimited' : '' }} days</td>
                    <td>
                        @if($package->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>

                        <a href="{{ route('backend.packages.show', $package) }}"
                           class="btn btn-sm btn-neutral"
                           title="Show">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('backend.packages.edit',$package->id) }}"
                           class="btn btn-sm btn-neutral"
                           title="Edit">
                            <i class="bi bi-pen"></i>
                        </a>

                        <form id="delete-form-{{ $package->id }}"
                              action="{{ route('backend.packages.destroy',$package->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-neutral text-danger"
                                    onclick="confirmDelete('delete-form-{{ $package->id }}', '{{ $package->name }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{ $packages->links() }}
</div>
@endsection
