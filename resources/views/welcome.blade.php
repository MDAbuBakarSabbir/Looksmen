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
        <div class="row gutters-10 position-relative mb-4">

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
                                <a href="{{ route('catProductView', [$category->id, $category->slug]) }}"
                                    class="text-truncate text-reset py-2 px-3 d-block">
                                    <i class="cat-image lazyload mr-2 opacity-60 {{ (str_starts_with($category->icon, 'fa-') && !str_contains($category->icon, ' ')) ? 'fa-solid ' . $category->icon : $category->icon }}"></i>
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
                                                                href="{{ route('subCatProductView', [$subCat->id, $subCat->slug]) }}">
                                                                {{ $subCat->name }}
                                                            </a>
                                                        </h6>
                                                        <ul class="mb-3 list-unstyled pl-2">
                                                            @foreach ($subCat->childcategories as $childCat)
                                                                <li class="mb-2">
                                                                    <a class="text-reset opacity-60 hov-opacity-100"
                                                                        href="{{ route('childCatProductView', [$childCat->id, $childCat->slug]) }}">
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
                <div id="carouselExampleControls" class="carousel slide mobile-img-auto-height" data-ride="carousel" data-autoplay="true">
                    <div class="carousel-inner">
                    @foreach ($sliders as $slider)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <a href="{{ $slider->url ? $slider->url : 'javascript:void(0)' }}">
                                <img class="d-block w-100" src="{{ asset('Uploads') }}/{{ $slider->image }}" alt="LOOKSMEN promo" height="315"
                                    onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder-rect.jpg';">
                            </a>
                        </div>
                    @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>

                <!-- Quick Category Bubbles -->

                <div id="carouselExampleControls2" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        {{-- ক্যাটেগরিগুলোকে ৬টি করে গ্রুপে (chunk) ভাগ করা হচ্ছে --}}
                        @foreach ($categories->chunk(6) as $chunkIndex => $categoryChunk)
                            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                <div class="row gutters-5">
                                    @foreach ($categoryChunk as $category)
                                        {{-- col-md-2 দিলে ডেক্সটপে ৬টি কলাম শো করবে (১২ / ২ = ৬) --}}
                                        <div class="col-4 col-md-2 mt-3 minw-0">
                                            <a href="{{ route('catProductView', [$category->id, $category->slug]) }}" class="d-block rounded bg-white p-2 text-reset shadow-sm text-center">
                                                <img
                                                    src="{{ asset('frontend/assets/img/placeholder.jpg') }}"
                                                    data-src="{{ asset('Uploads') }}/{{ $category->banner }}"
                                                    alt="{{ $category->name }}"
                                                    class="lazyload img-fit"
                                                    height="78"
                                                    onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder-rect.jpg') }}';"
                                                >
                                                <div class="text-truncate fs-12 fw-600 mt-2 opacity-70">{{ $category->name }}</div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- স্লাইড কন্ট্রোল বাটন --}}
                    <a class="carousel-control-prev" href="#carouselExampleControls2" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls2" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
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
                                    <a href="{{ route('ProductView', [$todaysDeal->id, $todaysDeal->slug]) }}"
                                        class="d-block p-2 text-reset bg-white h-100 rounded">
                                        <div class="row gutters-5 align-items-center">
                                            <div class="col-xxl">
                                                <div class="img">
                                                    <img class="lazyload img-fit h-140px h-lg-80px"
                                                        src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                                        data-src="{{ $todaysDeal->firstImage ? asset('Uploads/' . $todaysDeal->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
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

    <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="d-flex mb-3 align-items-baseline border-bottom">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">New Arrivals</span>
                    </h3>
                </div>
                <!--Previous Code Start -->
                <div class="row">
                    @foreach ($newArivals->take(6) as $newArival)
                        <div class="col-md-2 col-lg-2 col-6">
                            <div class="aiz-card-box border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white">
                                <span class="badge-custom">OFF<span class="box ml-1 mr-0">&nbsp;{!! $newArival->discount_percentage !!}%</span></span>
                                <div class="position-relative">
                                    <a href="{{ route('ProductView', [$newArival->id, $newArival->slug]) }}" class="d-block">
                                        <img class="img-fit mx-auto h-140px h-md-210px lazyload"
                                            src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                            data-src="{{ $newArival->firstImage ? asset('Uploads/' . $newArival->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
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
                                        <a href="{{ route('ProductView', [$newArival->id, $newArival->slug]) }}" class="d-block text-reset">{{ $newArival->title }}</a>
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
                                <a href="{{ route('catProductView', [$categoryProduct->id, $categoryProduct->slug]) }}"
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
                                                <a href="{{ route('ProductView', [$product->id, $product->slug]) }}"
                                                    class="d-block">
                                                    <img class="img-fit mx-auto h-140px h-md-210px ls-is-cached lazyload"
                                                        src="{{ asset('frontend/assets/img/placeholder.jpg') }}"
                                                        data-src="{{ $product->firstImage ? asset('Uploads/' . $product->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
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
                                                    <a href="{{ route('ProductView', [$product->id, $product->slug]) }}"
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
                                        src="{{ asset('Uploads/' . $currentBanner->image) }}"
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






    @php
        $hasBrands = false;
        $dbBrands = [];
        try {
            $dbBrands = \Illuminate\Support\Facades\Cache::remember('home_top_brands_list_v3', 3600, function () {
                if (\Illuminate\Support\Facades\Schema::hasTable('brands')) {
                    return \Illuminate\Support\Facades\DB::table('brands')->limit(6)->get();
                }
                return collect([]);
            });
            if (!($dbBrands instanceof \Illuminate\Support\Collection)) {
                $dbBrands = collect($dbBrands);
            }
            $hasBrands = $dbBrands->isNotEmpty();
        } catch (\Exception $e) {
            $hasBrands = false;
        }
    @endphp

    <section class="mb-5 mt-4">
        <div class="container">
            <div class="row gutters-10 align-items-stretch">
                <!-- Top Categories -->
                <div class="{{ $hasBrands ? 'col-lg-6' : 'col-12' }} mb-4 mb-lg-0">
                    <div class="card shadow-sm border-0 h-100 rounded-lg transition-all hover-shadow-lg">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="h5 fw-700 text-dark mb-0 d-flex align-items-center">
                                    <i class="las la-th-large text-primary mr-2 fs-24"></i> Top Categories
                                </h3>
                                <a href="{{ route('front.allCategory') }}" class="text-primary fw-600 fs-13 hover-text-underline transition-all">View All <i class="las la-angle-right"></i></a>
                            </div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="row gutters-10">
                                @if(isset($categories) && $categories->count() > 0)
                                    @foreach($categories->take(6) as $cat)
                                    <div class="col-sm-6 mb-3">
                                        <a href="{{ route('catProductView', [$cat->id, $cat->slug]) }}" class="bg-white category-card d-block text-reset rounded-lg p-3 transition-all h-100">
                                            <div class="d-flex align-items-center">
                                                <div class="category-icon bg-light rounded-circle d-flex align-items-center justify-content-center mr-3 overflow-hidden" style="width: 50px; height: 50px; min-width: 50px;">
                                                    @if(!empty($cat->banner) && (filter_var($cat->banner, FILTER_VALIDATE_URL) || file_exists(public_path('Uploads/' . $cat->banner))))
                                                        <img src="{{ asset('frontend/assets/img/placeholder.jpg') }}" data-src="{{ filter_var($cat->banner, FILTER_VALIDATE_URL) ? $cat->banner : asset('Uploads/'.$cat->banner) }}" alt="{{ $cat->name }}" class="lazyload w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder.jpg') }}';">
                                                    @elseif(!empty($cat->icon) && str_starts_with($cat->icon, 'fa'))
                                                        <i class="{{ $cat->icon }} text-primary fs-20"></i>
                                                    @else
                                                        <i class="las la-th-large text-primary fs-20"></i>
                                                    @endif
                                                </div>
                                                <div class="category-name flex-grow-1">
                                                    <h4 class="fs-14 fw-600 mb-0 text-dark">{{ $cat->name }}</h4>
                                                </div>
                                                <div class="category-arrow text-primary opacity-0 transition-all">
                                                    <i class="las la-arrow-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    @endforeach
                                @else
                                    @php
                                        $topCats = [
                                            ['name' => "Women's Fashion", 'icon' => 'las la-female', 'url' => 'javascript:void(0)'],
                                            ['name' => "Men's Fashion", 'icon' => 'las la-tshirt', 'url' => 'javascript:void(0)'],
                                            ['name' => "Kids & Toy", 'icon' => 'las la-baby-carriage', 'url' => 'javascript:void(0)'],
                                            ['name' => "Electronics", 'icon' => 'las la-laptop', 'url' => 'javascript:void(0)'],
                                            ['name' => "Home & Garden", 'icon' => 'las la-couch', 'url' => 'javascript:void(0)'],
                                            ['name' => "Beauty & Health", 'icon' => 'las la-heartbeat', 'url' => 'javascript:void(0)'],
                                        ];
                                    @endphp
                                    @foreach($topCats as $cat)
                                    <div class="col-sm-6 mb-3">
                                        <a href="{{ $cat['url'] }}" class="bg-white category-card d-block text-reset rounded-lg p-3 transition-all h-100">
                                            <div class="d-flex align-items-center">
                                                <div class="category-icon bg-light rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; min-width: 50px;">
                                                    <i class="{{ $cat['icon'] }} text-primary fs-20"></i>
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Brands -->
                @if($hasBrands)
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100 rounded-lg transition-all hover-shadow-lg">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="h5 fw-700 text-dark mb-0 d-flex align-items-center">
                                    <i class="las la-award text-warning mr-2 fs-24"></i> Top Brands
                                </h3>
                                @if(Route::has('brand.all'))
                                    <a href="{{ route('brand.all') }}" class="text-primary fw-600 fs-13 hover-text-underline transition-all">View All <i class="las la-angle-right"></i></a>
                                @else
                                    <a href="javascript:void(0)" class="text-primary fw-600 fs-13 hover-text-underline transition-all">View All <i class="las la-angle-right"></i></a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="row gutters-10">
                                @foreach ($dbBrands as $brand)
                                @php
                                    $brandName = is_string($brand) ? $brand : (is_array($brand) ? ($brand['name'] ?? $brand['title'] ?? 'Brand') : ($brand->name ?? $brand->title ?? 'Brand'));
                                    $brandId = is_string($brand) ? 0 : (is_array($brand) ? ($brand['id'] ?? 0) : ($brand->id ?? 0));
                                    $brandSlug = is_string($brand) ? \Illuminate\Support\Str::slug($brand) : (is_array($brand) ? ($brand['slug'] ?? \Illuminate\Support\Str::slug($brandName)) : ($brand->slug ?? \Illuminate\Support\Str::slug($brandName)));
                                    $brandLogo = is_string($brand) ? null : (is_array($brand) ? ($brand['logo'] ?? $brand['image'] ?? null) : ($brand->logo ?? $brand->image ?? null));
                                @endphp
                                <div class="col-sm-6 mb-3">
                                    <a href="{{ Route::has('brandProductView') && $brandId > 0 ? route('brandProductView', [$brandId, $brandSlug]) : 'javascript:void(0)' }}" class="bg-white border border-light d-block text-reset p-3 rounded-lg transition-all brand-card h-100">
                                        <div class="d-flex align-items-center">
                                            <div class="brand-logo text-center mr-3 border-right pr-3" style="width: 70px;">
                                                @if(!empty($brandLogo) && (filter_var($brandLogo, FILTER_VALIDATE_URL) || file_exists(public_path('Uploads/' . $brandLogo))))
                                                    <img src="{{ asset('frontend/assets/img/placeholder.jpg') }}" data-src="{{ filter_var($brandLogo, FILTER_VALIDATE_URL) ? $brandLogo : asset('Uploads/' . $brandLogo) }}" alt="{{ $brandName }}" class="img-fluid lazyload" style="max-height: 40px; object-fit: contain;" onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder.jpg') }}';">
                                                @else
                                                    <img src="{{ asset('frontend/assets/img/placeholder.jpg') }}" alt="{{ $brandName }}" class="img-fluid" style="max-height: 40px; object-fit: contain;">
                                                @endif
                                            </div>
                                            <div class="brand-name flex-grow-1">
                                                <h4 class="fs-15 fw-700 text-dark mb-0">{{ $brandName }}</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
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
