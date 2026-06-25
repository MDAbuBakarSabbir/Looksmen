@extends('layouts.frontEnd.master')

@section('title')
    AFFILIATE PARTNER DASHBOARD
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

    .dash-metric-card {
        background: var(--dash-surface);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 20px;
    }

    .dash-metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .metric-bg-1 { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
    .metric-bg-2 { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .metric-bg-3 { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .metric-bg-4 { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

    .metric-info h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dash-text);
    }

    .metric-info p {
        margin: 0;
        color: var(--dash-muted);
        font-size: 0.85rem;
    }

    .referral-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 20px;
    }

    .btn-generate {
        background: var(--dash-primary);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-generate:hover {
        opacity: 0.9;
        color: white;
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
                    <a href="{{ route('affiliate.user.index') }}" class="tab-link active">Dashboard</a>
                    <a href="{{ route('affiliate.user.payment_history') }}" class="tab-link">Payments</a>
                    <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="tab-link">Withdraw requests</a>
                    <a href="{{ route('affiliate.user.payment_settings') }}" class="tab-link">Payment Settings</a>
                </div>

                <!-- Stats Widgets -->
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-1"><i class="fa-solid fa-mouse-pointer"></i></div>
                            <div class="metric-info">
                                <h3>{{ $affliate_stats->count_click ?? 0 }}</h3>
                                <p>Referral Clicks</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-2"><i class="fa-solid fa-cart-shopping"></i></div>
                            <div class="metric-info">
                                <h3>{{ $affliate_stats->count_item ?? 0 }}</h3>
                                <p>Items Ordered</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-3"><i class="fa-solid fa-truck-fast"></i></div>
                            <div class="metric-info">
                                <h3>{{ $affliate_stats->count_delivered ?? 0 }}</h3>
                                <p>Delivered</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-4"><i class="fa-solid fa-circle-xmark"></i></div>
                            <div class="metric-info">
                                <h3>{{ $affliate_stats->count_cancel ?? 0 }}</h3>
                                <p>Canceled</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balance Details Card -->
                <div class="dash-card">
                    <div class="dash-card-header d-flex justify-content-between align-items-center">
                        <h5>Affiliate Balance</h5>
                        <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 14px;">
                            Balance: {{ single_price(Auth::user()->affiliate_user->balance ?? 0) }}
                        </span>
                    </div>
                </div>

                <!-- Referral Code Generator Card -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5>Referral Link Generator</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="referral-box mb-4">
                            <div class="font-weight-bold text-dark mb-1">Your Default Referral Link:</div>
                            <div class="input-group">
                                <input type="text" id="default-ref-link" class="form-control" value="{{ route('home') }}?ref={{ Auth::user()->referral_code }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" onclick="copyToClipboard('default-ref-link')"><i class="fa-solid fa-copy mr-1"></i>Copy</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-muted small">Generate Link for Specific Product</label>
                            <div class="row">
                                <div class="col-md-9 mb-2 mb-md-0">
                                    <input type="text" id="product-url-input" class="form-control" placeholder="Paste product URL here (e.g. {{ route('home') }}/product/mens-shirt)">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn-generate w-100" onclick="generateProductLink()"><i class="fa-solid fa-gears mr-1"></i>Generate</button>
                                </div>
                            </div>
                        </div>

                        <div id="generated-link-box" class="mt-4 p-3 bg-light rounded" style="display: none;">
                            <div class="font-weight-bold text-success mb-1">Generated Referral Link:</div>
                            <div class="input-group">
                                <input type="text" id="generated-ref-link" class="form-control" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-success" onclick="copyToClipboard('generated-ref-link')"><i class="fa-solid fa-copy mr-1"></i>Copy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Affiliate Logs -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5>Recent Affiliate Referral Logs</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Order ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($affiliate_logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('d M, Y h:i A') }}</td>
                                            <td class="font-weight-bold text-success">{{ single_price($log->amount) }}</td>
                                            <td>
                                                <span class="badge badge-light border">
                                                    {{ str_replace('_', ' ', $log->affiliate_type) }}
                                                </span>
                                            </td>
                                            <td class="font-weight-bold">
                                                {{ $log->order ? $log->order->invoice_id : 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="fa-solid fa-inbox fa-2x mb-2 opacity-50"></i><br>
                                                No referral activities logged yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($affiliate_logs->hasPages())
                            <div class="px-4 py-3 d-flex justify-content-center">
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
        
        // Show Swal alert
        Swal.fire({
            icon: 'success',
            title: 'Copied successfully!',
            showConfirmButton: false,
            timer: 1500,
            toast: true,
            position: 'top-end'
        });
    }

    function generateProductLink() {
        var urlInput = document.getElementById("product-url-input").value.trim();
        if (urlInput === "") {
            Swal.fire({
                icon: 'error',
                title: 'Please enter a valid URL',
                showConfirmButton: true
            });
            return;
        }

        var refCode = "{{ Auth::user()->referral_code }}";
        var separator = urlInput.indexOf('?') !== -1 ? '&' : '?';
        var generatedUrl = urlInput + separator + 'ref=' + refCode;

        document.getElementById("generated-ref-link").value = generatedUrl;
        document.getElementById("generated-link-box").style.display = "block";
    }
</script>
@endsection
