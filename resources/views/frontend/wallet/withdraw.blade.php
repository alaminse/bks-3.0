@extends('layouts.app')
@section('title', 'Withdraw')

@section('css')
<style>
/* ═══════════════════════════════════════
   WITHDRAW PAGE — CYBERPUNK REDESIGN
   Matches deposit / wallet design system
═══════════════════════════════════════ */

/* ── PAGE GRID ── */
.wd-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.5rem;
    align-items: start;
    margin-bottom: 1.5rem;
}
@media(max-width:1199px){ .wd-grid{ grid-template-columns:1fr 280px; gap:1.25rem; } }
@media(max-width:991px)  { .wd-grid{ grid-template-columns:1fr; } }

/* ── SHARED PANEL ── */
.wd-panel {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
}
.wd-panel::before {
    content: '';
    position: absolute; top:0; left:0; right:0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), var(--blue), transparent);
    transform: scaleX(0); transition: transform 0.4s;
}
.wd-panel:hover::before { transform: scaleX(1); }

.wd-panel-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.9rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
    gap: 0.75rem;
}
.wd-panel-title {
    font-family: 'Orbitron', monospace;
    font-size: 0.7rem; font-weight: 700;
    letter-spacing: 2px; color: var(--cyan);
    text-transform: uppercase; margin: 0;
    display: flex; align-items: center; gap: 0.5rem;
}
.wd-panel-body { padding: 1.5rem; }

/* ── METHOD INFO BOX ── */
.wd-method-box {
    display: flex; align-items: center; gap: 1rem;
    background: linear-gradient(135deg, var(--surface2), rgba(0,71,255,0.08));
    border: 1px solid var(--border-bright);
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    position: relative; overflow: hidden;
}
.wd-method-box::before {
    content: '';
    position: absolute; top:0; left:0; right:0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent);
}
.wd-method-icon {
    width: 44px; height: 44px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: var(--cyan);
    border: 1px solid rgba(0,245,255,0.3);
    background: rgba(0,245,255,0.07);
    clip-path: polygon(5px 0,100% 0,100% calc(100% - 5px),calc(100% - 5px) 100%,0 100%,0 5px);
}
.wd-method-info { flex: 1; }
.wd-method-name {
    font-family: 'Orbitron', monospace;
    font-size: 0.65rem; font-weight: 700;
    letter-spacing: 1.5px; color: #fff;
    text-transform: uppercase; margin-bottom: 0.25rem;
}
.wd-method-sub { font-size: 0.78rem; color: var(--text-muted); }
.wd-method-tag {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-family: 'Orbitron', monospace;
    font-size: 0.48rem; font-weight: 700; letter-spacing: 1px;
    padding: 0.2rem 0.55rem; border: 1px solid;
    color: #34d399; border-color: rgba(52,211,153,0.4);
    background: rgba(52,211,153,0.07);
    clip-path: polygon(3px 0, 100% 0, calc(100% - 3px) 100%, 0 100%);
}

/* ── FORM STYLES ── */
.wd-form-group { margin-bottom: 1.25rem; }
.wd-form-label {
    font-family: 'Orbitron', monospace;
    font-size: 0.55rem; font-weight: 700;
    letter-spacing: 2px; text-transform: uppercase;
    color: var(--text-dim); margin-bottom: 0.5rem; display: block;
}
.wd-form-label .req { color: var(--danger); margin-left: 2px; }

.wd-input-wrap { position: relative; }
.wd-input-icon {
    position: absolute; left: 0.85rem; top: 50%;
    transform: translateY(-50%);
    color: var(--cyan); font-size: 0.95rem; pointer-events: none;
    z-index: 2;
}
.wd-input {
    width: 100%;
    background: var(--surface2) !important;
    border: 1px solid var(--border) !important;
    color: #fff !important;
    border-radius: 0 !important;
    font-family: 'Rajdhani', sans-serif;
    font-size: 0.95rem; font-weight: 500;
    padding: 0.6rem 0.85rem;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
    -webkit-appearance: none;
}
.wd-input.has-icon-left  { padding-left: 2.5rem; }
.wd-input.has-max-btn    { padding-right: 5.5rem; }
.wd-input::placeholder   { color: var(--text-muted) !important; font-size: 0.88rem; }
.wd-input:focus {
    border-color: var(--cyan) !important;
    box-shadow: 0 0 0 2px rgba(0,245,255,0.1) !important;
}
.wd-input.is-invalid { border-color: var(--danger) !important; }

