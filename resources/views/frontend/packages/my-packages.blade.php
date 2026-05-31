@extends('layouts.app')
@section('title', 'My Packages')

@section('css')
<style>
/* ══════════════════════════════════════════════
   MY PACKAGES  ·  Cyberpunk  ·  New Layout
   Colors: --black / --surface / --cyan / --blue
══════════════════════════════════════════════ */

/* ── TOP BAR ── */
.mp-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.75rem;
    flex-wrap: wrap;
    gap: .75rem;
}
.mp-page-label {
    font-family: 'Orbitron', monospace;
    font-size: .48rem;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--cyan);
    opacity: .7;
    margin-bottom: .3rem;
}
.mp-page-title {
    font-family: 'Orbitron', monospace;
    font-size: 1.25rem;
    font-weight: 900;
    color: #fff;
    letter-spacing: 2px;
    text-transform: uppercase;
    line-height: 1;
}
.mp-buy-btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-family: 'Orbitron', monospace;
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: .65rem 1.4rem;
    text-decoration: none;
    background: transparent;
    color: var(--cyan);
    border: 1px solid var(--cyan);
    clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
    transition: all .25s;
    position: relative;
    overflow: hidden;
}
.mp-buy-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--cyan);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .25s;
    z-index: 0;
}
.mp-buy-btn:hover::before { transform: scaleX(1); }
.mp-buy-btn:hover { color: var(--black); box-shadow: var(--glow-cyan-lg); }
.mp-buy-btn span, .mp-buy-btn i { position: relative; z-index: 1; }

/* ── SECTION DIVIDER ── */
.mp-section-label {
    font-family: 'Orbitron', monospace;
    font-size: .5rem;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-bottom: 1rem;
}
.mp-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--border), transparent);
}

/* ══════════════════════
   PACKAGE TILE CARDS
   Vertical stacked design
══════════════════════ */
.mp-tiles {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.1rem;
    margin-bottom: 2rem;
}

.mp-tile {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: border-color .3s, transform .3s;
}
.mp-tile:hover {
    border-color: var(--border-bright);
    transform: translateY(-4px);
}

/* glowing top bar — thick neon line */
.mp-tile-glow {
    height: 3px;
    background: linear-gradient(90deg, var(--blue), var(--cyan), var(--blue));
    background-size: 200%;
    animation: mp-flow 3s linear infinite;
}
@keyframes mp-flow { 0%{background-position:0%} 100%{background-position:200%} }

/* diagonal number watermark */
.mp-tile::after {
    content: attr(data-index);
    position: absolute;
    bottom: -10px;
    right: -5px;
    font-family: 'Orbitron', monospace;
    font-size: 5rem;
    font-weight: 900;
    color: rgba(0,245,255,.03);
    line-height: 1;
    pointer-events: none;
    user-select: none;
}

/* tile top section */
.mp-tile-top {
    padding: 1.1rem 1.25rem .85rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: .75rem;
}
.mp-tile-name {
    font-family: 'Orbitron', monospace;
    font-size: .85rem;
    font-weight: 900;
    color: #fff;
    letter-spacing: 1px;
    margin-bottom: .25rem;
}
.mp-tile-desc {
    font-family: 'Rajdhani', sans-serif;
    font-size: .8rem;
    color: var(--text-muted);
}
.mp-tile-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-family: 'Orbitron', monospace;
    font-size: .46rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: .22rem .6rem;
    border: 1px solid;
    clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
    white-space: nowrap;
    flex-shrink: 0;
}
.mp-tile-badge.active   { color: var(--cyan); border-color: rgba(0,245,255,.4); background: rgba(0,245,255,.07); }
.mp-tile-badge.expired  { color: var(--text-muted); border-color: var(--border); background: var(--surface2); }
.mp-tile-badge.completed{ color: #34d399; border-color: rgba(52,211,153,.4); background: rgba(52,211,153,.07); }

/* big earned number */
.mp-tile-earned-block {
    padding: 1.25rem 1.25rem .75rem;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: .5rem;
}
.mp-earned-label {
    font-family: 'Orbitron', monospace;
    font-size: .44rem;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: .3rem;
    display: block;
}
.mp-earned-val {
    font-family: 'Orbitron', monospace;
    font-size: 2.1rem;
    font-weight: 900;
    color: var(--cyan);
    text-shadow: var(--glow-cyan);
    line-height: 1;
    display: block;
}
.mp-earned-of {
    font-family: 'Orbitron', monospace;
    font-size: .5rem;
    color: var(--text-muted);
    margin-top: .2rem;
    display: block;
}
.mp-roi-chip {
    font-family: 'Orbitron', monospace;
    font-size: .72rem;
    font-weight: 900;
    padding: .4rem .75rem;
    border: 1px solid;
    clip-path: polygon(5px 0, 100% 0, calc(100% - 5px) 100%, 0 100%);
    white-space: nowrap;
    flex-shrink: 0;
}
.mp-roi-chip.pos { color: var(--cyan); border-color: rgba(0,245,255,.35); background: rgba(0,245,255,.07); }
.mp-roi-chip.neg { color: var(--danger); border-color: rgba(248,113,113,.35); background: rgba(248,113,113,.07); }

/* progress bar */
.mp-prog-wrap {
    padding: 0 1.25rem .9rem;
}
.mp-prog-track {
    background: var(--surface2);
    border: 1px solid var(--border);
    height: 5px;
    overflow: hidden;
}
.mp-prog-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--blue), var(--cyan));
    box-shadow: 0 0 8px rgba(0,245,255,.5);
    transition: width 1s cubic-bezier(.4,0,.2,1);
}
.mp-prog-meta {
    display: flex;
    justify-content: space-between;
    margin-top: .35rem;
}
.mp-prog-meta span {
    font-family: 'Orbitron', monospace;
    font-size: .44rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-muted);
}
.mp-prog-meta .pct { color: var(--cyan); }

