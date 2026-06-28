@extends('layouts.Backend.master')
@section('title') PAYMENT API @endsection
@section('content')

<style>
    :root {
        --primary:  #6366f1;
        --primary2: #8b5cf6;
        --green:    #10b981;
        --red:      #f43f5e;
        --amber:    #f59e0b;
        --border:   rgba(229,231,235,0.8);
        --shadow:   0 4px 20px -4px rgba(0,0,0,0.06);
        --radius:   14px;
    }

    /* ===== Hero ===== */
    .payment-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 40%, #334155 100%);
        border-radius: var(--radius);
        padding: 26px 32px;
        margin-bottom: 26px;
        position: relative; overflow: hidden;
        display: flex; align-items: center; justify-content: space-between;
    }
    .payment-hero::after {
        content: '💳';
        position: absolute; right: 32px; bottom: -10px;
        font-size: 90px; opacity: 0.08;
        pointer-events: none;
    }
    .payment-hero h1 { color:#fff; font-size:20px; font-weight:800; margin:0 0 5px; }
    .payment-hero p  { color:rgba(255,255,255,0.65); font-size:12px; margin:0; }

    /* ===== Selection Grid ===== */
    .payment-selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }
    .payment-select-card {
        background: #fff;
        border: 2px solid var(--border);
        border-radius: 14px;
        padding: 16px 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
        position: relative;
        overflow: hidden;
    }
    .payment-select-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(99,102,241,0.15);
    }
    .payment-select-card.active {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(99,102,241,0.04), rgba(139,92,246,0.06));
        box-shadow: 0 4px 16px rgba(99,102,241,0.2);
    }
    .payment-logo-wrap {
        height: 38px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 10px;
    }
    .payment-logo-wrap img { max-height: 36px; max-width: 100%; object-fit: contain; }
    .payment-select-name {
        font-size: 12px; font-weight: 700; color: #374151;
        margin: 0;
    }

    /* ===== Config Panels ===== */
    .payment-panel { display: none; }
    .payment-panel.active { display: block; animation: fadeSlide .3s ease; }
    @keyframes fadeSlide { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }

    /* ===== Config Card ===== */
    .config-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .config-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(to right, #f9fafb, #fff);
        display: flex; align-items: center; justify-content: space-between;
    }
    .config-card-title {
        display: flex; align-items: center; gap: 12px;
    }
    .payment-logo-badge {
        height: 36px; padding: 4px 10px;
        background: #fff; border: 1px solid var(--border);
        border-radius: 10px; display: flex; align-items: center;
    }
    .payment-logo-badge img { height: 26px; object-fit: contain; }
    .config-card-header h4 { font-size:15px; font-weight:800; color:#111827; margin:0; }
    .config-card-header p  { font-size:11px; color:#9ca3af; margin:0; }
    .config-card-body { padding: 22px 24px; }

    /* Toggle switch */
    .payment-toggle-wrap { display:flex; align-items:center; gap:10px; }
    .switch { position:relative; display:inline-block; width:42px; height:24px; margin-bottom: 0; }
    .switch input { opacity:0; width:0; height:0; }
    .slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:#d1d5db; transition:.3s; border-radius:34px; }
    .slider:before { position:absolute; content:""; height:18px; width:18px; left:3px; bottom:3px; background:white; transition:.3s; border-radius:50%; }
    input:checked+.slider { background: var(--green); }
    input:checked+.slider:before { transform:translateX(18px); }

    /* Form fields */
    .field-label {
        font-size:12px; font-weight:700; color:#374151;
        text-transform:uppercase; letter-spacing:.5px;
        margin-bottom:6px; display:block;
    }
    .field-input {
        background:#f9fafb !important; border:1.5px solid #e5e7eb !important;
        border-radius:10px !important; padding:10px 14px !important;
        font-size:13px !important; font-weight:500; color:#111827;
        transition:all .2s; width:100%;
    }
    .field-input:focus {
        background:#fff !important; border-color:var(--primary) !important;
        box-shadow:0 0 0 3px rgba(99,102,241,.12) !important; outline:none;
    }
    .field-input.secret-field { font-family:monospace; letter-spacing:1px; }
    .field-group { margin-bottom:16px; }
    .input-icon-wrap { position:relative; }
    .input-eye {
        position:absolute; right:12px; top:50%; transform:translateY(-50%);
        color:#9ca3af; font-size:18px; cursor:pointer; z-index:10;
    }
    .input-eye:hover { color:var(--primary); }

    .field-divider { height:1px; background:var(--border); margin:18px 0; }

    /* Save button */
    .btn-payment-save {
        background:linear-gradient(135deg,var(--primary),var(--primary2)) !important;
        border:none !important; border-radius:10px !important;
        padding:10px 24px !important; font-size:13px !important; font-weight:700;
        color:#fff !important;
        box-shadow:0 4px 14px rgba(99,102,241,.3) !important;
        transition:all .2s; display:inline-flex; align-items:center; gap:7px;
    }
    .btn-payment-save:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(99,102,241,.4) !important; }
</style>

{{-- ===== HERO ===== --}}
<div class="payment-hero">
    <div>
        <h1>Payment API Configuration</h1>
        <p>Manage integrations for bKash, Nagad, Rocket & SSLCommerz</p>
    </div>
</div>

{{-- ===== SELECTION GRID ===== --}}
<div class="payment-selection-grid">
    <div class="payment-select-card active" id="tab-bkash" onclick="switchPayment('bkash')">
        <div class="payment-logo-wrap">
            <img src="{{ asset('adminDash/assets/img/layouts/bkash.png') }}" alt="bKash">
        </div>
        <p class="payment-select-name">bKash</p>
    </div>
    <div class="payment-select-card" id="tab-nagad" onclick="switchPayment('nagad')">
        <div class="payment-logo-wrap">
            <img src="{{ asset('adminDash/assets/img/layouts/nagad.png') }}" alt="Nagad">
        </div>
        <p class="payment-select-name">Nagad</p>
    </div>
    <div class="payment-select-card" id="tab-rocket" onclick="switchPayment('rocket')">
        <div class="payment-logo-wrap" style="transform: scale(1.3);">
            <img src="{{ asset('adminDash/assets/img/layouts/rocket.png') }}" alt="Rocket">
        </div>
        <p class="payment-select-name">Rocket</p>
    </div>
    <div class="payment-select-card" id="tab-sslcommerz" onclick="switchPayment('sslcommerz')">
        <div class="payment-logo-wrap">
            <img src="{{ asset('adminDash/assets/img/layouts/sslcommerz.png') }}" alt="SSLCommerz">
        </div>
        <p class="payment-select-name">SSLCommerz</p>
    </div>
</div>

{{-- ===== bKash PANEL ===== --}}
<div class="payment-panel active" id="panel-bkash">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="payment-logo-badge">
                            <img src="{{ asset('adminDash/assets/img/layouts/bkash.png') }}" alt="bKash">
                        </div>
                        <div>
                            <h4>bKash Payment Gateway</h4>
                            <p>bkash.com</p>
                        </div>
                    </div>
                    <div class="payment-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-id="">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="config-card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="field-group">
                            <label class="field-label">API Key <span style="color:#f43f5e">*</span></label>
                            <input type="text" class="field-input secret-field" value="" name="" placeholder="Enter bKash API Key">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Secret Key <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" value="" name="" placeholder="Enter bKash Secret Key">
                                <i class="las la-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="url" class="field-input" value="" name="" placeholder="https://checkout.bkash.com/api">
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-payment-save">
                            <i class="las la-save fs-16"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== Nagad PANEL ===== --}}
