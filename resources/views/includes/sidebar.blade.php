@php $user = Auth::user(); @endphp

<aside class="dash-sidebar" id="dashSidebar">

    {{-- Logo --}}
    <div class="sb-logo">
        <div class="sb-logo-mark">T</div>
        <span class="sb-logo-text">TopTrade</span>
        {{-- Close button (mobile) --}}
        <button onclick="closeSidebar()"
            style="display:none;margin-left:auto;background:none;border:none;color:var(--muted);font-size:1.2rem;cursor:pointer;padding:4px;"
            class="sb-close-btn">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- User Card --}}
    <div class="sb-user">
        <div class="sb-user-inner">
            <div class="sb-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
                <div class="sb-avatar-dot"></div>
            </div>
            <div style="overflow:hidden;flex:1;min-width:0;">
                <div class="sb-user-name">{{ $user->name }}</div>
                <div class="sb-user-bal">${{ number_format($user->wallet?->balance ?? 0, 2) }}</div>
            </div>
            <button onclick="closeSidebar()"
                style="background:none;border:none;color:var(--muted);cursor:pointer;padding:4px;display:none;"
                class="sb-close-btn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sb-nav">

        <div class="sb-section-label">Main</div>

        <a href="{{ route('dashboard') }}"
            class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i> Dashboard
        </a>

        {{-- Wallet submenu --}}
        <a class="sb-link sb-has-sub {{ request()->routeIs('wallet*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#walletSub" role="button"
            aria-expanded="{{ request()->routeIs('wallet*') ? 'true' : 'false' }}">
            <i class="bi bi-wallet2"></i>
            <span>Wallet</span>
            <i class="bi bi-chevron-down ms-auto" style="font-size:0.65rem;transition:transform 0.2s;"></i>
        </a>
        <div class="collapse {{ request()->routeIs('wallet*') ? 'show' : '' }}" id="walletSub">
            <div style="padding:2px 0 4px 14px;">
                <a href="{{ route('wallet.index') }}"
                    class="sb-link {{ request()->routeIs('wallet.index') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:8px 12px;">
                    <i class="bi bi-grid-1x2"></i> My Wallet
                </a>
                <a href="{{ route('wallet.deposit') }}"
                    class="sb-link {{ request()->routeIs('wallet.deposit') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:8px 12px;">
                    <i class="bi bi-plus-circle"></i> Deposit
                </a>
                <a href="{{ route('withdraw.index') }}"
                    class="sb-link {{ request()->routeIs('withdraw*') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:8px 12px;">
                    <i class="bi bi-send"></i> Withdraw
                </a>
                <a href="{{ route('wallet.transactions') }}"
                    class="sb-link {{ request()->routeIs('wallet.transactions') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:8px 12px;">
                    <i class="bi bi-clock-history"></i> Transactions
                </a>
            </div>
        </div>

        <div class="sb-section-label">Earn</div>

        {{-- Packages submenu --}}
        <a class="sb-link sb-has-sub {{ request()->routeIs('packages*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#packagesSub" role="button"
            aria-expanded="{{ request()->routeIs('packages*') ? 'true' : 'false' }}">
            <i class="bi bi-box-seam-fill"></i>
            <span>Packages</span>
            <i class="bi bi-chevron-down ms-auto" style="font-size:0.65rem;transition:transform 0.2s;"></i>
        </a>
        <div class="collapse {{ request()->routeIs('packages*') ? 'show' : '' }}" id="packagesSub">
            <div style="padding:2px 0 4px 14px;">
                <a href="{{ route('packages.my') }}"
                    class="sb-link {{ request()->routeIs('packages.my') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:8px 12px;">
                    <i class="bi bi-collection"></i> My Packages
                </a>
                <a href="{{ route('packages.index') }}"
                    class="sb-link {{ request()->routeIs('packages.index') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:8px 12px;">
                    <i class="bi bi-grid"></i> All Packages
                </a>
            </div>
        </div>

        <a href="{{ route('tasks.index') }}"
            class="sb-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
            <i class="bi bi-lightning-charge-fill"></i>
            <span>Daily Tasks</span>
        </a>

        <div class="sb-section-label">More</div>

        <a href="{{ route('referrals.index') }}"
            class="sb-link {{ request()->routeIs('referrals*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Referrals
        </a>

        <a href="{{ route('profile.index') }}"
            class="sb-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i> My Account
        </a>

    </nav>

    {{-- Logout --}}
    <div class="sb-footer">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button" class="sb-logout"
                onclick="confirmFormSubmit('logout-form',{
                    title:'Logout?',
                    text:'Are you sure you want to logout?',
                    confirmText:'Yes, Logout',
                    icon:'warning'
                })">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>

</aside>

<style>
/* Show close btn on mobile */
@media(max-width: 768px) {
    .sb-close-btn { display: block !important; }
    /* Rotate chevron when expanded */
    .sb-link[aria-expanded="true"] .bi-chevron-down { transform: rotate(180deg); }
}
</style>
