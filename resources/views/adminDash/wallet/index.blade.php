@extends('layouts.Backend.master')

@section('content')
<div class="row">
    <!-- Wallet Adjustment Form -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="las la-sliders-h mr-2"></i>Adjust User Wallet Balance</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.wallet.adjust') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-600">Select Customer</label>
                        <select name="user_id" class="form-control select2" required style="width: 100%;">
                            <option value="">-- Select Customer --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }}) - Bal: ৳{{ number_format($user->wallet_balance, 2) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group row">
                        <div class="col-6">
                            <label class="font-weight-600">Amount (BDT)</label>
                            <input type="number" name="amount" min="1" step="any" class="form-control" required placeholder="e.g. 100">
                        </div>
                        <div class="col-6">
                            <label class="font-weight-600">Action</label>
                            <select name="action" class="form-control" required>
                                <option value="credit">Add Balance (Credit)</option>
                                <option value="debit">Deduct Balance (Debit)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-600">Note / Reason</label>
                        <input type="text" name="note" class="form-control" placeholder="Reason for adjustment" required>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary px-4">Submit Adjustment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pending Manual Recharges -->
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden; height: 100%;">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="las la-university mr-2"></i>Pending Manual Recharges</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Deposit Details</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $pending = $transactions->where('payment_method', 'manual_deposit')->where('status', 'pending');
                            @endphp
                            @forelse($pending as $req)
                                @php
                                    $details = json_decode($req->payment_details, true);
                                @endphp
                                <tr>
                                    <td>
                                        <span class="d-block font-weight-bold">{{ $req->user->name ?? 'Deleted User' }}</span>
                                        <small class="text-muted">{{ $req->user->email ?? '' }}</small>
                                    </td>
                                    <td class="font-weight-bold text-primary">৳{{ number_format($req->amount, 2) }}</td>
                                    <td class="fs-12">
                                        <strong>Trx ID:</strong> {{ $details['trx_id'] ?? '-' }}<br>
                                        <strong>Account:</strong> {{ $details['bank_info'] ?? '-' }}<br>
                                        <strong>Note:</strong> {{ $details['note'] ?? '' }}
                                    </td>
                                    <td class="text-right">
                                        <form action="{{ route('admin.wallet.recharge.approve', $req->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm px-3" onclick="return confirm('Approve this recharge request?')">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.wallet.recharge.reject', $req->id) }}" method="POST" class="d-inline-block ml-1">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm px-3" onclick="return confirm('Reject this recharge request?')">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No pending manual recharge requests.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction History Logs -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3">
                <ul class="nav nav-pills" id="logTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active px-4 py-2 font-weight-bold" id="wallet-logs-tab" data-toggle="tab" href="#wallet-logs" role="tab" style="border-radius: 20px;">Wallet Transaction Logs</a>
                    </li>
                    <li class="nav-item ml-2">
                        <a class="nav-link px-4 py-2 font-weight-bold" id="points-logs-tab" data-toggle="tab" href="#points-logs" role="tab" style="border-radius: 20px;">Points History Logs</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content" id="logTabContent">
                    <!-- Wallet Logs -->
                    <div class="tab-pane fade show active" id="wallet-logs" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $tx)
                                        @php
                                            $details = json_decode($tx->payment_details, true);
                                            $trxToShow = $details['trxID'] ?? $details['trx_id'] ?? '-';
                                            $noteToShow = $details['note'] ?? '';
                                        @endphp
                                        <tr>
                                            <td>{{ $tx->created_at->format('d M, Y h:i A') }}</td>
                                            <td>
                                                <span class="d-block font-weight-bold">{{ $tx->user->name ?? 'Deleted User' }}</span>
                                                <small class="text-muted">{{ $tx->user->email ?? '' }}</small>
                                            </td>
                                            <td class="font-weight-bold">৳{{ number_format($tx->amount, 2) }}</td>
                                            <td><span class="badge badge-light text-capitalize px-2 py-1">{{ str_replace('_', ' ', $tx->payment_method) }}</span></td>
                                            <td>
                                                @if($tx->type === 'credit')
                                                    <span class="text-success font-weight-bold"><i class="las la-plus-circle mr-1"></i>Credit</span>
                                                @else
                                                    <span class="text-danger font-weight-bold"><i class="las la-minus-circle mr-1"></i>Debit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tx->status === 'approved')
                                                    <span class="badge badge-success px-2 py-1" style="border-radius:12px;">Approved</span>
                                                @elseif($tx->status === 'pending')
                                                    <span class="badge badge-warning text-dark px-2 py-1" style="border-radius:12px;">Pending</span>
                                                @else
                                                    <span class="badge badge-danger px-2 py-1" style="border-radius:12px;">Failed</span>
                                                @endif
                                            </td>
                                            <td class="text-muted fs-12">
                                                @if($trxToShow !== '-') <strong>Trx ID:</strong> {{ $trxToShow }}<br> @endif
                                                @if($noteToShow) <strong>Note:</strong> {{ $noteToShow }} @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">No transactions recorded.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            {{ $transactions->links() }}
                        </div>
                    </div>

                    <!-- Points Logs -->
                    <div class="tab-pane fade" id="points-logs" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Points</th>
                                        <th>Type</th>
                                        <th>Order ID</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($point_transactions as $ptx)
                                        <tr>
                                            <td>{{ $ptx->created_at->format('d M, Y h:i A') }}</td>
                                            <td>
                                                <span class="d-block font-weight-bold">{{ $ptx->user->name ?? 'Deleted User' }}</span>
                                                <small class="text-muted">{{ $ptx->user->email ?? '' }}</small>
                                            </td>
                                            <td class="font-weight-bold {{ $ptx->points > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $ptx->points > 0 ? '+' : '' }}{{ number_format($ptx->points) }}
                                            </td>
                                            <td><span class="badge badge-light text-capitalize px-2 py-1">{{ $ptx->type }}</span></td>
                                            <td>
                                                @if($ptx->order_id)
                                                    <a href="{{ route('admin.order-show', $ptx->order_id) }}" class="font-weight-bold text-primary">#LM-{{ $ptx->order_id }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-muted fs-12">{{ $ptx->details }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No point histories recorded.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            {{ $point_transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: "-- Select Customer --",
                allowClear: true
            });
        }
    });
</script>
@endsection
