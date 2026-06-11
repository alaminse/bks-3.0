@php $admin = Auth::user(); @endphp

<nav class="navbar show navbar-vertical h-lg-screen navbar-expand-lg px-0 py-0"
    id="navbarVertical">
    <div class="container-fluid flex-lg-column align-items-stretch px-0">

        {{-- Mobile topbar --}}
        <div class="d-flex d-lg-none align-items-center justify-content-between px-3 py-2 w-100"
            style="border-bottom:1px solid rgba(255,255,255,0.06);">
            <a href="{{ route('backend.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="max-height:28px;">
            </a>
            <button class="navbar-toggler border-0 p-1" type="button"
                data-bs-toggle="collapse" data-bs-target="#sidebarCollapse"
                aria-controls="sidebarCollapse" aria-expanded="false"
                style="color:rgba(255,255,255,0.7);font-size:1.25rem;">
                <i class="bi bi-list"></i>
            </button>
        </div>

        {{-- Desktop logo --}}
        <a class="navbar-brand d-none d-lg-flex align-items-center gap-2 px-4 py-3 mb-0"
            href="{{ route('backend.dashboard') }}"
            style="border-bottom:1px solid rgba(255,255,255,0.06);">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="max-height:32px;">
        </a>

        {{-- User card (desktop) --}}
        <div class="d-none d-lg-block px-3 py-2" style="border-bottom:1px solid rgba(255,255,255,0.06);">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle"
                    id="sidebarAvatar" data-bs-toggle="dropdown" aria-expanded="false">
                    <div style="width:34px;height:34px;border-radius:50%;background:var(--ad-accent);display:flex;align-items:center;justify-content:center;font-size:0.78rem;font-weight:700;color:#fff;flex-shrink:0;">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                    <span class="fw-semibold">{{ $admin->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarAvatar">
                    <li><a href="#" class="dropdown-item"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); confirmLogout();">
                            <i class="bi bi-box-arrow-left me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Collapse nav --}}
        <div class="collapse navbar-collapse" id="sidebarCollapse">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('backend.dashboard*') ? 'active' : '' }}"
                        href="{{ route('backend.dashboard') }}">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>

                {{-- Users --}}
                @php $userActive = request()->routeIs('backend.users*') || request()->routeIs('backend.roles*'); @endphp
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $userActive ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#userSubmenu" role="button"
                        aria-expanded="{{ $userActive ? 'true' : 'false' }}">
                        <span><i class="bi bi-people"></i> User Management</span>
                    </a>
                    <div class="collapse {{ $userActive ? 'show' : '' }}" id="userSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.users.index') ? 'active':'' }}" href="{{ route('backend.users.index') }}">Users</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.roles.*') ? 'active':'' }}" href="{{ route('backend.roles.index') }}">Role Permissions</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Transactions --}}
                @php $txActive = request()->routeIs('backend.deposits*') || request()->routeIs('backend.withdrawals*'); @endphp
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $txActive ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#txSubmenu" role="button"
                        aria-expanded="{{ $txActive ? 'true' : 'false' }}">
                        <span><i class="bi bi-receipt"></i> Transaction</span>
                    </a>
                    <div class="collapse {{ $txActive ? 'show' : '' }}" id="txSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.deposits.*') ? 'active':'' }}" href="{{ route('backend.deposits.index') }}">Deposits</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.withdrawals.*') ? 'active':'' }}" href="{{ route('backend.withdrawals.index') }}">Withdrawals</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Tasks --}}
                @php $taskActive = request()->routeIs('backend.tasks*'); @endphp
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $taskActive ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#taskSubmenu" role="button"
                        aria-expanded="{{ $taskActive ? 'true' : 'false' }}">
                        <span><i class="bi bi-list-task"></i> Task Management</span>
                    </a>
                    <div class="collapse {{ $taskActive ? 'show' : '' }}" id="taskSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.tasks.submissions') ? 'active':'' }}" href="{{ route('backend.tasks.submissions') }}"><i class="bi bi-check-circle"></i> Submissions</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.tasks.index') ? 'active':'' }}" href="{{ route('backend.tasks.index') }}"><i class="bi bi-list-ul"></i> All Tasks</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.tasks.create') ? 'active':'' }}" href="{{ route('backend.tasks.create') }}"><i class="bi bi-plus-circle"></i> Create Task</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.tasks.assign') ? 'active':'' }}" href="{{ route('backend.tasks.assign') }}"><i class="bi bi-link-45deg"></i> Assign to Packages</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Referrals --}}
                @php $refActive = request()->routeIs('backend.referrals*'); @endphp
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $refActive ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#refSubmenu" role="button"
                        aria-expanded="{{ $refActive ? 'true' : 'false' }}">
                        <span><i class="bi bi-diagram-3"></i> Referrals</span>
                    </a>
                    <div class="collapse {{ $refActive ? 'show' : '' }}" id="refSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.referrals.settings') ? 'active':'' }}" href="{{ route('backend.referrals.settings') }}"><i class="bi bi-gear"></i> Commission Settings</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.referrals.earnings') ? 'active':'' }}" href="{{ route('backend.referrals.earnings') }}"><i class="bi bi-cash-coin"></i> Earnings</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Partners --}}
                @php $partActive = request()->routeIs('backend.companies*') || request()->routeIs('backend.partner-shares*') || request()->routeIs('backend.profits*'); @endphp
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $partActive ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#partSubmenu" role="button"
                        aria-expanded="{{ $partActive ? 'true' : 'false' }}">
                        <span><i class="bi bi-building"></i> Partners</span>
                    </a>
                    <div class="collapse {{ $partActive ? 'show' : '' }}" id="partSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.companies.*') ? 'active':'' }}" href="{{ route('backend.companies.index') }}"><i class="bi bi-building-fill"></i> Company</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.partner-shares.*') ? 'active':'' }}" href="{{ route('backend.partner-shares.index') }}"><i class="bi bi-people"></i> Partner Shares</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('backend.profits.*') ? 'active':'' }}" href="{{ route('backend.profits.index') }}"><i class="bi bi-cash-stack"></i> Profits</a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('backend.packages.*') ? 'active':'' }}" href="{{ route('backend.packages.index') }}">
                        <i class="bi bi-box-seam"></i> Packages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('backend.images.*') ? 'active':'' }}" href="{{ route('backend.images.index') }}">
                        <i class="bi bi-images"></i> Featured Image
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('backend.contacts.*') ? 'active':'' }}" href="{{ route('backend.contacts.index') }}">
                        <i class="bi bi-chat-dots"></i> Messages
                    </a>
                </li>

            </ul>

            <hr class="navbar-divider my-3" style="opacity:0.15;">
            <div class="mt-auto"></div>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#"
                        style="color:rgba(248,113,113,0.7) !important;"
                        onclick="event.preventDefault(); confirmLogout();">
                        <i class="bi bi-box-arrow-left"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('backend.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>

        </div>{{-- /collapse --}}
    </div>
</nav>
