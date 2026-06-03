@extends('layouts.app')
@section('title', $package->name . ' — Package Details')

@section('css')
<style>
/* ══════════════════════════════════════════
   PACKAGE SHOW  ·  Cyberpunk  ·  New Layout
══════════════════════════════════════════ */

/* ── TOP BAR ── */
.ps-topbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem; margin-bottom: 1.75rem;
}
.ps-breadcrumb {
    font-family: 'Orbitron', monospace;
    font-size: .48rem; letter-spacing: 4px; text-transform: uppercase;
    color: rgba(0,245,255,.5); margin-bottom: .3rem;
    display: flex; align-items: center; gap: .4rem;
}
.ps-breadcrumb a { color: inherit; text-decoration: none; transition: color .2s; }
.ps-breadcrumb a:hover { color: var(--cyan); }
.ps-breadcrumb i { font-size: .6rem; opacity: .4; }
.ps-page-title {
    font-family: 'Orbitron', monospace;
    font-size: 1.15rem; font-weight: 900; letter-spacing: 2px;
    text-transform: uppercase; color: #fff; line-height: 1;
}
.ps-back-btn {
    display: inline-flex; align-items: center; gap: .45rem;
    font-family: 'Orbitron', monospace; font-size: .58rem; font-weight: 700;
    letter-spacing: 1.5px; text-transform: uppercase;
    padding: .55rem 1.2rem; text-decoration: none;
    color: var(--text-dim); border: 1px solid var(--border);
    background: var(--surface2);
    clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
    transition: all .2s;
}
.ps-back-btn:hover { color: var(--cyan); border-color: var(--cyan); background: rgba(0,245,255,.05); }

/* ── LAYOUT ── */
.ps-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.25rem;
    align-items: start;
    margin-bottom: 1.25rem;
}
@media(max-width: 1100px) { .ps-layout { grid-template-columns: 1fr; } }

/* ══════════════════════
   SHARED PANEL BASE
══════════════════════ */
.ps-block {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
    margin-bottom: 1.1rem;
}
.ps-block::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), var(--blue), transparent);
}
.ps-block-hd {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}
.ps-block-title {
    font-family: 'Orbitron', monospace; font-size: .65rem; font-weight: 700;
    letter-spacing: 2px; text-transform: uppercase; color: var(--cyan);
    margin: 0; display: flex; align-items: center; gap: .5rem;
}

/* status badge */
.ps-status {
    display: inline-flex; align-items: center; gap: .28rem;
    font-family: 'Orbitron', monospace; font-size: .46rem; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    padding: .22rem .6rem; border: 1px solid;
    clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
}
.ps-status.on  { color: var(--cyan);       border-color: rgba(0,245,255,.4);   background: rgba(0,245,255,.07); }
.ps-status.off { color: var(--text-muted); border-color: var(--border);        background: var(--surface2); }

/* ══════════════════════
   OVERVIEW
══════════════════════ */
.ps-desc {
    padding: 1.1rem 1.25rem;
    font-family: 'Rajdhani', sans-serif; font-size: .95rem;
    color: var(--text-dim); line-height: 1.65;
    border-bottom: 1px solid var(--border);
}

/* 4 stat tiles — horizontal strip */
.ps-stat-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border-bottom: 1px solid var(--border);
}
@media(max-width: 767px) { .ps-stat-strip { grid-template-columns: 1fr 1fr; } }

.ps-stat-tile {
    padding: 1.1rem .85rem;
    text-align: center;
    border-right: 1px solid var(--border);
    transition: background .2s;
    position: relative;
}
.ps-stat-tile:last-child { border-right: none; }
@media(max-width: 767px) {
    .ps-stat-tile:nth-child(2n) { border-right: none; }
    .ps-stat-tile:nth-child(-n+2) { border-bottom: 1px solid var(--border); }
}
.ps-stat-tile:hover { background: rgba(0,245,255,.03); }
.ps-stat-tile i {
    font-size: 1.2rem; color: var(--cyan); opacity: .45;
    display: block; margin-bottom: .45rem;
}
.ps-tile-val {
    font-family: 'Orbitron', monospace; font-size: 1rem; font-weight: 900;
    color: var(--cyan); display: block; line-height: 1; margin-bottom: .3rem;
    text-shadow: 0 0 10px rgba(0,245,255,.2);
}
.ps-tile-lbl {
    font-family: 'Orbitron', monospace; font-size: .43rem;
    letter-spacing: 2px; text-transform: uppercase; color: var(--text-muted);
}

