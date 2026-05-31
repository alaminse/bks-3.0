@extends('layouts.app')
@section('title', 'Transaction History')

@section('css')
<style>
/* ═══════════════════════════════════════
   TRANSACTION HISTORY — CYBERPUNK
   Full responsive rewrite
═══════════════════════════════════════ */

/* ── PAGE GRID ── */
.txh-grid {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 1.5rem;
    align-items: start;
}
@media(max-width:1199px){ .txh-grid{ grid-template-columns:1fr 260px; gap:1.25rem; } }
@media(max-width:991px)  { .txh-grid{ grid-template-columns:1fr; } }

.txh-left  { display:flex; flex-direction:column; gap:1.25rem; min-width:0; }
.txh-right { display:flex; flex-direction:column; gap:1.1rem; }

/* ════════════════════════
   FILTER BAR
════════════════════════ */
.txh-filter {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative;
}
.txh-filter::before {
    content: '';
    position: absolute; top:0; left:0; right:0; height:2px;
    background: linear-gradient(90deg,transparent,var(--cyan),var(--blue),transparent);
    pointer-events:none; z-index:1;
}
.txh-filter-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:0.85rem 1.25rem;
    background:var(--surface2); border-bottom:1px solid var(--border);
    gap:0.75rem; flex-wrap:wrap;
}
.txh-filter-title {
    font-family:'Orbitron',monospace; font-size:0.68rem; font-weight:700;
    letter-spacing:2px; color:var(--cyan); text-transform:uppercase; margin:0;
    display:flex; align-items:center; gap:0.5rem;
}
.txh-filter-clear {
    font-family:'Orbitron',monospace; font-size:0.52rem; letter-spacing:1.5px;
    text-transform:uppercase; color:var(--text-dim); text-decoration:none;
    border:1px solid var(--border); padding:0.25rem 0.65rem; transition:all 0.2s;
    clip-path:polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%);
    display:flex; align-items:center; gap:0.3rem;
}
.txh-filter-clear:hover { color:var(--danger); border-color:var(--danger); }
.txh-filter-body { padding:1rem 1.25rem; }

/* Filter inputs: 4-col row */
.txh-filter-grid {
    display:grid;
    grid-template-columns:1fr 1fr 1fr 1fr;
    gap:0.75rem; align-items:end;
}
.txh-f-group { display:flex; flex-direction:column; gap:0.3rem; }
.txh-f-label {
    font-family:'Orbitron',monospace; font-size:0.48rem; letter-spacing:2px;
    text-transform:uppercase; color:var(--text-muted); display:block;
}

/* custom select/input */
.cy-select-wrap { position:relative; }
.cy-select-wrap::after {
    content:'▾'; position:absolute; right:0.65rem; top:50%; transform:translateY(-50%);
    color:var(--cyan); font-size:0.7rem; pointer-events:none;
}
.cy-sel,.cy-inp {
    width:100%; background:var(--surface2) !important; border:1px solid var(--border) !important;
    color:var(--text) !important; border-radius:0 !important; font-family:'Rajdhani',sans-serif;
    font-size:0.85rem; font-weight:500; padding:0.48rem 0.75rem;
    transition:border-color 0.2s; outline:none; appearance:none; -webkit-appearance:none;
}
.cy-sel:focus,.cy-inp:focus {
    border-color:var(--cyan) !important; color:#fff !important;
    box-shadow:0 0 0 2px rgba(0,245,255,0.1) !important;
}
.cy-sel option { background:var(--surface2); color:var(--text); }
.cy-inp[type="date"]::-webkit-calendar-picker-indicator { filter:invert(0.5) sepia(1) saturate(5) hue-rotate(160deg); }

/* filter action row */
.txh-filter-actions {
    display:flex; gap:0.5rem;
    padding:0.75rem 1.25rem 1rem; border-top:1px solid var(--border);
}
.txh-filter-btn {
    font-family:'Orbitron',monospace; font-size:0.58rem; font-weight:700;
    letter-spacing:1.5px; text-transform:uppercase;
    background:linear-gradient(135deg,var(--cyan),var(--blue));
    color:var(--black); border:none; padding:0.55rem 1.5rem;
    cursor:pointer; transition:all 0.25s;
    clip-path:polygon(6px 0,100% 0,calc(100% - 6px) 100%,0 100%);
    display:flex; align-items:center; gap:0.4rem;
}
.txh-filter-btn:hover { box-shadow:var(--glow-cyan); transform:translateY(-1px); }

