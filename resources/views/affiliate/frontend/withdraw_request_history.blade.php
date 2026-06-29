@extends('layouts.Frontend.master')

@section('title')
    AFFILIATE WITHDRAW REQUESTS
@endsection

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    :root {
        --dash-primary: #4f46e5;
        --dash-primary-glow: rgba(79, 70, 229, 0.3);
        --dash-bg: #f3f4f6;
        --dash-surface: rgba(255, 255, 255, 0.85);
        --dash-border: rgba(226, 232, 240, 0.8);
        --dash-text: #0f172a;
        --dash-muted: #64748b;
    }

    .dash-section {
        background-color: var(--dash-bg);
        font-family: 'Outfit', sans-serif !important;
        min-height: calc(100vh - 150px);
        padding: 40px 0;
        position: relative;
    }

    /* Mesh Background Blob */
    .dash-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, rgba(255,255,255,0) 70%);
        z-index: 0;
        pointer-events: none;
    }

    .dash-container {
        position: relative;
        z-index: 1;
    }

    .dash-card {
        background: var(--dash-surface);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        border: 1px solid var(--dash-border);
        margin-bottom: 24px;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s;
    }

    .dash-card-header {
        background: transparent;
        padding: 24px 28px;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    }

    .dash-card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--dash-text);
        letter-spacing: -0.01em;
    }

    /* Ultra-premium Tabs */
    .dash-tabs {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(10px);
        padding: 6px;
        border-radius: 16px;
        display: inline-flex;
        gap: 4px;
        border: 1px solid rgba(226,232,240,0.8);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        margin-bottom: 30px;
    }

    .dash-tab-link {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--dash-muted);
        padding: 10px 24px;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
    }

    .dash-tab-link:hover {
        color: var(--dash-text);
        background: rgba(241, 245, 249, 0.8);
    }

    .dash-tab-link.active {
        background: #ffffff;
        color: var(--dash-primary);
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    /* Table Design */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
        margin-top: -8px;
    }
    
    .table-modern thead th {
        border: none;
        background: transparent;
        color: var(--dash-muted);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 24px;
    }

    .table-modern tbody tr {
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        transition: all 0.2s;
        border-radius: 12px;
    }
    
    .table-modern tbody tr td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }
    
    .table-modern tbody tr td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .table-modern tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    .table-modern td {
        border: none;
        padding: 16px 24px;
        vertical-align: middle;
        border-top: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }

    .badge-soft {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .badge-soft-success { background: rgba(16,185,129,0.1); color: #059669; }
    .badge-soft-warning { background: rgba(245,158,11,0.1); color: #d97706; }
    .badge-soft-danger { background: rgba(239,68,68,0.1); color: #dc2626; }

    /* ATM Style Form */
    .atm-form-wrapper {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);
    }

    .atm-input-group {
        display: flex;
        align-items: stretch;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #cbd5e1;
        transition: all 0.3s;
    }

    .atm-input-group:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
    }

    .atm-currency {
        background: #f1f5f9;
        color: #475569;
        padding: 15px 20px;
        font-weight: 700;
        font-size: 1.2rem;
        border-right: 2px solid #cbd5e1;
    }

    .atm-input {
        border: none;
        padding: 15px 20px;
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        width: 100%;
        outline: none;
    }
    
    .atm-input::placeholder {
        color: #cbd5e1;
    }

    .btn-withdraw {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 16px 24px;
        font-weight: 700;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-withdraw:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .alert-atm {
        background: rgba(245,158,11,0.1);
        border: 1px solid rgba(245,158,11,0.3);
        color: #b45309;
        border-radius: 12px;
        padding: 16px;
        font-weight: 500;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
</style>

<section class="dash-section">
    <div class="container dash-container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('frontEnd.dashboard.partials.usersideNav')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <!-- Premium Navigation Tabs -->
                <div class="dash-tabs">
                    <a href="{{ route('affiliate.user.index') }}" class="dash-tab-link"><i class="fa-solid fa-chart-pie mr-2"></i>Overview</a>
                    <a href="{{ route('affiliate.user.payment_history') }}" class="dash-tab-link"><i class="fa-solid fa-wallet mr-2"></i>Payouts</a>
                    <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="dash-tab-link active"><i class="fa-solid fa-money-bill-transfer mr-2"></i>Withdraw</a>
                    <a href="{{ route('affiliate.user.payment_settings') }}" class="dash-tab-link"><i class="fa-solid fa-gear mr-2"></i>Settings</a>
                </div>

                <div class="row">
                    <!-- Form to request withdrawal -->
                    <div class="col-md-5 mb-4">
                        <div class="dash-card h-100">
                            <div class="dash-card-header">
                                <h5 class="mb-1">Request Funds</h5>
                                <span class="text-muted small">Move money to your linked account</span>
                            </div>
                            <div class="card-body p-4">
                                <div class="text-center mb-4 p-4 bg-light" style="border-radius: 16px; border: 1px dashed #cbd5e1;">
                                    <div class="text-muted small font-weight-bold text-uppercase tracking-wide mb-1">Available to Withdraw</div>
                                    <h2 class="font-weight-bold text-success mb-0" style="font-size: 2.2rem;">
                                        {{ single_price(Auth::user()->affiliate_user->balance ?? 0) }}
                                    </h2>
                                </div>

                                @php
                                    $minWithdraw = 500; // Standard minimum withdrawal logic
                                    $balance = Auth::user()->affiliate_user->balance ?? 0;
                                @endphp

                                @if ($balance >= $minWithdraw)
                                    <form action="{{ route('affiliate.user.withdraw_request_store') }}" method="POST" class="atm-form-wrapper">
                                        @csrf
                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold text-dark mb-2">Amount to Withdraw</label>
                                            <div class="atm-input-group">
                                                <div class="atm-currency">৳</div>
                                                <input type="number" name="amount" min="{{ $minWithdraw }}" max="{{ $balance }}" step="1" class="atm-input" placeholder="0.00" required>
                                            </div>
                                            <small class="text-muted mt-2 d-block"><i class="fa-solid fa-circle-info mr-1"></i>Minimum limit: ৳{{ $minWithdraw }}</small>
                                        </div>

                                        <button type="submit" class="btn-withdraw">
                                            <i class="fa-solid fa-paper-plane"></i> Send Request
                                        </button>
                                    </form>
                                @else
                                    <div class="alert-atm">
                                        <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                                        <div>
                                            <strong class="d-block mb-1">Insufficient Balance</strong>
                                            You need a minimum balance of ৳{{ $minWithdraw }} to request a withdrawal. Keep sharing your links!
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Withdraw requests list -->
                    <div class="col-md-7 mb-4">
                        <div class="dash-card h-100">
                            <div class="dash-card-header">
                                <h5 class="mb-1">Withdrawal Ledger</h5>
                                <span class="text-muted small">Track your pending and processed requests.</span>
                            </div>
                            <div class="card-body p-4 bg-light">
                                <div class="table-responsive">
                                    <table class="table table-modern text-center w-100">
                                        <thead>
                                            <tr>
                                                <th>Date Submitted</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($affiliate_withdraw_requests as $request)
                                                @php
                                                    $statusBadge = match($request->status) {
                                                        1 => 'badge-soft-success',
                                                        2 => 'badge-soft-danger',
                                                        default => 'badge-soft-warning',
                                                    };
                                                    $statusIcon = match($request->status) {
                                                        1 => 'fa-check-circle',
                                                        2 => 'fa-xmark-circle',
                                                        default => 'fa-clock',
                                                    };
                                                    $statusLabel = match($request->status) {
                                                        1 => 'Paid',
                                                        2 => 'Rejected',
                                                        default => 'Pending',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td class="text-muted font-weight-bold">
                                                        {{ $request->created_at->format('M d, Y') }}
                                                    </td>
                                                    <td class="font-weight-bold text-dark" style="font-size: 1.1rem;">
                                                        ৳{{ number_format($request->amount, 2) }}
                                                    </td>
                                                    <td>
                                                        <span class="badge-soft {{ $statusBadge }}">
                                                            <i class="fa-solid {{ $statusIcon }}"></i> {{ $statusLabel }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-5 text-muted bg-white" style="border-radius: 12px;">
                                                        <div class="mb-3 opacity-50"><i class="fa-solid fa-file-invoice-dollar fa-3x"></i></div>
                                                        <h6 class="font-weight-bold text-dark mb-1">No Requests</h6>
                                                        <p class="small mb-0">You haven't requested any withdrawals yet.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if($affiliate_withdraw_requests->hasPages())
                                    <div class="pt-4 d-flex justify-content-center">
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
