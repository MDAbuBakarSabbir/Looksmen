@extends('layouts.Backend.master')
@section('title')
    SMTP SETTINGS
@endsection
@section('content')
    <style>
        .settings-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.05);
        }
        .guide-box {
            background-color: rgba(99, 102, 241, 0.04);
            border: 1px dashed rgba(99, 102, 241, 0.2);
            border-radius: 10px;
            padding: 20px;
            color: #4b5563;
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
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-submit-custom:hover {
            opacity: 0.95;
        }
    </style>

    <div class="row justify-content-center">
        @if ($featuresConfig['email_verification'] == '1')
            <div class="col-lg-7 mb-4">
                <div class="settings-card card border-0">
                    <div class="card-header bg-white border-bottom border-light p-4">
                        <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-envelope text-primary mr-2"></i>Mail SMTP Settings</h4>
                    </div>
                    <div class="card-body p-4">
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
                                            TLS (Usually port 587)
                                        </label>
                                    </div>
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" type="radio" name="mailencription" id="encryptionSSL" value="ssl" {{ (!isset($smtpSettings['mailencription']) || $smtpSettings['mailencription'] == 'ssl') ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-bold text-dark" for="encryptionSSL" style="cursor: pointer;">
                                            SSL (Usually port 465)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-submit-custom btn-block">
                                <i class="fa-solid fa-circle-check mr-2"></i>Save Configuration
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="settings-card card border-0">
                    <div class="card-header bg-white border-bottom border-light p-4">
                        <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-circle-info text-info mr-2"></i>Configuration Guide</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="guide-box mb-4">
                            <h5 class="font-weight-bold mb-3" style="color: #4f46e5;"><i class="fa-solid fa-circle-play mr-2"></i>Getting Started</h5>
                            <p style="font-size: 13px; line-height: 1.6;">SMTP stands for Simple Mail Transfer Protocol. Configure SMTP settings to send verification emails, notifications, and reset password links dynamically from this application.</p>
                        </div>

                        <h6 class="font-weight-bold text-dark mb-2">Recommended Settings for Popular Providers:</h6>
                        <ul class="pl-3" style="font-size: 13px; color: #4b5563; line-height: 2;">
                            <li><strong>Gmail:</strong> Host: <code>smtp.gmail.com</code> | Port: <code>465</code> (SSL) or <code>587</code> (TLS)</li>
                            <li><strong>Outlook:</strong> Host: <code>smtp-mail.outlook.com</code> | Port: <code>587</code> (TLS)</li>
                            <li><strong>Mailtrap (Testing):</strong> Host: <code>sandbox.smtp.mailtrap.io</code> | Port: <code>2525</code></li>
                        </ul>
                        <div class="alert alert-warning p-3 mt-3 border-0" style="border-radius: 8px; font-size: 13px;">
                            <i class="fa-solid fa-triangle-exclamation mr-2 text-warning"></i>
                            <strong>Important:</strong> If using Gmail, make sure you configure and use an <strong>App Password</strong> instead of your regular account password.
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-lg-8 text-center py-5">
                <div class="card border-0 shadow-sm p-5" style="border-radius: 12px;">
                    <i class="fa-solid fa-envelope-circle-check text-muted mb-4" style="font-size: 64px; opacity: 0.4;"></i>
                    <h3 class="font-weight-bold text-dark mb-3">Email Feature is Disabled</h3>
                    <p class="text-muted mb-4" style="font-size: 15px; max-width: 500px; margin: 0 auto;">To configure SMTP, please make sure the email verification feature is turned on in the Activation configs panel.</p>
                    <a href="{{ route('feature.index') }}" class="btn btn-primary d-inline-block mx-auto" style="border-radius: 8px; font-weight: 600; padding: 10px 24px; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">
                        Go to Feature Activation
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
