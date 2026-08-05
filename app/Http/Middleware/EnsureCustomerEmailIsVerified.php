<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if Email Verification Feature & Verification/OTP Templates are Active in Settings
        $settings = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        $featuresConfig = Cache::rememberForever('feature_activations_map', function () {
            return \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
        });

        $emailVerificationFeature = ($featuresConfig['email_verification'] ?? '0') === '1';
        $verificationTemplateActive = ($settings['verification_mail_active'] ?? '0') === '1';
        $otpTemplateActive = ($settings['otp_mail_active'] ?? '0') === '1';

        $emailVerificationRequired = $emailVerificationFeature && ($verificationTemplateActive || $otpTemplateActive);

        // If Email Verification is enabled and templates are active, check user verification status
        if ($emailVerificationRequired && !$user->hasVerifiedEmail()) {
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