/* ══════════════════════
   EARNINGS TABLE
══════════════════════ */
.ps-earn-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-bottom: 1px solid var(--border);
}
@media(max-width: 575px) { .ps-earn-grid { grid-template-columns: 1fr; } }

.ps-earn-cell {
    display: flex; align-items: center; justify-content: space-between;
    padding: .75rem 1.25rem;
    border-right: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}
.ps-earn-cell:nth-child(2n) { border-right: none; }
.ps-earn-cell:nth-last-child(-n+2) { border-bottom: none; }
@media(max-width: 575px) { .ps-earn-cell { border-right: none; } .ps-earn-cell:last-child { border-bottom: none; } }

.ps-earn-lbl { font-family: 'Rajdhani', sans-serif; font-size: .85rem; color: var(--text-muted); }
.ps-earn-val { font-family: 'Orbitron', monospace; font-size: .72rem; font-weight: 700; }
.ps-earn-val.c { color: var(--cyan); }
.ps-earn-val.b { color: #60a5fa; }
.ps-earn-val.d { color: var(--danger); }

/* ROI banner */
.ps-roi-banner {
    display: flex; align-items: center; justify-content: space-between;
    padding: .9rem 1.25rem;
    background: linear-gradient(135deg, rgba(0,71,255,.15), rgba(0,245,255,.06));
    border-bottom: 1px solid var(--border);
}
.ps-roi-left {
    font-family: 'Orbitron', monospace; font-size: .58rem; font-weight: 700;
    letter-spacing: 2px; text-transform: uppercase; color: var(--text-dim);
    display: flex; align-items: center; gap: .5rem;
}
.ps-roi-left i { color: var(--cyan); }
.ps-roi-num {
    font-family: 'Orbitron', monospace; font-size: 1.5rem; font-weight: 900;
    color: var(--cyan); text-shadow: var(--glow-cyan);
}

/* features */
.ps-features {
    display: grid; grid-template-columns: 1fr 1fr; gap: .5rem;
    padding: 1.1rem 1.25rem;
    border-bottom: 1px solid var(--border);
}
@media(max-width: 575px) { .ps-features { grid-template-columns: 1fr; } }
.ps-feature {
    display: flex; align-items: flex-start; gap: .55rem;
    background: var(--surface2); border: 1px solid var(--border);
    padding: .6rem .85rem;
    font-family: 'Rajdhani', sans-serif; font-size: .88rem; color: var(--text);
    transition: border-color .2s;
}
.ps-feature:hover { border-color: var(--border-bright); }
.ps-feature i { color: var(--cyan); flex-shrink: 0; margin-top: .12rem; }

/* how it works — numbered steps */
.ps-steps {
    display: grid; grid-template-columns: 1fr 1fr; gap: .75rem;
    padding: 1.1rem 1.25rem;
}
@media(max-width: 575px) { .ps-steps { grid-template-columns: 1fr; } }
.ps-step {
    display: flex; align-items: flex-start; gap: .75rem;
    background: var(--surface2); border: 1px solid var(--border);
    padding: .9rem 1rem;
    transition: border-color .2s;
}
.ps-step:hover { border-color: var(--border-bright); }
.ps-step-n {
    width: 28px; height: 28px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Orbitron', monospace; font-size: .62rem; font-weight: 900;
    color: var(--black); background: var(--cyan);
    clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
    margin-top: .05rem;
}
.ps-step-title { font-family: 'Orbitron', monospace; font-size: .6rem; font-weight: 700; letter-spacing: 1px; color: #fff; margin-bottom: .2rem; }
.ps-step-sub   { font-family: 'Rajdhani', sans-serif; font-size: .8rem; color: var(--text-muted); }

/* stats row */
.ps-pkg-stats {
    display: grid; grid-template-columns: repeat(3, 1fr);
}
.ps-pkg-stat {
    padding: 1.2rem 1rem; text-align: center;
    border-right: 1px solid var(--border);
}
.ps-pkg-stat:last-child { border-right: none; }
.ps-pkg-stat i { font-size: 1.5rem; color: var(--cyan); opacity: .35; display: block; margin-bottom: .5rem; }
.ps-pkg-stat-val { font-family: 'Orbitron', monospace; font-size: 1.05rem; font-weight: 900; color: #fff; display: block; margin-bottom: .2rem; }
.ps-pkg-stat-lbl { font-family: 'Orbitron', monospace; font-size: .44rem; letter-spacing: 2px; text-transform: uppercase; color: var(--text-muted); }

/* ══════════════════════
   RIGHT SIDEBAR
══════════════════════ */
.ps-sidebar { display: flex; flex-direction: column; gap: 1.1rem; }

/* wallet card */
.ps-wallet {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
}
.ps-wallet::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent);
}
.ps-wallet-inner {
    padding: 1.1rem 1.25rem;
    background: linear-gradient(135deg, var(--surface2), rgba(0,71,255,.1));
    display: flex; align-items: center; justify-content: space-between; gap: .5rem;
}
.ps-wallet-lbl-wrap {}
.ps-wallet-eyebrow { font-family:'Orbitron',monospace; font-size:.44rem; letter-spacing:3px; text-transform:uppercase; color:var(--text-muted); display:block; margin-bottom:.2rem; }
.ps-wallet-bal { font-family:'Orbitron',monospace; font-size:1.5rem; font-weight:900; color:var(--cyan); text-shadow:var(--glow-cyan); line-height:1; display:block; }
.ps-wallet-avail { font-family:'Orbitron',monospace; font-size:.46rem; letter-spacing:2px; text-transform:uppercase; color:rgba(0,245,255,.4); margin-top:.2rem; display:block; }
.ps-wallet-ico {
    width: 44px; height: 44px; display:flex; align-items:center; justify-content:center;
    font-size: 1.2rem; color: var(--cyan); flex-shrink: 0;
    border: 1px solid rgba(0,245,255,.2); background: rgba(0,245,255,.07);
    clip-path: polygon(6px 0,100% 0,100% calc(100% - 6px),calc(100% - 6px) 100%,0 100%,0 6px);
}

/* purchase block */
.ps-purchase {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
}
.ps-purchase::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--blue), var(--cyan), transparent);
}

