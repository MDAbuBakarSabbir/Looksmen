<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function authenticated(Request $request, $user)
    {
        $sessionCart = session()->get('cart', []);
        if (! empty($sessionCart)) {
            foreach ($sessionCart as $cartId => $item) {
                Cart::updateOrCreate(
                    ['user_id' => $user->id, 'cart_id' => $cartId],
                    [
                        'product_id' => $item['id'],
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'image' => $item['image'],
                        'attribute' => $item['attribute'],
                        'color' => $item['color'],
                    ]
                );
            }
            session()->forget('cart'); // সেশন ক্লিয়ার করুন
        }
    }
}
