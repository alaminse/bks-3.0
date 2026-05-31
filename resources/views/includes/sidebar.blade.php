@php $user = Auth::user(); @endphp

<nav class="tt-sidebar" id="navbarVertical">
  <div class="tt-sidebar-inner">

    {{-- ── MOBILE TOPBAR ── --}}
    <div class="tt-topbar d-flex d-lg-none">
      <a href="{{ route('dashboard') }}" class="tt-sidebar-brand">
        <img src="{{ asset('assets/images/logo.png') }}" alt="{{ env('APP_NAME') }}"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <span class="tt-brand-text" style="display:none">Top<em>Trade</em></span>
      </a>
      <button class="tt-toggler" type="button"
              data-bs-toggle="collapse"
              data-bs-target="#ttSidebarCollapse"
              aria-controls="ttSidebarCollapse"
              aria-expanded="false"
              aria-label="Toggle navigation">
        <i class="bi bi-layout-sidebar-reverse"></i>
      </button>
    </div>

    {{-- ── DESKTOP LOGO ── --}}
    <div class="tt-logo d-none d-lg-flex">
      <a href="{{ route('dashboard') }}">
        <img src="{{ asset('assets/images/logo.png') }}" alt="{{ env('APP_NAME') }}"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <span class="tt-brand-text" style="display:none">Top<em>Trade</em></span>
      </a>
    </div>

    {{-- ── COLLAPSIBLE BODY ── --}}
    <div class="collapse navbar-collapse tt-sidebar-collapse" id="ttSidebarCollapse">

      {{-- USER STRIP --}}
      <div class="tt-user-strip dropdown">
        <a href="#" class="tt-user-toggle dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="tt-user-avatar-wrap">
            <img src="{{ $user->avatar_url }}" alt="Avatar" class="tt-user-avatar" id="avatarPreview">
            <span class="tt-user-status"></span>
          </div>
          <div class="tt-user-info">
            <span class="tt-user-name">{{ $user->name }}</span>
            <span class="tt-user-balance">
              <i class="bi bi-currency-dollar"></i>{{ $user?->wallet_balance }}
            </span>
          </div>
          <i class="bi bi-chevron-down tt-user-chevron"></i>
        </a>

        <ul class="dropdown-menu tt-user-menu">
          <li class="tt-user-menu-header">
            <div class="tt-um-name">{{ $user->name }}</div>
            <div class="tt-um-email">{{ $user->email }}</div>
          </li>
          <li><hr class="dropdown-divider tt-menu-divider"></li>
          <li>
            <a class="dropdown-item tt-menu-item" href="{{ route('profile.index') }}">
              <i class="bi bi-person-circle"></i> My Profile
            </a>
          </li>
          <li>
            <a class="dropdown-item tt-menu-item" href="{{ route('wallet.index') }}">
              <i class="bi bi-wallet2"></i> My Wallet
            </a>
          </li>
          <li><hr class="dropdown-divider tt-menu-divider"></li>
          <li>
            <a class="dropdown-item tt-menu-item tt-menu-logout" href="#"
               onclick="event.preventDefault(); confirmLogout();">
              <i class="bi bi-box-arrow-right"></i> Sign Out
            </a>
          </li>
        </ul>
      </div>

      {{-- ── NAV SECTION: MAIN ── --}}
      <div class="tt-nav-label">Main</div>
      <ul class="tt-nav">

        <li>
          <a href="{{ route('dashboard') }}"
             class="tt-nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <span class="tt-nav-icon"><i class="bi bi-grid-1x2-fill"></i></span>
            <span class="tt-nav-text">Dashboard</span>
            @if(request()->routeIs('dashboard*'))<span class="tt-nav-pip"></span>@endif
          </a>
        </li>

        {{-- Wallet --}}
        <li>
          <a href="#walletSub"
             class="tt-nav-link tt-has-sub {{ request()->routeIs('wallet*') ? 'active' : '' }}"
             data-bs-toggle="collapse" role="button"
             aria-expanded="{{ request()->routeIs('wallet*') ? 'true' : 'false' }}">
            <span class="tt-nav-icon"><i class="bi bi-wallet2"></i></span>
            <span class="tt-nav-text">Wallet</span>
            <i class="bi bi-chevron-down tt-nav-arrow"></i>
          </a>
          <div class="collapse tt-subnav {{ request()->routeIs('wallet*') ? 'show' : '' }}" id="walletSub">
            <a class="tt-sublink {{ request()->routeIs('wallet.index') ? 'active' : '' }}"
               href="{{ route('wallet.index') }}">
              <i class="bi bi-grid-1x2"></i> My Wallet
            </a>
            <a class="tt-sublink {{ request()->routeIs('wallet.transactions') ? 'active' : '' }}"
               href="{{ route('wallet.transactions') }}">
              <i class="bi bi-clock-history"></i> Transactions
            </a>
          </div>
        </li>

        {{-- Trading Plans --}}
        <li>
          <a href="#plansSub"
             class="tt-nav-link tt-has-sub {{ request()->routeIs('packages*') ? 'active' : '' }}"
             data-bs-toggle="collapse" role="button"
             aria-expanded="{{ request()->routeIs('packages*') ? 'true' : 'false' }}">
            <span class="tt-nav-icon"><i class="bi bi-bar-chart-line-fill"></i></span>
            <span class="tt-nav-text">Trading Plans</span>
            <i class="bi bi-chevron-down tt-nav-arrow"></i>
          </a>
          <div class="collapse tt-subnav {{ request()->routeIs('packages*') ? 'show' : '' }}" id="plansSub">
            <a class="tt-sublink {{ request()->routeIs('packages.my') ? 'active' : '' }}"
               href="{{ route('packages.my') }}">
              <i class="bi bi-collection"></i> My Plans
            </a>
            <a class="tt-sublink {{ request()->routeIs('packages.index') ? 'active' : '' }}"
               href="{{ route('packages.index') }}">
              <i class="bi bi-grid"></i> All Plans
            </a>
          </div>
        </li>

        {{-- Sessions (Tasks) --}}
        <li>
          <a href="{{ route('tasks.index') }}"
             class="tt-nav-link {{ request()->routeIs('tasks*') ? 'active' : '' }}">
            <span class="tt-nav-icon"><i class="bi bi-lightning-charge-fill"></i></span>
            <span class="tt-nav-text">Sessions</span>
            @if(request()->routeIs('tasks*'))<span class="tt-nav-pip"></span>@endif
          </a>
        </li>

        {{-- Referrals --}}
        <li>
          <a href="{{ route('referrals.index') }}"
             class="tt-nav-link {{ request()->routeIs('referrals*') ? 'active' : '' }}">
            <span class="tt-nav-icon"><i class="bi bi-people-fill"></i></span>
            <span class="tt-nav-text">Referrals</span>
            @if(request()->routeIs('referrals*'))<span class="tt-nav-pip"></span>@endif
          </a>
        </li>

      </ul>

      {{-- ── DIVIDER ── --}}
      <div class="tt-nav-divider"></div>

      {{-- ── NAV SECTION: ACCOUNT ── --}}
      <div class="tt-nav-label">Account</div>
      <ul class="tt-nav">

        <li>
          <a href="{{ route('profile.index') }}"
             class="tt-nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
            <span class="tt-nav-icon"><i class="bi bi-person-fill"></i></span>
            <span class="tt-nav-text">My Account</span>
            @if(request()->routeIs('profile*'))<span class="tt-nav-pip"></span>@endif
          </a>
        </li>

        <li>
          <a href="#" class="tt-nav-link tt-nav-signout"
             onclick="event.preventDefault(); confirmLogout();">
            <span class="tt-nav-icon"><i class="bi bi-box-arrow-right"></i></span>
            <span class="tt-nav-text">Sign Out</span>
          </a>
        </li>

      </ul>

      {{-- ── BOTTOM STRIP ── --}}
      <div class="tt-sidebar-bottom">
        <div class="tt-sidebar-bottom-brand">Top<em>Trade</em></div>
        <div class="tt-sidebar-bottom-copy">Expert-Led Trading</div>
      </div>

    </div>{{-- /collapse --}}
  </div>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</nav>


