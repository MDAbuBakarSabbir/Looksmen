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

// Global helper functions moved to app/helpers.php

