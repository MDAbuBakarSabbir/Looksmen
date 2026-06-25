@extends('layouts.Frontend.master')

@section('title')
    AFFILIATE PARTNER PROGRAM
@endsection

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    .affiliate-section {
        font-family: 'Outfit', sans-serif !important;
        background: #f8fafc;
        color: #1e293b;
    }

    .hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        border-radius: 24px;
        color: white;
        padding: 60px 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .hero-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -30%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 60%);
        pointer-events: none;
    }

    .btn-affiliate-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 32px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-affiliate-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        color: white;
        opacity: 0.95;
    }

    .feature-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 30px;
        transition: all 0.3s;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(31, 38, 135, 0.05);
        border-color: #cbd5e1;
    }

    .step-number {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #4f46e5;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 20px;
    }
</style>

<div class="container affiliate-section my-5">
    <!-- Hero Header -->
    <div class="hero-banner text-center mb-5">
        <h1 class="display-4 font-weight-bold mb-3">Partner with LOOKSMEN</h1>
        <p class="lead mb-4 opacity-75" style="max-width: 650px; margin: 0 auto;">
            Recommend premium mens clothing products to your community, drive orders, and earn high-tier referral commission.
        </p>

        <div class="mt-4">
            @auth
                @php
                    $affiliate = Auth::user()->affiliate_user;
                @endphp
                @if ($affiliate)
                    @if ($affiliate->status == 1)
                        <a href="{{ route('affiliate.user.index') }}" class="btn btn-affiliate-primary btn-lg">
                            <i class="fa-solid fa-gauge mr-2"></i>Go to Affiliate Dashboard
                        </a>
                    @else
                        <div class="alert alert-info d-inline-block border-0 px-4 py-3 rounded-lg" style="background: rgba(255,255,255,0.1); color: white;">
                            <i class="fa-solid fa-clock-rotate-left mr-2"></i>Your affiliate request is currently **Pending Verification**. We will review it shortly.
                        </div>
                    @endif
                @else
                    <a href="{{ route('affiliate.apply') }}" class="btn btn-affiliate-primary btn-lg">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Apply for Affiliate Program
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-affiliate-primary btn-lg">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i>Log In to Apply
                </a>
            @endauth
        </div>
    </div>

    <!-- Marketing Stats Grid -->
    <div class="row mb-5 text-center">
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="feature-card">
                <div class="step-number mx-auto"><i class="fa-solid fa-wallet"></i></div>
                <h4 class="font-weight-bold mb-2">High Commissions</h4>
                <p class="text-muted mb-0 small">
                    Earn percentages or fixed rates for registration purchases, product sharing, and category referrals.
                </p>
            </div>
        </div>

        <div class="col-md-4 mb-4 mb-md-0">
            <div class="feature-card">
                <div class="step-number mx-auto"><i class="fa-solid fa-chart-line"></i></div>
                <h4 class="font-weight-bold mb-2">30-Day Tracking</h4>
                <p class="text-muted mb-0 small">
                    Our cookies remember your referrals for a full month, ensuring you get paid even if customer orders later.
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-card">
                <div class="step-number mx-auto"><i class="fa-solid fa-piggy-bank"></i></div>
                <h4 class="font-weight-bold mb-2">Quick Payouts</h4>
                <p class="text-muted mb-0 small">
                    Submit withdraw requests directly to your Bank or PayPal, handled rapidly by our finance staff.
                </p>
            </div>
        </div>
    </div>

    <!-- How it works -->
    <div class="bg-white rounded-lg p-5 border mb-5">
        <h2 class="text-center font-weight-bold mb-5">How Affiliate Partnership Works</h2>
        <div class="row">
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <h5 class="font-weight-bold text-primary mb-2">1. Apply & Verify</h5>
                <p class="text-muted small">Submit your application form. Our admin team validates and approves your partner status instantly.</p>
            </div>
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <h5 class="font-weight-bold text-primary mb-2">2. Share & Track</h5>
                <p class="text-muted small">Generate unique referral links on your partner dashboard and share them on social media channels.</p>
            </div>
            <div class="col-md-4 text-center">
                <h5 class="font-weight-bold text-primary mb-2">3. Earn Commissions</h5>
                <p class="text-muted small">Collect commission balances instantly when your referrals place orders and packages get delivered.</p>
            </div>
        </div>
    </div>
</div>
@endsection

