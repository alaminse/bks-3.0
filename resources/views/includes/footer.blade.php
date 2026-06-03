<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom SweetAlert Helper -->
<script src="{{ asset('assets/js/sweetalert-helper.js') }}"></script>

@stack('scripts')

<script>
    $(document).ready(function () {
        $('#navbarDropdown').mouseenter(function () {
            $('.dropdown-menu').stop(true, true).slideDown(300);
        });

        $('.dropdown-menu').mouseleave(function () {
            $(this).stop(true, true).slideUp(300);
        });
    });

    // Show success after redirect (in controller, use session flash)
    @if(session('success'))
        showSuccess('Success!', '{{ session('success') }}');
    @endif

    @if(session('error'))
        showError('Error!', '{{ session('error') }}');
    @endif
</script>
</body>

</html>
