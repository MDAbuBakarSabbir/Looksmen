<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Product;
use App\Models\Wishlist;
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
        return view('Frontend.dashboard.manageProfile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_pic' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'profile_pic')) {
            \Illuminate\Support\Facades\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('profile_pic')->nullable()->after('email');
            });
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_pic')) {
            if ($user->profile_pic && file_exists(public_path('Uploads/' . $user->profile_pic))) {
                @unlink(public_path('Uploads/' . $user->profile_pic));
            }

            if (function_exists('upload_to_webp')) {
                $user->profile_pic = upload_to_webp($request->file('profile_pic'), 'Uploads', 'user');
            } else {
                $file = $request->file('profile_pic');
                $filename = 'user_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('Uploads'), $filename);
                $user->profile_pic = $filename;
            }
        }

        $user->save();

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

    public function purchaseHistory()
    {
        $orders = \App\Models\Orders::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(10);
        return view('Frontend.dashboard.purchaseHistory', compact('orders'));
    }

    public function wishlist()
    {
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
                'product_id' => $request->product_id,
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

    public function compare()
    {
        $compareSession = session()->get('compare', []);
        $productIds = array_keys($compareSession);
        $products = Product::whereIn('id', $productIds)->with('firstImage')->get();

        return view('Frontend.dashboard.compare', compact('products'));
    }

    public function conversation()
    {
        return view('Frontend.dashboard.conversation');
    }

    public function myWallet()
    {
        return view('Frontend.dashboard.myWallet');
    }

    public function supportTicket()
    {
        return view('Frontend.dashboard.supportTicket');
    }
}