<style>
/* ════════════════════════════════════════════════
   TOPTRADE SIDEBAR
   Palette: Charcoal + Antique Gold
   Fonts: DM Serif + IBM Plex Mono + Syne
════════════════════════════════════════════════ */

:root {
  --tt-gold:          #C9A84C;
  --tt-gold-light:    #E8C97A;
  --tt-gold-dim:      #8A6F2E;
  --tt-gold-faint:    rgba(201,168,76,0.08);
  --tt-gold-mist:     rgba(201,168,76,0.15);
  --tt-ink:           #0C0C0E;
  --tt-ink-2:         #131316;
  --tt-ink-3:         #1A1A1F;
  --tt-ink-4:         #222228;
  --tt-border:        rgba(255,255,255,0.07);
  --tt-border-mid:    rgba(255,255,255,0.11);
  --tt-border-gold:   rgba(201,168,76,0.22);
  --tt-text:          #D4D4DC;
  --tt-text-dim:      #7A7A88;
  --tt-text-faint:    #3E3E48;
  --tt-green:         #4CAF82;
  --tt-red:           #D95F5F;
  --tt-sidebar-w:     240px;
  --tt-font-sans:     'Syne', 'Segoe UI', sans-serif;
  --tt-font-mono:     'IBM Plex Mono', 'Consolas', monospace;
  --tt-font-serif:    'DM Serif Display', Georgia, serif;
}

