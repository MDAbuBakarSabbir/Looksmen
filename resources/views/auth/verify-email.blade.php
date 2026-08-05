@extends('layouts.Frontend.master')
@section('title')
    EMAIL VERIFICATION
@endsection
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --auth-primary: #6366f1;
        --auth-primary-hover: #4f46e5;
        --auth-bg: #f8fafc;
        --auth-surface: #ffffff;
        --auth-border: #e2e8f0;
        --auth-text: #1e293b;
        --auth-muted: #64748b;
        --auth-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        --auth-radius: 20px;
    }

    .verification-page-wrapper {
        font-family: 'Outfit', sans-serif !important;
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
    }

    .verification-card {
        background: var(--auth-surface);
        border: 1px solid var(--auth-border);
        border-radius: var(--auth-radius);
        box-shadow: var(--auth-shadow);
        width: 100%;
        max-width: 520px;
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .verification-icon-badge {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(99, 102, 241, 0.1);
        color: var(--auth-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin-bottom: 1.5rem;
        box-shadow: 0 0 0 8px rgba(99, 102, 241, 0.05);
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
        70% { box-shadow: 0 0 0 14px rgba(99, 102, 241, 0); }
        100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
    }

    /* 6-Digit OTP Inputs */
    .otp-input-group {
        display: flex;
        justify-content: center;
        gap: 0.6rem;
        margin: 1.75rem 0;
    }
    .otp-field {
        width: 50px;
        height: 60px;
        border-radius: 12px;
        border: 2px solid #cbd5e1;
        background: #f8fafc;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        transition: all 0.2s ease;
    }
    .otp-field:focus {
        border-color: var(--auth-primary);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        outline: none;
        transform: translateY(-2px);
    }
    .otp-field.filled {
        border-color: #6366f1;
        background: #eef2ff;
    }

    .btn-verify-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        padding: 0.85rem 2rem;
        width: 100%;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
    }
    .btn-verify-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
        color: #ffffff;
    }

    .status-alert {
        border-radius: 12px;
        padding: 0.85rem 1.25rem;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        text-align: left;
    }
    .status-alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }
    .status-alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alt-tip-box {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 0.85rem 1rem;
        font-size: 0.85rem;
        color: #475569;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--auth-primary);
        text-align: left;
    }
</style>