/* MAX button inside input */
.wd-max-btn {
    position: absolute; right: 0; top: 0; bottom: 0;
    display: flex; align-items: center;
    font-family: 'Orbitron', monospace;
    font-size: 0.52rem; font-weight: 700; letter-spacing: 1.5px;
    padding: 0 0.85rem;
    color: var(--cyan);
    border: none; border-left: 1px solid var(--border);
    background: var(--surface2);
    cursor: pointer; transition: all 0.2s;
}
.wd-max-btn:hover { color: var(--black); background: var(--cyan); }

.wd-balance-row {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 0.4rem;
}
.wd-balance-hint {
    font-size: 0.72rem; color: var(--text-muted);
    display: flex; align-items: center; gap: 0.3rem;
}
.wd-balance-avail {
    font-family: 'Orbitron', monospace;
    font-size: 0.6rem; font-weight: 700;
    color: #34d399; letter-spacing: 0.5px;
    display: flex; align-items: center; gap: 0.3rem;
}

.wd-error { color: var(--danger); font-size: 0.75rem; margin-top: 0.35rem; font-family: 'Orbitron', monospace; letter-spacing: 0.5px; }

/* validation error list */
.wd-error-list {
    background: rgba(248,113,113,0.06);
    border: 1px solid rgba(248,113,113,0.4);
    border-left: 3px solid var(--danger);
    padding: 0.85rem 1rem;
    margin-bottom: 1.25rem;
}
.wd-error-list ul { margin: 0; padding-left: 1.25rem; }
.wd-error-list li { font-size: 0.82rem; color: var(--danger); margin-bottom: 0.2rem; }
.wd-error-list li:last-child { margin-bottom: 0; }

/* form action buttons */
.wd-btn {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-family: 'Orbitron', monospace;
    font-size: 0.65rem; font-weight: 700;
    letter-spacing: 1.5px; text-transform: uppercase;
    padding: 0.75rem 1.5rem;
    border: none; cursor: pointer; transition: all 0.25s;
    text-decoration: none;
    clip-path: polygon(7px 0, 100% 0, calc(100% - 7px) 100%, 0 100%);
    width: 100%; justify-content: center; margin-bottom: 0.65rem;
}
.wd-btn.primary {
    background: linear-gradient(135deg, var(--cyan), var(--blue));
    color: var(--black);
}
.wd-btn.primary:hover {
    box-shadow: var(--glow-cyan);
    transform: translateY(-2px); color: var(--black);
}
.wd-btn.outline {
    background: var(--surface2);
    border: 1px solid var(--border-bright) !important;
    color: var(--text-dim);
}
.wd-btn.outline:hover { border-color: var(--cyan) !important; color: var(--cyan); }

/* ── RIGHT SIDEBAR ── */
.wd-right { display: flex; flex-direction: column; gap: 1.25rem; }

/* balance cards row */
.wd-bal-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.85rem;
}

/* available (hero) balance */
.wd-avail-card {
    background: linear-gradient(135deg, #011a0e, #010e24, #021d45);
    border: 1px solid rgba(52,211,153,0.25);
    padding: 1.5rem;
    position: relative; overflow: hidden;
    clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));
}
.wd-avail-card::before {
    content: '';
    position: absolute; top:0; left:0; right:0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #34d399, transparent);
}
.wd-avail-card::after {
    content: '';
    position: absolute; top:-60px; right:-60px;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(52,211,153,0.12), transparent 65%);
    pointer-events: none;
}
.wd-avail-pre {
    font-family: 'Orbitron', monospace;
    font-size: 0.5rem; letter-spacing: 4px;
    text-transform: uppercase; color: #34d399;
    opacity: 0.85; margin-bottom: 0.4rem;
    position: relative; z-index: 1;
}
.wd-avail-val {
    font-family: 'Orbitron', monospace;
    font-size: 1.75rem; font-weight: 900;
    color: #fff; line-height: 1;
    text-shadow: 0 0 30px rgba(52,211,153,0.25);
    position: relative; z-index: 1;
}
.wd-avail-sub {
    font-size: 0.75rem; color: rgba(255,255,255,0.4);
    margin-top: 0.4rem; position: relative; z-index: 1;
}

