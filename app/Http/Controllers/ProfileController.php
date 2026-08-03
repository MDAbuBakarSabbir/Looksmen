<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Wishlist;
use App\Models\Product;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('frontEnd.dashboard.manageProfile', [
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
        $wishlists = Wishlist::where('user_id', Auth::id())->with('product')->get();
        return view('Frontend.dashboard.wishlist', compact('wishlists'));
    }

    public function addWishlist(Request $request)
    {
        if (Auth::check()) {
            $check = Wishlist::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();
            if ($check) {
                return response()->json(['status' => 'warning', 'message' => 'Product is already in your wishlist']);
            }
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            return response()->json(['status' => 'success', 'message' => 'Product added to wishlist successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'Please login first'], 401);
    }

    public function removeWishlist(Request $request)
    {
        if (Auth::check()) {
            Wishlist::where('user_id', Auth::id())->where('product_id', $request->product_id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Product removed from wishlist']);
        }
        return response()->json(['status' => 'error', 'message' => 'Please login first'], 401);
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
