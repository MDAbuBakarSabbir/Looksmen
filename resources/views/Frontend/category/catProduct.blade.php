@extends('layouts.Frontend.master')
@section('title')
    {{ strtoupper($category->name) }}
@endsection
@section('content')
<style>
    /* Premium Aesthetic Typography & Base */
    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    /* Modern Container & Shadows */
    .premium-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    /* Sidebar Styles */
    .sidebar-title {
        background: linear-gradient(135deg, #f1f5f9 0%, #ffffff 100%);
        color: #0f172a;
        font-weight: 700;
        font-size: 1rem;
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    .sidebar-list a {
        transition: color 0.2s;
    }
    .sidebar-list a:hover {
        color: #3b82f6 !important;
        transform: translateX(5px);
        display: inline-block;
    }

    /* Product Cards */
    .premium-product-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(226, 232, 240, 0.6);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .premium-product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }
    .premium-product-card .img-fit {
        transition: transform 0.5s ease;
    }
    .premium-product-card:hover .img-fit {
        transform: scale(1.05);
    }

    /* Product Info & Pricing */
    .product-title-link {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
        text-decoration: none;
        transition: color 0.2s;
    }
    .product-title-link:hover {
        color: #3b82f6;
    }
    .price-new {
        font-weight: 800;
        color: #2563eb;
        font-size: 1.1rem;
    }
    .price-old {
        font-weight: 500;
        color: #94a3b8;
        text-decoration: line-through;
        font-size: 0.85rem;
    }

    /* Buttons & Badges */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        padding: 8px 15px;
        transition: all 0.3s;
    }
    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        color: white;
    }
    .badge-discount {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 20px;
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    /* Action Icons */
    .action-icon-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: rgba(255,255,255,0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        margin-bottom: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }
    .action-icon-btn:hover {
        background: #3b82f6;
        color: white;
        transform: scale(1.1);
    }
    
    /* Breadcrumb Custom */
    .premium-breadcrumb .breadcrumb-item a {
        color: #64748b;
        font-weight: 500;
    }
    .premium-breadcrumb .breadcrumb-item.active a {
        color: #3b82f6;
        font-weight: 600;
    }
</style>

    <section class="mb-5 pt-4">
        <div class="container sm-px-0">
            <form class="" id="search-form" action="#" method="GET">
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-xl-3">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
                                data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar c-scrollbar-light text-left">
                                <div class="d-flex d-xl-none justify-content-between align-items-center pl-3 border-bottom mb-3 pb-2">
                                    <h3 class="h6 mb-0 fw-700 text-dark">Filters</h3>
                                    <button type="button" class="btn btn-sm p-2 filter-sidebar-thumb"
                                        data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                        <i class="las la-times la-2x text-danger"></i>
                                    </button>
                                </div>
                                
                                <div class="premium-card mb-4">
                                    <div class="sidebar-title">
                                        <i class="las la-list-ul mr-2 text-primary"></i>Categories
                                    </div>
                                    <div class="p-4 sidebar-list">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-3">
                                                <a class="text-reset fs-14 fw-600" href="{{ route('front.allCategory') }}">
                                                    <i class="las la-angle-left mr-1"></i>All categories
                                                </a>
                                            </li>
                                            <li class="mb-3">
                                                <a class="text-primary fs-14 fw-700"
                                                    href="{{ route('catProductView', [$category->slug, $category->id]) }}">
                                                    <i class="las la-angle-down mr-1"></i>{{ $category->name }}
                                                </a>
                                            </li>
                                            @foreach ($category->subcategories as $subcat)
                                                <li class="ml-4 mb-2">
                                                    <a class="text-secondary fs-14 fw-500"
                                                        href="{{ route('subCatProductView', [$subcat->slug, $subcat->id]) }}">{{ $subcat->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="premium-card mb-4">
                                    <div class="sidebar-title">
                                        <i class="las la-wallet mr-2 text-primary"></i>Price range
                                    </div>
                                    <div class="p-4">
                                        <div class="aiz-range-slider">
                                            <div id="input-slider-range" data-range-value-min="120.00"
                                                data-range-value-max="15050.00"></div>

                                            <div class="row mt-3">
                                                <div class="col-6">
                                                    <span class="badge badge-soft-primary px-3 py-2 fs-13 fw-600 rounded-pill"
                                                        data-range-value-low="450.00"
                                                        id="input-slider-range-value-low"></span>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <span class="badge badge-soft-primary px-3 py-2 fs-13 fw-600 rounded-pill"
                                                        data-range-value-high="15050.00"
                                                        id="input-slider-range-value-high"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="col-xl-9">
                        <ul class="breadcrumb premium-breadcrumb bg-transparent p-0 mb-4">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('front.allCategory') }}">All categories</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{ route('catProductView', [$category->slug, $category->id]) }}">{{ $category->name }}</a>
                            </li>
                        </ul>
                        
                        <div class="premium-card p-3 mb-4">
                            <div class="row gutters-5 flex-wrap align-items-center">
                                <div class="col-lg col-10">
                                    <h1 class="h4 fw-800 text-dark mb-0">
                                        {{ $category->name }}
                                    </h1>
                                    <input type="hidden" name="keyword" value="">
                                </div>
                                <div class="col-2 col-lg-auto d-xl-none text-right">
                                    <button type="button" class="btn btn-soft-primary btn-icon p-2 rounded-circle" data-toggle="class-toggle"
                                        data-target=".aiz-filter-sidebar">
                                        <i class="la la-filter fs-20"></i>
                                    </button>
                                </div>
                                <div class="col-6 col-lg-auto mt-3 mt-lg-0 w-lg-200px">
                                    <label class="mb-1 fw-600 text-muted fs-12 text-uppercase">Brands</label>
                                    <select class="form-control form-control-sm aiz-selectpicker rounded-pill" data-live-search="true"
                                        name="brand" onchange="filter()">
                                        <option value="">All Brands</option>
                                        <option value="Apple-Rpu7X">Apple</option>
                                        <option value="Dove-5KnP4">Dove</option>
                                        <option value="Addidas-A4poF">Addidas</option>
                                        <option value="Gucci-FCyXE">Gucci</option>
                                        <option value="Lifeboy-f28SD">Lifeboy</option>
                                        <option value="Huwaei-rvanQ">Huwaei</option>
                                    </select>
                                </div>
                                <div class="col-6 col-lg-auto mt-3 mt-lg-0 w-lg-200px">
                                    <label class="mb-1 fw-600 text-muted fs-12 text-uppercase">Sort by</label>
                                    <select class="form-control form-control-sm aiz-selectpicker rounded-pill" name="sort_by"
                                        onchange="filter()">
                                        <option value="newest">Newest Arrivals</option>
                                        <option value="oldest">Oldest Arrivals</option>
                                        <option value="price-asc">Price: Low to High</option>
                                        <option value="price-desc">Price: High to Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="min_price" value="">
                        <input type="hidden" name="max_price" value="">
                        
                        <div class="row gutters-10 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2">

                            @forelse ($catProducts as $catProduct)
                                <div class="col mb-4">
                                    <div class="premium-product-card">
                                        @if($catProduct->discount_percentage)
                                            <span class="badge-discount">-{{ $catProduct->discount_percentage }}%</span>
                                        @endif
                                        <div class="position-relative overflow-hidden">
                                            <a href="{{ route('ProductView', [$catProduct->slug, $catProduct->id]) }}" class="d-block text-center pt-3">
                                                <img class="img-fit lazyload mx-auto h-160px h-md-210px"
                                                    src="{{ asset('frontEnd') }}/assets/img/placeholder.jpg"
                                                    data-src="{{ $catProduct->firstImage ? asset('adminDash/uploads/products/' . $catProduct->firstImage->image) : asset('frontEnd/assets/img/placeholder.jpg') }}"
                                                    alt="{{ $catProduct->title }}"
                                                    onerror="this.onerror=null;this.src='{{ asset('frontEnd') }}/assets/img/placeholder.jpg';">
                                            </a>
                                            <div class="absolute-top-right mt-2 mr-2 z-3">
                                                <a href="javascript:void(0)" onclick="addToWishList({{ $catProduct->id }})" class="action-icon-btn" data-toggle="tooltip" data-title="Add to wishlist">
                                                    <i class="la la-heart-o fs-18"></i>
                                                </a>
                                                <a href="javascript:void(0)" onclick="addToCompare({{ $catProduct->id }})" class="action-icon-btn" data-toggle="tooltip" data-title="Add to compare">
                                                    <i class="las la-sync fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="p-3 text-left d-flex flex-column flex-grow-1">
                                            <div class="rating rating-sm mb-1">
                                                @php
                                                    $avg = $catProduct->getAverageRating() ?? 0;
                                                    $fullStars = floor($avg);
                                                    $fraction = $avg - $fullStars;
                                                @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $fullStars)
                                                        <i class="las la-star text-warning"></i>
                                                    @elseif ($i == $fullStars + 1 && $fraction >= 0.5)
                                                        <i class="las la-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="las la-star text-secondary opacity-30"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <h3 class="fw-600 fs-14 text-truncate-2 lh-1-4 mb-2 flex-grow-1">
                                                <a href="{{ route('ProductView', [$catProduct->slug, $catProduct->id]) }}" class="product-title-link">{{ $catProduct->title }}</a>
                                            </h3>
                                            <div class="fs-15 mb-3 d-flex align-items-center">
                                                <span class="price-new mr-2">৳{{ $catProduct->new_price }}</span>
                                                @if($catProduct->old_price > $catProduct->new_price)
                                                    <del class="price-old">৳{{ $catProduct->old_price }}</del>
                                                @endif
                                            </div>
                                            <button class="btn-gradient-primary w-100 add-to-cart-btn" data-id="{{ $catProduct->id }}" data-type="product">
                                                <i class="las la-shopping-cart mr-1"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="premium-card p-5 text-center w-100">
                                        <i class="las la-frown la-4x text-muted mb-3"></i>
                                        <h3 class="h5 fw-600 text-dark">No products found</h3>
                                        <p class="text-muted mb-0">Try checking out our other categories!</p>
                                    </div>
                                </div>
                            @endforelse

                        </div>
                        
                        <div class="aiz-pagination aiz-pagination-center mt-5 mb-4">
                            <nav>
                                {{ $catProducts->links() }}
                            </nav>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Modals -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content premium-card">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title fw-700" id="myModalLabel">Confirmation</h4>
                </div>
                <div class="modal-body p-4">
                    <p class="fs-15">Are you sure you want to delete this?</p>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-600" data-dismiss="modal">Cancel</button>
                    <a id="delete_link" class="btn btn-danger rounded-pill px-4 fw-600 btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addToCart">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size"
            role="document">
            <div class="modal-content premium-card position-relative">
                <div class="c-preloader text-center p-3">
                    <i class="las la-spinner la-spin la-3x text-primary"></i>
                </div>
                <button type="button" class="close absolute-top-right btn-icon close z-1 mt-2 mr-2" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true" class="la-2x">&times;</span>
                </button>
                <div id="addToCart-modal-body" class="p-2">
                </div>
            </div>
        </div>
    </div>
@endsection

