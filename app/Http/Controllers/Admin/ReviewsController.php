<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reviews;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index()
    {
        $reviews = Reviews::with(['user', 'product'])->latest()->get();
        return view('adminDash.reviews.index', compact('reviews'));
    }

    public function create()
    {
        //
    }
    public function view($id)
    {
        $review = Reviews::with(['user', 'product'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'review' => $review
        ]);
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

    public function admin_destroy($id)
    {
        $review = Reviews::findOrFail($id);
        $review->delete();
        return back()->with('success', 'Review deleted successfully!');
    }
    public function destroy($id)
    {
        return $this->admin_destroy($id);
    }

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