<div class="payment-panel" id="panel-nagad">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="payment-logo-badge">
                            <img src="{{ asset('adminDash/assets/img/layouts/nagad.png') }}" alt="Nagad">
                        </div>
                        <div>
                            <h4>Nagad Payment Gateway</h4>
                            <p>nagad.com.bd</p>
                        </div>
                    </div>
                    <div class="payment-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-id="">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="config-card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="field-group">
                            <label class="field-label">API Key <span style="color:#f43f5e">*</span></label>
                            <input type="text" class="field-input secret-field" value="" name="" placeholder="Enter Nagad API Key">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Secret Key <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" value="" name="" placeholder="Enter Nagad Secret Key">
                                <i class="las la-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="url" class="field-input" value="" name="" placeholder="https://api.nagad.com.bd">
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-payment-save">
                            <i class="las la-save fs-16"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== Rocket PANEL ===== --}}
<div class="payment-panel" id="panel-rocket">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="payment-logo-badge" style="transform: scale(1.3); transform-origin: left center;">
                            <img src="{{ asset('adminDash/assets/img/layouts/rocket.png') }}" alt="Rocket">
                        </div>
                        <div>
                            <h4>Rocket Payment Gateway</h4>
                            <p>dutchbanglabank.com/rocket</p>
                        </div>
                    </div>
                    <div class="payment-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-id="">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="config-card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="field-group">
                            <label class="field-label">API Key <span style="color:#f43f5e">*</span></label>
                            <input type="text" class="field-input secret-field" value="" name="" placeholder="Enter Rocket API Key">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Secret Key <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" value="" name="" placeholder="Enter Rocket Secret Key">
                                <i class="las la-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="url" class="field-input" value="" name="" placeholder="https://api.rocket.com.bd">
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-payment-save">
                            <i class="las la-save fs-16"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== SSLCommerz PANEL ===== --}}
<div class="payment-panel" id="panel-sslcommerz">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="payment-logo-badge">
                            <img src="{{ asset('adminDash/assets/img/layouts/sslcommerz.png') }}" alt="SSLCommerz">
                        </div>
                        <div>
                            <h4>SSLCommerz</h4>
                            <p>sslcommerz.com</p>
                        </div>
                    </div>
                    <div class="payment-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-id="">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="config-card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="field-group">
                            <label class="field-label">Store ID <span style="color:#f43f5e">*</span></label>
                            <input type="text" class="field-input secret-field" value="" name="" placeholder="Enter Store ID">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Store Password <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" value="" name="" placeholder="Enter Store Password">
                                <i class="las la-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="url" class="field-input" value="" name="" placeholder="https://securepay.sslcommerz.com">
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-payment-save">
                            <i class="las la-save fs-16"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
function switchPayment(name) {
    document.querySelectorAll('.payment-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.payment-select-card').forEach(c => c.classList.remove('active'));
    document.getElementById('panel-' + name).classList.add('active');
    document.getElementById('tab-'   + name).classList.add('active');
}

function toggleField(icon) {
    const input = icon.previousElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('la-eye', 'la-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('la-eye-slash', 'la-eye');
    }
}
</script>
@endsection
