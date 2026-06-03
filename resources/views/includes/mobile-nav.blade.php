<nav class="mobile-bottom-nav">
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
        <a href="{{ route('profile.index') }}"
            class="mob-nav-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i>
            <span>Profile</span>
        </a>
    </div>
</nav>

<script src="{{ asset('assets/js/theme.js') }}"></script>

<script>
function toggleSidebar() {
    document.getElementById('dashSidebar').classList.toggle('open');
    document.getElementById('sbOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('dashSidebar').classList.remove('open');
    document.getElementById('sbOverlay').classList.remove('open');
}
document.querySelectorAll('.sb-link').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 992) {
            if (!link.classList.contains('sb-has-sub')) closeSidebar();
        }
    });
});
</script>