/* data rows — key:value inside tile */
.mp-data-rows {
    border-top: 1px solid var(--border);
    display: grid;
    grid-template-columns: 1fr 1fr;
}
.mp-dr {
    padding: .65rem 1.25rem;
    border-right: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}
.mp-dr:nth-child(even) { border-right: none; }
.mp-dr:nth-last-child(-n+2) { border-bottom: none; }
.mp-dr-label {
    font-family: 'Orbitron', monospace;
    font-size: .42rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: .2rem;
    display: block;
}
.mp-dr-val {
    font-family: 'Orbitron', monospace;
    font-size: .72rem;
    font-weight: 700;
    color: #fff;
    display: block;
}
.mp-dr-val.cyan  { color: var(--cyan); }
.mp-dr-val.green { color: #34d399; }
.mp-dr-val.amber { color: var(--warning); }

/* daily task inline progress */
.mp-task-inline {
    display: flex;
    align-items: center;
    gap: .5rem;
}
.mp-task-inline .mp-prog-track {
    flex: 1;
    height: 3px;
}
.mp-task-num {
    font-family: 'Orbitron', monospace;
    font-size: .62rem;
    font-weight: 700;
    color: #fff;
    white-space: nowrap;
}

/* tile footer — action button */
.mp-tile-footer {
    margin-top: auto;
    padding: .85rem 1.25rem;
    border-top: 1px solid var(--border);
    background: rgba(0,0,0,.2);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
}
.mp-tile-date {
    font-family: 'Orbitron', monospace;
    font-size: .5rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--text-muted);
}
.mp-tile-date i { color: var(--cyan); margin-right: .3rem; }
.mp-action-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-family: 'Orbitron', monospace;
    font-size: .58rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: .55rem 1.1rem;
    text-decoration: none;
    background: linear-gradient(135deg, var(--cyan), var(--blue));
    color: var(--black);
    border: none;
    clip-path: polygon(6px 0, 100% 0, calc(100% - 6px) 100%, 0 100%);
    transition: all .25s;
}
.mp-action-btn:hover { box-shadow: var(--glow-cyan-lg); transform: translateY(-2px); color: var(--black); }

/* ══════════════════════
   EMPTY STATE
══════════════════════ */
.mp-empty {
    background: var(--surface);
    border: 1px solid var(--border);
    text-align: center;
    padding: 4rem 2rem;
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
}
.mp-empty::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent);
}
.mp-empty i { font-size: 3rem; color: var(--cyan); opacity: .12; display: block; margin-bottom: 1rem; }
.mp-empty-t { font-family: 'Orbitron', monospace; font-size: .75rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--text-dim); margin-bottom: .5rem; }
.mp-empty-s { font-family: 'Rajdhani', sans-serif; font-size: .9rem; color: var(--text-muted); margin-bottom: 1.5rem; }
.mp-empty-btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-family: 'Orbitron', monospace;
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: .7rem 1.5rem;
    text-decoration: none;
    background: linear-gradient(135deg, var(--cyan), var(--blue));
    color: var(--black);
    clip-path: polygon(6px 0, 100% 0, calc(100% - 6px) 100%, 0 100%);
    transition: all .25s;
}
.mp-empty-btn:hover { box-shadow: var(--glow-cyan-lg); transform: translateY(-2px); color: var(--black); }

