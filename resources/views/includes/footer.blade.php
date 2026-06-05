{{-- ══ SCRIPTS ══ --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')

<script>
// ── SweetAlert Helpers ──
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

// ── Flash messages ──
@if(session('success'))
    Swal.fire({
        icon: 'success', title: 'Success!',
        text: '{{ addslashes(session('success')) }}',
        timer: 3000, showConfirmButton: false,
        toast: true, position: 'top-end'
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error', title: 'Error',
        text: '{{ addslashes(session('error')) }}'
    });
@endif

// ── Swipe to close sidebar (mobile) ──
(function() {
    let touchStartX = 0;
    document.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].clientX; }, { passive: true });
    document.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        const sb = document.getElementById('dashSidebar');
        // Swipe left to close
        if (diff > 60 && sb?.classList.contains('open')) closeSidebar();
        // Swipe right from left edge to open
        if (diff < -60 && touchStartX < 30 && !sb?.classList.contains('open')) toggleSidebar();
    }, { passive: true });
})();

// ── Prevent body scroll when sidebar open (mobile) ──
const sbObserver = new MutationObserver(() => {
    const sb = document.getElementById('dashSidebar');
    if (sb) document.body.style.overflow = sb.classList.contains('open') ? 'hidden' : '';
});
const sb = document.getElementById('dashSidebar');
if (sb) sbObserver.observe(sb, { attributes: true, attributeFilter: ['class'] });
</script>

</body>
</html>
