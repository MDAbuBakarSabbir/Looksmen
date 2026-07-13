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
        $categories = \Illuminate\Support\Facades\Cache::remember('home_categories_tree_11', 3600, function () {
            return Category::with('subcategories.childcategories')->take(11)->get();
        });
        if (!($categories instanceof \Illuminate\Support\Collection)) {
            \Illuminate\Support\Facades\Cache::forget('home_categories_tree_11');
            $categories = Category::with('subcategories.childcategories')->take(11)->get();
            \Illuminate\Support\Facades\Cache::put('home_categories_tree_11', $categories, 3600);
        }

        $categoryProducts = \Illuminate\Support\Facades\Cache::remember('home_category_products_v2', 600, function () {
            return Category::whereHas('products', function ($query) {
                $query->where('status', '1');
            })->with(['products' => function ($query) {
                $query->where('status', '1')
                      ->with('firstImage')
                      ->withAvg(['reviews' => function ($q) {
                          $q->where('status', '1');
                      }], 'review_star')
                      ->latest()
                      ->take(12);
            }])->get();
        });
        if (!($categoryProducts instanceof \Illuminate\Support\Collection)) {
            \Illuminate\Support\Facades\Cache::forget('home_category_products_v2');
            $categoryProducts = Category::whereHas('products', fn ($query) => $query->where('status', '1'))
                ->with(['products' => fn ($query) => $query->where('status', '1')->with('firstImage')->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')->latest()->take(12)])
                ->get();
            \Illuminate\Support\Facades\Cache::put('home_category_products_v2', $categoryProducts, 600);
        }

        $todaysDeals = \Illuminate\Support\Facades\Cache::remember('home_todays_deals_v2', 600, function () {
            return Product::with('firstImage')
                ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
                ->where('todays_deal', '1')
                ->where('status', '1')
                ->take(4)
                ->get();
        });
        if (!($todaysDeals instanceof \Illuminate\Support\Collection) || ($todaysDeals->isNotEmpty() && !($todaysDeals->first() instanceof Product))) {
            \Illuminate\Support\Facades\Cache::forget('home_todays_deals_v2');
            $todaysDeals = Product::with('firstImage')->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')->where('todays_deal', '1')->where('status', '1')->take(4)->get();
            \Illuminate\Support\Facades\Cache::put('home_todays_deals_v2', $todaysDeals, 600);
        }

        $newArivals = \Illuminate\Support\Facades\Cache::remember('home_new_arrivals_v2', 600, function () {
            return Product::with('firstImage')
                ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
                ->where('status', '1')
                ->latest()
                ->take(10)
                ->get();
        });
        if (!($newArivals instanceof \Illuminate\Support\Collection) || ($newArivals->isNotEmpty() && !($newArivals->first() instanceof Product))) {
            \Illuminate\Support\Facades\Cache::forget('home_new_arrivals_v2');
            $newArivals = Product::with('firstImage')->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')->where('status', '1')->latest()->take(10)->get();
            \Illuminate\Support\Facades\Cache::put('home_new_arrivals_v2', $newArivals, 600);
        }

        $banners = \Illuminate\Support\Facades\Cache::remember('home_banners_v2', 600, function () {
            return Banner::inRandomOrder()->where('status', '1')->get();
        });
        if (!($banners instanceof \Illuminate\Support\Collection) || ($banners->isNotEmpty() && !($banners->first() instanceof Banner))) {
            \Illuminate\Support\Facades\Cache::forget('home_banners_v2');
            $banners = Banner::inRandomOrder()->where('status', '1')->get();
            \Illuminate\Support\Facades\Cache::put('home_banners_v2', $banners, 600);
        }

        $sliders = \Illuminate\Support\Facades\Cache::remember('home_sliders_v2', 600, function () {
            return Slider::inRandomOrder()->where('status', '1')->get();
        });
        if (!($sliders instanceof \Illuminate\Support\Collection) || ($sliders->isNotEmpty() && !($sliders->first() instanceof Slider))) {
            \Illuminate\Support\Facades\Cache::forget('home_sliders_v2');
            $sliders = Slider::inRandomOrder()->where('status', '1')->get();
            \Illuminate\Support\Facades\Cache::put('home_sliders_v2', $sliders, 600);
        }

        $flashSaleProducts = \Illuminate\Support\Facades\Cache::remember('home_flash_sale_products_v1', 600, function () {
            return Product::with('firstImage')->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')->where('status', '1')->where('flash_sale', '1')->take(6)->get();
        });
        if (!($flashSaleProducts instanceof \Illuminate\Support\Collection) || ($flashSaleProducts->isNotEmpty() && !($flashSaleProducts->first() instanceof Product))) {
            \Illuminate\Support\Facades\Cache::forget('home_flash_sale_products_v1');
            $flashSaleProducts = Product::with('firstImage')->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')->where('status', '1')->where('flash_sale', '1')->take(6)->get();
            \Illuminate\Support\Facades\Cache::put('home_flash_sale_products_v1', $flashSaleProducts, 600);
        }

        return view('welcome', compact('categories', 'todaysDeals', 'newArivals', 'categoryProducts', 'banners', 'sliders', 'flashSaleProducts'));
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

    public function flashSale(Request $request)
    {
        $page = (int) $request->input('page', 1);
        $cacheKey = 'flash_sale_products_page_' . $page;
        $products = \Illuminate\Support\Facades\Cache::remember($cacheKey, 600, function () {
            return Product::where('status', '1')
                ->where('flash_sale', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
                ->paginate(12);
        });

        if (!($products instanceof \Illuminate\Pagination\LengthAwarePaginator) || ($products->count() > 0 && !($products->first() instanceof Product))) {
            \Illuminate\Support\Facades\Cache::forget($cacheKey);
            $products = Product::where('status', '1')
                ->where('flash_sale', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')
                ->paginate(12);
            \Illuminate\Support\Facades\Cache::put($cacheKey, $products, 600);
        }

        return view('Frontend.flash_sale', compact('products'));
    }
}
