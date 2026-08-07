@extends('layouts.Frontend.master')

@php
    $webConfig = \Illuminate\Support\Facades\Cache::rememberForever('boot_general_web_settings_map', function () {
        return \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
    });
    $storeName = !empty($webConfig['web_name']) ? $webConfig['web_name'] : 'Looksmen';

    $activeMode = $mode ?? request()->get('tab', 'login');
    if ($errors->has('name') || $errors->has('password_confirmation') || $errors->has('terms')) {
        $activeMode = 'register';
    }
@endphp

@section('title')
    {{ $activeMode === 'register' ? 'CREATE ACCOUNT' : ($activeMode === 'forgot' ? 'RESET PASSWORD' : 'CUSTOMER LOGIN') }}
@endsection

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .auth-section-wrapper {
        font-family: 'Outfit', sans-serif !important;
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3.5rem 1rem;
        background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 50%, #e0e7ff 100%);
    }

    .auth-card-container {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.09), 0 10px 20px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        width: 100%;
        max-width: 1000px;
    }

    .auth-grid {
        display: flex;
        flex-wrap: wrap;
    }

    /* Left Hero Column */
    .auth-hero-panel {
        flex: 1 1 420px;
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 60%, #3730a3 100%);
        padding: 3.5rem 3rem;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        min-height: 540px;
    }

    .auth-hero-panel::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -30%;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-brand-title {
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #ffffff;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .hero-brand-sub {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .hero-feature-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        padding: 1.1rem 1.25rem;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        margin-bottom: 1.1rem;
        display: flex;
        align-items: center;
        transition: transform 0.2s ease, background 0.2s ease;
    }
    .hero-feature-box:hover {
        background: rgba(255, 255, 255, 0.16);
        transform: translateX(4px);
    }
    .hero-feature-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #ffffff;
        margin-right: 1.1rem;
        flex-shrink: 0;
    }
    .hero-feature-title {
        font-weight: 700;
        font-size: 0.98rem;
        color: #ffffff;
        margin-bottom: 2px;
    }
    .hero-feature-desc {
        font-size: 0.82rem;
        color: rgba(255, 255, 255, 0.75);
    }

    .hero-footer-note {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        padding-top: 1.25rem;
        margin-top: 1.5rem;
        line-height: 1.5;
    }

    /* Right Form Column */
    .auth-form-panel {
        flex: 1 1 460px;
        padding: 3.5rem 3.25rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #ffffff;
    }

    /* Tab Switcher Pills */
    .auth-tabs-header {
        display: flex;
        background: #f1f5f9;
        padding: 6px;
        border-radius: 14px;
        margin-bottom: 2.25rem;
        border: 1px solid #e2e8f0;
    }
    .auth-tab-item {
        flex: 1;
        text-align: center;
        padding: 0.75rem 1rem;
        font-weight: 700;
        font-size: 0.95rem;
        border-radius: 10px;
        color: #64748b;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .auth-tab-item.active {
        background: #ffffff;
        color: #4f46e5;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
    }

    /* Form Elements */
    .form-group-custom {
        margin-bottom: 1.35rem;
    }
    .form-label-custom {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1e293b;
        margin-bottom: 0.45rem;
        display: block;
    }
    .input-box-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-box-icon {
        position: absolute;
        left: 1.1rem;
        color: #94a3b8;
        font-size: 1.1rem;
        pointer-events: none;
        transition: color 0.2s ease;
    }
    .auth-input-control {
        width: 100%;
        height: 50px;
        padding: 0 2.8rem 0 2.9rem;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-size: 0.96rem;
        background: #f8fafc;
        color: #0f172a;
        transition: all 0.2s ease;
    }
    .auth-input-control:focus {
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        outline: none;
    }
    .auth-input-control:focus ~ .input-box-icon {
        color: #6366f1;
    }
    .eye-toggle-btn {
        position: absolute;
        right: 1rem;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }
    .eye-toggle-btn:hover {
        color: #4f46e5;
    }

    /* Checkbox & Options */
    .auth-options-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.25rem;
        margin-bottom: 1.75rem;
        font-size: 0.9rem;
    }
    .checkbox-label-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        cursor: pointer;
        color: #475569;
        margin-bottom: 0;
        user-select: none;
    }
    .checkbox-label-custom input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #6366f1;
        cursor: pointer;
        margin: 0;
    }

    .btn-submit-action {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        border-radius: 12px;
        height: 52px;
        width: 100%;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-submit-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
        color: #ffffff;
    }

    .auth-bottom-switch {
        text-align: center;
        margin-top: 1.75rem;
        color: #64748b;
        font-size: 0.92rem;
    }

    .invalid-feedback-custom {
        color: #ef4444;
        font-size: 0.83rem;
        margin-top: 5px;
        display: block;
        font-weight: 500;
    }

    .alert-banner-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 0.85rem 1.1rem;
        border-radius: 12px;
        font-size: 0.88rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    @media (max-width: 991px) {
        .auth-hero-panel {
            display: none;
        }
        .auth-form-panel {
            padding: 2.75rem 2rem;
        }
    }
    @media (max-width: 576px) {
        .auth-form-panel {
            padding: 2rem 1.25rem;
        }
    }
