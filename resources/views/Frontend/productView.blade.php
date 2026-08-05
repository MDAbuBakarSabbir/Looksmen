@php
    use App\Models\ProductImage;

    $productImages = ProductImage::where('product_id', $singleProduct->id)->get();
@endphp


@extends('layouts.Frontend.master')

@section('title')
    {{ strtoupper($singleProduct->title) }}
@endsection

@section('meta_title', $singleProduct->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($singleProduct->description), 160))
@section('meta_image', $singleProduct->productImages->count() > 0 ? asset('Uploads/'.$singleProduct->productImages->first()->image) : '')
@section('canonical', url()->current())
@section('og_type', 'product')

@section('content')
    <!-- Product Schema Markup -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@@type": "Product",
      "name": "{{ $singleProduct->title }}",
      "image": "{{ $singleProduct->productImages->count() > 0 ? asset('Uploads/'.$singleProduct->productImages->first()->image) : '' }}",
      "description": "{{ \Illuminate\Support\Str::limit(strip_tags($singleProduct->description), 160) }}",
      "sku": "{{ $singleProduct->sku ?? $singleProduct->id }}",
      "offers": {
        "@@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "BDT",
        "price": "{{ $singleProduct->new_price }}",
        "availability": "{{ $singleProduct->quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "itemCondition": "https://schema.org/NewCondition"
      }
    }
    </script>

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

        /* Exclude Font Awesome icons from Outfit override */
        .prod-view-page i[class*="fa-"],
        .prod-view-page i.fas,
        .prod-view-page i.far,
        .prod-view-page i.fab,
        .prod-view-page i.fa {
            font-family: var(--_fa-family, "Font Awesome 7 Free") !important;
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

        @media (min-width: 1280px) {
            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl {
                max-width: 1280px !important;
            }
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

        /* Related Products Grid — no carousel */
        .rel-products-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
        }
        .rel-products-grid .rel-product-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .rel-products-grid .rel-product-card:hover {
            box-shadow: 0 4px 18px rgba(4,66,68,0.12);
            transform: translateY(-3px);
        }
        .rel-products-grid .rel-product-img-box {
            overflow: hidden;
        }
        .rel-products-grid .rel-product-details {
            padding: 8px 8px 10px;
        }
        @media (max-width: 767px) {
            .rel-products-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 8px;
            }
            .rel-products-grid .rel-product-img-box img {
                height: 110px !important;
            }
        }
        @media (max-width: 480px) {
            .rel-products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>



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
                                    <!-- Main Big Image Preview -->
                                    <div class="col-12 col-md order-1 order-md-2 mb-3 mb-md-0">
                                        <div class="gallery-main-box border rounded p-2 text-center bg-white shadow-sm img-zoom" style="min-height: 380px; display: flex; align-items: center; justify-content: center;">
                                            <img id="main-product-image" class="img-fluid" style="max-height: 480px; width: 100%; object-fit: contain; transition: opacity 0.2s ease-in-out;"
                                                src="{{ asset('Uploads/' . $singleProduct->productImages->first()->image) }}"
                                                onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder.jpg') }}';">
                                        </div>
                                    </div>

                                    <!-- Thumbnails Column -->
                                    <div class="col-12 col-md-auto order-2 order-md-1">
                                        <div class="product-thumbnails d-flex flex-row flex-md-column gap-2" style="max-height: 480px; overflow-y: auto;">
                                            @foreach ($singleProduct->productImages as $key => $productImage)
                                                <div class="product-thumb-item c-pointer p-1 rounded mb-2 {{ $key == 0 ? 'border-primary active-thumb' : '' }}"
                                                    data-image="{{ asset('Uploads/' . $productImage->image) }}"
                                                    style="width: 65px; height: 65px; cursor: pointer; border: 2px solid {{ $key == 0 ? '#044244' : '#e2e8f0' }}; transition: all 0.2s ease;">
                                                    <img class="w-100 h-100" style="object-fit: cover; border-radius: 4px;"
                                                        src="{{ asset('Uploads/' . $productImage->image) }}"
                                                        onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder.jpg') }}';">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="gallery-main-box img-zoom">
                                            <img class="img-fluid" src="{{ asset('frontend/assets/img/placeholder.jpg') }}">
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
                                                    <i class="fa-regular fa-star"></i>
                                                @elseif ($i == $fullStars + 1)
                                                    @if ($fraction >= 0.3 && $fraction <= 0.7)
                                                        <i class="fa-regular fa-star-half-alt"></i>
                                                    @elseif ($fraction > 0.7)
                                                        <i class="fa-regular fa-star"></i>
                                                    @else
                                                        <i class="fa-regular fa-star" style="color: #ced4da;"></i>
                                                    @endif
                                                @else
                                                    <i class="fa-regular fa-star" style="color: #ced4da;"></i>
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="font-weight-bold" style="color: #475569;">{{ number_format($avg, 1) }}</span>
                                    </div>
                                    <span class="text-muted" style="font-size: 0.9rem; font-weight: 500;">
                                        ({{ $singleProduct->reviews()->count() }} customer reviews)
                                    </span>
                                </div>

                                <div class="d-flex align-items-center mb-2 flex-wrap gap-2">
                                    <div class="sold-by-badge mr-2 mb-1">
                                        Sold by: LOOKSMEN
                                    </div>
                                    @if (!empty($singleProduct->code))
                                        <div class="sold-by-badge mr-2 mb-1" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;">
                                            Product Code: <strong class="text-dark">{{ $singleProduct->code }}</strong>
                                        </div>
                                    @endif
                                    @auth
                                    <button class="message-seller-btn mb-1" onclick="show_chat_modal()">
                                        <i class="fa fa-comments mr-1 fs-16"></i> Message Seller
                                    </button>
                                    @endauth
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
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <input type="number" name="quantity"
                                                        class="quantity-picker-input col border-0 text-center flex-grow-1 fs-16 input-number"
                                                        placeholder="1" value="1" min="1" max="10"
                                                        lang="en">
                                                    <button class="quantity-picker-btn btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="plus" data-field="quantity">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                                <div>
                                                    @if ($singleProduct->stock != 0)
                                                        <span class="stock-pill stock-in">
                                                            <i class="fa fa-check-circle mr-1"></i> <span id="available-quantity">{{ $singleProduct->stock }}</span> items available
                                                        </span>
                                                    @else
                                                        <span class="stock-pill stock-out">
                                                            <i class="fa fa-times-circle mr-1"></i> Out of Stock
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
                                            <i class="fa fa-shopping-bag" style="font-size: 1.2rem;"></i>
                                            <span>Add to Cart</span>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-action-modern btn-buy-now-custom buy-now-btn mb-2"
                                            data-title="Add to cart" data-id="{{ $singleProduct->id }}"
                                            data-type="product">
                                            <i class="fa fa-shopping-cart" style="font-size: 1.2rem;"></i>
                                            <span>Buy Now</span>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-action-modern btn-add-cart-custom add-to-cart mr-3 mb-2"
                                            onclick="addToWishList({{ $singleProduct->id }})">
                                            <i class="fa fa-heart" style="font-size: 1.2rem;"></i>
                                            <span>Add to Wishlist</span>
                                        </a>
                                        <button class="btn btn-action-modern btn-out-stock-custom mb-2" disabled>
                                            <i class="fa fa-cart-arrow-down" style="font-size: 1.2rem;"></i> Out of Stock
                                        </button>
                                    @endif
                                </div>

                                <!-- Wishlist, Compare and Call to Order Links -->
                                <div class="d-flex flex-wrap align-items-center mt-4 pt-3 border-top">
                                    @if ($singleProduct->stock != 0)
                                        <button type="button" class="btn-link-action"
                                            onclick="addToWishList({{ $singleProduct->id }})">
                                            <i class="fa fa-heart" style="font-size: 1.1rem;"></i> Add to wishlist
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn-link-action"
                                        onclick="addToCompare({{ $singleProduct->id }})">
                                        <i class="fa fa-sync" style="font-size: 1.1rem;"></i> Add to compare
                                    </button>
                                    
                                    <span class="text-muted ml-sm-auto mt-2 mt-sm-0 d-flex align-items-center fw-600" style="font-size: 0.95rem; color: #044244 !important;">
                                        <i class="fa fa-phone-volume mr-2" style="font-size: 1.3rem; color: #044244;"></i> Hotline Order: 
                                    </span>
                                </div>

                                <!-- Social Share -->
                                @php
                                    $currentUrl = url()->current();
                                    $shareUrl = urlencode($currentUrl);
                                    $shareTitle = urlencode($singleProduct->title);
                                @endphp
                                <div class="row no-gutters mt-4 align-items-center">
                                    <div class="col-auto">
                                        <div class="text-muted font-weight-bold mr-3" style="font-size: 0.95rem;">Share:</div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" 
                                               target="_blank" rel="noopener noreferrer" 
                                               class="btn btn-icon btn-sm rounded-circle text-white shadow-sm d-inline-flex align-items-center justify-content-center" 
                                               style="width: 36px; height: 36px; background-color: #1877F2; text-decoration: none; transition: transform 0.2s;"
                                               title="Share on Facebook" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                                <i class="fab fa-facebook-f" style="font-size: 1rem;"></i>
                                            </a>
                                            
                                            <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20-%20{{ $shareUrl }}" 
                                               target="_blank" rel="noopener noreferrer" 
                                               class="btn btn-icon btn-sm rounded-circle text-white shadow-sm d-inline-flex align-items-center justify-content-center" 
                                               style="width: 36px; height: 36px; background-color: #25D366; text-decoration: none; transition: transform 0.2s;"
                                               title="Share on WhatsApp" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                                <i class="fab fa-whatsapp" style="font-size: 1.15rem;"></i>
                                            </a>
                                            
                                            <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" 
                                               target="_blank" rel="noopener noreferrer" 
                                               class="btn btn-icon btn-sm rounded-circle text-white shadow-sm d-inline-flex align-items-center justify-content-center" 
                                               style="width: 36px; height: 36px; background-color: #1DA1F2; text-decoration: none; transition: transform 0.2s;"
                                               title="Share on Twitter" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                                <i class="fab fa-twitter" style="font-size: 1rem;"></i>
                                            </a>
                                            
                                            <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}" 
                                               target="_blank" rel="noopener noreferrer" 
                                               class="btn btn-icon btn-sm rounded-circle text-white shadow-sm d-inline-flex align-items-center justify-content-center" 
                                               style="width: 36px; height: 36px; background-color: #0088cc; text-decoration: none; transition: transform 0.2s;"
                                               title="Share on Telegram" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                                <i class="fab fa-telegram-plane" style="font-size: 1rem;"></i>
                                            </a>
                                            
                                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareTitle }}" 
                                               target="_blank" rel="noopener noreferrer" 
                                               class="btn btn-icon btn-sm rounded-circle text-white shadow-sm d-inline-flex align-items-center justify-content-center" 
                                               style="width: 36px; height: 36px; background-color: #0A66C2; text-decoration: none; transition: transform 0.2s;"
                                               title="Share on LinkedIn" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                                <i class="fab fa-linkedin-in" style="font-size: 1rem;"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    onclick="copyProductUrl('{{ $currentUrl }}')" 
                                                    class="btn btn-icon btn-sm rounded-circle text-white shadow-sm d-inline-flex align-items-center justify-content-center border-0" 
                                                    style="width: 36px; height: 36px; background-color: #64748b; transition: transform 0.2s;"
                                                    title="Copy Link" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                                <i class="fa fa-copy" style="font-size: 0.95rem;"></i>
                                            </button>
                                        </div>
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
                                                    <a href="{{ route('ProductView', [$product->id, $product->slug]) }}"
                                                        class="d-block mini-product-img">
                                                        <img class="img-fit lazyload" style="height: 65px; width: 100%; object-fit: cover;"
                                                            src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                                            data-src="{{ $product->firstImage ? asset('Uploads/' . $product->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
                                                            alt="{{ $product->title }}"
                                                            onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder.jpg';">
                                                    </a>
                                                </div>
                                                <div class="col-8 text-left">
                                                    <h4 class="fs-13 fw-600 mb-1" style="line-height: 1.3;">
                                                        <a href="{{ route('ProductView', [$product->id, $product->slug]) }}"
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
                                                                <i class="fa-regular fa-star"></i>
                                                            @elseif ($i == $fullStars + 1)
                                                                @if ($fraction >= 0.3 && $fraction <= 0.7)
                                                                    <i class="fa-regular fa-star-half-alt"></i>
                                                                @elseif ($fraction > 0.7)
                                                                    <i class="fa-regular fa-star"></i>
                                                                @else
                                                                    <i class="fa-regular fa-star" style="color: #ced4da;"></i>
                                                                @endif
                                                            @else
                                                                <i class="fa-regular fa-star" style="color: #ced4da;"></i>
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
                                                            <img src="{{ $review->user && $review->user->image ? asset('Uploads/' . $review->user->image) : asset('frontend/assets/img/placeholder.jpg') }}"
                                                                class="review-user-avatar mr-3"
                                                                onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder.jpg') }}';">

                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <h6 class="fs-14 fw-700 mb-0" style="color: #0f172a;">
                                                                        {{ $review->user ? $review->user->name : 'Guest User' }}
                                                                    </h6>

                                                                    <div class="rating-stars-gold" style="font-size: 0.85rem;">
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            <i class="fas fa-star {{ $i <= $review->review_star ? '' : 'opacity-30' }}" style="{{ $i <= $review->review_star ? '' : 'color: #ced4da;' }}"></i>
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
                                                <i class="fa fa-comment-slash text-muted mb-3" style="font-size: 48px; opacity: 0.4; display: block;"></i>
                                                <span class="fs-15 text-muted fw-600">There are no reviews for this product yet.</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Related Products Card List -->
                        @if($relProducts && $relProducts->count() > 0)
                            <div class="related-card-modern text-left">
                                <div class="related-title-modern d-flex justify-content-between align-items-center">
                                    <span>Related Products</span>
                                    <a href="{{ route('catProductView', [$singleProduct->category_id, $singleProduct->category->slug ?? 'category']) }}" 
                                       class="btn btn-sm fw-600 rounded-pill px-3 py-1 d-inline-flex align-items-center" style="font-size: 0.85rem; border: 1.5px solid #044244; color: #044244; background: transparent; text-decoration: none;">
                                        View More <i class="fa fa-angle-right ml-1 fs-14"></i>
                                    </a>
                                </div>
                                <div class="p-3">
                                    <div class="rel-products-grid">
                                        @foreach ($relProducts as $relProduct)
                                            <div class="rel-product-card">
                                                <div class="rel-product-img-box">
                                                    <a href="{{ route('ProductView', [$relProduct->id, $relProduct->slug]) }}" class="d-block">
                                                        <img class="lazyload" style="height: 160px; width: 100%; object-fit: cover; border-radius: 8px;"
                                                            src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                                            data-src="{{ $relProduct->firstImage ? asset('Uploads/' . $relProduct->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
                                                            alt="{{ $relProduct->title }}"
                                                            onerror="this.onerror=null;this.src='{{ asset('frontend/assets/img/placeholder.jpg') }}';">
                                                    </a>
                                                </div>
                                                <div class="rel-product-details">
                                                    <div class="mb-1">
                                                        @if($relProduct->old_price > $relProduct->new_price)
                                                            <del class="text-muted mr-1" style="font-size: 0.78rem;">৳{{ $relProduct->old_price }}</del>
                                                        @endif
                                                        <span class="fw-700 d-block" style="color: #044244; font-size: 0.95rem;">৳{{ $relProduct->new_price }}</span>
                                                    </div>
                                                    
                                                    <div class="rating-stars-gold mb-1" style="font-size: 0.7rem;">
                                                        @php
                                                            $avg = $relProduct->getAverageRating();
                                                            $fullStars = floor($avg);
                                                            $fraction = $avg - $fullStars;
                                                        @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $fullStars)
                                                                <i class="fa-regular fa-star"></i>
                                                            @elseif ($i == $fullStars + 1)
                                                                @if ($fraction >= 0.3 && $fraction <= 0.7)
                                                                    <i class="fa-regular fa-star-half-alt"></i>
                                                                @elseif ($fraction > 0.7)
                                                                    <i class="fa-regular fa-star"></i>
                                                                @else
                                                                    <i class="fa-regular fa-star" style="color: #ced4da;"></i>
                                                                @endif
                                                            @else
                                                                <i class="fa-regular fa-star" style="color: #ced4da;"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    
                                                    <h3 class="fw-600 mb-0" style="font-size: 0.8rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.6em;">
                                                        <a href="{{ route('ProductView', [$relProduct->id, $relProduct->slug]) }}"
                                                            class="d-block" style="color: #0f172a; text-decoration: none;">{{ $relProduct->title }}</a>
                                                    </h3>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        function copyProductUrl(url) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', 'Product link copied to clipboard!');
                    } else {
                        alert('Product link copied to clipboard!');
                    }
                });
            } else {
                var tempInput = document.createElement("input");
                tempInput.value = url;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('success', 'Product link copied to clipboard!');
                } else {
                    alert('Product link copied to clipboard!');
                }
            }
        }

        $(document).ready(function() {
            function initZoom() {
                if ($('.img-zoom')[0] && typeof $.fn.zoom === 'function') {
                    $('.img-zoom').trigger('zoom.destroy');
                    $('.img-zoom').zoom({
                        magnify: 1.5
                    });
                }
            }

            initZoom();

            $('.product-thumb-item').on('click', function(e) {
                e.preventDefault();
                var targetImage = $(this).attr('data-image');
                
                var $mainImg = $('#main-product-image');
                $mainImg.css('opacity', '0.3');
                
                $mainImg.attr('src', targetImage);
                $mainImg.off('load').on('load', function() {
                    $(this).css('opacity', '1');
                    initZoom();
                });
                
                // Active thumbnail styling
                $('.product-thumb-item').css('border-color', '#e2e8f0').removeClass('active-thumb');
                $(this).css('border-color', '#044244').addClass('active-thumb');
            });
        });
    </script>
@endsection

