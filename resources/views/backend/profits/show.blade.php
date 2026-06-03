<div class="col-md-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Distribution Summary</h5>
        </div>
        <div class="card-body">
            @if($profit->distributions->isNotEmpty())
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Partners:</span>
                        <strong>{{ $profit->distributions->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Distributed:</span>
                        <strong class="text-success">৳{{ number_format($profit->total_distributed, 2) }}</strong>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Partner</th>
                                <th>Share %</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($profit->distributions as $distribution)
                            <tr>
                                <td>
                                    <div>{{ $distribution->user->name }}</div>
                                    <small class="text-muted">{{ $distribution->user->email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ number_format($distribution->share_percentage, 2) }}%</span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success">৳{{ number_format($distribution->profit_amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($distribution->status == 'paid')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Paid
                                        </span>
                                        <div class="small text-muted">
                                            {{ $distribution->paid_at->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="2">Total:</td>
                                <td class="text-end text-success">৳{{ number_format($profit->distributions->sum('profit_amount'), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-cash-stack text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No distribution yet.</p>
                    @if($profit->distribution_status == 'pending')
                        <p class="text-muted small">Click the "Distribute Profit" button to share profit with all active partners.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
