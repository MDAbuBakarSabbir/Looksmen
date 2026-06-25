<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Coupons;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class CouponsController extends Controller
{
    public function coupons()
    {
        $coupons = Coupons::latest('id')->paginate(15);
        return view('adminDash.promotion&coupons.coupons', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_type' => 'required',
            'coupon_type' => 'required',
            'code' => 'required',
            'discount' => 'required',
            'discount_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        $coupons = new Coupons();
        $coupons->coupon_type = $request->coupon_type;
        $coupons->code = $request->code;
        $coupons->details = $request->details;
        $coupons->discount = $request->discount;
        $coupons->discount_type = $request->discount_type;
        $coupons->min_cart_amount = $request->min_cart_amount;
        $coupons->quantity = $request->quantity;
        $coupons->start_date = $request->start_date;
        $coupons->end_date = $request->end_date;
        $coupons->created_at = now();
        $coupons->save();

        return response()->json([
            'success' => true,
            'data' => $coupons,
            'message' => 'Coupon created successfully!'
        ]);
    }

    public function status(Request $request)
    {
        $coupon = Coupons::findOrFail($request->id);
        if (!$coupon) {
            return response()->json(['success' => false]);
        }
        $coupon->status = $request->status == 1 ? 1 : 0;
        $coupon->save();

        return response()->json([
            'success' => true,
            'status' => $coupon->status
        ]);
    }

    public function edit($id)
    {
        $coupon = Coupons::find($id);
        return response()->json($coupon);
    }

    public function update(Request $request,$id)
    {

        $coupons = Coupons::find($id);
        $coupons->coupon_type = $request->coupon_type;
        $coupons->code = $request->code;
        $coupons->details = $request->details;
        $coupons->discount = $request->discount;
        $coupons->discount_type = $request->discount_type;
        $coupons->min_cart_amount = $request->min_cart_amount;
        $coupons->quantity = $request->quantity;
        $coupons->start_date = $request->start_date;
        $coupons->end_date = $request->end_date;
        $coupons->updated_at = now();
        $coupons->save();

        return response()->json([
            'success' => true,
            'data' => $coupons,
            'message' => 'Coupon updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $coupon = Coupons::find($id);
        if ($coupon) {
            $coupon->delete();
            return response()->json([
                'success' => true,
                'message' => 'Coupon deleted successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Coupon not found!'
        ], 404);
    }
}
