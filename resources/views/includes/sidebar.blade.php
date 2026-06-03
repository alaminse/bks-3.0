@php $user = Auth::user(); @endphp

<aside class="dash-sidebar" id="dashSidebar">

    {{-- Logo --}}
    <div class="sb-logo">
        <div class="sb-logo-mark">T</div>
        <span class="sb-logo-text">TopTrade</span>
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
                <div class="sb-user-bal">{{ $user->wallet_balance ?? '$0.00' }}</div>
            </div>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sb-nav">

        <div class="sb-section-label">Main</div>

        <a href="{{ route('dashboard') }}" class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i> Dashboard
        </a>

        {{-- Wallet submenu --}}
        <a class="sb-link {{ request()->routeIs('wallet*') ? 'active' : '' }} sb-has-sub" data-bs-toggle="collapse"
            href="#walletSub" role="button" aria-expanded="{{ request()->routeIs('wallet*') ? 'true' : 'false' }}">
            <i class="bi bi-wallet2"></i> Wallet
            <i class="bi bi-chevron-down ms-auto" style="font-size:0.7rem;"></i>
        </a>
        <div class="collapse {{ request()->routeIs('wallet*') ? 'show' : '' }}" id="walletSub">
            <div style="padding:4px 0 4px 28px;">
                <a href="{{ route('wallet.index') }}"
                    class="sb-link {{ request()->routeIs('wallet.index') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:7px 12px;">
                    <i class="bi bi-grid-1x2"></i> My Wallet
                </a>
                <a href="{{ route('wallet.transactions') }}"
                    class="sb-link {{ request()->routeIs('wallet.transactions') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:7px 12px;">
                    <i class="bi bi-clock-history"></i> Transactions
                </a>
            </div>
        </div>

        <div class="sb-section-label">Earn</div>

        {{-- Packages submenu --}}
        <a class="sb-link {{ request()->routeIs('packages*') ? 'active' : '' }} sb-has-sub" data-bs-toggle="collapse"
            href="#packagesSub" role="button"
            aria-expanded="{{ request()->routeIs('packages*') ? 'true' : 'false' }}">
            <i class="bi bi-box-seam-fill"></i> Packages
            <i class="bi bi-chevron-down ms-auto" style="font-size:0.7rem;"></i>
        </a>
        <div class="collapse {{ request()->routeIs('packages*') ? 'show' : '' }}" id="packagesSub">
            <div style="padding:4px 0 4px 28px;">
                <a href="{{ route('packages.my') }}"
                    class="sb-link {{ request()->routeIs('packages.my') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:7px 12px;">
                    <i class="bi bi-collection"></i> My Packages
                </a>
                <a href="{{ route('packages.index') }}"
                    class="sb-link {{ request()->routeIs('packages.index') ? 'active' : '' }}"
                    style="font-size:0.82rem;padding:7px 12px;">
                    <i class="bi bi-grid"></i> All Packages
                </a>
            </div>
        </div>

        <a href="{{ route('tasks.index') }}" class="sb-link {{ request()->routeIs('tasks*') ? 'active' : '' }}">
            <i class="bi bi-list-check"></i> Tasks
        </a>

        <div class="sb-section-label">More</div>

        <a href="{{ route('referrals.index') }}"
            class="sb-link {{ request()->routeIs('referrals*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Referrals
        </a>

        <a href="{{ route('profile.index') }}" class="sb-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i> My Account
        </a>

    </nav>

    {{-- Logout --}}
    <div class="sb-footer">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button" class="sb-logout"
                onclick="confirmFormSubmit('logout-form', {
                    title: 'Logout?',
                    text: 'Are you sure you want to logout?',
                    confirmText: 'Yes, Logout'
                })">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>

        <form id="mob-logout-form" action="{{ route('logout') }}" method="POST" class="mob-logout-form">
            @csrf
            <button type="button" class="mob-nav-item"
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

</aside>