/* ── SIDEBAR SHELL ── */
.tt-sidebar {
  background: var(--tt-ink-2);
  border-right: 1px solid var(--tt-border);
  width: var(--tt-sidebar-w);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  position: relative;
  z-index: 200;
  flex-shrink: 0;
}

.tt-sidebar-inner {
  display: flex;
  flex-direction: column;
  height: 100%;
}

/* subtle top accent line */
.tt-sidebar::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, var(--tt-gold-dim), transparent);
  pointer-events: none;
}

/* ── MOBILE TOPBAR ── */
.tt-topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.85rem 1.25rem;
  border-bottom: 1px solid var(--tt-border);
  background: var(--tt-ink-2);
}

.tt-toggler {
  background: var(--tt-ink-3);
  border: 1px solid var(--tt-border-mid);
  color: var(--tt-text-dim);
  width: 36px; height: 36px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; cursor: pointer; transition: all 0.2s;
  flex-shrink: 0;
}
.tt-toggler:hover { border-color: var(--tt-border-gold); color: var(--tt-gold); }

/* ── LOGO ── */
.tt-logo {
  padding: 1.35rem 1.25rem 1.1rem;
  border-bottom: 1px solid var(--tt-border);
  align-items: center;
}
.tt-logo a,
.tt-sidebar-brand {
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.tt-logo img,
.tt-sidebar-brand img {
  max-height: 30px;
  display: block;
}
.tt-brand-text {
  font-family: var(--tt-font-serif);
  font-size: 1.15rem;
  color: #fff;
  letter-spacing: 0.01em;
}
.tt-brand-text em {
  color: var(--tt-gold);
  font-style: normal;
}

/* ── COLLAPSE ── */
.tt-sidebar-collapse {
  display: flex !important;
  flex-direction: column;
  overflow-y: auto;
  overflow-x: hidden;
  flex: 1;
  padding-bottom: 0;
  scrollbar-width: thin;
  scrollbar-color: var(--tt-ink-4) transparent;
}
.tt-sidebar-collapse::-webkit-scrollbar { width: 3px; }
.tt-sidebar-collapse::-webkit-scrollbar-thumb { background: var(--tt-ink-4); border-radius: 2px; }

/* mobile collapse — hidden by default */
@media(max-width: 991.98px) {
  .tt-sidebar-collapse { display: none !important; }
  .tt-sidebar-collapse.show,
  .tt-sidebar-collapse.collapsing { display: flex !important; }
}

/* ── USER STRIP ── */
.tt-user-strip {
  margin: 0.85rem 0.85rem 0.5rem;
  border: 1px solid var(--tt-border);
  background: var(--tt-ink-3);
  transition: border-color 0.2s;
}
.tt-user-strip:hover { border-color: var(--tt-border-gold); }

.tt-user-toggle {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  padding: 0.82rem 0.9rem;
  text-decoration: none;
  width: 100%;
  color: var(--tt-text);
}
.tt-user-toggle::after { display: none; }

.tt-user-avatar-wrap { position: relative; flex-shrink: 0; }
.tt-user-avatar {
  width: 36px; height: 36px;
  object-fit: cover;
  border-radius: 50%;
  border: 1px solid var(--tt-border-gold);
  display: block;
}
.tt-user-status {
  position: absolute; bottom: 0; right: 0;
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--tt-green);
  border: 1.5px solid var(--tt-ink-3);
}

