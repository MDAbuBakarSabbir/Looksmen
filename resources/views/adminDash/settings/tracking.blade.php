@extends('layouts.Backend.master')
@section('title', 'Analytics, GTM & Meta Pixel Tracking Settings')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><i class="fa-solid fa-chart-line text-primary mr-2"></i> Analytics & Tracking Settings</h4>
            <p class="mb-0">Manage Google Tag Manager (GTM DataLayer) and Server-Side Meta Conversions API (CAPI)</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('websettings.index') }}">Settings</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Tracking Settings</a></li>
        </ol>
    </div>
</div>

<div class="container-fluid px-0">
    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <style>
        .settings-card {
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px -5px rgba(0,0,0,0.05);
            background: #ffffff;
            margin-bottom: 25px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .settings-card .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 24px;
        }
        .settings-card .card-body {
            padding: 24px;
        }
        .badge-pulse-live {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-pulse-live::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3);
            animation: pulse-dot 1.5s infinite;
        }
        @keyframes pulse-dot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .tracking-guide-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .event-badge {
            background: #e2e8f0;
            color: #334155;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11.5px;
            font-family: monospace;
        }
    </style>

    <div class="row">
        {{-- ==================== Google Tag Manager (Browser-Side Engine) ==================== --}}
        <div class="col-lg-6">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-code text-primary mr-2" style="font-size: 18px;"></i>
                        <h4 class="mb-0">Google Tag Manager (GTM)</h4>
                    </div>
                    <span class="badge-pulse-live">Browser-Side Tracking</span>
                </div>
                <div class="card-body">
                    <div class="tracking-guide-box">
                        <h6 class="font-weight-bold text-success mb-1">
                            <i class="fa-solid fa-layer-group mr-1"></i> DataLayer Event Architecture
                        </h6>
                        <p class="text-muted mb-0" style="font-size: 12.5px; line-height: 1.5;">
                            All frontend user actions automatically push standard GA4/GTM DataLayer events:
                            <span class="event-badge">view_item</span>, <span class="event-badge">add_to_cart</span>, <span class="event-badge">begin_checkout</span>, and <span class="event-badge">purchase</span>.
                        </p>
                    </div>

                    <form action="{{ route('websettings.webGtag') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Google Tag Manager Container ID</label>
                            <input type="text" class="form-control" name="gtagid" value="{{ $webConfig['gtagid']['value'] ?? ($webConfig['gtagid'] ?? '') }}" placeholder="e.g. GTM-XXXXXXX" required>
                            <small class="text-muted">Enter your GTM Container ID. The GTM script and noscript will automatically be injected across all pages.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Google Domain Verification</label>
                            <textarea class="form-control" name="gdomainverify" rows="2" placeholder="Paste verification meta code or token">{{ $webConfig['gdomainverify']['value'] ?? ($webConfig['gdomainverify'] ?? '') }}</textarea>
                            <small class="text-muted">Google Search Console verification meta tag (e.g. <code>&lt;meta name="google-site-verification" ...&gt;</code>).</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa-solid fa-floppy-disk mr-1"></i> Save Google Tag Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ==================== Meta Conversions API (Server-Side Engine) ==================== --}}
        <div class="col-lg-6">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-brands fa-facebook text-primary mr-2" style="font-size: 20px;"></i>
                        <h4 class="mb-0">Meta Conversions API (CAPI)</h4>
                    </div>
                    <span class="badge-pulse-live">Server-Side Tracking</span>
                </div>
                <div class="card-body">
                    <div class="tracking-guide-box" style="background: #eff6ff; border-color: #bfdbfe;">
                        <h6 class="font-weight-bold text-primary mb-1">
                            <i class="fa-solid fa-server mr-1"></i> Server-to-Server CAPI + 100% Deduplication
                        </h6>
                        <p class="text-muted mb-0" style="font-size: 12.5px; line-height: 1.5;">
                            When an order is placed, Laravel securely sends customer profile & item data directly to Meta Graph API v19.0 with matching <span class="event-badge">purchase_{order_id}</span> event ID for 100% Deduplication.
                        </p>
                    </div>

                    <form action="{{ route('websettings.webFbpixel') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Meta (Facebook) Pixel ID</label>
                            <input type="text" class="form-control" name="fb_pixel_id" value="{{ $webConfig['fb_pixel_id']['value'] ?? ($webConfig['fb_pixel_id'] ?? '1814018549762511') }}" placeholder="e.g. 1814018549762511" required>
                            <small class="text-muted">Enter your numeric Meta Pixel ID from Meta Events Manager.</small>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label font-weight-bold mb-0">Meta CAPI Access Token</label>
                                <div class="custom-control custom-switch">
                                    @php
                                        $capiStatusVal = $webConfig['fb_capi_status']['value'] ?? ($webConfig['fb_capi_status'] ?? '1');
                                    @endphp
                                    <input type="checkbox" class="custom-control-input" id="fb_capi_status" name="fb_capi_status" value="1" {{ $capiStatusVal == '1' ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-success" for="fb_capi_status">Enable CAPI</label>
                                </div>
                            </div>
                            <textarea class="form-control" name="fb_capi_access_token" rows="3" placeholder="Paste your Meta System User or Conversions API Access Token">{{ $webConfig['fb_capi_access_token']['value'] ?? ($webConfig['fb_capi_access_token'] ?? '') }}</textarea>
                            <small class="text-muted">Generated from Meta Events Manager > Settings > Conversions API > "Generate access token".</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Meta CAPI Test Event Code <span class="badge badge-secondary text-white">Optional</span></label>
                            <input type="text" class="form-control" name="fb_capi_test_code" value="{{ $webConfig['fb_capi_test_code']['value'] ?? ($webConfig['fb_capi_test_code'] ?? '') }}" placeholder="e.g. TEST12345">
                            <small class="text-muted">Enter code from Meta Events Manager "Test Events" tab to test server events in real-time. Clear when live.</small>
                        </div>

                        <hr class="my-3">

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Facebook Domain Verification</label>
                            <textarea class="form-control" name="fbdomainverify" rows="1" placeholder="Paste verification meta code or token">{{ $webConfig['fbdomainverify']['value'] ?? ($webConfig['fbdomainverify'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Facebook Page Plugin Iframe <small class="text-muted">(Optional)</small></label>
                            <textarea class="form-control" name="fbiframe" rows="1" placeholder="Paste page plugin iframe code">{{ $webConfig['fbiframe']['value'] ?? ($webConfig['fbiframe'] ?? '') }}</textarea>
                        </div>

                        @if (isset($featuresConfig['facebook_api']) && $featuresConfig['facebook_api'] == '1')
                            <div class="mb-3">
                                <label class="form-label">Facebook Chat Plugin Code</label>
                                <textarea class="form-control" name="fbchatplugin" rows="2" placeholder="Paste Chat Plugin code">{{ $webConfig['fbchatplugin']['value'] ?? ($webConfig['fbchatplugin'] ?? '') }}</textarea>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa-solid fa-floppy-disk mr-1"></i> Save Meta Pixel & CAPI Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== Setup Architecture Reference Table ==================== --}}
    <div class="row">
        <div class="col-12">
            <div class="settings-card card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa-solid fa-diagram-project text-info mr-2"></i> Active Tracking & Deduplication Workflow</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>User Step / Action</th>
                                    <th>Browser Event (GTM DataLayer)</th>
                                    <th>Server Event (Meta CAPI)</th>
                                    <th>Deduplication Key (`event_id`)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>1. Product Page View</strong></td>
                                    <td><code>view_item</code> (Product ID, Name, Price, Category)</td>
                                    <td>—</td>
                                    <td><code>view_item_{id}_{timestamp}</code></td>
                                    <td><span class="badge badge-success text-white">Active</span></td>
                                </tr>
                                <tr>
                                    <td><strong>2. Add To Cart</strong></td>
                                    <td><code>add_to_cart</code> (Items, Value, Currency)</td>
                                    <td>—</td>
                                    <td><code>add_to_cart_{id}_{timestamp}</code></td>
                                    <td><span class="badge badge-success text-white">Active</span></td>
                                </tr>
                                <tr>
                                    <td><strong>3. Initiate Checkout</strong></td>
                                    <td><code>begin_checkout</code> (Cart Items, Subtotal, Customer)</td>
                                    <td>—</td>
                                    <td><code>checkout_{timestamp}_{random}</code></td>
                                    <td><span class="badge badge-success text-white">Active</span></td>
                                </tr>
                                <tr>
                                    <td><strong>4. Order Purchase</strong></td>
                                    <td><code>purchase</code> (Transaction ID, Grand Total, Customer, Items)</td>
                                    <td><code>Purchase</code> via Graph API (Hashed Phone, Email, IP, Agent, Items)</td>
                                    <td><strong class="text-primary"><code>purchase_{order_id}</code></strong> (Exact Match)</td>
                                    <td><span class="badge badge-success text-white">100% Deduplicated</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
