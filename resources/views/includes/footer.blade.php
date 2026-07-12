<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/sweetalert-helper.js') }}"></script>

@stack('scripts')

<script>
    
function confirmFormSubmit(formId, opts = {}) {
    Swal.fire({
        title:             opts.title       || 'Are you sure?',
        text:              opts.text        || '',
        icon:              opts.icon        || 'question',
        showCancelButton:  true,
        confirmButtonText: opts.confirmText || 'Yes',
        cancelButtonText:  opts.cancelText  || 'Cancel',
    }).then(result => {
        if (result.isConfirmed) document.getElementById(formId)?.submit();
    });
}

function confirmLogout() {
    confirmFormSubmit('logout-form', {
        title: 'Logout?',
        text:  'Are you sure you want to logout?',
        confirmText: 'Yes, Logout',
        icon: 'warning'
    });
}

@if(session('success'))
    Swal.fire({
        icon: 'success', title: 'Success!',
        text: '{{ addslashes(session("success")) }}',
        timer: 3000, showConfirmButton: false,
        toast: true, position: 'top-end'
    });
@endif
@if(session('error'))
    Swal.fire({
        icon: 'error', title: 'Error',
        text: '{{ addslashes(session("error")) }}'
    });
@endif
</script>

</body>
</html>
