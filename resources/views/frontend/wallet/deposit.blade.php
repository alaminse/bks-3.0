@extends('layouts.app')
@section('title', 'Deposit')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/wallet.css') }}">
@endsection

@section('content')
    @include('includes.header', ['pageTitle' => 'Deposit Money'])

<div class="dep-grid">

    {{-- ════ LEFT ════ --}}
    <div>

        {{-- BINANCE ADDRESS --}}
        <div class="dep-panel">
            <div class="dep-panel-head">
                <span class="dep-panel-title" style="color:var(--gold);">
                    <i class="bi bi-hdd-network-fill"></i> Our Binance Wallet Address
                </span>
            </div>
            <div class="dep-panel-body">
                <div class="dep-addr-box">
                    <div class="dep-addr-network">
                        <i class="bi bi-broadcast"></i> Network: Binance TRC20
                        <span>TRON (TRC20)</span>
                    </div>
                    <div class="dep-addr-code" id="walletAddress">
                        TFDdDrdohNe9Er4uEpr1evCFMFCckm9rba
                    </div>
                    <button class="dep-copy-btn" id="copyBtn" onclick="copyAddress()">
                        <i class="bi bi-clipboard" id="copyIcon"></i>
                        <span id="copyText">Copy Address</span>
                    </button>
                </div>
                <div class="dep-warn">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Warning:</strong> Only send USDT (TRC20) to this address. Sending other tokens may result in permanent loss of funds.
                    </div>
                </div>
            </div>
        </div>

        {{-- DEPOSIT FORM --}}
        <div class="dep-panel">
            <div class="dep-panel-head">
                <span class="dep-panel-title">
                    <span class="pulse"></span> Submit Deposit Request
                </span>
            </div>
            <div class="dep-panel-body">
                <form id="deposit-form" action="{{ route('wallet.deposit.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Amount --}}
                    <div class="dep-form-group">
                        <label class="dep-form-label">Deposit Amount (USDT) <span class="req">*</span></label>
                        <div class="dep-input-wrap">
                            <span class="dep-input-icon">
                                <img src="https://cryptologos.cc/logos/tether-usdt-logo.png" width="16" alt="USDT" style="border-radius:50%;">
                            </span>
                            <input type="number" name="amount"
                                class="dep-input has-icon-left @error('amount') is-invalid @enderror"
                                placeholder="0.00" min="10" max="10000" step="0.01"
                                value="{{ old('amount') }}" required>
                            <span class="dep-input-suffix">USDT</span>
                        </div>
                        @error('amount')<div class="dep-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                        <div class="dep-hint"><i class="bi bi-info-circle"></i> Min: $10 &nbsp;·&nbsp; Max: $10,000</div>
                    </div>

                    {{-- Quick Amount Buttons --}}
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;">
                        @foreach([50, 100, 200, 500, 1000] as $amt)
                        <button type="button" onclick="setAmount({{ $amt }})"
                            style="padding:5px 14px;border-radius:99px;background:var(--card2);border:1px solid var(--border2);color:var(--muted);font-size:0.75rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;"
                            onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
                            onmouseout="this.style.borderColor='var(--border2)';this.style.color='var(--muted)'">
                            ${{ $amt }}
                        </button>
                        @endforeach
                    </div>

                    {{-- Payment Method --}}
                    <div class="dep-form-group">
                        <label class="dep-form-label">Payment Method <span class="req">*</span></label>
                        <div class="dep-select-wrap">
                            <select name="payment_method" id="paymentMethod"
                                class="dep-select @error('payment_method') is-invalid @enderror" required>
                                <option value="">Select payment method</option>
                                <option value="binance_pay" {{ old('payment_method')=='binance_pay'?'selected':'' }}>
                                    ⚡ Binance Pay — Recommended (FREE, Instant)
                                </option>
                                <option value="binance_p2p" {{ old('payment_method')=='binance_p2p'?'selected':'' }}>
                                    👥 Binance P2P
                                </option>
                            </select>
                        </div>
                        @error('payment_method')<div class="dep-error">{{ $message }}</div>@enderror
                    </div>

                    {{-- Transaction ID --}}
                    <div class="dep-form-group">
                        <label class="dep-form-label" id="transactionLabel">
                            Transaction ID / Order ID <span class="req">*</span>
                        </label>
                        <div class="dep-input-wrap">
                            <span class="dep-input-icon"><i class="bi bi-hash"></i></span>
                            <input type="text" name="transaction_id"
                                class="dep-input has-icon-left @error('transaction_id') is-invalid @enderror"
                                placeholder="Enter your transaction or order ID"
                                value="{{ old('transaction_id') }}" required>
                        </div>
                        @error('transaction_id')<div class="dep-error">{{ $message }}</div>@enderror
                        <div class="dep-hint" id="transactionHint">
                            <i class="bi bi-info-circle"></i>
                            Binance Pay → Transfer ID &nbsp;·&nbsp; P2P → Order Number
                        </div>
                    </div>

                    {{-- Screenshot --}}
                    <div class="dep-form-group">
                        <label class="dep-form-label">Payment Screenshot <span class="req">*</span></label>
                        <div class="dep-file-area" id="fileArea">
                            <input type="file" id="payment_proof" name="payment_proof" accept="image/*" required>
                            <i class="bi bi-cloud-arrow-up dep-file-icon"></i>
                            <div class="dep-file-text">Click or drag to upload screenshot</div>
                            <div class="dep-file-sub">PNG, JPG, WEBP — Max 5MB</div>
                        </div>
                        @error('payment_proof')<div class="dep-error">{{ $message }}</div>@enderror
                        <div class="dep-preview d-none" id="previewBox">
                            <span class="dep-preview-label">Preview</span>
                            <img id="previewImage" src="" alt="Payment proof">
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="button" class="dep-btn primary"
                        onclick="confirmFormSubmit('deposit-form',{
                            title:'Confirm Deposit',
                            text:'Submit this deposit request?',
                            confirmText:'Yes, Submit'
                        })">
                        <i class="bi bi-send-fill"></i> Submit Deposit Request
                    </button>
                    <a href="{{ route('wallet.index') }}" class="dep-btn outline">
                        <i class="bi bi-arrow-left"></i> Back to Wallet
                    </a>

                </form>
            </div>
        </div>

    </div>{{-- /left --}}

    {{-- ════ RIGHT ════ --}}
    <div class="dep-right">

        {{-- Balance --}}
        <div class="dep-bal-card">
            <div class="dep-bal-pre">Current Balance</div>
            <div class="dep-bal-val">${{ number_format($wallet->balance ?? 0, 2) }}</div>
            <div class="dep-bal-sub">USDT · Available to use</div>
        </div>

        {{-- Payment Methods Guide --}}
        <div class="dep-method">
            <div class="dep-method-head">Payment Methods</div>

            <div class="dep-method-item">
                <div class="dep-method-badge rec"><i class="bi bi-star-fill"></i> Recommended</div>
                <div class="dep-method-name">Binance Pay</div>
                <div class="dep-method-meta">
                    <span class="dep-method-tag green"><i class="bi bi-check-circle-fill"></i> FREE</span>
                    <span class="dep-method-tag green"><i class="bi bi-lightning-charge-fill"></i> Instant</span>
                </div>
                <ol class="dep-steps">
                    <li>Open Binance → Pay → Send</li>
                    <li>Enter our email / phone</li>
                    <li>Select USDT & enter amount</li>
                    <li>Copy the Transfer ID</li>
                    <li>Paste ID in the form</li>
                </ol>
            </div>

            <div class="dep-method-item">
                <div class="dep-method-badge p2p"><i class="bi bi-people-fill"></i> P2P</div>
                <div class="dep-method-name">Binance P2P</div>
                <div class="dep-method-meta">
                    <span class="dep-method-tag blue"><i class="bi bi-people-fill"></i> Peer-to-Peer</span>
                    <span class="dep-method-tag"><i class="bi bi-clock"></i> 15–30 min</span>
                </div>
                <ol class="dep-steps">
                    <li>Binance → P2P → Buy USDT</li>
                    <li>Pay with local method</li>
                    <li>Send to our address</li>
                    <li>Submit Order Number</li>
                </ol>
            </div>
        </div>

        {{-- Step by step --}}
        <div class="dep-instructions">
            <div class="dep-ins-head">How to Deposit</div>
            <div class="dep-ins-body">
                <ol class="dep-ins-steps">
                    @foreach([
                        'Choose Binance Pay or P2P method',
                        'Send USDT to our wallet address above',
                        'Copy your Transfer ID or Order Number',
                        'Take a screenshot of your payment',
                        'Fill the form and upload screenshot',
                        'Wait 1–4 hours for admin approval'
                    ] as $i => $step)
                    <li class="dep-ins-step">
                        <span class="dep-ins-num">{{ $i+1 }}</span>
                        <span class="dep-ins-text">{{ $step }}</span>
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>

    </div>{{-- /right --}}
