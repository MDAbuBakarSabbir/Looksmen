@extends('layouts.Backend.master')
@section('title') COURIER API @endsection
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
    .courier-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4f46e5 100%);
        border-radius: var(--radius);
        padding: 26px 32px;
        margin-bottom: 26px;
        position: relative; overflow: hidden;
        display: flex; align-items: center; justify-content: space-between;
    }
    .courier-hero::after {
        content: 'ðŸšš';
        position: absolute; right: 32px; bottom: -10px;
        font-size: 90px; opacity: 0.08;
        pointer-events: none;
    }
    .courier-hero h1 { color:#fff; font-size:20px; font-weight:800; margin:0 0 5px; }
    .courier-hero p  { color:rgba(255,255,255,0.65); font-size:12px; margin:0; }
    .hero-badge {
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        color:#fff; font-size:10px; font-weight:700;
        padding:3px 10px; border-radius:20px; letter-spacing:.5px;
        backdrop-filter: blur(4px);
        display: inline-block; margin-top:10px;
    }

    /* Active courier display */
    .active-courier-badge {
        background: rgba(16,185,129,0.2);
        border: 1px solid rgba(16,185,129,0.4);
        color: #d1fae5;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 12px; font-weight: 700;
        display: flex; align-items: center; gap: 8px;
        z-index:1;
    }
    .active-pulse {
        width: 8px; height: 8px; border-radius: 50%;
        background: #10b981; flex-shrink:0;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.3);
        animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%,100%{box-shadow:0 0 0 3px rgba(16,185,129,.3)} 50%{box-shadow:0 0 0 6px rgba(16,185,129,.1)} }

    /* ===== Courier Selection Grid ===== */
    .courier-selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }
    .courier-select-card {
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
    .courier-select-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(99,102,241,0.15);
    }
    .courier-select-card.active {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(99,102,241,0.04), rgba(139,92,246,0.06));
        box-shadow: 0 4px 16px rgba(99,102,241,0.2);
    }
    .courier-select-card.enabled-courier::before {
        content: '';
        position: absolute; top: 8px; right: 8px;
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--green);
        box-shadow: 0 0 0 2px rgba(16,185,129,0.25);
    }
    .courier-logo-wrap {
        height: 38px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 10px;
    }
    .courier-logo-wrap img { max-height: 36px; max-width: 100%; object-fit: contain; }
    .courier-logo-placeholder {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; font-weight: 800; color: #fff;
        margin: 0 auto;
    }
    .courier-select-name {
        font-size: 12px; font-weight: 700; color: #374151;
        margin: 0;
    }
    .courier-select-status {
        font-size: 10px; font-weight: 600;
        margin-top: 4px;
    }
    .status-on  { color: var(--green); }
    .status-off { color: #9ca3af; }

    /* ===== Config Panels ===== */
    .courier-panel { display: none; }
    .courier-panel.active { display: block; animation: fadeSlide .3s ease; }
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
    .courier-logo-badge {
        height: 36px; padding: 4px 10px;
        background: #fff; border: 1px solid var(--border);
        border-radius: 10px; display: flex; align-items: center;
    }
    .courier-logo-badge img { height: 26px; object-fit: contain; }
    .config-card-header h4 { font-size:15px; font-weight:800; color:#111827; margin:0; }
    .config-card-header p  { font-size:11px; color:#9ca3af; margin:0; }
    .config-card-body { padding: 22px 24px; }

    /* Toggle switch */
    .courier-toggle-wrap { display:flex; align-items:center; gap:10px; }
    .courier-toggle-label { font-size:12px; font-weight:700; }
    .toggle-on  { color:var(--green); }
    .toggle-off { color:#9ca3af; }
    .switch { position:relative; display:inline-block; width:42px; height:24px; }
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
        color:#9ca3af; font-size:13px; cursor:pointer; z-index:10;
    }
    .input-eye:hover { color:var(--primary); }

    .field-hint { font-size:11px; color:#9ca3af; margin-top:4px; }
    .field-divider { height:1px; background:var(--border); margin:18px 0; }

    /* Save button */
    .btn-courier-save {
        background:linear-gradient(135deg,var(--primary),var(--primary2)) !important;
        border:none !important; border-radius:10px !important;
        padding:10px 24px !important; font-size:13px !important; font-weight:700;
        color:#fff !important;
        box-shadow:0 4px 14px rgba(99,102,241,.3) !important;
        transition:all .2s; display:inline-flex; align-items:center; gap:7px;
    }
    .btn-courier-save:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(99,102,241,.4) !important; }

    /* Info card */
    .info-card {
        background:#fff; border:1px solid var(--border);
        border-radius:var(--radius); box-shadow:var(--shadow);
        overflow:hidden;
    }
    .info-card-header {
        padding:14px 18px; border-bottom:1px solid var(--border);
        font-size:13px; font-weight:700; color:#111827;
        display:flex; align-items:center; gap:8px;
        background:linear-gradient(to right,#f9fafb,#fff);
    }
    .info-card-body { padding:16px 18px; }
    .info-step {
        display:flex; gap:10px; padding:10px 12px;
        border-left:3px solid; border-radius:0 8px 8px 0;
        margin-bottom:8px; background:#fff;
        box-shadow:0 1px 4px rgba(0,0,0,.04);
        transition:transform .2s;
    }
    .info-step:hover { transform:translateX(3px); }
    .step-num {
        width:22px; height:22px; border-radius:6px;
        display:flex; align-items:center; justify-content:center;
        font-size:10px; font-weight:800; color:#fff; flex-shrink:0;
    }
    .info-step strong { font-size:11.5px; font-weight:700; color:#111827; display:block; margin-bottom:1px; }
    .info-step p { font-size:11px; color:#6b7280; margin:0; line-height:1.5; }
    .info-visit-btn {
        display:flex; align-items:center; justify-content:center; gap:7px;
        margin-top:12px; padding:9px 14px;
        border-radius:10px; font-size:12px; font-weight:700;
        text-decoration:none; border:1.5px solid; transition:all .2s;
    }
    .info-visit-btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.1); }
    .info-tip {
        background:linear-gradient(135deg,#fffbeb,#fef3c7);
        border:1px solid #fde68a; border-radius:8px;
        padding:10px 12px; font-size:11px; color:#92400e;
        display:flex; gap:7px; margin-top:10px;
    }
</style>

{{-- ===== HERO ===== --}}
<div class="courier-hero">
    <div>
        <h1>Courier API Configuration</h1>
        <p>Manage delivery partner integrations â€” Steadfast, Pathao, RedX, Paperfly, CityFast & eCourier</p>
        <span class="hero-badge">âš¡ Only one courier can be active at a time</span>
    </div>
    <div class="active-courier-badge">
        <div class="active-pulse"></div>
        @php
            $activeCourier = collect($courierStatusConfig)->firstWhere('status', '1');
            $activeName = $activeCourier ? ucfirst(array_key_first(array_filter($courierStatusConfig, fn($v) => ($v['status'] ?? '0') == '1'))) : 'None';
        @endphp
        <span>Active: {{ $activeName }}</span>
    </div>
</div>

{{-- ===== COURIER SELECTION GRID ===== --}}
<div class="courier-selection-grid">

    {{-- Steadfast --}}
    <div class="courier-select-card {{ isset($courierStatusConfig['steadfast']) && $courierStatusConfig['steadfast']['status']=='1' ? 'enabled-courier' : '' }} active"
         id="tab-steadfast" onclick="switchCourier('steadfast')">
        <div class="courier-logo-wrap">
            <img src="{{ asset('adminDash/assets/img/layouts/steadfastLogo.svg') }}" alt="Steadfast">
        </div>
        <p class="courier-select-name">Steadfast</p>
        <p class="courier-select-status {{ isset($courierStatusConfig['steadfast']) && $courierStatusConfig['steadfast']['status']=='1' ? 'status-on' : 'status-off' }}">
            {{ isset($courierStatusConfig['steadfast']) && $courierStatusConfig['steadfast']['status']=='1' ? 'â— Active' : 'â—‹ Inactive' }}
        </p>
    </div>

    {{-- Pathao --}}
    <div class="courier-select-card {{ isset($courierStatusConfig['pathao']) && $courierStatusConfig['pathao']['status']=='1' ? 'enabled-courier' : '' }}"
         id="tab-pathao" onclick="switchCourier('pathao')">
        <div class="courier-logo-wrap">
            <img src="{{ asset('adminDash/assets/img/layouts/Pathao_Logo-.svg') }}" alt="Pathao">
        </div>
        <p class="courier-select-name">Pathao</p>
        <p class="courier-select-status {{ isset($courierStatusConfig['pathao']) && $courierStatusConfig['pathao']['status']=='1' ? 'status-on' : 'status-off' }}">
            {{ isset($courierStatusConfig['pathao']) && $courierStatusConfig['pathao']['status']=='1' ? 'â— Active' : 'â—‹ Inactive' }}
        </p>
    </div>

    {{-- RedX --}}
    <div class="courier-select-card {{ isset($courierStatusConfig['redx']) && $courierStatusConfig['redx']['status']=='1' ? 'enabled-courier' : '' }}"
         id="tab-redx" onclick="switchCourier('redx')">
        <div class="courier-logo-wrap">
            <img src="{{ asset('adminDash/assets/img/layouts/redxlogo.png') }}" alt="RedX">
        </div>
        <p class="courier-select-name">RedX</p>
        <p class="courier-select-status {{ isset($courierStatusConfig['redx']) && $courierStatusConfig['redx']['status']=='1' ? 'status-on' : 'status-off' }}">
            {{ isset($courierStatusConfig['redx']) && $courierStatusConfig['redx']['status']=='1' ? 'â— Active' : 'â—‹ Inactive' }}
        </p>
    </div>

    {{-- Paperfly --}}
    <div class="courier-select-card {{ isset($courierStatusConfig['paperfly']) && $courierStatusConfig['paperfly']['status']=='1' ? 'enabled-courier' : '' }}"
         id="tab-paperfly" onclick="switchCourier('paperfly')">
        <div class="courier-logo-wrap">
            <img src="{{ asset('adminDash/assets/img/layouts/Paperfly-Logo.svg') }}" alt="Paperfly">
        </div>
        <p class="courier-select-name">Paperfly</p>
        <p class="courier-select-status {{ isset($courierStatusConfig['paperfly']) && $courierStatusConfig['paperfly']['status']=='1' ? 'status-on' : 'status-off' }}">
            {{ isset($courierStatusConfig['paperfly']) && $courierStatusConfig['paperfly']['status']=='1' ? 'â— Active' : 'â—‹ Inactive' }}
        </p>
    </div>

    {{-- CityFast --}}
    <div class="courier-select-card {{ isset($courierStatusConfig['cityfast']) && $courierStatusConfig['cityfast']['status']=='1' ? 'enabled-courier' : '' }}"
         id="tab-cityfast" onclick="switchCourier('cityfast')">
        <div class="courier-logo-wrap">
            <img src="{{ asset('adminDash/assets/img/layouts/cityfastlogo.png') }}" alt="CityFast">
        </div>
        <p class="courier-select-name">CityFast</p>
        <p class="courier-select-status {{ isset($courierStatusConfig['cityfast']) && $courierStatusConfig['cityfast']['status']=='1' ? 'status-on' : 'status-off' }}">
            {{ isset($courierStatusConfig['cityfast']) && $courierStatusConfig['cityfast']['status']=='1' ? 'â— Active' : 'â—‹ Inactive' }}
        </p>
    </div>

    {{-- eCourier --}}
    <div class="courier-select-card {{ isset($courierStatusConfig['ecourier']) && $courierStatusConfig['ecourier']['status']=='1' ? 'enabled-courier' : '' }}"
         id="tab-ecourier" onclick="switchCourier('ecourier')">
        <div class="courier-logo-wrap">
            <img src="{{ asset('adminDash/assets/img/layouts/ecourierlogo.png') }}" alt="eCourier">
        </div>
        <p class="courier-select-name">eCourier</p>
        <p class="courier-select-status {{ isset($courierStatusConfig['ecourier']) && $courierStatusConfig['ecourier']['status']=='1' ? 'status-on' : 'status-off' }}">
            {{ isset($courierStatusConfig['ecourier']) && $courierStatusConfig['ecourier']['status']=='1' ? 'â— Active' : 'â—‹ Inactive' }}
        </p>
    </div>

</div>

{{-- ===================== STEADFAST PANEL ===================== --}}
<div class="courier-panel active" id="panel-steadfast">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="courier-logo-badge">
                            <img src="{{ asset('adminDash/assets/img/layouts/steadfastLogo.svg') }}" alt="Steadfast">
                        </div>
                        <div>
                            <h4>Steadfast Courier</h4>
                            <p>steadfast-courier.com</p>
                        </div>
                    </div>
                    <div class="courier-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="steadfast"
                                data-url="/api/update-courier-status"
                                id="switch-steadfast"
                                {{ isset($courierStatusConfig['steadfast']) && $courierStatusConfig['steadfast']['status']=='1' ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                        <span class="courier-toggle-label" id="label-steadfast">
                            {{ isset($courierStatusConfig['steadfast']) && $courierStatusConfig['steadfast']['status']=='1' ? '<span class="toggle-on">Active</span>' : '<span class="toggle-off">Inactive</span>' }}
                        </span>
                    </div>
                </div>
                <div class="config-card-body">
                    <form class="settingsUpdateForm">
                        @csrf
                        <input type="hidden" name="courier_name" value="steadfast">
                        <div class="field-group">
                            <label class="field-label">API Key <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" name="api_key"
                                    value="{{ $courierStatusConfig['steadfast']['api_key'] ?? '' }}"
                                    placeholder="Enter Steadfast API Key">
                                <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Secret Key <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" name="secret_key"
                                    value="{{ $courierStatusConfig['steadfast']['secret_key'] ?? '' }}"
                                    placeholder="Enter Steadfast Secret Key">
                                <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="text" class="field-input" name="base_url"
                                value="{{ $courierStatusConfig['steadfast']['base_url'] ?? '' }}"
                                placeholder="https://portal.steadfast.com.bd/public/api">
                            <p class="field-hint">Leave blank to use the default Steadfast API endpoint.</p>
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-courier-save">
                            <i class="fa-solid fa-floppy-disk"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <div class="info-card-header">ðŸ“– API Key à¦•à¦¿à¦­à¦¾à¦¬à§‡ à¦ªà¦¾à¦¬à§‡à¦¨?</div>
                <div class="info-card-body">
                    <div class="info-step" style="border-color:#10b981;">
                        <div class="step-num" style="background:#10b981;">à§§</div>
                        <div><strong>Steadfast à¦ªà§‹à¦°à§à¦Ÿà¦¾à¦²à§‡ à¦¯à¦¾à¦¨</strong><p>steadfast-courier.com à¦ à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ à¦–à§à¦²à§à¦¨ à¦“ à¦²à¦—à¦‡à¦¨ à¦•à¦°à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#3b82f6;">
                        <div class="step-num" style="background:#3b82f6;">à§¨</div>
                        <div><strong>API Settings</strong><p>Dashboard â†’ Settings â†’ API Integration à¦ à¦¯à¦¾à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#f59e0b;">
                        <div class="step-num" style="background:#f59e0b;">à§©</div>
                        <div><strong>Keys à¦•à¦ªà¦¿ à¦•à¦°à§à¦¨</strong><p>API Key à¦“ Secret Key à¦•à¦ªà¦¿ à¦•à¦°à§‡ à¦‰à¦ªà¦°à§‡à¦° à¦«à¦°à§à¦®à§‡ à¦¦à¦¿à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#f43f5e;">
                        <div class="step-num" style="background:#f43f5e;">à§ª</div>
                        <div><strong>à¦¶à§à¦§à§ à¦à¦•à¦Ÿà¦¿ Active</strong><p>à¦à¦•à¦¸à¦¾à¦¥à§‡ à¦à¦•à¦Ÿà¦¿à¦‡ courier API active à¦°à¦¾à¦–à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-tip"><span>ðŸ’¡</span><span>Keys à¦•à¦–à¦¨à§‹ publicly à¦¶à§‡à¦¯à¦¼à¦¾à¦° à¦•à¦°à¦¬à§‡à¦¨ à¦¨à¦¾à¥¤</span></div>
                    <a href="https://steadfast-courier.com" target="_blank" class="info-visit-btn" style="color:#4f46e5;border-color:#e0e7ff;background:#eff6ff;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Steadfast Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== PATHAO PANEL ===================== --}}
<div class="courier-panel" id="panel-pathao">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="courier-logo-badge">
                            <img src="{{ asset('adminDash/assets/img/layouts/Pathao_Logo-.svg') }}" alt="Pathao">
                        </div>
                        <div><h4>Pathao Courier</h4><p>pathao.com</p></div>
                    </div>
                    <div class="courier-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="pathao" data-url="/api/update-courier-status"
                                {{ isset($courierStatusConfig['pathao']) && $courierStatusConfig['pathao']['status']=='1' ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="config-card-body">
                    <form class="settingsUpdateForm">
                        @csrf
                        <input type="hidden" name="courier_name" value="pathao">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label">Client ID</label>
                                    <input type="text" class="field-input" name="api_key"
                                        value="{{ $courierStatusConfig['pathao']['api_key'] ?? '' }}"
                                        placeholder="Pathao Client ID">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label">Client Secret</label>
                                    <div class="input-icon-wrap">
                                        <input type="password" class="field-input secret-field" name="secret_key"
                                            value="{{ $courierStatusConfig['pathao']['secret_key'] ?? '' }}"
                                            placeholder="Pathao Client Secret">
                                        <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label">Username</label>
                                    <input type="text" class="field-input" name="username"
                                        value="{{ $courierStatusConfig['pathao']['username'] ?? '' }}"
                                        placeholder="Pathao Username / Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label">Password</label>
                                    <div class="input-icon-wrap">
                                        <input type="password" class="field-input secret-field" name="password"
                                            value="{{ $courierStatusConfig['pathao']['password'] ?? '' }}"
                                            placeholder="Pathao Password">
                                        <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="text" class="field-input" name="base_url"
                                value="{{ $courierStatusConfig['pathao']['base_url'] ?? '' }}"
                                placeholder="https://api-hermes.pathao.com">
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-courier-save">
                            <i class="fa-solid fa-floppy-disk"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <div class="info-card-header">ðŸ“– Pathao API à¦¸à§‡à¦Ÿà¦†à¦ª</div>
                <div class="info-card-body">
                    <div class="info-step" style="border-color:#e11d48;">
                        <div class="step-num" style="background:#e11d48;">à§§</div>
                        <div><strong>Pathao Merchant à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ</strong><p>courier.pathao.com à¦ merchant à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ à¦–à§à¦²à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#3b82f6;">
                        <div class="step-num" style="background:#3b82f6;">à§¨</div>
                        <div><strong>Developer API Access</strong><p>Support à¦ à¦¯à§‹à¦—à¦¾à¦¯à§‹à¦— à¦•à¦°à§à¦¨ à¦…à¦¥à¦¬à¦¾ Developer Dashboard à¦¥à§‡à¦•à§‡ credentials à¦¨à¦¿à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#f59e0b;">
                        <div class="step-num" style="background:#f59e0b;">à§©</div>
                        <div><strong>Client ID & Secret</strong><p>à¦ªà¦¾à¦“à¦¯à¦¼à¦¾ Client ID, Secret, Username, Password à¦¦à¦¿à¦¯à¦¼à§‡ à¦«à¦°à§à¦® à¦ªà§‚à¦°à¦£ à¦•à¦°à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-tip"><span>ðŸ’¡</span><span>Pathao Sandbox à¦¬à§à¦¯à¦¬à¦¹à¦¾à¦° à¦•à¦°à§à¦¨ test à¦à¦° à¦œà¦¨à§à¦¯à¥¤</span></div>
                    <a href="https://pathao.com" target="_blank" class="info-visit-btn" style="color:#e11d48;border-color:#fecdd3;background:#fff1f2;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Pathao Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== REDX PANEL ===================== --}}
<div class="courier-panel" id="panel-redx">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="courier-logo-badge">
                            <img src="{{ asset('adminDash/assets/img/layouts/redxlogo.png') }}" alt="RedX">
                        </div>
                        <div><h4>RedX Courier</h4><p>redx.com.bd</p></div>
                    </div>
                    <div class="courier-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="redx" data-url="/api/update-courier-status"
                                {{ isset($courierStatusConfig['redx']) && $courierStatusConfig['redx']['status']=='1' ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="config-card-body">
                    <form class="settingsUpdateForm">
                        @csrf
                        <input type="hidden" name="courier_name" value="redx">
                        <div class="field-group">
                            <label class="field-label">API Token <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" name="api_key"
                                    value="{{ $courierStatusConfig['redx']['api_key'] ?? '' }}"
                                    placeholder="Enter RedX API Token">
                                <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Secret Key</label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" name="secret_key"
                                    value="{{ $courierStatusConfig['redx']['secret_key'] ?? '' }}"
                                    placeholder="Enter RedX Secret Key">
                                <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="text" class="field-input" name="base_url"
                                value="{{ $courierStatusConfig['redx']['base_url'] ?? '' }}"
                                placeholder="https://openapi.redx.com.bd">
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-courier-save">
                            <i class="fa-solid fa-floppy-disk"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <div class="info-card-header">ðŸ“– RedX API à¦¸à§‡à¦Ÿà¦†à¦ª</div>
                <div class="info-card-body">
                    <div class="info-step" style="border-color:#ef4444;">
                        <div class="step-num" style="background:#ef4444;">à§§</div>
                        <div><strong>RedX Merchant à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ</strong><p>redx.com.bd à¦¤à§‡ merchant account à¦–à§à¦²à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#3b82f6;">
                        <div class="step-num" style="background:#3b82f6;">à§¨</div>
                        <div><strong>API Token à¦ªà¦¾à¦¨</strong><p>Dashboard â†’ Settings â†’ API Access Token Generate à¦•à¦°à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#10b981;">
                        <div class="step-num" style="background:#10b981;">à§©</div>
                        <div><strong>Token à¦¦à¦¿à¦¯à¦¼à§‡ Connect à¦•à¦°à§à¦¨</strong><p>API Token à¦‰à¦ªà¦°à§‡à¦° à¦«à¦°à§à¦®à§‡ à¦¦à¦¿à¦¯à¦¼à§‡ Save à¦•à¦°à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-tip"><span>ðŸ’¡</span><span>RedX Production à¦“ Sandbox à¦†à¦²à¦¾à¦¦à¦¾ token à¦¬à§à¦¯à¦¬à¦¹à¦¾à¦° à¦•à¦°à§‡à¥¤</span></div>
                    <a href="https://redx.com.bd" target="_blank" class="info-visit-btn" style="color:#ef4444;border-color:#fecaca;background:#fff5f5;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> RedX Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== PAPERFLY PANEL ===================== --}}
<div class="courier-panel" id="panel-paperfly">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="courier-logo-badge">
                            <img src="{{ asset('adminDash/assets/img/layouts/Paperfly-Logo.svg') }}" alt="Paperfly">
                        </div>
                        <div><h4>Paperfly Courier</h4><p>paperfly.com.bd</p></div>
                    </div>
                    <div class="courier-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="paperfly" data-url="/api/update-courier-status"
                                {{ isset($courierStatusConfig['paperfly']) && $courierStatusConfig['paperfly']['status']=='1' ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="config-card-body">
                    <form class="settingsUpdateForm">
                        @csrf
                        <input type="hidden" name="courier_name" value="paperfly">
                        <div class="field-group">
                            <label class="field-label">Client ID <span style="color:#f43f5e">*</span></label>
                            <input type="text" class="field-input" name="api_key"
                                value="{{ $courierStatusConfig['paperfly']['api_key'] ?? '' }}"
                                placeholder="Enter Paperfly Client ID">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Client Secret <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" name="secret_key"
                                    value="{{ $courierStatusConfig['paperfly']['secret_key'] ?? '' }}"
                                    placeholder="Enter Paperfly Client Secret">
                                <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="text" class="field-input" name="base_url"
                                value="{{ $courierStatusConfig['paperfly']['base_url'] ?? '' }}"
                                placeholder="https://api.paperfly.com.bd">
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-courier-save">
                            <i class="fa-solid fa-floppy-disk"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <div class="info-card-header">ðŸ“– Paperfly API à¦¸à§‡à¦Ÿà¦†à¦ª</div>
                <div class="info-card-body">
                    <div class="info-step" style="border-color:#f59e0b;">
                        <div class="step-num" style="background:#f59e0b;">à§§</div>
                        <div><strong>Paperfly Merchant à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ</strong><p>paperfly.com.bd à¦¤à§‡ merchant à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ à¦•à¦°à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#3b82f6;">
                        <div class="step-num" style="background:#3b82f6;">à§¨</div>
                        <div><strong>API Credentials</strong><p>Support à¦¬à¦¾ dashboard à¦¥à§‡à¦•à§‡ Client ID à¦“ Secret à¦ªà¦¾à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-tip"><span>ðŸ’¡</span><span>Paperfly API à¦¸à¦®à§à¦ªà¦°à§à¦•à§‡ à¦†à¦°à¦“ à¦œà¦¾à¦¨à¦¤à§‡ à¦¤à¦¾à¦¦à§‡à¦° support à¦ à¦¯à§‹à¦—à¦¾à¦¯à§‹à¦— à¦•à¦°à§à¦¨à¥¤</span></div>
                    <a href="https://paperfly.com.bd" target="_blank" class="info-visit-btn" style="color:#f59e0b;border-color:#fde68a;background:#fffbeb;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Paperfly Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== CITYFAST PANEL ===================== --}}
<div class="courier-panel" id="panel-cityfast">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="courier-logo-badge">
                            <img src="{{ asset('adminDash/assets/img/layouts/cityfastlogo.png') }}" alt="CityFast">
                        </div>
                        <div><h4>CityFast Courier</h4><p>cityfast.com.bd</p></div>
                    </div>
                    <div class="courier-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="cityfast" data-url="/api/update-courier-status"
                                {{ isset($courierStatusConfig['cityfast']) && $courierStatusConfig['cityfast']['status']=='1' ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="config-card-body">
                    <form class="settingsUpdateForm">
                        @csrf
                        <input type="hidden" name="courier_name" value="cityfast">
                        <div class="field-group">
                            <label class="field-label">Client ID <span style="color:#f43f5e">*</span></label>
                            <input type="text" class="field-input" name="api_key"
                                value="{{ $courierStatusConfig['cityfast']['api_key'] ?? '' }}"
                                placeholder="Enter CityFast Client ID">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Client Secret <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" name="secret_key"
                                    value="{{ $courierStatusConfig['cityfast']['secret_key'] ?? '' }}"
                                    placeholder="Enter CityFast Secret">
                                <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="text" class="field-input" name="base_url"
                                value="{{ $courierStatusConfig['cityfast']['base_url'] ?? '' }}"
                                placeholder="https://api.cityfast.com.bd">
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-courier-save">
                            <i class="fa-solid fa-floppy-disk"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <div class="info-card-header">ðŸ“– CityFast API à¦¸à§‡à¦Ÿà¦†à¦ª</div>
                <div class="info-card-body">
                    <div class="info-step" style="border-color:#8b5cf6;">
                        <div class="step-num" style="background:#8b5cf6;">à§§</div>
                        <div><strong>CityFast Merchant à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ</strong><p>cityfast.com.bd à¦¤à§‡ merchant à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ à¦•à¦°à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#3b82f6;">
                        <div class="step-num" style="background:#3b82f6;">à§¨</div>
                        <div><strong>API Credentials à¦ªà¦¾à¦¨</strong><p>Dashboard â†’ API Integration à¦¥à§‡à¦•à§‡ credentials à¦¨à¦¿à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-tip"><span>ðŸ’¡</span><span>CityFast primarily à¦¢à¦¾à¦•à¦¾à¦° à¦­à§‡à¦¤à¦°à§‡ same-day delivery à¦¸à¦¾à¦°à§à¦­à¦¿à¦¸ à¦¦à§‡à¦¯à¦¼à¥¤</span></div>
                    <a href="https://cityfast.com.bd" target="_blank" class="info-visit-btn" style="color:#8b5cf6;border-color:#ddd6fe;background:#f5f3ff;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> CityFast Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== ECOURIER PANEL ===================== --}}
<div class="courier-panel" id="panel-ecourier">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="config-card">
                <div class="config-card-header">
                    <div class="config-card-title">
                        <div class="courier-logo-badge">
                            <img src="{{ asset('adminDash/assets/img/layouts/ecourierlogo.png') }}" alt="eCourier">
                        </div>
                        <div><h4>eCourier</h4><p>ecourier.com.bd</p></div>
                    </div>
                    <div class="courier-toggle-wrap">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="ecourier" data-url="/api/update-courier-status"
                                {{ isset($courierStatusConfig['ecourier']) && $courierStatusConfig['ecourier']['status']=='1' ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="config-card-body">
                    <form class="settingsUpdateForm">
                        @csrf
                        <input type="hidden" name="courier_name" value="ecourier">
                        <div class="field-group">
                            <label class="field-label">API Key <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" name="api_key"
                                    value="{{ $courierStatusConfig['ecourier']['api_key'] ?? '' }}"
                                    placeholder="Enter eCourier API Key">
                                <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Secret Key <span style="color:#f43f5e">*</span></label>
                            <div class="input-icon-wrap">
                                <input type="password" class="field-input secret-field" name="secret_key"
                                    value="{{ $courierStatusConfig['ecourier']['secret_key'] ?? '' }}"
                                    placeholder="Enter eCourier Secret Key">
                                <i class="fa-regular fa-eye input-eye" onclick="toggleField(this)"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Base URL <span style="color:#9ca3af;font-weight:500;text-transform:none;font-size:11px;">(Optional)</span></label>
                            <input type="text" class="field-input" name="base_url"
                                value="{{ $courierStatusConfig['ecourier']['base_url'] ?? '' }}"
                                placeholder="https://ecourier.com.bd/api">
                        </div>
                        <div class="field-divider"></div>
                        <button type="submit" class="btn btn-courier-save">
                            <i class="fa-solid fa-floppy-disk"></i> Save Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <div class="info-card-header">ðŸ“– eCourier API à¦¸à§‡à¦Ÿà¦†à¦ª</div>
                <div class="info-card-body">
                    <div class="info-step" style="border-color:#0ea5e9;">
                        <div class="step-num" style="background:#0ea5e9;">à§§</div>
                        <div><strong>eCourier Merchant à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ</strong><p>ecourier.com.bd à¦¤à§‡ merchant à¦à¦•à¦¾à¦‰à¦¨à§à¦Ÿ à¦–à§à¦²à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#3b82f6;">
                        <div class="step-num" style="background:#3b82f6;">à§¨</div>
                        <div><strong>API Keys à¦ªà¦¾à¦¨</strong><p>Dashboard â†’ Developer â†’ API Key Generate à¦•à¦°à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-step" style="border-color:#10b981;">
                        <div class="step-num" style="background:#10b981;">à§©</div>
                        <div><strong>Save à¦•à¦°à§à¦¨</strong><p>API Key à¦“ Secret à¦¦à¦¿à¦¯à¦¼à§‡ à¦«à¦°à§à¦® à¦ªà§‚à¦°à¦£ à¦•à¦°à§‡ Save à¦•à¦°à§à¦¨à¥¤</p></div>
                    </div>
                    <div class="info-tip"><span>ðŸ’¡</span><span>eCourier à¦¸à¦¾à¦°à¦¾à¦¦à§‡à¦¶à§‡ delivery à¦¸à¦¾à¦°à§à¦­à¦¿à¦¸ à¦ªà§à¦°à¦¦à¦¾à¦¨ à¦•à¦°à§‡à¥¤</span></div>
                    <a href="https://ecourier.com.bd" target="_blank" class="info-visit-btn" style="color:#0ea5e9;border-color:#bae6fd;background:#f0f9ff;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> eCourier Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const updateUrl = "{{ route('courier.update.status') }}";

    // ===== Status switch (radio behaviour â€” only one active) =====
    $('.status-switch').on('change', function() {
        const sw = $(this);
        if (sw.is(':checked')) {
            const selectedCourier = sw.data('name');
            $('.status-switch').not(sw).prop('checked', false);
            $.ajax({
                url: updateUrl, method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ courier_name: selectedCourier }),
                success: function(res) {
                    Toast.fire({ icon: 'success', title: res.message });
                    // Update selection card statuses
                    $('.courier-select-status').each(function() {
                        const card = $(this).closest('.courier-select-card');
                        const name = card.attr('id').replace('tab-','');
                        if (name === selectedCourier) {
                            $(this).removeClass('status-off').addClass('status-on').text('â— Active');
                            card.addClass('enabled-courier');
                        } else {
                            $(this).removeClass('status-on').addClass('status-off').text('â—‹ Inactive');
                            card.removeClass('enabled-courier');
                        }
                    });
                },
                error: function(xhr) {
                    sw.prop('checked', false);
                    Toast.fire({ icon: 'error', title: xhr.responseJSON?.message ?? 'Update failed!' });
                    setTimeout(() => location.reload(), 1000);
                }
            });
        } else {
            sw.prop('checked', true); // prevent unchecking active
        }
    });

    // ===== Credentials save =====
    $('.settingsUpdateForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn  = form.find('button[type="submit"]');
        const orig = btn.html();
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...');
        $.ajax({
            url: "{{ route('courier.update.credentials') }}",
            method: 'POST', data: form.serialize(),
            success: function(res) {
                btn.prop('disabled', false).html(orig);
                Toast.fire({ icon: res.success ? 'success' : 'error', title: res.message || 'Done' });
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(orig);
                let msg = 'Update failed!';
                if (xhr.responseJSON?.errors) msg = Object.values(xhr.responseJSON.errors)[0][0];
                else if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
                Toast.fire({ icon: 'error', title: msg });
            }
        });
    });
});

// ===== Panel switcher =====
function switchCourier(name) {
    document.querySelectorAll('.courier-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.courier-select-card').forEach(c => c.classList.remove('active'));
    document.getElementById('panel-' + name).classList.add('active');
    document.getElementById('tab-'   + name).classList.add('active');
}

// ===== Toggle key visibility =====
function toggleField(icon) {
    const input = icon.previousElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
