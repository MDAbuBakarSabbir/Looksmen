<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function home()
    {
        $categories = Cache::remember('home_categories_tree_11', 3600, function () {
            return Category::with('subcategories.childcategories')->take(11)->get();
        });
        if (! ($categories instanceof Collection)) {
            Cache::forget('home_categories_tree_11');
            $categories = Category::with('subcategories.childcategories')->take(11)->get();
            Cache::put('home_categories_tree_11', $categories, 3600);
        }

        $categoryProducts = Cache::remember('home_category_products_v2', 600, function () {
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
        if (! ($categoryProducts instanceof Collection)) {
            Cache::forget('home_category_products_v2');
            $categoryProducts = Category::whereHas('products', fn ($query) => $query->where('status', '1'))
                ->with(['products' => fn ($query) => $query->where('status', '1')->with('firstImage')->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')->latest()->take(12)])
                ->get();
            Cache::put('home_category_products_v2', $categoryProducts, 600);
        }

        $todaysDeals = Cache::remember('home_todays_deals_v2', 600, function () {
            return Product::with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->where('todays_deal', '1')
                ->where('status', '1')
                ->take(4)
                ->get();
        });
        if (! ($todaysDeals instanceof Collection) || ($todaysDeals->isNotEmpty() && ! ($todaysDeals->first() instanceof Product))) {
            Cache::forget('home_todays_deals_v2');
            $todaysDeals = Product::with('firstImage')->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')->where('todays_deal', '1')->where('status', '1')->take(4)->get();
            Cache::put('home_todays_deals_v2', $todaysDeals, 600);
        }

        $newArivals = Cache::remember('home_new_arrivals_v2', 600, function () {
            return Product::with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->where('status', '1')
                ->latest()
                ->take(10)
                ->get();
        });
        if (! ($newArivals instanceof Collection) || ($newArivals->isNotEmpty() && ! ($newArivals->first() instanceof Product))) {
            Cache::forget('home_new_arrivals_v2');
            $newArivals = Product::with('firstImage')->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')->where('status', '1')->latest()->take(10)->get();
            Cache::put('home_new_arrivals_v2', $newArivals, 600);
        }

        $banners = Cache::remember('home_banners_v2', 600, function () {
            return Banner::inRandomOrder()->where('status', '1')->get();
        });
        if (! ($banners instanceof Collection) || ($banners->isNotEmpty() && ! ($banners->first() instanceof Banner))) {
            Cache::forget('home_banners_v2');
            $banners = Banner::inRandomOrder()->where('status', '1')->get();
            Cache::put('home_banners_v2', $banners, 600);
        }

        $sliders = Cache::remember('home_sliders_v2', 600, function () {
            return Slider::inRandomOrder()->where('status', '1')->get();
        });
        if (! ($sliders instanceof Collection) || ($sliders->isNotEmpty() && ! ($sliders->first() instanceof Slider))) {
            Cache::forget('home_sliders_v2');
            $sliders = Slider::inRandomOrder()->where('status', '1')->get();
            Cache::put('home_sliders_v2', $sliders, 600);
        }

        $flashSaleProducts = Cache::remember('home_flash_sale_products_v1', 600, function () {
            return Product::with('firstImage')->withAvg(['reviews' => function ($q) {
                $q->where('status', '1');
            }], 'review_star')->where('status', '1')->where('flash_sale', '1')->take(6)->get();
        });
        if (! ($flashSaleProducts instanceof Collection) || ($flashSaleProducts->isNotEmpty() && ! ($flashSaleProducts->first() instanceof Product))) {
            Cache::forget('home_flash_sale_products_v1');
            $flashSaleProducts = Product::with('firstImage')->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')->where('status', '1')->where('flash_sale', '1')->take(6)->get();
            Cache::put('home_flash_sale_products_v1', $flashSaleProducts, 600);
        }

        return view('welcome', compact('categories', 'todaysDeals', 'newArivals', 'categoryProducts', 'banners', 'sliders', 'flashSaleProducts'));
    }

    public function userDash()
    {
        $settings = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        $featuresConfig = \Illuminate\Support\Facades\Cache::rememberForever('feature_activations_map', function () {
            return \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
        });

        $emailVerificationFeature = ($featuresConfig['email_verification'] ?? '0') === '1';
        $verificationTemplateActive = ($settings['verification_mail_active'] ?? '0') === '1';
        $otpTemplateActive = ($settings['otp_mail_active'] ?? '0') === '1';

        $emailVerificationRequired = $emailVerificationFeature && ($verificationTemplateActive || $otpTemplateActive);

        if ($emailVerificationRequired && auth()->check() && !auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

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
            $query = Orders::query();

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
        $cacheKey = 'flash_sale_products_page_'.$page;
        $products = Cache::remember($cacheKey, 600, function () {
            return Product::where('status', '1')
                ->where('flash_sale', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->paginate(12);
        });

        if (! ($products instanceof LengthAwarePaginator) || ($products->count() > 0 && ! ($products->first() instanceof Product))) {
            Cache::forget($cacheKey);
            $products = Product::where('status', '1')
                ->where('flash_sale', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => fn ($q) => $q->where('status', '1')], 'review_star')
                ->paginate(12);
            Cache::put($cacheKey, $products, 600);
        }

        return view('Frontend.flash_sale', compact('products'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $catProducts = Product::where('status', '1')
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%");
            })
            ->with('firstImage')
            ->withAvg(['reviews' => function ($q) {
                $q->where('status', '1');
            }], 'review_star')
            ->latest()
            ->paginate(12);

        $categoryType = 'search';

        return view('Frontend.search', compact('catProducts', 'keyword', 'categoryType'));
    }

    public function ajaxSearch(Request $request)
    {
        $keyword = trim($request->input('search', ''));
        if (empty($keyword)) {
            return response()->json(0);
        }

        $products = Product::where('status', '1')
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%");
            })
            ->with('firstImage')
            ->latest()
            ->take(6)
            ->get();

        if ($products->isEmpty()) {
            return response()->json(0);
        }

        $totalCount = Product::where('status', '1')
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%");
            })
            ->count();

        $categories = Category::where('name', 'like', "%{$keyword}%")->take(3)->get();

        return view('Frontend.partials.search_content', compact('products', 'keyword', 'totalCount', 'categories'))->render();
    }

    public function verifyEmail()
    {
        $user = auth()->user() ?? (object) [
            'name' => 'MD Abu Bakar Sabbir',
            'email' => 'sabbir@example.com',
        ];
        $url = url('/verify-account/sample-token');

        return view('Frontend.smtp.mail.verifyMail', compact('user', 'url'));
    }

    public function otpEmail()
    {
        $user = auth()->user() ?? (object) [
            'name' => 'MD Abu Bakar Sabbir',
            'email' => 'sabbir@example.com',
        ];
        $otp = '849201';
        $expireMinutes = 10;

        return view('Frontend.smtp.mail.otpMail', compact('user', 'otp', 'expireMinutes'));
    }

    public function welcomeEmail()
    {
        $user = auth()->user() ?? (object) [
            'name' => 'MD Abu Bakar Sabbir',
            'email' => 'sabbir@example.com',
        ];

        return view('Frontend.smtp.mail.welcomeMail', compact('user'));
    }
}