</div>

{{-- DEPOSIT HISTORY --}}
<div class="dep-history">
    <div class="dep-history-head">
        <h2 class="dep-history-title">
            <span class="pulse"></span> Recent Deposits
        </h2>
        <span style="font-size:0.72rem;color:var(--muted);">{{ $deposits->total() ?? 0 }} total</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="dep-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Txn ID</th>
                    <th>Status</th>
                    <th>Proof</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $deposit)
                <tr>
                    <td>
                        <div class="dt-date">{{ $deposit->created_at->format('d M Y') }}</div>
                        <div class="dt-time">{{ $deposit->created_at->format('h:i A') }}</div>
                    </td>
                    <td><span class="dt-ref">{{ $deposit->reference_number }}</span></td>
                    <td><span class="dt-amount">{{ number_format($deposit->amount, 2) }} USDT</span></td>
                    <td>
                        @if($deposit->payment_method === 'binance_pay')
                            <span class="dt-method pay"><i class="bi bi-lightning-charge-fill"></i> Binance Pay</span>
                        @elseif($deposit->payment_method === 'binance_p2p')
                            <span class="dt-method p2p"><i class="bi bi-people-fill"></i> P2P</span>
                        @else
                            <span class="dt-method oth">{{ strtoupper($deposit->payment_method) }}</span>
                        @endif
                    </td>
                    <td><span class="dt-txid">{{ Str::limit($deposit->transaction_id, 14) }}</span></td>
                    <td>
                        @php $sc = strtolower($deposit->status); @endphp
                        <span class="dt-status {{ $sc }}">
                            <i class="bi {{ $sc==='pending' ? 'bi-hourglass-split' : ($sc==='approved' ? 'bi-check-circle-fill' : 'bi-x-circle-fill') }}"></i>
                            {{ ucfirst($sc) }}
                        </span>
                    </td>
                    <td>
                        @if($deposit->payment_proof)
                            <a href="{{ asset('storage/'.$deposit->payment_proof) }}" target="_blank" class="dt-view">
                                <i class="bi bi-image"></i> View
                            </a>
                        @else
                            <span style="color:var(--muted);font-size:0.72rem;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="dep-table-empty">
                            <i class="bi bi-inbox"></i>
                            <p>No deposits yet</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($deposits->hasPages())
    <div class="dep-table-footer">{{ $deposits->links() }}</div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function copyAddress() {
    const addr = document.getElementById('walletAddress').textContent.trim();
    navigator.clipboard.writeText(addr).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.classList.add('copied');
        document.getElementById('copyIcon').className = 'bi bi-check2';
        document.getElementById('copyText').textContent = 'Copied!';
        setTimeout(() => {
            btn.classList.remove('copied');
            document.getElementById('copyIcon').className = 'bi bi-clipboard';
            document.getElementById('copyText').textContent = 'Copy Address';
        }, 2500);
    });
}
function setAmount(val) {
    document.querySelector('input[name="amount"]').value = val;
}
document.getElementById('paymentMethod').addEventListener('change', function() {
    const label = document.getElementById('transactionLabel');
    const hint  = document.getElementById('transactionHint');
    if (this.value === 'binance_pay') {
        label.innerHTML = 'Transfer ID <span class="req">*</span>';
        hint.innerHTML  = '<i class="bi bi-info-circle"></i> Enter the Transfer ID from your Binance Pay transaction';
    } else if (this.value === 'binance_p2p') {
        label.innerHTML = 'P2P Order Number <span class="req">*</span>';
        hint.innerHTML  = '<i class="bi bi-info-circle"></i> Enter the Order Number from your P2P purchase';
    }
});
document.getElementById('payment_proof').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImage').src = e.target.result;
        document.getElementById('previewBox').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
    document.querySelector('.dep-file-text').textContent = file.name;
    document.querySelector('.dep-file-sub').textContent  = (file.size/1024).toFixed(1)+' KB';
});
const fa = document.getElementById('fileArea');
fa.addEventListener('dragover',  e => { e.preventDefault(); fa.style.borderColor = 'var(--accent)'; });
fa.addEventListener('dragleave', ()  => { fa.style.borderColor = ''; });
fa.addEventListener('drop',      e => { e.preventDefault(); fa.style.borderColor = ''; });
</script>
@endpush
