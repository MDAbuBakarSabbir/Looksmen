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

        $topProducts = Cache::remember('home_top_products_v2', 600, function () {
            $prods = Product::with('firstImage')
                ->where('status', '1')
                ->withCount('orderDetails')
                ->orderByDesc('order_details_count')
                ->take(6)
                ->get();
            if ($prods->isEmpty()) {
                $prods = Product::with('firstImage')->where('status', '1')->latest()->take(6)->get();
            }
            return $prods;
        });
        if (! ($topProducts instanceof Collection) || ($topProducts->isNotEmpty() && ! ($topProducts->first() instanceof Product))) {
            Cache::forget('home_top_products_v2');
            $topProducts = Product::with('firstImage')->where('status', '1')->withCount('orderDetails')->orderByDesc('order_details_count')->take(6)->get();
            if ($topProducts->isEmpty()) {
                $topProducts = Product::with('firstImage')->where('status', '1')->latest()->take(6)->get();
            }
            Cache::put('home_top_products_v2', $topProducts, 600);
        }

        return view('welcome', compact('categories', 'todaysDeals', 'newArivals', 'categoryProducts', 'banners', 'sliders', 'flashSaleProducts', 'topProducts'));
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

            if ($phone) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                $last11 = strlen($cleanPhone) >= 11 ? substr($cleanPhone, -11) : $phone;
            }

            if ($orderId && $phone) {
                $query->where('id', $orderId)->where('phone', 'like', "%{$last11}%");
            } elseif ($orderId) {
                $query->where('id', $orderId);
            } else {
                $query->where('phone', 'like', "%{$last11}%");
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
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%");
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
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%");
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
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%");
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

    public function help()
    {
        $settings = Cache::rememberForever('boot_general_web_settings_map', function () {
            return \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        });
        $storeName = !empty($settings['web_name']) ? $settings['web_name'] : 'Looksmen';
        $contactPhone = $settings['contact_phone'] ?? '01568482005';
        $contactEmail = $settings['contact_email'] ?? 'support@looksmen.com';
        $categories = Category::where('status', '1')->take(8)->get();

        $faqs = Cache::remember('frontend_active_faqs_list', 3600, function () {
            return \App\Models\Faq::where('status', 1)
                ->orderBy('order', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        });

        $faqCategories = \App\Models\Faq::categories();

        return view('Frontend.help', compact('storeName', 'settings', 'contactPhone', 'contactEmail', 'categories', 'faqs', 'faqCategories'));
    }
}
