@extends('layouts.backend')
@section('title') Add Partner Share @endsection
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add New Partner Share</h2>
        <a class="btn btn-secondary" href="{{ route('backend.partner-shares.index') }}">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body">
            <form action="{{ route('backend.partner-shares.store') }}" method="POST" id="partnerShareForm">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label">Partner <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Select Partner</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}"
                                        data-balance="{{ $partner->wallet_balance }}"
                                        {{ old('user_id') == $partner->id ? 'selected' : '' }}>
                                    {{ $partner->name }} (Balance: ${{ $partner->wallet_balance }})
                                </option>
                            @endforeach
                        </select>
                        <small id="userBalance" class="text-muted"></small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                        <select name="company_id" id="company_id" class="form-select" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                        data-share-price="{{ $company->share_price }}"
                                        data-available-shares="{{ $company->available_shares }}"
                                        {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }} (Share Price: ${{ number_format($company->share_price, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <small id="companyInfo" class="text-muted"></small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="invested_amount" class="form-label">Investment Amount ($) <span class="text-danger">*</span></label>
                        <input type="number" name="invested_amount" class="form-control" id="invested_amount"
                               value="{{ old('invested_amount') }}" step="0.01" min="0" required>
                        <small id="shareCalculation" class="text-muted"></small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control" id="purchase_date"
                               value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Create Partner Share
                    </button>
                    <a href="{{ route('backend.partner-shares.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userSelect = document.getElementById('user_id');
            const companySelect = document.getElementById('company_id');
            const investedAmount = document.getElementById('invested_amount');
            const userBalance = document.getElementById('userBalance');
            const companyInfo = document.getElementById('companyInfo');
            const shareCalculation = document.getElementById('shareCalculation');

            // Show user balance
            userSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const balance = selectedOption.getAttribute('data-balance');
                console.log(balance);

                if (balance) {
                    userBalance.textContent = `Available Balance: $${balance}`;
                }
                calculateShares();
            });

            // Show company info
            companySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const availableShares = selectedOption.getAttribute('data-available-shares');
                if (availableShares) {
                    companyInfo.textContent = `Available Shares: ${parseFloat(availableShares).toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                }
                calculateShares();
            });

            // Calculate shares
            investedAmount.addEventListener('input', calculateShares);

            function calculateShares() {
                const amount = parseFloat(investedAmount.value) || 0;
                const companyOption = companySelect.options[companySelect.selectedIndex];
                const sharePrice = parseFloat(companyOption.getAttribute('data-share-price')) || 0;
                const availableShares = parseFloat(companyOption.getAttribute('data-available-shares')) || 0;

                if (amount > 0 && sharePrice > 0) {
                    const quantity = amount / sharePrice;
                    shareCalculation.textContent = `You will get ${quantity.toFixed(2)} shares`;

                    if (quantity > availableShares) {
                        shareCalculation.className = 'text-danger';
                        shareCalculation.textContent += ' (Not enough shares available!)';
                    } else {
                        shareCalculation.className = 'text-success';
                    }
                } else {
                    shareCalculation.textContent = '';
                }

                // Check balance
                const userOption = userSelect.options[userSelect.selectedIndex];
                const balance = parseFloat(userOption.getAttribute('data-balance')) || 0;

                if (amount > balance) {
                    userBalance.className = 'text-danger';
                } else {
                    userBalance.className = 'text-muted';
                }
            }
        });
    </script>

@endsection
