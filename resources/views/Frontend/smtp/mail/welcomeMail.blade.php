@php
    if (!isset($webConfig) || empty($webConfig)) {
        try {
            $webConfig = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        } catch (\Exception $e) {
            $webConfig = [];
        }
    }
    $siteName = $webConfig['web_name'] ?? config('app.name', 'LOOKSMEN');
    $siteLogoName = $webConfig['web_logo'] ?? 'Logo.png';
    $siteLogo = asset('adminDash/assets/img/layouts/' . $siteLogoName);
    $userDisplayName = $user->name ?? $name ?? 'Valued Customer';
    $userEmail = $user->email ?? $email ?? 'customer@example.com';
    $shopUrl = url('/');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $siteName }}!</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        .wrapper {
            width: 100%;
            background-color: #f4f6f9;
            padding: 40px 0;
        }
        .main-card {
            background-color: #ffffff;
            margin: 0 auto;
            max-width: 600px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #ffffff;
            padding: 30px 40px;
            text-align: center;
            border-bottom: 2px solid #f1f5f9;
        }
        .brand-logo-wrap {
            display: inline-block;
            padding: 6px 12px;
            background-color: #ffffff;
        }
        .brand-logo {
            max-height: 60px;
            max-width: 220px;
            height: auto;
            width: auto;
            display: inline-block;
            vertical-align: middle;
            object-fit: contain;
        }
        .brand-name {
            color: #0f172a;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin: 0;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
        }
        .hero-banner {
            background: linear-gradient(135deg, #0f172a 0%, #2563eb 100%);
            padding: 35px 40px;
            text-align: center;
            color: #ffffff;
        }
        .hero-title {
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 10px 0;
            letter-spacing: 0.5px;
        }
        .hero-subtitle {
            font-size: 15px;
            color: #e2e8f0;
            margin: 0;
            line-height: 1.5;
        }
        .content-body {
            padding: 40px;
            text-align: left;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .message-text {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .account-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px 24px;
            margin: 24px 0;
        }
        .account-title {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .account-detail {
            font-size: 15px;
            color: #0f172a;
            font-weight: 600;
            margin: 4px 0;
        }
        .btn-container {
            text-align: center;
            margin: 32px 0;
        }
        .cta-btn {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            padding: 15px 36px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            letter-spacing: 0.5px;
        }
        .features-grid {
            margin: 30px 0 10px 0;
        }
        .feature-item {
            padding: 12px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 14px;
            color: #334155;
        }
        .feature-item:last-child {
            border-bottom: none;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 30px 0;
        }
        .footer {
            background-color: #f8fafc;
            padding: 25px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 13px;
            color: #64748b;
        }
        @media screen and (max-width: 600px) {
            .main-card {
                width: 92% !important;
            }
            .content-body, .header, .hero-banner, .footer {
                padding: 25px 20px !important;
            }
            .hero-title {
                font-size: 20px !important;
            }
            .cta-btn {
                width: 85% !important;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table align="center" width="100%" cellPadding="0" cellSpacing="0" role="presentation">
            <tr>
                <td align="center">
                    <div class="main-card">
                        <!-- Header / Branding -->
                        <div class="header">
                            <div class="brand-logo-wrap">
                                @if(!empty($siteLogo))
                                    <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="brand-logo" onerror="this.style.display='none'; document.getElementById('brand-fallback').style.display='inline-block';">
                                    <span id="brand-fallback" class="brand-name" style="display: none;">{{ $siteName }}</span>
                                @else
                                    <span class="brand-name">{{ $siteName }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Hero Banner -->
                        <div class="hero-banner">
                            <h1 class="hero-title">Welcome to {{ $siteName }}! 🎉</h1>
                            <p class="hero-subtitle">We are thrilled to have you join our community.</p>
                        </div>

                        <!-- Email Body -->
                        <div class="content-body">
                            <h2 class="greeting">Hello {{ $userDisplayName }},</h2>
                            
                            <p class="message-text">
                                Thank you for registering with <strong>{{ $siteName }}</strong>. Your account has been successfully created and is ready to use!
                            </p>

                            <!-- Account Info Box -->
                            <div class="account-box">
                                <div class="account-title">Your Account Information</div>
                                <div class="account-detail">Name: <span>{{ $userDisplayName }}</span></div>
                                <div class="account-detail">Email / Username: <span style="color: #2563eb;">{{ $userEmail }}</span></div>
                            </div>

                            <!-- Feature Highlights -->
                            <div class="features-grid">
                                <div class="feature-item">🛍️ <strong>Discover Latest Collection:</strong> Browse our premium products with exclusive prices.</div>
                                <div class="feature-item">⚡ <strong>Fast & Easy Shipping:</strong> Enjoy quick and reliable delivery right to your doorstep.</div>
                                <div class="feature-item">🔒 <strong>100% Secure Shopping:</strong> Safe checkout options with multiple payment methods.</div>
                            </div>

                            <!-- CTA Button -->
                            <div class="btn-container">
                                <a href="{{ $shopUrl }}" class="cta-btn" target="_blank">
                                    Start Shopping Now
                                </a>
                            </div>

                            <div class="divider"></div>

                            <p class="message-text" style="font-size: 13px; color: #94a3b8; margin-bottom: 0;">
                                Need help? Feel free to contact our support team anytime. Happy shopping!
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <p style="margin: 0 0 6px 0; font-weight: 700; color: #334155; font-size: 14px;">The {{ $siteName }} Team</p>
                            <p style="margin: 0 0 10px 0; font-size: 12px;">Thank you for choosing us for your shopping needs.</p>
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                                &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
                            </p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>