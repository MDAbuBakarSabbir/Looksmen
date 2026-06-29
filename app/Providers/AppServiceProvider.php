<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (class_exists('App\Models\GeneralWebSettings')) {
            try {
                $settings = \App\Models\GeneralWebSettings::all()->pluck('value', 'name')->toArray();
                if (isset($settings['mailhost']) && !empty($settings['mailhost'])) {
                    config([
                        'mail.mailers.smtp.host' => $settings['mailhost'],
                        'mail.mailers.smtp.port' => $settings['mailport'] ?? 2525,
                        'mail.mailers.smtp.username' => $settings['mailusername'] ?? '',
                        'mail.mailers.smtp.password' => $settings['mailpassword'] ?? '',
                        'mail.mailers.smtp.encryption' => $settings['mailencription'] ?? null,
                        'mail.from.address' => $settings['mailaddress'] ?? 'no-reply@example.com',
                        'mail.from.name' => env('APP_NAME', 'FreshEcom'),
                    ]);
                }
            } catch (\Exception $e) {}
        }
    }
}

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
                    $feature = \App\Models\FeatureActivation::where('name', 'affiliate')->first();
                    return $feature && $feature->status == '1';
                } catch (\Exception $e) {}
            }
            return true;
        }
        return false;
    }
}

if (!function_exists('flash')) {
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

