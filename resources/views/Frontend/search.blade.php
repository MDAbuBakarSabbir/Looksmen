@extends('layouts.Frontend.master')
@section('title')
    Search Results for "{{ $keyword }}"
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
                                            @if($categoryType == 'category')
                                                <li class="mb-3">
                                                    <a class="text-primary fs-14 fw-700"
                                                        href="{{ route('catProductView', [$category->id, $category->slug]) }}">
                                                        <i class="las la-angle-down mr-1"></i>{{ $category->name }}
                                                    </a>
                                                </li>
                                                @foreach ($category->subcategories as $subcat)
                                                    <li class="ml-4 mb-2">
                                                        <a class="text-secondary fs-14 fw-500"
                                                             href="{{ route('subCatProductView', [$subcat->id, $subcat->slug]) }}">{{ $subcat->name }}</a>
                                                    </li>
                                                @endforeach
                                            @elseif($categoryType == 'subcategory')
                                                <li class="mb-3">
                                                    <a class="text-reset fs-14 fw-600"
                                                        href="{{ route('catProductView', [$parentCategory->id, $parentCategory->slug]) }}">
                                                        <i class="las la-angle-left mr-1"></i>{{ $parentCategory->name }}
                                                    </a>
                                                </li>
                                                <li class="mb-3 ml-3">
                                                    <a class="text-primary fs-14 fw-700"
                                                        href="{{ route('subCatProductView', [$category->id, $category->slug]) }}">
                                                        <i class="las la-angle-down mr-1"></i>{{ $category->name }}
                                                    </a>
                                                </li>
                                                @foreach ($category->childcategories as $childcat)
                                                    <li class="ml-5 mb-2">
                                                        <a class="text-secondary fs-14 fw-500"
                                                             href="{{ route('childCatProductView', [$childcat->id, $childcat->slug]) }}">{{ $childcat->name }}</a>
                                                    </li>
                                                @endforeach
                                            @elseif($categoryType == 'childcategory')
                                                <li class="mb-3">
                                                    <a class="text-reset fs-14 fw-600"
                                                        href="{{ route('catProductView', [$parentCategory->id, $parentCategory->slug]) }}">
                                                        <i class="las la-angle-left mr-1"></i>{{ $parentCategory->name }}
                                                    </a>
                                                </li>
                                                <li class="mb-3 ml-3">
                                                    <a class="text-reset fs-14 fw-600"
                                                        href="{{ route('subCatProductView', [$parentSubCategory->id, $parentSubCategory->slug]) }}">
                                                        <i class="las la-angle-left mr-1"></i>{{ $parentSubCategory->name }}
                                                    </a>
                                                </li>
                                                <li class="mb-3 ml-4">
                                                    <a class="text-primary fs-14 fw-700"
                                                        href="{{ route('childCatProductView', [$category->id, $category->slug]) }}">
                                                        <i class="las la-angle-down mr-1"></i>{{ $category->name }}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="premium-card mb-4">
                                    <div class="sidebar-title">
                                        <i class="las la-wallet mr-2 text-primary"></i>Price range
                                    </div>
                                    <div class="p-4">
                                        <div class="aiz-range-slider">
                                            <div id="input-slider-range" data-range-value-min="0"
                                                data-range-value-max="100000"></div>

                                            <div class="row mt-3">
                                                <div class="col-6">
                                                    <span class="badge badge-soft-primary px-3 py-2 fs-13 fw-600 rounded-pill"
                                                        data-range-value-low="0"
                                                        id="input-slider-range-value-low"></span>
                                                    <input type="hidden" name="min_price" value="0">
                                                </div>
                                                <div class="col-6 text-right">
                                                    <span class="badge badge-soft-primary px-3 py-2 fs-13 fw-600 rounded-pill"
                                                        data-range-value-high="100000"
                                                        id="input-slider-range-value-high"></span>
                                                    <input type="hidden" name="max_price" value="100000">
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
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb premium-breadcrumb bg-transparent p-0 justify-content-lg-start justify-content-center mb-0 fs-13">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Search</li>
                            </ol>
                        </nav>
                        
                        <div class="premium-card p-3 mb-4">
                            <div class="row align-items-center gutters-10">
                                <div class="col-10 col-lg mb-2 mb-lg-0">
                                    <h1 class="h5 fw-700 text-dark mb-0 d-flex align-items-center flex-wrap" style="gap: 8px;">
                                        <span class="text-secondary">Search Results for</span>
                                        <span class="badge badge-soft-primary px-3 py-2 rounded-pill fs-14 fw-600">
                                            "{{ $keyword }}"
                                        </span>
                                    </h1>
                                    <input type="hidden" name="keyword" id="searchKeyword" value="{{ $keyword }}">
                                </div>
                                <div class="col-2 col-lg-auto d-xl-none text-right mb-2 mb-lg-0">
                                    <button type="button" class="btn btn-soft-primary btn-icon p-2 rounded-circle" data-toggle="class-toggle"
                                        data-target=".aiz-filter-sidebar" title="Filter Products">
                                        <i class="la la-filter fs-20"></i>
                                    </button>
                                </div>
                                <div class="col-6 col-lg-auto w-lg-200px">
                                    <label class="mb-1 fw-600 text-muted fs-11 text-uppercase">Brands</label>
                                    @php
                                        $brands = \App\Models\Product::whereNotNull('brand_id')->where('brand_id', '!=', '')->select('brand_id')->distinct()->get();
                                    @endphp
                                    <select class="form-control form-control-sm custom-select rounded-pill shadow-none"
                                        name="brand" onchange="filter()">
                                        <option value="">All Brands</option>
                                        @foreach($brands as $b)
                                            @php
                                                $brandParts = explode('-', $b->brand_id);
                                                $brandName = count($brandParts) > 1 ? implode('-', array_slice($brandParts, 0, -1)) : $b->brand_id;
                                            @endphp
                                            <option value="{{ $b->brand_id }}">{{ $brandName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 col-lg-auto w-lg-200px">
                                    <label class="mb-1 fw-600 text-muted fs-11 text-uppercase">Sort by</label>
                                    <select class="form-control form-control-sm custom-select rounded-pill shadow-none" name="sort_by"
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
                        {{-- Hidden fields for AJAX context --}}
                        <input type="hidden" id="catType" value="{{ $categoryType }}">
                        <input type="hidden" id="catId"   value="{{ $keyword }}">

                        <div id="catProductGrid" class="row gutters-10 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2">
                            @include('Frontend.category.partials.cat_product_cards', ['catProducts' => $catProducts])
                        </div>

                        {{-- AJAX Pagination --}}
                        <div class="aiz-pagination aiz-pagination-center mt-5 mb-4" id="catPaginationWrap">
                            <div class="d-flex align-items-center justify-content-center flex-column gap-3">
                                <p class="text-muted fs-13 mb-2" id="catPaginationInfo">
                                    Showing <strong>{{ $catProducts->firstItem() ?? 0 }}</strong> &ndash;
                                    <strong>{{ $catProducts->lastItem() ?? 0 }}</strong> of
                                    <strong>{{ $catProducts->total() }}</strong> products
                                </p>
                                <nav id="catPaginationLinks" class="d-flex align-items-center flex-wrap" style="gap:6px;">
                                    @if($catProducts->lastPage() > 1)
                                        @if($catProducts->currentPage() > 1)
                                            <button type="button" class="btn btn-outline-secondary btn-sm cat-page-btn" data-page="{{ $catProducts->currentPage() - 1 }}" style="border-radius:8px;min-width:38px;">
                                                <i class="la la-angle-left"></i>
                                            </button>
                                        @endif
                                        @php
                                            $ps = max(1, $catProducts->currentPage() - 2);
                                            $pe = min($catProducts->lastPage(), $catProducts->currentPage() + 2);
                                        @endphp
                                        @if($ps > 1)
                                            <button type="button" class="btn btn-outline-secondary btn-sm cat-page-btn" data-page="1" style="border-radius:8px;min-width:38px;">1</button>
                                            @if($ps > 2)<span class="btn btn-sm disabled" style="min-width:38px;">&hellip;</span>@endif
                                        @endif
                                        @for($p = $ps; $p <= $pe; $p++)
                                            <button type="button" class="btn btn-sm cat-page-btn {{ $p == $catProducts->currentPage() ? 'btn-primary' : 'btn-outline-secondary' }}" data-page="{{ $p }}" style="border-radius:8px;min-width:38px;">{{ $p }}</button>
                                        @endfor
                                        @if($pe < $catProducts->lastPage())
                                            @if($pe < $catProducts->lastPage() - 1)<span class="btn btn-sm disabled" style="min-width:38px;">&hellip;</span>@endif
                                            <button type="button" class="btn btn-outline-secondary btn-sm cat-page-btn" data-page="{{ $catProducts->lastPage() }}" style="border-radius:8px;min-width:38px;">{{ $catProducts->lastPage() }}</button>
                                        @endif
                                        @if($catProducts->currentPage() < $catProducts->lastPage())
                                            <button type="button" class="btn btn-outline-secondary btn-sm cat-page-btn" data-page="{{ $catProducts->currentPage() + 1 }}" style="border-radius:8px;min-width:38px;">
                                                <i class="la la-angle-right"></i>
                                            </button>
                                        @endif
                                    @endif
                                </nav>
                            </div>
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

@section('script')
    <script>
        var catFilterPage = 1;
        var catFilterUrl  = "{{ route('front.category.filter') }}";

        function buildCatPaginationButtons(data) {
            if (data.last_page <= 1) {
                $('#catPaginationLinks').html('');
                $('#catPaginationInfo').text('');
                return;
            }
            var s = Math.max(1, data.current_page - 2);
            var e = Math.min(data.last_page, data.current_page + 2);
            var html = '';
            if (data.current_page > 1) {
                html += '<button type="button" class="btn btn-outline-secondary btn-sm cat-page-btn" data-page="' + (data.current_page - 1) + '" style="border-radius:8px;min-width:38px;"><i class="la la-angle-left"></i></button>';
            }
            if (s > 1) {
                html += '<button type="button" class="btn btn-outline-secondary btn-sm cat-page-btn" data-page="1" style="border-radius:8px;min-width:38px;">1</button>';
                if (s > 2) html += '<span class="btn btn-sm disabled" style="min-width:38px;">&hellip;</span>';
            }
            for (var p = s; p <= e; p++) {
                var cls = (p === data.current_page) ? 'btn-primary' : 'btn-outline-secondary';
                html += '<button type="button" class="btn btn-sm ' + cls + ' cat-page-btn" data-page="' + p + '" style="border-radius:8px;min-width:38px;">' + p + '</button>';
            }
            if (e < data.last_page) {
                if (e < data.last_page - 1) html += '<span class="btn btn-sm disabled" style="min-width:38px;">&hellip;</span>';
                html += '<button type="button" class="btn btn-outline-secondary btn-sm cat-page-btn" data-page="' + data.last_page + '" style="border-radius:8px;min-width:38px;">' + data.last_page + '</button>';
            }
            if (data.current_page < data.last_page) {
                html += '<button type="button" class="btn btn-outline-secondary btn-sm cat-page-btn" data-page="' + (data.current_page + 1) + '" style="border-radius:8px;min-width:38px;"><i class="la la-angle-right"></i></button>';
            }
            $('#catPaginationLinks').html(html);
            $('#catPaginationInfo').html(
                'Showing <strong>' + data.from + '</strong> &ndash; <strong>' + data.to + '</strong> of <strong>' + data.total + '</strong> products'
            );
        }

        function fetchCatProducts() {
            var grid = $('#catProductGrid');
            grid.css('opacity', 0.4);

            $.ajax({
                url: catFilterUrl,
                type: 'GET',
                data: {
                    type:      $('#catType').val(),
                    id:        $('#catId').val(),
                    sort_by:   $('[name="sort_by"]').val(),
                    brand:     $('[name="brand"]').val(),
                    min_price: $('[name="min_price"]').val(),
                    max_price: $('[name="max_price"]').val(),
                    page:      catFilterPage
                },
                success: function(res) {
                    grid.html(res.html).css('opacity', 1);
                    buildCatPaginationButtons(res);
                    // Re-init lazy loading
                    if (typeof lazyload !== 'undefined') lazyload.update();
                    $('[data-toggle="tooltip"]').tooltip();
                    // Scroll to grid
                    $('html, body').animate({ scrollTop: grid.offset().top - 120 }, 300);
                },
                error: function() {
                    grid.css('opacity', 1);
                }
            });
        }

        function filter() {
            catFilterPage = 1;
            fetchCatProducts();
        }

        // Paginator click (delegated)
        $(document).on('click', '.cat-page-btn', function(e) {
            e.preventDefault();
            catFilterPage = parseInt($(this).data('page'));
            fetchCatProducts();
        });

        $(document).ready(function() {
            var slider = document.getElementById('input-slider-range');
            if(slider && typeof noUiSlider !== 'undefined') {
                if(!slider.noUiSlider) {
                    var min = parseFloat(slider.getAttribute('data-range-value-min')) || 0;
                    var max = parseFloat(slider.getAttribute('data-range-value-max')) || 100000;
                    var startLow = parseFloat(document.getElementById('input-slider-range-value-low').getAttribute('data-range-value-low')) || min;
                    var startHigh = parseFloat(document.getElementById('input-slider-range-value-high').getAttribute('data-range-value-high')) || max;

                    noUiSlider.create(slider, {
                        start: [startLow, startHigh],
                        connect: true,
                        range: {
                            'min': min,
                            'max': max
                        }
                    });

                    slider.noUiSlider.on('update', function (values, handle) {
                        if (handle == 0) {
                            $('#input-slider-range-value-low').text(values[0]);
                            $('[name="min_price"]').val(values[0]);
                        } else {
                            $('#input-slider-range-value-high').text(values[1]);
                            $('[name="max_price"]').val(values[1]);
                        }
                    });
                }
                
                // Bind change event to trigger ajax
                slider.noUiSlider.on('change', function (values, handle) {
                    $('[name="min_price"]').val(values[0]);
                    $('[name="max_price"]').val(values[1]);
                    filter();
                });
            }
        });
    </script>
@endsection

