<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CompareController extends Controller
{
    public function addToCompare(Request $request)
    {
        $productId = $request->id;
        $compare = session()->get('compare', []);

        // Optional: limit to 4 products to compare
        if(count($compare) >= 4) {
            return response()->json([
                'status' => 'warning',
                'message' => 'You can only compare up to 4 products at a time.'
            ]);
        }

        if(isset($compare[$productId])) {
            return response()->json([
                'status' => 'info',
                'message' => 'Product is already in compare list.'
            ]);
        }

        // Fetch product to verify it exists
        $product = Product::find($productId);
        if(!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.'
            ]);
        }

        $compare[$productId] = [
            'id' => $product->id
        ];

        session()->put('compare', $compare);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to compare list successfully.',
            'count' => count($compare)
        ]);
    }

    public function removeFromCompare(Request $request)
    {
        $productId = $request->id;
        $compare = session()->get('compare', []);

        if(isset($compare[$productId])) {
            unset($compare[$productId]);
            session()->put('compare', $compare);
            return response()->json([
                'status' => 'success',
                'message' => 'Product removed from compare list.',
                'count' => count($compare)
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Product not in compare list.'
        ]);
    }

    public function resetCompare()
    {
        session()->forget('compare');
        return redirect()->back()->with('success', 'Compare list cleared.');
    }

    public function countCompare()
    {
        $compare = session()->get('compare', []);
        return response()->json(['count' => count($compare)]);
    }
}
