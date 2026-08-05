<?php

if (!function_exists('translate')) {
    function translate($key) {
        return $key;
    }
}

if (!function_exists('single_price')) {
    function single_price($price) {
        return "৳" . number_format((float)$price, 2);
    }
}

if (!function_exists('addon_is_activated')) {
    function addon_is_activated($addon) {
        if ($addon === 'affiliate_system') {
            if (class_exists('App\Models\FeatureActivation')) {
                try {
                    $features = \Illuminate\Support\Facades\Cache::rememberForever('feature_activations_map', function () {
                        return \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
                    });
                    return ($features['affiliate'] ?? '0') == '1';
                } catch (\Exception $e) {}
            }
            return true;
        } elseif ($addon === 'wallet_system') {
            if (class_exists('App\Models\FeatureActivation')) {
                try {
                    $features = \Illuminate\Support\Facades\Cache::rememberForever('feature_activations_map', function () {
                        return \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
                    });
                    return ($features['wallet_system'] ?? '0') == '1';
                } catch (\Exception $e) {}
            }
            return false;
        } elseif ($addon === 'conversations') {
            if (class_exists('App\Models\FeatureActivation')) {
                try {
                    $features = \Illuminate\Support\Facades\Cache::rememberForever('feature_activations_map', function () {
                        return \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
                    });
                    return ($features['conversations'] ?? '0') == '1';
                } catch (\Exception $e) {}
            }
            return false;
        }
        return false;
    }
}

if ( !function_exists('flash')) {
    function flash($message) {
        session()->flash('success', $message);
        return new class {
            public function success() { return $this; }
            public function error() { 
                session()->flash('error', session()->get('success'));
                return $this; 
            }
            public function warning() { 
                session()->flash('warning', session()->get('success'));
                return $this; 
            }
        };
    }
}

