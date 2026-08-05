@extends('layouts.Backend.master')
@section('title')
    SMTP & SMS SETTINGS
@endsection
@section('content')
    <style>
        .settings-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.05);
            height: 100%;
        }
        .form-label-custom {
            font-size: 13px;
            font-weight: 700;
            color: #4b5563;
            margin-bottom: 6px;
        }
        .form-control-custom {
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            border: 1px solid #d1d5db;
            color: #1f2937;
            transition: all 0.2s ease;
        }
        .form-control-custom:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        .btn-submit-custom {
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            transition: opacity 0.2s;
            color: #fff;
        }
        .btn-submit-custom:hover {
            opacity: 0.95;
        }

        /* Template Redesign CSS */
        .template-item {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .template-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            transform: translateY(-2px);
        }
        .template-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .template-title {
            font-size: 14px;
            font-weight: 700;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .textarea-custom {
            width: 100%;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 14px;
            border: 1px solid #cbd5e1;
            color: #1f2937;
            resize: vertical;
            min-height: 110px;
            transition: all 0.2s ease;
            background-color: #f8fafc;
        }
        .textarea-custom:focus {
            background-color: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        .template-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
        }
        .btn-save-template {
            font-size: 13px;
            font-weight: 600;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
        }
        .btn-save-template:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 10px -1px rgba(99, 102, 241, 0.3);
            color: #ffffff;
        }
        .btn-save-template:active {
            transform: translateY(0);
        }
        .btn-save-template-sms {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }
        .btn-save-template-sms:hover {
            box-shadow: 0 6px 10px -1px rgba(16, 185, 129, 0.3);
            color: #ffffff;
        }
        
        /* Modern Switch Styles */
        .switch-custom {
            position: relative;
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
            margin-bottom: 0;
            gap: 8px;
        }
        .switch-custom input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .switch-slider {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
            background-color: #cbd5e1;
            border-radius: 34px;
            transition: background-color 0.2s ease;
        }
        .switch-slider::before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }
        .switch-custom input:checked + .switch-slider {
            background-color: #6366f1;
        }
        .switch-custom input:checked + .switch-slider-sms {
            background-color: #10b981;
        }
        .switch-custom input:checked + .switch-slider::before {
            transform: translateX(18px);
        }
        .switch-text {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
        }
    </style>

    <div class="row">
        {{-- Email SMTP Section --}}
        <div class="col-lg-6 mb-4">
            <div class="settings-card card border-0">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-envelope text-primary mr-2"></i>Mail SMTP Settings</h4>
                    @if ($featuresConfig['email_verification'] != '1')
                        <span class="badge badge-warning">Disabled in Config</span>
                    @else
                        <span class="badge badge-success">Active</span>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if ($featuresConfig['email_verification'] == '1')
                        <form action="{{ route('smtp.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom">Mail Host</label>
                                    <input type="text" class="form-control form-control-custom" name="mailhost" placeholder="e.g. smtp.gmail.com" value="{{ $smtpSettings['mailhost'] ?? '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom">Mail Port</label>
                                    <input type="text" class="form-control form-control-custom" name="mailport" placeholder="e.g. 465 or 587" value="{{ $smtpSettings['mailport'] ?? '' }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom">Mail Username</label>
                                    <input type="text" class="form-control form-control-custom" name="mailusername" placeholder="e.g. username@gmail.com" value="{{ $smtpSettings['mailusername'] ?? '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom">Mail Password</label>
                                    <input type="password" class="form-control form-control-custom" name="mailpassword" placeholder="••••••••" value="{{ $smtpSettings['mailpassword'] ?? '' }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Mail From Address</label>
                                <input type="email" class="form-control form-control-custom" name="mailaddress" placeholder="e.g. no-reply@yourdomain.com" value="{{ $smtpSettings['mailaddress'] ?? '' }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Mail Encryption</label>
                                <div class="d-flex mt-1">
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" type="radio" name="mailencription" id="encryptionTLS" value="tls" {{ (isset($smtpSettings['mailencription']) && $smtpSettings['mailencription'] == 'tls') ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-bold text-dark" for="encryptionTLS" style="cursor: pointer;">
                                            TLS
                                        </label>
                                    </div>
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" type="radio" name="mailencription" id="encryptionSSL" value="ssl" {{ (!isset($smtpSettings['mailencription']) || $smtpSettings['mailencription'] == 'ssl') ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-bold text-dark" for="encryptionSSL" style="cursor: pointer;">
                                            SSL
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-submit-custom btn-block" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
                                <i class="fa-solid fa-circle-check mr-2"></i>Save SMTP Configuration
                            </button>
                        </form>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-envelope-open-text mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                            <p class="mb-2 font-weight-bold">Email SMTP is Disabled</p>
                            <a href="{{ route('feature.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 6px;">Enable in Feature Activation</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- SMS Gateway Section --}}
        <div class="col-lg-6 mb-4">
            <div class="settings-card card border-0">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-comment-sms text-success mr-2"></i>SMS Gateway Settings</h4>
                    @if ($featuresConfig['sms_verification'] != '1')
                        <span class="badge badge-warning">Disabled in Config</span>
                    @else
                        <span class="badge badge-success">Active</span>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if ($featuresConfig['sms_verification'] == '1')
                        <form action="{{ route('sms.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label-custom">SMS Gateway Provider</label>
                                <select class="form-control form-control-custom" name="sms_gateway_provider">
                                    <option value="steadfast" {{ (isset($smtpSettings['sms_gateway_provider']) && $smtpSettings['sms_gateway_provider'] == 'steadfast') ? 'selected' : '' }}>Steadfast SMS (Recommended)</option>
                                    <option value="greenweb" {{ (isset($smtpSettings['sms_gateway_provider']) && $smtpSettings['sms_gateway_provider'] == 'greenweb') ? 'selected' : '' }}>Greenweb SMS</option>
                                    <option value="bulksmsbd" {{ (isset($smtpSettings['sms_gateway_provider']) && $smtpSettings['sms_gateway_provider'] == 'bulksmsbd') ? 'selected' : '' }}>BulkSMS BD</option>
                                    <option value="mimsms" {{ (isset($smtpSettings['sms_gateway_provider']) && $smtpSettings['sms_gateway_provider'] == 'mimsms') ? 'selected' : '' }}>Mim SMS</option>
                                    <option value="other" {{ (isset($smtpSettings['sms_gateway_provider']) && $smtpSettings['sms_gateway_provider'] == 'other') ? 'selected' : '' }}>Other HTTP API Gateway</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">API Key / Token</label>
                                <input type="password" class="form-control form-control-custom" name="sms_api_key" placeholder="Enter API Access Token" value="{{ $smtpSettings['sms_api_key'] ?? '' }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-custom">Sender ID (Masking / Client ID)</label>
                                <input type="text" class="form-control form-control-custom" name="sms_sender_id" placeholder="e.g. 8809612... or BRANDNAME" value="{{ $smtpSettings['sms_sender_id'] ?? '' }}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">API Gateway Endpoint URL</label>
                                <input type="text" class="form-control form-control-custom" name="sms_api_url" placeholder="e.g. https://api.smsprovider.com/send" value="{{ $smtpSettings['sms_api_url'] ?? '' }}">
                            </div>

                            <button type="submit" class="btn btn-submit-custom btn-block" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fa-solid fa-circle-check mr-2"></i>Save SMS Configuration
                            </button>
                        </form>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-sms mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                            <p class="mb-2 font-weight-bold">SMS Verification is Disabled</p>
                            <a href="{{ route('feature.index') }}" class="btn btn-sm btn-outline-success" style="border-radius: 6px;">Enable in Feature Activation</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- Mail Templates Section --}}
        <div class="col-lg-6 mb-4">
            <div class="settings-card card border-0">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-envelope-open-text text-primary mr-2"></i>Mail Templates</h4>
                </div>
                <div class="card-body p-4">
                    {{-- Welcome Mail --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-handshake text-indigo"></i> 
                                Welcome Mail Template
                            </span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="welcomeMail" id="welcomeMailCheckbox" {{ (isset($smtpSettings['welcome_mail_active']) && $smtpSettings['welcome_mail_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="welcomeMail" id="welcomeMail" class="textarea-custom" placeholder="Write welcome email content...">{{ $smtpSettings['welcome_mail_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>

                    {{-- Verification Mail --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-user-check text-indigo"></i> Verification Mail Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="verificationMail" id="verificationMailCheckbox" {{ (isset($smtpSettings['verification_mail_active']) && $smtpSettings['verification_mail_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="verificationMail" id="verificationMail" class="textarea-custom" placeholder="Write verification email content...">{{ $smtpSettings['verification_mail_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>

                    {{-- OTP Mail --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-key text-indigo"></i> OTP Mail Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="otpMail" id="otpMailCheckbox" {{ (isset($smtpSettings['otp_mail_active']) && $smtpSettings['otp_mail_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="otpMail" id="otpMail" class="textarea-custom" placeholder="Write OTP email content...">{{ $smtpSettings['otp_mail_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>

                    {{-- Order Confirmation Mail --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-cart-check text-indigo"></i> Order Confirmation Mail Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="orderConfirmationMail" id="orderConfirmationMailCheckbox" {{ (isset($smtpSettings['order_confirmation_mail_active']) && $smtpSettings['order_confirmation_mail_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="orderConfirmationMail" id="orderConfirmationMail" class="textarea-custom" placeholder="Write order confirmation email content...">{{ $smtpSettings['order_confirmation_mail_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>

                    {{-- Order Cancelled Mail --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-cart-xmark text-indigo"></i> Order Cancelled Mail Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="orderCancelMail" id="orderCancelMailCheckbox" {{ (isset($smtpSettings['order_cancel_mail_active']) && $smtpSettings['order_cancel_mail_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="orderCancelMail" id="orderCancelMail" class="textarea-custom" placeholder="Write order cancelled email content...">{{ $smtpSettings['order_cancel_mail_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>

                    {{-- Order Delivered Mail --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-truck-ramp-box text-indigo"></i> Order Delivered Mail Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="orderDeliveredMail" id="orderDeliveredMailCheckbox" {{ (isset($smtpSettings['order_delivered_mail_active']) && $smtpSettings['order_delivered_mail_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="orderDeliveredMail" id="orderDeliveredMail" class="textarea-custom" placeholder="Write order delivered email content...">{{ $smtpSettings['order_delivered_mail_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SMS Templates Section --}}
        <div class="col-lg-6 mb-4">
            <div class="settings-card card border-0">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-message-sms text-success mr-2"></i>SMS Templates</h4>
                </div>
                <div class="card-body p-4">
                    {{-- Welcome SMS --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-comment-dots text-emerald"></i> Welcome SMS Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="welcomeSms" id="welcomeSmsCheckbox" {{ (isset($smtpSettings['welcome_sms_active']) && $smtpSettings['welcome_sms_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider switch-slider-sms"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="welcomeSms" id="welcomeSms" class="textarea-custom" placeholder="Write welcome SMS content...">{{ $smtpSettings['welcome_sms_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template btn-save-template-sms"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>

                    {{-- Order Confirmation SMS --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-comment-check text-emerald"></i> Order Confirmation SMS Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="orderConfirmationSms" id="orderConfirmationSmsCheckbox" {{ (isset($smtpSettings['order_confirmation_sms_active']) && $smtpSettings['order_confirmation_sms_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider switch-slider-sms"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="orderConfirmationSms" id="orderConfirmationSms" class="textarea-custom" placeholder="Write order confirmation SMS content...">{{ $smtpSettings['order_confirmation_sms_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template btn-save-template-sms"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>

                    {{-- OTP SMS --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-shield-keyhole text-emerald"></i> OTP SMS Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="otpSms" id="otpSmsCheckbox" {{ (isset($smtpSettings['otp_sms_active']) && $smtpSettings['otp_sms_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider switch-slider-sms"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="otpSms" id="otpSms" class="textarea-custom" placeholder="Write OTP SMS content...">{{ $smtpSettings['otp_sms_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template btn-save-template-sms"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>

                    {{-- Order Cancel SMS --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-comment-xmark text-emerald"></i> Order Cancel SMS Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="orderCancelSms" id="orderCancelSmsCheckbox" {{ (isset($smtpSettings['order_cancel_sms_active']) && $smtpSettings['order_cancel_sms_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider switch-slider-sms"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="orderCancelSms" id="orderCancelSms" class="textarea-custom" placeholder="Write order cancel SMS content...">{{ $smtpSettings['order_cancel_sms_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template btn-save-template-sms"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>

                    {{-- Order Delivered SMS --}}
                    <div class="template-item">
                        <div class="template-header">
                            <span class="template-title"><i class="fa-solid fa-truck-fast text-emerald"></i> Order Delivered SMS Template</span>
                            <label class="switch-custom mb-0">
                                <input type="checkbox" name="orderDeliveredSms" id="orderDeliveredSmsCheckbox" {{ (isset($smtpSettings['order_delivered_sms_active']) && $smtpSettings['order_delivered_sms_active'] == '1') ? 'checked' : '' }}>
                                <span class="switch-slider switch-slider-sms"></span>
                                <span class="switch-text">Active</span>
                            </label>
                        </div>
                        <textarea name="orderDeliveredSms" id="orderDeliveredSms" class="textarea-custom" placeholder="Write order delivered SMS content...">{{ $smtpSettings['order_delivered_sms_template'] ?? '' }}</textarea>
                        <div class="template-footer">
                            <button type="button" class="btn-save-template btn-save-template-sms"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function notifyToast(type, message) {
            if (typeof AIZ !== 'undefined' && AIZ.plugins && typeof AIZ.plugins.notify === 'function') {
                AIZ.plugins.notify(type === 'error' ? 'danger' : 'success', message);
            } else if (typeof Toast !== 'undefined' && typeof Toast.fire === 'function') {
                Toast.fire({
                    icon: type,
                    title: message
                });
            } else if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: type,
                    title: message,
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true
                });
            } else {
                alert(message);
            }
        }

        function performSaveTemplate(template_name, active, body, callback) {
            $.post('{{ route('smtp.template.save') }}', {
                _token: '{{ csrf_token() }}',
                name: template_name,
                active: active,
                body: body
            }, function(res) {
                if (callback) callback(res);
            }).fail(function() {
                if (callback) callback({ success: false, message: 'Server error occurred.' });
            });
        }

        $(document).ready(function() {
            // Instant Switch Toggle Event
            $(document).on('change', '.switch-custom input[type="checkbox"]', function() {
                let checkbox = $(this);
                let card = checkbox.closest('.template-item');
                let textarea = card.find('textarea');
                
                let template_name = checkbox.attr('name');
                let active = checkbox.is(':checked') ? '1' : '0';
                let body = textarea.val();

                let readableName = template_name.replace(/([A-Z])/g, ' $1');
                readableName = readableName.charAt(0).toUpperCase() + readableName.slice(1);

                performSaveTemplate(template_name, active, body, function(res) {
                    if (res.success) {
                        let statusText = active === '1' ? 'Enabled' : 'Disabled';
                        notifyToast('success', readableName + ' status ' + statusText);
                    } else {
                        checkbox.prop('checked', active !== '1');
                        notifyToast('error', 'Failed to update ' + readableName + ' status.');
                    }
                });
            });

            // Save Template Button Click Event
            $(document).on('click', '.btn-save-template', function() {
                let btn = $(this);
                let card = btn.closest('.template-item');
                let checkbox = card.find('input[type="checkbox"]');
                let textarea = card.find('textarea');
                
                let template_name = checkbox.attr('name');
                let active = checkbox.is(':checked') ? '1' : '0';
                let body = textarea.val();

                let readableName = template_name.replace(/([A-Z])/g, ' $1');
                readableName = readableName.charAt(0).toUpperCase() + readableName.slice(1);

                let originalHtml = btn.html();
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...');

                performSaveTemplate(template_name, active, body, function(res) {
                    btn.prop('disabled', false).html(originalHtml);
                    if (res.success) {
                        notifyToast('success', readableName + ' template saved successfully!');
                    } else {
                        notifyToast('error', 'Failed to save ' + readableName + ' template.');
                    }
                });
            });
        });
    </script>
@endsection