/* big price */
.ps-price-zone {
    padding: 1.5rem 1.25rem 1.1rem;
    text-align: center;
    border-bottom: 1px solid var(--border);
    position: relative;
}
.ps-price-tag {
    font-family: 'Orbitron', monospace; font-size: 2.5rem; font-weight: 900;
    color: #fff; line-height: 1; display: block; margin-bottom: .2rem;
    text-shadow: 0 0 30px rgba(0,245,255,.15);
}
.ps-price-sub { font-family:'Orbitron',monospace; font-size:.46rem; letter-spacing:2.5px; text-transform:uppercase; color:var(--text-muted); }

/* notice boxes */
.ps-notice {
    margin: 1rem 1.25rem;
    padding: .9rem 1rem;
    display: flex; align-items: flex-start; gap: .75rem;
    border: 1px solid;
}
.ps-notice i { font-size: 1.2rem; flex-shrink: 0; margin-top: .05rem; }
.ps-notice-title { font-family:'Orbitron',monospace; font-size:.6rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; display:block; margin-bottom:.2rem; }
.ps-notice-sub   { font-family:'Rajdhani',sans-serif; font-size:.82rem; color:var(--text-muted); }
.ps-notice.info  { border-color:rgba(0,245,255,.3); background:rgba(0,245,255,.05); }
.ps-notice.info i, .ps-notice.info .ps-notice-title { color:var(--cyan); }
.ps-notice.danger { border-color:rgba(248,113,113,.3); background:rgba(248,113,113,.05); }
.ps-notice.danger i, .ps-notice.danger .ps-notice-title { color:var(--danger); }