/* ══════════════════════
   HISTORY — minimal list
══════════════════════ */
.mp-hist-wrap {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
}
.mp-hist-wrap::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent);
}
.mp-hist-hd {
    padding: .85rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}
.mp-hist-hd-title {
    font-family: 'Orbitron', monospace;
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--cyan);
    margin: 0;
    display: flex;
    align-items: center;
    gap: .5rem;
}

/* table */
.mp-htable { width: 100%; border-collapse: collapse; }
.mp-htable th {
    background: rgba(1,21,53,.95);
    color: var(--cyan);
    font-family: 'Orbitron', monospace;
    font-size: .5rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: .7rem 1.1rem;
    border-bottom: 1px solid var(--border-bright);
    white-space: nowrap;
}
.mp-htable td {
    padding: .8rem 1.1rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.mp-htable tr:last-child td { border-bottom: none; }
.mp-htable tbody tr:hover td { background: rgba(0,245,255,.025); }

.ht-name  { font-family: 'Orbitron', monospace; font-size: .65rem; font-weight: 700; color: #fff; }
.ht-date  { font-family: 'Orbitron', monospace; font-size: .58rem; color: var(--text-dim); }
.ht-money { font-family: 'Orbitron', monospace; font-size: .7rem; font-weight: 900; }
.ht-money.earn  { color: var(--cyan); }
.ht-money.price { color: var(--text); }
.ht-unlim { font-family: 'Orbitron', monospace; font-size: .5rem; letter-spacing: 1px; color: #34d399; }

.ht-badge {
    display: inline-flex;
    align-items: center;
    gap: .28rem;
    font-family: 'Orbitron', monospace;
    font-size: .46rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: .2rem .55rem;
    border: 1px solid;
    clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
}
.ht-badge.pos       { color: var(--cyan);    border-color: rgba(0,245,255,.4);    background: rgba(0,245,255,.06); }
.ht-badge.neg       { color: var(--danger);  border-color: rgba(248,113,113,.4);  background: rgba(248,113,113,.06); }
.ht-badge.completed { color: #34d399;        border-color: rgba(52,211,153,.4);   background: rgba(52,211,153,.06); }
.ht-badge.expired   { color: var(--text-muted); border-color: var(--border);      background: var(--surface2); }

@media(max-width: 767px) {
    .mp-tiles { grid-template-columns: 1fr; }
    .mp-earned-val { font-size: 1.6rem; }
    .mp-data-rows { grid-template-columns: 1fr 1fr; }
    .mp-htable th, .mp-htable td { padding: .55rem .75rem; font-size: .72rem; }
}
</style>
@endsection

@section('content')
@include('includes.header', ['pageTitle' => 'My Packages'])

{{-- alerts --}}
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
{{-- <div class="mp-topbar">
    <div>
        <div class="mp-page-label">// Packages</div>
        <div class="mp-page-title">My Packages</div>
    </div>
    <a href="{{ route('packages.index') }}" class="mp-buy-btn">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Buy New Package</span>
    </a>
</div> --}}

{{-- ── ACTIVE PACKAGES ── --}}
<div class="mp-section-label">Active Packages &nbsp;({{ $activePackages->count() }})</div>

@if($activePackages->count() > 0)
<div class="mp-tiles">
    @foreach($activePackages as $i => $up)
    @php
        $taskPct  = $up->daily_task_limit > 0 ? ($up->today_task_count / $up->daily_task_limit) * 100 : 0;
        $earnPct  = $up->progress_percentage ?? 0;
    @endphp
    <div class="mp-tile" data-index="{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}">

        {{-- animated glow line --}}
        <div class="mp-tile-glow"></div>

        {{-- name + badge --}}
        <div class="mp-tile-top">
            <div>
                <div class="mp-tile-name">{{ $up->package->name }}</div>
                <div class="mp-tile-desc">{{ $up->package->description }}</div>
            </div>
            <span class="mp-tile-badge active">
                <i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Active
            </span>
        </div>

        {{-- big earned number --}}
        <div class="mp-tile-earned-block">
            <div>
                <span class="mp-earned-label">Total Earned</span>
                <span class="mp-earned-val">${{ number_format($up->total_earning, 2) }}</span>
                <span class="mp-earned-of">of ${{ number_format($up->package->total_earning_potential, 2) }}</span>
            </div>
            <div class="mp-roi-chip {{ $up->roi_achieved > 0 ? 'pos' : 'neg' }}">
                {{ number_format($up->roi_achieved, 1) }}% ROI
            </div>
        </div>

        {{-- earn progress --}}
        <div class="mp-prog-wrap">
            <div class="mp-prog-track">
                <div class="mp-prog-fill" style="width:{{ min($earnPct,100) }}%"></div>
            </div>
            <div class="mp-prog-meta">
                <span>Earnings Progress</span>
                <span class="pct">{{ number_format($earnPct, 1) }}%</span>
            </div>
        </div>

        {{-- 4 data cells --}}
        <div class="mp-data-rows">
            <div class="mp-dr">
                <span class="mp-dr-label">Daily Tasks</span>
                <div class="mp-task-inline">
                    <span class="mp-task-num">{{ $up->today_task_count }}/{{ $up->daily_task_limit }}</span>
                    <div class="mp-prog-track">
                        <div class="mp-prog-fill" style="width:{{ min($taskPct,100) }}%"></div>
                    </div>
                </div>
            </div>
            <div class="mp-dr">
                <span class="mp-dr-label">Today Earned</span>
                <span class="mp-dr-val cyan">${{ number_format($up->today_earning, 2) }}</span>
            </div>
            <div class="mp-dr">
                <span class="mp-dr-label">Days Running</span>
                <span class="mp-dr-val">{{ $up->days_remaining }} <span style="font-size:.52rem;color:var(--text-muted)">days</span></span>
            </div>
            <div class="mp-dr">
                <span class="mp-dr-label">Valid Until</span>
                <span class="mp-dr-val green" style="font-size:.6rem;letter-spacing:1px;">Unlimited</span>
            </div>
        </div>

        {{-- footer --}}
        <div class="mp-tile-footer">
            <div class="mp-tile-date">
                <i class="bi bi-calendar3"></i>{{ $up->created_at->format('d M Y') }}
            </div>
            <a href="{{ route('tasks.index') }}" class="mp-action-btn">
                <i class="bi bi-play-fill"></i> Start Tasks
            </a>
        </div>

    </div>
    @endforeach
</div>
@else
<div class="mp-empty">
    <i class="bi bi-box-seam"></i>
    <div class="mp-empty-t">No Active Packages</div>
    <p class="mp-empty-s">Purchase a package to start earning daily rewards</p>
    <a href="{{ route('packages.index') }}" class="mp-empty-btn">
        <i class="bi bi-cart-check"></i> Browse Packages
    </a>
</div>
@endif

{{-- ── HISTORY ── --}}
@if($expiredPackages->count() > 0)
<div class="mp-section-label" style="margin-top:.5rem;">Package History &nbsp;({{ $expiredPackages->count() }})</div>

<div class="mp-hist-wrap">
    <div class="mp-hist-hd">
        <h2 class="mp-hist-hd-title"><i class="bi bi-clock-history"></i> Past Packages</h2>
    </div>
    <div style="overflow-x:auto;">
        <table class="mp-htable">
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Purchased</th>
                    <th>Price Paid</th>
                    <th>Total Earned</th>
                    <th>ROI</th>
                    <th>Status</th>
                    <th>Valid Until</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expiredPackages as $up)
                <tr>
                    <td><span class="ht-name">{{ $up->package->name }}</span></td>
                    <td><span class="ht-date">{{ $up->created_at->format('d M Y') }}</span></td>
                    <td><span class="ht-money price">${{ number_format($up->purchase_price, 2) }}</span></td>
                    <td><span class="ht-money earn">${{ number_format($up->total_earning, 2) }}</span></td>
                    <td>
                        <span class="ht-badge {{ $up->roi_achieved > 0 ? 'pos' : 'neg' }}">
                            {{ number_format($up->roi_achieved, 1) }}%
                        </span>
                    </td>
                    <td>
                        @if($up->status === 'completed')
                            <span class="ht-badge completed"><i class="bi bi-check-all"></i> Completed</span>
                        @else
                            <span class="ht-badge expired"><i class="bi bi-clock"></i> Expired</span>
                        @endif
                    </td>
                    <td><span class="ht-unlim">Unlimited</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
