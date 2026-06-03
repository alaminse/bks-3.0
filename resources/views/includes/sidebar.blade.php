@php $user = Auth::user(); @endphp

<nav class="cy-sidebar navbar show navbar-vertical h-lg-screen navbar-expand-lg" id="navbarVertical">
    <div class="cy-sidebar-inner">
        {{-- ══ MOBILE TOPBAR ══ --}}
        <div class="cy-topbar d-lg-none">
            <a href="{{ route('dashboard') }}" class="cy-brand">
                <img src="{{ asset('assets/images/logo.png') }}" alt="{{ env('APP_NAME') }}">
            </a>
            <span class="cy-topbar-spacer"></span>
            <button class="cy-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sidebarCollapse"
                aria-controls="sidebarCollapse"
                aria-expanded="false">
                <i class="bi bi-layout-sidebar"></i>
            </button>
        </div>

        {{-- ══ LOGO (desktop) ══ --}}
        <div class="cy-logo d-none d-lg-flex">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="{{ env('APP_NAME') }}">
            </a>
        </div>

        {{-- ══ COLLAPSE WRAPPER ══ --}}
        <div class="collapse navbar-collapse cy-collapse" id="sidebarCollapse">

            {{-- USER CARD --}}
            <div class="dropdown cy-user-card">
                <a href="#"
                   class="cy-user-toggle dropdown-toggle"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">
                    <div class="cy-avatar-wrap">
                        <img src="{{ $user->avatar_url }}"
                             alt="Avatar"
                             class="cy-avatar"
                             id="avatarPreview">
                        <span class="cy-online-dot"></span>
                    </div>
                    <div class="cy-user-meta">
                        <span class="cy-user-name">{{ $user->name }}</span>
                        <span class="cy-user-bal">
                            <i class="bi bi-wallet2"></i> {{ $user?->wallet_balance }}
                        </span>
                    </div>
                    <i class="bi bi-chevron-down cy-chevron"></i>
                </a>
                <ul class="dropdown-menu cy-dropdown">
                    <li class="cy-dropdown-info">
                        <div class="cy-di-name">{{ $user->name }}</div>
                        <div class="cy-di-email">{{ $user->email }}</div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('wallet.index') }}"><i class="bi bi-wallet2"></i> My Wallet</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item cy-logout-item" href="#"
                           onclick="event.preventDefault(); confirmLogout();">
                            <i class="bi bi-box-arrow-left"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            {{-- SECTION: MAIN --}}
            <div class="cy-section-label">// Main</div>
            <ul class="navbar-nav cy-nav">

                <li class="nav-item">
                    <a class="cy-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <span class="cy-icon"><i class="bi bi-house-fill"></i></span>
                        <span>Dashboard</span>
                        @if(request()->routeIs('dashboard*'))<span class="cy-pip"></span>@endif
                    </a>
                </li>

                {{-- Wallet --}}
                <li class="nav-item">
                    <a class="cy-link cy-has-sub {{ request()->routeIs('wallet*') ? 'active' : '' }}"
                       data-bs-toggle="collapse" href="#walletSubmenu" role="button"
                       aria-expanded="{{ request()->routeIs('wallet*') ? 'true' : 'false' }}">
                        <span class="cy-icon"><i class="bi bi-wallet2"></i></span>
                        <span>Wallet</span>
                        <i class="bi bi-chevron-down cy-arrow"></i>
                    </a>
                    <div class="collapse cy-sub {{ request()->routeIs('wallet*') ? 'show' : '' }}" id="walletSubmenu">
                        <a class="cy-sublink {{ request()->routeIs('wallet.index') ? 'active' : '' }}"
                           href="{{ route('wallet.index') }}">
                            <i class="bi bi-grid-1x2"></i> My Wallet
                        </a>
                        <a class="cy-sublink {{ request()->routeIs('wallet.transactions') ? 'active' : '' }}"
                           href="{{ route('wallet.transactions') }}">
                            <i class="bi bi-clock-history"></i> All Transactions
                        </a>
                    </div>
                </li>

                {{-- Packages --}}
                <li class="nav-item">
                    <a class="cy-link cy-has-sub {{ request()->routeIs('packages*') ? 'active' : '' }}"
                       data-bs-toggle="collapse" href="#packagesSubmenu" role="button"
                       aria-expanded="{{ request()->routeIs('packages*') ? 'true' : 'false' }}">
                        <span class="cy-icon"><i class="bi bi-box-seam-fill"></i></span>
                        <span>Packages</span>
                        <i class="bi bi-chevron-down cy-arrow"></i>
                    </a>
                    <div class="collapse cy-sub {{ request()->routeIs('packages*') ? 'show' : '' }}" id="packagesSubmenu">
                        <a class="cy-sublink {{ request()->routeIs('packages.my') ? 'active' : '' }}"
                           href="{{ route('packages.my') }}">
                            <i class="bi bi-collection"></i> My Packages
                        </a>
                        <a class="cy-sublink {{ request()->routeIs('packages.index') ? 'active' : '' }}"
                           href="{{ route('packages.index') }}">
                            <i class="bi bi-grid"></i> All Packages
                        </a>
                    </div>
                </li>

                {{-- Tasks --}}
                <li class="nav-item">
                    <a class="cy-link {{ request()->routeIs('tasks*') ? 'active' : '' }}"
                       href="{{ route('tasks.index') }}">
                        <span class="cy-icon"><i class="bi bi-list-check"></i></span>
                        <span>Tasks</span>
                        @if(request()->routeIs('tasks*'))<span class="cy-pip"></span>@endif
                    </a>
                </li>

                {{-- Referrals --}}
                <li class="nav-item">
                    <a class="cy-link {{ request()->routeIs('referrals*') ? 'active' : '' }}"
                       href="{{ route('referrals.index') }}">
                        <span class="cy-icon"><i class="bi bi-people-fill"></i></span>
                        <span>Referrals</span>
                        @if(request()->routeIs('referrals*'))<span class="cy-pip"></span>@endif
                    </a>
                </li>

            </ul>

            {{-- DIVIDER --}}
            <div class="cy-divider"></div>

            {{-- SECTION: ACCOUNT --}}
            <div class="cy-section-label">// Account</div>
            <ul class="navbar-nav cy-nav">

                <li class="nav-item">
                    <a class="cy-link {{ request()->routeIs('profile*') ? 'active' : '' }}"
                       href="{{ route('profile.index') }}">
                        <span class="cy-icon"><i class="bi bi-person-fill"></i></span>
                        <span>My Account</span>
                        @if(request()->routeIs('profile*'))<span class="cy-pip"></span>@endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="cy-link cy-link-logout" href="#"
                       onclick="event.preventDefault(); confirmLogout();">
                        <span class="cy-icon"><i class="bi bi-box-arrow-left"></i></span>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>

            {{-- BOTTOM GLOW STRIP --}}
            <div class="cy-bottom-strip"></div>

        </div>{{-- end collapse --}}
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</nav>
