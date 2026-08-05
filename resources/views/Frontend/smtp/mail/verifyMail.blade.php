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
    $verifyUrl = $url ?? $actionUrl ?? '#';
    $userDisplayName = $user->name ?? $name ?? 'Valued Customer';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Registration - {{ $siteName }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
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
        .btn-container {
            text-align: center;
            margin: 32px 0;
        }
        .verify-btn {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            letter-spacing: 0.5px;
        }
        .otp-box {
            background-color: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 24px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #2563eb;
            font-family: monospace;
        }
        .otp-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .alt-link-section {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            font-size: 13px;
            color: #64748b;
            word-break: break-all;
            margin-top: 24px;
            border: 1px solid #e2e8f0;
        }
        .alt-link-section a {
            color: #2563eb;
            text-decoration: underline;
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
            .verify-btn {
                width: 80% !important;
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

                        <!-- Email Body -->
                        <div class="content-body">
                            <h2 class="greeting">Hello {{ $userDisplayName }},</h2>
                            
                            <p class="message-text">
                                @if(!empty($customMessage))
                                    {!! nl2br(e($customMessage)) !!}
                                @else
                                    Thank you for registering with <strong>{{ $siteName }}</strong>! To complete your registration and activate your account, please verify your email address.
                                @endif
                            </p>

                            @if(isset($otp) || isset($code))
                                <div class="otp-box">
                                    <div class="otp-label">Your Verification Code</div>
                                    <div class="otp-code">{{ $otp ?? $code }}</div>
                                </div>
                            @endif

                            @if($verifyUrl !== '#')
                                <div class="btn-container">
                                    <a href="{{ $verifyUrl }}" class="verify-btn" target="_blank">
                                        Verify Email Address
                                    </a>
                                </div>

                                <div class="alt-link-section">
                                    <strong>Having trouble with the button?</strong> Copy and paste the following link into your browser:<br>
                                    <a href="{{ $verifyUrl }}" target="_blank">{{ $verifyUrl }}</a>
                                </div>
                            @endif

                            <div class="divider"></div>

                            <p class="message-text" style="font-size: 13px; color: #94a3b8; margin-bottom: 0;">
                                If you did not create an account on {{ $siteName }}, no further action is required.
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <p style="margin: 0 0 8px 0; font-weight: 600; color: #334155;">{{ $siteName }}</p>
                            <p style="margin: 0 0 12px 0; font-size: 12px;">Thank you for shopping with us.</p>
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