if (!function_exists('send_custom_mail')) {
    function send_custom_mail($to, $subject, $body) {
        try {
            $settings = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();

            if (empty($settings['mailhost']) || empty($settings['mailusername'])) {
                \Illuminate\Support\Facades\Log::warning('SMTP Mail Failed: Mail host or username is not configured in Settings.');
                return false;
            }

            $encryption = $settings['mailencription'] ?? 'ssl';
            $port = (int)($settings['mailport'] ?? 465);

            $config = [
                'transport' => 'smtp',
                'host' => trim($settings['mailhost']),
                'port' => $port,
                'encryption' => trim($encryption),
                'username' => trim($settings['mailusername']),
                'password' => $settings['mailpassword'] ?? '',
                'timeout' => 30,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ];

            config(['mail.mailers.smtp' => array_merge(config('mail.mailers.smtp', []), $config)]);
            config(['mail.default' => 'smtp']);
            config(['mail.from.address' => trim($settings['mailaddress'] ?? $settings['mailusername'])]);
            config(['mail.from.name' => config('app.name', 'Looksmen')]);

            // Clear mailer instance to apply runtime changes
            app()->make('mail.manager')->forgetMailers();

            // Detect if body is already HTML formatted
            $isHtml = preg_match('/<[a-z][\s\S]*>/i', $body);
            $formattedBody = $isHtml ? $body : nl2br($body);

            // Send HTML email
            \Illuminate\Support\Facades\Mail::html($formattedBody, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMTP Mail Error: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('parse_template')) {
    function parse_template($template, $data) {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }
}

if (!function_exists('send_verification_email')) {
    function send_verification_email($user, $otp, $verifyUrl = '#') {
        $settings = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();

        $verificationActive = ($settings['verification_mail_active'] ?? '0') === '1';
        $otpActive = ($settings['otp_mail_active'] ?? '0') === '1';

        // Check if at least one verification template is active
        if (!$verificationActive && !$otpActive) {
            \Illuminate\Support\Facades\Log::info('Email verification skipped: Neither verification_mail nor otp_mail template is active in settings.');
            return false;
        }

        $templateText = '';
        if ($verificationActive && !empty($settings['verification_mail_template'])) {
            $templateText = $settings['verification_mail_template'];
        } elseif ($otpActive && !empty($settings['otp_mail_template'])) {
            $templateText = $settings['otp_mail_template'];
        }

        $siteName = !empty($settings['web_name']) ? $settings['web_name'] : config('app.name', 'Looksmen');

        $replacements = [
            '{customer_name}' => $user->name,
            '{name}'          => $user->name,
            '{customer_email}'=> $user->email,
            '{email}'         => $user->email,
            '{site_name}'     => $siteName,
            '{otp}'           => $otp,
            '{code}'          => $otp,
            '{verify_url}'    => $verifyUrl,
        ];

        $customMessage = !empty($templateText) ? parse_template($templateText, $replacements) : null;

        $mailHtml = view('Frontend.smtp.mail.verifyMail', [
            'user'          => $user,
            'otp'           => $otp,
            'verifyUrl'     => $verifyUrl,
            'customMessage' => $customMessage,
            'siteName'      => $siteName,
        ])->render();

        $subject = 'Verify Your Email Address - ' . $siteName;

        return send_custom_mail($user->email, $subject, $mailHtml);
    }
}

if (!function_exists('send_template_mail')) {
    function send_template_mail($toEmail, $templateName, $data) {
        $settings = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();

        if (isset($settings[$templateName . '_active']) && $settings[$templateName . '_active'] == '1' && !empty($settings[$templateName . '_template'])) {
            $siteName = !empty($settings['web_name']) ? $settings['web_name'] : config('app.name', 'Looksmen');
            $data['site_name'] = $data['site_name'] ?? $siteName;

            $body = parse_template($settings[$templateName . '_template'], $data);

            $subject = match($templateName) {
                'welcome_mail' => 'Welcome to ' . $siteName,
                'verification_mail' => 'Email Verification - ' . $siteName,
                'otp_mail' => 'Your OTP Code - ' . $siteName,
                'order_confirmation_mail' => 'Order Confirmation - #' . ($data['order_id'] ?? ''),
                'order_cancel_mail' => 'Order Cancelled - #' . ($data['order_id'] ?? ''),
                'order_delivered_mail' => 'Order Delivered - #' . ($data['order_id'] ?? ''),
                default => 'Notification from ' . $siteName,
            };

            return send_custom_mail($toEmail, $subject, $body);
        }
        return false;
    }
}

if (!function_exists('upload_to_webp')) {
    function upload_to_webp($file, $destinationFolder, $prefix = 'img', $quality = 85) {
        if (!$file || !is_object($file) || (method_exists($file, 'isValid') && !$file->isValid())) {
            return null;
        }

        $absolutePath = (is_dir($destinationFolder) || str_starts_with($destinationFolder, '/') || str_starts_with($destinationFolder, '\\') || preg_match('/^[a-zA-Z]:/', $destinationFolder))
            ? $destinationFolder
            : public_path($destinationFolder);

        if (!file_exists($absolutePath)) {
            mkdir($absolutePath, 0777, true);
        }

        $fileName = $prefix . '_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.webp';
        $fullPath = rtrim($absolutePath, '/\\') . '/' . $fileName;

        try {
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->decode($file);
            $image->save($fullPath, quality: $quality);
            return $fileName;
        } catch (\Exception $e) {
            $fallbackExt = method_exists($file, 'getClientOriginalExtension') ? ($file->getClientOriginalExtension() ?: 'webp') : 'webp';
            $fallbackName = $prefix . '_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $fallbackExt;
            if (method_exists($file, 'move')) {
                $file->move($absolutePath, $fallbackName);
            }
            return $fallbackName;
        }
    }
}

if (!function_exists('send_custom_sms')) {
    function send_custom_sms($to, $message) {
        try {
            $settings = \Illuminate\Support\Facades\Cache::rememberForever('boot_general_web_settings_map', function () {
                return \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
            });

            $provider = strtolower($settings['sms_gateway_provider'] ?? '');
            $apiKey = trim($settings['sms_api_key'] ?? '');
            $senderId = trim($settings['sms_sender_id'] ?? '');
            $apiUrl = trim($settings['sms_api_url'] ?? '');

            if (empty($apiKey) && empty($apiUrl)) {
                \Illuminate\Support\Facades\Log::warning('SMS Send Failed: API Key and API URL are not configured.');
                return false;
            }

            // Clean phone number (keep digits only)
            $phone = preg_replace('/[^0-9]/', '', (string)$to);
            if (empty($phone)) {
                return false;
            }

            if ($provider === 'steadfast') {
                $targetUrl = $apiUrl ?: 'https://portal.packzy.com/api/v1/send_sms';
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Api-Key' => $apiKey,
                    'Secret-Key' => $senderId,
                    'Content-Type' => 'application/json',
                ])->post($targetUrl, [
                    'recipient' => $phone,
                    'message' => $message,
                ]);
                return $response->successful();
            } elseif ($provider === 'greenweb') {
                $targetUrl = $apiUrl ?: 'https://api.greenweb.com.bd/api.php';
                $response = \Illuminate\Support\Facades\Http::post($targetUrl, [
                    'token' => $apiKey,
                    'to' => $phone,
                    'message' => $message,
                ]);
                return $response->successful();
            } elseif ($provider === 'bulksmsbd') {
                $targetUrl = $apiUrl ?: 'http://bulksmsbd.net/api/smsapi';
                $response = \Illuminate\Support\Facades\Http::get($targetUrl, [
                    'api_key' => $apiKey,
                    'senderid' => $senderId,
                    'number' => $phone,
                    'message' => $message,
                ]);
                return $response->successful();
            } else {
                $targetUrl = $apiUrl ?: 'https://api.smsprovider.com/send';
                $response = \Illuminate\Support\Facades\Http::get($targetUrl, [
                    'api_key' => $apiKey,
                    'sender_id' => $senderId,
                    'to' => $phone,
                    'message' => $message,
                ]);
                return $response->successful();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMS Dispatch Error: ' . $e->getMessage());
            return false;
        }
    }
}

