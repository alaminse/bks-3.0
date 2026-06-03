@extends('layouts.app')
@section('title', 'Buy Package')

@section('css')
<style>
/* ══════════════════════════════════════════
   PACKAGES INDEX  ·  New Layout
   Inspired by: crypto exchange tier selector
══════════════════════════════════════════ */

/* ── TOP SPLIT BAR ── */
.pk-topbar {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.75rem;
    flex-wrap: wrap;
}
.pk-eyebrow { font-family:'Orbitron',monospace; font-size:.48rem; letter-spacing:4px; text-transform:uppercase; color:rgba(0,245,255,.5); margin-bottom:.3rem; }
.pk-title   { font-family:'Orbitron',monospace; font-size:1.2rem; font-weight:900; letter-spacing:2px; text-transform:uppercase; color:#fff; line-height:1; }

/* wallet pill */
.pk-wallet-pill {
    display: flex; align-items: center; gap: 1rem;
    background: var(--surface);
    border: 1px solid var(--border);
    padding: .7rem 1.25rem;
    position: relative; overflow: hidden;
    clip-path: polygon(10px 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
}
.pk-wallet-pill::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent);
}
.pk-wp-item { text-align: center; }
.pk-wp-lbl { font-family:'Orbitron',monospace; font-size:.42rem; letter-spacing:2px; text-transform:uppercase; color:var(--text-muted); display:block; }
.pk-wp-val { font-family:'Orbitron',monospace; font-size:.88rem; font-weight:900; color:var(--cyan); display:block; }
.pk-wp-sep { width:1px; height:30px; background:var(--border); flex-shrink:0; }
.pk-add-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    font-family:'Orbitron',monospace; font-size:.56rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase;
    padding: .5rem 1rem; text-decoration: none; flex-shrink: 0;
    background: linear-gradient(135deg, var(--cyan), var(--blue));
    color: var(--black); border: none;
    clip-path: polygon(6px 0, 100% 0, calc(100% - 6px) 100%, 0 100%);
    transition: all .25s;
}
.pk-add-btn:hover { box-shadow:var(--glow-cyan-lg); transform:translateY(-2px); color:var(--black); }

/* ── ACTIVE PACKAGES BAND ── */
.pk-active-band {
    background: var(--surface);
    border: 1px solid rgba(0,245,255,.25);
    margin-bottom: 1.75rem;
    position: relative; overflow: hidden;
}
.pk-active-band::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, var(--blue), var(--cyan), var(--blue));
    background-size: 200%;
    animation: pk-flow 3s linear infinite;
}
@keyframes pk-flow { 0%{background-position:0%} 100%{background-position:200%} }

.pk-ab-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: .75rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}
.pk-ab-title {
    font-family:'Orbitron',monospace; font-size:.62rem; font-weight:700; letter-spacing:2px; text-transform:uppercase;
    color: var(--cyan); display: flex; align-items: center; gap: .5rem; margin: 0;
}
.pk-ab-dot { width:7px; height:7px; border-radius:50%; background:var(--cyan); box-shadow:0 0 6px var(--cyan); animation:blink 1.5s infinite; }
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.25} }

.pk-ab-link {
    font-family:'Orbitron',monospace; font-size:.5rem; letter-spacing:1.5px; text-transform:uppercase;
    color:var(--text-dim); text-decoration:none; border:1px solid var(--border); padding:.22rem .65rem;
    clip-path:polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%); transition:all .2s;
}
.pk-ab-link:hover { color:var(--cyan); border-color:var(--cyan); }

/* active package row */
.pk-ab-scroll {
    display: flex; gap: 0; overflow-x: auto;
    scrollbar-width: none;
}
.pk-ab-scroll::-webkit-scrollbar { display: none; }

.pk-active-item {
    display: flex; align-items: center; gap: 1.25rem;
    padding: 1rem 1.5rem;
    border-right: 1px solid var(--border);
    min-width: 280px; flex-shrink: 0;
    transition: background .2s;
}
.pk-active-item:last-child { border-right: none; }
.pk-active-item:hover { background: rgba(0,245,255,.025); }

