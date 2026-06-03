@extends('layouts.app')
@section('title')
    Invest in {{ $company->name }}
@endsection
@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-cash-stack"></i> Invest in {{ $company->name }}</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong>Error!</strong> Please fix the following issues:<br>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Company Info -->
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                @if ($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}"
                                        class="img-fluid rounded" style="max-height: 150px;">
                                @else
                                    <div class="bg-gradient-primary rounded d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 150px; height: 150px;">
                                        <i class="bi bi-building text-white" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h5>{{ $company->name }}</h5>
                                <p class="text-muted small">{{ $company->description }}</p>

                                <div class="row g-2 mt-2">
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <small class="text-muted d-block">Share Price</small>
                                            <strong
                                                class="text-primary">${{ number_format($company->share_price, 2) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <small class="text-muted d-block">Available Shares</small>
                                            <strong
                                                class="text-success">{{ number_format($company->available_shares, 2) }} %</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Investment Form -->
                        <form action="{{ route('companies.process-investment', $company->id) }}" method="POST"
                            id="investmentForm">
                            @csrf

                            <!-- Share Percentage Input -->
                            <div class="mb-4">
                                <label for="share_percentage" class="form-label fw-bold">
                                    Share Percentage (%) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="share_percentage" class="form-control form-control-lg"
                                    id="share_percentage" value="{{ old('share_percentage') }}"
                                    placeholder="e.g., 0.1, 0.9, 1, 1.5, 2" required>
                                <small class="text-muted">
                                    Enter percentage in format: 0.1%, 0.2%...0.9%, 1%, 1.1%...1.9%, 2%, etc.
                                </small>
                                <div id="percentageError" class="text-danger small mt-1" style="display: none;"></div>
                            </div>

                            <div class="mb-4">
                                <label for="invested_amount" class="form-label fw-bold">
                                    Investment Amount ($) <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="invested_amount" class="form-control form-control-lg"
                                    id="invested_amount" value="{{ old('invested_amount') }}" step="0.01"
                                    max="{{ min($user->balance, $company->available_shares * $company->share_price) }}"
                                    readonly required>
                                <small class="text-muted">
                                    This will be calculated automatically based on share percentage (Percentage × Share Price)
                                </small>
                            </div>

                            <!-- Calculation Display -->
                            <div class="alert alert-info" id="calculationBox" style="display: none;">
                                <h6 class="mb-3"><i class="bi bi-calculator"></i> Investment Summary</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted d-block">Investment Amount</small>
                                        <strong id="displayAmount">$0.00</strong>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted d-block">Shares You'll Get</small>
                                        <strong id="displayShares" class="text-primary">0.00</strong>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted d-block">Estimated Ownership</small>
                                        <strong id="displayPercentage" class="text-success">0.00%</strong>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted d-block">Your Current Balance</small>
                                        <strong>${{ number_format($user->balance, 2) }}</strong>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted d-block">Balance After Investment</small>
                                        <strong id="displayBalance">${{ number_format($user->balance, 2) }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Warning Messages -->
                            <div id="warningBox"></div>

                            <!-- Terms and Conditions -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    I understand that this investment is subject to market risks and company performance.
                                    I agree to the <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#termsModal">terms and conditions</a>.
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                    <i class="bi bi-check-circle"></i> Confirm Investment
                                </button>
                                <a href="{{ route('companies.show', $company->id) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Investment Tips -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Investment Tips</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Only invest what you can afford to lose</li>
                            <li>Diversify your investments across multiple companies</li>
                            <li>Research the company's performance and history</li>
                            <li>Monitor your investments regularly</li>
                            <li>Profits are distributed based on your ownership percentage</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Investment Terms</h6>
                    <ol>
                        <li>All investments are subject to market risks and company performance.</li>
                        <li>Share prices may fluctuate based on company valuation changes.</li>
                        <li>Profits will be distributed proportionally to your ownership percentage.</li>
                        <li>Investment amounts will be deducted from your account balance immediately.</li>
                    </ol>

                    <h6 class="mt-3">Partner Rights</h6>
                    <ol>
                        <li>Partners are entitled to receive profit distributions based on ownership.</li>
                        <li>Partners can view company performance and statistics.</li>
                        <li>Shares cannot be transferred without company approval.</li>
                    </ol>

                    <h6 class="mt-3">Risks</h6>
                    <ol>
                        <li>Investment values may decrease based on company performance.</li>
                        <li>There is no guarantee of profit distribution.</li>
                        <li>Shares may not be immediately liquidated.</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sharePercentageInput = document.getElementById('share_percentage');
            const investedAmountInput = document.getElementById('invested_amount');
            const calculationBox = document.getElementById('calculationBox');
            const warningBox = document.getElementById('warningBox');
            const percentageError = document.getElementById('percentageError');
            const agreeTerms = document.getElementById('agreeTerms');
            const submitBtn = document.getElementById('submitBtn');

            const sharePrice = {{ $company->share_price }};
            const availableShares = {{ $company->available_shares }};
            const userBalance = {{ $user->wallet_balance }};
            const totalInvested = {{ $company->partnerShares->where('status', 'active')->sum('invested_amount') }};

            // ----- Validate percentage format -----
            function isValidPercentageFormat(value) {
                // Remove % sign if present
                value = value.toString().replace('%', '').trim();

                const num = parseFloat(value);

                // Check if it's a valid number
                if (isNaN(num) || num <= 0) {
                    return false;
                }

                // Valid formats: 0.1 to 0.9 (with single decimal)
                if (num >= 0.1 && num < 1) {
                    return /^0\.[1-9]$/.test(value);
                }

                // Valid formats: 1, 2, 3, etc. (whole numbers) OR 1.1 to 1.9, 2.1 to 2.9, etc.
                if (num >= 1) {
                    // Check if it's a whole number (1, 2, 3) or has .1 to .9 decimal (1.1, 1.5, 2.3)
                    if (Number.isInteger(num)) {
                        return true; // 1, 2, 3, etc.
                    } else {
                        // Must be X.Y where Y is 1-9
                        const parts = value.split('.');
                        if (parts.length === 2 && parts[1].length === 1 && /^[1-9]$/.test(parts[1])) {
                            return true;
                        }
                    }
                }

                return false;
            }

            // ----- Update calculations and warnings -----
            function updateCalculation() {
                let percentageValue = sharePercentageInput.value.replace('%', '').trim();

                // Clear previous states
                percentageError.style.display = 'none';
                percentageError.textContent = '';
                sharePercentageInput.classList.remove('is-invalid');

                if (!percentageValue) {
                    calculationBox.style.display = 'none';
                    warningBox.innerHTML = '';
                    investedAmountInput.value = '';
                    submitBtn.disabled = true;
                    return;
                }

                // Validate format
                if (!isValidPercentageFormat(percentageValue)) {
                    percentageError.style.display = 'block';
                    percentageError.textContent = 'Invalid format! Please enter: 0.1, 0.2...0.9, 1, 1.1...1.9, 2, 2.1, etc.';
                    sharePercentageInput.classList.add('is-invalid');
                    calculationBox.style.display = 'none';
                    warningBox.innerHTML = '';
                    investedAmountInput.value = '';
                    submitBtn.disabled = true;
                    return;
                }

                const percentage = parseFloat(percentageValue);

                // Calculate investment amount based on percentage and share price
                // Formula: Investment Amount = Percentage × Share Price
                const amount = percentage * sharePrice;

                investedAmountInput.value = amount.toFixed(2);

                const shares = amount / sharePrice;
                const newTotalInvested = totalInvested + amount;
                const ownership = (amount / newTotalInvested) * 100;
                const balanceAfter = userBalance - amount;

                // Show calculation box
                calculationBox.style.display = 'block';
                document.getElementById('displayAmount').textContent =
                    `$${amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                document.getElementById('displayShares').textContent = shares.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                document.getElementById('displayPercentage').textContent =
                    `${ownership.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}%`;
                document.getElementById('displayBalance').textContent =
                    `$${balanceAfter.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

                // Show warning messages
                let warningHTML = '';
                if (amount > userBalance) {
                    warningHTML =
                        `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Insufficient balance! You need $${(amount - userBalance).toLocaleString('en-US', {minimumFractionDigits: 2})} more.</div>`;
                } else if (shares > availableShares) {
                    warningHTML =
                        `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Not enough shares available! Maximum available: ${availableShares.toLocaleString('en-US', {minimumFractionDigits: 2})} shares ($${(availableShares * sharePrice).toLocaleString('en-US', {minimumFractionDigits: 2})}).</div>`;
                } else if (balanceAfter < 0) {
                    warningHTML =
                        `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> This investment exceeds your balance!</div>`;
                } else {
                    warningHTML =
                        `<div class="alert alert-success"><i class="bi bi-check-circle"></i> Investment amount is valid!</div>`;
                }
                warningBox.innerHTML = warningHTML;

                // Enable submit button only if valid
                submitBtn.disabled = !(amount > 0 && amount <= userBalance && shares <= availableShares &&
                    agreeTerms.checked && isValidPercentageFormat(percentageValue));
            }

            // Event listeners
            sharePercentageInput.addEventListener('input', updateCalculation);
            agreeTerms.addEventListener('change', updateCalculation);

            // SweetAlert2 confirmation on submit
            document.getElementById('investmentForm').addEventListener('submit', function(e) {
                e.preventDefault(); // stop default submission

                const amount = parseFloat(investedAmountInput.value);
                const shares = amount / sharePrice;
                const percentage = sharePercentageInput.value.replace('%', '').trim();

                if (isNaN(amount) || amount <= 0 || !isValidPercentageFormat(percentage)) {
                    Swal.fire('Error', 'Please enter a valid share percentage.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Confirm Investment',
                    html: `
                <p>You are about to invest <strong>$${amount.toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></p>
                <p>Share percentage: <strong>${percentage}%</strong></p>
                <p>Shares you will receive: <strong>${shares.toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></p>
                <p>Balance after investment: <strong>$${(userBalance - amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></p>
            `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Invest',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // Submit the form if confirmed
                    }
                });
            });
        });
    </script>


    <style>
        .form-control-lg {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .is-invalid {
            border-color: #dc3545;
        }
    </style>

@endsection