/* active pills */
.txh-active-filters {
    display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;
    padding:0.5rem 1.25rem 0.75rem;
}
.txh-af-label {
    font-family:'Orbitron',monospace; font-size:0.46rem; letter-spacing:2px;
    text-transform:uppercase; color:var(--text-muted); flex-shrink:0;
}
.txh-pill {
    display:inline-flex; align-items:center; gap:0.3rem;
    font-family:'Orbitron',monospace; font-size:0.5rem; font-weight:700; letter-spacing:1px;
    padding:0.2rem 0.55rem; border:1px solid rgba(0,245,255,0.4);
    background:rgba(0,245,255,0.07); color:var(--cyan);
    clip-path:polygon(3px 0,100% 0,calc(100% - 3px) 100%,0 100%);
}
.txh-pill a { color:rgba(0,245,255,0.6); text-decoration:none; margin-left:0.2rem; font-size:0.7rem; }
.txh-pill a:hover { color:var(--danger); }

/* ════════════════════════
   TABLE PANEL
════════════════════════ */
.txh-panel { background:var(--surface); border:1px solid var(--border); min-width:0; }
.txh-panel-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:0.85rem 1.25rem; background:var(--surface2);
    border-bottom:1px solid var(--border); flex-wrap:wrap; gap:0.65rem;
}
.txh-panel-title {
    font-family:'Orbitron',monospace; font-size:0.68rem; font-weight:700;
    letter-spacing:2px; color:var(--cyan); text-transform:uppercase; margin:0;
    display:flex; align-items:center; gap:0.5rem;
}
.txh-back-btn {
    font-family:'Orbitron',monospace; font-size:0.52rem; letter-spacing:1.5px;
    text-transform:uppercase; color:var(--text-dim); text-decoration:none;
    border:1px solid var(--border); padding:0.25rem 0.65rem; transition:all 0.2s;
    clip-path:polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%);
    display:flex; align-items:center; gap:0.35rem;
}
.txh-back-btn:hover { color:var(--cyan); border-color:var(--cyan); }

/* KEY FIX: scroll wrapper forces table to stay inside column */
.txh-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(0,245,255,0.2) transparent;
}
.txh-scroll::-webkit-scrollbar { height:4px; }
.txh-scroll::-webkit-scrollbar-thumb { background:rgba(0,245,255,0.25); }

/* Table itself — min-width prevents columns from squishing */
.txh-table {
    width:100%; border-collapse:collapse;
    min-width: 540px; /* allows scrolling on small screens */
}
.txh-table th {
    font-family:'Orbitron',monospace; font-size:0.5rem; letter-spacing:2px;
    text-transform:uppercase; color:var(--cyan); padding:0.75rem 0.85rem;
    white-space:nowrap; border-bottom:1px solid var(--border-bright);
    background:rgba(1,21,53,0.9);
}
.txh-table td {
    padding:0.75rem 0.85rem; border-bottom:1px solid var(--border);
    vertical-align:middle; font-size:0.85rem; color:var(--text);
}
.txh-table tr:last-child td { border-bottom:none; }
.txh-table tbody tr:hover td { background:rgba(0,245,255,0.025); }

