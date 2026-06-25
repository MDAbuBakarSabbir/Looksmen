@php
    use App\Models\GeneralWebSettings;
    use App\Models\Pages;
    use App\Models\Category;

    $webinfo = GeneralWebSettings::first();
    $webConfig = GeneralWebSettings::first()->pluck('value', 'name', 'status')->toArray();
    $pages = Pages::where('status', 1)->get();
    $categories = Category::with('subcategories.childcategories')->get()->take(11);

@endphp

<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="//www.store.looksmen.com/">
    <meta name="file-base-url" content="//www.store.looksmen.com/public/">

    <title>{{ $webConfig['web_name'] }} | @yield('title')</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description"
        content="{{ $webConfig['web_description'] }}" />
    <meta name="keywords" content="Looksmen.com, Looksmen-Online Shopping, Looksmen, looksmen.com,looks">


    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="looksmen.com">
    <meta itemprop="description"
        content="{{ $webConfig['web_description'] }}">
    <meta itemprop="image" content="{{ asset('frontend') }}/uploads/CcWodTkHslpIjY8rstUMkVdmzLDJdT3ULIstJtHy.png">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="looksmen.com">
    <meta name="twitter:description"
        content="{{ $webConfig['web_description'] }}">
    <meta name="twitter:creator"
        content="@author_handle">
    <meta name="twitter:image" content="{{ asset('frontend') }}/uploads/CcWodTkHslpIjY8rstUMkVdmzLDJdT3ULIstJtHy.png">

    <!-- Open Graph data -->
    <meta property="og:title" content="looksmen.com" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="index.html" />
    <meta property="og:image" content="{{ asset('frontend') }}/uploads/CcWodTkHslpIjY8rstUMkVdmzLDJdT3ULIstJtHy.png" />
    <meta property="og:description"
        content="{{ $webConfig['web_description'] }}" />
    <meta property="og:site_name" content="LOOKSMEN" />
    <meta property="fb:app_id" content="1125412091428219">
    <meta name="facebook-domain-verification" content="fyxav3lhjmr6gjtzvyu9o0r4utfakz" />
    <!-- Favicon -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="icon" href="{{ asset('adminDash/assets/img/layouts/'.$webConfig['web_favicon']) }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;display=swap"
        rel="stylesheet">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/vendors.css">

    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/aiz-core.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/custom-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="{{ asset('frontend') }}/assets/js/jquryui.js"></script>
    <script>
        var AIZ = AIZ || {};
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
        }
    </script>

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
        }

        :root {
            --primary: #044244;
            --hov-primary: #044244;
            --soft-primary: ;
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
    </style>



    <!-- Google Tag Manager -->
    <script>
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
            j.src =
                '../www.googletagmanager.com/gtm5445.html?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-N36FZRPR');
    </script>
    <!-- End Google Tag Manager -->

    <style>
        img.mw-100.h-30px.h-md-40px {
            width: 100%;
        }

        .container,
        .container-lg,
        .container-md,
        .container-sm,
        .container-xl {
            max-width: 1280px !important;
        }
    </style>
</head>

