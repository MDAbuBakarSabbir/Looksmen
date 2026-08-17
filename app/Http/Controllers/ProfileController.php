<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Product;
use App\Models\Reviews;
use App\Models\Wishlist;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'profile_pic' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        if (! Schema::hasColumn('users', 'profile_pic')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_pic')->nullable()->after('email');
            });
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_pic')) {
            if ($user->profile_pic && file_exists(public_path('Uploads/'.$user->profile_pic))) {
                @unlink(public_path('Uploads/'.$user->profile_pic));
            }

            if (function_exists('upload_to_webp')) {
                $user->profile_pic = upload_to_webp($request->file('profile_pic'), 'Uploads', 'user');
            } else {
                $file = $request->file('profile_pic');
                $filename = 'user_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
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
        $orders = Orders::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(10);

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

    public function toReview()
    {
        $userId = Auth::id();

        // 1. Get all delivered orders for this user
        $deliveredOrders = Orders::where('user_id', $userId)
            ->whereIn('delivery_status', ['delivered', 'partial_delivered'])
            ->with(['orderDetails.orderProduct.firstImage', 'orderDetails.orderProduct.productImages'])
            ->orderBy('id', 'desc')
            ->get();

        // 2. Extract all unique delivered products with their order info
        $deliveredProducts = collect();
        foreach ($deliveredOrders as $order) {
            foreach ($order->orderDetails as $detail) {
                if ($detail->orderProduct && ! $deliveredProducts->has($detail->product_id)) {
                    $deliveredProducts->put($detail->product_id, [
                        'order' => $order,
                        'detail' => $detail,
                        'product' => $detail->orderProduct,
                    ]);
                }
            }
        }

        // 3. Get all user reviews
        $userReviews = Reviews::where('user_id', $userId)
            ->with(['product.firstImage', 'product.productImages'])
            ->latest()
            ->get()
            ->keyBy('product_id');

        // 4. Separate into To Review (unsubmitted) and Reviewed (submitted)
        $toReviewItems = collect();
        $reviewedItems = collect();

        foreach ($deliveredProducts as $productId => $item) {
            if ($userReviews->has($productId)) {
                $review = $userReviews->get($productId);
                $reviewedItems->push([
                    'order' => $item['order'],
                    'detail' => $item['detail'],
                    'product' => $item['product'],
                    'review' => $review,
                ]);
            } else {
                $toReviewItems->push([
                    'order' => $item['order'],
                    'detail' => $item['detail'],
                    'product' => $item['product'],
                ]);
            }
        }

        // Any other reviews submitted that might not be in recent delivered orders
        foreach ($userReviews as $productId => $review) {
            if (! $deliveredProducts->has($productId) && $review->product) {
                $reviewedItems->push([
                    'order' => null,
                    'detail' => null,
                    'product' => $review->product,
                    'review' => $review,
                ]);
            }
        }

        return view('Frontend.dashboard.toReview', compact('toReviewItems', 'reviewedItems'));
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'review_star' => 'required|integer|min:1|max:5',
            'review_description' => 'required|string|min:3|max:2000',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Check if user has purchased this product in a delivered order
        $hasDeliveredOrder = Orders::where('user_id', $userId)
            ->whereIn('delivery_status', ['delivered', 'partial_delivered'])
            ->whereHas('orderDetails', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();

        if (! $hasDeliveredOrder) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'You can only review products from delivered orders.'], 403);
            }

            return redirect()->back()->with('error', 'You can only review products from delivered orders.');
        }

        // Check if review already exists (only 1 review allowed per product, update if exists)
        $existingReview = Reviews::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            $existingReview->review_star = $request->review_star;
            $existingReview->review_description = $request->review_description;
            $existingReview->status = '1';
            $existingReview->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your review has been updated successfully!',
                    'review' => $existingReview,
                ]);
            }

            return redirect()->back()->with('success', 'Your review has been updated successfully!');
        }

        $review = new Reviews;
        $review->user_id = $userId;
        $review->product_id = $productId;
        $review->review_star = $request->review_star;
        $review->review_description = $request->review_description;
        $review->status = '1';
        $review->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your review has been submitted successfully.',
                'review' => $review,
            ]);
        }

        return redirect()->back()->with('success', 'Thank you! Your review has been submitted successfully.');
    }

    public function updateReview(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'review_star' => 'required|integer|min:1|max:5',
            'review_description' => 'required|string|min:3|max:2000',
        ]);

        $userId = Auth::id();
        $review = Reviews::where('id', $request->review_id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $review->review_star = $request->review_star;
        $review->review_description = $request->review_description;
        $review->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully!',
                'review' => $review,
            ]);
        }

        return redirect()->back()->with('success', 'Review updated successfully!');
    }

    public function getReviewData(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->product_id;
        $reviewId = $request->review_id;

        $review = null;
        if ($reviewId) {
            $review = Reviews::where('id', $reviewId)
                ->where('user_id', $userId)
                ->with('product.firstImage')
                ->first();
        } elseif ($productId) {
            $review = Reviews::where('product_id', $productId)
                ->where('user_id', $userId)
                ->with('product.firstImage')
                ->first();
        }

        if (! $review) {
            return response()->json(['success' => false, 'message' => 'Review not found.'], 404);
        }

        return response()->json(['success' => true, 'review' => $review]);
    }
}
