@extends('layouts.backend')

@section('title') Role Management @endsection

@section('content')
@include('includes.header', [
    'pageTitle' => 'Role Management',
    'createRoute' => route('backend.roles.create'),
    'createText' => 'Create New Role',
    'createPermission' => 'role-create'
])

<div class="card shadow border-0 mb-7">
    <div class="card-header">
        <h5 class="mb-0">Roles</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-nowrap">
            <thead class="thead-light">
                <tr>
                    <th width="100px">No</th>
                    <th>Name</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a class="btn btn-sm btn-neutral" href="{{ route('backend.roles.show', $role->id) }}">
                            <i class="bi bi-list"></i>
                        </a>
                        @can('role-edit')
                            <a class="btn btn-sm btn-neutral" href="{{ route('backend.roles.edit', $role->id) }}">
                                <i class="bi bi-pen"></i>
                            </a>
                        @endcan

                        @can('role-delete')
                            <form method="POST" action="{{ route('backend.roles.destroy', $role->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-neutral">
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
    {!! $roles->links('pagination::bootstrap-5') !!}
    {{-- <div class="card-footer border-0 py-5">
        <span class="text-muted text-sm">Showing 10 items out of 250 results found</span>
    </div> --}}
</div>
@endsection
