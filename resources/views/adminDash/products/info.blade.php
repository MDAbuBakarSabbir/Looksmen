@extends('layouts.adminLays.master')
@section('title')
    PRODUCT DETAILS
@endsection

@section('content')
    <style>
        .premium-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .premium-card-header {
            background: #ffffff;
            padding: 24px 30px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .showcase-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .showcase-meta {
            font-size: 13px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .badge-premium {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-success-premium {
            background-color: #ecfdf5;
            color: #059669;
        }

        .badge-danger-premium {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .badge-primary-premium {
            background-color: #eeebff;
            color: #4f46e5;
        }

        .badge-secondary-premium {
            background-color: #f1f5f9;
            color: #475569;
        }

        /* Gallery Styles */
        .gallery-main {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .gallery-main img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .gallery-main:hover img {
            transform: scale(1.02);
        }

        .gallery-thumbs {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .gallery-thumb {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            border: 2px solid transparent;
            cursor: pointer;
            overflow: hidden;
            flex-shrink: 0;
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s ease;
        }

        .gallery-thumb img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .gallery-thumb.active {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        /* Product Details Info Block */
        .info-section-title {
            font-size: 14px;
            font-weight: 700;
            text-uppercase: true;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
        }

        .price-box {
            display: flex;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 15px;
        }

        .new-price {
            font-size: 32px;
            font-weight: 800;
            color: #4f46e5;
        }

        .old-price {
            font-size: 18px;
            text-decoration: line-through;
            color: #94a3b8;
        }

        .discount-badge {
            background-color: #fef2f2;
            color: #ef4444;
            font-weight: 700;
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 8px;
        }

        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .metadata-item {
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid #f1f5f9;
        }

        .metadata-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .metadata-value {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }

        .pill-container {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .color-pill {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            box-shadow: 0 0 0 1px #cbd5e1;
            cursor: default;
            position: relative;
        }

        .color-pill:hover::after {
            content: attr(data-name);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: #ffffff;
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 4px;
            white-space: nowrap;
            margin-bottom: 5px;
            z-index: 10;
        }

        .value-badge {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 600;
            font-size: 13px;
            padding: 5px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .action-button-bar {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 30px;
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
        }

        .btn-premium-primary {
            background-color: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .btn-premium-primary:hover {
            background-color: #4338ca;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-premium-secondary {
            background-color: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-premium-secondary:hover {
            background-color: #f8fafc;
            color: #1e293b;
            transform: translateY(-1px);
        }

        .cod-status-card {
            border: 1px solid;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 24px;
        }

        .cod-status-card.active {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .cod-status-card.inactive {
            background-color: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        .cod-icon {
            font-size: 28px;
        }

        .cod-details h5 {
            margin: 0;
            font-weight: 700;
            font-size: 15px;
        }

        .cod-details p {
            margin: 2px 0 0 0;
            font-size: 13px;
            opacity: 0.85;
        }
    </style>

    <!-- Back Button -->
    <div class="back mb-4">
        <a href="{{ route('product.index') }}" class="btn-premium-secondary py-2 px-3">
            <i class="fa-solid fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <div class="premium-card">
        <div class="premium-card-header">
            <div>
                <div class="showcase-title">{{ $product->title }}</div>
                <div class="showcase-meta">
                    <span><strong>Product Code:</strong> LM-{{ $product->code }}</span>
                    <span>•</span>
                    <span><strong>Category:</strong> {{ $categoryName }}</span>
                    @if($subcategoryName)
                        <span>•</span>
                        <span><strong>Subcategory:</strong> {{ $subcategoryName }}</span>
                    @endif
                    @if($childcategoryName)
                        <span>•</span>
                        <span><strong>Child Category:</strong> {{ $childcategoryName }}</span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <span class="badge-premium {{ $product->status == 1 ? 'badge-success-premium' : 'badge-danger-premium' }}">
                    <i class="fa-solid {{ $product->status == 1 ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                    {{ $product->status == 1 ? 'Published' : 'Draft' }}
                </span>
                @if($product->todays_deal == 1)
                    <span class="badge-premium badge-primary-premium">
                        <i class="fa-solid fa-fire"></i> Todays Deal
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body p-4 p-md-5">
            <div class="row">
                <!-- Left Side: Product Gallery -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="gallery-main" id="mainGalleryBox">
                        @if($proImages->isNotEmpty())
                            <img id="mainGalleryImage" src="{{ asset('adminDash/images/product/' . $proImages->first()->image) }}" alt="{{ $product->title }}">
                        @else
                            <img id="mainGalleryImage" src="{{ asset('adminDash/images/product/demo.jpg') }}" alt="Demo Product">
                        @endif
                    </div>
                    @if($proImages->count() > 1)
                        <div class="gallery-thumbs">
                            @foreach($proImages as $key => $img)
                                <div class="gallery-thumb {{ $key == 0 ? 'active' : '' }}" onclick="switchMainImage(this, '{{ asset('adminDash/images/product/' . $img->image) }}')">
                                    <img src="{{ asset('adminDash/images/product/' . $img->image) }}" alt="Thumbnail">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right Side: Details Info -->
                <div class="col-lg-6">
                    <!-- Pricing Details -->
                    <div class="info-section-title">PRICING</div>
                    <div class="price-box">
                        <span class="new-price">{{ $product->new_price }} BDT</span>
                        @if($product->old_price > $product->new_price)
                            <span class="old-price">{{ $product->old_price }} BDT</span>
                            <span class="discount-badge">-{{ $product->discount_percentage }}% OFF</span>
                        @endif
                    </div>

                    <!-- Inventory & Info -->
                    <div class="info-section-title">INVENTORY & SALES</div>
                    <div class="metadata-grid">
                        <div class="metadata-item">
                            <div class="metadata-label">Available Stock</div>
                            <div class="metadata-value">{{ $product->stock }} PCS</div>
                        </div>
                        <div class="metadata-item">
                            <div class="metadata-label">Total Sales</div>
                            <div class="metadata-value">{{ $product->num_of_sale ?? 0 }} PCS</div>
                        </div>
                    </div>

                    <!-- Attributes and Variations -->
                    @if($selectedAttribute && !empty($selectedAttributeValues))
                        <div class="info-section-title">ATTRIBUTES ({{ $selectedAttribute }})</div>
                        <div class="pill-container mb-4">
                            @foreach($selectedAttributeValues as $val)
                                <span class="value-badge">{{ $val }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Colors -->
                    @if($colors->isNotEmpty())
                        <div class="info-section-title">AVAILABLE COLORS</div>
                        <div class="pill-container mb-4">
                            @foreach($colors as $color)
                                <div class="color-pill" style="background-color: {{ $color->color_code }};" data-name="{{ $color->color_name }}"></div>
                            @endforeach
                        </div>
                    @endif

                    <!-- COD & Delivery Options -->
                    <div class="info-section-title">PAYMENT POLICY</div>
                    @if($product->cod == 0)
                        <div class="cod-status-card active">
                            <div class="cod-icon"><i class="fa-solid fa-shield-halved"></i></div>
                            <div class="cod-details">
                                <h5>Advance Payment Required</h5>
                                <p>Advance Amount: <strong>{{ $product->advance_amount ?? '0' }} BDT</strong></p>
                            </div>
                        </div>
                    @else
                        <div class="cod-status-card inactive">
                            <div class="cod-icon"><i class="fa-solid fa-truck-ramp-box"></i></div>
                            <div class="cod-details">
                                <h5>Cash On Delivery Available</h5>
                                <p>No advance payment needed to place orders.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Video Link -->
                    @if($product->video)
                        <div class="info-section-title">PRODUCT VIDEO</div>
                        <div class="mb-4">
                            <a href="{{ $product->video }}" target="_blank" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
                                <i class="fa-brands fa-youtube fa-lg"></i> Watch Product Video
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Description Card -->
    <div class="premium-card">
        <div class="premium-card-header">
            <h4 class="card-title font-weight-bold mb-0">Product Description</h4>
        </div>
        <div class="card-body p-4 p-md-5">
            <div style="font-size: 15px; line-height: 1.8; color: #334155;">
                {!! $product->description !!}
            </div>
        </div>
        
        <!-- Action Bar -->
        <div class="action-button-bar">
            <a href="{{ route('product.index') }}" class="btn-premium-secondary">
                <i class="fa-solid fa-circle-xmark"></i> Close
            </a>
            <a href="{{ route('product.edit', $product->id) }}" class="btn-premium-primary">
                <i class="fa-solid fa-pen-to-square"></i> Edit Product
            </a>
        </div>
    </div>

    <script>
        function switchMainImage(thumbElement, imageUrl) {
            document.getElementById('mainGalleryImage').src = imageUrl;
            document.querySelectorAll('.gallery-thumb').forEach(el => {
                el.classList.remove('active');
            });
            thumbElement.classList.add('active');
        }
    </script>
@endsection
