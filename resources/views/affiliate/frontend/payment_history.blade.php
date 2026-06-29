@extends('layouts.Frontend.master')

@section('title')
    AFFILIATE PAYOUTS
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
    
    .badge-soft-primary { background: rgba(99,102,241,0.1); color: #4f46e5; }
    .badge-soft-success { background: rgba(16,185,129,0.1); color: #059669; }

    /* Lifetime Summary Widget */
    .summary-widget {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
        border-radius: 20px;
        padding: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 15px 35px rgba(15,23,42,0.2);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .summary-widget::after {
        content: '\f53d';
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -40px;
        font-size: 140px;
        color: rgba(255,255,255,0.03);
        transform: rotate(-15deg);
    }

    .summary-title {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .summary-amount {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        background: linear-gradient(to right, #ffffff, #cbd5e1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
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
                    <a href="{{ route('affiliate.user.payment_history') }}" class="dash-tab-link active"><i class="fa-solid fa-wallet mr-2"></i>Payouts</a>
                    <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="dash-tab-link"><i class="fa-solid fa-money-bill-transfer mr-2"></i>Withdraw</a>
                    <a href="{{ route('affiliate.user.payment_settings') }}" class="dash-tab-link"><i class="fa-solid fa-gear mr-2"></i>Settings</a>
                </div>

                @php
                    $payments = $affiliate_payments instanceof \Illuminate\Database\Eloquent\Builder 
                                ? $affiliate_payments->orderBy('id', 'desc')->paginate(12) 
                                : $affiliate_payments;
                                
                    $totalLifetime = collect($payments->items())->sum('amount');
                @endphp

                <!-- Lifetime Earnings Summary -->
                <div class="summary-widget">
                    <div style="z-index: 2; position: relative;">
                        <div class="summary-title">Total Lifetime Payouts</div>
                        <h2 class="summary-amount">{{ single_price($totalLifetime) }}</h2>
                    </div>
                    <div style="z-index: 2; position: relative;">
                        <div class="bg-white text-dark rounded-circle d-flex justify-content-center align-items-center" style="width: 64px; height: 64px; font-size: 24px; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
                            <i class="fa-solid fa-sack-dollar text-success"></i>
                        </div>
                    </div>
                </div>

                <!-- Payments list -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5 class="mb-1">Payment History</h5>
                        <span class="text-muted small">A record of all your successfully processed payouts.</span>
                    </div>
                    <div class="card-body p-4 bg-light">
                        <div class="table-responsive">
                            <table class="table table-modern text-center w-100">
                                <thead>
                                    <tr>
                                        <th>Payout Date</th>
                                        <th>Amount Sent</th>
                                        <th>Destination</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $key => $payment)
                                        <tr>
                                            <td class="text-muted font-weight-bold">
                                                <i class="fa-regular fa-calendar mr-2 text-primary"></i>
                                                {{ $payment->created_at->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="font-weight-bold text-success" style="font-size: 1.2rem;">
                                                +{{ single_price($payment->amount) }}
                                            </td>
                                            <td>
                                                @if(strtolower($payment->payment_method) == 'paypal')
                                                    <span class="badge-soft badge-soft-primary">
                                                        <i class="fa-brands fa-paypal"></i> PayPal
                                                    </span>
                                                @else
                                                    <span class="badge-soft badge-soft-success">
                                                        <i class="fa-solid fa-building-columns"></i> {{ strtoupper($payment->payment_method) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted bg-white" style="border-radius: 12px;">
                                                <div class="mb-3 opacity-50"><i class="fa-solid fa-wallet fa-3x"></i></div>
                                                <h6 class="font-weight-bold text-dark mb-1">No Payouts Yet</h6>
                                                <p class="small mb-0">Your processed withdrawals will appear here.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($payments instanceof \Illuminate\Pagination\LengthAwarePaginator && $payments->hasPages())
                            <div class="pt-4 d-flex justify-content-center">
                                {{ $payments->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
