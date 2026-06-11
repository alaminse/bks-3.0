@extends('layouts.app')
@section('title', 'My Wallet')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/wallet.css') }}">
@endsection

@section('content')
@include('includes.header', ['pageTitle' => 'My Wallet'])

<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-wallet2" style="color:var(--accent);font-size:1.2rem;"></i> My Wallet</h1>
        <p>Manage your balance, deposits and withdrawals</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('wallet.deposit') }}" class="cy-hbtn primary"><i class="bi bi-plus-circle-fill"></i> Deposit</a>
        <a href="{{ route('withdraw.index') }}"  class="cy-hbtn outline"><i class="bi bi-send"></i> Withdraw</a>
    </div>
</div>

<div class="wlt-grid">

    {{-- LEFT --}}
    <div class="wlt-left">

        <div class="wlt-hero">
            <div class="wlt-hero-inner">
                <div class="wlt-bal-side">
                    <div class="wlt-bal-pre">Total Balance</div>
                    <div class="wlt-bal-amount">${{ number_format($wallet->balance ?? 0, 2) }}</div>
                    <div class="wlt-bal-sub">Your current wallet balance in USDT</div>
                    <div class="wlt-bal-actions">
                        <a href="{{ route('wallet.deposit') }}" class="wlt-action-btn primary"><i class="bi bi-plus-circle-fill"></i> Deposit</a>
                        <a href="{{ route('withdraw.index') }}"  class="wlt-action-btn"><i class="bi bi-send-fill"></i> Withdraw</a>
                        <a href="{{ route('wallet.transactions') }}" class="wlt-action-btn"><i class="bi bi-clock-history"></i> History</a>
                    </div>
                </div>
                <div class="wlt-mini-stats">
                    <div class="wlt-mini-item">
                        <div class="wlt-mini-icon avail"><i class="bi bi-cash-coin"></i></div>
                        <div>
                            <div class="wlt-mini-label">Available</div>
                            <div class="wlt-mini-value" style="color:var(--green);">${{ number_format($wallet->available_balance ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <div class="wlt-mini-item">
                        <div class="wlt-mini-icon locked"><i class="bi bi-lock-fill"></i></div>
                        <div>
                            <div class="wlt-mini-label">Locked</div>
                            <div class="wlt-mini-value" style="color:var(--gold);">${{ number_format($wallet->locked_balance ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <div class="wlt-mini-item">
                        <div class="wlt-mini-icon earned"><i class="bi bi-graph-up-arrow"></i></div>
                        <div>
                            <div class="wlt-mini-label">Total Earned</div>
                            <div class="wlt-mini-value" style="color:var(--accent);">${{ number_format($totalEarned ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wlt-chart-card">
            <div class="wlt-chart-head">
                <span class="wlt-chart-title"><span class="pulse"></span> Spending Overview</span>
                <div class="wlt-chart-filters">
                    <button class="wlt-filter-btn active" onclick="setFilter(this,'7d')">7D</button>
                    <button class="wlt-filter-btn" onclick="setFilter(this,'30d')">30D</button>
                    <button class="wlt-filter-btn" onclick="setFilter(this,'90d')">90D</button>
                </div>
            </div>
            <div class="wlt-chart-body">
                <div class="wlt-chart-wrap"><canvas id="walletChart"></canvas></div>
                <div class="wlt-chart-stats">
                    <div class="wcs-item"><div class="wcs-label">Deposits</div><div class="wcs-val up">${{ number_format($totalDeposits, 2) }}</div></div>
                    <div class="wcs-item"><div class="wcs-label">Withdrawals</div><div class="wcs-val down">${{ number_format($totalWithdrawals, 2) }}</div></div>
                    <div class="wcs-item"><div class="wcs-label">Task Earnings</div><div class="wcs-val">${{ number_format($totalEarned ?? 0, 2) }}</div></div>
                </div>
            </div>
        </div>

        <div class="wlt-qa">
            <div class="wlt-qa-head"><span class="wlt-qa-title">Quick Actions</span></div>
            <div class="wlt-qa-grid">
                <a href="{{ route('wallet.deposit') }}"      class="wlt-qa-item highlight"><div class="wlt-qa-icon"><i class="bi bi-plus-circle-fill" style="color:var(--green);"></i></div>Deposit</a>
                <a href="{{ route('withdraw.index') }}"       class="wlt-qa-item"><div class="wlt-qa-icon"><i class="bi bi-send-fill" style="color:var(--red);"></i></div>Withdraw</a>
                <a href="{{ route('packages.index') }}"       class="wlt-qa-item"><div class="wlt-qa-icon"><i class="bi bi-box-seam-fill" style="color:var(--blue);"></i></div>Packages</a>
                <a href="{{ route('wallet.transactions') }}"  class="wlt-qa-item"><div class="wlt-qa-icon"><i class="bi bi-clock-history" style="color:var(--gold);"></i></div>History</a>
                <a href="{{ route('tasks.index') }}"          class="wlt-qa-item"><div class="wlt-qa-icon"><i class="bi bi-lightning-charge-fill" style="color:var(--accent);"></i></div>Tasks</a>
            </div>
        </div>

        <div class="wlt-txn">
            <div class="wlt-txn-header">
                <span class="wlt-txn-htitle"><i class="bi bi-arrow-left-right" style="color:var(--accent);"></i> Recent Transactions</span>
                <a href="{{ route('wallet.transactions') }}" class="wlt-txn-viewall">View All →</a>
            </div>
            <div style="overflow-x:auto;">
                <table class="wlt-table">
                    <thead>
                        <tr>
                            <th>Date</th><th>Type</th><th class="wlt-hide-sm">Description</th>
                            <th style="text-align:right">Amount</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $txn)
                        @php
                            $dir = $txn->direction ?? ($txn->type === 'deposit' ? 'credit' : 'debit');
                            $typeMap = ['deposit'=>['icon'=>'bi-arrow-down-circle-fill','label'=>'Deposit'],'task'=>['icon'=>'bi-check-circle-fill','label'=>'Task'],'package'=>['icon'=>'bi-box-seam-fill','label'=>'Package'],'withdraw'=>['icon'=>'bi-arrow-up-circle-fill','label'=>'Withdraw'],'referral'=>['icon'=>'bi-people-fill','label'=>'Referral'],'adjustment'=>['icon'=>'bi-gear-fill','label'=>'Adjust']];
                            $tm = $typeMap[$txn->type] ?? ['icon'=>'bi-circle-fill','label'=>ucfirst($txn->type)];
                            $sc = strtolower($txn->status ?? 'completed');
                        @endphp
                        <tr>
                            <td><div class="wt-date">{{ $txn->created_at->format('d M Y') }}</div><div class="wt-time">{{ $txn->created_at->format('h:i A') }}</div></td>
                            <td><span class="wt-type {{ $txn->type }}"><i class="bi {{ $tm['icon'] }}"></i>{{ $tm['label'] }}</span></td>
                            <td class="wlt-hide-sm"><div class="wt-desc">{{ Str::limit($txn->description ?? '—', 40) }}</div></td>
                            <td style="text-align:right"><div class="wt-amount {{ $dir==='credit'?'cr':'db' }}">{{ $dir==='credit'?'+':'−' }}${{ number_format($txn->amount, 2) }}</div></td>
                            <td><span class="wt-status {{ $sc }}"><i class="bi {{ $sc==='pending'?'bi-hourglass-split':($sc==='rejected'||$sc==='failed'?'bi-x-circle-fill':'bi-check-circle-fill') }}"></i>{{ ucfirst($sc) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="wlt-empty"><i class="bi bi-inbox"></i><p>No transactions yet</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->hasPages())
            <div style="padding:12px 16px;border-top:1px solid var(--border);">{{ $transactions->links() }}</div>
            @endif
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="wlt-right">

        <div class="wlt-card-widget">
            <div class="wlt-cw-head"><span class="wlt-cw-title"><i class="bi bi-credit-card" style="color:var(--accent);"></i> My Card</span></div>
            <div class="wlt-cw-body">
                <div class="cyber-card">
                    <div class="cc-inner">
                        <div class="cc-top"><div class="cc-brand">TopTrade</div><div class="cc-chip"></div></div>
                        <div class="cc-bal-label">Current Balance</div>
                        <div class="cc-bal-val">${{ number_format($wallet->balance ?? 0, 2) }}</div>
                        <div class="cc-bottom">
                            <div class="cc-user">{{ auth()->user()->name }}</div>
                            <div><div class="cc-avail-lbl">Available</div><div class="cc-avail-val">${{ number_format($wallet->available_balance ?? 0, 2) }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="cc-details">
                    <div class="cc-detail-row"><span class="cc-dk">Account ID</span><span class="cc-dv">••• {{ str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) }}</span></div>
                    <div class="cc-detail-row"><span class="cc-dk">Status</span><span class="cc-dv" style="color:var(--green);">● Active</span></div>
                    <div class="cc-detail-row"><span class="cc-dk">Locked</span><span class="cc-dv" style="color:var(--gold);">${{ number_format($wallet->locked_balance ?? 0, 2) }}</span></div>
                    <div class="cc-detail-row"><span class="cc-dk">Currency</span><span class="cc-dv">USDT</span></div>
                </div>
            </div>
        </div>

        <div class="wlt-ql">
            <div class="wlt-ql-head">Quick Links</div>
            <div class="wlt-ql-grid">
                <a href="{{ route('wallet.deposit') }}"     class="wlt-ql-item ql-deposit"><div class="wlt-ql-icon"><i class="bi bi-plus-circle-fill"></i></div>Deposit</a>
                <a href="{{ route('withdraw.index') }}"      class="wlt-ql-item ql-withdraw"><div class="wlt-ql-icon"><i class="bi bi-send-fill"></i></div>Withdraw</a>
                <a href="{{ route('packages.index') }}"      class="wlt-ql-item ql-package"><div class="wlt-ql-icon"><i class="bi bi-box-seam-fill"></i></div>Packages</a>
                <a href="{{ route('wallet.transactions') }}" class="wlt-ql-item ql-history"><div class="wlt-ql-icon"><i class="bi bi-clock-history"></i></div>History</a>
            </div>
        </div>

        <div class="wlt-txn">
            <div class="wlt-txn-header">
                <span class="wlt-txn-htitle" style="font-size:0.78rem;"><span class="pulse"></span> Latest</span>
                <a href="{{ route('wallet.transactions') }}" class="wlt-txn-viewall">All →</a>
            </div>
            @forelse($transactions->take(5) as $txn)
            @php $dir=$txn->direction??($txn->type==='deposit'?'credit':'debit'); $icons=['deposit'=>'bi-arrow-down-circle-fill','task'=>'bi-check-circle-fill','withdraw'=>'bi-arrow-up-circle-fill','referral'=>'bi-people-fill','package'=>'bi-box-seam-fill']; $icon=$icons[$txn->type]??'bi-circle-fill'; @endphp
            <div class="txn-row">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="txn-ico {{ $dir==='credit'?'cr':'db' }}"><i class="bi {{ $icon }}"></i></div>
                    <div><div class="txn-type">{{ ucfirst($txn->type) }}</div><div class="txn-date">{{ $txn->created_at->format('d M, h:i A') }}</div></div>
                </div>
                <div class="txn-amt {{ $dir==='credit'?'cr':'db' }}">{{ $dir==='credit'?'+':'−' }}${{ number_format($txn->amount, 2) }}</div>
            </div>
            @empty
            <div class="wlt-empty" style="padding:24px;"><i class="bi bi-inbox"></i><p>No activity yet</p></div>
            @endforelse
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    const ctx = document.getElementById('walletChart');
    if (!ctx) return;
    const accent = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#00f5d4';
    const g1 = ctx.getContext('2d').createLinearGradient(0,0,0,160);
    g1.addColorStop(0, accent+'40'); g1.addColorStop(1, accent+'00');
    const g2 = ctx.getContext('2d').createLinearGradient(0,0,0,160);
    g2.addColorStop(0,'rgba(239,68,68,0.25)'); g2.addColorStop(1,'rgba(239,68,68,0)');
    new Chart(ctx, {
        type:'line',
        data:{ labels:['Mon','Tue','Wed','Thu','Fri','Sat','Sun'], datasets:[{label:'Income',data:[0,0,0,0,0,0,0],borderColor:accent,backgroundColor:g1,borderWidth:2,pointRadius:3,pointBackgroundColor:accent,pointBorderColor:'#111119',tension:0.45,fill:true},{label:'Spend',data:[0,0,0,0,0,0,0],borderColor:'#ef4444',backgroundColor:g2,borderWidth:2,pointRadius:3,pointBackgroundColor:'#ef4444',pointBorderColor:'#111119',tension:0.45,fill:true}]},
        options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:{ backgroundColor:'#16161f', borderColor:'rgba(255,255,255,0.1)', borderWidth:1, titleColor:'rgba(255,255,255,0.5)', bodyColor:'#e8e8f0', callbacks:{label:c=>' $'+c.parsed.y.toFixed(2)} } }, scales:{ x:{grid:{color:'rgba(255,255,255,0.04)'},ticks:{color:'rgba(255,255,255,0.3)',font:{family:'DM Sans',size:11}}}, y:{grid:{color:'rgba(255,255,255,0.04)'},ticks:{color:'rgba(255,255,255,0.3)',font:{family:'DM Sans',size:11},callback:v=>'$'+v}} } }
    });
})();
function setFilter(btn,range) { document.querySelectorAll('.wlt-filter-btn').forEach(b=>b.classList.remove('active')); btn.classList.add('active'); }
</script>
@endpush