.pk-ai-icon {
    width: 42px; height: 42px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: var(--cyan);
    background: rgba(0,245,255,.08); border: 1px solid rgba(0,245,255,.2);
    clip-path: polygon(6px 0,100% 0,100% calc(100% - 6px),calc(100% - 6px) 100%,0 100%,0 6px);
}
.pk-ai-name  { font-family:'Orbitron',monospace; font-size:.65rem; font-weight:700; color:#fff; margin-bottom:.4rem; }
.pk-ai-stats { display:flex; gap:.85rem; }
.pk-ai-stat  { font-family:'Orbitron',monospace; font-size:.5rem; letter-spacing:1px; text-transform:uppercase; }
.pk-ai-stat .v { color:var(--cyan); font-size:.65rem; font-weight:700; display:block; }
.pk-ai-stat .l { color:var(--text-muted); }

.pk-ai-bar-wrap { flex: 1; min-width: 80px; }
.pk-ai-bar-bg { background:var(--surface2); border:1px solid var(--border); height:3px; overflow:hidden; margin-bottom:.2rem; }
.pk-ai-bar-fill { height:100%; background:linear-gradient(90deg, var(--blue), var(--cyan)); box-shadow:0 0 5px rgba(0,245,255,.5); transition:width 1s; }
.pk-ai-pct { font-family:'Orbitron',monospace; font-size:.42rem; letter-spacing:1px; color:var(--text-muted); text-align:right; display:block; }

/* ── SECTION DIVIDER ── */
.pk-sec-div {
    font-family:'Orbitron',monospace; font-size:.5rem; letter-spacing:4px; text-transform:uppercase; color:var(--text-muted);
    display:flex; align-items:center; gap:.75rem; margin-bottom:1.25rem;
}
.pk-sec-div::after { content:''; flex:1; height:1px; background:linear-gradient(90deg,var(--border),transparent); }

/* ══════════════════════════════
   PACKAGE CARDS — New Design
   Horizontal card with left price column
══════════════════════════════ */
.pk-cards-list { display:flex; flex-direction:column; gap:.85rem; margin-bottom:2.5rem; }

.pk-card {
    background: var(--surface);
    border: 1px solid var(--border);
    display: grid;
    grid-template-columns: 200px 1fr auto;
    position: relative; overflow: hidden;
    transition: border-color .3s, transform .3s;
}
.pk-card:hover { border-color: var(--border-bright); transform: translateX(4px); }
.pk-card.pk-popular { border-color: rgba(0,245,255,.3); }

/* animated left edge on hover */
.pk-card::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
    background: linear-gradient(180deg, transparent, var(--cyan), transparent);
    transform: scaleY(0); transform-origin: top;
    transition: transform .4s;
}
.pk-card:hover::before { transform: scaleY(1); }

/* POPULAR ribbon */
.pk-ribbon {
    position: absolute; top: 14px; right: -24px;
    background: var(--cyan); color: var(--black);
    font-family:'Orbitron',monospace; font-size:.42rem; font-weight:900; letter-spacing:1px;
    padding: 3px 28px; transform: rotate(45deg); z-index: 10;
    box-shadow: 0 0 10px rgba(0,245,255,.4);
}

