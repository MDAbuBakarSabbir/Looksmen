@extends('layouts.frontEnd.master')

@section('title')
    AFFILIATE PAYMENT SETTINGS
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
                    <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="tab-link">Withdraw requests</a>
                    <a href="{{ route('affiliate.user.payment_settings') }}" class="tab-link active">Payment Settings</a>
                </div>

                <!-- Payment settings form card -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5>Configure Withdrawal Methods</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('affiliate.user.payment_settings_store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <!-- PayPal Section -->
                                <div class="col-md-6 mb-4">
                                    <div class="p-3 border rounded-lg h-100 bg-light">
                                        <h6 class="font-weight-bold text-dark mb-3"><i class="fa-brands fa-paypal text-primary mr-2"></i>PayPal Withdrawal</h6>
                                        <div class="form-group mb-0">
                                            <label class="text-muted small font-weight-bold">PayPal Email Address</label>
                                            <input type="email" name="paypal_email" value="{{ $affiliate_user->paypal_email ?? '' }}" class="form-control" placeholder="example@paypal.com">
                                        </div>
                                    </div>
                                </div>

                                <!-- Bank Account Section -->
                                <div class="col-md-6 mb-4">
                                    <div class="p-3 border rounded-lg h-100 bg-light">
                                        <h6 class="font-weight-bold text-dark mb-3"><i class="fa-solid fa-building-columns text-primary mr-2"></i>Bank Transfer</h6>
                                        <div class="form-group mb-0">
                                            <label class="text-muted small font-weight-bold">Bank Details (Account No, Branch, Bank Name, Routing No)</label>
                                            <textarea name="bank_information" rows="3" class="form-control text-left" placeholder="Enter bank info">{{ $affiliate_user->bank_information ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-3 border-top pt-4">
                                <button type="submit" class="btn-apply-submit"><i class="fa-solid fa-floppy-disk mr-2"></i>Save Payment Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