<div class="verification-page-wrapper">
    <div class="verification-card">

        <!-- Badge Icon -->
        <div class="verification-icon-badge">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h3 class="font-weight-bold text-dark mb-1" style="font-size: 1.6rem;">Verify Your Email</h3>
        <p class="text-muted mb-4" style="font-size: 0.92rem; line-height: 1.5;">
            We have sent a 6-digit verification code to<br>
            <strong class="text-dark">{{ auth()->user()->email ?? 'your email address' }}</strong>
        </p>

        <!-- Status Alerts -->
        @if (session('status') == 'verification-code-sent' || session('status') == 'verification-link-sent')
            <div class="status-alert status-alert-success">
                <i class="fa-solid fa-circle-check mr-2"></i>
                A fresh verification code has been dispatched to your email address!
            </div>
        @endif

        @if (session('success'))
            <div class="status-alert status-alert-success">
                <i class="fa-solid fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->has('otp'))
            <div class="status-alert status-alert-error">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                {{ $errors->first('otp') }}
            </div>
        @endif

        <!-- OTP Verification Form -->
        <form method="POST" action="{{ route('verification.verify.otp') }}" id="otpForm">
            @csrf

            <!-- Hidden aggregated code -->
            <input type="hidden" name="otp" id="otp_full_code">

            <div class="otp-input-group">
                <input type="text" class="otp-field" id="otp1" maxlength="1" pattern="[0-9]*" inputmode="numeric" autofocus autocomplete="off">
                <input type="text" class="otp-field" id="otp2" maxlength="1" pattern="[0-9]*" inputmode="numeric" autocomplete="off">
                <input type="text" class="otp-field" id="otp3" maxlength="1" pattern="[0-9]*" inputmode="numeric" autocomplete="off">
                <input type="text" class="otp-field" id="otp4" maxlength="1" pattern="[0-9]*" inputmode="numeric" autocomplete="off">
                <input type="text" class="otp-field" id="otp5" maxlength="1" pattern="[0-9]*" inputmode="numeric" autocomplete="off">
                <input type="text" class="otp-field" id="otp6" maxlength="1" pattern="[0-9]*" inputmode="numeric" autocomplete="off">
            </div>

            <button type="submit" id="btnVerifySubmit" class="btn-verify-primary mb-3">
                <i class="fa-solid fa-check-double mr-2"></i> Verify & Activate Account
            </button>
        </form>

        <!-- Tip Box -->
        <div class="alt-tip-box">
            <i class="fa-solid fa-circle-info text-primary mr-1"></i>
            <strong>One-Click Option:</strong> You can also verify directly by clicking the <strong>"Verify Email Address"</strong> link in the message sent to your inbox.
        </div>

        <!-- Resend Code & Logout Options -->
        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
            <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                @csrf
                <button type="submit" id="btnResendCode" class="btn btn-link text-primary p-0 font-weight-bold" style="font-size: 0.88rem; text-decoration: none;">
                    <i class="fa-solid fa-rotate-right mr-1"></i> Resend Verification Code
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-muted p-0" style="font-size: 0.88rem; text-decoration: none;">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Log Out
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const otpFields = [
            document.getElementById('otp1'),
            document.getElementById('otp2'),
            document.getElementById('otp3'),
            document.getElementById('otp4'),
            document.getElementById('otp5'),
            document.getElementById('otp6')
        ];
        const fullCodeInput = document.getElementById('otp_full_code');
        const otpForm = document.getElementById('otpForm');

        function updateFullCode() {
            let code = '';
            otpFields.forEach(field => {
                code += field.value;
                if (field.value) {
                    field.classList.add('filled');
                } else {
                    field.classList.remove('filled');
                }
            });
            fullCodeInput.value = code;
        }

        otpFields.forEach((field, index) => {
            field.addEventListener('input', function(e) {
                // Allow numbers only
                this.value = this.value.replace(/[^0-9]/g, '');

                if (this.value && index < otpFields.length - 1) {
                    otpFields[index + 1].focus();
                }
                updateFullCode();

                // Auto submit if all 6 digits entered
                if (fullCodeInput.value.length === 6) {
                    document.getElementById('btnVerifySubmit').innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Verifying...';
                    otpForm.submit();
                }
            });

            field.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpFields[index - 1].focus();
                }
            });

            field.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim().replace(/[^0-9]/g, '');

                if (pasteData.length > 0) {
                    for (let i = 0; i < Math.min(pasteData.length, 6); i++) {
                        otpFields[i].value = pasteData[i];
                    }
                    updateFullCode();

                    if (pasteData.length >= 6) {
                        otpFields[5].focus();
                        document.getElementById('btnVerifySubmit').innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Verifying...';
                        otpForm.submit();
                    } else {
                        otpFields[Math.min(pasteData.length, 5)].focus();
                    }
                }
            });
        });

        // Resend Timer Logic
        const btnResend = document.getElementById('btnResendCode');
        let cooldown = 60;
        let timer = null;

        function startCooldown() {
            btnResend.disabled = true;
            btnResend.style.opacity = '0.6';
            btnResend.style.cursor = 'not-allowed';

            timer = setInterval(() => {
                if (cooldown <= 0) {
                    clearInterval(timer);
                    btnResend.disabled = false;
                    btnResend.style.opacity = '1';
                    btnResend.style.cursor = 'pointer';
                    btnResend.innerHTML = '<i class="fa-solid fa-rotate-right mr-1"></i> Resend Verification Code';
                } else {
                    btnResend.innerHTML = `<i class="fa-solid fa-clock mr-1"></i> Resend Code in ${cooldown}s`;
                    cooldown--;
                }
            }, 1000);
        }

        // Start timer if status is sent
        if ("{{ session('status') }}" === 'verification-code-sent' || "{{ session('status') }}" === 'verification-link-sent') {
            startCooldown();
        }
    });
</script>
@endsection
