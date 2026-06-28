<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Sign In</title>
    <!-- Import Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-primary: #08090d;
            --bg-secondary: #11131c;
            --glass-bg: rgba(255, 255, 255, 0.02);
            --glass-border: rgba(255, 255, 255, 0.07);
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --accent-glow: rgba(99, 102, 241, 0.15);
            --accent-color: #6366f1;
            --accent-hover: #4f46e5;
            --accent-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --error-color: #f87171;
            --error-bg: rgba(248, 113, 113, 0.08);
            --error-border: rgba(248, 113, 113, 0.2);
            --input-bg: rgba(17, 19, 28, 0.6);
            --input-border: rgba(255, 255, 255, 0.08);
            --input-focus-border: rgba(99, 102, 241, 0.5);
            --input-focus-glow: rgba(99, 102, 241, 0.25);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-primary);
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.08) 0px, transparent 50%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow-x: hidden;
        }

        /* Ambient background glow objects */
        .ambient-orb {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: var(--accent-gradient);
            filter: blur(120px);
            opacity: 0.12;
            z-index: -1;
            pointer-events: none;
            animation: floatOrb 10s ease-in-out infinite alternate;
        }

        .orb-left {
            top: 20%;
            left: 10%;
        }

        .orb-right {
            bottom: 20%;
            right: 10%;
            animation-delay: -5s;
        }

        @keyframes floatOrb {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(30px) scale(1.1); }
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            perspective: 1000px;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 
                0 4px 30px rgba(0, 0, 0, 0.4),
                0 1px 0 rgba(255, 255, 255, 0.1) inset,
                0 0 20px rgba(99, 102, 241, 0.05);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .login-card:hover {
            box-shadow: 
                0 4px 40px rgba(0, 0, 0, 0.5),
                0 1px 0 rgba(255, 255, 255, 0.15) inset,
                0 0 30px rgba(99, 102, 241, 0.08);
        }

        .brand-section {
            text-align: center;
            margin-bottom: 36px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            background: var(--accent-gradient);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .brand-logo svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        .brand-section h1 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            background: linear-gradient(to right, #ffffff, #d1d5db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-section p {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .alert-error {
            background-color: var(--error-bg);
            border: 1px solid var(--error-border);
            color: var(--error-color);
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
        }

        .form-group:focus-within .form-label {
            color: var(--accent-color);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-control {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 14px;
            padding: 14px 16px 14px 44px;
            font-family: inherit;
            color: var(--text-primary);
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-control:focus {
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 4px var(--input-focus-glow);
            background-color: var(--bg-secondary);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: var(--text-secondary);
            pointer-events: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-group:focus-within .input-icon {
            color: var(--accent-color);
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
        }

        .options-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            font-size: 0.85rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }

        .remember-me:hover {
            color: var(--text-primary);
        }

        .checkbox-custom {
            width: 18px;
            height: 18px;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        input[type="checkbox"] {
            display: none;
        }

        input[type="checkbox"]:checked + .remember-me .checkbox-custom {
            background: var(--accent-gradient);
            border-color: transparent;
        }

        .checkbox-custom::after {
            content: "";
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg) translate(-1px, -1px);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        input[type="checkbox"]:checked + .remember-me .checkbox-custom::after {
            opacity: 1;
        }

        .btn-submit {
            width: 100%;
            background: var(--accent-gradient);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 14px;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
            filter: brightness(1.1);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Subtle button hover shine animation */
        .btn-submit::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 20%;
            height: 200%;
            background: rgba(255, 255, 255, 0.15);
            transform: rotate(30deg);
            transition: none;
        }

        .btn-submit:hover::after {
            left: 120%;
            transition: all 0.6s cubic-bezier(0.19, 1, 0.22, 1);
        }

        /* Validation UI styling */
        .error-message {
            color: var(--error-color);
            font-size: 0.8rem;
            margin-top: 6px;
            display: block;
        }

        .input-error {
            border-color: rgba(248, 113, 113, 0.4);
        }

        .input-error:focus {
            border-color: var(--error-color);
            box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.15);
        }

        /* Simple footer alignment */
        .portal-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>

    <div class="ambient-orb orb-left"></div>
    <div class="ambient-orb orb-right"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="brand-section">
                <div class="brand-logo">
                    @php
                        $webLogo = \App\Models\GeneralWebSettings::where('name', 'web_logo')->first();
                        $logoPath = $webLogo && $webLogo->value ? 'adminDash/assets/img/layouts/' . $webLogo->value : 'logo.png';
                    @endphp
                    <img src="{{ asset($logoPath) }}" alt="Website Logo" style="max-height: 50px; max-width: 100%;">
                </div>
                <h1>Admin Control Panel</h1>
                <p>Sign in with your administrator credentials</p>
            </div>

            <!-- Global errors if any -->
            @if ($errors->has('login_error'))
                <div class="alert-error">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>{{ $errors->first('login_error') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="input-control @error('email') input-error @enderror" 
                            placeholder="admin@example.com" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                        >
                    </div>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="input-control @error('password') input-error @enderror" 
                            placeholder="••••••••" 
                            required
                        >
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me & Options -->
                <div class="options-bar">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" class="remember-me">
                        <span class="checkbox-custom"></span>
                        Keep me signed in
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    Sign In to Dashboard
                </button>
            </form>
        </div>

        <div class="portal-footer">
            &copy; {{ date('Y') }} Admin Dashboard. Secure Infrastructure Portal.
        </div>
    </div>

</body>
</html>