/* LEFT — price column */
.pk-card-price {
    background: linear-gradient(180deg, rgba(0,71,255,.12), rgba(0,8,16,.5));
    border-right: 1px solid var(--border);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 1.5rem 1rem; text-align: center;
    position: relative;
}
.pk-card-price::after {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, var(--blue), var(--cyan));
}
.pk-price-dollar { font-family:'Orbitron',monospace; font-size:2.4rem; font-weight:900; color:#fff; line-height:1; display:block; text-shadow:0 0 20px rgba(0,245,255,.15); }
.pk-price-cent   { font-family:'Orbitron',monospace; font-size:.85rem; color:var(--cyan); }
.pk-price-sub    { font-family:'Orbitron',monospace; font-size:.44rem; letter-spacing:2px; text-transform:uppercase; color:var(--text-muted); margin-top:.4rem; display:block; }
.pk-name-chip {
    display: inline-block;
    font-family:'Orbitron',monospace; font-size:.48rem; font-weight:700; letter-spacing:2px; text-transform:uppercase;
    color:var(--cyan); border:1px solid rgba(0,245,255,.3); background:rgba(0,245,255,.05);
    padding:.18rem .6rem; margin-bottom:.65rem;
    clip-path:polygon(3px 0,100% 0,calc(100% - 3px) 100%,0 100%);
}

/* MIDDLE — info */
.pk-card-info {
    padding: 1.1rem 1.25rem;
    display: flex; flex-direction: column; justify-content: center; gap: .6rem;
}
.pk-card-name { font-family:'Orbitron',monospace; font-size:.78rem; font-weight:900; color:#fff; letter-spacing:1px; margin-bottom:.1rem; }
.pk-card-desc { font-family:'Rajdhani',sans-serif; font-size:.85rem; color:var(--text-muted); line-height:1.5; margin-bottom:.2rem; }

/* stat pills row */
.pk-stat-pills { display:flex; gap:.4rem; flex-wrap:wrap; }
.pk-stat-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    font-family:'Orbitron',monospace; font-size:.46rem; font-weight:700; letter-spacing:1px; text-transform:uppercase;
    padding: .22rem .6rem; border: 1px solid; white-space: nowrap;
    clip-path: polygon(3px 0,100% 0,calc(100% - 3px) 100%,0 100%);
}
.pk-stat-pill i { font-size:.62rem; }
.pk-stat-pill.tasks  { color:var(--cyan);    border-color:rgba(0,245,255,.3);   background:rgba(0,245,255,.05); }
.pk-stat-pill.earn   { color:#34d399;        border-color:rgba(52,211,153,.3);  background:rgba(52,211,153,.05); }
.pk-stat-pill.roi    { color:var(--warning); border-color:rgba(251,191,36,.3);  background:rgba(251,191,36,.05); }
.pk-stat-pill.days   { color:#60a5fa;        border-color:rgba(96,165,250,.3);  background:rgba(96,165,250,.05); }

/* collapsible features */
.pk-feat-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    font-family:'Orbitron',monospace; font-size:.5rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase;
    color:var(--text-muted); background:none; border:1px solid var(--border); padding:.28rem .7rem;
    cursor:pointer; transition:all .2s;
    clip-path:polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%);
    align-self: flex-start;
}
.pk-feat-btn:hover { color:var(--cyan); border-color:var(--cyan); }
.pk-feat-btn .arrow { transition:transform .25s; font-size:.55rem; }
.pk-feat-btn[aria-expanded="true"] .arrow { transform:rotate(180deg); color:var(--cyan); }

/* features expand */
.pk-feat-list {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: .3rem; margin-top: .5rem;
}
@media(max-width:575px){ .pk-feat-list { grid-template-columns:1fr; } }
.pk-feat-row {
    display:flex; align-items:flex-start; gap:.4rem;
    font-family:'Rajdhani',sans-serif; font-size:.84rem; color:var(--text-dim);
}
.pk-feat-row i { color:var(--cyan); font-size:.75rem; flex-shrink:0; margin-top:.12rem; }

/* RIGHT — action */
.pk-card-action {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: .65rem; padding: 1.25rem 1.35rem;
    border-left: 1px solid var(--border);
    min-width: 170px;
}
.pk-buy-btn {
    display: flex; align-items: center; justify-content: center; gap: .45rem;
    font-family:'Orbitron',monospace; font-size:.6rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase;
    padding: .75rem 1.1rem; width:100%; border:none; cursor:pointer;
    background: linear-gradient(135deg, var(--cyan), var(--blue));
    color: var(--black);
    clip-path: polygon(6px 0,100% 0,calc(100% - 6px) 100%,0 100%);
    transition: all .25s; position:relative; overflow:hidden;
}
.pk-buy-btn::after { content:''; position:absolute; inset:0; background:rgba(255,255,255,.1); transform:scaleX(0); transform-origin:left; transition:transform .25s; }
.pk-buy-btn:hover::after { transform:scaleX(1); }
.pk-buy-btn:hover { box-shadow:var(--glow-cyan-lg); transform:translateY(-2px); }

.pk-state-btn {
    display:flex; align-items:center; justify-content:center; gap:.45rem;
    font-family:'Orbitron',monospace; font-size:.6rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase;
    padding: .75rem 1.1rem; width:100%;
    clip-path: polygon(6px 0,100% 0,calc(100% - 6px) 100%,0 100%);
    cursor: not-allowed;
}
.pk-state-btn.active  { color:var(--cyan);   border:1px solid rgba(0,245,255,.3); background:rgba(0,245,255,.06); }
.pk-state-btn.low-bal { color:var(--danger);  border:1px solid rgba(248,113,113,.3); background:rgba(248,113,113,.06); }

.pk-detail-link {
    font-family:'Orbitron',monospace; font-size:.5rem; letter-spacing:1.5px; text-transform:uppercase;
    color:var(--text-muted); text-decoration:none; transition:color .2s;
}
.pk-detail-link:hover { color:var(--cyan); }
.pk-deposit-link {
    display:flex; align-items:center; gap:.3rem;
    font-family:'Orbitron',monospace; font-size:.5rem; letter-spacing:1.5px; text-transform:uppercase;
    color:var(--cyan); text-decoration:none;
    border:1px solid rgba(0,245,255,.25); padding:.3rem .65rem; width:100%; justify-content:center;
    clip-path:polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%);
    transition:all .2s;
}
.pk-deposit-link:hover { background:rgba(0,245,255,.07); border-color:var(--cyan); }

