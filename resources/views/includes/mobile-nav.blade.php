{{-- ══ MOBILE BOTTOM NAV ══ --}}
<nav class="mobile-bottom-nav" id="mobileBottomNav">
    <div class="mob-nav-inner">

        <a href="{{ route('dashboard') }}"
            class="mob-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i>
            <span>Home</span>
        </a>

        <a href="{{ route('wallet.index') }}"
            class="mob-nav-item {{ request()->routeIs('wallet*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i>
            <span>Wallet</span>
        </a>

        {{-- Center CTA --}}
        <a href="{{ route('tasks.index') }}" class="mob-nav-item cta-item">
            <div class="mob-nav-cta-wrap">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <span>Tasks</span>
        </a>

        <a href="{{ route('referrals.index') }}"
            class="mob-nav-item {{ request()->routeIs('referrals*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Refer</span>
        </a>

        {{-- Logout --}}
        <form id="mob-logout-form" action="{{ route('logout') }}" method="POST" class="mob-logout-form">
            @csrf
            <button type="button"
                class="mob-nav-item"
                onclick="confirmFormSubmit('mob-logout-form',{
                    title:'Logout?',
                    text:'Are you sure you want to logout?',
                    confirmText:'Yes, Logout',
                    icon:'warning'
                })"
                style="color:var(--red);">
                <i class="bi bi-box-arrow-right" style="font-size:22px;"></i>
                <span style="color:var(--red);">Logout</span>
            </button>
        </form>

    </div>
</nav>

{{-- Theme Switcher --}}
<script src="{{ asset('assets/js/theme.js') }}"></script>

{{-- Sidebar JS --}}
<script>
function toggleSidebar() {
    const sb  = document.getElementById('dashSidebar');
    const ov  = document.getElementById('sbOverlay');
    sb.classList.toggle('open');
    ov.classList.toggle('open');
    document.body.style.overflow = sb.classList.contains('open') ? 'hidden' : '';
}
function closeSidebar() {
    document.getElementById('dashSidebar')?.classList.remove('open');
    document.getElementById('sbOverlay')?.classList.remove('open');
    document.body.style.overflow = '';
}
// Close sidebar on nav link click (mobile)
document.querySelectorAll('.sb-link:not(.sb-has-sub)').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 992) closeSidebar();
    });
});
// Close on outside click / swipe
document.getElementById('sbOverlay')?.addEventListener('click', closeSidebar);
</script>
