@extends('layouts.Frontend.master')
@section('title')
    LOGIN
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
        --auth-radius: 16px;
    }

    body {
        font-family: 'Outfit', sans-serif !important;
        background-color: var(--auth-bg);
    }

    .auth-section {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        padding: 60px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #eef2f6 100%);
    }

    .auth-card {
        background: var(--auth-surface);
        border-radius: var(--auth-radius);
        box-shadow: var(--auth-shadow);
        border: 1px solid rgba(255,255,255,0.8);
        overflow: hidden;
        padding: 40px;
    }

    .auth-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--auth-text);
        margin-bottom: 10px;
        text-align: center;
    }

    .auth-subtitle {
        color: var(--auth-muted);
        text-align: center;
        margin-bottom: 30px;
        font-size: 0.95rem;
    }

    .auth-form-group {
        margin-bottom: 20px;
    }

    .auth-label {
        font-weight: 500;
        color: var(--auth-text);
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }

    .auth-input {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid var(--auth-border);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .auth-input:focus {
        border-color: var(--auth-primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .auth-btn {
        background: var(--auth-primary);
        color: white;
        border: none;
        padding: 14px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        margin-top: 10px;
    }

    .auth-btn:hover {
        background: var(--auth-primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(99,102,241,0.4);
        color: white;
    }

    .auth-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        margin-bottom: 25px;
    }

    .auth-checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        color: var(--auth-muted);
    }

    .auth-checkbox input {
        accent-color: var(--auth-primary);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .auth-link {
        color: var(--auth-primary);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .auth-link:hover {
        color: var(--auth-primary-hover);
        text-decoration: underline;
    }

    .auth-footer {
        text-align: center;
        margin-top: 30px;
        color: var(--auth-muted);
        font-size: 0.95rem;
    }

    .invalid-feedback-custom {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 6px;
        display: block;
    }
</style>

<section class="auth-section">
    <div class="container">
        <div class="row">
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
                <div class="auth-card">
                    <h1 class="auth-title">Welcome Back</h1>
                    <p class="auth-subtitle">Log in to your account to continue.</p>

                    <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="auth-form-group">
                            <label class="auth-label" for="email">Email Address</label>
                            <input type="email" autofocus class="auth-input @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter your email" name="email" id="email" autocomplete="username" required>
                            @error('email')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="auth-form-group">
                            <label class="auth-label" for="password">Password</label>
                            <input type="password" autocomplete="current-password" class="auth-input @error('password') is-invalid @enderror" placeholder="Enter your password" name="password" id="password" required>
                            @error('password')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="auth-options">
                            <label class="auth-checkbox">
                                <input type="checkbox" name="remember" id="remember_me">
                                <span>Remember Me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="auth-btn">Login</button>
                    </form>

                    <div class="auth-footer">
                        Don't have an account? <a href="{{ route('register') }}" class="auth-link">Register Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
