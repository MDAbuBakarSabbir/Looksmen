<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: true).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            $user = $request->user();
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();

            event(new Verified($user));

            try {
                send_template_mail($user->email, 'welcome_mail', [
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Welcome Mail Error: ' . $e->getMessage());
            }
        }

        return redirect()->intended(route('dashboard', absolute: true).'?verified=1');
    }

    /**
     * Mark the authenticated user's email address as verified using 6-digit OTP code.
     */
    public function verifyOtp(\Illuminate\Http\Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: true).'?verified=1');
        }

        if (empty($user->verification_code) || $user->verification_code !== trim($request->otp)) {
            return back()->withErrors(['otp' => 'The verification code entered is invalid. Please double check your email.'])->withInput();
        }

        if ($user->verification_code_expires_at && now()->greaterThan($user->verification_code_expires_at)) {
            return back()->withErrors(['otp' => 'The verification code has expired. Please click "Resend Code" to get a new code.'])->withInput();
        }

        $user->markEmailAsVerified();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        event(new Verified($user));

        try {
            send_template_mail($user->email, 'welcome_mail', [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Welcome Mail Error: ' . $e->getMessage());
        }

        return redirect()->intended(route('dashboard', absolute: true).'?verified=1')->with('success', 'Email address verified successfully!');
    }
}
