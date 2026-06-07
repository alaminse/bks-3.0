@extends('layouts.app')
@section('title', 'Profit History')
@section('page-title', 'Profit History')

@section('content')

<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-graph-up-arrow" style="color:var(--accent);font-size:1.1rem;"></i> Profit History</h1>
        <p>Track your earnings from company investments</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('companies.my-investments') }}" class="cy-hbtn outline">
            <i class="bi bi-briefcase-fill"></i> My Investments
        </a>
    </div>
</div>

{{-- TOTAL PROFIT BANNER --}}
<div class="ph-banner">
    <div>
        <div style="font-size:0.68rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.5);margin-bottom:6px;">Total Profit Earned</div>
        <div style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;line-height:1;">${{ number_format($totalProfit, 2) }}</div>
        <div style="font-size:0.78rem;color:rgba(255,255,255,0.4);margin-top:6px;">From all investments since you became a partner</div>
    </div>
    <i class="bi bi-trophy-fill" style="font-size:4rem;opacity:0.1;"></i>
</div>

{{-- STATS --}}
@if($profitDistributions->count() > 0)
<div class="stats-row" style="margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--accent);"><i class="bi bi-calendar-check"></i></div>
        <div class="stat-card-lbl">Total Distributions</div>
        <div class="stat-card-val" style="color:var(--accent);">{{ $profitDistributions->total() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--green);"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-card-lbl">Total Earned</div>
        <div class="stat-card-val" style="color:var(--green);">${{ number_format($totalProfit, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--blue);"><i class="bi bi-calendar-month"></i></div>
        <div class="stat-card-lbl">This Month</div>
        <div class="stat-card-val" style="color:var(--blue);">${{ number_format($monthlyProfit, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--gold);"><i class="bi bi-calendar-year"></i></div>
        <div class="stat-card-lbl">This Year</div>
        <div class="stat-card-val" style="color:var(--gold);">${{ number_format($yearlyProfit, 2) }}</div>
    </div>
</div>
@endif

{{-- TABLE --}}
<div class="s-card">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-list-ul"></i> Profit Distributions</span>
        @if($profitDistributions->count() > 0)
        <span style="font-size:0.72rem;color:var(--muted);">{{ $profitDistributions->total() }} records</span>
        @endif
    </div>
    @if($profitDistributions->count() > 0)
    <div style="overflow-x:auto;">
        <table class="act-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Company</th>
                    <th class="ph-hide-sm">Type</th>
                    <th class="ph-hide-sm">Your Share %</th>
                    <th class="ph-hide-sm">Total Profit</th>
                    <th>Your Earnings</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profitDistributions as $dist)
                <tr>
                    <td>
                        <div style="font-size:0.82rem;">{{ $dist->created_at->format('d M Y') }}</div>
                        <div style="font-size:0.65rem;color:var(--muted);">{{ $dist->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            @if($dist->company->logo)
                            <img src="{{ asset('storage/'.$dist->company->logo) }}" alt="{{ $dist->company->name }}"
                                style="width:28px;height:28px;border-radius:6px;object-fit:cover;flex-shrink:0;">
                            @else
                            <div style="width:28px;height:28px;border-radius:6px;background:var(--card2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.7rem;color:var(--muted);">
                                <i class="bi bi-building"></i>
                            </div>
                            @endif
                            <span style="font-weight:600;font-size:0.82rem;">{{ $dist->company->name }}</span>
                        </div>
                    </td>
                    <td class="ph-hide-sm">
                        <span class="s-pill info">{{ ucfirst($dist->companyProfit->profit_type) }}</span>
                    </td>
                    <td class="ph-hide-sm">
                        <span class="s-pill pending">{{ number_format($dist->share_percentage, 2) }}%</span>
                    </td>
                    <td class="ph-hide-sm" style="font-size:0.78rem;color:var(--muted);">
                        ${{ number_format($dist->companyProfit->profit_amount, 2) }}
                    </td>
                    <td>
                        <span class="t-reward">+${{ number_format($dist->profit_amount, 2) }}</span>
                    </td>
                    <td>
                        @if($dist->status == 'paid')
                        <span class="s-pill approved"><i class="bi bi-check-circle-fill" style="font-size:0.55rem;"></i> Received</span>
                        @else
                        <span class="s-pill pending"><i class="bi bi-clock" style="font-size:0.55rem;"></i> Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align:right;font-weight:700;font-size:0.82rem;padding:10px 16px;border-top:1px solid var(--border);color:var(--muted);">Total Earnings:</td>
                    <td colspan="2" style="padding:10px 16px;border-top:1px solid var(--border);">
                        <span class="t-reward">${{ number_format($totalProfit, 2) }}</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    @if($profitDistributions->hasPages())
    <div style="padding:12px 16px;border-top:1px solid var(--border);">{{ $profitDistributions->links() }}</div>
    @endif
    @else
    <div class="empty-state">
        <i class="bi bi-graph-down"></i>
        <p>No profit distributions yet — they appear when companies distribute profits</p>
        <div style="margin-top:12px;">
            <a href="{{ route('companies.my-investments') }}" class="cy-hbtn outline">
                <i class="bi bi-briefcase"></i> View My Investments
            </a>
        </div>
    </div>
    @endif
</div>

{{-- BOTTOM STATS --}}
@if($profitDistributions->count() > 0)
<div class="s-card" style="margin-top:16px;">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-bar-chart-fill"></i> Earning Statistics</span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);">
        @foreach([
            ['icon'=>'bi-calendar-check','color'=>'var(--accent)','lbl'=>'Total Distributions','val'=>$profitDistributions->total()],
            ['icon'=>'bi-cash-stack',    'color'=>'var(--green)', 'lbl'=>'Average Profit',    'val'=>'$'.number_format($profitDistributions->avg('profit_amount'),2)],
            ['icon'=>'bi-trophy-fill',   'color'=>'var(--gold)',  'lbl'=>'Highest Profit',    'val'=>'$'.number_format($profitDistributions->max('profit_amount'),2)],
        ] as $s)
        <div style="background:var(--card);padding:18px 16px;text-align:center;">
            <i class="bi {{ $s['icon'] }}" style="font-size:1.3rem;color:{{ $s['color'] }};opacity:0.6;display:block;margin-bottom:8px;"></i>
            <div style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:800;color:{{ $s['color'] }};margin-bottom:4px;">{{ $s['val'] }}</div>
            <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);">{{ $s['lbl'] }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<style>
.ph-banner {
    background: linear-gradient(135deg, rgba(0,245,212,0.15), rgba(99,102,241,0.15));
    border: 1px solid rgba(0,245,212,0.25);
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    position: relative;
    overflow: hidden;
}
.ph-banner::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, var(--accent), transparent);
}
.ph-hide-sm {}
@media(max-width: 768px) {
    .ph-hide-sm { display: none !important; }
    .ph-banner { flex-direction: column; align-items: flex-start; }
}
@media(max-width: 480px) {
    .stats-row { grid-template-columns: 1fr 1fr; }
    [style*="grid-template-columns: repeat(3"] { grid-template-columns: 1fr !important; }
}
</style>
@endpush
