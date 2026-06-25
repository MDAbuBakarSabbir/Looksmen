@extends('layouts.Frontend.master')
@section('hide_everything')
@endsection
@section('title')
    Shopping Cart
@endsection
@section('content')
<style>
    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }
    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .cart-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
    }
    .cart-header {
        background: linear-gradient(135deg, #f1f5f9 0%, #ffffff 100%);
        padding: 24px 32px;
        border-bottom: 1px solid #f1f5f9;
    }
    .cart-title {
        font-weight: 700;
        font-size: 1.5rem;
        color: #0f172a;
        margin: 0;
    }
    .cart-item-row {
        transition: all 0.3s ease;
        padding: 24px 32px;
        border-bottom: 1px solid #f1f5f9;
    }
    .cart-item-row:hover {
        background-color: #f8fafc;
    }
    .cart-item-row:last-child {
        border-bottom: none;
    }
    .product-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }
    .cart-item-row:hover .product-img {
        transform: scale(1.05);
    }
    .product-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .product-code {
        font-size: 0.85rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        font-weight: 500;
    }
    .price-text {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.1rem;
    }
    .qty-control {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        padding: 4px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .qty-btn {
        background: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #475569;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .qty-btn:hover {
        background: #3b82f6;
        color: white;
    }
    .qty-input {
        width: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #0f172a;
    }
    .qty-input:focus {
        outline: none;
    }
    .total-price {
        font-weight: 800;
        color: #2563eb;
        font-size: 1.2rem;
    }
    .btn-remove {
        background: #fef2f2;
        color: #ef4444;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-remove:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }
    .cart-summary {
        background: white;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(226, 232, 240, 0.8);
        position: sticky;
        top: 20px;
    }
    .summary-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #0f172a;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px dashed #cbd5e1;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 16px;
        color: #64748b;
        font-weight: 500;
    }
    .summary-row.total {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px dashed #cbd5e1;
    }
    .btn-checkout {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        font-weight: 600;
        padding: 16px;
        border-radius: 12px;
        width: 100%;
        border: none;
        margin-top: 24px;
        transition: all 0.3s;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        display: block;
        text-align: center;
        text-decoration: none;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(37, 99, 235, 0.3);
        color: white;
    }
    .btn-continue {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        padding: 16px;
        border-radius: 12px;
        width: 100%;
        border: none;
        margin-top: 12px;
        transition: all 0.3s;
        display: block;
        text-align: center;
        text-decoration: none;
    }
    .btn-continue:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .empty-cart {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-cart-icon {
        font-size: 80px;
        color: #cbd5e1;
        margin-bottom: 24px;
    }
    .empty-cart-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.5rem;
        margin-bottom: 12px;
    }
    .empty-cart-text {
        color: #64748b;
        margin-bottom: 32px;
    }
</style>

<section class="py-5">
    <div class="container cart-container">
        
        <!-- Steps Indicator -->
        <div class="row aiz-steps arrow-divider mb-5">
            <div class="col active">
                <div class="text-center text-primary">
                    <i class="la-3x mb-2 las la-shopping-cart"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block">1. My Cart</h3>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                    <i class="la-3x mb-2 opacity-50 las la-map"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">2. Shipping Info</h3>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                    <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">3. Payment</h3>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                    <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">4. Confirmation</h3>
                </div>
            </div>
        </div>

        @php
            $subtotal = 0;
        @endphp

        <div class="row">
            <!-- Cart Items Section -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="cart-card">
                    <div class="cart-header d-flex justify-content-between align-items-center">
                        <h2 class="cart-title">Shopping Cart</h2>
                        <span class="badge badge-primary badge-pill fs-14 px-3 py-2" style="background: #e0e7ff; color: #4338ca;">
                            {{ (!empty($cart) && count($cart) > 0) ? count($cart) : 0 }} Items
                        </span>
                    </div>

                    @if (!empty($cart) && count($cart) > 0)
                        <div class="cart-items-wrapper">
                            @foreach ($cart as $key => $rawItem)
                                @php
                                    $isDb = is_object($rawItem);
                                    $id = $isDb ? $rawItem->cart_id : $key;
                                    $name = $isDb ? $rawItem->product->title ?? $rawItem->name : $rawItem['name'];
                                    $image = $isDb ? $rawItem->product->thumbnail_img ?? $rawItem->image : $rawItem['image'];
                                    $price = $isDb ? $rawItem->product->new_price ?? $rawItem->price : $rawItem['price'];
                                    $qty = $isDb ? $rawItem->quantity : $rawItem['quantity'];
                                    $stock = $isDb ? $rawItem->product->stock ?? 10 : $rawItem['stock'] ?? 10;
                                    $code = $isDb ? $rawItem->product->code ?? 'N/A' : $rawItem['code'] ?? 'N/A';
                                    
                                    $cleanPrice = (float) str_replace(',', '', $price);
                                    $line_total = $cleanPrice * (int) $qty;
                                    $subtotal += $line_total;
                                @endphp

                                <div class="cart-item-row d-flex flex-column flex-md-row align-items-md-center cart-row-{{ $id }}">
                                    
                                    <!-- Product Info -->
                                    <div class="d-flex align-items-center flex-grow-1 mb-3 mb-md-0">
                                        <img src="{{ asset($image) }}" class="product-img mr-4" alt="{{ $name }}">
                                        <div>
                                            <h4 class="product-title">{{ $name }}</h4>
                                            <div class="product-code mt-1"><i class="las la-barcode mr-1"></i> Code: {{ $code }}</div>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="text-md-center px-md-4 mb-3 mb-md-0" style="min-width: 120px;">
                                        <div class="text-muted fs-12 fw-600 mb-1 d-md-none">Price</div>
                                        <div class="price-text">৳ {{ number_format($cleanPrice, 2) }}</div>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="px-md-4 mb-3 mb-md-0 d-flex justify-content-md-center" style="min-width: 150px;">
                                        <div class="text-muted fs-12 fw-600 mb-1 d-md-none mr-3 align-self-center">Quantity</div>
                                        <div class="qty-control">
                                            <button class="qty-btn" type="button" onclick="changeQuantity('{{ $id }}', -1)">
                                                <i class="las la-minus"></i>
                                            </button>
                                            <input type="text" class="qty-input cart-qty-{{ $id }}" data-max="{{ $stock }}" value="{{ $qty }}" readonly>
                                            <button class="qty-btn" type="button" onclick="changeQuantity('{{ $id }}', 1)">
                                                <i class="las la-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Total & Action -->
                                    <div class="d-flex align-items-center justify-content-between px-md-2" style="min-width: 150px;">
                                        <div>
                                            <div class="text-muted fs-12 fw-600 mb-1 d-md-none">Subtotal</div>
                                            <div class="total-price">৳ <span class="line-total-{{ $id }}">{{ number_format($line_total, 2) }}</span></div>
                                        </div>
                                        <button onclick="removeguest('{{ $id }}')" class="btn-remove ml-3" title="Remove Item">
                                            <i class="las la-trash-alt fs-20"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-cart">
                            <i class="las la-shopping-basket empty-cart-icon"></i>
                            <h3 class="empty-cart-title">Your Cart is Empty</h3>
                            <p class="empty-cart-text">Looks like you haven't added anything to your cart yet.</p>
                            <a href="{{ url('/') }}" class="btn btn-primary px-5 py-2 fw-600" style="border-radius: 10px;">Start Shopping</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Summary Section -->
            @if (!empty($cart) && count($cart) > 0)
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="cart-summary">
                        <h3 class="summary-title">Order Summary</h3>
                        
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>৳ <span class="all-subtotal" id="cart-subtotal">{{ number_format($subtotal, 2) }}</span></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span class="text-success fw-600">Calculated in next step</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <span>৳ 0.00</span>
                        </div>
                        
                        <div class="summary-row total">
                            <span>Total</span>
                            <span class="text-primary">৳ <span class="all-subtotal">{{ number_format($subtotal, 2) }}</span></span>
                        </div>

                        <a href="{{ route('checkout') }}" class="btn-checkout">
                            Proceed to Checkout <i class="las la-arrow-right ml-2"></i>
                        </a>
                        <a href="{{ url('/') }}" class="btn-continue">
                            <i class="las la-arrow-left mr-2"></i> Continue Shopping
                        </a>
                        
                        <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-center text-muted fs-12">
                            <i class="las la-shield-alt fs-20 mr-2 text-success"></i>
                            Safe & Secure Checkout
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

