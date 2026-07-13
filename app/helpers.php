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
            $features = \Illuminate\Support\Facades\Cache::rememberForever('feature_activations_map', function () {
                return \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
            });
            if (($features['email_verification'] ?? '0') !== '1') {
                return false;
            }

            $settings = \Illuminate\Support\Facades\Cache::rememberForever('boot_general_web_settings_map', function () {
                return \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
            });

            if (empty($settings['mailhost']) || empty($settings['mailusername'])) {
                return false;
            }

            $config = [
                'transport' => 'smtp',
                'host' => $settings['mailhost'],
                'port' => (int)($settings['mailport'] ?? 465),
                'encryption' => $settings['mailencription'] ?? 'ssl',
                'username' => $settings['mailusername'],
                'password' => $settings['mailpassword'],
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ];

            config(['mail.mailers.smtp' => array_merge(config('mail.mailers.smtp', []), $config)]);
            config(['mail.default' => 'smtp']);
            config(['mail.from.address' => $settings['mailaddress'] ?? $settings['mailusername']]);
            config(['mail.from.name' => config('app.name', 'Looksmen')]);

            // Clear mailer instance to apply runtime changes
            app()->make('mail.manager')->forgetMailers();

            // Send HTML email
            \Illuminate\Support\Facades\Mail::html(nl2br($body), function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('SMTP Mail Error: ' . $e->getMessage());
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

if (!function_exists('send_template_mail')) {
    function send_template_mail($toEmail, $templateName, $data) {
        $settings = \Illuminate\Support\Facades\Cache::rememberForever('boot_general_web_settings_map', function () {
            return \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        });

        if (isset($settings[$templateName . '_active']) && $settings[$templateName . '_active'] == '1' && !empty($settings[$templateName . '_template'])) {
            $body = parse_template($settings[$templateName . '_template'], $data);
            
            $subject = match($templateName) {
                'welcome_mail' => 'Welcome to ' . config('app.name', 'Looksmen'),
                'verification_mail' => 'Email Verification',
                'otp_mail' => 'Your OTP Code',
                'order_confirmation_mail' => 'Order Confirmation - #' . ($data['order_id'] ?? ''),
                'order_cancel_mail' => 'Order Cancelled - #' . ($data['order_id'] ?? ''),
                'order_delivered_mail' => 'Order Delivered - #' . ($data['order_id'] ?? ''),
                default => 'Notification from ' . config('app.name', 'Looksmen'),
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
