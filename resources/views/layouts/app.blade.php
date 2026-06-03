
@include('includes.head')

<div class="dash-wrap">

    {{-- SIDEBAR --}}
    @include('includes.sidebar')

    {{-- OVERLAY --}}
    <div class="sb-overlay" id="sbOverlay" onclick="closeSidebar()"></div>

    {{-- MAIN --}}
    <div class="dash-main">

        {{-- TOPBAR --}}
        <div class="dash-topbar">
            <div class="topbar-left">
                <button class="topbar-ham" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="topbar-page-title">@yield('page-title', 'Dashboard')</div>
                    <div class="topbar-page-sub">Welcome, {{ auth()->user()->name }}!</div>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-bal">
                    <div class="topbar-bal-lbl">Balance</div>
                    <div class="topbar-bal-val">{{ auth()->user()->wallet_balance ?? '$0.00' }}</div>
                </div>
                <div class="topbar-notif">
                    <i class="bi bi-bell"></i>
                    <div class="notif-dot"></div>
                </div>
                <a href="{{ route('profile.index') }}">
                    <div class="topbar-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </a>
            </div>
        </div>

        {{-- PAGE CONTENT --}}
        <div class="dash-content">
            @yield('content')
        </div>
    </div>
</div>

@include('includes.mobile-nav')
@include('includes.footer')