/* small stat cards */
.wd-stat-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; }
.wd-stat {
    background: var(--surface);
    border: 1px solid var(--border);
    padding: 1rem;
    position: relative; overflow: hidden;
    clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));
    transition: border-color 0.25s;
}
.wd-stat:hover { border-color: var(--border-bright); }
.wd-stat-label {
    font-family: 'Orbitron', monospace;
    font-size: 0.48rem; letter-spacing: 2px;
    text-transform: uppercase; color: var(--text-muted);
    margin-bottom: 0.4rem;
}
.wd-stat-val {
    font-family: 'Orbitron', monospace;
    font-size: 0.95rem; font-weight: 700;
}
.wd-stat-val.cyan   { color: var(--cyan); text-shadow: var(--glow-cyan); }
.wd-stat-val.yellow { color: var(--warning); }

/* notes panel */
.wd-notes {
    background: var(--surface);
    border: 1px solid var(--border);
}
.wd-notes-head {
    padding: 0.85rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
    font-family: 'Orbitron', monospace;
    font-size: 0.7rem; font-weight: 700;
    letter-spacing: 2px; color: var(--warning);
    text-transform: uppercase;
    display: flex; align-items: center; gap: 0.5rem;
}
.wd-notes-body { padding: 1.1rem 1.25rem; }
.wd-note-item {
    display: flex; align-items: flex-start; gap: 0.75rem;
    padding: 0.55rem 0;
    border-bottom: 1px solid var(--border);
    font-size: 0.82rem; color: var(--text-dim);
}
.wd-note-item:last-child { border-bottom: none; }
.wd-note-key {
    font-family: 'Orbitron', monospace;
    font-size: 0.52rem; font-weight: 700; letter-spacing: 1px;
    color: var(--cyan); flex-shrink: 0; min-width: 80px;
    text-transform: uppercase; padding-top: 0.1rem;
}
.wd-note-val { color: var(--text-dim); line-height: 1.5; }
.wd-note-val strong { color: #fff; }

/* pulse */
.pulse { width:8px; height:8px; background:var(--cyan); border-radius:50%;
         display:inline-block; animation:blink 1.5s infinite;
         box-shadow:0 0 6px var(--cyan); flex-shrink:0; }
@keyframes blink { 0%,100%{opacity:1}50%{opacity:.4} }

/* ══════════════════════════════════
   RECENT WITHDRAWALS TABLE
══════════════════════════════════ */
.wd-history { background: var(--surface); border: 1px solid var(--border); }
.wd-history-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.9rem 1.25rem; background: var(--surface2);
    border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 0.75rem;
}
.wd-history-title {
    font-family: 'Orbitron', monospace; font-size: 0.7rem; font-weight: 700;
    letter-spacing: 2px; color: var(--cyan); text-transform: uppercase; margin: 0;
    display: flex; align-items: center; gap: 0.5rem;
}

.wd-table { width: 100%; border-collapse: collapse; }
.wd-table th {
    background: rgba(1,21,53,0.8);
    color: var(--cyan); font-family: 'Orbitron', monospace;
    font-size: 0.52rem; letter-spacing: 2px; text-transform: uppercase;
    padding: 0.8rem 1rem; white-space: nowrap;
    border-bottom: 1px solid var(--border-bright);
}
.wd-table td {
    padding: 0.85rem 1rem;
    border-bottom: 1px solid var(--border);
    font-size: 0.88rem; color: var(--text); vertical-align: middle;
}
.wd-table tr:last-child td { border-bottom: none; }
.wd-table tbody tr:hover td { background: rgba(0,245,255,0.025); }

