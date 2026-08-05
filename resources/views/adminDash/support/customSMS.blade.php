@extends('layouts.Backend.master')
@section('title')
    CUSTOM SMS DISPATCHER
@endsection

@section('style')
<style>
    .custom-sms-wrapper {
        padding-bottom: 2rem;
    }
    .hero-banner-sms {
        background: linear-gradient(135deg, #059669 0%, #0d9488 50%, #0f766e 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        color: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.3);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .hero-banner-sms::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-icon-badge-sms {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #fff;
        margin-right: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .composer-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .composer-header {
        background: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
        padding: 1.25rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Target Audience Selector Tiles */
    .recipient-tiles {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .recipient-tile {
        position: relative;
        cursor: pointer;
    }
    .recipient-tile input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .tile-content {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.9rem 1rem;
        text-align: center;
        background: #fff;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .tile-content i {
        font-size: 1.4rem;
        color: #6b7280;
        margin-bottom: 0.4rem;
        transition: color 0.25s ease;
    }
    .tile-title {
        font-weight: 600;
        font-size: 0.88rem;
        color: #374151;
        margin-bottom: 2px;
    }
    .tile-desc {
        font-size: 0.75rem;
        color: #9ca3af;
    }
    .recipient-tile input[type="radio"]:checked + .tile-content {
        border-color: #059669;
        background: #ecfdf5;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);
    }
    .recipient-tile input[type="radio"]:checked + .tile-content i {
        color: #059669;
    }
    .recipient-tile input[type="radio"]:checked + .tile-content .tile-title {
        color: #065f46;
    }
    .recipient-tile:hover .tile-content {
        border-color: #6ee7b7;
        transform: translateY(-2px);
    }

    /* Variable Tags Bar */
    .variables-bar {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
    }
    .variable-tag {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.25rem 0.6rem;
        font-size: 0.78rem;
        font-family: monospace;
        font-weight: 600;
        color: #059669;
        cursor: pointer;
        margin: 0.2rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .variable-tag:hover {
        background: #059669;
        color: #ffffff;
        border-color: #059669;
        transform: scale(1.05);
    }

    /* Form Controls */
    .form-control-custom {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        padding: 0.65rem 1rem;
        font-size: 0.92rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control-custom:focus {
        border-color: #059669;
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
        outline: none;
    }

    /* SMS Counter Card */
    .sms-metrics-bar {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.5rem;
    }
    .sms-metric-item {
        text-align: center;
    }
    .sms-metric-val {
        font-size: 1.1rem;
        font-weight: 700;
        color: #166534;
    }
    .sms-metric-lbl {
        font-size: 0.75rem;
        color: #15803d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Sidebar Cards */
    .sidebar-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .sidebar-card-header {
        padding: 1rem 1.25rem;
        background: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
        font-weight: 700;
        font-size: 0.95rem;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Preset Template Button */
    .preset-template-btn {
        width: 100%;
        text-align: left;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.6rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
    }
    .preset-template-btn:hover {
        background: #ecfdf5;
        border-color: #6ee7b7;
        transform: translateX(4px);
    }
    .preset-icon-sms {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #d1fae5;
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* Phone Mockup Simulator for Preview */
    .phone-mockup-frame {
        width: 320px;
        height: 560px;
        background: #000000;
        border-radius: 40px;
        padding: 12px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        margin: 0 auto;
        position: relative;
    }
    .phone-screen {
        width: 100%;
        height: 100%;
        background: #f2f2f7;
        border-radius: 30px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .phone-header {
        background: #e5e5ea;
        padding: 14px 15px 10px;
        text-align: center;
        border-bottom: 1px solid #d1d1d6;
    }
    .phone-chat-area {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }
    .sms-bubble {
        background: #007aff;
        color: #ffffff;
        padding: 10px 14px;
        border-radius: 18px 18px 2px 18px;
        font-size: 0.88rem;
        line-height: 1.4;
        max-width: 85%;
        align-self: flex-end;
        box-shadow: 0 1px 2px rgba(0,0,0,0.15);
        word-wrap: break-word;
    }
    .sms-time {
        font-size: 0.65rem;
        color: rgba(255,255,255,0.7);
        text-align: right;
        margin-top: 4px;
    }

    /* Pulse dot */
    .pulse-dot-sms {
        display: inline-block;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-animation-sms 1.6s infinite;
        margin-right: 4px;
    }
    @keyframes pulse-animation-sms {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endsection

@section('content')
<div class="container-fluid custom-sms-wrapper">

    <!-- Hero Header Banner -->
    <div class="hero-banner-sms">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="hero-icon-badge-sms">
                    <i class="fa-solid fa-comment-sms"></i>
                </div>
                <div>
                    <h2 class="font-weight-bold text-white mb-1">Custom SMS Dispatcher</h2>
                    <p class="mb-0 text-white-50" style="font-size: 0.95rem;">
                        Dispatch high-speed promotional SMS alerts, transactional updates, or support texts directly to mobile phones.
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if($isSmsConfigured)
                    <span class="badge badge-light text-dark px-3 py-2" style="border-radius: 20px; font-weight: 600;">
                        <span class="pulse-dot-sms"></span> Gateway Active ({{ $smsProvider }})
                    </span>
                @else
                    <a href="{{ route('smtp.index') }}" class="badge badge-warning text-dark px-3 py-2" style="border-radius: 20px; font-weight: 600; text-decoration: none;">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> SMS Gateway Needed
                    </a>
                @endif
                <span class="badge badge-light text-success px-3 py-2 ml-2" style="border-radius: 20px; font-weight: 600;">
                    <i class="fa-solid fa-users mr-1"></i> {{ number_format($usersCount ?? 0) }} Customer Profiles
                </span>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #ecfdf5; color: #065f46; border-left: 5px solid #10b981 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fa-lg mr-3 text-success"></i>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #fef2f2; color: #991b1b; border-left: 5px solid #ef4444 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation fa-lg mr-3 text-danger"></i>
                <div>
                    <strong>Attention Required!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #fffbeb; color: #92400e; border-left: 5px solid #f59e0b !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fa-lg mr-3 text-warning"></i>
                <div>
                    <strong>Form Validation Errors:</strong>
                    <ul class="mb-0 pl-3 mt-1" style="font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Main Composer Column -->
        <div class="col-lg-8">
            <div class="composer-card">
                <div class="composer-header">
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark"><i class="fa-solid fa-pen-to-square text-success mr-2"></i> Compose SMS Message</h5>
                        <small class="text-muted">Specify target recipients and write your SMS message body below</small>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="resetSmsComposerForm()">
                            <i class="fa-solid fa-rotate-left mr-1"></i> Clear Form
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.customSMS.send') }}" method="POST" id="customSmsForm">
                        @csrf

                        <!-- STEP 1: Target Audience Selection -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <i class="fa-solid fa-bullseye text-danger mr-1"></i> Target Audience <span class="text-danger">*</span>
                            </label>

                            <div class="recipient-tiles">
                                <label class="recipient-tile">
                                    <input type="radio" name="recipient_type" value="user" {{ old('recipient_type', 'user') == 'user' ? 'checked' : '' }} onchange="toggleSmsTargetType(this.value)">
                                    <div class="tile-content">
                                        <i class="fa-solid fa-user-check"></i>
                                        <span class="tile-title">Select Customer</span>
                                        <span class="tile-desc">Pick user from database</span>
                                    </div>
                                </label>

                                <label class="recipient-tile">
                                    <input type="radio" name="recipient_type" value="phone" {{ old('recipient_type') == 'phone' ? 'checked' : '' }} onchange="toggleSmsTargetType(this.value)">
                                    <div class="tile-content">
                                        <i class="fa-solid fa-phone"></i>
                                        <span class="tile-title">Direct Number</span>
                                        <span class="tile-desc">Single mobile number</span>
                                    </div>
                                </label>

                                <label class="recipient-tile">
                                    <input type="radio" name="recipient_type" value="multiple" {{ old('recipient_type') == 'multiple' ? 'checked' : '' }} onchange="toggleSmsTargetType(this.value)">
                                    <div class="tile-content">
                                        <i class="fa-solid fa-list-numeric"></i>
                                        <span class="tile-title">Bulk List</span>
                                        <span class="tile-desc">Multiple phone numbers</span>
                                    </div>
                                </label>

                                <label class="recipient-tile">
                                    <input type="radio" name="recipient_type" value="all" {{ old('recipient_type') == 'all' ? 'checked' : '' }} onchange="toggleSmsTargetType(this.value)">
                                    <div class="tile-content">
                                        <i class="fa-solid fa-tower-broadcast"></i>
                                        <span class="tile-title">All Phone Numbers</span>
                                        <span class="tile-desc">Broadcast to everyone</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Target Option Inputs -->
                            <div id="smsTargetUserContainer" class="target-sms-container">
                                <label class="small text-muted font-weight-bold">Select Customer Account:</label>
                                <select name="user_id" id="sms_user_id_select" class="form-control select2 w-100">
                                    <option value="">-- Search & Choose Customer --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="smsTargetPhoneContainer" class="target-sms-container d-none">
                                <label class="small text-muted font-weight-bold">Mobile Phone Number:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fa-solid fa-mobile-button text-success"></i></span>
                                    </div>
                                    <input type="text" name="phone" class="form-control form-control-custom" placeholder="e.g. 01712345678 or +8801712345678" value="{{ old('phone') }}">
                                </div>
                            </div>

                            <div id="smsTargetMultipleContainer" class="target-sms-container d-none">
                                <label class="small text-muted font-weight-bold">Multiple Mobile Numbers (Comma / Line Separated):</label>
                                <textarea name="multiple_phones" class="form-control form-control-custom" rows="3" placeholder="01711000000, 01822000000, 01933000000">{{ old('multiple_phones') }}</textarea>
                                <small class="form-text text-muted">Enter mobile numbers separated by commas or line breaks.</small>
                            </div>

                            <div id="smsTargetAllContainer" class="target-sms-container d-none">
                                <div class="p-3 bg-light-success text-success border border-success rounded-lg d-flex align-items-center" style="background:#ecfdf5;">
                                    <i class="fa-solid fa-circle-info fa-2x mr-3 text-success"></i>
                                    <div>
                                        <strong class="d-block text-success">Bulk Broadcast SMS Mode</strong>
                                        <span style="font-size: 0.88rem; color:#065f46;">This SMS alert will be dispatched to all registered phone numbers recorded in customer orders and user profiles.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: Quick SMS Presets Dropdown & Variables -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">
                                <i class="fa-solid fa-comment-dots text-success mr-1"></i> SMS Message Content <span class="text-danger">*</span>
                            </label>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm text-success p-0 dropdown-toggle font-weight-bold" type="button" data-toggle="dropdown">
                                    <i class="fa-solid fa-bolt mr-1"></i> Quick Message Presets
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="applySmsPreset('🎉 Special Deal: Get 15% OFF on your next order at {site_name}! Code: SAVE15. Shop now: {site_url}')">🎉 Promo Discount Coupon</a>
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="applySmsPreset('📢 Hello {name}, your order has been confirmed at {site_name}. Thank you for shopping with us!')">📢 Order Confirmation</a>
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="applySmsPreset('🚚 Order Alert: Hello {name}, your parcel is out for delivery today. Have a great day!')">🚚 Delivery Status Update</a>
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="applySmsPreset('👋 Welcome {name}! Thank you for registering at {site_name}. Visit us at {site_url}')">👋 Welcome Greeting</a>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Variable Tags Bar -->
                        <div class="variables-bar">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small font-weight-bold text-dark"><i class="fa-solid fa-code text-success mr-1"></i> Click to Insert Placeholder Tag:</span>
                            </div>
                            <div>
                                <span class="variable-tag" onclick="insertSmsTag('{name}')" title="Customer Name"><i class="fa-solid fa-user mr-1"></i> {name}</span>
                                <span class="variable-tag" onclick="insertSmsTag('{phone}')" title="Customer Phone Number"><i class="fa-solid fa-phone mr-1"></i> {phone}</span>
                                <span class="variable-tag" onclick="insertSmsTag('{site_name}')" title="Store Name"><i class="fa-solid fa-store mr-1"></i> {site_name}</span>
                                <span class="variable-tag" onclick="insertSmsTag('{site_url}')" title="Store Website Link"><i class="fa-solid fa-link mr-1"></i> {site_url}</span>
                                <span class="variable-tag" onclick="insertSmsTag('{date}')" title="Today's Date"><i class="fa-solid fa-calendar mr-1"></i> {date}</span>
                            </div>
                        </div>

                        <!-- SMS Textarea Body -->
                        <div class="form-group mb-2">
                            <textarea name="message" id="smsContentTextarea" class="form-control form-control-custom" rows="6" placeholder="Type your SMS message here..." oninput="updateSmsMetrics()" required>{{ old('message') }}</textarea>
                        </div>

                        <!-- Real-time SMS Character & Segment Counter Engine -->
                        <div class="sms-metrics-bar mb-4">
                            <div class="sms-metric-item">
                                <div class="sms-metric-val" id="metricCharCount">0</div>
                                <div class="sms-metric-lbl">Characters</div>
                            </div>
                            <div style="border-right: 1px solid #bbf7d0; height: 30px;"></div>
                            <div class="sms-metric-item">
                                <div class="sms-metric-val" id="metricSmsCount">1</div>
                                <div class="sms-metric-lbl">SMS Parts</div>
                            </div>
                            <div style="border-right: 1px solid #bbf7d0; height: 30px;"></div>
                            <div class="sms-metric-item">
                                <div class="sms-metric-val" id="metricRemaining">160</div>
                                <div class="sms-metric-lbl">Chars Remaining</div>
                            </div>
                            <div style="border-right: 1px solid #bbf7d0; height: 30px;"></div>
                            <div class="sms-metric-item">
                                <div class="sms-metric-val text-success" id="metricEncoding">GSM 7-Bit</div>
                                <div class="sms-metric-lbl">Encoding</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-success rounded-pill px-4" onclick="openSmsSimulatorPreview()">
                                <i class="fa-solid fa-mobile-screen-button mr-2"></i> Simulator Preview
                            </button>

                            <button type="submit" id="btnSubmitSms" class="btn btn-success rounded-pill px-4 py-2 font-weight-bold shadow-sm" style="background:#059669; border-color:#059669;">
                                <i class="fa-solid fa-paper-plane mr-2"></i> Dispatch SMS Now
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">

            <!-- Pre-designed SMS Templates -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fa-solid fa-wand-magic-sparkles text-success"></i> Ready SMS Templates
                </div>
                <div class="p-3">
                    <p class="small text-muted mb-3">Click any template below to populate the message composer:</p>

                    <div class="preset-template-btn" onclick="loadSmsTemplate('promo')">
                        <div class="preset-icon-sms"><i class="fa-solid fa-percent"></i></div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">Flash Sale Promo</div>
                            <div class="small text-muted">Discount code & store link</div>
                        </div>
                    </div>

                    <div class="preset-template-btn" onclick="loadSmsTemplate('order')">
                        <div class="preset-icon-sms" style="background:#dbeafe; color:#2563eb;"><i class="fa-solid fa-bag-shopping"></i></div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">Order Confirmation</div>
                            <div class="small text-muted">Customer order acknowledgment</div>
                        </div>
                    </div>

                    <div class="preset-template-btn" onclick="loadSmsTemplate('delivery')">
                        <div class="preset-icon-sms" style="background:#fef3c7; color:#d97706;"><i class="fa-solid fa-truck-fast"></i></div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">Delivery Dispatch</div>
                            <div class="small text-muted">Out for delivery status notification</div>
                        </div>
                    </div>

                    <div class="preset-template-btn" onclick="loadSmsTemplate('welcome')">
                        <div class="preset-icon-sms" style="background:#f3e8ff; color:#9333ea;"><i class="fa-solid fa-heart"></i></div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">Customer Onboarding</div>
                            <div class="small text-muted">Friendly welcoming message</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMS Gateway Status -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fa-solid fa-server text-info"></i> Gateway Gateway Engine Status
                </div>
                <div class="p-3">
                    @if($isSmsConfigured)
                        <div class="d-flex align-items-center text-success mb-2">
                            <i class="fa-solid fa-circle-check mr-2"></i>
                            <span class="font-weight-bold" style="font-size: 0.9rem;">SMS Gateway Configured</span>
                        </div>
                        <p class="small text-muted mb-3">Active Gateway Provider: <strong>{{ $smsProvider }}</strong></p>
                    @else
                        <div class="d-flex align-items-center text-danger mb-2">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                            <span class="font-weight-bold" style="font-size: 0.9rem;">SMS API Key Missing</span>
                        </div>
                        <p class="small text-muted mb-3">Configure your SMS API Token or Gateway Provider to enable SMS broadcasting.</p>
                    @endif
                    <a href="{{ route('smtp.index') }}" class="btn btn-outline-success btn-sm btn-block rounded-pill">
                        <i class="fa-solid fa-gear mr-1"></i> Configure SMS Gateway
                    </a>
                </div>
            </div>

            <!-- SMS Calculation Info -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fa-solid fa-circle-info text-warning"></i> SMS Character Limits
                </div>
                <div class="p-3">
                    <ul class="pl-3 mb-0 small text-muted" style="line-height: 1.6;">
                        <li class="mb-2"><strong>Standard English (GSM):</strong> 160 characters per single SMS. Multi-part SMS allows 153 chars/part.</li>
                        <li class="mb-2"><strong>Bangla / Emojis (Unicode):</strong> 70 characters for 1st SMS, and 67 characters per part thereafter.</li>
                        <li><strong>Placeholder Tags:</strong> Real character length is calculated based on customer values (e.g. `{name}` replaced by actual customer name).</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- REALISTIC PHONE SIMULATOR MODAL -->
<div class="modal fade" id="smsSimulatorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 420px;">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="phone-mockup-frame">
                <div class="phone-screen">
                    <div class="phone-header">
                        <div class="small font-weight-bold text-dark"><i class="fa-solid fa-comments text-primary mr-1"></i> {{ config('app.name', 'Looksmen') }} SMS</div>
                        <small class="text-muted" style="font-size: 0.7rem;">Message Simulator</small>
                    </div>
                    <div class="phone-chat-area">
                        <div class="sms-bubble">
                            <span id="simulatorSmsBody">Type your message to see a live simulation...</span>
                            <div class="sms-time" id="simulatorSmsTime">Just now</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <button type="button" class="btn btn-light rounded-pill px-4 shadow-sm" data-dismiss="modal">Close Simulator</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        if ($('.select2').length > 0) {
            $('.select2').select2({
                placeholder: "-- Search & Select Customer --",
                allowClear: true
            });
        }

        const initialTarget = $('input[name="recipient_type"]:checked').val() || 'user';
        toggleSmsTargetType(initialTarget);
        updateSmsMetrics();

        $('#customSmsForm').on('submit', function() {
            const btn = $('#btnSubmitSms');
            btn.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Sending SMS...');
            btn.prop('disabled', true);
        });
    });

    /**
     * Toggle SMS Target Input Containers
     */
    function toggleSmsTargetType(type) {
        $('.target-sms-container').addClass('d-none');
        if (type === 'user') {
            $('#smsTargetUserContainer').removeClass('d-none');
        } else if (type === 'phone') {
            $('#smsTargetPhoneContainer').removeClass('d-none');
        } else if (type === 'multiple') {
            $('#smsTargetMultipleContainer').removeClass('d-none');
        } else if (type === 'all') {
            $('#smsTargetAllContainer').removeClass('d-none');
        }
    }

    /**
     * Real-Time SMS Metrics Counter Engine
     */
    function updateSmsMetrics() {
        const text = $('#smsContentTextarea').val() || '';
        const charCount = text.length;

        // Detect Unicode (Bangla, Emojis, Non-ASCII)
        const isUnicode = /[^\u0000-\u00ff]/.test(text);
        
        let smsCount = 1;
        let remaining = 0;
        let encoding = isUnicode ? 'Unicode' : 'GSM 7-Bit';

        if (charCount === 0) {
            smsCount = 1;
            remaining = isUnicode ? 70 : 160;
        } else if (!isUnicode) {
            if (charCount <= 160) {
                smsCount = 1;
                remaining = 160 - charCount;
            } else {
                smsCount = Math.ceil(charCount / 153);
                remaining = (smsCount * 153) - charCount;
            }
        } else {
            if (charCount <= 70) {
                smsCount = 1;
                remaining = 70 - charCount;
            } else {
                smsCount = Math.ceil(charCount / 67);
                remaining = (smsCount * 67) - charCount;
            }
        }

        $('#metricCharCount').text(charCount);
        $('#metricSmsCount').text(smsCount);
        $('#metricRemaining').text(remaining);
        $('#metricEncoding').text(encoding);
        if (isUnicode) {
            $('#metricEncoding').removeClass('text-success').addClass('text-warning');
        } else {
            $('#metricEncoding').removeClass('text-warning').addClass('text-success');
        }
    }

    /**
     * Apply SMS Quick Preset
     */
    function applySmsPreset(presetText) {
        $('#smsContentTextarea').val(presetText);
        updateSmsMetrics();
        Toast.fire({
            icon: 'info',
            title: 'SMS Preset loaded!'
        });
    }

    /**
     * Insert Dynamic Tag Chip into SMS Textarea
     */
    function insertSmsTag(tag) {
        const textarea = $('#smsContentTextarea');
        const currentVal = textarea.val();
        textarea.val(currentVal + ' ' + tag + ' ');
        updateSmsMetrics();
        Toast.fire({
            icon: 'success',
            title: 'Inserted: ' + tag
        });
    }

    /**
     * Clear SMS Composer Form
     */
    function resetSmsComposerForm() {
        Swal.fire({
            title: 'Reset SMS Form?',
            text: "Are you sure you want to clear the message text?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, clear it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#customSmsForm')[0].reset();
                if ($('#sms_user_id_select').length > 0) {
                    $('#sms_user_id_select').val('').trigger('change');
                }
                toggleSmsTargetType('user');
                updateSmsMetrics();
                Toast.fire({
                    icon: 'info',
                    title: 'Form cleared.'
                });
            }
        });
    }

    /**
     * Load SMS Templates
     */
    function loadSmsTemplate(type) {
        let msg = '';
        if (type === 'promo') {
            msg = "🎉 Exclusive Offer: Get 15% OFF on your next order at {site_name}! Use coupon code SAVE15 at checkout. Shop now: {site_url}";
        } else if (type === 'order') {
            msg = "📢 Hello {name}, your order has been received & confirmed at {site_name}. Thank you for shopping with us! View details: {site_url}";
        } else if (type === 'delivery') {
            msg = "🚚 Out for Delivery: Hello {name}, your parcel from {site_name} is on its way to your address today!";
        } else if (type === 'welcome') {
            msg = "👋 Hello {name}, welcome to {site_name}! We are excited to serve you. Visit our store anytime at {site_url}";
        }

        $('#smsContentTextarea').val(msg);
        updateSmsMetrics();
        Toast.fire({
            icon: 'success',
            title: 'Template loaded!'
        });
    }

    /**
     * Open Realistic Phone Simulator Modal
     */
    function openSmsSimulatorPreview() {
        let text = $('#smsContentTextarea').val() || 'Type your message in the composer...';

        text = text.replace(/{name}/g, 'John Doe')
                   .replace(/{phone}/g, '01712345678')
                   .replace(/{site_name}/g, '{{ config("app.name", "Looksmen") }}')
                   .replace(/{site_url}/g, '{{ url("/") }}')
                   .replace(/{date}/g, '{{ date("d M, Y") }}');

        $('#simulatorSmsBody').text(text);
        const now = new Date();
        $('#simulatorSmsTime').text(now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
        $('#smsSimulatorModal').modal('show');
    }
</script>
@endsection
