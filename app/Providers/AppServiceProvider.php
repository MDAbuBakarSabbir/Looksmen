<?php

namespace App\Providers;

require_once __DIR__ . '/../helpers.php';

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
        \Illuminate\Pagination\Paginator::useBootstrapFour();

        if (class_exists('App\Models\GeneralWebSettings')) {
            try {
                $settings = \Illuminate\Support\Facades\Cache::rememberForever('boot_general_web_settings_map', function () {
                    return \App\Models\GeneralWebSettings::all()->pluck('value', 'name')->toArray();
                });

                $tz = $settings['timezone'] ?? env('APP_TIMEZONE') ?? config('app.timezone') ?? 'Asia/Dhaka';
                if (! empty($tz) && in_array($tz, timezone_identifiers_list())) {
                    date_default_timezone_set($tz);
                    config(['app.timezone' => $tz]);
                }

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

// Global helper functions moved to app/helpers.php