.wt-date  { font-family:'Orbitron',monospace; font-size:0.62rem; font-weight:700; color:#fff; }
.wt-time  { font-size:0.68rem; color:var(--text-muted); margin-top:0.15rem; }
.wt-ref {
    font-family: 'Orbitron', monospace; font-size: 0.58rem;
    letter-spacing: 1px; color: var(--text-muted);
    border: 1px solid var(--border); padding: 0.18rem 0.5rem;
    display: inline-block;
}
.wt-amount {
    font-family: 'Orbitron', monospace; font-size: 0.88rem;
    font-weight: 700; color: #34d399; white-space: nowrap;
}
.wt-method-badge {
    display: inline-flex; align-items: center; gap: 0.35rem;
    font-family: 'Orbitron', monospace; font-size: 0.5rem; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 0.22rem 0.6rem; border: 1px solid;
    color: var(--warning); border-color: rgba(251,191,36,0.4);
    background: rgba(251,191,36,0.06);
    clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
}
.wt-account { font-size: 0.88rem; color: #fff; font-weight: 600; }
.wt-addr    { font-family:'Orbitron',monospace; font-size:0.58rem; color:var(--text-muted); letter-spacing:0.5px; }

.wt-status {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-family: 'Orbitron', monospace; font-size: 0.5rem; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 0.22rem 0.6rem; border: 1px solid;
    clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
}
.wt-status.pending  { color:var(--warning); border-color:rgba(251,191,36,0.4);  background:rgba(251,191,36,0.06); }
.wt-status.approved { color:#34d399;        border-color:rgba(52,211,153,0.4);  background:rgba(52,211,153,0.06); }
.wt-status.rejected { color:var(--danger);  border-color:rgba(248,113,113,0.4); background:rgba(248,113,113,0.06); }

.wd-table-empty {
    text-align: center; padding: 3rem 1rem;
}
.wd-table-empty i { font-size: 2.5rem; color: var(--text-muted); display: block; margin-bottom: 0.75rem; opacity: 0.4; }
.wd-table-empty p { font-family:'Orbitron',monospace; font-size:0.65rem; letter-spacing:2px; text-transform:uppercase; color:var(--text-muted); margin:0; }

.wd-table-footer {
    background: var(--surface2); border-top: 1px solid var(--border);
    padding: 0.85rem 1.25rem;
}

/* ══════════════════════════════════
   RESPONSIVE
══════════════════════════════════ */
@media(max-width:991px){
    .wd-right { flex-direction: row; flex-wrap: wrap; gap: 1rem; }
    .wd-avail-card { flex: 1 1 100%; }
    .wd-stat-row, .wd-notes { flex: 1 1 calc(50% - 0.5rem); min-width: 240px; }
}
@media(max-width:767px){
    .wd-right { flex-direction: column; }
    .wd-stat-row, .wd-notes { flex: 1 1 100%; min-width: unset; }
    /* table: hide account name col */
    .wd-table th:nth-child(4),
    .wd-table td:nth-child(4) { display: none; }
}
@media(max-width:575px){
    .wd-panel-body { padding: 1rem; }
    .wd-avail-val  { font-size: 1.4rem; }
    .wd-stat-row   { grid-template-columns: 1fr 1fr; }
    .wd-table               { font-size: 0.72rem; }
    .wd-table th,
    .wd-table td            { padding: 0.45rem 0.45rem; }
    .wd-table th:nth-child(2),
    .wd-table td:nth-child(2),
    .wd-table th:nth-child(4),
    .wd-table td:nth-child(4) { display: none; }
    .wt-amount   { font-size: 0.75rem; }
    .wt-status   { font-size: 0.44rem; }
    .wd-method-box { flex-direction: column; align-items: flex-start; gap: 0.65rem; }
}
</style>
@endsection

@section('content')
    @include('includes.header', ['pageTitle' => 'Withdraw Money'])

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ══ TWO-COLUMN GRID ══ --}}
    <div class="wd-grid">

        {{-- ════ LEFT: FORM ════ --}}
        <div>
            <div class="wd-panel">
                <div class="wd-panel-head">
                    <h2 class="wd-panel-title">
                        <span class="pulse"></span> Request Withdrawal
                    </h2>
                </div>
                <div class="wd-panel-body">

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="wd-error-list">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Receive Method Info --}}
                    <div class="wd-method-box">
                        <div class="wd-method-icon">
                            <i class="bi bi-currency-bitcoin"></i>
                        </div>
                        <div class="wd-method-info">
                            <div class="wd-method-name">Binance Internal Transfer — USDT</div>
                            <div class="wd-method-sub">BNB Smart Chain (BEP20)</div>
                        </div>
                        <span class="wd-method-tag">
                            <i class="bi bi-check-circle-fill"></i> FREE
                        </span>
                    </div>

                    <form id="withdraw-form" action="{{ route('withdraw.store') }}" method="POST">
                        @csrf

                        {{-- Amount --}}
                        <div class="wd-form-group">
                            <label class="wd-form-label">
                                Withdrawal Amount <span class="req">*</span>
                            </label>
                            <div class="wd-input-wrap">
                                <span class="wd-input-icon"><i class="bi bi-currency-dollar"></i></span>
                                <input type="number" name="amount"
                                    class="wd-input has-icon-left has-max-btn @error('amount') is-invalid @enderror"
                                    placeholder="0.00"
                                    min="5" step="0.01"
                                    max="{{ $wallet->available_balance ?? 0 }}"
                                    value="{{ old('amount') }}" required>
                                <button type="button" class="wd-max-btn" onclick="setMaxAmount()">MAX</button>
                            </div>
                            @error('amount')
                                <div class="wd-error">{{ $message }}</div>
                            @enderror
                            <div class="wd-balance-row">
                                <span class="wd-balance-hint"><i class="bi bi-info-circle"></i> Minimum: $5.00</span>
                                <span class="wd-balance-avail">
                                    <i class="bi bi-wallet2"></i>
                                    Available: ${{ number_format($wallet->available_balance ?? 0, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- Binance Address --}}
                        <div class="wd-form-group">
                            <label class="wd-form-label">
                                BEP20 Wallet Address <span class="req">*</span>
                            </label>
                            <div class="wd-input-wrap">
                                <span class="wd-input-icon"><i class="bi bi-hdd-network"></i></span>
                                <input type="text" name="account_number"
                                    class="wd-input has-icon-left @error('account_number') is-invalid @enderror"
                                    placeholder="0x…"
                                    value="{{ old('account_number') }}" required>
                            </div>
                            @error('account_number')
                                <div class="wd-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Account Name --}}
                        <div class="wd-form-group">
                            <label class="wd-form-label">Binance Account Name</label>
                            <div class="wd-input-wrap">
                                <span class="wd-input-icon"><i class="bi bi-person"></i></span>
                                <input type="text" name="account_name"
                                    class="wd-input has-icon-left @error('account_name') is-invalid @enderror"
                                    placeholder="Name as shown on Binance"
                                    value="{{ old('account_name') }}">
                            </div>
                            @error('account_name')
                                <div class="wd-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="button" class="wd-btn primary"
                            onclick="confirmFormSubmit('withdraw-form', {
                                title: 'Confirm Withdrawal',
                                text: 'Do you want to submit this withdrawal request?',
                                confirmButtonText: 'Yes, withdraw'
                            })">
                            <i class="bi bi-send-fill"></i> Submit Withdrawal Request
                        </button>
                        <a href="{{ route('wallet.index') }}" class="wd-btn outline">
                            <i class="bi bi-arrow-left"></i> Back to Wallet
                        </a>
                    </form>

                </div>
            </div>
        </div>{{-- /left --}}

        {{-- ════ RIGHT SIDEBAR ════ --}}
        <div class="wd-right">

            {{-- Available Balance --}}
            <div class="wd-avail-card">
                <div class="wd-avail-pre">Available Balance</div>
                <div class="wd-avail-val">${{ number_format($wallet->available_balance ?? 0, 2) }}</div>
                <div class="wd-avail-sub">Ready to withdraw</div>
            </div>

            {{-- Stat cards --}}
            <div class="wd-stat-row">
                <div class="wd-stat">
                    <div class="wd-stat-label">Total Balance</div>
                    <div class="wd-stat-val cyan">${{ number_format($wallet->balance ?? 0, 2) }}</div>
                </div>
                <div class="wd-stat">
                    <div class="wd-stat-label">Locked</div>
                    <div class="wd-stat-val yellow">${{ number_format($wallet->locked_balance ?? 0, 2) }}</div>
                </div>
            </div>

            {{-- Important Notes --}}
            <div class="wd-notes">
                <div class="wd-notes-head">
                    <i class="bi bi-exclamation-triangle-fill"></i> Important Notes
                </div>
                <div class="wd-notes-body">
                    <div class="wd-note-item">
                        <span class="wd-note-key">Method</span>
                        <span class="wd-note-val"><strong>Binance Internal Transfer</strong></span>
                    </div>
                    <div class="wd-note-item">
                        <span class="wd-note-key">Minimum</span>
                        <span class="wd-note-val"><strong>$5.00 USDT</strong></span>
                    </div>
                    <div class="wd-note-item">
                        <span class="wd-note-key">Processing</span>
                        <span class="wd-note-val">24–48 hours after approval</span>
                    </div>
                    <div class="wd-note-item">
                        <span class="wd-note-key">Fees</span>
                        <span class="wd-note-val"><strong style="color:#34d399;">FREE (0 Fees)</strong></span>
                    </div>
                    <div class="wd-note-item">
                        <span class="wd-note-key">Network</span>
                        <span class="wd-note-val">BNB Smart Chain <strong>(BEP20)</strong> only</span>
                    </div>
                    <div class="wd-note-item">
                        <span class="wd-note-key">Warning</span>
                        <span class="wd-note-val" style="color:var(--warning);">Double-check your BEP20 address. Wrong address = lost funds.</span>
                    </div>
                </div>
            </div>

        </div>{{-- /right --}}
    </div>{{-- /wd-grid --}}

    {{-- ══ RECENT WITHDRAWALS TABLE ══ --}}
    <div class="wd-history">
        <div class="wd-history-head">
            <h2 class="wd-history-title">
                <span class="pulse"></span> Recent Withdrawals
            </h2>
        </div>

        <div style="overflow-x:auto;">
            <table class="wd-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Amount</th>
                        <th>Account</th>
                        <th>Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td>
                                <div class="wt-date">{{ $withdrawal->created_at->format('d M Y') }}</div>
                                <div class="wt-time">{{ $withdrawal->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <span class="wt-ref">{{ $withdrawal->reference_number }}</span>
                            </td>
                            <td>
                                <span class="wt-amount">-${{ number_format($withdrawal->amount, 2) }}</span>
                            </td>
                            <td>
                                @if($withdrawal->account_name)
                                    <div class="wt-account">{{ $withdrawal->account_name }}</div>
                                @endif
                                <div class="wt-addr">{{ Str::limit($withdrawal->account_number, 20) }}</div>
                            </td>
                            <td>
                                <span class="wt-method-badge">
                                    <i class="bi bi-currency-bitcoin"></i> Binance
                                </span>
                            </td>
                            <td>
                                @php $sc = strtolower($withdrawal->status); @endphp
                                <span class="wt-status {{ $sc }}">
                                    <i class="bi {{ $sc === 'pending' ? 'bi-hourglass-split' : ($sc === 'approved' ? 'bi-check-circle-fill' : 'bi-x-circle-fill') }}"></i>
                                    {{ ucfirst($sc) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="wd-table-empty">
                                    <i class="bi bi-inbox"></i>
                                    <p>No withdrawals yet</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="wd-table-footer">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
function setMaxAmount() {
    const max = {{ $wallet->available_balance ?? 0 }};
    const input = document.querySelector('input[name="amount"]');
    input.value = max.toFixed(2);
    // Flash the input border green briefly
    input.style.borderColor = '#34d399';
    setTimeout(() => { input.style.borderColor = ''; }, 1000);
}
</script>
@endpush
