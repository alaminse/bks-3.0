@extends('layouts.backend')
@section('title') Users Management @endsection
@section('content')
    <!-- Header -->
    @include('includes.header', [
        'pageTitle' => 'Users Management',
        'createRoute' => route('backend.users.create'),
        'createText' => 'Add User',
        'createPermission' => 'user-create'
    ])

<div class="card shadow border-0 mb-7">
    <div class="card-header">
        <h5 class="mb-0">Users</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-nowrap">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Is Demo</th>
                    <th width="20%" class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <span class="user-name">{{ $user->name }}</span>
                        {{-- @if($user->isDemo)
                            <span class="badge bg-warning text-dark ms-2 demo-badge">DEMO</span>
                        @endif --}}
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                            <label class="badge bg-success">{{ $v }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <button type="button"
                                class="btn btn-sm btn-square toggle-demo-btn {{ $user->isDemo ? 'btn-success' : 'btn-warning' }}"
                                data-user-id="{{ $user->id }}"
                                title="{{ $user->isDemo ? 'Remove Demo Status' : 'Make Demo User' }}">
                            {{-- Main button icon --}}
                            <i class="btn-icon {{ $user->isDemo ? 'bi-check-circle' : 'bi-exclamation-triangle' }}"></i>
                            {{-- Loading spinner --}}
                            <i class="spinner-border spinner-border-sm d-none" role="status"></i>
                        </button>
                    </td>
                    <td class="text-end">
                        {{-- Show / Edit / Delete Buttons --}}
                        <a class="btn btn-sm btn-neutral" href="{{ route('backend.users.show',$user->id) }}" title="Show"><i class="bi bi-eye"></i></a>
                        <a class="btn btn-neutral btn-sm" href="{{ route('backend.users.edit',$user->id) }}"><i class="bi bi-pen"></i></a>
                        <form method="POST" action="{{ route('backend.users.destroy', $user->id) }}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-square btn-neutral text-danger-hover" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {!! $data->links('pagination::bootstrap-5') !!}
</div>
@endsection

@push('scripts')
<script>
 $(function() {
    // Handle the toggle demo button click
    $('.toggle-demo-btn').on('click', function(e) {
        e.preventDefault();

        const $button = $(this);
        const userId = $button.data('user-id');
        const isCurrentlyDemo = $button.hasClass('btn-success'); // If it's green, it's a demo user
        const actionText = isCurrentlyDemo ? 'remove demo status from' : 'make a demo user';

        // Confirmation dialog
        if (!confirm(`Are you sure you want to ${actionText} this user?`)) {
            return;
        }

        // Show loading state
        $button.prop('disabled', true);
        $button.find('.btn-icon').addClass('d-none');
        $button.find('.spinner-border').removeClass('d-none');

        // AJAX request to the server
        $.ajax({
            url: `/bks/users/${userId}/toggle-demo`, // The route we will create
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // On success, update the button and UI
                const $userCell = $button.closest('tr').find('td:nth-child(2)');
                const $demoBadge = $userCell.find('.demo-badge');

                if (response.isDemo) {
                    // User is now a DEMO user
                    $button.removeClass('btn-warning').addClass('btn-success');
                    $button.find('.btn-icon').removeClass('bi-exclamation-triangle').addClass('bi-check-circle');
                    $button.attr('title', 'Remove Demo Status');

                    // Add DEMO badge if it doesn't exist
                    if ($demoBadge.length === 0) {
                        $userCell.append(' <span class="badge bg-warning text-dark ms-2 demo-badge">DEMO</span>');
                    }
                } else {
                    // User is now a REAL user
                    $button.removeClass('btn-success').addClass('btn-warning');
                    $button.find('.btn-icon').removeClass('bi-check-circle').addClass('bi-exclamation-triangle');
                    $button.attr('title', 'Make Demo User');

                    // Remove DEMO badge if it exists
                    if ($demoBadge.length > 0) {
                        $demoBadge.remove();
                    }
                }

                showNotification(response.message, 'success');
            },
            error: function(xhr) {
                // On error, show an alert
                const errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                showNotification(errorMessage, 'danger');
            },
            complete: function() {
                // Hide loading state
                $button.prop('disabled', false);
                $button.find('.btn-icon').removeClass('d-none');
                $button.find('.spinner-border').addClass('d-none');
            }
        });
    });

    // Simple notification function
    function showNotification(message, type) {
        // Remove any existing alerts
        $('.alert-notification').remove();

        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show alert-notification" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        $('body').append(alertHtml);

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            $('.alert-notification').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }
});
</script>
@endpush
