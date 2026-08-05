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
    $otpCode = $otp ?? $code ?? '123456';
    $validMinutes = $expireMinutes ?? $validity ?? 10;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification Code - {{ $siteName }}</title>
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
            max-width: 580px;
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
        .content-body {
            padding: 40px;
            text-align: center;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 12px;
            text-align: left;
        }
        .message-text {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
            text-align: left;
        }
        .otp-card {
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
            border: 2px dashed #bfdbfe;
            border-radius: 12px;
            padding: 24px 20px;
            margin: 28px 0;
            display: block;
        }
        .otp-title {
            font-size: 13px;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .otp-display {
            font-size: 38px;
            font-weight: 900;
            letter-spacing: 8px;
            color: #1d4ed8;
            font-family: 'Courier New', Courier, monospace;
            margin: 10px 0;
            line-height: 1.2;
        }
        .otp-expiry {
            font-size: 13px;
            color: #64748b;
            margin-top: 8px;
            font-weight: 500;
        }
        .warning-card {
            background-color: #fffbe6;
            border-left: 4px solid #f59e0b;
            padding: 14px 16px;
            border-radius: 6px;
            text-align: left;
            margin-top: 24px;
        }
        .warning-text {
            font-size: 13px;
            color: #b45309;
            margin: 0;
            line-height: 1.5;
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
            .content-body, .header, .footer {
                padding: 25px 20px !important;
            }
            .otp-display {
                font-size: 32px !important;
                letter-spacing: 5px !important;
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
                        <!-- Email Body -->
                        <div class="content-body">
                            <h2 class="greeting">Hello {{ $userDisplayName }},</h2>
                            
                            <p class="message-text">
                                You are receiving this email to verify your One-Time Password (OTP) for account action on <strong>{{ $siteName }}</strong>.
                            </p>
                            <!-- OTP Code Box -->
                            <div class="otp-card">
                                <div class="otp-title">Your OTP Verification Code</div>
                                <div class="otp-display">{{ $otpCode }}</div>
                                <div class="otp-expiry">⏱ Valid for {{ $validMinutes }} minutes</div>
                            </div>
                            <!-- Security Warning Card -->
                            <div class="warning-card">
                                <p class="warning-text">
                                    <strong>🔒 Security Notice:</strong> Please do not share this OTP with anyone, including {{ $siteName }} staff. If you did not request this OTP, please ignore this email or contact support.
                                </p>
                            </div>
                            <div class="divider"></div>
                            <p class="message-text" style="font-size: 13px; color: #94a3b8; margin-bottom: 0; text-align: center;">
                                Thank you for choosing {{ $siteName }}.
                            </p>
                        </div>
                        <!-- Footer -->
                        <div class="footer">
                            <p style="margin: 0 0 6px 0; font-weight: 700; color: #334155; font-size: 14px;">{{ $siteName }}</p>
                            <p style="margin: 0 0 10px 0; font-size: 12px;">Fast, reliable, and secure online shopping.</p>
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
