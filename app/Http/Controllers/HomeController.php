<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $categories = Category::with('subcategories.childcategories')->get()->take(11);
        $products = Product::where('status', '1')->get();
        $categoryProducts = Category::with(['products' => function ($query) {
            $query->where('status', '1')->with('firstImage');
        }])->get();

        $todaysDeals = Product::with('firstImage')->where('todays_deal', '1')->where('status', '1')->take(4)->get();
        $newArivals = Product::with('firstImage')->where('status', '1')->latest()->take(10)->get();
        $banners = Banner::inRandomOrder()->where('status', '1')->get();
        $sliders = Slider::inRandomOrder()->where('status', '1')->get();

        return view('welcome', compact('categories', 'todaysDeals', 'newArivals', 'categoryProducts', 'banners', 'sliders'));
    }

    public function userDash()
    {
        return view('Frontend.dashboard.dashboard');
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        $searched = false;

        $orderId = trim($request->input('order_id', ''));
        $phone = trim($request->input('phone', ''));

        if ($orderId || $phone) {
            $searched = true;
            $query = \App\Models\Orders::query();

            if ($orderId && $phone) {
                $query->where('id', $orderId)->where('phone', $phone);
            } elseif ($orderId) {
                $query->where('id', $orderId);
            } else {
                $query->where('phone', $phone);
            }

            $order = $query->latest()->first();
        }

        return view('Frontend.track_order', compact('order', 'searched'));
    }

    public function flashSale()
    {
        $products = Product::where('status', '1')
            ->where('flash_sale', '1')
            ->with('firstImage')
            ->paginate(12);

        return view('Frontend.flash_sale', compact('products'));
    }
}
