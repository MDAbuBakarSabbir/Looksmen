@extends('layouts.Frontend.master')

@section('title')
    AFFILIATE PARTNER DASHBOARD
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

    .dash-card:hover {
        box-shadow: 0 15px 50px rgba(0,0,0,0.06);
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

    .dash-metric-card {
        background: var(--dash-surface);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.02);
        border: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .dash-metric-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 35px var(--dash-primary-glow);
        border-color: rgba(99, 102, 241, 0.2);
    }

    .metric-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-right: 18px;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }

    .metric-bg-1 { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 8px 20px rgba(99,102,241,0.3); }
    .metric-bg-2 { background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 8px 20px rgba(16,185,129,0.3); }
    .metric-bg-3 { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 8px 20px rgba(245,158,11,0.3); }
    .metric-bg-4 { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); box-shadow: 0 8px 20px rgba(239,68,68,0.3); }

    .metric-info h3 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--dash-text);
        line-height: 1.1;
    }

    .metric-info p {
        margin: 4px 0 0 0;
        color: var(--dash-muted);
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Command Bar Link Generator */
    .cmd-bar-wrapper {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 6px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .cmd-bar-wrapper:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
    }

    .cmd-bar-input {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        font-family: monospace;
        font-size: 14px;
        color: #334155;
        padding: 10px 15px;
        flex-grow: 1;
    }

    .cmd-bar-btn {
        background: #1e293b;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cmd-bar-btn:hover {
        background: #0f172a;
        transform: scale(1.02);
    }

    .cmd-bar-btn-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    }
    
    .cmd-bar-btn-primary:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
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
    }
    
    .badge-soft-primary { background: rgba(99,102,241,0.1); color: #4f46e5; }
    .badge-soft-success { background: rgba(16,185,129,0.1); color: #059669; }

    /* Balance Tag */
    .balance-tag {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(16,185,129,0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
                    <a href="{{ route('affiliate.user.index') }}" class="dash-tab-link active"><i class="fa-solid fa-chart-pie mr-2"></i>Overview</a>
                    <a href="{{ route('affiliate.user.payment_history') }}" class="dash-tab-link"><i class="fa-solid fa-wallet mr-2"></i>Payouts</a>
                    <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="dash-tab-link"><i class="fa-solid fa-money-bill-transfer mr-2"></i>Withdraw</a>
                    <a href="{{ route('affiliate.user.payment_settings') }}" class="dash-tab-link"><i class="fa-solid fa-gear mr-2"></i>Settings</a>
                </div>

                <!-- Stats Widgets -->
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-1"><i class="fa-solid fa-arrow-pointer"></i></div>
                            <div class="metric-info">
                                <h3>{{ $affliate_stats->count_click ?? 0 }}</h3>
                                <p>Clicks</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-2"><i class="fa-solid fa-bag-shopping"></i></div>
                            <div class="metric-info">
                                <h3>{{ $affliate_stats->count_item ?? 0 }}</h3>
                                <p>Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-3"><i class="fa-solid fa-box-open"></i></div>
                            <div class="metric-info">
                                <h3>{{ $affliate_stats->count_delivered ?? 0 }}</h3>
                                <p>Delivered</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-4"><i class="fa-solid fa-ban"></i></div>
                            <div class="metric-info">
                                <h3>{{ $affliate_stats->count_cancel ?? 0 }}</h3>
                                <p>Canceled</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balance Details Card -->
                <div class="dash-card">
                    <div class="dash-card-header d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                        <div>
                            <h5 class="mb-1">Available Balance</h5>
                            <span class="text-muted small">Funds available for withdrawal</span>
                        </div>
                        <div class="balance-tag">
                            <i class="fa-solid fa-coins"></i>
                            {{ single_price(Auth::user()->affiliate_user->balance ?? 0) }}
                        </div>
                    </div>
                </div>

                <!-- Referral Code Generator Card -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5 class="mb-1">Affiliate Toolkit</h5>
                        <span class="text-muted small">Generate and share your custom referral links</span>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="mb-5">
                            <label class="font-weight-bold text-dark mb-2">Standard Store Link</label>
                            <p class="text-muted small mb-3">Share this link to direct users to our homepage with your tracking tag attached.</p>
                            <div class="cmd-bar-wrapper">
                                <div class="pl-3 text-muted"><i class="fa-solid fa-link"></i></div>
                                <input type="text" id="default-ref-link" class="cmd-bar-input" value="{{ route('home') }}?ref={{ Auth::user()->referral_code }}" readonly>
                                <button class="cmd-bar-btn" onclick="copyToClipboard('default-ref-link')"><i class="fa-regular fa-copy"></i> Copy</button>
                            </div>
                        </div>

                        <div>
                            <label class="font-weight-bold text-dark mb-2">Deep Link Generator</label>
                            <p class="text-muted small mb-3">Paste any specific product URL below to create a targeted referral link.</p>
                            <div class="cmd-bar-wrapper mb-3">
                                <div class="pl-3 text-primary"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                                <input type="text" id="product-url-input" class="cmd-bar-input" placeholder="e.g. {{ route('home') }}/product/awesome-shirt">
                                <button class="cmd-bar-btn cmd-bar-btn-primary" onclick="generateProductLink()"><i class="fa-solid fa-bolt"></i> Generate</button>
                            </div>

                            <!-- Generated Output -->
                            <div id="generated-link-box" class="cmd-bar-wrapper" style="display: none; background: #f8fafc; border-color: #10b981;">
                                <div class="pl-3 text-success"><i class="fa-solid fa-check-circle"></i></div>
                                <input type="text" id="generated-ref-link" class="cmd-bar-input text-success" readonly>
                                <button class="cmd-bar-btn" style="background: #10b981;" onclick="copyToClipboard('generated-ref-link')"><i class="fa-regular fa-copy"></i> Copy</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Affiliate Logs -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5>Recent Activity Logs</h5>
                    </div>
                    <div class="card-body p-4 bg-light">
                        <div class="table-responsive">
                            <table class="table table-modern text-center w-100">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Commission</th>
                                        <th>Trigger Event</th>
                                        <th>Order Ref</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($affiliate_logs as $log)
                                        <tr>
                                            <td class="text-muted font-weight-bold">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                            <td class="font-weight-bold text-success" style="font-size: 1.1rem;">+{{ single_price($log->amount) }}</td>
                                            <td>
                                                <span class="badge-soft badge-soft-primary">
                                                    {{ ucwords(str_replace('_', ' ', $log->affiliate_type)) }}
                                                </span>
                                            </td>
                                            <td class="font-weight-bold text-dark">
                                                #{{ $log->order ? $log->order->invoice_id : 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted bg-white" style="border-radius: 12px;">
                                                <div class="mb-3 opacity-50"><i class="fa-solid fa-ghost fa-3x"></i></div>
                                                <h6 class="font-weight-bold text-dark mb-1">No Activity Yet</h6>
                                                <p class="small mb-0">Share your referral links to start earning commissions!</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($affiliate_logs->hasPages())
                            <div class="pt-4 d-flex justify-content-center">
                                {{ $affiliate_logs->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    function copyToClipboard(id) {
        var copyText = document.getElementById(id);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        Swal.fire({
            icon: 'success',
            title: 'Copied to clipboard!',
            showConfirmButton: false,
            timer: 1500,
            toast: true,
            position: 'top-end',
            background: '#ffffff',
            iconColor: '#10b981',
            customClass: {
                title: 'text-dark font-weight-bold'
            }
        });
    }

    function generateProductLink() {
        var urlInput = document.getElementById("product-url-input").value.trim();
        if (urlInput === "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please paste a valid product URL first.',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        var refCode = "{{ Auth::user()->referral_code }}";
        var separator = urlInput.indexOf('?') !== -1 ? '&' : '?';
        var generatedUrl = urlInput + separator + 'ref=' + refCode;

        document.getElementById("generated-ref-link").value = generatedUrl;
        
        // Add a nice slide-down effect
        var linkBox = document.getElementById("generated-link-box");
        linkBox.style.opacity = '0';
        linkBox.style.display = "flex";
        setTimeout(() => {
            linkBox.style.transition = 'opacity 0.4s ease';
            linkBox.style.opacity = '1';
        }, 50);
    }
</script>
@endsection
