<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function purchaseHistory(){
        return view('Frontend.dashboard.purchaseHistory');
    }

    public function wishlist(){
        return view('Frontend.dashboard.wishlist');
    }

    public function compare(){
        $compareSession = session()->get('compare', []);
        $productIds = array_keys($compareSession);
        $products = Product::whereIn('id', $productIds)->with('firstImage')->get();
        return view('Frontend.dashboard.compare', compact('products'));
    }

    public function conversation(){
        return view('Frontend.dashboard.conversation');
    }

    public function myWallet(){
        return view('Frontend.dashboard.myWallet');
    }

    public function supportTicket(){
        return view('Frontend.dashboard.supportTicket');
    }
}
