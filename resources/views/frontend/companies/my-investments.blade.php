@extends('layouts.app')
@section('title', 'My Investments')
@section('page-title', 'My Investments')

@section('content')

<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-briefcase-fill" style="color:var(--accent);font-size:1.1rem;"></i> My Investments</h1>
        <p>Track and manage your company partnerships</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('companies.profit-history') }}" class="cy-hbtn outline">
            <i class="bi bi-graph-up"></i> Profit History
        </a>
        <a href="{{ route('companies.index') }}" class="cy-hbtn primary">
            <i class="bi bi-plus-circle-fill"></i> New Investment
        </a>
    </div>
</div>

{{-- STATS --}}
<div class="stats-row" style="margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--accent);"><i class="bi bi-cash-stack"></i></div>
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
        <div class="stat-card-lbl">Total P/L</div>
        <div class="stat-card-val" style="color:{{ $totalProfitLoss >= 0 ? 'var(--green)' : 'var(--red)' }};">{{ $totalProfitLoss >= 0 ? '+' : '' }}${{ number_format($totalProfitLoss, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--blue);"><i class="bi bi-building-fill"></i></div>
        <div class="stat-card-lbl">Active Partnerships</div>
        <div class="stat-card-val" style="color:var(--blue);">{{ $investments->where('status','active')->count() }}</div>
    </div>
</div>

{{-- TABLE --}}
<div class="s-card">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-pie-chart-fill"></i> Investment Portfolio</span>
        <span style="font-size:0.72rem;color:var(--muted);">{{ $investments->count() }} positions</span>
    </div>
    @if($investments->count() > 0)
    <div style="overflow-x:auto;">
        <table class="act-table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Investment</th>
                    <th class="mi-hide-sm">Shares</th>
                    <th class="mi-hide-sm">Ownership</th>
                    <th class="mi-hide-sm">P/L</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($investments as $inv)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($inv->company->logo)
                            <img src="{{ asset('storage/'.$inv->company->logo) }}" alt="{{ $inv->company->name }}"
                                style="width:36px;height:36px;border-radius:8px;object-fit:cover;flex-shrink:0;">
                            @else
                            <div style="width:36px;height:36px;border-radius:8px;background:var(--card2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--muted);flex-shrink:0;">
                                <i class="bi bi-building"></i>
                            </div>
                            @endif
                            <div>
                                <div style="font-weight:700;font-size:0.85rem;">{{ $inv->company->name }}</div>
                                <div style="font-size:0.68rem;color:var(--muted);">${{ number_format($inv->company->share_price, 2) }}/share</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent);">${{ number_format($inv->invested_amount, 2) }}</div>
                        @if($inv->status == 'active')
                        <div style="font-size:0.68rem;color:var(--muted);">Now: ${{ number_format($inv->current_value, 2) }}</div>
                        @endif
                    </td>
                    <td class="mi-hide-sm">
                        <span class="s-pill info">{{ number_format($inv->share_quantity, 2) }}</span>
                    </td>
                    <td class="mi-hide-sm">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="background:var(--card2);border-radius:99px;height:5px;width:80px;overflow:hidden;flex-shrink:0;">
                                <div style="height:100%;width:{{ min($inv->share_percentage, 100) }}%;background:var(--accent);border-radius:99px;"></div>
                            </div>
                            <span style="font-size:0.78rem;font-weight:700;">{{ number_format($inv->share_percentage, 2) }}%</span>
                        </div>
                    </td>
                    <td class="mi-hide-sm">
                        @if($inv->status == 'active')
                        <div style="font-weight:700;color:{{ $inv->unrealized_profit_loss >= 0 ? 'var(--green)' : 'var(--red)' }};">
                            {{ $inv->unrealized_profit_loss >= 0 ? '+' : '' }}${{ number_format($inv->unrealized_profit_loss, 2) }}
                        </div>
                        <div style="font-size:0.68rem;color:{{ $inv->unrealized_profit_loss >= 0 ? 'var(--green)' : 'var(--red)' }};">
                            {{ $inv->unrealized_profit_loss >= 0 ? '+' : '' }}{{ number_format($inv->unrealized_profit_loss_percentage, 2) }}%
                        </div>
                        @elseif($inv->status == 'sold')
                        <div style="font-weight:700;color:{{ $inv->profit_loss >= 0 ? 'var(--green)' : 'var(--red)' }};">
                            {{ $inv->profit_loss >= 0 ? '+' : '' }}${{ number_format($inv->profit_loss, 2) }}
                        </div>
                        <div style="font-size:0.65rem;color:var(--muted);">Realized</div>
                        @else
                        <span style="color:var(--muted);font-size:0.78rem;">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($inv->status == 'active')
                        <span class="s-pill approved">Active</span>
                        @elseif($inv->status == 'sold')
                        <span class="s-pill pending">Sold</span>
                        @else
                        <span class="s-pill inactive">{{ ucfirst($inv->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('companies.show', $inv->company->id) }}" class="cy-hbtn outline" style="padding:5px 10px;font-size:0.72rem;">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($investments->hasPages())
    <div style="padding:12px 16px;border-top:1px solid var(--border);">{{ $investments->links() }}</div>
    @endif
    @else
    <div class="empty-state">
        <i class="bi bi-briefcase"></i>
        <p>No investments yet — start investing to become a partner</p>
        <div style="margin-top:12px;">
            <a href="{{ route('companies.index') }}" class="cy-hbtn primary">
                <i class="bi bi-plus-circle"></i> Browse Companies
            </a>
        </div>
    </div>
    @endif
</div>

{{-- WHY INVEST --}}
<div class="s-card" style="margin-top:20px;">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-stars"></i> Why Invest?</span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--border);">
        @foreach([
            ['icon'=>'bi-shield-check',     'color'=>'var(--accent)', 'title'=>'Secure',         'desc'=>'Your investments are protected'],
            ['icon'=>'bi-graph-up-arrow',   'color'=>'var(--green)',  'title'=>'Track Growth',   'desc'=>'Monitor your portfolio'],
            ['icon'=>'bi-cash-coin',        'color'=>'var(--gold)',   'title'=>'Earn Profits',   'desc'=>'Regular distributions'],
            ['icon'=>'bi-people-fill',      'color'=>'var(--blue)',   'title'=>'Network',        'desc'=>'Connect with investors'],
        ] as $w)
        <div style="background:var(--card);padding:20px 14px;text-align:center;">
            <i class="bi {{ $w['icon'] }}" style="font-size:1.5rem;color:{{ $w['color'] }};opacity:0.6;display:block;margin-bottom:8px;"></i>
            <div style="font-weight:700;font-size:0.82rem;margin-bottom:4px;">{{ $w['title'] }}</div>
            <div style="font-size:0.72rem;color:var(--muted);">{{ $w['desc'] }}</div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<style>
.mi-hide-sm {}
@media(max-width: 768px) { .mi-hide-sm { display: none !important; } }
@media(max-width: 900px) {
    .page-header-actions { flex-direction: column; width: 100%; }
    .page-header-actions .cy-hbtn { width: 100%; justify-content: center; }
    [style*="grid-template-columns: repeat(4"] { grid-template-columns: 1fr 1fr !important; }
}
</style>
@endpush
