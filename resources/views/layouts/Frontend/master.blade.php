@php
    use App\Models\GeneralWebSettings;
    use App\Models\Pages;
    use App\Models\Category;
    use Illuminate\Support\Facades\Cache;

    $webinfo = Cache::remember('global_webinfo_first', 3600, function () {
        return GeneralWebSettings::first();
    });
    if (!($webinfo instanceof GeneralWebSettings)) {
        Cache::forget('global_webinfo_first');
        $webinfo = GeneralWebSettings::first();
        Cache::put('global_webinfo_first', $webinfo, 3600);
    }
    $webConfig = Cache::remember('global_webconfig_pluck', 3600, function () {
        return GeneralWebSettings::pluck('value', 'name')->toArray();
    });
    if (!is_array($webConfig) || empty($webConfig)) {
        Cache::forget('global_webconfig_pluck');
        $webConfig = GeneralWebSettings::pluck('value', 'name')->toArray();
        Cache::put('global_webconfig_pluck', $webConfig, 3600);
    }
    $pages = Cache::remember('global_pages_list', 3600, function () {
        return Pages::where('status', 1)->get();
    });
    if (!($pages instanceof \Illuminate\Support\Collection)) {
        Cache::forget('global_pages_list');
        $pages = Pages::where('status', 1)->get();
        Cache::put('global_pages_list', $pages, 3600);
    }
    $categories = Cache::remember('global_categories_tree_11', 3600, function () {
        return Category::with('subcategories.childcategories')->take(11)->get();
    });
    if (!($categories instanceof \Illuminate\Support\Collection)) {
        Cache::forget('global_categories_tree_11');
        $categories = Category::with('subcategories.childcategories')->take(11)->get();
        Cache::put('global_categories_tree_11', $categories, 3600);
    }
    $cartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->count() : count(session('cart', []));
@endphp