/* action buttons */
.ps-btn-zone { padding: 0 1.25rem 1rem; display:flex; flex-direction:column; gap:.55rem; }
.ps-btn {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    font-family: 'Orbitron', monospace; font-size: .62rem; font-weight: 700;
    letter-spacing: 1.5px; text-transform: uppercase;
    padding: .82rem 1rem; text-decoration: none; width: 100%;
    border: none; cursor: pointer;
    clip-path: polygon(6px 0, 100% 0, calc(100% - 6px) 100%, 0 100%);
    transition: all .25s;
}
.ps-btn.primary { background: linear-gradient(135deg, var(--cyan), var(--blue)); color: var(--black); }
.ps-btn.primary:hover { box-shadow: var(--glow-cyan-lg); transform: translateY(-2px); color: var(--black); }
.ps-btn.ghost { background: transparent; color: var(--text-dim); border: 1px solid var(--border); }
.ps-btn.ghost:hover { border-color: var(--cyan); color: var(--cyan); background: rgba(0,245,255,.05); }
.ps-btn.deposit { background: transparent; color: var(--cyan); border: 1px solid var(--cyan); }
.ps-btn.deposit:hover { background: var(--cyan); color: var(--black); box-shadow: var(--glow-cyan); }

/* payment summary */
.ps-sum-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .65rem 1.25rem;
    border-top: 1px solid var(--border);
    font-family: 'Rajdhani', sans-serif; font-size: .9rem; color: var(--text-muted);
}
.ps-sum-row .val { font-family:'Orbitron',monospace; font-size:.7rem; font-weight:700; color:#fff; }
.ps-sum-row .free { font-family:'Orbitron',monospace; font-size:.62rem; color:var(--cyan); }
.ps-sum-row.total { background: rgba(0,245,255,.04); border-top: 1px solid var(--border-bright); }
.ps-sum-row.total .label { font-family:'Orbitron',monospace; font-size:.58rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--text-dim); }
.ps-sum-row.total .big   { font-family:'Orbitron',monospace; font-size:1rem; font-weight:900; color:var(--cyan); }

/* what you get */
.ps-benefits {
    border-top: 1px solid var(--border);
}
.ps-benefits-hd {
    padding: .7rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
    font-family:'Orbitron',monospace; font-size:.6rem; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--cyan);
    display: flex; align-items: center; gap: .5rem;
}
.ps-benefit-row {
    display: flex; align-items: center; gap: .6rem;
    padding: .58rem 1.25rem;
    border-bottom: 1px solid var(--border);
    font-family:'Rajdhani',sans-serif; font-size:.88rem; color:var(--text);
}
.ps-benefit-row:last-child { border-bottom: none; }
.ps-benefit-row i { color: var(--cyan); font-size: .82rem; flex-shrink: 0; }

/* ══════════════════════
   FAQ
══════════════════════ */
.ps-faq {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
}
.ps-faq::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent);
}
.ps-faq-hd {
    padding: .85rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}
.ps-faq-title { font-family:'Orbitron',monospace; font-size:.65rem; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--cyan); margin:0; display:flex; align-items:center; gap:.5rem; }

.ps-faq .accordion-item { background:transparent !important; border:none !important; border-bottom:1px solid var(--border) !important; border-radius:0 !important; margin:0 !important; }
.ps-faq .accordion-item:last-child { border-bottom:none !important; }
.ps-faq .accordion-button { background:var(--surface) !important; color:var(--text) !important; font-family:'Rajdhani',sans-serif !important; font-size:.92rem !important; font-weight:600 !important; padding:.9rem 1.25rem !important; border:none !important; box-shadow:none !important; }
.ps-faq .accordion-button:not(.collapsed) { color:var(--cyan) !important; background:rgba(0,245,255,.04) !important; }
.ps-faq .accordion-button::after { filter:brightness(0) saturate(100%) invert(82%) sepia(91%) saturate(1000%) hue-rotate(145deg); }
.ps-faq .accordion-body { background:var(--surface2) !important; padding:.85rem 1.25rem !important; color:var(--text-muted); font-family:'Rajdhani',sans-serif; font-size:.9rem; line-height:1.65; }

@media(max-width:575px){
    .ps-price-tag { font-size:1.9rem; }
    .ps-pkg-stats { grid-template-columns: 1fr 1fr 1fr; }
    .ps-steps { grid-template-columns:1fr; }
    .ps-features { grid-template-columns:1fr; }
}
</style>
@endsection

@section('content')
@include('includes.header', ['pageTitle' => $package->name . ' Package Details'])

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

