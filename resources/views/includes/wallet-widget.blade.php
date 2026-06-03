<!-- Wallet Widget - anywhere can use -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-muted">
                <i class="bi bi-wallet2 me-2"></i>My Wallet
            </h6>
            <a href="{{ route('wallet.index') }}" class="btn btn-sm btn-link text-decoration-none">
                View Details <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3">
            <!-- Balance -->
            <div class="col-6">
                <div class="bg-light rounded p-3 text-center">
                    <small class="text-muted d-block mb-1">Balance</small>
                    <h5 class="mb-0 text-primary" id="wallet-balance">
                        ${{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}
                    </h5>
                </div>
            </div>

            <!-- Available -->
            <div class="col-6">
                <div class="bg-light rounded p-3 text-center">
                    <small class="text-muted d-block mb-1">Available</small>
                    <h5 class="mb-0 text-success" id="wallet-available">
                        ${{ number_format(auth()->user()->wallet->available_balance ?? 0, 2) }}
                    </h5>
                </div>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="d-grid gap-2 mt-3">
            <a href="{{ route('packages.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-box-seam me-1"></i> Buy Package
            </a>
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('wallet.deposit') }}" class="btn btn-outline-success btn-sm w-100">
                        <i class="bi bi-plus-circle"></i> Deposit
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('withdraw.index') }}" class="btn btn-outline-warning btn-sm w-100">
                        <i class="bi bi-arrow-up-circle"></i> Withdraw
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto refresh wallet balance every 30 seconds
    setInterval(function() {
        fetch('{{ route("wallet.balance") }}')
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('wallet-balance').textContent =
                        '$' + parseFloat(data.data.balance).toFixed(2);
                    document.getElementById('wallet-available').textContent =
                        '$' + parseFloat(data.data.available_balance).toFixed(2);
                }
            })
            .catch(error => console.error('Error:', error));
    }, 30000);
</script>
@endpush