/* ── CELLS ── */
.tc-id   { font-family:'Orbitron',monospace; font-size:0.58rem; color:var(--text-muted); }
.tc-date { font-family:'Orbitron',monospace; font-size:0.62rem; font-weight:700; color:#fff; white-space:nowrap; }
.tc-time { font-size:0.62rem; color:var(--text-muted); margin-top:0.1rem; }

.tc-type {
    display:inline-flex; align-items:center; gap:0.35rem;
    font-family:'Orbitron',monospace; font-size:0.48rem; font-weight:700;
    letter-spacing:0.8px; text-transform:uppercase;
    padding:0.25rem 0.55rem; border:1px solid; background:transparent;
    clip-path:polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%); white-space:nowrap;
}
.tc-type i { font-size:0.65rem; }
.tc-deposit    { color:var(--cyan);    border-color:rgba(0,245,255,0.4);   background:rgba(0,245,255,0.06); }
.tc-task       { color:#34d399;        border-color:rgba(52,211,153,0.4);  background:rgba(52,211,153,0.06); }
.tc-package    { color:#60a5fa;        border-color:rgba(96,165,250,0.4);  background:rgba(96,165,250,0.06); }
.tc-withdraw   { color:var(--warning); border-color:rgba(251,191,36,0.4);  background:rgba(251,191,36,0.06); }
.tc-referral   { color:#a78bfa;        border-color:rgba(167,139,250,0.4); background:rgba(167,139,250,0.06); }
.tc-adjustment { color:var(--text-dim); border-color:var(--border);        background:var(--surface2); }

.tc-desc {
    font-size:0.82rem; color:var(--text); max-width:160px;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.tc-amount {
    font-family:'Orbitron',monospace; font-size:0.88rem; font-weight:900;
    white-space:nowrap; text-align:right;
}
.tc-amount.cr { color:#34d399; }
.tc-amount.db { color:var(--danger); }
.tc-bal { font-family:'Orbitron',monospace; font-size:0.68rem; color:var(--text-dim); text-align:right; white-space:nowrap; }

.tc-status {
    display:inline-flex; align-items:center; gap:0.28rem;
    font-family:'Orbitron',monospace; font-size:0.46rem; font-weight:700;
    letter-spacing:0.8px; text-transform:uppercase;
    padding:0.22rem 0.55rem; border:1px solid; white-space:nowrap;
    clip-path:polygon(3px 0,100% 0,calc(100% - 3px) 100%,0 100%);
}
.tc-status.completed { color:var(--cyan);    border-color:rgba(0,245,255,0.4);   background:rgba(0,245,255,0.07); }
.tc-status.pending   { color:var(--warning); border-color:rgba(251,191,36,0.4);  background:rgba(251,191,36,0.07); }
.tc-status.rejected,
.tc-status.failed    { color:var(--danger);  border-color:rgba(248,113,113,0.4); background:rgba(248,113,113,0.07); }

/* empty state */
.txh-empty { text-align:center; padding:3.5rem 1rem; }
.txh-empty i { font-size:2.5rem; color:var(--text-muted); display:block; margin-bottom:0.85rem; opacity:0.35; }
.txh-empty h5 { font-family:'Orbitron',monospace; font-size:0.68rem; color:var(--text-dim); letter-spacing:2px; margin-bottom:0.35rem; }
.txh-empty p  { font-size:0.78rem; color:var(--text-muted); margin:0; }

/* pagination footer */
.txh-footer {
    background:var(--surface2); border-top:1px solid var(--border);
    padding:0.8rem 1.25rem;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:0.65rem;
}
.txh-count { font-family:'Orbitron',monospace; font-size:0.5rem; letter-spacing:1.5px; text-transform:uppercase; color:var(--text-muted); }
.txh-count strong { color:var(--cyan); }
.pagination { margin:0; gap:2px; }
.page-link {
    font-family:'Orbitron',monospace !important; font-size:0.52rem !important; letter-spacing:1px;
    background:var(--surface2) !important; border:1px solid var(--border) !important;
    color:var(--text-dim) !important; border-radius:0 !important;
    padding:0.35rem 0.6rem; transition:all 0.2s;
}
.page-link:hover { border-color:var(--cyan) !important; color:var(--cyan) !important; background:rgba(0,245,255,0.06) !important; }
.page-item.active .page-link { background:linear-gradient(135deg,var(--cyan),var(--blue)) !important; color:var(--black) !important; border-color:transparent !important; }
.page-item.disabled .page-link { opacity:0.35 !important; }

/* ════════════════════════
   RIGHT SIDEBAR
════════════════════════ */
.txh-stat {
    background:var(--surface); border:1px solid var(--border);
    padding:1.1rem 1.25rem; position:relative; overflow:hidden;
    clip-path:polygon(0 0,calc(100% - 10px) 0,100% 10px,100% 100%,10px 100%,0 calc(100% - 10px));
    transition:all 0.3s;
}
.txh-stat::before {
    content:''; position:absolute; top:0; left:0; right:0; height:2px;
    background:linear-gradient(90deg,transparent,var(--sc-color,var(--cyan)),transparent);
    transform:scaleX(0); transition:transform 0.4s;
}
.txh-stat:hover { border-color:var(--border-bright); }
.txh-stat:hover::before { transform:scaleX(1); }
.txh-stat-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.6rem; }
.txh-stat-label { font-family:'Orbitron',monospace; font-size:0.48rem; letter-spacing:3px; text-transform:uppercase; color:var(--text-dim); }
.txh-stat-icon {
    width:30px; height:30px; display:flex; align-items:center; justify-content:center;
    font-size:0.8rem; color:var(--sc-color,var(--cyan));
    border:1px solid rgba(0,0,0,0.2); background:rgba(0,0,0,0.15);
    clip-path:polygon(4px 0,100% 0,100% calc(100% - 4px),calc(100% - 4px) 100%,0 100%,0 4px);
}
.txh-stat-val { font-family:'Orbitron',monospace; font-size:1.2rem; font-weight:900; color:#fff; line-height:1; margin-bottom:0.3rem; }
.txh-stat-val span { color:var(--sc-color,var(--cyan)); font-size:0.75em; margin-right:1px; }
.txh-stat-sub { font-size:0.65rem; color:var(--text-muted); display:flex; align-items:center; gap:0.3rem; }

.txh-widget { background:var(--surface); border:1px solid var(--border); overflow:hidden; }
.txh-widget-head {
    padding:0.8rem 1.25rem; background:var(--surface2); border-bottom:1px solid var(--border);
    font-family:'Orbitron',monospace; font-size:0.65rem; font-weight:700;
    letter-spacing:2px; color:var(--cyan); text-transform:uppercase;
    display:flex; align-items:center; gap:0.5rem;
}

/* breakdown */
.txh-bk-body { padding:0.9rem 1.25rem; display:flex; flex-direction:column; gap:0.75rem; }
.txh-bk-row  { display:flex; flex-direction:column; gap:0.28rem; }
.txh-bk-top  { display:flex; align-items:center; justify-content:space-between; }
.txh-bk-name { display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:var(--text-dim); }
.txh-bk-dot  { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
.txh-bk-count { font-family:'Orbitron',monospace; font-size:0.6rem; font-weight:700; color:#fff; }
.txh-bk-bar-bg   { background:var(--surface2); border:1px solid var(--border); height:4px; }
.txh-bk-bar-fill { height:100%; transition:width 1s cubic-bezier(.4,0,.2,1); }

/* quick nav */
.txh-nav-link {
    display:flex; align-items:center; gap:0.65rem;
    padding:0.65rem 1.25rem; color:var(--text-dim); text-decoration:none;
    font-family:'Orbitron',monospace; font-size:0.6rem; letter-spacing:1px; text-transform:uppercase;
    border-bottom:1px solid var(--border); transition:color 0.2s, background 0.2s;
}
.txh-nav-link:last-child { border-bottom:none; }
.txh-nav-link i { width:16px; text-align:center; font-size:0.85rem; flex-shrink:0; }
.txh-nav-link:hover { color:var(--cyan); background:rgba(0,245,255,0.04); }

.pulse { width:8px; height:8px; background:var(--cyan); border-radius:50%; display:inline-block;
         animation:blink 1.5s infinite; box-shadow:0 0 6px var(--cyan); flex-shrink:0; }
@keyframes blink { 0%,100%{opacity:1}50%{opacity:.4} }

/* ════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════ */

/* ── Tablet ≤991px: sidebar flows below as a row ── */
@media(max-width:991px){
    .txh-right { flex-direction:row; flex-wrap:wrap; gap:1rem; }
    .txh-stat   { flex:1 1 calc(33% - 0.7rem); min-width:130px; }
    .txh-widget { flex:1 1 calc(50% - 0.5rem); min-width:220px; }

    /* filter: 2x2 */
    .txh-filter-grid { grid-template-columns:1fr 1fr; }
}

/* ── Mobile ≤767px ── */
@media(max-width:767px){
    /* stats stay in a row, widgets full width */
    .txh-stat   { flex:1 1 calc(33% - 0.5rem); min-width:110px; clip-path:none; }
    .txh-widget { flex:1 1 100%; min-width:unset; }

    /* hide balance-after column */
    .col-after { display:none; }

    /* table can be a bit narrower */
    .txh-table { min-width:460px; }
}

/* ── Small mobile ≤575px ── */
@media(max-width:575px){
    /* filter: single col */
    .txh-filter-grid { grid-template-columns:1fr; }
    .txh-filter-body { padding:0.85rem 1rem; }
    .txh-filter-actions { padding:0.5rem 1rem 0.85rem; }
    .txh-filter-btn { width:100%; justify-content:center; }

    /* stat cards: 3 equal columns */
    .txh-stat { flex:1 1 calc(33% - 0.4rem); min-width:90px; padding:0.75rem 0.6rem; }
    .txh-stat-val { font-size:0.9rem; }
    .txh-stat-label { font-size:0.4rem; letter-spacing:1px; }
    .txh-stat-sub, .txh-stat-icon { display:none; }

    /* table */
    .txh-table { min-width:340px; }
    .txh-table th, .txh-table td { padding:0.45rem 0.5rem; }

    /* hide ID col too */
    .col-id, .col-after { display:none; }

    /* shrink text */
    .tc-date { font-size:0.58rem; }
    .tc-time { font-size:0.56rem; }
    .tc-type { font-size:0.42rem; padding:0.18rem 0.38rem; }
    .tc-type i { display:none; } /* icon only on tiny — just show text */
    .tc-desc { max-width:90px; font-size:0.72rem; }
    .tc-amount { font-size:0.78rem; }
    .tc-status { font-size:0.42rem; padding:0.18rem 0.38rem; }
    .tc-status i { display:none; }

    /* footer */
    .txh-footer { flex-direction:column; align-items:flex-start; padding:0.65rem 1rem; }
    .txh-count  { font-size:0.44rem; }
    .page-link  { font-size:0.44rem !important; padding:0.28rem 0.45rem; }

    .txh-panel-head { padding:0.75rem 1rem; }
    .txh-panel-title { font-size:0.6rem; }
}
</style>
@endsection

@section('content')
    @include('includes.header', [
        'pageTitle' => 'Transaction History',
        'backRoute' => route('wallet.index'),
        'backText'  => 'Back to Wallet',
    ])

    <div class="txh-grid">

        {{-- ════ LEFT ════ --}}
        <div class="txh-left">

            {{-- FILTER BAR --}}
            <div class="txh-filter">
                <div class="txh-filter-head">
                    <h2 class="txh-filter-title">
                        <i class="bi bi-funnel-fill"></i> Filters
                    </h2>
                    @if(request()->hasAny(['type','direction','date_from','date_to']))
                        <a href="{{ route('wallet.transactions') }}" class="txh-filter-clear">
                            <i class="bi bi-x-circle"></i> Clear All
                        </a>
                    @endif
                </div>

                <div class="txh-filter-body">
                    <form method="GET" action="{{ route('wallet.transactions') }}">
                        <div class="txh-filter-grid">
                            <div class="txh-f-group">
                                <label class="txh-f-label">Type</label>
                                <div class="cy-select-wrap">
                                    <select name="type" class="cy-sel">
                                        <option value="all">All Types</option>
                                        @foreach(['deposit'=>'Deposit','task'=>'Task Earning','package'=>'Package','withdraw'=>'Withdrawal','referral'=>'Referral','adjustment'=>'Adjustment'] as $val=>$lbl)
                                            <option value="{{ $val }}" {{ request('type')===$val?'selected':'' }}>{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="txh-f-group">
                                <label class="txh-f-label">Direction</label>
                                <div class="cy-select-wrap">
                                    <select name="direction" class="cy-sel">
                                        <option value="all">All</option>
                                        <option value="credit" {{ request('direction')==='credit'?'selected':'' }}>Credit (+)</option>
                                        <option value="debit"  {{ request('direction')==='debit' ?'selected':'' }}>Debit (−)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="txh-f-group">
                                <label class="txh-f-label">From Date</label>
                                <input type="date" name="date_from" class="cy-inp" value="{{ request('date_from') }}">
                            </div>
                            <div class="txh-f-group">
                                <label class="txh-f-label">To Date</label>
                                <input type="date" name="date_to" class="cy-inp" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="txh-filter-actions">
                            <button type="submit" class="txh-filter-btn">
                                <i class="bi bi-funnel-fill"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>

                @if(request()->hasAny(['type','direction','date_from','date_to']))
                    <div class="txh-active-filters">
                        <span class="txh-af-label">Active:</span>
                        @if(request('type') && request('type') !== 'all')
                            <span class="txh-pill">Type: {{ ucfirst(request('type')) }} <a href="{{ request()->fullUrlWithQuery(['type'=>null]) }}">×</a></span>
                        @endif
                        @if(request('direction') && request('direction') !== 'all')
                            <span class="txh-pill">Dir: {{ ucfirst(request('direction')) }} <a href="{{ request()->fullUrlWithQuery(['direction'=>null]) }}">×</a></span>
                        @endif
                        @if(request('date_from'))
                            <span class="txh-pill">From: {{ request('date_from') }}</span>
                        @endif
                        @if(request('date_to'))
                            <span class="txh-pill">To: {{ request('date_to') }}</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- TABLE --}}
            <div class="txh-panel">
                <div class="txh-panel-head">
                    <h2 class="txh-panel-title">
                        <span class="pulse"></span> All Transactions
                    </h2>
                    <a href="{{ route('wallet.index') }}" class="txh-back-btn">
                        <i class="bi bi-arrow-left"></i> Wallet
                    </a>
                </div>

                {{-- scroll wrapper is THE key fix for table overflow --}}
                <div class="txh-scroll">
                    <table class="txh-table">
                        <thead>
                            <tr>
                                <th class="col-id">#</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th style="text-align:right">Amount</th>
                                <th class="col-after" style="text-align:right">After</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                                @php
                                    $typeMap = [
                                        'deposit'    => ['icon'=>'arrow-down-circle-fill', 'cls'=>'tc-deposit',    'label'=>'Deposit'],
                                        'task'       => ['icon'=>'check-circle-fill',       'cls'=>'tc-task',       'label'=>'Task'],
                                        'package'    => ['icon'=>'box-seam-fill',            'cls'=>'tc-package',    'label'=>'Package'],
                                        'withdraw'   => ['icon'=>'arrow-up-circle-fill',     'cls'=>'tc-withdraw',   'label'=>'Withdraw'],
                                        'referral'   => ['icon'=>'people-fill',              'cls'=>'tc-referral',   'label'=>'Referral'],
                                        'adjustment' => ['icon'=>'gear-fill',                'cls'=>'tc-adjustment', 'label'=>'Adjust'],
                                    ];
                                    $cfg = $typeMap[$txn->type] ?? ['icon'=>'circle-fill','cls'=>'tc-adjustment','label'=>ucfirst($txn->type ?? '—')];
                                    $dir = $txn->direction ?? (in_array($txn->type, ['deposit','task','referral']) ? 'credit' : 'debit');
                                    $sc  = strtolower($txn->status ?? 'completed');
                                @endphp
                                <tr>
                                    <td class="col-id"><span class="tc-id">{{ $txn->id }}</span></td>
                                    <td>
                                        <div class="tc-date">{{ $txn->created_at->format('d M Y') }}</div>
                                        <div class="tc-time">{{ $txn->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="tc-type {{ $cfg['cls'] }}">
                                            <i class="bi bi-{{ $cfg['icon'] }}"></i>{{ $cfg['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="tc-desc" title="{{ $txn->description ?? '' }}">
                                            {{ Str::limit($txn->description ?? '—', 40) }}
                                        </div>
                                    </td>
                                    <td style="text-align:right">
                                        <div class="tc-amount {{ $dir === 'credit' ? 'cr' : 'db' }}">
                                            {{ $dir === 'credit' ? '+' : '−' }}${{ number_format($txn->amount, 2) }}
                                        </div>
                                    </td>
                                    <td class="col-after" style="text-align:right">
                                        <div class="tc-bal">${{ number_format($txn->balance_after ?? 0, 2) }}</div>
                                    </td>
                                    <td>
                                        <span class="tc-status {{ $sc }}">
                                            <i class="bi {{ $sc === 'pending' ? 'bi-hourglass-split' : ($sc === 'rejected' || $sc === 'failed' ? 'bi-x-circle-fill' : 'bi-check-circle-fill') }}"></i>
                                            {{ ucfirst($sc) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="txh-empty">
                                            <i class="bi bi-journal-x"></i>
                                            <h5>No transactions found</h5>
                                            <p>Try adjusting your filters or make a deposit to get started.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->total() > 0)
                    <div class="txh-footer">
                        <div class="txh-count">
                            Showing <strong>{{ $transactions->firstItem() }}</strong>–<strong>{{ $transactions->lastItem() }}</strong>
                            of <strong>{{ $transactions->total() }}</strong>
                        </div>
                        {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

        </div>{{-- /left --}}

        {{-- ════ RIGHT SIDEBAR ════ --}}
        <div class="txh-right">

            <div class="txh-stat" style="--sc-color:var(--cyan);">
                <div class="txh-stat-top">
                    <div class="txh-stat-label">Balance</div>
                    <div class="txh-stat-icon"><i class="bi bi-wallet2"></i></div>
                </div>
                <div class="txh-stat-val"><span>$</span>{{ number_format($wallet->balance ?? 0, 2) }}</div>
                <div class="txh-stat-sub"><i class="bi bi-circle-fill" style="font-size:0.35rem;"></i> Total</div>
            </div>

            <div class="txh-stat" style="--sc-color:#34d399;">
                <div class="txh-stat-top">
                    <div class="txh-stat-label">Available</div>
                    <div class="txh-stat-icon"><i class="bi bi-unlock"></i></div>
                </div>
                <div class="txh-stat-val"><span>$</span>{{ number_format($wallet->available_balance ?? 0, 2) }}</div>
                <div class="txh-stat-sub"><i class="bi bi-lightning-charge-fill"></i> Ready</div>
            </div>

            <div class="txh-stat" style="--sc-color:var(--warning);">
                <div class="txh-stat-top">
                    <div class="txh-stat-label">Locked</div>
                    <div class="txh-stat-icon"><i class="bi bi-lock-fill"></i></div>
                </div>
                <div class="txh-stat-val"><span>$</span>{{ number_format($wallet->locked_balance ?? 0, 2) }}</div>
                <div class="txh-stat-sub"><i class="bi bi-hourglass-split"></i> Pending</div>
            </div>

            {{-- Breakdown --}}
            <div class="txh-widget">
                <div class="txh-widget-head"><i class="bi bi-pie-chart-fill"></i> Breakdown</div>
                <div class="txh-bk-body">
                    @php
                        $total = method_exists($transactions,'total') ? $transactions->total() : $transactions->count();
                        $breakdown = [
                            ['label'=>'Deposits',    'color'=>'#00f5ff','type'=>'deposit'],
                            ['label'=>'Withdrawals', 'color'=>'#f87171','type'=>'withdraw'],
                            ['label'=>'Tasks',       'color'=>'#34d399','type'=>'task'],
                            ['label'=>'Packages',    'color'=>'#60a5fa','type'=>'package'],
                            ['label'=>'Referrals',   'color'=>'#a78bfa','type'=>'referral'],
                        ];
                    @endphp
                    @foreach($breakdown as $bk)
                        @php
                            $cnt = $typeCounts[$bk['type']] ?? 0;
                            $pct = $total > 0 ? round(($cnt/$total)*100) : 0;
                        @endphp
                        <div class="txh-bk-row">
                            <div class="txh-bk-top">
                                <div class="txh-bk-name">
                                    <span class="txh-bk-dot" style="background:{{ $bk['color'] }};box-shadow:0 0 4px {{ $bk['color'] }};"></span>
                                    {{ $bk['label'] }}
                                </div>
                                <div class="txh-bk-count">{{ $cnt }}</div>
                            </div>
                            <div class="txh-bk-bar-bg">
                                <div class="txh-bk-bar-fill" style="width:{{ $pct }}%;background:{{ $bk['color'] }};box-shadow:0 0 4px {{ $bk['color'] }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Quick Nav --}}
            <div class="txh-widget">
                <div class="txh-widget-head"><i class="bi bi-compass-fill"></i> Quick Nav</div>
                <div>
                    <a href="{{ route('wallet.index') }}" class="txh-nav-link"><i class="bi bi-wallet2"></i> My Wallet</a>
                    <a href="{{ route('wallet.deposit') }}" class="txh-nav-link"><i class="bi bi-plus-circle-fill"></i> Deposit</a>
                    <a href="{{ route('withdraw.index') }}" class="txh-nav-link"><i class="bi bi-send-fill"></i> Withdraw</a>
                </div>
            </div>

        </div>{{-- /right --}}
    </div>{{-- /grid --}}

@endsection