{{-- TOP BAR --}}
<div class="ps-topbar">
    <div>
        <div class="ps-breadcrumb">
            <a href="{{ route('packages.index') }}">Packages</a>
            <i class="bi bi-chevron-right"></i>
            {{ $package->name }}
        </div>
        <div class="ps-page-title">{{ $package->name }}</div>
    </div>
    <a href="{{ route('packages.index') }}" class="ps-back-btn">
        <i class="bi bi-arrow-left"></i> All Packages
    </a>
</div>

{{-- 2-COL LAYOUT --}}
<div class="ps-layout">

    {{-- ══ LEFT ══ --}}
    <div>

        {{-- OVERVIEW BLOCK --}}
        <div class="ps-block">
            <div class="ps-block-hd">
                <h2 class="ps-block-title"><i class="bi bi-box-seam-fill"></i> Package Overview</h2>
                @if($package->is_active)
                    <span class="ps-status on"><i class="bi bi-circle-fill" style="font-size:.38rem"></i> Active</span>
                @else
                    <span class="ps-status off">Inactive</span>
                @endif
            </div>

            {{-- description --}}
            <div class="ps-desc">{{ $package->description }}</div>

            {{-- 4 tiles --}}
            <div class="ps-stat-strip">
                <div class="ps-stat-tile">
                    <i class="bi bi-currency-dollar"></i>
                    <span class="ps-tile-val">${{ number_format($package->price, 2) }}</span>
                    <span class="ps-tile-lbl">Package Price</span>
                </div>
                <div class="ps-stat-tile">
                    <i class="bi bi-list-check"></i>
                    <span class="ps-tile-val">{{ $package->daily_tasks }}</span>
                    <span class="ps-tile-lbl">Tasks / Day</span>
                </div>
                <div class="ps-stat-tile">
                    <i class="bi bi-cash-coin"></i>
                    <span class="ps-tile-val">${{ number_format($package->daily_earning, 2) }}</span>
                    <span class="ps-tile-lbl">Daily Earning</span>
                </div>
                <div class="ps-stat-tile">
                    <i class="bi bi-infinity"></i>
                    <span class="ps-tile-val" style="font-size:.75rem;">{{ $package->duration_days === 0 ? 'Unlimited' : $package->duration_days.'d' }}</span>
                    <span class="ps-tile-lbl">Validity</span>
                </div>
            </div>

            {{-- earning breakdown --}}
            <div class="ps-earn-grid">
                <div class="ps-earn-cell">
                    <span class="ps-earn-lbl">Per Task Earning</span>
                    <span class="ps-earn-val c">${{ number_format($package->per_task_earning, 2) }}</span>
                </div>
                <div class="ps-earn-cell">
                    <span class="ps-earn-lbl">Daily Maximum</span>
                    <span class="ps-earn-val c">${{ number_format($package->daily_earning, 2) }}</span>
                </div>
                <div class="ps-earn-cell">
                    <span class="ps-earn-lbl">Monthly Potential</span>
                    <span class="ps-earn-val b">${{ number_format($package->daily_earning * 30, 2) }}</span>
                </div>
                <div class="ps-earn-cell">
                    <span class="ps-earn-lbl">Investment</span>
                    <span class="ps-earn-val d">−${{ number_format($package->price, 2) }}</span>
                </div>
            </div>

            {{-- ROI banner --}}
            <div class="ps-roi-banner">
                <div class="ps-roi-left"><i class="bi bi-graph-up-arrow"></i> Return on Investment (ROI)</div>
                <div class="ps-roi-num">{{ number_format($package->roi_percentage, 1) }}%</div>
            </div>

            {{-- features --}}
            @if($package->features)
            <div style="padding:.85rem 1.25rem .4rem; border-bottom:1px solid var(--border);">
                <div style="font-family:'Orbitron',monospace;font-size:.52rem;letter-spacing:3px;text-transform:uppercase;color:var(--text-muted);margin-bottom:.65rem;display:flex;align-items:center;gap:.6rem;">
                    Package Features
                    <span style="flex:1;height:1px;background:linear-gradient(90deg,var(--border),transparent);display:block;"></span>
                </div>
                <div class="ps-features" style="padding:0;border:none;">
                    @foreach(explode("\n", $package->features) as $feat)
                        @if(trim($feat))
                        <div class="ps-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ trim($feat) }}
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- how it works --}}
            <div style="padding:.85rem 1.25rem 1.1rem;">
                <div style="font-family:'Orbitron',monospace;font-size:.52rem;letter-spacing:3px;text-transform:uppercase;color:var(--text-muted);margin-bottom:.65rem;display:flex;align-items:center;gap:.6rem;">
                    How It Works
                    <span style="flex:1;height:1px;background:linear-gradient(90deg,var(--border),transparent);display:block;"></span>
                </div>
                <div class="ps-steps" style="padding:0;">
                    <div class="ps-step"><div class="ps-step-n">01</div><div><div class="ps-step-title">Purchase Package</div><div class="ps-step-sub">Buy with your wallet balance — instant activation</div></div></div>
                    <div class="ps-step"><div class="ps-step-n">02</div><div><div class="ps-step-title">Complete Daily Tasks</div><div class="ps-step-sub">Up to {{ $package->daily_tasks }} tasks per day</div></div></div>
                    <div class="ps-step"><div class="ps-step-n">03</div><div><div class="ps-step-title">Earn Daily</div><div class="ps-step-sub">Up to ${{ number_format($package->daily_earning, 2) }} per day</div></div></div>
                    <div class="ps-step"><div class="ps-step-n">04</div><div><div class="ps-step-title">Withdraw Anytime</div><div class="ps-step-sub">No lock-in — withdraw whenever you want</div></div></div>
                </div>
            </div>
        </div>

        {{-- STATISTICS BLOCK --}}
        <div class="ps-block" style="margin-bottom:0;">
            <div class="ps-block-hd">
                <h2 class="ps-block-title"><i class="bi bi-bar-chart-fill"></i> Package Statistics</h2>
            </div>
            <div class="ps-pkg-stats">
                <div class="ps-pkg-stat">
                    <i class="bi bi-people-fill"></i>
                    <span class="ps-pkg-stat-val">{{ $package->total_subscribers }}</span>
                    <span class="ps-pkg-stat-lbl">Total Subscribers</span>
                </div>
                <div class="ps-pkg-stat">
                    <i class="bi bi-check-circle-fill"></i>
                    <span class="ps-pkg-stat-val">{{ $package->active_subscribers }}</span>
                    <span class="ps-pkg-stat-lbl">Active Now</span>
                </div>
                <div class="ps-pkg-stat">
                    <i class="bi bi-star-fill"></i>
                    <span class="ps-pkg-stat-val">4.8</span>
                    <span class="ps-pkg-stat-lbl">User Rating</span>
                </div>
            </div>
        </div>

    </div>{{-- /left --}}

    {{-- ══ RIGHT SIDEBAR ══ --}}
    <div class="ps-sidebar">

        {{-- WALLET --}}
        <div class="ps-wallet">
            <div class="ps-wallet-inner">
                <div class="ps-wallet-lbl-wrap">
                    <span class="ps-wallet-eyebrow">Your Wallet</span>
                    <span class="ps-wallet-bal">${{ number_format($wallet->balance ?? 0, 2) }}</span>
                    <span class="ps-wallet-avail">Available: ${{ number_format($wallet->available_balance ?? 0, 2) }}</span>
                </div>
                <div class="ps-wallet-ico"><i class="bi bi-wallet2"></i></div>
            </div>
        </div>

        {{-- PURCHASE --}}
        @php
            $canAfford = $wallet && $wallet->hasSufficientBalance($package->price);
            $shortage  = !$canAfford ? $package->price - ($wallet->available_balance ?? 0) : 0;
        @endphp
        <div class="ps-purchase">
            <div class="ps-price-zone">
                <span class="ps-price-tag">${{ number_format($package->price, 2) }}</span>
                <span class="ps-price-sub">One-time payment</span>
            </div>

            @if($hasActivePackage)
                <div class="ps-notice info">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>
                        <span class="ps-notice-title">Already Subscribed</span>
                        <span class="ps-notice-sub">You have an active subscription to this package</span>
                    </div>
                </div>
                <div class="ps-btn-zone">
                    <a href="{{ route('packages.my') }}" class="ps-btn ghost">
                        <i class="bi bi-box-seam"></i> View My Packages
                    </a>
                </div>
            @elseif(!$canAfford)
                <div class="ps-notice danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <span class="ps-notice-title">Insufficient Balance</span>
                        <span class="ps-notice-sub">You need ${{ number_format($shortage, 2) }} more to purchase</span>
                    </div>
                </div>
                <div class="ps-btn-zone">
                    <a href="{{ route('wallet.deposit') }}" class="ps-btn deposit">
                        <i class="bi bi-plus-circle-fill"></i> Deposit Funds
                    </a>
                    <a href="{{ route('packages.index') }}" class="ps-btn ghost">
                        <i class="bi bi-arrow-left"></i> Back to Packages
                    </a>
                </div>
            @else
                <div class="ps-btn-zone" style="padding-top:1rem;">
                    <form id="purchase-form-{{ $package->slug }}"
                          action="{{ route('packages.purchase', $package->slug) }}"
                          method="POST">
                        @csrf
                        <button type="button" class="ps-btn primary" style="width:100%"
                            onclick="confirmFormSubmit(
                                'purchase-form-{{ $package->slug }}',
                                {
                                    title: 'Confirm Purchase?',
                                    text: 'Purchase {{ $package->name }} for ${{ number_format($package->price, 2) }}?',
                                    confirmText: 'Yes, Purchase',
                                    cancelText: 'Cancel'
                                }
                            )">
                            <i class="bi bi-cart-check-fill"></i> Purchase Now
                        </button>
                    </form>
                    <a href="{{ route('packages.index') }}" class="ps-btn ghost">
                        <i class="bi bi-arrow-left"></i> Back to Packages
                    </a>
                </div>
            @endif

            {{-- payment summary rows --}}
            <div class="ps-sum-row">
                <span>Package Price</span>
                <span class="val">${{ number_format($package->price, 2) }}</span>
            </div>
            <div class="ps-sum-row">
                <span>Processing Fee</span>
                <span class="free">Free</span>
            </div>
            <div class="ps-sum-row total">
                <span class="label">Total Amount</span>
                <span class="big">${{ number_format($package->price, 2) }}</span>
            </div>

            {{-- what you get --}}
            <div class="ps-benefits">
                <div class="ps-benefits-hd"><i class="bi bi-gift-fill"></i> What You Get</div>
                <div class="ps-benefit-row"><i class="bi bi-check-circle-fill"></i> {{ $package->daily_tasks }} daily tasks</div>
                <div class="ps-benefit-row"><i class="bi bi-check-circle-fill"></i> Up to ${{ number_format($package->daily_earning, 2) }} / day</div>
                <div class="ps-benefit-row"><i class="bi bi-check-circle-fill"></i> {{ $package->duration_days === 0 ? 'Unlimited' : $package->duration_days.'d' }} access</div>
                <div class="ps-benefit-row"><i class="bi bi-check-circle-fill"></i> Total potential: ${{ number_format($package->total_earning_potential, 2) }}</div>
                <div class="ps-benefit-row"><i class="bi bi-check-circle-fill"></i> ROI: {{ number_format($package->roi_percentage, 1) }}%</div>
                <div class="ps-benefit-row"><i class="bi bi-check-circle-fill"></i> Instant activation</div>
            </div>
        </div>

    </div>{{-- /sidebar --}}

</div>{{-- /layout --}}

{{-- FAQ --}}
<div class="ps-faq">
    <div class="ps-faq-hd">
        <h2 class="ps-faq-title"><i class="bi bi-question-circle-fill"></i> Frequently Asked Questions</h2>
    </div>
    <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">How does this package work?</button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                <div class="accordion-body">After purchasing, complete up to {{ $package->daily_tasks }} tasks per day and earn up to ${{ number_format($package->daily_earning, 2) }} daily. The package stays active for {{ $package->duration_days === 0 ? 'unlimited time' : $package->duration_days.' days' }}.</div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">Can I buy multiple packages at once?</button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">Yes! You can hold multiple different packages simultaneously. You can only have one active subscription of the same package at a time. Once it expires you can repurchase it.</div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">When can I withdraw my earnings?</button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">You can withdraw anytime after completing tasks — no lock-in period. KYC verification is required before your first withdrawal.</div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">What happens when the package expires?</button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">When the package expires, task access stops. All your earnings are yours to keep and withdraw. You can immediately repurchase the same package or choose a different tier.</div>
            </div>
        </div>
    </div>
</div>

@endsection