/* empty state */
.pk-empty {
    background:var(--surface); border:1px solid var(--border);
    text-align:center; padding:4rem 2rem;
}
.pk-empty i { font-size:3rem; opacity:.12; display:block; margin-bottom:1rem; color:var(--cyan); }
.pk-empty p { font-family:'Orbitron',monospace; font-size:.7rem; color:var(--text-muted); margin:0; }

/* ══════════════════════
   HOW IT WORKS
══════════════════════ */
.pk-hiw {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
}
.pk-hiw::before {
    content: '';
    position: absolute; top:0; left:0; right:0; height:2px;
    background: linear-gradient(90deg, transparent, var(--cyan), var(--blue), transparent);
}
.pk-hiw-head {
    padding: .85rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}
.pk-hiw-title { font-family:'Orbitron',monospace; font-size:.65rem; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--cyan); margin:0; display:flex; align-items:center; gap:.5rem; }

.pk-hiw-steps {
    display: grid; grid-template-columns: repeat(4, 1fr);
    position: relative;
}
@media(max-width:767px){ .pk-hiw-steps { grid-template-columns:1fr 1fr; } }
@media(max-width:400px){ .pk-hiw-steps { grid-template-columns:1fr; } }

/* connector line */
.pk-hiw-steps::before {
    content: '';
    position: absolute; top: 48px; left: 12.5%; right: 12.5%;
    height: 1px;
    background: linear-gradient(90deg, var(--border), var(--cyan), var(--border));
    pointer-events: none;
    z-index: 0;
}
@media(max-width:767px){ .pk-hiw-steps::before { display:none; } }

.pk-step {
    padding: 1.5rem 1rem;
    text-align: center;
    border-right: 1px solid var(--border);
    position: relative; z-index: 1;
    transition: background .2s;
}
.pk-step:last-child { border-right: none; }
.pk-step:hover { background: rgba(0,245,255,.02); }
@media(max-width:767px){
    .pk-step:nth-child(2n) { border-right:none; }
    .pk-step:nth-child(-n+2) { border-bottom:1px solid var(--border); }
}

