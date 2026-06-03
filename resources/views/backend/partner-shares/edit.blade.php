@extends('layouts.backend')
@section('title') Edit Partner Share @endsection
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Partner Share</h2>
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

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Update Share Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.partner-shares.update', $partnerShare->id) }}" method="POST" id="updateForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Partner</label>
                                <input type="text" class="form-control" value="{{ $partnerShare->user->name }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" class="form-control" value="{{ $partnerShare->company->name }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Invested Amount</label>
                                <input type="text" class="form-control" value="৳{{ number_format($partnerShare->invested_amount, 2) }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Share Quantity</label>
                                <input type="text" class="form-control" value="{{ number_format($partnerShare->share_quantity, 2) }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Share Percentage</label>
                                <input type="text" class="form-control" value="{{ number_format($partnerShare->share_percentage, 2) }}%" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Share Price</label>
                                <input type="text" class="form-control" value="৳{{ number_format($currentCompany->share_price, 2) }}" readonly>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="active" {{ old('status', $partnerShare->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="sold" {{ old('status', $partnerShare->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                                    <option value="transferred" {{ old('status', $partnerShare->status) == 'transferred' ? 'selected' : '' }}>Transferred</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3" id="soldPriceField" style="display: none;">
                                <label for="sold_price" class="form-label">Sold Price (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="sold_price" id="sold_price" class="form-control"
                                       value="{{ old('sold_price', $partnerShare->sold_price) }}"
                                       step="0.01" min="0">
                                <small class="text-muted">
                                    Original Investment: ৳{{ number_format($partnerShare->invested_amount, 2) }} |
                                    Current Market Value: ৳{{ number_format($partnerShare->share_quantity * $currentCompany->share_price, 2) }}
                                </small>
                                <div id="profitLoss" class="mt-2"></div>
                            </div>

                            <div class="col-md-12 mb-3" id="transferField" style="display: none;">
                                <label for="transfer_to_user_id" class="form-label">Transfer To <span class="text-danger">*</span></label>
                                <select name="transfer_to_user_id" id="transfer_to_user_id" class="form-select">
                                    <option value="">Select User</option>
                                    @foreach($partners as $partner)
                                        @if($partner->id != $partnerShare->user_id)
                                            <option value="{{ $partner->id }}" {{ old('transfer_to_user_id') == $partner->id ? 'selected' : '' }}>
                                                {{ $partner->name }} ({{ $partner->email }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Important:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Sold:</strong> Amount will be credited to wallet. Shares become available.</li>
                                <li><strong>Transferred:</strong> Ownership transferred to another user.</li>
                                <li><strong>Active:</strong> Partner receives profit distributions.</li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Status
                            </button>
                            <a href="{{ route('backend.partner-shares.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Share Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Purchase Date</small>
                        <strong>{{ $partnerShare->purchase_date->format('d M Y') }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Current Market Value</small>
                        <strong class="text-success">৳{{ number_format($partnerShare->current_value, 2) }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Investment Value</small>
                        <strong>৳{{ number_format($partnerShare->invested_amount, 2) }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Unrealized Profit/Loss</small>
                        <strong class="{{ $partnerShare->unrealized_profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $partnerShare->unrealized_profit_loss >= 0 ? '+' : '' }}৳{{ number_format($partnerShare->unrealized_profit_loss, 2) }}
                            ({{ number_format($partnerShare->unrealized_profit_loss_percentage, 2) }}%)
                        </strong>
                    </div>

                    @if($partnerShare->status == 'sold' && $partnerShare->sold_price)
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted d-block">Sold Price</small>
                        <strong>৳{{ number_format($partnerShare->sold_price, 2) }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Realized Profit/Loss</small>
                        <strong class="{{ $partnerShare->profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $partnerShare->profit_loss >= 0 ? '+' : '' }}৳{{ number_format($partnerShare->profit_loss, 2) }}
                            ({{ number_format($partnerShare->profit_loss_percentage, 2) }}%)
                        </strong>
                    </div>
                    @endif

                    @if($partnerShare->status == 'transferred')
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted d-block">Transferred To</small>
                        <strong>{{ $partnerShare->transferredToUser->name ?? 'N/A' }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const soldPriceField = document.getElementById('soldPriceField');
            const transferField = document.getElementById('transferField');
            const soldPriceInput = document.getElementById('sold_price');
            const transferUserSelect = document.getElementById('transfer_to_user_id');
            const profitLossDiv = document.getElementById('profitLoss');

            const investedAmount = {{ $partnerShare->invested_amount }};

            function toggleFields() {
                const status = statusSelect.value;
                soldPriceField.style.display = 'none';
                transferField.style.display = 'none';
                soldPriceInput.removeAttribute('required');
                transferUserSelect.removeAttribute('required');

                if (status === 'sold') {
                    soldPriceField.style.display = 'block';
                    soldPriceInput.setAttribute('required', 'required');
                } else if (status === 'transferred') {
                    transferField.style.display = 'block';
                    transferUserSelect.setAttribute('required', 'required');
                }
            }

            function calculateProfitLoss() {
                const soldPrice = parseFloat(soldPriceInput.value) || 0;
                const profitLoss = soldPrice - investedAmount;
                const percentage = investedAmount > 0 ? (profitLoss / investedAmount) * 100 : 0;

                if (soldPrice > 0) {
                    const color = profitLoss >= 0 ? 'success' : 'danger';
                    const sign = profitLoss >= 0 ? '+' : '';
                    profitLossDiv.innerHTML = `
                        <div class="alert alert-${color}">
                            <strong>Profit/Loss:</strong> ${sign}৳${profitLoss.toLocaleString('en-US', {minimumFractionDigits: 2})}
                            (${sign}${percentage.toFixed(2)}%)
                        </div>
                    `;
                } else {
                    profitLossDiv.innerHTML = '';
                }
            }

            statusSelect.addEventListener('change', toggleFields);
            soldPriceInput.addEventListener('input', calculateProfitLoss);

            toggleFields();
            if (soldPriceInput.value) {
                calculateProfitLoss();
            }
        });
    </script>

@endsection
