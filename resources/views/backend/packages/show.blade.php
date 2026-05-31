@extends('layouts.backend')

@section('title')
    Package Details
@endsection

@section('content')
    <div class="container-fluid">

        @include('includes.header', [
            'pageTitle' => 'Package Details',
            'backRoute' => route('backend.packages.index'),
            'backText' => 'Back to Packages',
        ])

        <div class="card shadow border-0">
            <div class="card-body">

                <table class="table table-bordered">
                    <tr>
                        <th width="200">Name</th>
                        <td>{{ $package->name }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $package->description }}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{{ $package->price }}</td>
                    </tr>
                    <tr>
                        <th>Daily Tasks</th>
                        <td>{{ $package->daily_tasks }}</td>
                    </tr>
                    <tr>
                        <th>Daily Earning</th>
                        <td>{{ $package->daily_earning }}</td>
                    </tr>
                    <tr>
                        <th>Duration</th>
                        <td>{{ $package->duration_days === 0 ? 'Unlimited' : '' }} days</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if ($package->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $package->created_at->format('d M Y h:i A') }}</td>
                    </tr>
                </table>

            </div>
        </div>

    </div>
@endsection