.pk-step-num {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family:'Orbitron',monospace; font-size:.65rem; font-weight:900;
    background: var(--black); border: 1px solid var(--cyan);
    color: var(--cyan); margin: 0 auto .85rem;
    box-shadow: 0 0 12px rgba(0,245,255,.2);
    position: relative; z-index: 2;
}
.pk-step-ico { font-size:1.4rem; color:var(--cyan); opacity:.45; display:block; margin-bottom:.5rem; }
.pk-step-title { font-family:'Orbitron',monospace; font-size:.6rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#fff; margin-bottom:.35rem; }
.pk-step-sub   { font-family:'Rajdhani',sans-serif; font-size:.82rem; color:var(--text-muted); line-height:1.5; }

@media(max-width:1100px){
    .pk-card { grid-template-columns:160px 1fr auto; }
}
@media(max-width:767px){
    .pk-card { grid-template-columns:1fr; }
    .pk-card-price { border-right:none; border-bottom:1px solid var(--border); flex-direction:row; justify-content:flex-start; gap:1rem; padding:1rem 1.25rem; }
    .pk-card-action { border-left:none; border-top:1px solid var(--border); flex-direction:row; flex-wrap:wrap; min-width:auto; padding:1rem 1.25rem; }
    .pk-topbar { grid-template-columns:1fr; }
    .pk-wallet-pill { justify-content:space-between; clip-path:none; }
}
@media(max-width:575px){
    .pk-stat-pills { gap:.3rem; }
    .pk-hiw-steps::before { display:none; }
}
</style>
@endsection

@section('content')
@include('includes.header', ['pageTitle' => 'Buy Package'])

{{-- ALERTS --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── TOP BAR ── --}}
<div class="pk-topbar">
    <div>
        {{-- <div class="pk-eyebrow">// Select Tier</div>
        <div class="pk-title">Buy Package</div> --}}
    </div>
    <div class="pk-wallet-pill">
        <div class="pk-wp-item">
            <span class="pk-wp-lbl">Balance</span>
            <span class="pk-wp-val">${{ number_format($wallet->balance ?? 0, 2) }}</span>
        </div>
        <div class="pk-wp-sep"></div>
        <div class="pk-wp-item">
            <span class="pk-wp-lbl">Available</span>
            <span class="pk-wp-val">${{ number_format($wallet->available_balance ?? 0, 2) }}</span>
        </div>
        <div class="pk-wp-sep"></div>
        <a href="{{ route('wallet.deposit') }}" class="pk-add-btn">
            <i class="bi bi-plus-lg"></i> Add Funds
        </a>
    </div>
</div>

{{-- ── ACTIVE PACKAGES BAND ── --}}
@if($userPackages->count() > 0)
<div class="pk-active-band">
    <div class="pk-ab-head">
        <h2 class="pk-ab-title">
            <span class="pk-ab-dot"></span> Running Packages
        </h2>
        <a href="{{ route('packages.my') }}" class="pk-ab-link">View All →</a>
    </div>
    <div class="pk-ab-scroll">
        @foreach($userPackages as $up)
        @php $pct = $up->daily_task_limit > 0 ? min(100, round(($up->today_task_count / $up->daily_task_limit) * 100)) : 0; @endphp
        <div class="pk-active-item">
            <div class="pk-ai-icon"><i class="bi bi-box-seam-fill"></i></div>
            <div style="flex:1;min-width:0;">
                <div class="pk-ai-name">{{ $up->package->name }}</div>
                <div class="pk-ai-stats">
                    <div class="pk-ai-stat">
                        <span class="v">{{ $up->today_task_count }}/{{ $up->daily_task_limit }}</span>
                        <span class="l">Tasks</span>
                    </div>
                    <div class="pk-ai-stat">
                        <span class="v">${{ number_format($up->today_earning, 2) }}</span>
                        <span class="l">Today</span>
                    </div>
                </div>
            </div>
            <div class="pk-ai-bar-wrap">
                <div class="pk-ai-bar-bg">
                    <div class="pk-ai-bar-fill" style="width:{{ $pct }}%"></div>
                </div>
                <span class="pk-ai-pct">{{ $pct }}%</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ── PACKAGES LIST ── --}}
<div class="pk-sec-div">Available Packages &nbsp;({{ $packages->count() }})</div>

