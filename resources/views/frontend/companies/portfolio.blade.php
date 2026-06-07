@extends('layouts.app')
@section('title', 'My Portfolio')
@section('page-title', 'Portfolio')

@section('content')

<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-pie-chart-fill" style="color:var(--accent);font-size:1.1rem;"></i> My Investment Portfolio</h1>
        <p>Overview of your investments and performance</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('companies.index') }}" class="cy-hbtn primary">
            <i class="bi bi-plus-circle-fill"></i> New Investment
        </a>
    </div>
</div>

{{-- STATS --}}
<div class="stats-row" style="margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--accent);"><i class="bi bi-wallet2"></i></div>
        <div class="stat-card-lbl">Total Invested</div>
        <div class="stat-card-val" style="color:var(--accent);">${{ number_format($totalInvested, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--green);"><i class="bi bi-graph-up"></i></div>
        <div class="stat-card-lbl">Current Value</div>
        <div class="stat-card-val" style="color:var(--green);">${{ number_format($currentValue, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:{{ $totalProfitLoss >= 0 ? 'var(--green)' : 'var(--red)' }};"><i class="bi bi-arrow-{{ $totalProfitLoss >= 0 ? 'up' : 'down' }}-circle-fill"></i></div>
        <div class="stat-card-lbl">Unrealized P/L</div>
        <div class="stat-card-val" style="color:{{ $totalProfitLoss >= 0 ? 'var(--green)' : 'var(--red)' }};">{{ $totalProfitLoss >= 0 ? '+' : '' }}${{ number_format($totalProfitLoss, 2) }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge {{ $totalProfitLoss >= 0 ? 'badge-up':'badge-down' }}">{{ number_format($totalProfitLossPercentage, 2) }}%</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--gold);"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-card-lbl">Profit Received</div>
        <div class="stat-card-val" style="color:var(--gold);">${{ number_format($totalProfitReceived, 2) }}</div>
    </div>
</div>

{{-- COMPANY BREAKDOWN --}}
<div class="s-card">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-building-fill"></i> Company Breakdown</span>
        <span style="font-size:0.72rem;color:var(--muted);">{{ $companiesData->count() }} companies</span>
    </div>
    @if($companiesData->count() > 0)
    <div>
        @foreach($companiesData as $d)
        <div class="port-row">
            <div style="flex:1.5;min-width:0;">
                <div style="font-family:'Syne',sans-serif;font-size:0.88rem;font-weight:700;margin-bottom:2px;">{{ $d['company']->name }}</div>
                <div style="font-size:0.7rem;color:var(--muted);">{{ number_format($d['ownership'], 2) }}% ownership</div>
            </div>
            <div class="port-cell port-hide-sm">
                <div class="port-lbl">Investment</div>
                <div class="port-val">${{ number_format($d['investment'], 2) }}</div>
            </div>
            <div class="port-cell port-hide-sm">
                <div class="port-lbl">Current Value</div>
                <div class="port-val">${{ number_format($d['current_value'], 2) }}</div>
            </div>
            <div class="port-cell port-hide-sm">
                <div class="port-lbl">Shares</div>
                <div class="port-val">{{ number_format($d['shares'], 2) }}</div>
            </div>
            <div class="port-cell">
                <div class="port-lbl">P/L</div>
                <div class="port-val" style="color:{{ $d['profit_loss'] >= 0 ? 'var(--green)' : 'var(--red)' }};">
                    {{ $d['profit_loss'] >= 0 ? '+' : '' }}${{ number_format($d['profit_loss'], 2) }}
                    <span style="font-size:0.65rem;opacity:0.75;">({{ number_format($d['profit_loss_percentage'], 2) }}%)</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <i class="bi bi-briefcase"></i>
        <p>No active investments</p>
        <div style="margin-top:12px;">
            <a href="{{ route('companies.index') }}" class="cy-hbtn primary">
                <i class="bi bi-plus-circle"></i> Browse Companies
            </a>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<style>
.port-row {
    display: flex; align-items: center; gap: 16px;
    padding: 14px 20px; border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}
.port-row:last-child { border-bottom: none; }
.port-row:hover { background: var(--card2); }
.port-cell { text-align: center; min-width: 90px; flex-shrink: 0; }
.port-lbl { font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); margin-bottom: 3px; }
.port-val { font-family: 'Syne', sans-serif; font-size: 0.85rem; font-weight: 700; }
.port-hide-sm {}
@media(max-width: 768px) { .port-hide-sm { display: none !important; } }
@media(max-width: 480px) { .port-row { padding: 12px 14px; gap: 10px; } }
</style>
@endpush
