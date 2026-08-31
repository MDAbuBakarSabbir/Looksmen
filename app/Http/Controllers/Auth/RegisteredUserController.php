<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FeatureActivation;
use App\Models\GeneralWebSettings;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'referral_code' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $referred_by = null;
        $referralCodeInput = trim($request->input('referral_code') ?? $request->cookie('referral_code', ''));
        if (! empty($referralCodeInput)) {
            $referrer = User::where('referral_code', $referralCodeInput)->first();
            if ($referrer) {
                $referred_by = $referrer->id;
            }
        }

        // Generate a unique referral code for this new user
        $userReferralCode = strtoupper(substr(md5(uniqid((string) mt_rand(), true)), 0, 10));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'referral_code' => $userReferralCode,
            'referred_by' => $referred_by,
        ]);

        event(new Registered($user));

        // Check if Email Verification Feature & Verification/OTP Mail Templates are Active
        $settings = GeneralWebSettings::pluck('value', 'name')->toArray();
        $featuresConfig = Cache::rememberForever('feature_activations_map', function () {
            return FeatureActivation::pluck('status', 'name')->toArray();
        });

        $emailVerificationFeature = ($featuresConfig['email_verification'] ?? '0') === '1';
        $verificationTemplateActive = ($settings['verification_mail_active'] ?? '0') === '1';
        $otpTemplateActive = ($settings['otp_mail_active'] ?? '0') === '1';

        $emailVerificationRequired = $emailVerificationFeature && ($verificationTemplateActive || $otpTemplateActive);

        if ($emailVerificationRequired) {
            // Generate 6-Digit Verification Code
            $otp = sprintf('%06d', mt_rand(1, 999999));
            $user->verification_code = $otp;
            $user->verification_code_expires_at = now()->addMinutes(15);
            $user->save();

            // Send Verification Mail
            try {
                $verifyUrl = URL::temporarySignedRoute(
                    'verification.verify',
                    now()->addMinutes(60),
                    ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
                );

                send_verification_email($user, $otp, $verifyUrl);
            } catch (\Exception $e) {
                Log::error('Verification Mail Error: '.$e->getMessage());
            }

            Auth::login($user);

            return redirect()->route('verification.notice')->with('status', 'verification-code-sent');
        }

        // Default: If Email Verification is disabled, mark verified & send Welcome Mail
        $user->email_verified_at = now();
        $user->save();

        try {
            send_template_mail($user->email, 'welcome_mail', [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Welcome Mail Trigger Error: '.$e->getMessage());
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: true));
    }
}
