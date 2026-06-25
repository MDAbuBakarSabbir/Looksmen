@php
    use App\Models\ProductImage;

    $productImages = ProductImage::where('product_id', $singleProduct->id)->get();
@endphp


@extends('layouts.Frontend.master')

@section('title')
    {{ strtoupper($singleProduct->title) }}
@endsection

@section('content')
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

        function show_chat_modal() {
            @if(Auth::check())
                var message = "Hello, I am interested in your product: {{ addslashes($singleProduct->title) }} ({{ url()->current() }})";
                window.location.href = "{{ route('conversation') }}?message=" + encodeURIComponent(message);
            @else
                AIZ.plugins.notify('warning', 'Please login first to send message.');
                setTimeout(function() {
                    window.location.href = "{{ route('login') }}";
                }, 1500);
            @endif
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

        .prod-view-page {
            font-family: 'Outfit', sans-serif !important;
            background-color: #f8fafc;
            color: #0f172a;
            padding-top: 30px;
            padding-bottom: 50px;
        }

        .prod-view-page * {
            font-family: 'Outfit', sans-serif !important;
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

        /* Redesign Styles */
        .prod-main-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.04);
            border: 1px solid #f1f5f9;
            padding: 30px;
            margin-bottom: 30px;
        }

        /* Gallery Styles */
        .product-gallery-container {
            position: sticky;
            top: 20px;
        }
        
        .gallery-main-box {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .gallery-main-box:hover {
            box-shadow: 0 12px 30px -10px rgba(0,0,0,0.1);
            border-color: #cbd5e1;
        }

        .product-gallery-thumb .carousel-box {
            border-radius: 12px !important;
            border: 2px solid #e2e8f0 !important;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #ffffff;
            overflow: hidden;
            margin: 4px;
        }

        .product-gallery-thumb .carousel-box:hover,
        .product-gallery-thumb .slick-current .carousel-box {
            border-color: #044244 !important;
            box-shadow: 0 4px 10px rgba(4, 66, 68, 0.08);
            transform: translateY(-2px);
        }

        /* Product Details Styles */
        .product-title-modern {
            font-size: 1.95rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            letter-spacing: -0.02em;
        }

        .rating-pill-container {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.9rem;
            gap: 6px;
            font-weight: 500;
        }

        .rating-stars-gold {
            color: #f59e0b;
            display: flex;
            gap: 2px;
        }

        .sold-by-badge {
            background: rgba(4, 66, 68, 0.06);
            color: #044244;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }

        .message-seller-btn {
            background: transparent;
            border: 1px solid #044244;
            color: #044244;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .message-seller-btn:hover {
            background: #044244;
            color: white;
            transform: translateY(-1px);
        }

        /* Pricing */
        .price-box-modern {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #f1f5f9;
            margin: 20px 0;
        }

        .regular-price-label {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
        }

        .regular-price-val {
            font-size: 1.15rem;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .current-price-val {
            font-size: 2.2rem;
            font-weight: 800;
            color: #044244;
        }

        .save-tag {
            background: #ef4444;
            color: white;
            padding: 4px 10px;
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 8px;
            margin-left: 10px;
            display: inline-block;
            vertical-align: middle;
            animation: pulse 2s infinite;
        }

        /* Quantity Picker Capsule */
        .quantity-picker-capsule {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 100px;
            padding: 4px;
            border: 1px solid #e2e8f0;
        }

        .quantity-picker-btn {
            width: 36px;
            height: 36px;
            border-radius: 50% !important;
            background: white;
            border: none;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .quantity-picker-btn:hover:not(:disabled) {
            background: #044244;
            color: white;
            transform: scale(1.05);
        }

        .quantity-picker-input {
            width: 45px;
            text-align: center;
            border: none !important;
            background: transparent;
            font-weight: 700;
            font-size: 1.05rem;
            color: #0f172a;
            margin: 0 4px;
        }

        .quantity-picker-input:focus {
            outline: none;
        }

        .stock-pill {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .stock-in {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .stock-out {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Action Buttons */
        .btn-action-modern {
            padding: 14px 28px;
            font-weight: 700;
            border-radius: 12px;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-add-cart-custom {
            background: #eef2f6;
            color: #044244;
            border: 1px solid #cbd5e1;
        }

        .btn-add-cart-custom:hover {
            background: #e2e8f0;
            border-color: #94a3b8;
            transform: translateY(-2px);
        }

        .btn-buy-now-custom {
            background: #044244;
            color: white;
            box-shadow: 0 4px 15px rgba(4, 66, 68, 0.2);
        }

        .btn-buy-now-custom:hover {
            background: #022b2c;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(4, 66, 68, 0.3);
            color: white;
        }

        .btn-out-stock-custom {
            background: #cbd5e1;
            color: #64748b;
            cursor: not-allowed;
        }

        /* Mini Links */
        .btn-link-action {
            color: #64748b;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
            text-decoration: none !important;
            border: none;
            background: transparent;
            padding: 0;
            margin-right: 20px;
            cursor: pointer;
        }

        .btn-link-action:hover {
            color: #044244;
        }

        /* Sidebar Top Selling */
        .sidebar-card-modern {
            background: white;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .sidebar-title-modern {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 1px solid #f1f5f9;
            padding: 20px;
        }

        .list-group-item-modern {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 15px 20px;
            transition: background-color 0.2s;
        }

        .list-group-item-modern:hover {
            background-color: #f8fafc;
        }

        .mini-product-img {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: transform 0.3s;
        }

        .list-group-item-modern:hover .mini-product-img {
            transform: scale(1.04);
        }

        /* Main Tab Panels */
        .tab-card-modern {
            background: white;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.04);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .tab-header-modern {
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
            display: flex;
        }

        .tab-link-modern {
            padding: 18px 25px;
            font-weight: 700;
            font-size: 1rem;
            color: #64748b;
            text-decoration: none !important;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }

        .tab-link-modern:hover {
            color: #044244;
        }

        .tab-link-modern.active {
            color: #044244;
            border-bottom-color: #044244;
            background: white;
        }

        /* Related Products Section */
        .related-card-modern {
            background: white;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .related-title-modern {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 1px solid #f1f5f9;
            padding: 20px;
        }

        .rel-product-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin: 10px 4px;
        }

        .rel-product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.06);
            border-color: #cbd5e1;
        }

        .rel-product-img-box {
            overflow: hidden;
            position: relative;
        }

        .rel-product-img-box img {
            transition: transform 0.5s ease;
        }

        .rel-product-card:hover .rel-product-img-box img {
            transform: scale(1.05);
        }

        .rel-product-details {
            padding: 15px;
            text-align: left;
        }

        /* Review items */
        .review-user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
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
                '../../www.googletagmanager.com/gtm5445.html?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-N36FZRPR');
    </script>
    <!-- End Google Tag Manager -->

    <!-- aiz-main-wrapper -->
    <div class="prod-view-page aiz-main-wrapper d-flex flex-column">

        <section class="mb-4">
            <div class="container">
                <div class="prod-main-card">
                    <div class="row">
                        <!-- Product Gallery Section -->
                        <div class="col-xl-5 col-lg-6 mb-4">
                            <div class="product-gallery-container sticky-top z-3 row gutters-10">
                                @if ($singleProduct->productImages->count() > 0)
                                    <div class="col order-1 order-md-2">
                                        <div class="gallery-main-box aiz-carousel product-gallery" data-nav-for='.product-gallery-thumb'
                                            data-fade='true' data-auto-height='true'>

                                            @foreach ($singleProduct->productImages as $key => $productImage)
                                                <div class="carousel-box img-zoom">
                                                    <img class="img-fluid lazyload" style="width: 100%; max-height: 480px; object-fit: contain;"
                                                        src="{{ asset('adminDash/uploads/products') }}/{{ $productImage->image }}"
                                                        data-src="{{ asset('adminDash/uploads/products') }}/{{ $productImage->image }}"
                                                        onerror="this.onerror=null;this.src='{{ asset('frontEnd/assets/img/placeholder.jpg') }}';">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-auto w-md-80px order-2 order-md-1 mt-3 mt-md-0">
                                        <div class="aiz-carousel product-gallery-thumb" data-items='5'
                                            data-nav-for='.product-gallery' data-vertical='true' data-vertical-sm='false'
                                            data-focus-select='true' data-arrows='true'>

                                            @foreach ($singleProduct->productImages as $key => $productImage)
                                                <div class="carousel-box c-pointer">
                                                    <img class="lazyload mw-100 size-50px mx-auto" style="object-fit: cover;"
                                                        src="{{ asset('adminDash/uploads/products') }}/{{ $productImage->image }}"
                                                        onerror="this.onerror=null;this.src='{{ asset('frontEnd/assets/img/placeholder.jpg') }}';">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="gallery-main-box img-zoom">
                                            <img class="img-fluid" src="{{ asset('frontEnd/assets/img/placeholder.jpg') }}">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Specs/Info Section -->
                        <div class="col-xl-7 col-lg-6">
                            <div class="text-left pl-md-3">
                                <h1 class="product-title-modern mb-3">{{ $singleProduct->title }}</h1>

                                <div class="d-flex align-items-center flex-wrap mb-4">
                                    <div class="rating-pill-container mr-3">
                                        <span class="rating-stars-gold">
                                            @php
                                                $avg = $singleProduct->getAverageRating();
                                                $fullStars = floor($avg);
                                                $fraction = $avg - $fullStars;
                                            @endphp

                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $fullStars)
                                                    <i class="las la-star"></i>
                                                @elseif ($i == $fullStars + 1)
                                                    @if ($fraction >= 0.3 && $fraction <= 0.7)
                                                        <i class="las la-star-half-alt"></i>
                                                    @elseif ($fraction > 0.7)
                                                        <i class="las la-star"></i>
                                                    @else
                                                        <i class="las la-star" style="color: #ced4da;"></i>
                                                    @endif
                                                @else
                                                    <i class="las la-star" style="color: #ced4da;"></i>
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="font-weight-bold" style="color: #475569;">{{ number_format($avg, 1) }}</span>
                                    </div>
                                    <span class="text-muted" style="font-size: 0.9rem; font-weight: 500;">
                                        ({{ $singleProduct->reviews()->count() }} customer reviews)
                                    </span>
                                </div>

                                <div class="d-flex align-items-center mb-2">
                                    <div class="sold-by-badge mr-3">
                                        Sold by: Inhouse Product
                                    </div>
                                    <button class="message-seller-btn" onclick="show_chat_modal()">
                                        <i class="las la-comments mr-1 fs-16"></i> Message Seller
                                    </button>
                                </div>

                                <!-- Pricing Card -->
                                <div class="price-box-modern text-left">
                                    @if ($singleProduct->old_price && $singleProduct->old_price > $singleProduct->new_price)
                                        <div class="mb-1">
                                            <span class="regular-price-label mr-2">Regular Price:</span>
                                            <span class="regular-price-val">৳{{ $singleProduct->old_price }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex align-items-center">
                                        <span class="current-price-val">৳{{ $singleProduct->new_price }}</span>
                                        <span class="text-muted ml-1" style="font-size: 1rem; font-weight: 500;">/ pcs</span>
                                        
                                        @if ($singleProduct->old_price && $singleProduct->new_price && $singleProduct->old_price > $singleProduct->new_price)
                                            @php
                                                $discountPercent = round((($singleProduct->old_price - $singleProduct->new_price) / $singleProduct->old_price) * 100);
                                            @endphp
                                            <span class="save-tag">SAVE {{ $discountPercent }}%</span>
                                        @endif
                                    </div>
                                </div>

                                <form id="option-choice-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $singleProduct->id }}">

                                    <!-- Quantity Picker and Stock Info -->
                                    <div class="row align-items-center no-gutters mb-4 mt-3">
                                        <div class="col-sm-2 text-left">
                                            <div class="text-muted font-weight-bold mb-2 mb-sm-0">Quantity:</div>
                                        </div>
                                        <div class="col-sm-10 text-left">
                                            <div class="d-flex align-items-center">
                                                <div class="quantity-picker-capsule mr-3">
                                                    <button class="quantity-picker-btn btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="minus" data-field="quantity"
                                                        disabled="">
                                                        <i class="las la-minus"></i>
                                                    </button>
                                                    <input type="number" name="quantity"
                                                        class="quantity-picker-input col border-0 text-center flex-grow-1 fs-16 input-number"
                                                        placeholder="1" value="1" min="1" max="10"
                                                        lang="en">
                                                    <button class="quantity-picker-btn btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="plus" data-field="quantity">
                                                        <i class="las la-plus"></i>
                                                    </button>
                                                </div>
                                                <div>
                                                    @if ($singleProduct->stock != 0)
                                                        <span class="stock-pill stock-in">
                                                            <i class="las la-check-circle mr-1"></i> <span id="available-quantity">{{ $singleProduct->stock }}</span> items available
                                                        </span>
                                                    @else
                                                        <span class="stock-pill stock-out">
                                                            <i class="las la-times-circle mr-1"></i> Out of Stock
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row no-gutters pb-3 d-none" id="chosen_price_div">
                                        <div class="col-sm-2 text-left">
                                            <div class="text-muted font-weight-bold my-2">Total Price:</div>
                                        </div>
                                        <div class="col-sm-10 text-left">
                                            <div class="product-price">
                                                <strong id="chosen_price" class="h4 fw-700 text-primary"></strong>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!-- Add to Cart / Buy Now Action buttons -->
                                <div class="mt-4 pt-2 d-flex flex-wrap align-items-center text-left">
                                    @if ($singleProduct->stock != 0)
                                        <a href="javascript:void(0)"
                                            class="btn btn-action-modern btn-add-cart-custom add-to-cart-btn mr-3 mb-2"
                                            data-title="Add to cart" data-id="{{ $singleProduct->id }}"
                                            data-type="product">
                                            <i class="las la-shopping-bag" style="font-size: 1.2rem;"></i>
                                            <span>Add to Cart</span>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-action-modern btn-buy-now-custom buy-now-btn mb-2"
                                            data-title="Add to cart" data-id="{{ $singleProduct->id }}"
                                            data-type="product">
                                            <i class="la la-shopping-cart" style="font-size: 1.2rem;"></i>
                                            <span>Buy Now</span>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-action-modern btn-add-cart-custom add-to-cart mr-3 mb-2"
                                            onclick="addToWishList({{ $singleProduct->id }})">
                                            <i class="las la-heart" style="font-size: 1.2rem;"></i>
                                            <span>Add to Wishlist</span>
                                        </a>
                                        <button class="btn btn-action-modern btn-out-stock-custom mb-2" disabled>
                                            <i class="la la-cart-arrow-down" style="font-size: 1.2rem;"></i> Out of Stock
                                        </button>
                                    @endif
                                </div>

                                <!-- Wishlist, Compare and Call to Order Links -->
                                <div class="d-flex flex-wrap align-items-center mt-4 pt-3 border-top">
                                    @if ($singleProduct->stock != 0)
                                        <button type="button" class="btn-link-action"
                                            onclick="addToWishList({{ $singleProduct->id }})">
                                            <i class="las la-heart" style="font-size: 1.1rem;"></i> Add to wishlist
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn-link-action"
                                        onclick="addToCompare({{ $singleProduct->id }})">
                                        <i class="las la-sync" style="font-size: 1.1rem;"></i> Add to compare
                                    </button>
                                    
                                    <span class="text-muted ml-sm-auto mt-2 mt-sm-0 d-flex align-items-center fw-600" style="font-size: 0.95rem; color: #044244 !important;">
                                        <i class="las la-phone-volume mr-2" style="font-size: 1.3rem; color: #044244;"></i> Hotline Order: +01788246452
                                    </span>
                                </div>

                                <!-- Social Share -->
                                <div class="row no-gutters mt-4 align-items-center">
                                    <div class="col-auto">
                                        <div class="text-muted font-weight-bold mr-3" style="font-size: 0.9rem;">Share:</div>
                                    </div>
                                    <div class="col">
                                        <div class="aiz-share"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Description, Reviews and Related Products Grid -->
        <section class="mb-4">
            <div class="container">
                <div class="row gutters-16">
                    <!-- Sidebar: Top Selling -->
                    <div class="col-xl-3 order-2 order-xl-1 mb-4">
                        <div class="sidebar-card-modern">
                            <div class="sidebar-title-modern">
                                Top Selling Products
                            </div>
                            <div class="p-0">
                                <ul class="list-group list-group-flush text-left">
                                    @foreach ($topSellingProducts as $product)
                                        <li class="list-group-item-modern list-group-item border-0">
                                            <div class="row gutters-10 align-items-center">
                                                <div class="col-4">
                                                    <a href="{{ route('ProductView', [$product->slug, $product->id]) }}"
                                                        class="d-block mini-product-img">
                                                        <img class="img-fit lazyload" style="height: 65px; width: 100%; object-fit: cover;"
                                                            src="{{ asset('frontEnd') }}/assets/img/placeholder.jpg"
                                                            data-src="{{ $product->firstImage ? asset('adminDash/uploads/products/' . $product->firstImage->image) : asset('frontEnd/assets/img/placeholder.jpg') }}"
                                                            alt="{{ $product->title }}"
                                                            onerror="this.onerror=null;this.src='{{ asset('frontEnd') }}/assets/img/placeholder.jpg';">
                                                    </a>
                                                </div>
                                                <div class="col-8 text-left">
                                                    <h4 class="fs-13 fw-600 mb-1" style="line-height: 1.3;">
                                                        <a href="{{ route('ProductView', [$product->slug, $product->id]) }}"
                                                            class="d-block text-reset" style="color: #0f172a; text-decoration: none;">{{ Str::limit($product->title, 32) }}</a>
                                                    </h4>
                                                    <div class="rating-stars-gold mb-1" style="font-size: 0.75rem;">
                                                        @php
                                                            $avg = $product->getAverageRating();
                                                            $fullStars = floor($avg);
                                                            $fraction = $avg - $fullStars;
                                                        @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $fullStars)
                                                                <i class="las la-star"></i>
                                                            @elseif ($i == $fullStars + 1)
                                                                @if ($fraction >= 0.3 && $fraction <= 0.7)
                                                                    <i class="las la-star-half-alt"></i>
                                                                @elseif ($fraction > 0.7)
                                                                    <i class="las la-star"></i>
                                                                @else
                                                                    <i class="las la-star" style="color: #ced4da;"></i>
                                                                @endif
                                                            @else
                                                                <i class="las la-star" style="color: #ced4da;"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <div>
                                                        <span class="fw-700" style="color: #044244; font-size: 0.95rem;">৳{{ $product->new_price }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Main Tab Content + Related Products List -->
                    <div class="col-xl-9 order-1 order-xl-2 col-12">
                        <div class="tab-card-modern">
                            <div class="tab-header-modern nav" role="tablist">
                                <a href="#tab_default_1" data-toggle="tab"
                                    class="tab-link-modern active show">Description</a>
                                <a href="#tab_default_4" data-toggle="tab"
                                    class="tab-link-modern">Reviews ({{ $singleProduct->reviews()->count() }})</a>
                            </div>

                            <div class="tab-content pt-0">
                                <div class="tab-pane fade active show" id="tab_default_1">
                                    <div class="p-4 text-left">
                                        <div class="mw-100 overflow-hidden aiz-editor-data" style="color: #475569; font-size: 1rem; line-height: 1.7;">
                                            <h3 class="fw-700 mb-3" style="color: #0f172a; font-size: 1.35rem; letter-spacing: -0.01em;">
                                                Product Description
                                            </h3>
                                            <div style="white-space: pre-line;">
                                                {!! nl2br($singleProduct->description) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tab_default_4">
                                    <div class="p-4 text-left">
                                        @if ($singleProduct->reviews->count() > 0)
                                            <ul class="list-group list-group-flush">
                                                @foreach ($singleProduct->reviews as $review)
                                                    <li class="list-group-item px-0 py-3 border-0" style="border-bottom: 1px solid #f1f5f9 !important;">
                                                        <div class="d-flex align-items-start">
                                                            <img src="{{ $review->user && $review->user->image ? asset('uploads/users/' . $review->user->image) : asset('frontEnd/assets/img/placeholder.jpg') }}"
                                                                class="review-user-avatar mr-3"
                                                                onerror="this.onerror=null;this.src='{{ asset('frontEnd/assets/img/placeholder.jpg') }}';">

                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <h6 class="fs-14 fw-700 mb-0" style="color: #0f172a;">
                                                                        {{ $review->user ? $review->user->name : 'Guest User' }}
                                                                    </h6>

                                                                    <div class="rating-stars-gold" style="font-size: 0.85rem;">
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            <i class="las la-star {{ $i <= $review->review_star ? '' : 'opacity-30' }}" style="{{ $i <= $review->review_star ? '' : 'color: #ced4da;' }}"></i>
                                                                        @endfor
                                                                    </div>
                                                                </div>

                                                                <div class="text-muted fs-12 mb-2">
                                                                    {{ $review->created_at ? $review->created_at->format('d M, Y') : 'Date not available' }}
                                                                </div>

                                                                <p class="mt-2 mb-0" style="color: #475569; font-size: 0.95rem; line-height: 1.6;">
                                                                    {{ $review->review_description }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="las la-comment-slash text-muted mb-3" style="font-size: 48px; opacity: 0.4; display: block;"></i>
                                                <span class="fs-15 text-muted fw-600">There are no reviews for this product yet.</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Related Products Card List -->
                        <div class="related-card-modern text-left">
                            <div class="related-title-modern">
                                Related Products
                            </div>
                            <div class="p-3">
                                <div class="aiz-carousel gutters-5 half-outside-arrow" data-items="4" data-xl-items="3"
                                    data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2"
                                    data-arrows='true' data-infinite='true' data-autoplay="true" data-speed="500">
                                    @foreach ($relProducts as $relProduct)
                                        <div class="carousel-box">
                                            <div class="rel-product-card">
                                                <div class="rel-product-img-box">
                                                    <a href="{{ route('ProductView', [$relProduct->slug, $relProduct->id]) }}" class="d-block">
                                                        <img class="img-fit lazyload mx-auto" style="height: 190px; width: 100%; object-fit: cover;"
                                                            src="{{ asset('frontEnd') }}/assets/img/placeholder.jpg"
                                                            data-src="{{ $relProduct->firstImage ? asset('adminDash/uploads/products/' . $relProduct->firstImage->image) : asset('frontEnd/assets/img/placeholder.jpg') }}"
                                                            alt="{{ $relProduct->title }}"
                                                            onerror="this.onerror=null;this.src='{{ asset('frontEnd') }}/assets/img/placeholder.jpg';">
                                                    </a>
                                                </div>
                                                <div class="rel-product-details">
                                                    <div class="mb-2">
                                                        @if($relProduct->old_price > $relProduct->new_price)
                                                            <del class="text-muted mr-2" style="font-size: 0.85rem;">৳{{ $relProduct->old_price }}</del>
                                                        @endif
                                                        <span class="fw-700" style="color: #044244; font-size: 1.05rem;">৳{{ $relProduct->new_price }}</span>
                                                    </div>
                                                    
                                                    <div class="rating-stars-gold mb-2" style="font-size: 0.75rem;">
                                                        @php
                                                            $avg = $relProduct->getAverageRating();
                                                            $fullStars = floor($avg);
                                                            $fraction = $avg - $fullStars;
                                                        @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $fullStars)
                                                                <i class="las la-star"></i>
                                                            @elseif ($i == $fullStars + 1)
                                                                @if ($fraction >= 0.3 && $fraction <= 0.7)
                                                                    <i class="las la-star-half-alt"></i>
                                                                @elseif ($fraction > 0.7)
                                                                    <i class="las la-star"></i>
                                                                @else
                                                                    <i class="las la-star" style="color: #ced4da;"></i>
                                                                @endif
                                                            @else
                                                                <i class="las la-star" style="color: #ced4da;"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    
                                                    <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0" style="height: 38px;">
                                                        <a href="{{ route('ProductView', [$relProduct->slug, $relProduct->id]) }}"
                                                            class="d-block text-reset" style="color: #0f172a; text-decoration: none;">{{ $relProduct->title }}</a>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