</style>

<div class="auth-section-wrapper">
    <div class="auth-card-container">
        <div class="auth-grid">
            
            <!-- LEFT HERO BRANDING PANEL -->
            <div class="auth-hero-panel">
                <div>
                    <h1 class="hero-brand-title">{{ $storeName }}</h1>
                    <p class="hero-brand-sub">
                        Welcome to our official store! Sign in to access your personal dashboard, track shipments, and redeem exclusive rewards.
                    </p>
                </div>

                <div>
                    <div class="hero-feature-box">
                        <div class="hero-feature-icon">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div>
                            <div class="hero-feature-title">Fast Nationwide Shipping</div>
                            <div class="hero-feature-desc">Real-time parcel tracking and courier updates</div>
                        </div>
                    </div>

                    <div class="hero-feature-box">
                        <div class="hero-feature-icon">
                            <i class="fa-solid fa-gift"></i>
                        </div>
                        <div>
                            <div class="hero-feature-title">Wallet & Points Cashback</div>
                            <div class="hero-feature-desc">Earn points on every order and convert to wallet balance</div>
                        </div>
                    </div>

                    <div class="hero-feature-box">
                        <div class="hero-feature-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <div class="hero-feature-title">100% Encrypted Security</div>
                            <div class="hero-feature-desc">Protected customer data and instant checkout</div>
                        </div>
                    </div>
                </div>

                <div class="hero-footer-note">
                    Need support? Our customer service team is available 24/7 to assist with your order.
                </div>
            </div>

            <!-- RIGHT FORM PANEL -->
            <div class="auth-form-panel">
                
                <!-- TAB SWITCHER HEADER -->
                <div class="auth-tabs-header {{ $activeMode === 'forgot' ? 'd-none' : '' }}">
                    <button class="auth-tab-item {{ $activeMode !== 'register' ? 'active' : '' }}" onclick="switchAuthTab('login')">
                        <i class="fa-solid fa-right-to-bracket"></i> Sign In
                    </button>
                    <button class="auth-tab-item {{ $activeMode === 'register' ? 'active' : '' }}" onclick="switchAuthTab('register')">
                        <i class="fa-solid fa-user-plus"></i> Create Account
                    </button>
                </div>

                <!-- LOGIN FORM PANEL -->
                <div id="loginFormPanel" class="{{ $activeMode !== 'login' ? 'd-none' : '' }}">
                    <div class="mb-4">
                        <h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.6rem; letter-spacing: -0.3px;">Welcome Back!</h2>
                        <p class="text-muted" style="font-size: 0.92rem;">Sign in with your email address to continue shopping.</p>
                    </div>

                    @if(session('error'))
                        <div class="alert-banner-error">
                            <i class="fa-solid fa-circle-exclamation text-danger fa-lg"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" id="formLoginSubmit">
                        @csrf

                        <div class="form-group-custom">
                            <label class="form-label-custom" for="login_email">Email Address <span class="text-danger">*</span></label>
                            <div class="input-box-wrapper">
                                <input type="email" name="email" id="login_email" class="auth-input-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="e.g. customer@example.com" required autofocus autocomplete="username">
                                <i class="fa-solid fa-envelope input-box-icon"></i>
                            </div>
                            @error('email')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-custom" for="login_password">Password <span class="text-danger">*</span></label>
                            <div class="input-box-wrapper">
                                <input type="password" name="password" id="login_password" class="auth-input-control @error('password') is-invalid @enderror" placeholder="••••••••" required autocomplete="current-password">
                                <i class="fa-solid fa-lock input-box-icon"></i>
                                <button type="button" class="eye-toggle-btn" onclick="togglePasswordVisibility('login_password', this)" title="Show/Hide Password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="auth-options-row">
                            <label class="checkbox-label-custom">
                                <input type="checkbox" name="remember" id="remember_me">
                                <span>Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-primary font-weight-bold" style="text-decoration: none;">Forgot Password?</a>
                            @endif
                        </div>

                        <button type="submit" id="btnLoginBtn" class="btn-submit-action">
                            <i class="fa-solid fa-right-to-bracket mr-1"></i> Sign In to Account
                        </button>
                    </form>

                    <div class="auth-bottom-switch">
                        Don't have an account? <a href="javascript:void(0)" onclick="switchAuthTab('register')" class="text-primary font-weight-bold ml-1">Create one now</a>
                    </div>
                </div>

                <!-- REGISTER FORM PANEL -->
                <div id="registerFormPanel" class="{{ $activeMode !== 'register' ? 'd-none' : '' }}">
                    <div class="mb-4">
                        <h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.6rem; letter-spacing: -0.3px;">Create Your Free Account</h2>
                        <p class="text-muted" style="font-size: 0.92rem;">Fill in your details to register as a new customer.</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST" id="formRegisterSubmit">
                        @csrf

                        <div class="form-group-custom">
                            <label class="form-label-custom" for="reg_name">Full Name <span class="text-danger">*</span></label>
                            <div class="input-box-wrapper">
                                <input type="text" name="name" id="reg_name" class="auth-input-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. John Doe" required autocomplete="name">
                                <i class="fa-solid fa-user input-box-icon"></i>
                            </div>
                            @error('name')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-custom" for="reg_email">Email Address <span class="text-danger">*</span></label>
                            <div class="input-box-wrapper">
                                <input type="email" name="email" id="reg_email" class="auth-input-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="e.g. john@example.com" required autocomplete="username">
                                <i class="fa-solid fa-envelope input-box-icon"></i>
                            </div>
                            @error('email')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-custom" for="reg_password">Password <span class="text-danger">*</span></label>
                            <div class="input-box-wrapper">
                                <input type="password" name="password" id="reg_password" class="auth-input-control @error('password') is-invalid @enderror" placeholder="At least 8 characters" required autocomplete="new-password">
                                <i class="fa-solid fa-lock input-box-icon"></i>
                                <button type="button" class="eye-toggle-btn" onclick="togglePasswordVisibility('reg_password', this)" title="Show/Hide Password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-custom" for="reg_password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-box-wrapper">
                                <input type="password" name="password_confirmation" id="reg_password_confirmation" class="auth-input-control" placeholder="Re-enter your password" required autocomplete="new-password">
                                <i class="fa-solid fa-shield-check input-box-icon"></i>
                                <button type="button" class="eye-toggle-btn" onclick="togglePasswordVisibility('reg_password_confirmation', this)" title="Show/Hide Password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="checkbox-label-custom">
                                <input type="checkbox" name="terms" required checked>
                                <span>I agree to the <a href="#" class="text-primary font-weight-bold" style="text-decoration: underline;">Terms & Conditions</a> and Privacy Policy.</span>
                            </label>
                        </div>

                        <button type="submit" id="btnRegBtn" class="btn-submit-action">
                            <i class="fa-solid fa-user-check mr-1"></i> Create My Account
                        </button>
                    </form>

                    <div class="auth-bottom-switch">
                        Already have an account? <a href="javascript:void(0)" onclick="switchAuthTab('login')" class="text-primary font-weight-bold ml-1">Sign in here</a>
                    </div>
                </div>

                <!-- FORGOT PASSWORD FORM PANEL -->
                <div id="forgotFormPanel" class="{{ $activeMode !== 'forgot' ? 'd-none' : '' }}">
                    <div class="mb-4">
                        <h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.6rem; letter-spacing: -0.3px;">Reset Password</h2>
                        <p class="text-muted" style="font-size: 0.92rem;">Enter your email to receive a password reset link.</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 8px;">
                            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST" id="formForgotSubmit">
                        @csrf

                        <div class="form-group-custom mb-4">
                            <label class="form-label-custom" for="forgot_email">Email Address <span class="text-danger">*</span></label>
                            <div class="input-box-wrapper">
                                <input type="email" name="email" id="forgot_email" class="auth-input-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@gmail.com" required autofocus>
                                <i class="fa-solid fa-envelope input-box-icon"></i>
                            </div>
                            @error('email')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" id="btnForgotBtn" class="btn-submit-action mt-2">
                            <i class="fa-solid fa-paper-plane mr-1"></i> Send Reset Link
                        </button>
                    </form>

                    <div class="auth-bottom-switch mt-4 text-center" style="margin-top: 1.5rem;">
                        Remember your password? <a href="javascript:void(0)" onclick="switchAuthTab('login')" class="text-primary font-weight-bold ml-1">Back to Sign In</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function switchAuthTab(tab) {
        $('.auth-tabs-header').removeClass('d-none');
        $('#forgotFormPanel').addClass('d-none');

        if (tab === 'register') {
            $('#loginFormPanel').addClass('d-none');
            $('#registerFormPanel').removeClass('d-none');
            $('.auth-tab-item').removeClass('active');
            $('.auth-tab-item:contains("Create Account")').addClass('active');
            history.replaceState(null, null, '?tab=register');
        } else {
            $('#registerFormPanel').addClass('d-none');
            $('#loginFormPanel').removeClass('d-none');
            $('.auth-tab-item').removeClass('active');
            $('.auth-tab-item:contains("Sign In")').addClass('active');
            history.replaceState(null, null, '?tab=login');
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    $(document).ready(function() {
        $('#formLoginSubmit').on('submit', function() {
            const btn = $('#btnLoginBtn');
            btn.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Signing In...');
            btn.prop('disabled', true);
        });

        $('#formRegisterSubmit').on('submit', function() {
            const btn = $('#btnRegBtn');
            btn.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Creating Account...');
            btn.prop('disabled', true);
        });

        $('#formForgotSubmit').on('submit', function() {
            const btn = $('#btnForgotBtn');
            btn.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Sending Link...');
            btn.prop('disabled', true);
        });
    });
</script>
@endsection