.tt-user-info { flex: 1; min-width: 0; }
.tt-user-name {
  font-family: var(--tt-font-sans);
  font-size: 0.82rem; font-weight: 700;
  color: #fff;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;
  letter-spacing: 0.01em;
}
.tt-user-balance {
  font-family: var(--tt-font-mono);
  font-size: 0.65rem; color: var(--tt-gold);
  letter-spacing: 0.04em; margin-top: 0.15rem;
  display: flex; align-items: center; gap: 0.25rem;
}
.tt-user-chevron {
  font-size: 0.6rem; color: var(--tt-text-faint);
  transition: transform 0.25s; flex-shrink: 0;
}
.tt-user-toggle[aria-expanded="true"] .tt-user-chevron {
  transform: rotate(180deg); color: var(--tt-gold-dim);
}

/* user dropdown menu */
.tt-user-menu {
  background: var(--tt-ink-3) !important;
  border: 1px solid var(--tt-border-gold) !important;
  border-radius: 0 !important;
  min-width: 200px; padding: 0;
  box-shadow: 0 8px 32px rgba(0,0,0,0.5) !important;
}
.tt-user-menu-header {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid var(--tt-border);
}
.tt-um-name {
  font-family: var(--tt-font-sans);
  font-size: 0.82rem; font-weight: 700; color: #fff;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.tt-um-email { font-size: 0.72rem; color: var(--tt-text-dim); margin-top: 0.15rem; }
.tt-menu-divider { border-color: var(--tt-border) !important; margin: 0 !important; }
.tt-menu-item {
  color: var(--tt-text-dim) !important;
  font-family: var(--tt-font-sans);
  font-size: 0.875rem; font-weight: 500;
  padding: 0.55rem 1rem !important;
  transition: all 0.15s;
  display: flex !important; align-items: center; gap: 0.55rem;
  border-radius: 0 !important;
}
.tt-menu-item:hover { background: var(--tt-gold-faint) !important; color: var(--tt-gold) !important; }
.tt-menu-item i { width: 14px; text-align: center; font-size: 0.85rem; }
.tt-menu-logout { color: var(--tt-red) !important; }
.tt-menu-logout:hover { background: rgba(217,95,95,0.08) !important; color: var(--tt-red) !important; }

/* ── NAV LABEL ── */
.tt-nav-label {
  font-family: var(--tt-font-mono);
  font-size: 0.58rem; letter-spacing: 0.16em;
  text-transform: uppercase; color: var(--tt-text-faint);
  padding: 1rem 1.25rem 0.3rem;
}

/* ── NAV LIST ── */
.tt-nav {
  list-style: none;
  margin: 0; padding: 0 0.65rem;
}
.tt-nav li { margin-bottom: 1px; }

/* ── NAV LINK ── */
.tt-nav-link {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  padding: 0.6rem 0.85rem;
  color: var(--tt-text-dim);
  font-family: var(--tt-font-sans);
  font-size: 0.875rem; font-weight: 500;
  text-decoration: none;
  border-left: 2px solid transparent;
  transition: all 0.15s;
  position: relative;
  border-radius: 0 !important;
  width: 100%;
  cursor: pointer;
  background: transparent;
  letter-spacing: 0.01em;
}
.tt-nav-link:hover {
  color: #fff;
  background: var(--tt-ink-3);
  border-left-color: var(--tt-border-mid);
}
.tt-nav-link.active {
  color: var(--tt-gold) !important;
  background: var(--tt-gold-faint);
  border-left-color: var(--tt-gold);
}
.tt-nav-signout:hover {
  color: var(--tt-red) !important;
  background: rgba(217,95,95,0.06) !important;
  border-left-color: var(--tt-red) !important;
}
.tt-nav-signout:hover .tt-nav-icon { color: var(--tt-red) !important; }

/* icon */
.tt-nav-icon {
  width: 22px; height: 22px;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.875rem; color: var(--tt-text-faint); flex-shrink: 0;
  transition: color 0.15s;
}
.tt-nav-link:hover .tt-nav-icon { color: var(--tt-text-dim); }
.tt-nav-link.active .tt-nav-icon { color: var(--tt-gold); }

/* text */
.tt-nav-text { flex: 1; }

/* chevron for collapsible */
.tt-nav-arrow {
  font-size: 0.6rem;
  color: var(--tt-text-faint);
  margin-left: auto; flex-shrink: 0;
  transition: transform 0.25s;
}
.tt-nav-link[aria-expanded="true"] .tt-nav-arrow {
  transform: rotate(180deg);
  color: var(--tt-gold-dim);
}

/* active indicator pip */
.tt-nav-pip {
  width: 5px; height: 5px;
  border-radius: 50%;
  background: var(--tt-gold);
  flex-shrink: 0;
}

/* ── SUBNAV ── */
.tt-subnav { padding: 0.15rem 0 0.3rem 0.75rem; }
.tt-sublink {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.38rem 0.85rem;
  font-family: var(--tt-font-sans);
  font-size: 0.82rem; font-weight: 500;
  color: var(--tt-text-faint);
  text-decoration: none;
  border-left: 1px solid var(--tt-border);
  margin-left: 0.35rem;
  transition: all 0.15s;
}
.tt-sublink i { font-size: 0.72rem; width: 14px; text-align: center; }
.tt-sublink:hover { color: var(--tt-text); border-left-color: var(--tt-border-mid); }
.tt-sublink.active { color: var(--tt-gold); border-left-color: var(--tt-gold-dim); }

/* ── DIVIDER ── */
.tt-nav-divider {
  border-top: 1px solid var(--tt-border);
  margin: 0.75rem 0.85rem 0.5rem;
}

/* ── BOTTOM STRIP ── */
.tt-sidebar-bottom {
  margin-top: auto;
  padding: 1.1rem 1.25rem;
  border-top: 1px solid var(--tt-border);
  background: var(--tt-ink-2);
}
.tt-sidebar-bottom-brand {
  font-family: var(--tt-font-serif);
  font-size: 0.95rem; color: #fff; margin-bottom: 0.15rem;
}
.tt-sidebar-bottom-brand em {
  color: var(--tt-gold); font-style: normal;
}
.tt-sidebar-bottom-copy {
  font-family: var(--tt-font-mono);
  font-size: 0.58rem; letter-spacing: 0.08em;
  text-transform: uppercase; color: var(--tt-text-faint);
}

/* ── RESPONSIVE ── */
@media(max-width: 991.98px) {
  .tt-sidebar {
    width: 100%;
    min-height: auto;
    border-right: none;
    border-bottom: 1px solid var(--tt-border);
  }
  .tt-sidebar-collapse {
    max-height: calc(100vh - 60px);
    overflow-y: auto;
  }
}
@media(min-width: 992px) {
  .tt-sidebar {
    min-height: 100vh;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
  }
}
</style>