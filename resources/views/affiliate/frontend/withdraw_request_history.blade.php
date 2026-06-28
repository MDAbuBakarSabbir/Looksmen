@extends('layouts.Frontend.master')

@section('title')
    AFFILIATE WITHDRAW REQUESTS
@endsection

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --dash-primary: #6366f1;
        --dash-bg: #f8fafc;
        --dash-surface: #ffffff;
        --dash-border: #e2e8f0;
        --dash-text: #1e293b;
        --dash-muted: #64748b;
    }

    .dash-section {
        background-color: var(--dash-bg);
        font-family: 'Outfit', sans-serif !important;
        min-height: calc(100vh - 150px);
        padding: 40px 0;
    }

    .dash-card {
        background: var(--dash-surface);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid var(--dash-border);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .dash-card-header {
        background: #ffffff;
        padding: 20px 24px;
        border-bottom: 1px solid var(--dash-border);
    }

    .dash-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--dash-text);
    }

    .tab-link {
        font-weight: 600;
        color: var(--dash-muted);
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.2s;
        text-decoration: none !important;
    }

    .tab-link.active {
        background: #e0e7ff;
        color: var(--dash-primary);
    }

    .btn-apply-submit {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
    }

    .btn-apply-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
        color: white;
    }
</style>

<section class="dash-section">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('frontEnd.dashboard.partials.usersideNav')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <!-- Navigation Tabs for Affiliate Panel -->
                <div class="d-flex mb-4 flex-wrap" style="gap: 10px;">
                    <a href="{{ route('affiliate.user.index') }}" class="tab-link">Dashboard</a>
                    <a href="{{ route('affiliate.user.payment_history') }}" class="tab-link">Payments</a>
                    <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="tab-link active">Withdraw requests</a>
                    <a href="{{ route('affiliate.user.payment_settings') }}" class="tab-link">Payment Settings</a>
                </div>

                <div class="row">
                    <!-- Form to request withdrawal -->
                    <div class="col-md-4 mb-4">
                        <div class="dash-card">
                            <div class="dash-card-header">
                                <h5>Request Withdrawal</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="text-center mb-4 p-3 bg-light rounded-lg">
                                    <div class="text-muted small">Available Balance</div>
                                    <h4 class="font-weight-bold text-success mb-0">
                                        {{ single_price(Auth::user()->affiliate_user->balance ?? 0) }}
                                    </h4>
                                </div>

                                @php
                                    $minWithdraw = 500; // Standard minimum withdrawal logic
                                    $balance = Auth::user()->affiliate_user->balance ?? 0;
                                @endphp

                                @if ($balance >= $minWithdraw)
                                    <form action="{{ route('affiliate.user.withdraw_request_store') }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold text-muted small">Withdrawal Amount (৳)</label>
                                            <input type="number" name="amount" min="{{ $minWithdraw }}" max="{{ $balance }}" step="1" class="form-control" placeholder="Min {{ $minWithdraw }}" required>
                                            <small class="text-muted mt-1 d-block">Minimum withdrawal limit: ৳{{ $minWithdraw }}</small>
                                        </div>

                                        <button type="submit" class="btn-apply-submit w-100"><i class="fa-solid fa-money-bill-transfer mr-2"></i>Send Request</button>
                                    </form>
                                @else
                                    <div class="alert alert-warning border-0 rounded-lg p-3 small mb-0">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>You need a minimum balance of ৳{{ $minWithdraw }} to request a withdrawal.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Withdraw requests list -->
                    <div class="col-md-8 mb-4">
                        <div class="dash-card">
                            <div class="dash-card-header">
                                <h5>Withdrawal Logs</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0 text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Requested Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($affiliate_withdraw_requests as $request)
                                                @php
                                                    $statusBadge = match($request->status) {
                                                        1 => 'badge-success',
                                                        2 => 'badge-danger',
                                                        default => 'badge-warning text-dark',
                                                    };
                                                    $statusLabel = match($request->status) {
                                                        1 => 'Paid',
                                                        2 => 'Rejected',
                                                        default => 'Pending',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td>{{ $request->created_at->format('d M, Y') }}</td>
                                                    <td class="font-weight-bold">৳{{ number_format($request->amount, 2) }}</td>
                                                    <td>
                                                        <span class="badge px-3 py-1 font-weight-bold {{ $statusBadge }}">
                                                            {{ $statusLabel }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-5 text-muted">
                                                        <i class="fa-solid fa-file-invoice-dollar fa-2x mb-2 opacity-50"></i><br>
                                                        No withdraw requests submitted yet.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if($affiliate_withdraw_requests->hasPages())
                                    <div class="px-4 py-3 d-flex justify-content-center">
                                        {{ $affiliate_withdraw_requests->links('pagination::bootstrap-4') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
