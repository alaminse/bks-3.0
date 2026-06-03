<nav class="navbar show navbar-vertical h-lg-screen navbar-expand-lg px-0 py-3 navbar-light bg-white border-bottom border-bottom-lg-0 border-end-lg"
    id="navbarVertical">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler ms-n2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse"
            aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        @php
            $user = Auth::user();
        @endphp

        <a class="navbar-brand py-lg-2 mb-lg-5 px-lg-6 me-0" href="{{ route('backend.dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="...">
        </a>
        <!-- Dropdown -->
        <div class="dropdown mb-3">
            <a href="#" id="sidebarAvatar" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-parent-child">
                    <img alt="{{ $user->name }}"
                        src="https://images.unsplash.com/photo-1548142813-c348350df52b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80"
                        class="avatar avatar-sm rounded-circle">
                    <span class="avatar-child avatar-badge bg-success"></span>
                </div>
                <div class="ms-2 d-none d-lg-block">
                    <span class="d-block text-sm fw-semibold">
                        {{ $user->name }}
                    </span>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarAvatar">
                <li><a href="#" class="dropdown-item">Profile</a></li>
                <li><a href="#" class="dropdown-item">Settings</a></li>
                <li><a href="#" class="dropdown-item">Billing</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <!-- Logout button in dropdown -->
                <li>
                    <a class="dropdown-item logout-btn" href="#"
                        onclick="event.preventDefault(); confirmLogout();">
                        <i class="bi bi-box-arrow-left"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidebarCollapse">
            <!-- Navigation -->
            <ul class="navbar-nav">
                {{-- For Admin --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('backend.dashboard*') ? 'active' : '' }}"
                        href="{{ route('backend.dashboard') }}">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>

                @php
                    $userMenuActive = request()->routeIs('backend.users*') || request()->routeIs('backend.roles*');
                @endphp

                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $userMenuActive ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#userSubmenu" role="button"
                        aria-expanded="{{ $userMenuActive ? 'true' : 'false' }}" aria-controls="userSubmenu">
                        <span>
                            <i class="bi bi-people"></i> User Management
                        </span>
                    </a>

                    <div class="collapse {{ $userMenuActive ? 'show' : '' }}" id="userSubmenu">
                        <ul class="nav flex-column ms-3">

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.users.index') ? 'active' : '' }}"
                                    href="{{ route('backend.users.index') }}">
                                    Users
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.roles.index') ? 'active' : '' }}"
                                    href="{{ route('backend.roles.index') }}">
                                    Role Permissions
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                @php
                    $transectionsMenuActive = request()->routeIs('backend.deposits*') || request()->routeIs('backend.withdrawals*');
                @endphp
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $transectionsMenuActive ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#transectionSubmenu" role="button"
                        aria-expanded="{{ $transectionsMenuActive ? 'true' : 'false' }}" aria-controls="transectionSubmenu">
                        <span>
                            <i class="bi bi-receipt"></i> Transection
                        </span>
                    </a>

                    <div class="collapse {{ $transectionsMenuActive ? 'show' : '' }}" id="transectionSubmenu">
                        <ul class="nav flex-column ms-3">

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.deposits.*') ? 'active' : '' }}"
                                    href="{{ route('backend.deposits.index') }}">
                                    Deposits
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.withdrawals.*') ? 'active' : '' }}"
                                    href="{{ route('backend.withdrawals.index') }}">
                                    Withdrawals
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('backend.tasks*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#taskSubmenu" role="button"
                        aria-expanded="{{ request()->routeIs('backend.tasks*') ? 'true' : 'false' }}"
                        aria-controls="taskSubmenu">
                        <span>
                            <i class="bi bi-list-task"></i> Task Management
                        </span>
                    </a>

                    <div class="collapse {{ request()->routeIs('backend.tasks*') ? 'show' : '' }}" id="taskSubmenu">
                        <ul class="nav flex-column ms-3">

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.tasks.submissions') ? 'active' : '' }}"
                                    href="{{ route('backend.tasks.submissions') }}">
                                    <i class="bi bi-check-circle"></i> Submissions
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.tasks.index') ? 'active' : '' }}"
                                    href="{{ route('backend.tasks.index') }}">
                                    <i class="bi bi-list-ul"></i> All Tasks
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.tasks.create') ? 'active' : '' }}"
                                    href="{{ route('backend.tasks.create') }}">
                                    <i class="bi bi-plus-circle"></i> Create Task
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.tasks.assign') ? 'active' : '' }}"
                                    href="{{ route('backend.tasks.assign') }}">
                                    <i class="bi bi-link-45deg"></i> Assign to Packages
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                @php
                    $referralMenuActive = request()->routeIs('backend.referrals*');
                @endphp

                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $referralMenuActive ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#referralSubmenu" role="button"
                        aria-expanded="{{ $referralMenuActive ? 'true' : 'false' }}"
                        aria-controls="referralSubmenu">
                        <span>
                            <i class="bi bi-diagram-3"></i> Referrals
                        </span>
                    </a>

                    <div class="collapse {{ $referralMenuActive ? 'show' : '' }}" id="referralSubmenu">
                        <ul class="nav flex-column ms-3">

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.referrals.settings') ? 'active' : '' }}"
                                    href="{{ route('backend.referrals.settings') }}">
                                    <i class="bi bi-gear"></i> Commission Settings
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.referrals.earnings') ? 'active' : '' }}"
                                    href="{{ route('backend.referrals.earnings') }}">
                                    <i class="bi bi-cash-coin"></i> Earnings
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                @php
                    $partnerMenuActive = request()->routeIs('backend.companies*') || request()->routeIs('backend.partner-shares*');
                @endphp

                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $partnerMenuActive ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#partnerSubmenu" role="button"
                        aria-expanded="{{ $partnerMenuActive ? 'true' : 'false' }}" aria-controls="partnerSubmenu">
                        <span>
                            <i class="bi bi-people"></i> Partners
                        </span>
                    </a>

                    <div class="collapse {{ $partnerMenuActive ? 'show' : '' }}" id="partnerSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.companies.*') ? 'active' : '' }}"
                                    href="{{ route('backend.companies.index') }}">
                                    <i class="bi bi-check-circle"></i> Company
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.partner-shares.*') ? 'active' : '' }}" href="{{ route('backend.partner-shares.index') }}">
                                    <i class="bi bi-people"></i> Partner Shares
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('backend.profits.*') ? 'active' : '' }}" href="{{ route('backend.profits.index') }}">
                                    <i class="bi bi-cash-stack"></i> Profits
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('backend.packages.*') ? 'active' : '' }}" href="{{ route('backend.packages.index') }}">
                        Packages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('backend.images.*') ? 'active' : '' }}" href="{{ route('backend.images.index') }}">
                        Featured Image
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('backend.contacts.*') ? 'active' : '' }}" href="{{ route('backend.contacts.index') }}">
                        Message
                    </a>
                </li>

            </ul>
            <!-- Divider -->
            <hr class="navbar-divider my-5 opacity-20">
            <!-- Push content down -->
            <div class="mt-auto"></div>
            <!-- User (md) -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link logout-btn" href="#" onclick="event.preventDefault(); confirmLogout();">
                        <i class="bi bi-box-arrow-left"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('backend.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