<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="//www.looksmen.com/">
    <meta name="file-base-url" content="//www.looksmen.com/public/">

    <title>@hasSection('meta_title') @yield('meta_title') | {{ $webConfig['web_name'] }} @else {{ $webConfig['web_name'] }} | @yield('title') @endif</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', $webConfig['meta_description'] ?? $webConfig['web_description'])" />
    <meta name="keywords" content="@yield('meta_keyword', $webConfig['meta_keyword'] ?? 'looksmen, online shopping Bangladesh, men fashion BD, gadgets Bangladesh, buy online BD, ecommerce Bangladesh')">
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    <!-- Preconnect & DNS Prefetch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">

    @php
        $yieldedImg = trim(view()->yieldContent('meta_image'));
        if (!empty($yieldedImg)) {
            $shareImage = $yieldedImg;
        } elseif (!empty($webConfig['social_banner'])) {
            $shareImage = asset('adminDash/assets/img/layouts/' . $webConfig['social_banner']);
        } elseif (!empty($webConfig['web_logo'])) {
            $shareImage = asset('adminDash/assets/img/layouts/' . $webConfig['web_logo']);
        } elseif (!empty($webConfig['footer_logo'])) {
            $shareImage = asset('adminDash/assets/img/layouts/' . $webConfig['footer_logo']);
        } else {
            $shareImage = asset('adminDash/assets/img/layouts/' . ($webConfig['web_favicon'] ?? 'favicon.png'));
        }

        if ($shareImage && !preg_match('~^https?://~i', $shareImage)) {
            $shareImage = url($shareImage);
        }
    @endphp

    <!-- Schema.org markup for Google -->
    <meta itemprop="name" content="@yield('meta_title', $webConfig['web_name'] ?? config('app.name'))">
    <meta itemprop="description" content="@yield('meta_description', $webConfig['meta_description'] ?? $webConfig['web_description'] ?? '')">
    <meta itemprop="image" content="{{ $shareImage }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@yield('twitter_site', '@looksmen')">
    <meta name="twitter:title" content="@yield('meta_title', $webConfig['web_name'] ?? config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', $webConfig['meta_description'] ?? $webConfig['web_description'] ?? '')">
    <meta name="twitter:image" content="{{ $shareImage }}">

    <!-- Open Graph data (Facebook, WhatsApp, Messenger, LinkedIn, Telegram) -->
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:title" content="@yield('meta_title', $webConfig['web_name'] ?? config('app.name'))" />
    <meta property="og:description" content="@yield('meta_description', $webConfig['meta_description'] ?? $webConfig['web_description'] ?? '')" />
    <meta property="og:url" content="@yield('canonical', url()->current())" />
    <meta property="og:site_name" content="{{ $webConfig['web_name'] ?? config('app.name') }}" />
    <meta property="og:image" content="{{ $shareImage }}" />
    <meta property="og:image:secure_url" content="{{ $shareImage }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:locale" content="en_US" />
    <meta property="fb:app_id" content="1125412091428219">
    
    <!-- Facebook Domain Verification -->
    @if (!empty($webConfig['fbdomainverify']))
        @if (strpos($webConfig['fbdomainverify'], '<') !== false)
            {!! $webConfig['fbdomainverify'] !!}
        @else
            <meta name="facebook-domain-verification" content="{{ $webConfig['fbdomainverify'] }}" />
        @endif
    @else
        <meta name="facebook-domain-verification" content="fyxav3lhjmr6gjtzvyu9o0r4utfakz" />
    @endif

    <!-- Google Domain Verification -->
    @if (!empty($webConfig['gdomainverify']))
        @if (strpos($webConfig['gdomainverify'], '<') !== false)
            {!! $webConfig['gdomainverify'] !!}
        @else
            <meta name="google-site-verification" content="{{ $webConfig['gdomainverify'] }}" />
        @endif
    @endif
    <!-- Favicon -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="icon" href="{{ asset('adminDash/assets/img/layouts/'.$webConfig['web_favicon']) }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;display=swap"
        rel="stylesheet">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/vendors.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/aiz-core.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/custom-style.css">
    <script>
        window.AIZ = window.AIZ || {};
        AIZ.local = {
            nothing_selected: 'Nothing selected',
            nothing_found: 'Nothing found',
            choose_file: 'Choose File',
            file_selected: 'File selected',
            files_selected: 'Files selected',
            add_more_files: 'Add more files',
            adding_more_files: 'Adding more files',
            drop_files_here_paste_or: 'Drop files here, paste or',
            browse: 'Browse',
            upload_complete: 'Upload complete',
            upload_paused: 'Upload paused',
            resume_upload: 'Resume upload',
            pause_upload: 'Pause upload',
            retry_upload: 'Retry upload',
            cancel_upload: 'Cancel upload',
            uploading: 'Uploading',
            processing: 'Processing',
            complete: 'Complete',
            file: 'File',
            files: 'Files',
        };
    </script>

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
        }

        :root {
            --primary: #044244;
            --hov-primary: #044244;
            --soft-primary: rgba(4, 66, 68, 0.1);
        }

        /* Global Theme Product Card Action Buttons (Mobile & Desktop Optimized) */
        .product-card-btn-group {
            display: flex !important;
            gap: 6px !important;
            width: 100% !important;
            margin-top: 8px !important;
            align-items: center !important;
        }
        .product-card-btn-group .btn-card-cart {
            flex: 0 0 36px !important;
            width: 36px !important;
            height: 34px !important;
            padding: 0 !important;
            border-radius: 6px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            background-color: rgba(4, 66, 68, 0.08) !important;
            color: #044244 !important;
            border: 1.5px solid rgba(4, 66, 68, 0.25) !important;
            box-shadow: 0 1px 2px rgba(4, 66, 68, 0.04) !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            cursor: pointer !important;
            text-decoration: none !important;
        }
        .product-card-btn-group .btn-card-cart i {
            font-size: 18px !important;
            line-height: 1 !important;
        }
        .product-card-btn-group .btn-card-cart:hover {
            background-color: #044244 !important;
            color: #ffffff !important;
            border-color: #044244 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 10px rgba(4, 66, 68, 0.2) !important;
        }
        .product-card-btn-group .btn-card-buy {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            height: 34px !important;
            padding: 0 8px !important;
            font-size: 13.5px !important;
            font-weight: 700 !important;
            letter-spacing: 0.2px !important;
            border-radius: 6px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1.2 !important;
            text-decoration: none !important;
            background: linear-gradient(135deg, #044244 0%, #065b5e 100%) !important;
            color: #ffffff !important;
            border: 1.5px solid #044244 !important;
            box-shadow: 0 2px 6px rgba(4, 66, 68, 0.22) !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            white-space: nowrap !important;
            cursor: pointer !important;
        }
        .product-card-btn-group .btn-card-buy:hover {
            background: linear-gradient(135deg, #02292a 0%, #044244 100%) !important;
            border-color: #02292a !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(4, 66, 68, 0.35) !important;
        }
        @media (max-width: 575.98px) {
            .product-card-btn-group {
                gap: 4px !important;
                margin-top: 6px !important;
            }
            .product-card-btn-group .btn-card-cart {
                flex: 0 0 32px !important;
                width: 32px !important;
                height: 32px !important;
                border-radius: 5px !important;
            }
            .product-card-btn-group .btn-card-cart i {
                font-size: 16px !important;
            }
            .product-card-btn-group .btn-card-buy {
                height: 32px !important;
                font-size: 12.5px !important;
                padding: 0 6px !important;
                border-radius: 5px !important;
            }
        }

        #map {
            width: 100%;
            height: 250px;
        }

        #edit_map {
            width: 100%;
            height: 250px;
        }

        .pac-container {
            z-index: 100000;
        }

        /* Mobile category drawer styling */
        .toggle-subcategories-btn i, 
        .toggle-childcategories-btn i {
            transition: transform 0.2s ease;
        }
        .toggle-subcategories-btn.active i, 
        .toggle-childcategories-btn.active i {
            transform: rotate(180deg);
        }
        .subcategory-list {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            background-color: #f8fafc;
            padding-left: 1.5rem;
            padding-right: 1rem;
            padding-top: 0;
            padding-bottom: 0;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease, padding 0.3s ease;
        }
        .subcategory-list.show {
            opacity: 1;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .childcategory-list {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            padding-left: 1rem;
            padding-top: 0;
            padding-bottom: 0;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease, padding 0.3s ease;
        }
        .childcategory-list.show {
            opacity: 1;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }
    </style>

    <!-- Google Tag Manager -->
    <script>
        window.dataLayer = window.dataLayer || [];
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ $webConfig['gtagid'] ?? 'GTM-N36FZRPR' }}');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Custom Tracking / Header Code (if provided) -->
    @if (!empty($webConfig['fb_pixel']))
        {!! $webConfig['fb_pixel'] !!}
    @endif

    <style>
        img.mw-100.h-30px.h-md-40px {
            width: 100%;
        }

        @media (min-width: 1280px) {
            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl {
                max-width: 1280px !important;
            }
        }

        /* Prevent nested containers from causing double padding or layout issues */
        .container .container {
            padding-left: 0 !important;
            padding-right: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        /* Prevent flexbox column width calculation issues with sliders */
        .row, .col, [class*="col-"] {
            min-width: 0;
        }
        .aiz-carousel {
            max-width: 100%;
            width: 100%;
            min-width: 0;
        }
        .slick-list, .slick-track {
            max-width: 100%;
        }

        /* Hide in-page user-sidenav on mobile, but display inside aiz-mobile-side-nav drawer */
        @media (max-width: 1199.98px) {
            .user-sidenav {
                display: none !important;
            }
            .aiz-mobile-side-nav .collapse-sidebar {
                height: 100vh !important;
                max-height: 100vh !important;
                overflow-y: auto !important;
                background: #ffffff !important;
            }
            .aiz-mobile-side-nav .user-sidenav {
                display: block !important;
                margin-bottom: 0 !important;
                border-radius: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }
            .mobile-dashboard-toggle {
                display: inline-block !important;
            }
        }

        /* On desktop always hide the mobile toggle */
        @media (min-width: 1200px) {
            .mobile-dashboard-toggle {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $webConfig['gtagid'] ?? 'GTM-N36FZRPR' }}" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="aiz-main-wrapper d-flex flex-column">
        <div class="top-navbar bg-white border-bottom border-soft-secondary z-1035">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col">
                        <ul class="list-inline d-flex justify-content-between justify-content-lg-start mb-0">
                            <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                                <a href="tel:{{ $webConfig['contact_phone'] ?? '01568482005' }}" class="text-reset d-inline-block opacity-60 py-2">
                                    <i class="la la-phone"></i>
                                    <span>Help line</span>
                                    <span>{{ $webConfig['contact_phone'] ?? '01568482005' }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-5 text-right d-none d-lg-block">
                        <ul class="list-inline mb-0 h-100 d-flex justify-content-end align-items-center">
                            @auth
                                <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                                        <a href="{{ route('dashboard') }}"
                                            class="text-reset d-inline-block opacity-60 py-2">Account</a>
                                    </li>
                                <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                class="text-reset d-inline-block opacity-60 py-2">Logout</a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @else

                                    <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                                        <a href="{{ route('login') }}"
                                            class="text-reset d-inline-block opacity-60 py-2">Login</a>
                                    </li>
                                    <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                                        <a href="{{ route('register') }}" class="text-reset d-inline-block opacity-60 py-2">Sign Up</a>

                                    </li> @endauth

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Top Bar -->
    <header class="sticky-top
        z-1020 bg-white border-bottom shadow-sm">
    <div class="position-relative logo-bar-area z-1">
        <div class="container">
            <div class="d-flex align-items-center">

                <div class="col-auto col-xl-3 pl-0 pr-3 d-flex align-items-center">
                    @auth
                    <button type="button" class="btn p-0 mr-3 d-xl-none mobile-dashboard-toggle" aria-label="Open Mobile Menu" style="font-size: 24px; color: #000; background: none; border: none; outline: none; cursor: pointer;" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                        <i class="las la-bars"></i>
                    </button>
                    @endauth
                    <a class="d-block py-20px mr-3 ml-0" href="{{ url('/') }}">
                        <img src="{{ asset('adminDash/assets/img/layouts') }}/{{ $webConfig['web_logo'] ?? 'Logo.png' }}"
                            alt="LOOKSMEN" class="mw-100 h-40px h-md-60px" height="40">
                    </a>

                    <div class="hover-category-menu position-absolute w-100 top-100 left-0 right-0 z-3 d-none"
                        id="hover-category-menu">
                        <div class="container">
                            <div class="row gutters-10 position-relative">
                                <div class="col-lg-3 position-static">
                                    <div class="aiz-category-menu bg-white rounded  shadow-lg" id="category-sidebar">
                                        <div
                                            class="p-3 bg-soft-primary d-none d-lg-block rounded-top all-category position-relative text-left">
                                            <span class="fw-600 fs-16 mr-3">Categories</span>
                                            <a href="https://www.looksmen.com/categories" class="text-reset">
                                                <span class="d-none d-lg-inline-block">All categories &gt;</span>
                                            </a>
                                        </div>
                                        <ul class="list-unstyled categories no-scrollbar py-2 mb-0 text-left">
                                            @foreach ($categories as $category)
                                                <li class="category-nav-element" data-id="{{ $category->id }}">
                                                    <a href="{{ route('catProductView', $category->slug) }}"
                                                        class="text-truncate text-reset py-2 px-3 d-block">
                                                        <img class="cat-image mr-2 opacity-60 ls-is-cached lazyloaded"
                                                            src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                                            data-src="{{ asset('frontend') }}/uploads/jWfNXjIDci5blBvokxp9u0RS89WqXeoBNws92KlQ.svg"
                                                            width="16" alt="Men's Clothing &amp; Fashion"
                                                            onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder.jpg';">
                                                        <span class="cat-name">{{ $category->name }}</span>
                                                    </a>
                                                    <div
                                                        class="sub-cat-menu c-scrollbar-light rounded shadow-lg p-4 loaded">
                                                        <div class="card-columns">
                                                            @if ($category->subcategories->count() > 0)
                                                                <div class="card shadow-none border-0">
                                                                    <ul class="list-unstyled mb-3">
                                                                        @foreach ($category->subcategories as $subCat)
                                                                            <li class="fw-600 border-bottom pb-2 mb-3">
                                                                                <a class="text-reset"
                                                                                    href="{{ route('subCatProductView', [$category->slug, $subCat->slug]) }}">
                                                                                    {{ $subCat->name }}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @else
                                                                <div class="col-12 text-center text-muted">
                                                                    No Sub-categories found.
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="d-lg-none ml-auto mr-0">
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" aria-label="Toggle Search" data-toggle="class-toggle"
                        data-target=".front-header-search">
                        <i class="las la-search la-flip-horizontal la-2x"></i>
                    </a>
                </div>

                <div class="flex-grow-1 front-header-search d-flex align-items-center bg-white">
                    <div class="position-relative flex-grow-1">
                        <form action="{{ route('front.search') }}" method="GET" class="stop-propagation">
                            <div class="d-flex position-relative align-items-center">
                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                    <button class="btn px-2" type="button" aria-label="Close Search"><i
                                            class="la la-2x la-long-arrow-left"></i></button>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="border-0 border-lg form-control" id="search"
                                        name="keyword" placeholder="I am shopping for..." autocomplete="off">
                                    <div class="input-group-append d-none d-lg-block">
                                        <button style="border-radius: 0px;" class="btn btn-primary" type="submit" aria-label="Submit Search">
                                            <i class="la la-search la-flip-horizontal fs-18"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                            style="min-height: 200px">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16">

                            </div>
                            <div id="search-content" class="text-left">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-none d-lg-none ml-3 mr-0">
                    <div class="nav-search-box">
                        <a href="#" class="nav-box-link">
                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                        </a>
                    </div>
                </div>

                <div class="d-none d-lg-block ml-3 mr-0">
                    <div class="" id="compare">
                        <a href="{{ route('ProductCompare') }}"
                            class="d-flex align-items-center text-reset">
                            <i class="la la-refresh la-2x opacity-80 "></i>
                            <span class="flex-grow-1 ml-1">
                                <span class="badge badge-primary badge-inline badge-pill compare-count">{{ count(session()->get('compare', [])) }}</span>
                                <span class=" nav-box-text d-none d-xl-block opacity-100">Compare</span>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="d-none d-lg-block ml-3 mr-0">
                    <div class="" id="wishlist">
                        <a href="{{ auth()->check() ? route('wishlist') : route('login') }}" class="d-flex align-items-center text-reset">
                            <i class="la la-heart-o la-2x opacity-80 "></i>
                            <span class="flex-grow-1 ml-1">
                                <span class="badge badge-primary  badge-inline badge-pill">0</span>

                                <span class=" nav-box-text d-none d-xl-block opacity-100">Wishlist</span>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="d-none d-lg-block  align-self-stretch ml-3 mr-0" data-hover="dropdown">

                    <div class="nav-cart-box dropdown h-100" id="cart_items">
                        <a href="javascript:void(0)" onclick="showCartModal()"
                            class="d-flex align-items-center text-reset h-100">
                            <i class="la la-shopping-cart la-2x opacity-80"></i>
                            <span class="flex-grow-1 ml-1">
                                <span class="badge badge-primary badge-inline badge-pill cart-count">
                                    {{ $cartCount }}
                                </span>
                                <span class="nav-box-text d-none d-xl-block">Cart</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class=" border-top border-gray-200 py-1">
        <div class="container">
            <ul class="list-inline mb-0 pl-0 mobile-hor-swipe text-center">
                <li class="list-inline-item mr-0">
                    <a href="{{ url('/') }}"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        Home
                    </a>
                </li>
                <li class="list-inline-item mr-0">
                    <a href="{{ route('front.trackOrder') }}"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        Track Order
                    </a>
                </li>
                @php
                    $hasActiveFlashSale = \Illuminate\Support\Facades\Cache::remember('has_active_flash_sale_v1', 300, function () {
                        return \App\Models\Product::where('status', '1')->where('flash_sale', '1')->exists();
                    });
                @endphp
                @if($hasActiveFlashSale)
                <li class="list-inline-item mr-0">
                    <a href="{{ route('front.flashSale') }}"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        Flash Sale
                    </a>
                </li>
                @endif
                <li class="list-inline-item mr-0">
                    <a href="{{ route('front.allCategory') }}"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        All Categories
                    </a>
                </li>
                <li class="list-inline-item mr-0">
                    <a href="{{ route('front.help') }}"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        Help Center
                    </a>
                </li>
            </ul>
        </div>
    </div>
    </header>

    <div class="mb-4 pt-3">
        <div class="container">
            @yield('content')
        </div>
    </div>


    <div class="footsection {{ View::hasSection('hide_everything') ? 'd-none' : '' }}">
        <section class="bg-dark py-5 text-light footer-widget">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-xl-4 text-center text-md-left">
                        <div class="mt-4">
                            <a href="{{ url('/') }}" class="d-block">
                                <img class="lazyload" src="{{ asset('frontend') }}/assets/img/placeholder-rect.jpg"
                                    data-src="{{ asset('adminDash/assets/img/layouts') }}/{{ $webConfig['footer_logo'] }}"
                                    alt="LOOKSMEN" height="44">
                            </a>
                            <div class="my-3 opacity-70">
                                {!! $webConfig['web_description'] ?? '' !!}
                            </div>
                            <div class="d-inline-block d-md-block mb-4">
                                <form class="form-inline" method="POST" action="{{ url('/subscribers') }}">
                                    @csrf
                                    <div class="form-group mb-0">
                                        <input type="email" class="form-control" placeholder="Your Email Address"
                                            name="email" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Subscribe
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 ml-xl-auto col-md-4 mr-0">
                        <div class="text-center text-md-left mt-4">
                            <h4 class="fs-13 text-uppercase fw-600 border-bottom border-gray-900 pb-2 mb-4">
                                Contact Info
                            </h4>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="d-block opacity-30">Address:</span>
                                    <span class="d-block opacity-70">{{ $webConfig['contact_address'] }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="d-block opacity-30">Phone:</span>
                                    <a class="text-reset"
                                        href="tel:{{ $webConfig['contact_phone'] }}">{{ $webConfig['contact_phone'] }}</a>
                                </li>
                                <li class="mb-2">
                                    <span class="d-block opacity-30">Email:</span>
                                    <span class="d-block opacity-70">
                                        <a href="mailto:{{ $webConfig['contact_email'] }}"
                                            class="text-reset">{{ $webConfig['contact_email'] }}</a>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <div class="text-center text-md-left mt-4">
                            <h4 class="fs-13 text-uppercase fw-600 border-bottom border-gray-900 pb-2 mb-4">
                                Company
                            </h4>
                            <ul class="list-unstyled">
                                @foreach ($pages as $page)
                                    <li class="mb-2">
                                        <a href="{{ route('pages', $page->slug) }}"
                                            class="opacity-50 hov-opacity-100 text-reset">
                                            {{ $page->page_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-2">
                        <div class="text-center text-md-left mt-4">
                            <h4 class="fs-13 text-uppercase fw-600 border-bottom border-gray-900 pb-2 mb-4">
                                My Account
                            </h4>
                            <ul class="list-unstyled">
                                @auth
                                    <li class="mb-2">
                                        <a class="opacity-50 hov-opacity-100 text-reset" href="{{ route('dashboard') }}">
                                            Account
                                        </a>
                                    </li>
                                @else
                                    <li class="mb-2">
                                        <a class="opacity-50 hov-opacity-100 text-reset" href="{{ route('login') }}">
                                            Login
                                        </a>
                                    </li>
                                @endauth
                                <li class="mb-2">
                                    <a class="opacity-50 hov-opacity-100 text-reset" href="{{ route('purchaseHistory') }}">
                                        Order History
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="opacity-50 hov-opacity-100 text-reset" href="{{ route('wishlist') }}">
                                        My Wishlist
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="opacity-50 hov-opacity-100 text-reset" href="{{ route('front.trackOrder') }}">
                                        Track Order
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="opacity-50 hov-opacity-100 text-reset" href="{{ route('front.help') }}">
                                        Help Center
                                    </a>
                                </li>
                                @if (addon_is_activated('affiliate_system'))
                                <li class="mb-2">
                                    <a class="opacity-50 hov-opacity-100 text-light"
                                        href="@auth
                                        @php
                                            $affiliate = Auth::user()->affiliate_user;
                                        @endphp
                                        @if ($affiliate && $affiliate->status == 1)
                                            {{ route('affiliate.user.index') }}
                                        @else
                                            {{ route('affiliate.index') }}
                                        @endif
                                    @else
                                        {{ route('login') }} @endauth">Be an affiliate partner</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="pt-3 pb-7 pb-xl-3 bg-black text-light">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4">
                        <div class="text-center text-md-left" current-verison="5.5.4">
                            <p>All Rights Reserved By <a href="https://looksmen.com" style="color: white;">LOOKSMEN</a><br></p>
                            <p>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-inline my-3 my-md-0 social colored text-center">
                            <li class="list-inline-item">
                                <a href="https://facebook.com/looksmenstore" target="_blank" rel="noopener noreferrer" aria-label="Facebook Page" class="facebook"><i
                                        class="lab la-facebook-f"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://twitter.com/looksmenstore" target="_blank" rel="noopener noreferrer" aria-label="Twitter Profile" class="twitter"><i
                                        class="lab la-twitter"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://instagram.com/looksmenstore" target="_blank" rel="noopener noreferrer" aria-label="Instagram Profile" class="instagram"><i
                                        class="lab la-instagram"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://youtube.com/looksmenstore" target="_blank" rel="noopener noreferrer" aria-label="YouTube Channel" class="youtube"><i
                                        class="lab la-youtube"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <div class="text-center text-md-right">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <span style="color: white;">&nbsp;Website Designed By: <a href="https://www.facebook.com/sabalontech"
                                            target="_blank"><span style="color: white;">SABALON TECH</span></a></span>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>


        <div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom bg-white shadow-lg border-top rounded-top"
            style="box-shadow: 0px -1px 10px rgb(0 0 0 / 15%)!important; ">
            <div class="row align-items-center gutters-5">
                <div class="col">
                    <a href="{{ url('/') }}" class="text-reset d-block text-center pb-2 pt-3">
                        <i class="las la-home fs-20 opacity-60 opacity-100 text-primary"></i>
                        <span class="d-block fs-10 fw-600 opacity-60 opacity-100 fw-600">Home</span>
                    </a>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" class="text-reset d-block text-center pb-2 pt-3" id="mobile-categories-toggle">
                        <i class="las la-list-ul fs-20 opacity-60 "></i>
                        <span class="d-block fs-10 fw-600 opacity-60 ">Categories</span>
                    </a>
                </div>
                <div class="col-auto">
                    <a href="javascript:void(0)" onclick="showCartModal()" class="text-reset d-block text-center pb-2 pt-3">
                        <span id="mobileCartCircle"
                            class="align-items-center bg-primary border border-white border-width-4 d-flex justify-content-center position-relative rounded-circle size-50px {{ ($cartCount ?? 0) > 0 ? 'cart-circle-glow' : '' }}"
                            style="margin-top: -33px;box-shadow: 0px -5px 10px rgb(0 0 0 / 15%);border-color: #fff !important;">
                            <i class="las la-shopping-bag la-2x text-white"></i>
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 opacity-60 ">
                            Cart
                            (<span class="cart-count">
                                {{ $cartCount }}
                            </span>)
                        </span>
                    </a>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" class="text-reset d-block text-center pb-2 pt-3">
                        <span class="d-inline-block position-relative px-2">
                            <i class="las la-bell fs-20 opacity-60 "></i>
                        </span>
                        <span class="d-block fs-10 fw-600 opacity-60 ">Notifications</span>
                    </a>
                </div>
                <div class="col">
                    @auth
                        <a href="javascript:void(0)"
                            class="text-reset d-block text-center pb-2 pt-3" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                            <span class="d-block mx-auto">
                                <img src="{{ asset('frontend/assets/img/avatar-place.png') }}"
                                    class="rounded-circle size-20px">
                            </span>
                            <span class="d-block fs-10 fw-600 opacity-60">Account</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-reset d-block text-center pb-2 pt-3">
                            <span class="d-block mx-auto">
                                <img src="{{ asset('frontend/assets/img/avatar-place.png') }}"
                                    class="rounded-circle size-20px">
                            </span>
                            <span class="d-block fs-10 fw-600 opacity-60">Account</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>



        <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-angles-up"></i></button>


    </div>

    <style>
        .color-box {
            transition: all 0.2s ease;
        }

        .color-input:checked+.color-box {
            border-color: #000 !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .modal-backdrop {
            display: none !important;
            /* ডাবল কালো ছায়া বন্ধ করতে */
        }

        /* Mobile Categories Drawer */
        .mobile-categories-drawer.show {
            left: 0 !important;
        }

        .mobile-categories-drawer .subcategory-list,
        .mobile-categories-drawer .childcategory-list {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .mobile-categories-drawer .toggle-subcategories-btn i,
        .mobile-categories-drawer .toggle-childcategories-btn i {
            transition: transform 0.3s ease;
        }
        
        .mobile-categories-drawer .toggle-subcategories-btn.active i,
        .mobile-categories-drawer .toggle-childcategories-btn.active i {
            transform: rotate(180deg);
        }

        /* Glowing Pulse Animation for Mobile Cart Circle when products exist */
        @keyframes cartGlowPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.8), 0px -5px 10px rgba(0, 0, 0, 0.15);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 0 12px rgba(99, 102, 241, 0), 0 0 20px rgba(99, 102, 241, 0.9);
                transform: scale(1.08);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0), 0px -5px 10px rgba(0, 0, 0, 0.15);
                transform: scale(1);
            }
        }

        .cart-circle-glow {
            animation: cartGlowPulse 1.8s infinite cubic-bezier(0.66, 0, 0, 1) !important;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            border-color: #ffffff !important;
        }
    </style>

    <div class="modal fade" id="cart-modal" tabindex="-1" role="dialog" aria-hidden="true"
        style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document" style="max-height: 90vh;">
            <div class="modal-content" id="cart-modal-content" style="border-radius: 15px; overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
            </div>
        </div>
    </div>


    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">Login</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="email" class="form-control h-auto form-control-lg" value=""
                                    placeholder="Email" name="email" required>
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg"
                                    placeholder="Password" required>
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember">
                                        <span class="opacity-60">Remember Me</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}" class="text-reset opacity-60 fs-14">Forgot
                                        password?</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit" class="btn btn-primary btn-block fw-600">Login</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">Don't have an account?</p>
                            <a href="{{ route('register') }}">Register Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirm_modal(delete_url) {
            jQuery('#confirm-delete').modal('show', {
                backdrop: 'static'
            });
            document.getElementById('delete_link').setAttribute('href', delete_url);
        }
    </script>





    @auth
        <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
            <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static"
                data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
            <div class="collapse-sidebar bg-white border-0 p-0 shadow-lg" style="height: 100vh; overflow-y: auto;">
                @include('Frontend.dashboard.partials.usersideNav')
            </div>
        </div>
    @endauth

    <!-- Dashboard mobile sidebar backdrop -->
    <div class="user-sidenav-backdrop" id="userSidenavBackdrop"></div>

    <!-- Mobile Categories Drawer -->
    <div class="mobile-categories-drawer bg-white shadow-lg" id="mobileCategoriesDrawer" style="position: fixed; top: 0; left: -280px; width: 280px; height: 100vh; z-index: 1050; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); overflow-y: auto;">
        <div class="p-3 bg-primary text-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-600 fs-16"><i class="las la-list-ul mr-2"></i> Categories</h5>
            <button type="button" class="btn text-white p-0" id="mobile-categories-close-btn" style="font-size: 24px; line-height: 1; background: none; border: none; outline: none; cursor: pointer;"><i class="las la-times"></i></button>
        </div>
        <ul class="list-unstyled mb-0 py-2 mobile-category-list">
            @foreach ($categories as $category)
                <li class="border-bottom">
                    <div class="d-flex align-items-center justify-content-between py-3 px-4">
                        <a href="{{ route('catProductView', $category->slug) }}" class="text-reset text-dark fw-600 flex-grow-1 d-flex align-items-center" style="text-decoration: none;">
                            <img class="cat-image mr-3 opacity-60 lazyload"
                                src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                data-src="{{ asset('Uploads/'.$category->banner) }}"
                                width="24"
                                height="24"
                                style="object-fit: cover; border-radius: 4px;"
                                alt="{{ $category->name }}"
                                onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder.jpg';">
                            <span>{{ $category->name }}</span>
                        </a>
                        @if ($category->subcategories->count() > 0)
                            <button type="button" class="btn p-0 text-muted toggle-subcategories-btn" style="font-size: 18px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; background: none; border: none; outline: none;">
                                <i class="las la-angle-down"></i>
                            </button>
                        @endif
                    </div>
                    
                    @if ($category->subcategories->count() > 0)
                        <ul class="list-unstyled subcategory-list">
                            @foreach ($category->subcategories as $subCat)
                                <li class="py-2 border-bottom-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="{{ route('subCatProductView', [$category->slug, $subCat->slug]) }}" class="text-reset text-dark fs-14 fw-500 py-1 flex-grow-1" style="text-decoration: none;">
                                            {{ $subCat->name }}
                                        </a>
                                        @if ($subCat->childcategories->count() > 0)
                                            <button type="button" class="btn p-0 text-muted toggle-childcategories-btn" style="font-size: 16px; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; background: none; border: none; outline: none;">
                                                <i class="las la-angle-down"></i>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    @if ($subCat->childcategories->count() > 0)
                                        <ul class="list-unstyled childcategory-list">
                                            @foreach ($subCat->childcategories as $childCat)
                                                <li class="py-1">
                                                    <a href="{{ route('childCatProductView', [$category->slug, $subCat->slug, $childCat->slug]) }}" class="text-reset text-muted fs-13 py-1 d-block" style="text-decoration: none;">
                                                        <i class="las la-minus mr-1 fs-10 opacity-50"></i> {{ $childCat->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    <div class="user-sidenav-backdrop" id="mobileCategoriesBackdrop" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5); z-index: 1040; display: none; opacity: 0; transition: opacity 0.3s ease;"></div>

    <!-- SCRIPTS -->
    <script src="{{ asset('frontend') }}/assets/js/vendors.js"></script>
    <script src="{{ asset('frontend') }}/assets/js/jquryui.js"></script>
    <script>
        if (typeof $ !== 'undefined' && typeof $.widget !== 'undefined' && typeof $.widget.bridge === 'function') {
            $.widget.bridge('uitooltip', $.ui.tooltip);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('frontend') }}/assets/js/aiz-core.js?v=1.1"></script>
    <script src="{{ asset('frontend') }}/assets/js/custom.js?v=1.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Mobile Sidenav drawer logic
            function toggleUserSidenav() {
                var $sidenav = $('.user-sidenav');
                var $backdrop = $('#userSidenavBackdrop');
                if ($sidenav.length > 0) {
                    $sidenav.toggleClass('show');
                    $backdrop.toggleClass('show');
                    $('body').toggleClass('overflow-hidden');
                }
            }

            function closeUserSidenav() {
                $('.user-sidenav').removeClass('show');
                $('#userSidenavBackdrop').removeClass('show');
                $('body').removeClass('overflow-hidden');
            }

            // Mobile Categories drawer logic
            function toggleCategoriesDrawer() {
                var $drawer = $('#mobileCategoriesDrawer');
                var $backdrop = $('#mobileCategoriesBackdrop');
                if ($drawer.length > 0) {
                    var isShowing = $drawer.hasClass('show');
                    if (isShowing) {
                        $drawer.removeClass('show');
                        $backdrop.removeClass('show').fadeOut(200);
                        $('body').removeClass('overflow-hidden');
                    } else {
                        $drawer.addClass('show');
                        $backdrop.addClass('show').fadeIn(200);
                        $('body').addClass('overflow-hidden');
                    }
                }
            }

            // Bind Categories toggle click
            $('#mobile-categories-toggle').on('click', function(e) {
                e.preventDefault();
                toggleCategoriesDrawer();
            });

            function closeCategoriesDrawer() {
                $('#mobileCategoriesDrawer').removeClass('show');
                $('#mobileCategoriesBackdrop').removeClass('show').fadeOut(200);
                $('body').removeClass('overflow-hidden');
            }

            // Bind Categories close events
            $(document).on('click', '#mobile-categories-close-btn, #mobileCategoriesBackdrop', closeCategoriesDrawer);
            $(document).on('click', '#mobileCategoriesDrawer a:not(.toggle-subcategories-btn, .toggle-childcategories-btn)', closeCategoriesDrawer);

            // Toggle Subcategories in mobile drawer
            $(document).on('click', '.toggle-subcategories-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $btn = $(this);
                var $subList = $btn.closest('li').find('> .subcategory-list');
                
                $btn.toggleClass('active');
                $subList.toggleClass('show');
                
                if ($subList.hasClass('show')) {
                    $subList.css('max-height', $subList[0].scrollHeight + 'px');
                    setTimeout(function() {
                        if ($subList.hasClass('show')) {
                            $subList.css('max-height', 'none');
                        }
                    }, 300);
                } else {
                    $subList.css('max-height', $subList[0].scrollHeight + 'px');
                    $subList[0].offsetHeight; // force reflow
                    $subList.css('max-height', '0px');
                }
            });

            // Toggle Child categories in mobile drawer
            $(document).on('click', '.toggle-childcategories-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $btn = $(this);
                var $childList = $btn.closest('li').find('> .childcategory-list');
                var $parentSubList = $btn.closest('.subcategory-list');
                
                $btn.toggleClass('active');
                $childList.toggleClass('show');
                
                if ($childList.hasClass('show')) {
                    if ($parentSubList.css('max-height') === 'none') {
                        $parentSubList.css('max-height', $parentSubList[0].scrollHeight + 'px');
                    }
                    $childList.css('max-height', $childList[0].scrollHeight + 'px');
                    
                    setTimeout(function() {
                        if ($childList.hasClass('show')) {
                            $childList.css('max-height', 'none');
                        }
                        if ($parentSubList.length > 0 && $parentSubList.hasClass('show')) {
                            $parentSubList.css('max-height', 'none');
                        }
                    }, 300);
                } else {
                    if ($parentSubList.css('max-height') === 'none') {
                        $parentSubList.css('max-height', $parentSubList[0].scrollHeight + 'px');
                    }
                    $childList.css('max-height', $childList[0].scrollHeight + 'px');
                    $childList[0].offsetHeight; // force reflow
                    $childList.css('max-height', '0px');
                    
                    setTimeout(function() {
                        if ($parentSubList.length > 0 && $parentSubList.hasClass('show')) {
                            $parentSubList.css('max-height', 'none');
                        }
                    }, 300);
                }
            });

            // Bind click event
            $(document).on('click', '.mobile-dashboard-toggle', function(e) {
                e.preventDefault();
                var $sidenav = $('.user-sidenav');
                if ($sidenav.length > 0) {
                    toggleUserSidenav();
                } else {
                    // Redirect to dashboard with hash to open menu automatically
                    window.location.href = "{{ route('dashboard') }}#open-menu";
                }
            });

            // Close when clicking backdrop or close button
            $('#userSidenavBackdrop').on('click', closeUserSidenav);
            $(document).on('click', '#mobile-sidenav-close-btn', closeUserSidenav);
            $(document).on('click', '.user-sidenav .user-nav-link', closeUserSidenav);

            // Add close button to user-sidenav header if on mobile
            if ($('.user-sidenav').length > 0 && $('#mobile-sidenav-close-btn').length === 0) {
                $('.user-sidenav-header').addClass('position-relative').prepend(
                    '<button type="button" id="mobile-sidenav-close-btn" class="btn p-0 d-lg-none" style="position: absolute; top: 15px; right: 15px; color: white; font-size: 24px; z-index: 10; border: none; background: none; outline: none;">&times;</button>'
                );
            }

            // Check if page loaded with hash to open menu
            if (window.location.hash === '#open-menu') {
                history.replaceState(null, null, ' ');
                setTimeout(function() {
                    toggleUserSidenav();
                }, 300);
            }

            $('.category-nav-element').each(function(i, el) {
                $(el).on('mouseover', function() {
                    if (!$(el).find('.sub-cat-menu').hasClass('loaded')) {
                        $.post('category/nav-element-list.html', {
                            _token: AIZ.data.csrf,
                            id: $(el).data('id')
                        }, function(data) {
                            $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                        });
                    }
                });
            });
            if ($('#lang-change').length > 0) {
                $('#lang-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();
                        var $this = $(this);
                        var locale = $this.data('flag');
                        $.post('language.html', {
                            _token: AIZ.data.csrf,
                            locale: locale
                        }, function(data) {
                            location.reload();
                        });

                    });
                });
            }

            if ($('#currency-change').length > 0) {
                $('#currency-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();
                        var $this = $(this);
                        var currency_code = $this.data('currency');
                        $.post('currency.html', {
                            _token: AIZ.data.csrf,
                            currency_code: currency_code
                        }, function(data) {
                            location.reload();
                        });

                    });
                });
            }
        });

        var searchTimeout = null;
        $('#search').on('keyup focus', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                search();
            }, 250);
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.front-header-search').length) {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        });

        function search() {
            var searchKey = $('#search').val();
            if (searchKey.length > 0) {
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route("front.ajaxSearch") }}', {
                    _token: '{{ csrf_token() }}',
                    search: searchKey
                }, function(data) {
                    if (data == '0') {
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html(
                            'Sorry, nothing found for <strong>"' + searchKey + '"</strong>');
                        $('.search-preloader').addClass('d-none');

                    } else {
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            } else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }


        function addToCompare(id) {
            $.post('compare/addToCompare.html', {
                _token: AIZ.data.csrf,
                id: id
            }, function(data) {
                $('#compare').html(data);
                AIZ.plugins.notify('success', "Item has been added to compare list");
                $('#compare_items_sidenav').html(parseInt($('#compare_items_sidenav').html()) + 1);
            });
        }

        function addToWishList(id) {
            @auth
            $.post('{{ route('wishlist.add') }}', {
                _token: '{{ csrf_token() }}',
                product_id: id
            }, function(response) {
                if (response.status === 'success') {
                    AIZ.plugins.notify('success', response.message);
                } else if (response.status === 'warning') {
                    AIZ.plugins.notify('warning', response.message);
                } else {
                    AIZ.plugins.notify('error', response.message);
                }
            }).fail(function(xhr) {
                AIZ.plugins.notify('error', 'Something went wrong');
            });
            @else
            AIZ.plugins.notify('warning', "Please login first");
            @endauth
        }
    </script>

    <script>
        //Get the button
        var mybutton = document.getElementById("myBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            // document.body.scrollTop = 0 ;
            // document.documentElement.scrollTop = 0;
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            // ১. Add to Cart Click
            $(document).on('click', '.action-add-to-cart', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let quantity = $('input[name="quantity"]').val() || 1;
                addToCart(id, { quantity: quantity });
            });

            // Quantity picker plus/minus buttons handler
            $(document).on('click', '.quantity-picker-btn', function(e) {
                e.preventDefault();
                let type = $(this).data('type');
                let field = $(this).data('field');
                let input = $('input[name="' + field + '"]');
                let currentVal = parseInt(input.val()) || 1;
                
                if (type === 'minus') {
                    if (currentVal > 1) {
                        input.val(currentVal - 1).change();
                    }
                } else if (type === 'plus') {
                    let maxVal = parseInt(input.attr('max')) || 10;
                    if (currentVal < maxVal) {
                        input.val(currentVal + 1).change();
                    }
                }
            });

            // Toggle disabled state of minus button based on quantity value
            $(document).on('change', 'input[name="quantity"]', function() {
                let val = parseInt($(this).val()) || 1;
                let minusBtn = $('.quantity-picker-btn[data-type="minus"]');
                if (val <= 1) {
                    minusBtn.attr('disabled', true);
                } else {
                    minusBtn.removeAttr('disabled');
                }
            });
        });

        // Track AddToCart Event for Google Tag Manager (DataLayer)
        function trackAddToCart(data) {
            if (!data || data.status !== 'success') return;
            var productId = String(data.product_id || '');
            var productName = data.product_name || '';
            var productPrice = parseFloat(data.product_price) || 0;
            var quantity = parseInt(data.quantity) || 1;
            var currency = data.currency || 'BDT';
            var totalVal = productPrice * quantity;
            var eventId = 'add_to_cart_' + productId + '_' + Date.now();

            // Push to Google Tag Manager DataLayer
            try {
                var gtmPayload = {
                    'event': 'add_to_cart',
                    'event_id': eventId,
                    'content_name': productName,
                    'content_ids': [productId],
                    'content_id': productId,
                    'content_type': 'product',
                    'contents': [{
                        'id': productId,
                        'quantity': quantity,
                        'item_price': productPrice
                    }],
                    'value': totalVal,
                    'currency': currency,
                    'quantity': quantity,
                    'item_id': productId,
                    'item_name': productName,
                    'price': productPrice,
                    'ecommerce': {
                        'currency': currency,
                        'value': totalVal,
                        'items': [{
                            'item_id': productId,
                            'item_name': productName,
                            'price': productPrice,
                            'quantity': quantity,
                            'currency': currency
                        }]
                    }
                };

                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({ ecommerce: null }); // Clear previous ecommerce object
                window.dataLayer.push(gtmPayload);

                console.log("GTM AddToCart DataLayer Event Pushed:", eventId, productName, [productId]);
            } catch (e) {
                console.error("GTM AddToCart Error:", e);
            }
        }

        // ২. মেইন Add to Cart ফাংশন
        function addToCart(id, options = {}) {
            if (!options.quantity) {
                options.quantity = 1;
            }
            $.post('{{ route('cart.add') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                ...options
            }, function(data) {
                if (data.status === 'show_options') {
                    // যদি কালার/সাইজ সিলেক্ট করতে হয়
                    $('#cart-modal-content').html(data.view);
                    $('#cart-modal').modal('show');
                } else if (data.status === 'success') {
                    trackAddToCart(data);
                    // নেভবার আপডেট
                    updateNavCart();

                    // মোবাইল এবং ডেক্সটপ সব ভিউতেই পপআপ মডাল দেখাবে
                    showCartModal();
                }
            }).fail(function(xhr) {
                console.error("Error adding to cart:", xhr.responseText);
            });
        }
        $(document).on('click', '[data-dismiss="modal"], .close', function() {
            $('#cart-modal').fadeOut(300);
            $('body').removeClass('modal-open');
        });


        function confirmAddToCart() {
            let id = $('#option_product_id').val();
            let isValid = true;
            let attributes = []; // আমরা লুপ করে এখানে ডাটা রাখব
            let colorName = $('input[name="color_id"]:checked').data('name') || ""; // কালার নাম ধরার জন্য
            let quantity = $('input[name="quantity"]').val() || 1; // মেইন পেজের কোয়ান্টিটি

            // ১. অ্যাট্রিবিউট চেক এবং ডাটা নেওয়া
            $('.attribute-select').each(function() {
                let attrLabel = $(this).prev('label').text().replace('Select ', '').replace(':', '').trim();
                let attrValue = $(this).val();

                if (attrValue === "null" || attrValue === null || attrValue === "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Please select " + attrLabel
                    });
                    isValid = false;
                    return false;
                }
                // "Size: M" এই ফরম্যাটে স্ট্রিং তৈরি করা
                attributes.push(attrLabel + ": " + attrValue);
            });

            // ২. কালার চেক (যদি থাকে)
            if ($('input[name="color_id"]').length > 0 && !$('input[name="color_id"]:checked').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Please select a color"
                });
                isValid = false;
            }

            if (!isValid) return;

            // ৩. AJAX রিকোয়েস্ট
            $.post('{{ route('cart.add') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                option_selected: true, // কন্ট্রোলারের কন্ডিশন চেক করার জন্য
                attribute_value: attributes.join(', '), // "Size: M, Fabric: Cotton" হিসেবে পাঠাবে
                color_name: colorName,
                quantity: quantity
            }, function(data) {
                if (data.status === 'success') {
                    trackAddToCart(data);
                    $('#cart-modal').modal('hide');
                    updateNavCart();

                    // সাকসেস মেসেজ বা পপআপ দেখানো
                    setTimeout(function() {
                        showCartModal();
                    }, 500);
                }
            });
        }

        // কালার বক্সে ক্লিক করলে রেডিও বাটন সিলেক্ট হওয়ার জন্য (সহজ করার জন্য)
        $(document).on('click', '.color-box', function() {
            $(this).prev('input[type="radio"]').prop('checked', true);
            $('.color-box').css('border-color', 'transparent');
            $(this).css('border-color', '#000'); // সিলেক্টেড বর্ডার
        });




        // ৩. নেভবার আপডেট ফাংশন
        function updateNavCart() {
            $.get('{{ route('cart.count') }}', function(data) {
                if (data.count !== undefined) {
                    $('.cart-count').text(data.count);
                    const countNum = parseInt(data.count) || 0;
                    const mobileCircle = $('#mobileCartCircle');
                    if (mobileCircle.length > 0) {
                        if (countNum > 0) {
                            mobileCircle.addClass('cart-circle-glow');
                        } else {
                            mobileCircle.removeClass('cart-circle-glow');
                        }
                    }
                }
            });
        }

        // ৪. কার্ট মডাল দেখানো (ডেক্সটপ ও মোবাইল উভয় ভিউতেই)
        function showCartModal() {
            $.get('{{ route('cart.showModal') }}', function(data) {
                $('#cart-modal-content').html(data);
                $('#cart-modal').modal('show');
            });
        }


        function changeQuantity(id, delta) {
            // let currentQtyInput = $('.cart-qty-' + id);
            let qtyInput = $('.cart-qty-' + id);
            let currentQty = parseInt(qtyInput.val()) || 1;
            let maxQty = parseInt(qtyInput.attr('data-max')) || 100;
            // let newQty = (action === 1) ? currentQty + 1 : currentQty - 1;
            let newQty = currentQty + delta;

            if (newQty < 1) return;
            if (newQty > maxQty) {
                // alert('দুঃখিত, আমাদের কাছে মাত্র ' + maxVal + ' টি স্টক আছে।');
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Limit!',
                    text: 'দুঃখিত, আমাদের কাছে মাত্র ' + maxQty + ' টি স্টক আছে।',
                    confirmButtonColor: '#3085d6',
                    timer: 2000
                });
                return;
            }

            // ১. সব জায়গায় কোয়ান্টিটি ইনপুট আপডেট (যদি একই পেজে দুইটা থাকে)
            $('.cart-qty-' + id).val(newQty);

            $.post('{{ route('cart.update') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                quantity: newQty
            }, function(response) {
                if (response.status === 'success') {
                    // ২. লাইন টোটাল আপডেট (সব জায়গায় যেখানে এই ক্লাস আছে)
                    $('.line-total-' + id).text(response.line_total);

                    // ৩. সাবটোটাল আপডেট
                    $('.all-subtotal').text(response.subtotal);

                    if (response.free_delivery !== undefined) {
                        window.freeDeliveryState = response.free_delivery;
                        updateModalFreeDeliveryUI(response.free_delivery);
                        if (typeof updateFreeDeliveryUI === "function") {
                            updateFreeDeliveryUI();
                        }
                    }

                    // ৪. গ্র্যান্ড টোটাল আপডেট (চেকআউট পেজের জন্য)
                    if (typeof calculateGrandTotal === "function") {
                        calculateGrandTotal(response.subtotal);
                    }
                    if (typeof updateNavCart === "function") updateNavCart();
                }
            }).fail(function() {
                console.log("Error updating cart");
            });
        }

        function updateModalFreeDeliveryUI(fd) {
            if (!fd) return;
            let hasOffer = (fd.has_offer === true || fd.has_offer === "true" || fd.has_offer == 1);
            let isFree = (fd.is_free === true || fd.is_free === "true" || fd.is_free == 1);

            if (hasOffer) {
                $('.free-delivery-progress-container').show();
                $('#modal_fd_progress_title').text(fd.progress_message || '');
                $('#modal_fd_progress_qty').text((fd.current_qty || 0) + '/' + (fd.threshold || 0));
                $('#modal_fd_progress_bar').css('width', (fd.progress_percent || 0) + '%');
                
                if (isFree) {
                    $('.free-delivery-progress-container').css({
                        'background': 'linear-gradient(135deg, #ecfdf5, #f0fdf4)',
                        'border-color': '#10b981'
                    });
                    $('#modal_fd_progress_bar').css('background', 'linear-gradient(90deg, #10b981, #059669)');
                } else {
                    $('.free-delivery-progress-container').css({
                        'background': '#f8fafc',
                        'border-color': '#e2e8f0'
                    });
                    $('#modal_fd_progress_bar').css('background', 'linear-gradient(90deg, #6366f1, #4f46e5)');
                }
            } else {
                $('.free-delivery-progress-container').hide();
            }
        }

        function calculateSubtotal() {
            let subtotal = 0;
            // টেবিলের প্রতিটি ডাটা রো লুপ করা
            $('tbody tr').each(function() {
                // আপনার টেবিলে ৪র্থ কলাম হলো Total
                let totalText = $(this).find('td:nth-child(4)').text().replace(/[৳,]/g, '').trim();
                let totalValue = parseFloat(totalText);

                if (!isNaN(totalValue)) {
                    subtotal += totalValue;
                }
            });

            // সাবটোটাল যেখানে দেখায় (দশমিক ছাড়া দেখাতে চাইলে toFixed(0))
            $('.modal-footer .h5 span').text('৳ ' + subtotal.toFixed(0));
        }

        function calculateGrandTotal(subtotal) {
            // ১. সাবটোটাল থেকে সিম্বল সরিয়ে সংখ্যায় রূপান্তর
            let cleanSubtotal = String(subtotal).replace(/[৳,]/g, '').trim();
            let s = parseFloat(cleanSubtotal) || 0;

            // ২. শিপিং চার্জ এবং ডিসকাউন্ট নেওয়া
            let isFree = window.freeDeliveryState && (window.freeDeliveryState.is_free === true || window.freeDeliveryState.is_free == 1);
            let shipping = 0;
            
            if (!isFree) {
                shipping = parseFloat($('#charge_display').text()) || 0;
            }
            
            let discount = parseFloat($('#discount_amount').text().replace(/[৳,]/g, '').trim()) || 0;

            // ৩. গ্র্যান্ড টোটাল হিসাব
            let total = (s + shipping) - discount;

            // ৪. আউটপুট দেখানো (NaN চেকসহ)
            if (!isNaN(total)) {
                $('#grand-total').text(total.toFixed(0)); // দশমিক ছাড়া দেখাতে
                $('#hidden_total_amount').val(s);
                $('#hidden_grand_total').val(total);
            } else {
                $('#grand-total').text(s.toFixed(0));
            }
        }

        // জেলা পরিবর্তনের সাথে গ্র্যান্ড টোটাল আপডেট
        $(document).on('change', '#district_select', function() {
            let selectedOption = $(this).find(':selected');
            let charge = parseFloat(selectedOption.data('charge')) || 0;
            let isFree = window.freeDeliveryState && (window.freeDeliveryState.is_free === true || window.freeDeliveryState.is_free == 1);

            $('#original_charge_display').text(charge);

            if (isFree) {
                $('#charge_display').text('0');
                $('#hidden_charge_display').val('0');
            } else {
                $('#charge_display').text(charge);
                $('#hidden_charge_display').val(charge);
            }

            // বর্তমান সাবটোটাল থেকে গ্র্যান্ড টোটাল আবার হিসাব করুন
            let currentSubtotal = $('.all-subtotal').first().text().replace(/[৳,]/g, '').trim();
            calculateGrandTotal(currentSubtotal);
        });

        // ৬. কার্ট থেকে রিমুভ
        function removeguest(id) {
            if (event) event.preventDefault();
            $.post('{{ route('cart.remove') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(response) {
                if (response.status === 'success') {
                    // ১. সব পেজ থেকে ঐ নির্দিষ্ট প্রোডাক্টের রো (Row) মুছে ফেলা
                    $('.cart-row-' + id).fadeOut(300, function() {
                        $(this).remove();

                        // ২. কার্ট যদি একদম খালি হয়ে যায় তবে রিডাইরেক্ট
                        if (response.cart_count == 0) {
                            window.location.href = "{{ route('cartView') }}";
                        }
                    });

                    // ৩. সাবটোটাল এবং গ্র্যান্ড টোটাল আপডেট করা
                    $('.all-subtotal').text(response.subtotal);
                    
                    if (response.free_delivery !== undefined) {
                        window.freeDeliveryState = response.free_delivery;
                        updateModalFreeDeliveryUI(response.free_delivery);
                        if (typeof updateFreeDeliveryUI === "function") {
                            updateFreeDeliveryUI();
                        }
                    }

                    if ($('#grand-total').length > 0) {
                        calculateGrandTotal(response.subtotal);
                    }

                    // ৪. নেভিগেশন কার্ট (পপআপ) আপডেট
                    updateNavCart();
                }
            });



            // function(data) {
            //     updateNavCart();
            //     refreshCartModal();
            // });
        }

        // ৭. সরাসরি ইনপুট ফিল্ড আপডেট
        function guest_cart_update(id, qty) {
            $.post('{{ route('cart.update') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                quantity: qty
            }, function(data) {
                updateNavCart();
                refreshCartModal();
            });
        }

        // ৮. মডাল কন্টেন্ট রিফ্রেশ
        function refreshCartModal() {
            $.get('{{ route('cart.showModal') }}', function(data) {
                $('#cart-modal-content').html(data);
            });
        }
    </script>
    <script>
        $(document).on('click', '.buy-now-btn', function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let quantity = $('input[name="quantity"]').val() || 1; // আপনার ফর্মের কোয়ান্টিটি ইনপুট

            // কার্ট কন্ট্রোলারে রিকোয়েস্ট পাঠানো
            $.post('{{ route('cart.add') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                quantity: quantity
            }, function(data) {
                if (data.status === 'show_options') {
                    // ১. যদি অ্যাট্রিবিউট সিলেক্ট করতে হয়, তবে মডাল দেখাবে
                    $('#cart-modal-content').html(data.view);
                    $('#cart-modal').modal('show');

                    // মডালের ভেতরকার বাটনটিকে "Buy Now" মোডে পরিবর্তন করা
                    // এখানে একটি কাস্টম অ্যাট্রিবিউট সেট করছি যাতে বুঝা যায় এটি Buy Now থেকে আসছে
                    $('#cart-modal-content').find('button').attr('onclick', 'confirmBuyNow()');

                } else if (data.status === 'success') {
                    trackAddToCart(data);
                    // ২. যদি কোনো অ্যাট্রিবিউট না থাকে, সরাসরি চেকআউটে রিডাইরেক্ট
                    window.location.href = "{{ route('checkout') }}";
                }
            }).fail(function(xhr) {
                console.error("Buy Now Error:", xhr.responseText);
            });
        });

        // মডালের ভেতর "Confirm" বাটন ক্লিক করলে যা হবে
        function confirmBuyNow() {
            let id = $('#option_product_id').val();
            let isValid = true;
            let attributes = [];
            let colorName = $('input[name="color_id"]:checked').data('name') || "";
            let quantity = $('input[name="quantity"]').val() || 1; // মেইন পেজের কোয়ান্টিটি

            // অ্যাট্রিবিউট ভ্যালিডেশন
            $('.attribute-select').each(function() {
                let attrLabel = $(this).prev('label').text().replace('Select ', '').replace(':', '').trim();
                let attrValue = $(this).val();

                if (attrValue === "null" || attrValue === null || attrValue === "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Please select " + attrLabel
                    });
                    isValid = false;
                    return false;
                }
                attributes.push(attrLabel + ": " + attrValue);
            });

            if (!isValid) return;

            // কালার চেক (যদি থাকে)
            if ($('input[name="color_id"]').length > 0 && !$('input[name="color_id"]:checked').val()) {
                Swal.fire({
                    icon: 'error',
                    text: "Please select a color"
                });
                return;
            }

            // সব ঠিক থাকলে কার্টে অ্যাড করে চেকআউটে পাঠানো
            $.post('{{ route('cart.add') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                option_selected: true,
                attribute_value: attributes.join(', '),
                color_name: colorName,
                quantity: quantity
            }, function(data) {
                if (data.status === 'success') {
                    trackAddToCart(data);
                    // মডাল হাইড করে সরাসরি চেকআউট পেজে রিডাইরেক্ট
                    $('#cart-modal').modal('hide');
                    window.location.href = "{{ route('checkout') }}";
                }
            });
        }
    </script>



    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0&appId=4979933988710837&autoLogAppEvents=0"
        nonce="mvlbA8Xg"></script>

    <script>
        function addToCompare(id) {
            $.post('{{ route('compare.add') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(response) {
                if (response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Added', text: response.message, timer: 1500, showConfirmButton: false });
                    updateCompareCount(response.count);
                } else if (response.status === 'warning') {
                    Swal.fire({ icon: 'warning', title: 'Limit Reached', text: response.message });
                } else if (response.status === 'info') {
                    Swal.fire({ icon: 'info', title: 'Notice', text: response.message, timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                }
            }).fail(function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
            });
        }

        function removeFromCompare(id) {
            $.post('{{ route('compare.remove') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(response) {
                if (response.status === 'success') {
                    updateCompareCount(response.count);
                    location.reload(); // Reload to update compare page table
                }
            });
        }

        function updateCompareCount(count) {
            $('.compare-count').text(count);
        }
    </script>

    {{-- ==================== AI SUPPORT FLOATING HELP WIDGET ==================== --}}
    @php
        $featuresConfig = \Illuminate\Support\Facades\Cache::rememberForever('feature_activations_map', function () {
            return \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
        });
        $aiSupportActive = ($featuresConfig['ai_support'] ?? '0') === '1';
    @endphp

    @if($aiSupportActive)
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        .ai-support-widget-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.45);
            cursor: pointer;
            z-index: 99999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(255, 255, 255, 0.8);
        }
        .ai-support-widget-btn:hover {
            transform: scale(1.08) translateY(-3px);
            box-shadow: 0 14px 30px rgba(99, 102, 241, 0.55);
            color: #ffffff;
        }

        .ai-chat-box-modal {
            position: fixed;
            bottom: 95px;
            right: 25px;
            width: 380px;
            max-width: calc(100vw - 30px);
            height: 540px;
            max-height: calc(100vh - 120px);
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18), 0 10px 25px rgba(0, 0, 0, 0.08);
            z-index: 99999;
            display: none;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }

        .ai-chat-header {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            padding: 1.1rem 1.25rem;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .ai-chat-header-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .ai-chat-header-sub {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.8);
            margin: 0;
        }

        .btn-talk-agent-header {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-talk-agent-header:hover {
            background: #ffffff;
            color: #4f46e5;
        }

        .ai-chat-body {
            flex: 1;
            padding: 1.1rem;
            background: #f8fafc;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .ai-chat-msg-row {
            display: flex;
            flex-direction: column;
            max-width: 85%;
        }
        .ai-chat-msg-row.msg-user {
            align-self: flex-end;
        }
        .ai-chat-msg-row.msg-ai, .ai-chat-msg-row.msg-admin {
            align-self: flex-start;
        }

        .ai-chat-bubble {
            padding: 0.75rem 1rem;
            border-radius: 16px;
            font-size: 0.88rem;
            line-height: 1.5;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            word-wrap: break-word;
        }
        .msg-user .ai-chat-bubble {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }
        .msg-ai .ai-chat-bubble {
            background: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }
        .msg-admin .ai-chat-bubble {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-bottom-left-radius: 4px;
        }

        .ai-chat-sender-label {
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 3px;
            color: #64748b;
        }

        .quick-chips-row {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding: 0.5rem 1.1rem;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
        }
        .quick-chip-btn {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 14px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .quick-chip-btn:hover {
            background: #e0e7ff;
            color: #4f46e5;
            border-color: #c7d2fe;
        }

        .ai-chat-footer {
            padding: 0.85rem 1.1rem;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
        }
        .ai-input-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .ai-input-field {
            flex: 1;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 0.65rem 0.9rem;
            font-size: 0.9rem;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s ease;
        }
        .ai-input-field:focus {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }
        .btn-ai-send {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #6366f1;
            color: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-ai-send:hover {
            background: #4f46e5;
        }

        /* Mobile Phone View Optimization */
        @media (max-width: 576px) {
            .ai-support-widget-btn {
                bottom: 140px !important;
                right: 15px !important;
                width: 52px !important;
                height: 52px !important;
                font-size: 20px !important;
                box-shadow: 0 10px 25px rgba(99, 102, 241, 0.5) !important;
            }
            .ai-chat-box-modal {
                position: fixed !important;
                bottom: 200px !important;
                right: 10px !important;
                left: 10px !important;
                width: calc(100vw - 20px) !important;
                max-width: none !important;
                height: calc(100vh - 220px) !important;
                max-height: 480px !important;
                border-radius: 20px !important;
            }
            .ai-chat-header {
                padding: 0.85rem 1rem !important;
            }
            .ai-chat-header-title {
                font-size: 0.95rem !important;
            }
            .btn-talk-agent-header {
                padding: 4px 9px !important;
                font-size: 0.7rem !important;
            }
            .ai-chat-body {
                padding: 0.85rem !important;
            }
            .ai-chat-msg-row {
                max-width: 90% !important;
            }
            .quick-chips-row {
                padding: 0.4rem 0.85rem !important;
            }
            .ai-chat-footer {
                padding: 0.65rem 0.85rem !important;
            }
        }
    </style>

    <!-- Floating Trigger Icon -->
    <div class="ai-support-widget-btn" onclick="toggleAiChatModal()" id="aiWidgetTrigger" role="button" aria-label="Open AI Support Chat" title="Need Help? Chat with Us!">
        <i class="fa-solid fa-headset" id="triggerIcon"></i>
    </div>
    <!-- Chat Modal Window -->
    <div class="ai-chat-box-modal" id="aiChatModal">
        <div class="ai-chat-header">
            <div>
                <h4 class="ai-chat-header-title">
                    <i class="fa-solid fa-robot"></i> AI Support
                </h4>
                <div class="ai-chat-header-sub" id="aiHeaderSubStatus">● Online • LOOK-AI</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn-talk-agent-header" onclick="requestHumanAgentTransfer()">
                    <i class="fa-solid fa-user-headset mr-1"></i> Talk to Agent
                </button>
                <button type="button" class="btn p-0 text-white opacity-80" onclick="toggleAiChatModal()" style="font-size: 22px; line-height: 1; border: none; background: none;">
                    &times;
                </button>
            </div>
        </div>

        <div class="ai-chat-body" id="aiChatBody">
            <!-- Messages populated dynamically -->
        </div>

        <!-- Quick Suggestion Chips -->
        <div class="quick-chips-row">
            <button class="quick-chip-btn" onclick="sendQuickChip('Track my order')">📦 Track Order</button>
            <button class="quick-chip-btn" onclick="sendQuickChip('Shipping details')">🚚 Shipping Info</button>
            <button class="quick-chip-btn" onclick="sendQuickChip('Payment options')">💳 Payment Methods</button>
            <button class="quick-chip-btn" onclick="requestHumanAgentTransfer()">🎧 Talk to Agent</button>
        </div>

        <div class="ai-chat-footer">
            <form id="aiChatForm" onsubmit="handleAiFormSubmit(event)">
                <div class="ai-input-group">
                    <input type="text" id="aiInputMsg" class="ai-input-field" placeholder="Ask LOOK-AI AI or 'Talk to agent'..." autocomplete="off" required>
                    <button type="submit" id="aiSendBtn" class="btn-ai-send">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let isChatModalOpen = false;
        let aiPollingInterval = null;

        function toggleAiChatModal() {
            isChatModalOpen = !isChatModalOpen;
            const modal = document.getElementById('aiChatModal');
            const icon = document.getElementById('triggerIcon');

            if (!modal) return;

            if (isChatModalOpen) {
                modal.style.display = 'flex';
                if (icon) icon.className = 'fa-solid fa-xmark';
                loadAiChatHistory();
                if (!aiPollingInterval) {
                    aiPollingInterval = setInterval(loadAiChatHistory, 4000);
                }
            } else {
                modal.style.display = 'none';
                if (icon) icon.className = 'fa-solid fa-headset';
                if (aiPollingInterval) {
                    clearInterval(aiPollingInterval);
                    aiPollingInterval = null;
                }
            }
        }

        function loadAiChatHistory() {
            $.get("{{ route('aiSupport.history') }}", function(res) {
                if (res.active) {
                    const subStatus = document.getElementById('aiHeaderSubStatus');
                    if (subStatus) {
                        if (res.is_transferred) {
                            subStatus.innerText = '● Transferred to Live Admin';
                        } else {
                            subStatus.innerText = '● Online • LOOK-AI';
                        }
                    }

                    let html = '';
                    if (!res.messages || res.messages.length === 0) {
                        html = `
                            <div class="ai-chat-msg-row msg-ai">
                                <div class="ai-chat-sender-label">LOOK-AI</div>
                                <div class="ai-chat-bubble">
                                    Hello! 👋 Welcome to our store. I am your LOOK AI Assistant. How can I help you today? Ask me about order tracking, shipping, or type 'Talk to agent' to connect with a live representative.
                                </div>
                            </div>
                        `;
                    } else {
                        res.messages.forEach(function(msg) {
                            let msgType = 'msg-user';
                            let label = 'You';
                            if (msg.sender === 'ai') {
                                msgType = 'msg-ai';
                                label = 'LOOK-AI';
                            } else if (msg.sender === 'admin') {
                                msgType = 'msg-admin';
                                label = 'Live Support Admin';
                            }

                            html += `
                                <div class="ai-chat-msg-row ${msgType}">
                                    <div class="ai-chat-sender-label">${label}</div>
                                    <div class="ai-chat-bubble">${escapeAiHtml(msg.message).replace(/\n/g, '<br>')}</div>
                                </div>
                            `;
                        });
                    }

                    const body = document.getElementById('aiChatBody');
                    if (body) {
                        body.innerHTML = html;
                        scrollAiBodyToBottom();
                    }
                }
            });
        }

        function handleAiFormSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('aiInputMsg');
            const msg = input ? input.value.trim() : '';
            if (!msg) return;

            if (input) input.value = '';
            appendUserBubble(msg);

            $.post("{{ route('aiSupport.send') }}", {
                _token: '{{ csrf_token() }}',
                message: msg
            }, function(res) {
                if (res.success) {
                    loadAiChatHistory();
                }
            });
        }

        function sendQuickChip(text) {
            const input = document.getElementById('aiInputMsg');
            if (input) {
                input.value = text;
                document.getElementById('aiChatForm').dispatchEvent(new Event('submit'));
            }
        }

        function requestHumanAgentTransfer() {
            appendUserBubble('Talk to agent');
            $.post("{{ route('aiSupport.transfer') }}", {
                _token: '{{ csrf_token() }}'
            }, function(res) {
                if (res.success) {
                    loadAiChatHistory();
                }
            });
        }

        function appendUserBubble(msg) {
            const body = document.getElementById('aiChatBody');
            if (!body) return;

            const existingTyping = document.getElementById('typingIndicator');
            if (existingTyping) existingTyping.remove();

            const userHtml = `
                <div class="ai-chat-msg-row msg-user">
                    <div class="ai-chat-sender-label">You</div>
                    <div class="ai-chat-bubble">${escapeAiHtml(msg).replace(/\n/g, '<br>')}</div>
                </div>
                <div class="ai-chat-msg-row msg-ai" id="typingIndicator">
                    <div class="ai-chat-sender-label">AI Support (LOOK-AI)</div>
                    <div class="ai-chat-bubble" style="color: #6366f1; font-style: italic;">
                        <i class="fa-solid fa-sparkles fa-spin mr-1"></i> LOOK-AI thinking...
                    </div>
                </div>
            `;
            body.innerHTML += userHtml;
            scrollAiBodyToBottom();
        }

        function escapeAiHtml(text) {
            return text ? text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : '';
        }

        function scrollAiBodyToBottom() {
            const body = document.getElementById('aiChatBody');
            if (body) {
                body.scrollTop = body.scrollHeight;
            }
        }
    </script>
    @endif
    @yield('script')
</body>
</html>
