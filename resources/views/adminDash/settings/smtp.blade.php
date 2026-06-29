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
@endsection
