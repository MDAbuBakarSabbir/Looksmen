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

        // Send Welcome Mail
        try {
            send_template_mail($user->email, 'welcome_mail', [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
            ]);
        } catch (\Exception $e) {
            \Log::error('Welcome Mail Trigger Error: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: true));
    }
}
