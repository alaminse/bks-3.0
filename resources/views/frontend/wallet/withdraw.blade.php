@extends('layouts.app')
@section('title', 'Withdraw')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/wallet.css') }}">
@endsection

@section('content')
    @include('includes.header', ['pageTitle' => 'Withdraw Money'])

    <div class="wd-grid">

        {{-- ════ LEFT ════ --}}
        <div>
            <div class="wd-panel">
                <div class="wd-panel-head">
                    <span class="wd-panel-title"><span class="pulse"></span> Request Withdrawal</span>
                </div>
                <div class="wd-panel-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="wd-error-list">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Method Info --}}
                    <div class="wd-method-box">
                        <div class="wd-method-icon"><i class="bi bi-currency-bitcoin"></i></div>
                        <div class="wd-method-info">
                            <div class="wd-method-name">Binance Internal Transfer — USDT</div>
                            <div class="wd-method-sub">BNB Smart Chain (BEP20)</div>
                        </div>
                        <span class="wd-method-tag"><i class="bi bi-check-circle-fill"></i> FREE</span>
                    </div>

                    <form id="withdraw-form" action="{{ route('withdraw.store') }}" method="POST">
                        @csrf

                        {{-- Amount --}}
                        <div class="wd-form-group">
                            <label class="wd-form-label">Withdrawal Amount <span class="req">*</span></label>
                            <div class="wd-input-wrap">
                                <span class="wd-input-icon"><i class="bi bi-currency-dollar"></i></span>
                                <input type="number" name="amount"
                                    class="wd-input has-icon-left has-max-btn @error('amount') is-invalid @enderror"
                                    placeholder="0.00" min="5" step="0.01"
                                    max="{{ $wallet->available_balance ?? 0 }}" value="{{ old('amount') }}" required>
                                <button type="button" class="wd-max-btn" onclick="setMaxAmount()">MAX</button>
                            </div>
                            @error('amount')
                                <div class="wd-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                            @enderror
                            <div class="wd-balance-row">
                                <span class="wd-balance-hint"><i class="bi bi-info-circle"></i> Minimum: $5.00</span>
                                <span class="wd-balance-avail">
                                    <i class="bi bi-wallet2"></i> Available:
                                    ${{ number_format($wallet->available_balance ?? 0, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- Quick amount --}}
                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;">
                            @foreach ([10, 25, 50, 100, 200] as $amt)
                                <button type="button"
                                    onclick="document.querySelector('input[name=amount]').value='{{ $amt }}'"
                                    style="padding:5px 14px;border-radius:99px;background:var(--card2);border:1px solid var(--border2);color:var(--muted);font-size:0.75rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;"
                                    onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
                                    onmouseout="this.style.borderColor='var(--border2)';this.style.color='var(--muted)'">
                                    ${{ $amt }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Wallet Address --}}
                        <div class="wd-form-group">
                            <label class="wd-form-label">BEP20 Wallet Address <span class="req">*</span></label>
                            <div class="wd-input-wrap">
                                <span class="wd-input-icon"><i class="bi bi-hdd-network"></i></span>
                                <input type="text" name="account_number"
                                    class="wd-input has-icon-left @error('account_number') is-invalid @enderror"
                                    placeholder="0x…" value="{{ old('account_number') }}" required>
                            </div>
                            @error('account_number')
                                <div class="wd-error">{{ $message }}</div>
                            @enderror
                            <div style="font-size:0.72rem;color:var(--muted);margin-top:5px;">
                                <i class="bi bi-exclamation-triangle" style="color:var(--gold);"></i>
                                Double-check your BEP20 address. Wrong address = lost funds.
                            </div>
                        </div>

                        {{-- Account Name --}}
                        <div class="wd-form-group">
                            <label class="wd-form-label">Binance Account Name <span
                                    style="color:var(--muted);font-size:0.68rem;">(optional)</span></label>
                            <div class="wd-input-wrap">
                                <span class="wd-input-icon"><i class="bi bi-person"></i></span>
                                <input type="text" name="account_name"
                                    class="wd-input has-icon-left @error('account_name') is-invalid @enderror"
                                    placeholder="Name as shown on Binance" value="{{ old('account_name') }}">
                            </div>
                            @error('account_name')
                                <div class="wd-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="button" class="wd-btn primary"
                            onclick="confirmFormSubmit('withdraw-form',{
                            title:'Confirm Withdrawal',
                            text:'Do you want to submit this withdrawal request?',
                            confirmText:'Yes, Withdraw'
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

        {{-- ════ RIGHT ════ --}}
        <div class="wd-right">

            {{-- Available Balance --}}
            <div class="wd-avail-card">
                <div class="wd-avail-pre">Available Balance</div>
                <div class="wd-avail-val">${{ number_format($wallet->available_balance ?? 0, 2) }}</div>
                <div class="wd-avail-sub">Ready to withdraw now</div>
            </div>

            {{-- Stat Row --}}
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
                    @foreach ([['Method', 'Binance Internal Transfer'], ['Minimum', '$5.00 USDT'], ['Processing', '24–48 hrs after approval'], ['Fees', 'FREE (0 Fees)'], ['Network', 'BEP20 only']] as [$k, $v])
                        <div class="wd-note-item">
                            <span class="wd-note-key">{{ $k }}</span>
                            <span class="wd-note-val">{{ $v }}</span>
                        </div>
                    @endforeach
                    <div class="wd-note-item">
                        <span class="wd-note-key" style="color:var(--gold);">⚠ Warning</span>
                        <span class="wd-note-val" style="color:var(--gold);">Wrong address = lost funds permanently</span>
                    </div>
                </div>
            </div>

        </div>{{-- /right --}}
    </div>

    {{-- WITHDRAWAL HISTORY --}}
    <div class="wd-history">
        <div class="wd-history-head">
            <h2 class="wd-history-title"><span class="pulse"></span> Recent Withdrawals</h2>
            <span style="font-size:0.72rem;color:var(--muted);">{{ $withdrawals->total() ?? 0 }} total</span>
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
                    @forelse($withdrawals as $wd)
                        <tr>
                            <td>
                                <div class="wt-date">{{ $wd->created_at->format('d M Y') }}</div>
                                <div class="wt-time">{{ $wd->created_at->format('h:i A') }}</div>
                            </td>
                            <td><span class="wt-ref">{{ $wd->reference_number }}</span></td>
                            <td><span class="wt-amount">-${{ number_format($wd->amount, 2) }}</span></td>
                            <td>
                                @if ($wd->account_name)
                                    <div class="wt-account">{{ $wd->account_name }}</div>
                                @endif
                                <div class="wt-addr">{{ Str::limit($wd->account_number, 20) }}</div>
                            </td>
                            <td><span class="wt-method-badge"><i class="bi bi-currency-bitcoin"></i> Binance</span></td>
                            <td>
                                @php $sc = strtolower($wd->status); @endphp
                                <span class="wt-status {{ $sc }}">
                                    <i
                                        class="bi {{ $sc === 'pending' ? 'bi-hourglass-split' : ($sc === 'approved' ? 'bi-check-circle-fill' : 'bi-x-circle-fill') }}"></i>
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
        @if ($withdrawals->hasPages())
            <div class="wd-table-footer">{{ $withdrawals->links() }}</div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        function setMaxAmount() {
            const max = {{ $wallet->available_balance ?? 0 }};
            const input = document.querySelector('input[name="amount"]');
            input.value = max.toFixed(2);
            input.style.borderColor = 'var(--green)';
            setTimeout(() => {
                input.style.borderColor = '';
            }, 1000);
        }
    </script>
@endpush
