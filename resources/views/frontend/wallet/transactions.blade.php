@extends('layouts.app')
@section('title', 'Transaction History')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/wallet.css') }}">
@endsection

@section('content')
@include('includes.header', ['pageTitle'=>'Transaction History','backRoute'=>route('wallet.index'),'backText'=>'Back to Wallet'])

<div class="txh-page-grid">

    {{-- LEFT --}}
    <div class="txh-left-col">

        <div class="txh-filter">
            <div class="txh-filter-head">
                <span class="txh-filter-title"><i class="bi bi-funnel-fill"></i> Filters</span>
                @if(request()->hasAny(['type','direction','date_from','date_to']))
                <a href="{{ route('wallet.transactions') }}" class="txh-filter-clear"><i class="bi bi-x-circle"></i> Clear All</a>
                @endif
            </div>
            <div class="txh-filter-body">
                <form method="GET" action="{{ route('wallet.transactions') }}">
                    <div class="txh-filter-grid">
                        <div class="txh-f-group">
                            <label class="txh-f-label">Type</label>
                            <select name="type" class="cy-sel">
                                <option value="all">All Types</option>
                                @foreach(['deposit'=>'Deposit','task'=>'Task Earning','package'=>'Package','withdraw'=>'Withdrawal','referral'=>'Referral','adjustment'=>'Adjustment'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ request('type')===$val?'selected':'' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="txh-f-group">
                            <label class="txh-f-label">Direction</label>
                            <select name="direction" class="cy-sel">
                                <option value="all">All</option>
                                <option value="credit" {{ request('direction')==='credit'?'selected':'' }}>Credit (+)</option>
                                <option value="debit"  {{ request('direction')==='debit' ?'selected':'' }}>Debit (−)</option>
                            </select>
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
                        <button type="submit" class="txh-filter-btn"><i class="bi bi-funnel-fill"></i> Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="txh-panel">
            <div class="txh-panel-head">
                <span class="txh-panel-title"><span class="pulse"></span> All Transactions</span>
                <a href="{{ route('wallet.index') }}" class="txh-back-btn"><i class="bi bi-arrow-left"></i> Wallet</a>
            </div>
            <div class="txh-scroll">
                <table class="txh-table">
                    <thead>
                        <tr>
                            <th class="col-id txh-hide-sm">#</th>
                            <th>Date</th><th>Type</th>
                            <th class="txh-hide-sm">Description</th>
                            <th style="text-align:right">Amount</th>
                            <th class="col-after txh-hide-sm" style="text-align:right">After</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $txn)
                        @php
                            $typeMap=['deposit'=>['icon'=>'arrow-down-circle-fill','cls'=>'tc-deposit','label'=>'Deposit'],'task'=>['icon'=>'check-circle-fill','cls'=>'tc-task','label'=>'Task'],'package'=>['icon'=>'box-seam-fill','cls'=>'tc-package','label'=>'Package'],'withdraw'=>['icon'=>'arrow-up-circle-fill','cls'=>'tc-withdraw','label'=>'Withdraw'],'referral'=>['icon'=>'people-fill','cls'=>'tc-referral','label'=>'Referral'],'adjustment'=>['icon'=>'gear-fill','cls'=>'tc-adjustment','label'=>'Adjust']];
                            $cfg=$typeMap[$txn->type]??['icon'=>'circle-fill','cls'=>'tc-adjustment','label'=>ucfirst($txn->type??'—')];
                            $dir=$txn->direction??(in_array($txn->type,['deposit','task','referral'])?'credit':'debit');
                            $sc=strtolower($txn->status??'completed');
                        @endphp
                        <tr>
                            <td class="col-id txh-hide-sm"><span class="tc-id">{{ $txn->id }}</span></td>
                            <td><div class="tc-date">{{ $txn->created_at->format('d M Y') }}</div><div class="tc-time">{{ $txn->created_at->format('h:i A') }}</div></td>
                            <td><span class="tc-type {{ $cfg['cls'] }}"><i class="bi bi-{{ $cfg['icon'] }}"></i>{{ $cfg['label'] }}</span></td>
                            <td class="txh-hide-sm"><div class="tc-desc">{{ Str::limit($txn->description??'—',40) }}</div></td>
                            <td style="text-align:right"><div class="tc-amount {{ $dir==='credit'?'cr':'db' }}">{{ $dir==='credit'?'+':'−' }}${{ number_format($txn->amount,2) }}</div></td>
                            <td class="col-after txh-hide-sm" style="text-align:right"><div class="tc-bal">${{ number_format($txn->balance_after??0,2) }}</div></td>
                            <td><span class="tc-status {{ $sc }}"><i class="bi {{ $sc==='pending'?'bi-hourglass-split':($sc==='rejected'||$sc==='failed'?'bi-x-circle-fill':'bi-check-circle-fill') }}"></i>{{ ucfirst($sc) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="7"><div class="txh-empty"><i class="bi bi-journal-x"></i><h5>No transactions found</h5><p>Try adjusting your filters.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->total() > 0)
            <div class="txh-footer">
                <div class="txh-count">Showing <strong>{{ $transactions->firstItem() }}</strong>–<strong>{{ $transactions->lastItem() }}</strong> of <strong>{{ $transactions->total() }}</strong></div>
                {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="txh-right-col">

        <div class="txh-stat" style="--sc-color:var(--accent);">
            <div class="txh-stat-top"><div class="txh-stat-label">Balance</div><div class="txh-stat-icon"><i class="bi bi-wallet2"></i></div></div>
            <div class="txh-stat-val"><span>$</span>{{ number_format($wallet->balance??0,2) }}</div>
            <div class="txh-stat-sub"><i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> Total</div>
        </div>

        <div class="txh-stat" style="--sc-color:var(--green);">
            <div class="txh-stat-top"><div class="txh-stat-label">Available</div><div class="txh-stat-icon"><i class="bi bi-unlock"></i></div></div>
            <div class="txh-stat-val"><span>$</span>{{ number_format($wallet->available_balance??0,2) }}</div>
            <div class="txh-stat-sub"><i class="bi bi-lightning-charge-fill"></i> Ready</div>
        </div>

        <div class="txh-stat" style="--sc-color:var(--gold);">
            <div class="txh-stat-top"><div class="txh-stat-label">Locked</div><div class="txh-stat-icon"><i class="bi bi-lock-fill"></i></div></div>
            <div class="txh-stat-val"><span>$</span>{{ number_format($wallet->locked_balance??0,2) }}</div>
            <div class="txh-stat-sub"><i class="bi bi-hourglass-split"></i> Pending</div>
        </div>

        <div class="txh-widget">
            <div class="txh-widget-head"><i class="bi bi-pie-chart-fill"></i> Breakdown</div>
            <div class="txh-bk-body">
                @php $total=method_exists($transactions,'total')?$transactions->total():$transactions->count(); $breakdown=[['label'=>'Deposits','color'=>'var(--green)','type'=>'deposit'],['label'=>'Withdrawals','color'=>'var(--red)','type'=>'withdraw'],['label'=>'Tasks','color'=>'var(--accent)','type'=>'task'],['label'=>'Packages','color'=>'var(--blue)','type'=>'package'],['label'=>'Referrals','color'=>'#818cf8','type'=>'referral']]; @endphp
                @foreach($breakdown as $bk)
                @php $cnt=$typeCounts[$bk['type']]??0; $pct=$total>0?round(($cnt/$total)*100):0; @endphp
                <div class="txh-bk-row">
                    <div class="txh-bk-top"><div class="txh-bk-name"><span class="txh-bk-dot" style="background:{{ $bk['color'] }};"></span>{{ $bk['label'] }}</div><div class="txh-bk-count">{{ $cnt }}</div></div>
                    <div class="txh-bk-bar-bg"><div class="txh-bk-bar-fill" style="width:{{ $pct }}%;background:{{ $bk['color'] }};"></div></div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="txh-widget">
            <div class="txh-widget-head"><i class="bi bi-compass-fill"></i> Quick Nav</div>
            <div>
                <a href="{{ route('wallet.index') }}"   class="txh-nav-link"><i class="bi bi-wallet2"></i> My Wallet</a>
                <a href="{{ route('wallet.deposit') }}" class="txh-nav-link"><i class="bi bi-plus-circle-fill"></i> Deposit</a>
                <a href="{{ route('withdraw.index') }}" class="txh-nav-link"><i class="bi bi-send-fill"></i> Withdraw</a>
            </div>
        </div>

    </div>
</div>
@endsection
