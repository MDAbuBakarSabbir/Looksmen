<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: true));
        }

        // Generate New 6-Digit OTP Code
        $otp = sprintf("%06d", mt_rand(1, 999999));
        $user->verification_code = $otp;
        $user->verification_code_expires_at = now()->addMinutes(15);
        $user->save();

        try {
            $verifyUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
            );

            send_verification_email($user, $otp, $verifyUrl);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Resend Verification Mail Error: ' . $e->getMessage());
        }

        return back()->with('status', 'verification-code-sent');
    }
}
