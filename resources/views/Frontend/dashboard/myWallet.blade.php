@extends('layouts.Frontend.master')
@section('title')
    MY WALLET & CLUB POINTS
@endsection
@section('content')
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex align-items-start">

                @include('Frontend.dashboard.partials.usersideNav')

                <div class="aiz-user-panel" style="flex: 1; margin-left: 20px;">
                    <div class="aiz-titlebar mt-2 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="h3 font-weight-bold text-dark mb-0">My Wallet & Club Points</h1>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Stat Cards -->
                    <div class="row gutters-10 mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-white" style="border-radius: 16px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); overflow: hidden; position: relative;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0 text-white-50 fs-16">Wallet Balance</h5>
                                        <i class="las la-wallet fs-36 text-white-50"></i>
                                    </div>
                                    <h2 class="display-5 font-weight-bold text-white mb-2">৳{{ number_format($user->wallet_balance, 2) }}</h2>
                                    <p class="mb-0 text-white-50 fs-13">Ready to be used for your next purchase</p>
                                </div>
                                <div style="position: absolute; right: -20px; bottom: -20px; opacity: 0.1; font-size: 150px; line-height: 1;">
                                    <i class="las la-wallet"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-white" style="border-radius: 16px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); overflow: hidden; position: relative;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0 text-white-50 fs-16">Club Points</h5>
                                        <i class="las la-coins fs-36 text-white-50"></i>
                                    </div>
                                    <h2 class="display-5 font-weight-bold text-white mb-2">{{ number_format($user->points) }} <span class="fs-18">Pts</span></h2>
                                    <p class="mb-0 text-white-50 fs-13">Rate: {{ $point_conversion_rate }} Pts = ৳1.00</p>
                                </div>
                                <div style="position: absolute; right: -20px; bottom: -20px; opacity: 0.1; font-size: 150px; line-height: 1;">
                                    <i class="las la-coins"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs and Forms Card -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header bg-white border-0 pt-4">
                            <ul class="nav nav-pills" id="walletTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active px-4 py-2 font-weight-bold" id="topup-tab" data-toggle="tab" href="#topup" role="tab" style="border-radius: 20px;">Top-Up Wallet</a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="nav-link px-4 py-2 font-weight-bold" id="points-tab" data-toggle="tab" href="#points" role="tab" style="border-radius: 20px;">Convert Points</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="walletTabContent">
                                <!-- Top-up Tab -->
                                <div class="tab-pane fade show active" id="topup" role="tabpanel">
                                    <form action="{{ route('wallet.recharge') }}" method="POST" id="recharge-form">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label class="font-weight-600 text-dark">Amount (BDT)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text font-weight-bold" style="background:#f1f5f9; border-radius:10px 0 0 10px;">৳</span>
                                                        </div>
                                                        <input type="number" name="amount" min="1" step="any" class="form-control px-3" placeholder="Enter amount to recharge" style="border-radius:0 10px 10px 0;" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-600 text-dark">Payment Method</label>
                                                    <div class="d-flex" style="gap: 15px;">
                                                        <label class="payment-method-card flex-grow-1 p-3 border rounded text-center cursor-pointer mb-0 active-method" style="border-radius:12px; transition:all 0.2s;">
                                                            <input type="radio" name="payment_method" value="bkash" class="d-none" checked>
                                                            <img src="https://www.logo.wine/a/logo/BKash/BKash-Logo.wine.svg" style="max-height: 40px; margin-bottom:5px;" class="img-fluid" alt="bKash">
                                                            <span class="d-block font-weight-bold fs-13">bKash Instant</span>
                                                        </label>
                                                        <label class="payment-method-card flex-grow-1 p-3 border rounded text-center cursor-pointer mb-0" style="border-radius:12px; transition:all 0.2s;">
                                                            <input type="radio" name="payment_method" value="manual_deposit" class="d-none">
                                                            <i class="las la-university fs-30 text-primary mb-1"></i>
                                                            <span class="d-block font-weight-bold fs-13">Bank / Manual</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3 d-none" id="manual-details-section">
                                                <div class="card bg-light border-0 p-3" style="border-radius: 12px;">
                                                    <h5 class="fs-14 font-weight-bold text-dark mb-2">Manual Bank Transfer Details</h5>
                                                    <p class="fs-12 text-muted mb-3">
                                                        Please transfer the amount to the bank below, then enter your details:<br>
                                                        <strong>Bank Name:</strong> Looksmen Bank Ltd<br>
                                                        <strong>Account Number:</strong> 1209-5487-1234<br>
                                                        <strong>Or bKash Personal:</strong> 01700000000
                                                    </p>
                                                    <div class="form-group mb-2">
                                                        <label class="fs-12 font-weight-600 text-dark">Transaction ID / Reference</label>
                                                        <input type="text" name="trx_id" id="trx_id" class="form-control form-control-sm" placeholder="e.g. TRX12984578">
                                                    </div>
                                                    <div class="form-group mb-2">
                                                        <label class="fs-12 font-weight-600 text-dark">Sender Account / Reference Info</label>
                                                        <textarea name="bank_info" id="bank_info" rows="2" class="form-control form-control-sm" placeholder="Bank name, branch, or sending phone number"></textarea>
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label class="fs-12 font-weight-600 text-dark">Additional Note</label>
                                                        <input type="text" name="note" class="form-control form-control-sm" placeholder="Optional notes">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold" style="border-radius:10px;">Proceed to Recharge</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Points Tab -->
                                <div class="tab-pane fade" id="points" role="tabpanel">
                                    <form action="{{ route('wallet.convert-points') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label class="font-weight-600 text-dark">Points to Convert</label>
                                                    <input type="number" name="points" min="{{ $point_conversion_rate }}" step="{{ $point_conversion_rate }}" class="form-control" placeholder="Enter points to convert (Multiple of {{ $point_conversion_rate }})" required>
                                                    <small class="text-muted mt-1 d-block">You have <strong>{{ $user->points }}</strong> points. You must convert in multiples of {{ $point_conversion_rate }}.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="card bg-light border-0 p-3 h-100 d-flex flex-column justify-content-center" style="border-radius: 12px;">
                                                    <h5 class="fs-14 font-weight-bold text-success mb-2"><i class="las la-info-circle mr-1"></i> Point Conversion Rules</h5>
                                                    <ul class="pl-3 fs-13 text-muted mb-0">
                                                        <li>Minimum conversion limit: <strong>{{ $point_conversion_rate }} points</strong>.</li>
                                                        <li>Conversion value: <strong>{{ $point_conversion_rate }} points = ৳1.00 BDT</strong>.</li>
                                                        <li>Converted points will immediately reflect as usable balance in your wallet.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right mt-3">
                                            <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold" style="border-radius:10px;" @if($user->points < $point_conversion_rate) disabled @endif>Convert to Cash</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Histories Card -->
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header bg-white border-0 pt-4">
                            <h5 class="font-weight-bold text-dark mb-0">Transaction History</h5>
                        </div>
                        <div class="card-body">
                            <!-- Nav tabs for histories -->
                            <ul class="nav nav-tabs border-bottom mb-3" id="historyTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active px-3" id="wallet-history-tab" data-toggle="tab" href="#wallet-history" role="tab">Wallet Statement</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-3" id="points-history-tab" data-toggle="tab" href="#points-history" role="tab">Club Points Statement</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="historyTabContent">
                                <!-- Wallet Statement -->
                                <div class="tab-pane fade show active" id="wallet-history" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Tx ID</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($wallet_transactions as $tx)
                                                    @php
                                                        $tx_details = json_decode($tx->payment_details, true);
                                                        $trxIdToShow = $tx_details['trxID'] ?? $tx_details['trx_id'] ?? '-';
                                                    @endphp
                                                    <tr>
                                                        <td class="fs-13">{{ $tx->created_at->format('d M, Y h:i A') }}</td>
                                                        <td class="font-weight-bold fs-13">{{ $trxIdToShow }}</td>
                                                        <td class="font-weight-bold fs-14 text-dark">৳{{ number_format($tx->amount, 2) }}</td>
                                                        <td>
                                                            <span class="badge badge-light text-dark text-capitalize px-2 py-1" style="border-radius:6px;">
                                                                {{ str_replace('_', ' ', $tx->payment_method) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($tx->type === 'credit')
                                                                <span class="text-success font-weight-bold"><i class="las la-plus-circle mr-1"></i>Credit</span>
                                                            @else
                                                                <span class="text-danger font-weight-bold"><i class="las la-minus-circle mr-1"></i>Debit</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($tx->status === 'approved')
                                                                <span class="badge badge-success px-2 py-1" style="border-radius:12px;">Success</span>
                                                            @elseif($tx->status === 'pending')
                                                                <span class="badge badge-warning text-dark px-2 py-1" style="border-radius:12px;">Pending</span>
                                                            @else
                                                                <span class="badge badge-danger px-2 py-1" style="border-radius:12px;">Failed</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4 text-muted">No transactions found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        {{ $wallet_transactions->links() }}
                                    </div>
                                </div>

                                <!-- Points Statement -->
                                <div class="tab-pane fade" id="points-history" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Points</th>
                                                    <th>Type</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($point_transactions as $ptx)
                                                    <tr>
                                                        <td class="fs-13">{{ $ptx->created_at->format('d M, Y h:i A') }}</td>
                                                        <td class="font-weight-bold fs-14 {{ $ptx->points > 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $ptx->points > 0 ? '+' : '' }}{{ number_format($ptx->points) }}
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light text-dark text-capitalize px-2 py-1" style="border-radius:6px;">
                                                                {{ $ptx->type }}
                                                            </span>
                                                        </td>
                                                        <td class="text-muted fs-13">{{ $ptx->details }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4 text-muted">No point transaction histories found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        {{ $point_transactions->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .payment-method-card input[type="radio"]:checked + span,
        .payment-method-card.active-method {
            border-color: #4f46e5 !important;
            background-color: #f5f3ff !important;
            box-shadow: 0 0 0 1px #4f46e5;
        }
        .payment-method-card:hover {
            border-color: #c084fc;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.payment-method-card').on('click', function() {
                $('.payment-method-card').removeClass('active-method');
                $(this).addClass('active-method');
                $(this).find('input[type="radio"]').prop('checked', true);

                let selectedMethod = $(this).find('input[type="radio"]').val();
                if (selectedMethod === 'manual_deposit') {
                    $('#manual-details-section').removeClass('d-none');
                    $('#trx_id').attr('required', true);
                    $('#bank_info').attr('required', true);
                } else {
                    $('#manual-details-section').addClass('d-none');
                    $('#trx_id').removeAttr('required').val('');
                    $('#bank_info').removeAttr('required').val('');
                }
            });
        });
    </script>
@endsection


