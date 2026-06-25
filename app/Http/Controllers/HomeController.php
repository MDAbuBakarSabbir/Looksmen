<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;

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
}