@if($packages->count())
<div class="pk-cards-list">
    @foreach($packages as $package)
    @php
        $hasActive = $userPackages->where('package_id', $package->id)->count() > 0;
        $canAfford = $wallet && $wallet->hasSufficientBalance($package->price);
        $isPopular = $loop->iteration === 2;
        $priceInt  = floor($package->price);
        $priceDec  = str_pad((int)(($package->price - $priceInt) * 100), 2, '0');
    @endphp

    <div class="pk-card {{ $isPopular ? 'pk-popular' : '' }}">
        @if($isPopular)<div class="pk-ribbon">★ Popular</div>@endif

        {{-- LEFT: price --}}
        <div class="pk-card-price">
            <span class="pk-name-chip">{{ $package->name }}</span>
            <span class="pk-price-dollar">${{ $priceInt }}<span class="pk-price-cent">.{{ $priceDec }}</span></span>
            <span class="pk-price-sub">One-time</span>
        </div>

        {{-- MIDDLE: info --}}
        <div class="pk-card-info">
            <div>
                <div class="pk-card-name">{{ $package->name }} Package</div>
                @if($package->description)
                <div class="pk-card-desc">{{ $package->description }}</div>
                @endif
            </div>

            <div class="pk-stat-pills">
                <span class="pk-stat-pill tasks"><i class="bi bi-list-check"></i> {{ $package->daily_tasks }} tasks/day</span>
                <span class="pk-stat-pill earn"><i class="bi bi-cash-coin"></i> ${{ number_format($package->daily_earning, 2) }}/day</span>
                <span class="pk-stat-pill roi"><i class="bi bi-graph-up-arrow"></i> {{ number_format($package->roi_percentage, 1) }}% ROI</span>
                <span class="pk-stat-pill days"><i class="bi bi-infinity"></i> {{ $package->duration_days === 0 ? 'Unlimited' : $package->duration_days.'d' }}</span>
            </div>

            <div>
                <button class="pk-feat-btn" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#pf-{{ $package->id }}"
                        aria-expanded="false">
                    <i class="bi bi-list-ul"></i> Features
                    <i class="bi bi-chevron-down arrow"></i>
                </button>
                <div class="collapse" id="pf-{{ $package->id }}">
                    <div class="pk-feat-list" style="margin-top:.6rem;">
                        <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> {{ $package->daily_tasks }} tasks per day</div>
                        <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> ${{ number_format($package->per_task_earning, 2) }} per task</div>
                        <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> ${{ number_format($package->daily_earning, 2) }} daily max</div>
                        <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> Total: ${{ number_format($package->total_earning_potential, 2) }}</div>
                        @if($package->features)
                            @foreach(explode("\n", $package->features) as $feat)
                                @if(trim($feat))
                                <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> {{ trim($feat) }}</div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: action --}}
        <div class="pk-card-action">
            @if($hasActive)
                <div class="pk-state-btn active"><i class="bi bi-check-lg"></i> Active</div>
            @elseif(!$canAfford)
                <div class="pk-state-btn low-bal"><i class="bi bi-wallet2"></i> Low Balance</div>
                <a href="{{ route('wallet.deposit') }}" class="pk-deposit-link">
                    <i class="bi bi-plus-circle"></i> Add Funds
                </a>
            @else
                <form id="pkf-{{ $package->slug }}"
                      action="{{ route('packages.purchase', $package->slug) }}"
                      method="POST">
                    @csrf
                    <button type="button" class="pk-buy-btn"
                        onclick="confirmFormSubmit('pkf-{{ $package->slug }}', {
                            title: 'Confirm Purchase',
                            text: 'Purchase {{ $package->name }} for ${{ number_format($package->price, 2) }}?',
                            confirmText: 'Purchase Now',
                            cancelText: 'Cancel'
                        })">
                        <i class="bi bi-cart-check-fill"></i> Buy Now
                    </button>
                </form>
            @endif
            <a href="{{ route('packages.show', $package->slug) }}" class="pk-detail-link">
                <i class="bi bi-info-circle me-1"></i> Full Details
            </a>
        </div>

    </div>
    @endforeach
</div>
@else
<div class="pk-empty">
    <i class="bi bi-inbox"></i>
    <p>No packages available at this time</p>
</div>
@endif

{{-- ── HOW IT WORKS ── --}}
<div class="pk-hiw">
    <div class="pk-hiw-head">
        <h2 class="pk-hiw-title"><i class="bi bi-info-circle-fill"></i> How It Works</h2>
    </div>
    <div class="pk-hiw-steps">
        <div class="pk-step">
            <div class="pk-step-num">01</div>
            <i class="bi bi-cart-check pk-step-ico"></i>
            <div class="pk-step-title">Choose Package</div>
            <p class="pk-step-sub">Select the tier that matches your earning goals and budget</p>
        </div>
        <div class="pk-step">
            <div class="pk-step-num">02</div>
            <i class="bi bi-wallet2 pk-step-ico"></i>
            <div class="pk-step-title">Make Payment</div>
            <p class="pk-step-sub">Pay instantly from your wallet balance — zero fees</p>
        </div>
        <div class="pk-step">
            <div class="pk-step-num">03</div>
            <i class="bi bi-list-check pk-step-ico"></i>
            <div class="pk-step-title">Complete Tasks</div>
            <p class="pk-step-sub">Do your daily task quota every 24 hours without fail</p>
        </div>
        <div class="pk-step">
            <div class="pk-step-num">04</div>
            <i class="bi bi-cash-coin pk-step-ico" style="color:var(--warning);opacity:.5;"></i>
            <div class="pk-step-title" style="color:var(--warning);">Earn & Withdraw</div>
            <p class="pk-step-sub">Watch your balance grow — withdraw anytime you want</p>
        </div>
    </div>
</div>

@endsection
