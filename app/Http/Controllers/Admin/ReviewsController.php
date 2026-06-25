<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Reviews;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index()
    {
        $reviews = Reviews::all();
        return view('adminDash.reviews.index', compact('reviews'));
    }

    public function create()
    {
        //
    }
    public function view()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit()
    {
        //
    }

    public function update(Request $request, $id) {}

    public function admin_destroy($id) {}
    public function destroy($id) {}

    public function status(Request $request)
    {
        $review = Reviews::find($request->id);

        if (!$review) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $review->status = $request->status == 1 ? 1 : 0;
        $review->save();

        return response()->json([
            'success' => true,
            'status' => $review->status
        ]);
    }
}
