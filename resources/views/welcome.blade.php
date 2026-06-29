@php
    use App\Models\Product;
    use App\Models\ProductImage;
    use App\models\ProductAttributes;

@endphp

@extends('layouts.Frontend.master')
@section('title')
    HOME
@endsection
@section('content')
    <div class="home-banner-area">
        <div class="row gutters-10 position-relative">

            <div class="col-lg-3 position-static d-none d-lg-block">
                <div class="aiz-category-menu bg-white rounded @if (Route::currentRouteName() == 'home') shadow-sm" @else shadow-lg" id="category-sidebar" @endif style="min-height:
                    450px">
                    <div class="p-3 bg-soft-primary d-none d-lg-block rounded-top all-category position-relative text-left">
                        <span class="fw-600 fs-16 mr-3">Categories</span>
                        <a href="{{ route('front.allCategory') }}" class="text-reset">
                            <span class="d-none d-lg-inline-block">All categories ></span>
                        </a>
                    </div>
                    <ul class="list-unstyled categories no-scrollbar py-2 mb-0 text-left">
                        @foreach ($categories as $category)
                            <li class="category-nav-element" data-id="{{ $category->id }}">
                                <a href="{{ route('catProductView', [$category->slug, $category->id]) }}"
                                    class="text-truncate text-reset py-2 px-3 d-block">
                                    <img class="cat-image lazyload mr-2 opacity-60"
                                        src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                        data-src="{{ asset('frontend') }}/uploads/jWfNXjIDci5blBvokxp9u0RS89WqXeoBNws92KlQ.svg"
                                        width="16" alt="{{ $category->name }}"
                                        onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder.jpg';">
                                    <span class="cat-name">{{ $category->name }}</span>
                                </a>
                                <div class="sub-cat-menu c-scrollbar-light rounded shadow-lg p-4">

                                    <div class="row no-gutters">
                                        @if ($category->subcategories->count() > 0)
                                            @foreach ($category->subcategories as $subCat)
                                                <div class="col-lg-4 col-6">
                                                    <div class="p-2">
                                                        <h6 class="mb-3">
                                                            <a class="text-reset fw-600 fs-14"
                                                                href="{{ route('subCatProductView', [$subCat->slug, $subCat->id]) }}">
                                                                {{ $subCat->name }}
                                                            </a>
                                                        </h6>
                                                        <ul class="mb-3 list-unstyled pl-2">
                                                            @foreach ($subCat->childcategories as $childCat)
                                                                <li class="mb-2">
                                                                    <a class="text-reset opacity-60 hov-opacity-100"
                                                                        href="{{ route('childCatProductView', [$childCat->slug, $childCat->id]) }}">
                                                                        {{ $childCat->name }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach
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




            <div class=" col-lg-7 ">
                <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height" data-arrows="true" data-dots="true"
                    data-autoplay="true" data-infinite="true">
                    @foreach ($sliders as $slider)
                        <div class="carousel-box">
                            <a href="{{ $slider->url ? $slider->url : 'javascript:void(0)' }}">
                                <img class="d-block mw-100 img-fit rounded shadow-sm overflow-hidden"
                                    src="{{ asset('adminDash/uploads/slider&banner') }}/{{ $slider->image }}"
                                    alt="LOOKSMEN promo" height="315"
                                    onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder-rect.jpg';">
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Quick Category Bubbles -->
                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="6" data-lg-items="4"
                    data-md-items="3" data-sm-items="3" data-xs-items="3" data-arrows='true' data-autoplay="true"
                    data-infinite="true" data-speed="500">
                    @foreach ($categories as $category)
                        <div class="ca-item my-3">
                            <a href="{{ route('catProductView', [$category->slug, $category->id]) }}"
                                class="d-block rounded bg-white p-2 text-reset shadow-sm text-center hov-shadow-md mr-2">
                                <img src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                    data-src="{{ asset('adminDash/assets/img/category') }}/{{ $category->banner }}"
                                    alt="{{ $category->name }}" class="lazyload img-fit mx-auto h-50px h-md-78px"
                                    onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder-rect.jpg') }}';">

                                <div class="text-truncate fs-12 fw-600 mt-2 opacity-70">
                                    {{ $category->name }}
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-2 order-3 mt-3 mt-lg-0">
                <div class="bg-white rounded shadow-sm">
                    <div class="bg-soft-primary rounded-top p-3 d-flex align-items-center justify-content-center">
                        <span class="fw-600 fs-16 mr-2 text-truncate">
                            Todays Deal
                        </span>
                        <span class="badge badge-primary badge-inline">Hot</span>
                    </div>

                    <div class="c-scrollbar-light overflow-auto h-lg-400px p-2 bg-primary rounded-bottom">
                        <div class="gutters-5 lg-no-gutters row row-cols-2 row-cols-lg-1">

                            @foreach ($todaysDeals as $todaysDeal)
                                <div class="col mb-2" title="{{ $todaysDeal->title }}">
                                    <a href="{{ route('ProductView', [$todaysDeal->slug, $todaysDeal->id]) }}"
                                        class="d-block p-2 text-reset bg-white h-100 rounded">
                                        <div class="row gutters-5 align-items-center">
                                            <div class="col-xxl">
                                                <div class="img">
                                                    <img class="lazyload img-fit h-140px h-lg-80px"
                                                        src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                                        data-src="{{ $todaysDeal->firstImage ? asset('adminDash/uploads/products/' . $todaysDeal->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
                                                        alt="{{ $todaysDeal->title }}"
                                                        onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder.jpg';">
                                                </div>
                                            </div>
                                            <div class="col-xxl">
                                                <div class="fs-16">
                                                    <span
                                                        class="d-block text-primary fw-600">৳{{ $todaysDeal->new_price }}</span>
                                                    <del class="d-block opacity-70">৳{{ $todaysDeal->old_price }}</del>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </div>

    {{-- <div class="mb-5 mt-2">
        <div class="container">
            <div class="row gutters-10">
                <div class="col-xl col-md-3 col-sm-12">
                    <!--<div class="col-sm-12 col-md-4 col-lg-4">-->
                    <div class="mb-3 mb-lg-0">
                        <a href="" class="d-block text-reset">
                            <img src="https://www.store.looksmen.com/public/uploads/all/nrQdkljWGpUmNJW7q4pOt56592493rOzbdHTe8AT.jpg"
                                data-src="https://www.store.looksmen.com/public/uploads/all/nrQdkljWGpUmNJW7q4pOt56592493rOzbdHTe8AT.jpg"
                                alt="LOOKSMEN promo" class="img-fluid w-100 ls-is-cached lazyloaded">
                        </a>
                    </div>
                </div>
                <div class="col-xl col-md-3 col-sm-12">
                    <!--<div class="col-sm-12 col-md-4 col-lg-4">-->
                    <div class="mb-3 mb-lg-0">
                        <a href="" class="d-block text-reset">
                            <img src="https://www.store.looksmen.com/public/uploads/all/HwliQME2juoqeaYigL71C2O121jkXJIRjpCFgZLf.jpg"
                                data-src="https://www.store.looksmen.com/public/uploads/all/HwliQME2juoqeaYigL71C2O121jkXJIRjpCFgZLf.jpg"
                                alt="LOOKSMEN promo" class="img-fluid w-100 ls-is-cached lazyloaded">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="d-flex mb-3 align-items-baseline border-bottom">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">New Arrivals</span>
                    </h3>
                </div>
                <!--Previous Code Start -->
                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5"
                    data-lg-items="4" data-md-items="4" data-sm-items="2" data-xs-items="2" data-autoplay="true"
                    data-speed="500" data-arrows="true">
                    @foreach ($newArivals->take(6) as $newArival)
                        <div class="carousel-box">
                            <div class="aiz-card-box border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white">
                                <span class="badge-custom">OFF<span class="box ml-1 mr-0">&nbsp;{!! $newArival->discount_percentage !!}%</span></span>
                                <div class="position-relative">
                                    <a href="{{ route('ProductView', [$newArival->slug, $newArival->id]) }}" class="d-block">
                                        <img class="img-fit mx-auto h-140px h-md-210px lazyload"
                                            src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                            data-src="{{ $newArival->firstImage ? asset('adminDash/uploads/products/' . $newArival->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
                                            alt="{{ $newArival->name }}"
                                            onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder.jpg';">
                                    </a>
                                    <div class="absolute-top-right aiz-p-hov-icon">
                                        <a href="javascript:void(0)" onclick="addToWishList()">
                                            <i class="la la-heart-o"></i>
                                        </a>
                                        <a href="javascript:void(0)" onclick="addToCompare()">
                                            <i class="las la-sync"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="p-md-3 p-2 text-left">
                                    <div class="fs-15">
                                        <del class="fw-600 opacity-50 mr-1">৳{{ $newArival->old_price }}</del>
                                        <span class="fw-700 text-primary">৳{{ $newArival->new_price }}</span>
                                    </div>
                                    <div class="rating rating-sm mt-1">
                                        @php
                                            $avg = $newArival->getAverageRating();
                                            $fullStars = floor($avg);
                                            $fraction = $avg - $fullStars;
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $fullStars)
                                                <i class="las la-star" style="color: #ffc107;"></i>
                                            @elseif ($i == $fullStars + 1)
                                                @if ($fraction >= 0.3 && $fraction <= 0.7)
                                                    <i class="las la-star-half-alt" style="color: #ffc107;"></i>
                                                @elseif ($fraction > 0.7)
                                                    <i class="las la-star" style="color: #ffc107;"></i>
                                                @else
                                                    <i class="las la-star" style="color: #ced4da;"></i>
                                                @endif
                                            @else
                                                <i class="las la-star" style="color: #ced4da;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                        <a href="{{ route('ProductView', [$newArival->slug, $newArival->id]) }}" class="d-block text-reset">{{ $newArival->title }}</a>
                                    </h3>
                                    <a href="javascript:void(0)" class="btn btn-primary add-to-cart-btn mt-2" style="width: 100%" data-title="Add to cart" data-id="{{ $newArival->id }}" data-type="product">
                                        Add to Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!--Previous Code Ends -->


            </div>
        </div>
    </section>


    <div id="section_home_categories">
        @foreach ($categoryProducts as $key => $categoryProduct)
            @if ($categoryProduct->products->count() > 0)
                <section class="mb-4">
                    <div class="container">
                        <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                            <div class="d-flex mb-3 align-items-baseline border-bottom">
                                <h3 class="h5 fw-700 mb-0">
                                    <span
                                        class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ $categoryProduct->name }}</span>
                                </h3>
                                <a href="{{ route('catProductView', [$categoryProduct->slug, $categoryProduct->id]) }}"
                                    class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">View More</a>
                            </div>

                            <div class="row">

                                @foreach ($categoryProduct->products->take(6) as $product)
                                    <div class="col-md-2 col-lg-2 col-6">
                                        <div
                                            class="aiz-card-box border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white">
                                            <span class="badge-custom">OFF<span
                                                    class="box ml-1 mr-0">&nbsp;{!! $product->discount_percentage !!}%</span></span>
                                            <div class="position-relative">
                                                <a href="{{ route('ProductView', [$product->slug, $product->id]) }}"
                                                    class="d-block">
                                                    <img class="img-fit mx-auto h-140px h-md-210px ls-is-cached lazyload"
                                                        src="{{ asset('frontend/assets/img/placeholder.jpg') }}"
                                                        data-src="{{ $product->firstImage ? asset('adminDash/uploads/products/' . $product->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
                                                        alt="Exclusive Fu l  l Sleeve Check Formal and Casual Shirt for Men"
                                                        onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder.jpg') }}';"
                                                        alt="{{ $product->title }}">
                                                </a>
                                                <div class="absolute-top-right aiz-p-hov-icon">
                                                    <a href="javascript:void(0)" onclick="addToWishList(282)"
                                                        data-toggle="tooltip" data-title="Add to wishlist"
                                                        data-placement="left">
                                                        <i class="la la-heart-o"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick="addToCompare(282)"
                                                        data-toggle="tooltip" data-title="Add to compare"
                                                        data-placement="left">
                                                        <i class="las la-sync"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="p-md-3 p-2 text-left">
                                                <div class="fs-15">
                                                    <del class="fw-600 opacity-50 mr-1">৳{{ $product->old_price }}</del>
                                                    <span class="fw-700 text-primary">৳{{ $product->new_price }}</span>
                                                </div>
                                                <div class="rating rating-sm mt-1">
                                                    @php
                                                        $avg = $product->getAverageRating();
                                                        $fullStars = floor($avg);
                                                        $fraction = $avg - $fullStars;
                                                    @endphp
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $fullStars)
                                                            <i class="las la-star" style="color: #ffc107;"></i>
                                                        @elseif ($i == $fullStars + 1)
                                                            @if ($fraction >= 0.3 && $fraction <= 0.7)
                                                                <i class="las la-star-half-alt"
                                                                    style="color: #ffc107;"></i>
                                                            @elseif ($fraction > 0.7)
                                                                <i class="las la-star" style="color: #ffc107;"></i>
                                                            @else
                                                                <i class="las la-star" style="color: #ced4da;"></i>
                                                            @endif
                                                        @else
                                                            <i class="las la-star" style="color: #ced4da;"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                    <a href="{{ route('ProductView', [$product->slug, $product->id]) }}"
                                                        class="d-block text-reset">{{ $product->title }}</a>
                                                </h3>

                                                <a href="javascript:void(0)" class="btn btn-primary add-to-cart-btn mt-2"
                                                    style="width: 100%" data-title="Add to cart"
                                                    data-id="{{ $product->id }}" data-type="product">
                                                    Add to Cart
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="product_id" value="{{ $product->id }}">
                                @endforeach
                            </div>
                        </div>
                </section>
                @if (count($banners) > 0)
                    @php
                        $bannerIndex = $key % $banners->count();
                        $currentBanner = $banners[$bannerIndex];
                    @endphp

                    <section class="mb-4">
                        <div class="container">
                            <div class="banner-wrapper">
                                <a href="{{ $currentBanner->url ? $currentBanner->url : 'javascript:void(0)' }}">
                                    <img style="height: 100px"
                                        src="{{ asset('adminDash/uploads/slider&banner/' . $currentBanner->image) }}"
                                        class="img-fluid w-100 rounded shadow-sm" alt="Banner"
                                        onerror="this.onerror=null;this.src='{{ asset('public/assets/img/placeholder.jpg') }}';">
                                </a>
                            </div>
                        </div>
                    </section>
                @endif
            @endif
        @endforeach

    </div>






    <section class="mb-5 mt-4">
        <div class="container">
            <div class="row gutters-10 align-items-stretch">
                <!-- Top Categories -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card shadow-sm border-0 h-100 rounded-lg transition-all hover-shadow-lg">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="h5 fw-700 text-dark mb-0 d-flex align-items-center">
                                    <i class="las la-th-large text-primary mr-2 fs-24"></i> Top Categories
                                </h3>
                                <a href="categories.html" class="text-primary fw-600 fs-13 hover-text-underline transition-all">View All <i class="las la-angle-right"></i></a>
                            </div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="row gutters-10">
                                @php
                                    $topCats = [
                                        ['name' => "Women's Fashion", 'img' => 'Wqsoc7xawVjv3oUgSMWlhi8IvrFzRErT2clwzJac.png', 'url' => 'category/womenclothing.html'],
                                        ['name' => "Men's Fashion", 'img' => '1zeM14zNX9RB2KcUKjhSIpOy9IfXcyknPsWrO5e0.png', 'url' => 'category/menclothing.html'],
                                        ['name' => "Kids & Toy", 'img' => 'Pm4F1YvDhezPaxJIkeUUOYPoKWLlszqwmPed3qnL.png', 'url' => 'category/kidstoy.html'],
                                        ['name' => "Electronics", 'img' => 'placeholder.jpg', 'url' => 'javascript:void(0)'],
                                        ['name' => "Home & Garden", 'img' => 'placeholder.jpg', 'url' => 'javascript:void(0)'],
                                        ['name' => "Beauty & Health", 'img' => 'placeholder.jpg', 'url' => 'javascript:void(0)'],
                                    ];
                                @endphp
                                @foreach($topCats as $cat)
                                <div class="col-sm-6 mb-3">
                                    <a href="{{ $cat['url'] }}" class="bg-white category-card d-block text-reset rounded-lg p-3 transition-all h-100">
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon bg-light rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; min-width: 50px;">
                                                <img src="{{ asset('frontend/assets/img/placeholder.jpg') }}" data-src="{{ $cat['img'] == 'placeholder.jpg' ? asset('frontend/assets/img/placeholder.jpg') : asset('frontend/uploads/'.$cat['img']) }}" alt="{{ $cat['name'] }}" class="img-fluid lazyload" style="max-height: 25px; max-width: 25px; object-fit: contain;" onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder.jpg') }}';">
                                            </div>
                                            <div class="category-name flex-grow-1">
                                                <h4 class="fs-14 fw-600 mb-0 text-dark">{{ $cat['name'] }}</h4>
                                            </div>
                                            <div class="category-arrow text-primary opacity-0 transition-all">
                                                <i class="las la-arrow-right"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Brands -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100 rounded-lg transition-all hover-shadow-lg">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="h5 fw-700 text-dark mb-0 d-flex align-items-center">
                                    <i class="las la-award text-warning mr-2 fs-24"></i> Top Brands
                                </h3>
                                <a href="javascript:void(0)" class="text-primary fw-600 fs-13 hover-text-underline transition-all">View All <i class="las la-angle-right"></i></a>
                            </div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="row gutters-10">
                                @php
                                    $staticBrands = [
                                        'Apple', 'Dove', 'Adidas', 'Gucci', 'Lifebuoy', 'Huawei'
                                    ];
                                    $staticImgs = [
                                        'ubccXxytrayFiJvCo7hHUooPmw2HieetuPgoYk1P.png',
                                        'wL6ekMxbUXqMu1JoVBKCxO09xJYfvfy8ADzwP0Mm.png',
                                        'niviyf6aeUwyynNGOtOYR4DuuS6TWqhWkK2NbXHD.png',
                                        'cC2MKTWOYfcmOKdvj8wsGOB50LBPrP1MZkQOOU6k.png',
                                        'FxChvGtIih1wX5GZoqE0xtEzBlSo7STHOVuxEgdr.png',
                                        'wYbxWXUtI9KpKo0GLNuB3BfsdMvQ3D1UbMvRiSRx.png',
                                    ];
                                @endphp
                                @foreach ($staticBrands as $index => $brand)
                                <div class="col-sm-6 mb-3">
                                    <a href="javascript:void(0)" class="bg-white border border-light d-block text-reset p-3 rounded-lg transition-all brand-card h-100">
                                        <div class="d-flex align-items-center">
                                            <div class="brand-logo text-center mr-3 border-right pr-3" style="width: 70px;">
                                                <img src="{{ asset('frontend/assets/img/placeholder.jpg') }}" data-src="{{ asset('frontend/uploads/' . $staticImgs[$index]) }}" alt="{{ $brand }}" class="img-fluid lazyload" style="max-height: 40px; object-fit: contain;" onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder.jpg') }}';">
                                            </div>
                                            <div class="brand-name flex-grow-1">
                                                <h4 class="fs-15 fw-700 text-dark mb-0">{{ $brand }}</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .category-card { border: 1px solid #edf2f9; }
        .category-card:hover { border-color: var(--primary) !important; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transform: translateY(-3px); }
        .category-card:hover .category-arrow { opacity: 1 !important; transform: translateX(3px); }
        .category-card:hover .category-icon { background-color: var(--primary) !important; color: white !important; }
        .brand-card:hover { border-color: var(--primary) !important; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transform: translateY(-3px); }
        .hover-text-underline:hover { text-decoration: underline !important; }
        .transition-all { transition: all 0.3s ease; }
    </style>
@endsection

@section('script')
    <script>
        $('.aiz-carousel').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true, 
            autoplaySpeed: 2000,
            infinite: true,
            arrows: true,
            dots: false,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                    }
                }
            ]
        });
    </script>
@endsection
