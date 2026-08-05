<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $referred_by = null;
        if ($request->hasCookie('referral_code')) {
            $referrer = User::where('referral_code', $request->cookie('referral_code'))->first();
            if ($referrer) {
                $referred_by = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referred_by' => $referred_by,
        ]);

        event(new Registered($user));

        // Check if Email Verification Feature & Verification/OTP Mail Templates are Active
        $settings = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        $featuresConfig = \Illuminate\Support\Facades\Cache::rememberForever('feature_activations_map', function () {
            return \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
        });

        $emailVerificationFeature = ($featuresConfig['email_verification'] ?? '0') === '1';
        $verificationTemplateActive = ($settings['verification_mail_active'] ?? '0') === '1';
        $otpTemplateActive = ($settings['otp_mail_active'] ?? '0') === '1';

        $emailVerificationRequired = $emailVerificationFeature && ($verificationTemplateActive || $otpTemplateActive);

        if ($emailVerificationRequired) {
            // Generate 6-Digit Verification Code
            $otp = sprintf("%06d", mt_rand(1, 999999));
            $user->verification_code = $otp;
            $user->verification_code_expires_at = now()->addMinutes(15);
            $user->save();

            // Send Verification Mail
            try {
                $verifyUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'verification.verify',
                    now()->addMinutes(60),
                    ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
                );

                send_verification_email($user, $otp, $verifyUrl);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Verification Mail Error: ' . $e->getMessage());
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
            \Illuminate\Support\Facades\Log::error('Welcome Mail Trigger Error: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: true));
    }
}