<body>
    <div class="aiz-main-wrapper d-flex flex-column">
        <div class="top-navbar bg-white border-bottom border-soft-secondary z-1035">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col">
                        <ul class="list-inline d-flex justify-content-between justify-content-lg-start mb-0">

                            <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                                <a href="tel:01568482005" class="text-reset d-inline-block opacity-60 py-2">
                                    <i class="la la-phone"></i>
                                    <span>Help line</span>
                                    <span>01568482005</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-5 text-right d-none d-lg-block">
                        <ul class="list-inline mb-0 h-100 d-flex justify-content-end align-items-center">
                    @auth
                        <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                                <a href="{{ route('login') }}"
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
                    <a class="d-block py-20px mr-3 ml-0" href="{{ url('/') }}">
                        <img src="{{ asset('frontend') }}/uploads/fETh72eayEQqMyqsArGAXDlxFO3TzCj9dH9ukG12.png"
                            alt="LOOKSMEN" class="mw-100 h-40px h-md-60px" height="40">
                    </a>

                    @if (Route::currentRouteName() != 'home')
                        <div class="d-none d-xl-block align-self-stretch category-menu-icon-box ml-auto mr-0">
                            <div class="h-100 d-flex align-items-center" id="category-menu-icon">
                                <div
                                    class="dropdown-toggle navbar-light bg-light h-40px w-50px pl-2 rounded border c-pointer">
                                    <span class="navbar-toggler-icon"></span>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                                    <a href="{{ route('catProductView', [$category->slug, $category->id]) }}"
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
                                                                                    href="{{ route('subCatProductView', [$subCat->slug, $subCat->id]) }}">
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
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle"
                        data-target=".front-header-search">
                        <i class="las la-search la-flip-horizontal la-2x"></i>
                    </a>
                </div>

                <div class="flex-grow-1 front-header-search d-flex align-items-center bg-white">
                    <div class="position-relative flex-grow-1">
                        <form action="https://www.store.looksmen.com/search" method="GET" class="stop-propagation">
                            <div class="d-flex position-relative align-items-center">
                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                    <button class="btn px-2" type="button"><i
                                            class="la la-2x la-long-arrow-left"></i></button>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="border-0 border-lg form-control" id="search"
                                        name="keyword" placeholder="I am shopping for..." autocomplete="off">
                                    <div class="input-group-append d-none d-lg-block">
                                        <button style="border-radius: 0px;" class="btn btn-primary" type="submit">
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
                        <a href="{{ route('login') }}" class="d-flex align-items-center text-reset">
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
                                    @if (auth()->check())
                                        {{ \App\Models\Cart::where('user_id', auth()->id())->count() }}
                                    @else
                                        {{ count(session('cart', [])) }}
                                    @endif
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
        <!--bg-white-->
        <div class="container">
            <ul class="list-inline mb-0 pl-0 mobile-hor-swipe text-center">
                <li class="list-inline-item mr-0">
                    <a href="{{ url('/') }}"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        <!--text-reset-->
                        Home
                    </a>
                </li>
                <li class="list-inline-item mr-0">
                    <a href="track-your-order.html"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        <!--text-reset-->
                        Track Order
                    </a>
                </li>
                <li class="list-inline-item mr-0">
                    <a href="flash-deal/flash-sale-84ExG.html"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        <!--text-reset-->
                        Flash Sale
                    </a>
                </li>
                <li class="list-inline-item mr-0">
                    <a href="blog.html"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        <!--text-reset-->
                        Blogs
                    </a>
                </li>
                <li class="list-inline-item mr-0">
                    <a href="{{ route('front.allCategory') }}"
                        class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                        <!--text-reset-->
                        All Categorie
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
                                    data-src="{{ asset('frontend') }}/uploads/fETh72eayEQqMyqsArGAXDlxFO3TzCj9dH9ukG12.png"
                                    alt="LOOKSMEN" height="44">
                            </a>
                            <div class="my-3">
                                <span style="font-weight: bolder;">looksmen.com&nbsp;</span>is a trusted virtual
                                e-commerce marketplace. It is an online retailer that sells various products online from
                                anywhere in Bangladesh. We have more than 25000 products. We sell them online. We have a
                                7-day money-back guarantee service. We always work with fidelity. Thanks for staying
                                with us.
                            </div>
                            <div class="d-inline-block d-md-block mb-4">
                                <form class="form-inline" method="POST"
                                    action="https://www.store.looksmen.com/subscribers">
                                    <input type="hidden" name="_token"
                                        value="kbxvDqGfGdVazCkZ4DhRVh8xW2Ztv6FGgoKTFXGQ">
                                    <div class="form-group mb-0">
                                        <input type="email" class="form-control" placeholder="Your Email Address"
                                            name="email" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Subscribe
                                    </button>
                                </form>
                            </div>
                            <div class="w-300px mw-100 mx-auto mx-md-0">
                                <a href="#" target="_blank" class="d-inline-block mr-3 ml-0">
                                    <img src="{{ asset('frontend') }}/assets/img/play.png" class="mx-100 h-40px">
                                </a>
                                <a href="#" target="_blank" class="d-inline-block">
                                    <img src="{{ asset('frontend') }}/assets/img/app.png" class="mx-100 h-40px">
                                </a>
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
                                    <a class="opacity-50 hov-opacity-100 text-reset" href="">
                                        Order History
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="opacity-50 hov-opacity-100 text-reset" href="">
                                        My Wishlist
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="opacity-50 hov-opacity-100 text-reset" href="">
                                        Track Order
                                    </a>
                                </li>
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
                            <p>All Rights Reserved By LOOKSMEN<br></p>
                            <p>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-inline my-3 my-md-0 social colored text-center">
                            <li class="list-inline-item">
                                <a href="https://facebook.com/looksmenstore" target="_blank" class="facebook"><i
                                        class="lab la-facebook-f"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://twitter.com/looksmenstore" target="_blank" class="twitter"><i
                                        class="lab la-twitter"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://instagram.com/looksmenstore" target="_blank" class="instagram"><i
                                        class="lab la-instagram"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://youtube.com/looksmenstore" target="_blank" class="youtube"><i
                                        class="lab la-youtube"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <div class="text-center text-md-right">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <span style="color: white;">&nbsp;Website Designed By: <a href=""
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
                    <a href="{{ route('front.allCategory') }}" class="text-reset d-block text-center pb-2 pt-3">
                        <i class="las la-list-ul fs-20 opacity-60 "></i>
                        <span class="d-block fs-10 fw-600 opacity-60 ">Categories</span>
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('cartView') }}" class="text-reset d-block text-center pb-2 pt-3">
                        <span
                            class="align-items-center bg-primary border border-white border-width-4 d-flex justify-content-center position-relative rounded-circle size-50px"
                            style="margin-top: -33px;box-shadow: 0px -5px 10px rgb(0 0 0 / 15%);border-color: #fff !important;">
                            <i class="las la-shopping-bag la-2x text-white"></i>
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 opacity-60 ">
                            Cart
                            (<span class="cart-count">
                                @if (auth()->check())
                                    {{ \App\Models\Cart::where('user_id', auth()->id())->count() }}
                                @else
                                    {{ count(session('cart', [])) }}
                                @endif
                            </span>)
                        </span>
                    </a>
                </div>
                <div class="col">
                    <a href="users/login.html" class="text-reset d-block text-center pb-2 pt-3">
                        <span class="d-inline-block position-relative px-2">
                            <i class="las la-bell fs-20 opacity-60 "></i>
                        </span>
                        <span class="d-block fs-10 fw-600 opacity-60 ">Notifications</span>
                    </a>
                </div>
                <div class="col">
                    @auth
                        <a href="javascript:void(0)"
                            class="text-reset d-block text-center pb-2 pt-3 mobile-side-nav-thumb"
                            data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                            <span class="d-block mx-auto">
                                <img src="{{ asset('frontend') }}/assets/img/avatar-place.png"
                                    class="rounded-circle size-20px">
                            </span>
                            <span class="d-block fs-10 fw-600 opacity-60">Account</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-reset d-block text-center pb-2 pt-3 mobile-side-nav-thumb"
                            data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                            <span class="d-block mx-auto">
                                <img src="{{ asset('frontend') }}/assets/img/avatar-place.png"
                                    class="rounded-circle size-20px">
                            </span>
                            <span class="d-block fs-10 fw-600 opacity-60">Account</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>



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
    </style>

    <div class="modal fade" id="cart-modal" tabindex="-1" role="dialog" aria-hidden="true"
        style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content" id="cart-modal-content" style="border-radius: 15px; overflow: hidden;">
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
                        <form class="form-default" role="form" action="" method="POST">
                            @csrf
                            <input type="hidden" name="_token" value="kbxvDqGfGdVazCkZ4DhRVh8xW2Ztv6FGgoKTFXGQ">
                            <div class="form-group">
                                <input type="email" class="form-control h-auto form-control-lg " value=""
                                    placeholder="Email" name="email">
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg"
                                    placeholder="Password">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember">
                                        <span class=opacity-60>Remember Me</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="../password/reset.html" class="text-reset opacity-60 fs-14">Forgot
                                        password?</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit" class="btn btn-primary btn-block fw-600">Login</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">Dont have an account?</p>
                            <a href="../users/registration.html">Register Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
        <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
            <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static"
                data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
            <div class="collapse-sidebar bg-white">
                <div class="aiz-user-sidenav-wrap position-relative z-1 shadow-sm">
                    <div class="aiz-user-sidenav rounded overflow-auto c-scrollbar-light pb-5 pb-xl-0">
                        <div class="p-4 text-xl-center mb-4 border-bottom bg-primary text-white position-relative">
                            <span class="avatar avatar-md mb-3">
                                <img src="https://www.store.looksmen.com/public/assets/img/avatar-place.png"
                                    class="image rounded-circle"
                                    onerror="this.onerror=null;this.src='https://www.store.looksmen.com/public/assets/img/avatar-place.png';">
                            </span>
                            <h4 class="h5 fs-16 mb-1 fw-600">{{ Auth::user()->name }}</h4>
                            <div class="text-truncate opacity-60">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="sidemnenu mb-3">
                            <ul class="aiz-side-nav-list px-2" data-toggle="aiz-side-menu">

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('dashboard') }}" class="aiz-side-nav-link active">
                                        <i class="las la-home aiz-side-nav-icon"></i>
                                        <span class="aiz-side-nav-text">Dashboard</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('purchaseHistory') }}" class="aiz-side-nav-link ">
                                        <i class="las la-file-alt aiz-side-nav-icon"></i>
                                        <span class="aiz-side-nav-text">Purchase History</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('wishlist') }}" class="aiz-side-nav-link ">
                                        <i class="la la-heart-o aiz-side-nav-icon"></i>
                                        <span class="aiz-side-nav-text">Wishlist</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('compare') }}" class="aiz-side-nav-link ">
                                        <i class="la la-refresh aiz-side-nav-icon"></i>
                                        <span class="aiz-side-nav-text">Compare</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="" class="aiz-side-nav-link ">
                                        <i class="lab la-sketch aiz-side-nav-icon"></i>
                                        <span class="aiz-side-nav-text">Classified Products</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('conversation') }}" class="aiz-side-nav-link ">
                                        <i class="las la-comment aiz-side-nav-icon"></i>
                                        <span class="aiz-side-nav-text">Conversations</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('myWallet') }}" class="aiz-side-nav-link ">
                                        <i class="las la-dollar-sign aiz-side-nav-icon"></i>
                                        <span class="aiz-side-nav-text">My Wallet</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('supportTicket') }}" class="aiz-side-nav-link ">
                                        <i class="las la-atom aiz-side-nav-icon"></i>
                                        <span class="aiz-side-nav-text">Support Ticket</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="https://www.store.looksmen.com/profile" class="aiz-side-nav-link ">
                                        <i class="las la-user aiz-side-nav-icon"></i>
                                        <span class="aiz-side-nav-text">Manage Profile</span>
                                    </a>
                                </li>

                            </ul>
                        </div>

                    </div>

                    <div class="fixed-bottom d-xl-none bg-white border-top d-flex justify-content-between px-2"
                        style="box-shadow: 0 -5px 10px rgb(0 0 0 / 10%);">
                        <a class="btn btn-sm p-2 d-flex align-items-center" href="{{ route('logout') }}">
                            <i class="las la-sign-out-alt fs-18 mr-2"></i>
                            <span>Logout</span>
                        </a>
                        <button class="btn btn-sm p-2 " data-toggle="class-toggle" data-backdrop="static"
                            data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb">
                            <i class="las la-times la-2x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endauth


    <script>
        function confirm_modal(delete_url) {
            jQuery('#confirm-delete').modal('show', {
                backdrop: 'static'
            });
            document.getElementById('delete_link').setAttribute('href', delete_url);
        }
    </script>





    <!-- SCRIPTS -->
    <script src="{{ asset('frontend') }}/assets/js/vendors.js"></script>
    <script src="{{ asset('frontend') }}/assets/js/aiz-core.js"></script>
    <script src="{{ asset('frontend') }}/assets/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @yield('script')

    <script>
        $(document).ready(function() {
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

        $('#search').on('keyup', function() {
            search();
        });

        $('#search').on('focus', function() {
            search();
        });

        function search() {
            var searchKey = $('#search').val();
            if (searchKey.length > 0) {
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('ajax-search.html', {
                    _token: AIZ.data.csrf,
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
            AIZ.plugins.notify('warning', "Please login first");
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
            $(document).on('click', '.add-to-cart-btn', function() {
                let id = $(this).data('id');
                addToCart(id);
            });
        });

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
                    // নেভবার আপডেট
                    updateNavCart();

                    // শুধুমাত্র বড় স্ক্রিনে মডাল দেখাবে
                    if ($(window).width() > 768) {
                        showCartModal();
                    }
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

            // ১. অ্যাট্রিবিউট চেক এবং ডাটা নেওয়া
            $('.attribute-select').each(function() {
                let attrLabel = $(this).prev('label').text().replace('Select ', '').replace(':', '').trim();
                let attrValue = $(this).val();

                if (attrValue === "null" || attrValue === null || attrValue === "") {
                    alert("Please select " + attrLabel);
                    isValid = false;
                    return false;
                }
                // "Size: M" এই ফরম্যাটে স্ট্রিং তৈরি করা
                attributes.push(attrLabel + ": " + attrValue);
            });

            // ২. কালার চেক (যদি থাকে)
            if ($('input[name="color_id"]').length > 0 && !$('input[name="color_id"]:checked').val()) {
                alert("Please select a color");
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
                quantity: 1
            }, function(data) {
                if (data.status === 'success') {
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
                }
            });
        }

        // ৪. কার্ট মডেল দেখানো
        function showCartModal() {
            if ($(window).width() <= 768) return false;

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
                    text: 'দুঃখিত, আমাদের কাছে মাত্র' + maxVal + ' টি স্টক আছে।',
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

                    // ৪. গ্র্যান্ড টোটাল আপডেট (চেকআউট পেজের জন্য)
                    if (typeof calculateGrandTotal === "function") {
                        calculateGrandTotal(response.subtotal);
                    }
                    if (typeof updateNavCart === "function") updateNavCart();
                    // calculateGrandTotal(response.subtotal);

                    // updateNavCart();
                }
            }).fail(function() {
                console.log("Error updating cart");
            });


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

        // function calculateGrandTotal(subtotal) {
        //     let shipping = parseFloat($('#charge_display').text()) || 0;
        //     let discount = parseFloat($('#discount_amount').text()) || 0; // যদি ডিসকাউন্ট থাকে
        //     let grandTotal = (parseFloat(subtotal) + shipping) - discount;

        //     $('#grand-total').text('৳ ' + grandTotal.toFixed(0));
        //     $('#payable_amount_input').val(grandTotal); // যদি ফর্ম পাঠাতে হয়
        // }
        function calculateGrandTotal(subtotal) {
            // ১. সাবটোটাল থেকে সিম্বল সরিয়ে সংখ্যায় রূপান্তর
            let cleanSubtotal = String(subtotal).replace(/[৳,]/g, '').trim();
            let s = parseFloat(cleanSubtotal) || 0;

            // ২. শিপিং চার্জ এবং ডিসকাউন্ট নেওয়া
            let shipping = parseFloat($('#charge_display').text()) || 0;
            let discount = parseFloat($('#discount_amount').text()) || 0;

            // ৩. গ্র্যান্ড টোটাল হিসাব
            let total = (s + shipping) - discount;

            // ৪. আউটপুট দেখানো (NaN চেকসহ)
            if (!isNaN(total)) {
                $('#grand-total').text(total.toFixed(0)); // দশমিক ছাড়া দেখাতে
                $('#hidden_total_amount').val(subtotal);
                $('#hidden_grand_total').val(total);
            } else {
                $('#grand-total').text(s.toFixed(0));
            }
        }

        // জেলা পরিবর্তনের সাথে গ্র্যান্ড টোটাল আপডেট
        $(document).on('change', '#district_select', function() {
            let charge = parseFloat($(this).find(':selected').data('charge')) || 0;
            $('#charge_display').text(charge);

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
                    // মডাল হাইড করে সরাসরি চেকআউট পেজে রিডাইরেক্ট
                    $('#cart-modal').modal('hide');
                    window.location.href = "{{ route('checkout') }}";
                }
            });
        }
    </script>



    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N36FZRPR" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous"
        src="../connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0&appId=4979933988710837&autoLogAppEvents=1"
        nonce="mvlbA8Xg"></script>
    </div>

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
    </body>
</html>
