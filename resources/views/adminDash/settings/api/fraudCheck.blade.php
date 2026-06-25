@php
    use App\Models\GeneralWebSettings;
    $settings  = GeneralWebSettings::all()->keyBy('name');
    $webConfig = $settings->map(fn($i) => $i->value)->toArray();
@endphp
@extends('layouts.AdminLays.master')
@section('title') FRAUD CHECK API @endsection
@section('content')

<style>
    /* ===== CSS Variables ===== */
    :root {
        --indigo:   #6366f1;
        --purple:   #8b5cf6;
        --emerald:  #10b981;
        --rose:     #f43f5e;
        --amber:    #f59e0b;
        --blue:     #3b82f6;
        --card-bg:  #ffffff;
        --border:   rgba(229,231,235,0.7);
        --shadow:   0 4px 24px -4px rgba(0,0,0,0.07);
        --radius:   16px;
    }

    /* ===== Page Header ===== */
    .fca-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        border-radius: var(--radius);
        padding: 28px 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .fca-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .fca-hero-icon {
        width: 52px; height: 52px;
        background: rgba(255,255,255,0.15);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.2);
        margin-bottom: 14px;
    }
    .fca-hero h1 { color: #fff; font-size: 22px; font-weight: 800; margin: 0 0 6px; }
    .fca-hero p  { color: rgba(255,255,255,0.75); font-size: 13px; margin: 0; }
    .fca-hero-badges { display: flex; gap: 8px; margin-top: 14px; flex-wrap: wrap; }
    .fca-hero-badge {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        color: #fff; font-size: 10px; font-weight: 700;
        padding: 4px 12px; border-radius: 20px;
        letter-spacing: 0.5px; backdrop-filter: blur(4px);
    }

    /* ===== Provider Tabs ===== */
    .provider-tabs {
        display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: wrap;
    }
    .provider-tab {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 18px;
        background: #fff;
        border: 2px solid var(--border);
        border-radius: 12px;
        cursor: pointer;
        font-size: 13px; font-weight: 600;
        color: #6b7280;
        transition: all 0.25s ease;
    }
    .provider-tab:hover { border-color: var(--indigo); color: var(--indigo); }
    .provider-tab.active {
        background: linear-gradient(135deg, var(--indigo), var(--purple));
        border-color: transparent;
        color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }
    .provider-tab .tab-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.6;
    }
    .provider-tab.active .tab-dot { background: #fff; opacity: 1; }

    /* ===== Provider Panels ===== */
    .provider-panel { display: none; }
    .provider-panel.active { display: block; }

    /* ===== API Config Card ===== */
    .api-config-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 0;
        transition: all 0.3s ease;
    }
    .api-config-card:hover {
        box-shadow: 0 12px 32px -4px rgba(0,0,0,0.10);
        transform: translateY(-2px);
    }
    .api-config-card .card-top {
        padding: 20px 24px 0;
        display: flex; align-items: center; gap: 14px;
    }
    .provider-logo {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 800;
        flex-shrink: 0;
        color: #fff;
    }
    .api-config-card .provider-name  { font-size: 16px; font-weight: 800; color: #111827; margin: 0; }
    .api-config-card .provider-label { font-size: 11px; color: #9ca3af; margin: 0; }
    .api-config-card .card-body-inner { padding: 20px 24px 24px; }

    /* ===== Form Inputs ===== */
    .fca-label {
        font-size: 12px; font-weight: 700; color: #374151;
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;
    }
    .fca-input {
        background: #f9fafb !important;
        border: 1.5px solid #e5e7eb !important;
        border-radius: 10px !important;
        padding: 11px 14px !important;
        font-size: 13px !important;
        font-weight: 500;
        color: #111827;
        transition: all 0.2s ease;
    }
    .fca-input:focus {
        background: #fff !important;
        border-color: var(--indigo) !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12) !important;
        outline: none;
    }
    .fca-input-icon-wrap { position: relative; }
    .fca-input-icon {
        position: absolute; right: 12px; top: 50%;
        transform: translateY(-50%);
        color: #9ca3af; font-size: 13px;
        cursor: pointer; z-index: 10;
    }
    .fca-input-icon:hover { color: var(--indigo); }

    /* ===== Save Button ===== */
    .fca-save-btn {
        background: linear-gradient(135deg, var(--indigo), var(--purple)) !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 11px 24px !important;
        font-size: 13px !important; font-weight: 700;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(99,102,241,0.3) !important;
        transition: all 0.2s ease;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .fca-save-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99,102,241,0.4) !important;
    }

    /* ===== Status Indicator ===== */
    .api-status-row {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 14px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        margin-bottom: 18px;
        font-size: 12px; font-weight: 600; color: #065f46;
    }
    .api-status-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.25);
        animation: pulse-dot 2s infinite;
        flex-shrink: 0;
    }
    @keyframes pulse-dot {
        0%, 100% { box-shadow: 0 0 0 3px rgba(16,185,129,0.25); }
        50%       { box-shadow: 0 0 0 6px rgba(16,185,129,0.1); }
    }

    /* ===== Instruction Panel ===== */
    .inst-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        height: 100%;
    }
    .inst-card .inst-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(to right, #f9fafb, #fff);
        display: flex; align-items: center; gap: 10px;
    }
    .inst-card .inst-header h5 { font-size: 14px; font-weight: 700; margin: 0; color: #111827; }
    .inst-card .inst-body { padding: 20px 22px; }

    .inst-step {
        display: flex; gap: 12px;
        padding: 12px 14px;
        border-radius: 10px;
        margin-bottom: 10px;
        border-left: 4px solid;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: transform 0.2s ease;
    }
    .inst-step:hover { transform: translateX(4px); }
    .inst-step-num {
        width: 26px; height: 26px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800; color: #fff;
        flex-shrink: 0; margin-top: 1px;
    }
    .inst-step strong { font-size: 12px; font-weight: 700; color: #111827; display: block; margin-bottom: 2px; }
    .inst-step p { font-size: 11.5px; color: #6b7280; margin: 0; line-height: 1.5; }

    .inst-tip {
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 12px; color: #92400e;
        display: flex; gap: 8px; align-items: flex-start;
        margin-top: 14px;
    }
    .inst-visit-btn {
        display: inline-flex; align-items: center; gap: 7px;
        margin-top: 14px; width: 100%;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 12px; font-weight: 700;
        text-decoration: none;
        border: 2px solid;
        transition: all 0.2s ease;
        justify-content: center;
    }
    .inst-visit-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

    /* Divider in card */
    .fca-divider {
        height: 1px; background: var(--border); margin: 18px 0;
    }

    /* Key masked display */
    .key-masked {
        font-family: monospace; letter-spacing: 2px; color: #6b7280; font-size: 12px;
    }
</style>

{{-- ===== Page Hero ===== --}}
<div class="fca-hero">
    <div class="fca-hero-icon">🛡️</div>
    <h1>Fraud Check API Configuration</h1>
    <p>Configure third-party fraud detection APIs to verify customer phone numbers during checkout and reduce fraudulent orders.</p>
    <div class="fca-hero-badges">
        <span class="fca-hero-badge">BD Courier</span>
        <span class="fca-hero-badge">Zachaikori</span>
        <span class="fca-hero-badge">FraudShield BD</span>
        <span class="fca-hero-badge">🔒 Secure Configuration</span>
    </div>
</div>

{{-- ===== Provider Tabs ===== --}}
<div class="provider-tabs">
    <div class="provider-tab active" onclick="switchProvider('bdcourier')" id="tab-bdcourier">
        <span style="font-size:15px;">🚚</span>
        <span>BD Courier</span>
    </div>
    <div class="provider-tab" onclick="switchProvider('zachaikori')" id="tab-zachaikori">
        <span style="font-size:15px;">🔍</span>
        <span>Zachaikori</span>
    </div>
    <div class="provider-tab" onclick="switchProvider('fraudshield')" id="tab-fraudshield">
        <span style="font-size:15px;">🛡️</span>
        <span>FraudShield BD</span>
    </div>
</div>

{{-- ===================== BD COURIER PANEL ===================== --}}
<div class="provider-panel active" id="panel-bdcourier">
    <div class="row g-4">
        {{-- Config Form --}}
        <div class="col-lg-6">
            <div class="api-config-card">
                <div class="card-top">
                    <div class="provider-logo" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">🚚</div>
                    <div>
                        <p class="provider-name">BD Courier</p>
                        <p class="provider-label">BDCourier.com — Fraud Check & Courier History</p>
                    </div>
                </div>
                <div class="card-body-inner">
                    @if(!empty($webConfig['fraud_check_api_key'] ?? ''))
                    <div class="api-status-row">
                        <div class="api-status-dot"></div>
                        <span>API Key configured — Active</span>
                        <span class="key-masked ms-auto">••••{{ substr($webConfig['fraud_check_api_key'] ?? '', -6) }}</span>
                    </div>
                    @endif

                    <form action="{{ route('admin.fraudCheck.update') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="fca-label">API Key <span style="color:var(--rose)">*</span></label>
                            <div class="fca-input-icon-wrap">
                                <input type="password" id="bdcourier-apikey" class="form-control fca-input"
                                    value="{{ $webConfig['fraud_check_api_key'] ?? '' }}"
                                    name="api_key"
                                    placeholder="Enter BD Courier API Key"
                                    autocomplete="off">
                                <i class="fa-regular fa-eye fca-input-icon" onclick="toggleKey('bdcourier-apikey', this)"></i>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="fca-label">Base URL <span style="color:#9ca3af; font-weight:500; text-transform:none; font-size:11px;">(Optional)</span></label>
                            <input type="text" class="form-control fca-input"
                                value="{{ $webConfig['fraud_check_api_url'] ?? '' }}"
                                name="base_url"
                                placeholder="https://bdcourier.com/api/courier-check">
                            <div style="font-size:11px; color:#9ca3af; margin-top:5px;">Leave blank to use the default BDCourier API endpoint.</div>
                        </div>
                        <div class="fca-divider"></div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn fca-save-btn">
                                <i class="fa-solid fa-floppy-disk"></i> Save Configuration
                            </button>
                            <a href="https://bdcourier.com" target="_blank"
                               style="font-size:12px; color:var(--indigo); font-weight:600; text-decoration:none;">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Visit BDCourier
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Instructions --}}
        <div class="col-lg-6">
            <div class="inst-card">
                <div class="inst-header">
                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;">📖</div>
                    <h5>API Key কিভাবে পাবেন?</h5>
                </div>
                <div class="inst-body">
                    <div class="inst-step" style="border-color:#10b981;">
                        <div class="inst-step-num" style="background:#10b981;">১</div>
                        <div>
                            <strong>রেজিস্ট্রেশন করুন</strong>
                            <p>BDCourier.com এ যান, Sign Up করুন এবং আপনার একাউন্ট ভেরিফাই করুন।</p>
                        </div>
                    </div>
                    <div class="inst-step" style="border-color:#3b82f6;">
                        <div class="inst-step-num" style="background:#3b82f6;">২</div>
                        <div>
                            <strong>Developer Panel এ যান</strong>
                            <p>Dashboard → Settings → Developer / API Section এ যান।</p>
                        </div>
                    </div>
                    <div class="inst-step" style="border-color:#f59e0b;">
                        <div class="inst-step-num" style="background:#f59e0b;">৩</div>
                        <div>
                            <strong>API Key কপি করুন</strong>
                            <p>"Generate New Key" বাটনে ক্লিক করুন এবং key টি কপি করুন।</p>
                        </div>
                    </div>
                    <div class="inst-step" style="border-color:#f43f5e;">
                        <div class="inst-step-num" style="background:#f43f5e;">৪</div>
                        <div>
                            <strong>নিরাপদে রাখুন</strong>
                            <p>API Key কোথাও পাবলিকলি শেয়ার বা GitHub এ commit করবেন না।</p>
                        </div>
                    </div>
                    <div class="inst-tip">
                        <span>💡</span>
                        <span>প্রথমে Sandbox/Test মোডে পরীক্ষা করুন, তারপর Live API Key ব্যবহার করুন।</span>
                    </div>
                    <a href="https://bdcourier.com" target="_blank" class="inst-visit-btn"
                       style="color:#4f46e5; border-color:#e0e7ff; background:#eff6ff;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        BDCourier.com এ যান
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== ZACHAIKORI PANEL ===================== --}}
<div class="provider-panel" id="panel-zachaikori">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="api-config-card">
                <div class="card-top">
                    <div class="provider-logo" style="background: linear-gradient(135deg, #059669, #10b981);">🔍</div>
                    <div>
                        <p class="provider-name">Zachaikori</p>
                        <p class="provider-label">Zachaikori.com — Phone Verification Service</p>
                    </div>
                </div>
                <div class="card-body-inner">
                    <form action="{{ route('admin.fraudCheck.update') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="fca-label">API Key <span style="color:var(--rose)">*</span></label>
                            <div class="fca-input-icon-wrap">
                                <input type="password" id="zachaikori-apikey" class="form-control fca-input"
                                    value="{{ $webConfig['fraud_check_api_key'] ?? '' }}"
                                    name="api_key"
                                    placeholder="Enter Zachaikori API Key"
                                    autocomplete="off">
                                <i class="fa-regular fa-eye fca-input-icon" onclick="toggleKey('zachaikori-apikey', this)"></i>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="fca-label">Base URL <span style="color:#9ca3af; font-weight:500; text-transform:none; font-size:11px;">(Optional)</span></label>
                            <input type="text" class="form-control fca-input"
                                value="{{ $webConfig['fraud_check_api_url'] ?? '' }}"
                                name="base_url"
                                placeholder="https://api.zachaikori.com/v1/check">
                        </div>
                        <div class="fca-divider"></div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn fca-save-btn" style="background: linear-gradient(135deg,#059669,#10b981) !important; box-shadow: 0 4px 14px rgba(16,185,129,0.3) !important;">
                                <i class="fa-solid fa-floppy-disk"></i> Save Configuration
                            </button>
                            <a href="https://zachaikori.com" target="_blank"
                               style="font-size:12px; color:#059669; font-weight:600; text-decoration:none;">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Visit Zachaikori
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="inst-card">
                <div class="inst-header">
                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#059669,#10b981);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;">📖</div>
                    <h5>API Key কিভাবে পাবেন?</h5>
                </div>
                <div class="inst-body">
                    <div class="inst-step" style="border-color:#10b981;">
                        <div class="inst-step-num" style="background:#10b981;">১</div>
                        <div>
                            <strong>রেজিস্ট্রেশন করুন</strong>
                            <p>Zachaikori.com এ রেজিস্টার করুন এবং আপনার একাউন্ট লগইন করুন।</p>
                        </div>
                    </div>
                    <div class="inst-step" style="border-color:#3b82f6;">
                        <div class="inst-step-num" style="background:#3b82f6;">২</div>
                        <div>
                            <strong>Developer/API সেকশন</strong>
                            <p>Panel → Developer/API → নতুন API Key তৈরি করুন।</p>
                        </div>
                    </div>
                    <div class="inst-step" style="border-color:#f59e0b;">
                        <div class="inst-step-num" style="background:#f59e0b;">৩</div>
                        <div>
                            <strong>Key কপি করুন</strong>
                            <p>Generated API Key টি কপি করে এখানে paste করুন।</p>
                        </div>
                    </div>
                    <div class="inst-step" style="border-color:#f43f5e;">
                        <div class="inst-step-num" style="background:#f43f5e;">৪</div>
                        <div>
                            <strong>নিরাপত্তা</strong>
                            <p>API Key/Secret Key কোথাও পাবলিকলি শেয়ার করবেন না।</p>
                        </div>
                    </div>
                    <div class="inst-tip">
                        <span>💡</span>
                        <span>প্রথমে টেস্ট মোডে পরীক্ষা করুন, তারপর লাইভ ব্যবহার করুন।</span>
                    </div>
                    <a href="https://zachaikori.com" target="_blank" class="inst-visit-btn"
                       style="color:#059669; border-color:#d1fae5; background:#ecfdf5;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        Zachaikori.com এ যান
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== FRAUDSHIELD BD PANEL ===================== --}}
<div class="provider-panel" id="panel-fraudshield">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="api-config-card">
                <div class="card-top">
                    <div class="provider-logo" style="background: linear-gradient(135deg, #dc2626, #f43f5e);">🛡️</div>
                    <div>
                        <p class="provider-name">FraudShield BD</p>
                        <p class="provider-label">FraudShieldBD.com — Advanced Fraud Detection</p>
                    </div>
                </div>
                <div class="card-body-inner">
                    <form action="{{ route('admin.fraudCheck.update') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="fca-label">API Key <span style="color:var(--rose)">*</span></label>
                            <div class="fca-input-icon-wrap">
                                <input type="password" id="fraudshield-apikey" class="form-control fca-input"
                                    value="{{ $webConfig['fraud_check_api_key'] ?? '' }}"
                                    name="api_key"
                                    placeholder="Enter FraudShield BD API Key"
                                    autocomplete="off">
                                <i class="fa-regular fa-eye fca-input-icon" onclick="toggleKey('fraudshield-apikey', this)"></i>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="fca-label">Base URL <span style="color:#9ca3af; font-weight:500; text-transform:none; font-size:11px;">(Optional)</span></label>
                            <input type="text" class="form-control fca-input"
                                value="{{ $webConfig['fraud_check_api_url'] ?? '' }}"
                                name="base_url"
                                placeholder="https://api.fraudshieldbd.com/v1/check">
                        </div>
                        <div class="fca-divider"></div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn fca-save-btn" style="background: linear-gradient(135deg,#dc2626,#f43f5e) !important; box-shadow: 0 4px 14px rgba(244,63,94,0.3) !important;">
                                <i class="fa-solid fa-floppy-disk"></i> Save Configuration
                            </button>
                            <a href="https://fraudshieldbd.com" target="_blank"
                               style="font-size:12px; color:#dc2626; font-weight:600; text-decoration:none;">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Visit FraudShield
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="inst-card">
                <div class="inst-header">
                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#dc2626,#f43f5e);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;">📖</div>
                    <h5>API Key কিভাবে পাবেন?</h5>
                </div>
                <div class="inst-body">
                    <div class="inst-step" style="border-color:#10b981;">
                        <div class="inst-step-num" style="background:#10b981;">১</div>
                        <div>
                            <strong>রেজিস্ট্রেশন করুন</strong>
                            <p>FraudShieldBD.com এ রেজিস্টার করুন এবং লগইন করুন।</p>
                        </div>
                    </div>
                    <div class="inst-step" style="border-color:#3b82f6;">
                        <div class="inst-step-num" style="background:#3b82f6;">২</div>
                        <div>
                            <strong>Developer সেকশন</strong>
                            <p>Panel → Developer/API → নতুন API Key কপি করুন।</p>
                        </div>
                    </div>
                    <div class="inst-step" style="border-color:#f59e0b;">
                        <div class="inst-step-num" style="background:#f59e0b;">৩</div>
                        <div>
                            <strong>Key Paste করুন</strong>
                            <p>আপনার API Key টি বাম পাশের ফর্মে paste করে Save করুন।</p>
                        </div>
                    </div>
                    <div class="inst-step" style="border-color:#f43f5e;">
                        <div class="inst-step-num" style="background:#f43f5e;">৪</div>
                        <div>
                            <strong>নিরাপত্তা</strong>
                            <p>API Key/Secret Key কোথাও পাবলিকলি শেয়ার করবেন না।</p>
                        </div>
                    </div>
                    <div class="inst-tip">
                        <span>💡</span>
                        <span>প্রথমে টেস্ট মোডে পরীক্ষা করুন, তারপর লাইভ ব্যবহার করুন।</span>
                    </div>
                    <a href="https://fraudshieldbd.com" target="_blank" class="inst-visit-btn"
                       style="color:#dc2626; border-color:#fecdd3; background:#fff1f2;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        FraudShieldBD.com এ যান
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ===== Provider Tab Switcher =====
    function switchProvider(name) {
        // Hide all panels & deactivate tabs
        document.querySelectorAll('.provider-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.provider-tab').forEach(t => t.classList.remove('active'));

        // Show selected panel & activate tab
        document.getElementById('panel-' + name).classList.add('active');
        document.getElementById('tab-'   + name).classList.add('active');
    }

    // ===== Toggle API Key Visibility =====
    function toggleKey(inputId, iconEl) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            iconEl.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            iconEl.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

@endsection
