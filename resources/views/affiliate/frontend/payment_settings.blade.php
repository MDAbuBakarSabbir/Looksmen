@extends('layouts.Frontend.master')

@section('title')
    AFFILIATE PAYMENT SETTINGS
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

    .btn-apply-submit {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 28px;
        font-weight: 600;
        font-size: 1.05rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px var(--dash-primary-glow);
    }

    .btn-apply-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        color: white;
    }

    /* Brand Payment Blocks */
    .payment-block {
        background: #ffffff;
        border: 2px solid transparent;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        transition: all 0.3s;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        position: relative;
        overflow: hidden;
    }

    .payment-block::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        transition: all 0.3s;
        pointer-events: none;
    }

    .payment-block:focus-within::before {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
    }

    .payment-brand-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .payment-brand-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .icon-paypal { background: #e0f2fe; color: #0284c7; }
    .icon-bank { background: #f1f5f9; color: #475569; }

    .payment-brand-header h6 {
        margin: 0;
        font-weight: 700;
        font-size: 1.1rem;
        color: #0f172a;
    }

    .form-control-premium {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 12px 16px;
        font-size: 1rem;
        background: #f8fafc;
        transition: all 0.2s;
    }

    .form-control-premium:focus {
        background: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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
                    <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="dash-tab-link"><i class="fa-solid fa-money-bill-transfer mr-2"></i>Withdraw</a>
                    <a href="{{ route('affiliate.user.payment_settings') }}" class="dash-tab-link active"><i class="fa-solid fa-gear mr-2"></i>Settings</a>
                </div>

                <!-- Payment settings form card -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5 class="mb-1">Payment Profiles</h5>
                        <span class="text-muted small">Configure where you want your affiliate earnings to be sent.</span>
                    </div>
                    <div class="card-body p-4 p-md-5 bg-light">
                        <form action="{{ route('affiliate.user.payment_settings_store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <!-- PayPal Section -->
                                <div class="col-md-6 mb-4">
                                    <div class="payment-block">
                                        <div class="payment-brand-header">
                                            <div class="payment-brand-icon icon-paypal">
                                                <i class="fa-brands fa-paypal"></i>
                                            </div>
                                            <div>
                                                <h6>PayPal</h6>
                                                <span class="text-muted small">Fast digital payments</span>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="text-dark small font-weight-bold mb-2">PayPal Email Address</label>
                                            <input type="email" name="paypal_email" value="{{ $affiliate_user->paypal_email ?? '' }}" class="form-control form-control-premium" placeholder="e.g. john@example.com">
                                        </div>
                                    </div>
                                </div>

                                <!-- Bank Account Section -->
                                <div class="col-md-6 mb-4">
                                    <div class="payment-block">
                                        <div class="payment-brand-header">
                                            <div class="payment-brand-icon icon-bank">
                                                <i class="fa-solid fa-building-columns"></i>
                                            </div>
                                            <div>
                                                <h6>Bank Transfer</h6>
                                                <span class="text-muted small">Direct to your bank</span>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="text-dark small font-weight-bold mb-2">Bank Details (Account, Branch, Routing)</label>
                                            <textarea name="bank_information" rows="3" class="form-control form-control-premium" placeholder="e.g. Account No: 123456789, Branch: NYC, Bank Name: Chase, Routing No: 111000111">{{ $affiliate_user->bank_information ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-3 pt-3">
                                <button type="submit" class="btn-apply-submit"><i class="fa-solid fa-floppy-disk mr-2"></i>Save Configuration</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
