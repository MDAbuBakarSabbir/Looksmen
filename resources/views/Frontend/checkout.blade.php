@extends('layouts.Frontend.master')
@section('hide_everything')
@endsection
@section('title')
    CHECKOUT
@endsection
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --chk-primary: #6366f1;
        --chk-primary-hover: #4f46e5;
        --chk-bg: #f8fafc;
        --chk-surface: #ffffff;
        --chk-border: #e2e8f0;
        --chk-text: #1e293b;
        --chk-muted: #64748b;
        --chk-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        --chk-radius: 16px;
    }

    body {
        font-family: 'Outfit', sans-serif !important;
        background-color: var(--chk-bg);
    }

    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Sleek Stepper */
    .sleek-stepper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--chk-surface);
        padding: 20px 40px;
        border-radius: var(--chk-radius);
        box-shadow: var(--chk-shadow);
        margin-bottom: 30px;
    }
    .stepper-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--chk-muted);
        position: relative;
        flex: 1;
    }
    .stepper-item.active {
        color: var(--chk-primary);
    }
    .stepper-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 60%;
        width: 80%;
        height: 2px;
        background: var(--chk-border);
        z-index: 1;
    }
    .stepper-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--chk-surface);
        border: 2px solid var(--chk-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 8px;
        z-index: 2;
        transition: all 0.3s;
    }
    .stepper-item.active .stepper-icon {
        border-color: var(--chk-primary);
        background: var(--chk-primary);
        color: white;
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
    }

    /* Cards */
    .chk-card {
        background: var(--chk-surface);
        border-radius: var(--chk-radius);
        box-shadow: var(--chk-shadow);
        padding: 30px;
        margin-bottom: 24px;
        border: 1px solid rgba(255,255,255,0.8);
    }

    .chk-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--chk-text);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--chk-border);
    }

    /* Forms */
    .chk-form-group {
        margin-bottom: 20px;
    }
    .chk-label {
        font-weight: 500;
        color: var(--chk-text);
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }
    .chk-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--chk-border);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .chk-input:focus {
        border-color: var(--chk-primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }
    
    /* Order Summary specific */
    .sticky-summary {
        position: sticky;
        top: 20px;
    }

    .cart-item-modern {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px dashed var(--chk-border);
    }
    .cart-item-modern:last-child {
        border-bottom: none;
    }
    .cart-img-wrap {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        overflow: hidden;
        margin-right: 15px;
        flex-shrink: 0;
        border: 1px solid var(--chk-border);
    }
    .cart-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .cart-details {
        flex-grow: 1;
    }
    .cart-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--chk-text);
        margin-bottom: 4px;
        line-height: 1.2;
    }
    .cart-meta {
        font-size: 0.8rem;
        color: var(--chk-muted);
    }
    .cart-price-col {
        text-align: right;
        min-width: 80px;
    }
    .cart-price-val {
        font-weight: 600;
        color: var(--chk-primary);
    }

    /* Select2 Customization */
    .select2-container--default .select2-selection--single {
        height: 48px;
        border: 1px solid var(--chk-border);
        border-radius: 10px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        outline: none !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--chk-text);
        font-size: 1rem;
        padding-left: 16px;
        font-family: 'Outfit', sans-serif;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px;
        right: 12px;
    }
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--chk-primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background: white;
    }
    .select2-dropdown {
        border: 1px solid var(--chk-border);
        border-radius: 10px;
        box-shadow: var(--chk-shadow);
        font-family: 'Outfit', sans-serif;
        overflow: hidden;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border-radius: 6px;
        border: 1px solid var(--chk-border);
        padding: 8px 12px;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        outline: none;
        border-color: var(--chk-primary);
    }
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: var(--chk-primary);
        color: white;
    }
    /* Buttons */
    .btn-chk-primary {
        background: var(--chk-primary);
        color: white;
        border: none;
        padding: 14px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(99,102,241,0.3);
    }
    .btn-chk-primary:hover:not(:disabled) {
        background: var(--chk-primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(99,102,241,0.4);
        color: white;
    }
    .btn-chk-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Payment Radio Modern */
    .payment-option-modern {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 1px solid var(--chk-border);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 10px;
    }
    .payment-option-modern:hover {
        border-color: var(--chk-primary);
        background: #f1f5f9;
    }
    .payment-option-modern input[type="radio"] {
        margin-right: 12px;
        transform: scale(1.2);
        accent-color: var(--chk-primary);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }
    .summary-row.total {
        font-size: 1.2rem;
        font-weight: 700;
        border-top: 1px solid var(--chk-border);
        padding-top: 15px;
        margin-top: 5px;
        color: var(--chk-primary);
    }

</style>

<div class="checkout-container py-4">
    <!-- Sleek Stepper -->
    <div class="sleek-stepper d-none d-md-flex">
        <div class="stepper-item">
            <div class="stepper-icon"><i class="las la-shopping-cart"></i></div>
            <span class="fw-600 fs-14">1. My Cart</span>
        </div>
        <div class="stepper-item active">
            <div class="stepper-icon"><i class="las la-map"></i></div>
            <span class="fw-600 fs-14">2. Shipping Info</span>
        </div>
        <div class="stepper-item">
            <div class="stepper-icon"><i class="las la-credit-card"></i></div>
            <span class="fw-600 fs-14">3. Payment</span>
        </div>
        <div class="stepper-item">
            <div class="stepper-icon"><i class="las la-check-circle"></i></div>
            <span class="fw-600 fs-14">4. Confirmation</span>
        </div>
    </div>

    <form action="{{ route('order.store') }}" method="post" id="checkoutForm">
        @csrf
        <div class="row">
            <!-- Left Column: Details & Payment -->
            <div class="col-lg-7">
                
                <!-- Customer Details Card -->
                <div class="chk-card">
                    <h3 class="chk-card-title">Shipping Details</h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chk-form-group">
                                <label class="chk-label" for="name">Your Name
                                    @auth <span class="text-muted fw-normal">(Optional)</span> @else <span class="text-danger">*</span> @endauth
                                </label>
                                <input type="text" class="chk-input" name="name" id="name" required placeholder="আপনার নাম" title="আপনার নাম">
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chk-form-group">
                                <label class="chk-label" for="phone">Your Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="chk-input" name="phone" id="phone" placeholder="Ex:01XXXXXXXXX" title="আপনার মোবাইল নাম্বার" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                <div class="invalid-feedback">Please enter a valid phone number.</div>
                                
                                <div id="scanLoading" class="mt-2 text-info d-none fs-13">
                                    <span class="spinner-border spinner-border-sm me-1"></span> Checking courier records...
                                </div>
                                <div id="courierInfo" class="mt-2 d-none fs-13 p-2 bg-light rounded border">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-info fw-600">Total: <span id="totalParcel"></span></span>
                                        <span class="text-danger fw-600">Cancelled: <span id="cancelledParcel"></span></span>
                                    </div>
                                    <div id="successRateText" class="fw-bold">Success Rate: <span id="successRate"></span>%</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="chk-form-group">
                        <label class="chk-label" for="district_select">Your District
                            @auth <span class="text-muted fw-normal">(Optional)</span> @else <span class="text-danger">*</span> @endauth
                        </label>
                        <select class="chk-input" required name="district_id" id="district_select">
                            <option value="null" disabled selected>আপনার জেলা নির্বাচন করুন</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" data-charge="{{ $district->delivery_charge }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select your district.</div>
                    </div>

                    @auth
                        <div class="chk-form-group">
                            <label class="chk-label" for="address">Your Address <span class="text-muted fw-normal">(Optional)</span></label>
                            <textarea class="chk-input" name="address" id="address" placeholder="আপনার সম্পূর্ণ ঠিকানা" rows="2"></textarea>
                        </div>
                        
                        <!-- Address Book Snippet -->
                        <div class="bg-light p-3 rounded mb-4 border">
                            <div class="row gutters-5">
                                @foreach ($addresses as $address)
                                    <div class="col-md-6 col-12 mb-3">
                                        <label class="aiz-megabox d-block bg-white mb-0 border rounded p-3 h-100 position-relative cursor-pointer" style="border-color: #cbd5e1 !important;">
                                            <input type="radio" name="address_id" value="{{ $address->id }}" required="">
                                            <span class="d-flex align-items-start mt-2">
                                                <span class="flex-grow-1 text-left fs-13">
                                                    <div class="mb-1"><span class="text-muted">Name:</span> <span class="fw-600">{{ $address->name }}</span></div>
                                                    <div class="mb-1"><span class="text-muted">Address:</span> <span class="fw-600">{{ $address->address }}, {{ $address->thana->name ?? 'N/A' }}, {{ $address->district->name ?? 'N/A' }}</span></div>
                                                    <div><span class="text-muted">Phone:</span> <span class="fw-600">{{ $address->phone }}</span></div>
                                                </span>
                                            </span>
                                        </label>
                                        <div class="position-absolute right-0 top-0 m-2">
                                            <button class="btn btn-sm btn-light p-1" type="button" onclick="edit_address('{{ $address->id }}')"><i class="las la-pen"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                                <input type="hidden" name="checkout_type" value="logged">
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="border border-dashed p-3 rounded text-center bg-white h-100 d-flex flex-column justify-content-center cursor-pointer hover-bg-light" onclick="add_new_address()" style="border-width: 2px !important; border-color: #cbd5e1 !important;">
                                        <i class="las la-plus la-2x mb-2 text-primary"></i>
                                        <div class="fw-600 text-primary">Add New Address</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="chk-form-group">
                            <label class="chk-label" for="address">Your Address <span class="text-danger">*</span></label>
                            <textarea class="chk-input" name="address" id="address" placeholder="আপনার সম্পূর্ণ ঠিকানা" rows="2" required></textarea>
                            <div class="invalid-feedback">Please provide your detailed address.</div>
                        </div>
                    @endauth

                    <div class="chk-form-group mb-0">
                        <label class="chk-label" for="note">Order Note <span class="text-muted fw-normal">(Optional)</span></label>
                        <textarea class="chk-input" placeholder="অর্ডার নোট" name="note" rows="2"></textarea>
                    </div>

                </div>

                <!-- Payment Methods Card -->
                <div class="chk-card">
                    <h3 class="chk-card-title">Payment Method</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="payment-option-modern w-100">
                                <input class="payment_method" type="radio" name="payment" id="cod" value="cod" checked>
                                <span class="fw-600">Cash on Delivery</span>
                            </label>
                        </div>
                        @if ($featuresConfig['payment_api'] == '1')
                        <div class="col-md-6">
                            <label class="payment-option-modern w-100">
                                <input class="payment_method" type="radio" name="payment" id="prepaid" value="prepaid">
                                <span class="fw-600">Prepayment <small class="text-muted">(Card/bKash)</small></span>
                            </label>
                        </div>
                        @endif
                        @if (isset($featuresConfig['wallet_system']) && $featuresConfig['wallet_system'] == '1' && auth()->check())
                        <div class="col-md-6">
                            <label class="payment-option-modern w-100">
                                <input class="payment_method" type="radio" name="payment" id="wallet" value="wallet">
                                <span class="fw-600">Pay with Wallet <small class="text-muted">(Balance: ৳{{ number_format(auth()->user()->wallet_balance, 2) }})</small></span>
                            </label>
                        </div>
                        @endif
                    </div>
                </div>

                @auth
                    <!-- Coupon Card -->
                    <div class="chk-card">
                        <h3 class="chk-card-title">Have a Coupon?</h3>
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <input type="text" class="chk-input" id="coupon_code" placeholder="Enter Coupon Code">
                                <input type="hidden" name="coupon_code" id="applied_coupon_name">
                                <div id="coupon_error_feedback" class="invalid-feedback fs-13 mt-1"></div>
                                <div id="coupon_success_feedback" class="valid-feedback text-success fs-13 mt-1"></div>
                            </div>
                            <button type="button" onclick="applyCoupon()" class="btn btn-dark ml-2 px-4 py-2" id="coupon_apply_btn" style="border-radius:10px;">
                                <span id="btn_text">Apply</span>
                                <span id="btn_spinner" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </div>
                    </div>
                @endauth

            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-lg-5">
                <div class="sticky-summary">
                    <div class="chk-card">
                        <h3 class="chk-card-title">Order Summary</h3>
                        
                        <!-- Cart Items -->
                        <div class="mb-4" style="max-height: 350px; overflow-y: auto; padding-right: 10px;">
                            @php $subtotal = 0; @endphp
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

                                <div class="cart-item-modern cart-row-{{ $id }}">
                                    <div class="cart-img-wrap">
                                        <img src="{{ asset($image) }}" alt="product">
                                    </div>
                                    <div class="cart-details">
                                        <div class="cart-title text-truncate" style="max-width: 180px;">{{ $name }}</div>
                                        <div class="text-info fs-11 fw-bold mb-1">Code: {{ $code }}</div>
                                        <div class="cart-meta mt-1 d-flex align-items-center">
                                            <div class="input-group input-group-sm" style="width: 80px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-secondary px-2" type="button" onclick="changeQuantity('{{ $id }}', -1)"><i class="las la-minus"></i></button>
                                                </div>
                                                <input type="text" class="form-control text-center px-0 cart-qty-{{ $id }}" data-max="{{ $stock }}" value="{{ $qty }}" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary px-2" type="button" onclick="changeQuantity('{{ $id }}', 1)"><i class="las la-plus"></i></button>
                                                </div>
                                            </div>
                                            <button type="button" onclick="removeguest('{{ $id }}')" class="btn btn-sm text-danger ml-2 p-0"><i class="las la-trash fs-16"></i></button>
                                        </div>
                                    </div>
                                    <div class="cart-price-col">
                                        <div class="text-muted fs-12 mb-1">৳{{ $price }} /ea</div>
                                        <div class="cart-price-val">৳<span class="line-total-{{ $id }}">{{ $line_total }}</span></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Calculations -->
                        <div id="order_summary">
                            <div class="summary-row">
                                <span class="text-muted fw-500">Subtotal</span>
                                <span class="fw-600">৳<span class="all-subtotal" id="summary-subtotal">{{ $subtotal }}</span></span>
                            </div>
                            <div class="summary-row">
                                <span class="text-muted fw-500">Shipping Charge</span>
                                <span class="fw-600">৳<span id="charge_display">0</span></span>
                            </div>
                            <div class="summary-row text-success">
                                <span class="fw-500">Discount</span>
                                <span class="fw-600">- ৳<span id="discount_amount">0</span></span>
                            </div>
                            <div class="summary-row total">
                                <span>Grand Total</span>
                                <span>৳<span id="grand-total">{{ $subtotal }}</span></span>
                            </div>

                            <!-- Hidden Inputs -->
                            <input type="hidden" name="total_amount" id="hidden_total_amount" value="{{ $subtotal }}">
                            <input type="hidden" name="delivery_charge" id="hidden_charge_display" value="">
                            <input type="hidden" name="coupon_discount" id="hidden_coupon_discount" value="0">
                            <input type="hidden" name="grand_total" id="hidden_grand_total" value="{{ $subtotal }}">
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            @php
                                $cartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->count() : count(session('cart', []));
                            @endphp
                            
                            @if ($cartCount == 0)
                                <a href="{{ url('/') }}" class="btn btn-chk-primary text-center d-block">Continue Shopping</a>
                            @else
                                <button type="submit" id="confirm_order_btn" class="btn btn-chk-primary" disabled="disabled">
                                    <i class="las la-check-circle me-1"></i> Confirm Order
                                </button>
                                <button type="submit" id="proceed_payment_btn" class="btn btn-chk-primary" style="display: none;">
                                    <i class="las la-credit-card me-1"></i> Proceed to Payment
                                </button>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modals -->
@if ($featuresConfig['payment_api'] == '1')
    <div class="modal fade" id="paymentOptionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document" style="max-width: 450px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow:hidden;">
                <div class="modal-header bg-light border-0 py-3">
                    <h6 class="modal-title fw-700">Select Payment Method</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-center text-muted mb-4 fs-14">অর্ডারটি কনফার্ম করতে অগ্রিম ডেলিভারি চার্জ পরিশোধ করুন।</p>
                    <div class="row g-3">
                        <div class="col-6 mb-3">
                            <div class="payment-card text-center p-3 border rounded cursor-pointer h-100 d-flex flex-column align-items-center justify-content-center" onclick="submitWithGateway('bkash')" style="transition: all 0.2s;">
                                <img src="https://www.logo.wine/a/logo/BKash/BKash-Logo.wine.svg" class="img-fluid" style="max-height: 80px;" alt="bKash">
                                <span class="d-block mt-2 small fw-600 text-dark">bKash</span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="payment-card text-center p-3 border rounded cursor-pointer h-100 d-flex flex-column align-items-center justify-content-center" onclick="submitWithGateway('ssl')" style="transition: all 0.2s;">
                                <img src="{{ asset('frontEnd/assets/img/OthersPayments.png') }}" class="img-fluid" style="max-height: 80px;" alt="SSLCommerz">
                                <span class="d-block mt-2 small fw-600 text-dark">Cards/Others</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .payment-card:hover { border-color: var(--chk-primary) !important; background: #f8fafc; transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
@endif

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            function initSelect2() {
                $('#district_select').select2({
                    width: '100%',
                    placeholder: 'আপনার জেলা নির্বাচন করুন'
                });
            }

            if (!$.fn.select2) {
                // dynamically load select2 JS and CSS if not present
                let link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
                document.head.appendChild(link);
                
                let script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
                script.onload = function() {
                    initSelect2();
                };
                document.head.appendChild(script);
            } else {
                initSelect2();
            }

            // ড্রপডাউন আইডি ঠিক আছে কি না নিশ্চিত করুন
            $(document).on('change', '#district_select', function() {

                // সিলেক্টেড অপশন থেকে ডেটা নেওয়া
                let selectedOption = $(this).find(':selected');
                let charge = selectedOption.data('charge');

                console.log("Selected Charge:", charge); // এটি চেক করার জন্য (ব্রাউজার কনসোলে দেখা যাবে)

                if (charge !== undefined && charge !== "") {
                    $('#charge_display').text(charge);
                    $('#hidden_charge_display').val(charge);
                } else {
                    $('#charge_display').text('0');
                }
            });
        });
    </script>

    <script>
        const $featuresConfig = @json($featuresConfig);
        $(document).on('change', 'input[name="payment"]', function() {
            updateOrderButtons();
        });
        let scanTimer = null;

        $('#phone').on('keyup', function() {
            let phone = $('#phone').val();
            let phoneRegex = /^(01)[3-9][0-9]{8}$/;
            let isPhoneValid = phoneRegex.test(phone);
            // let phone = $(this).val();

            if (phone.length !== 11 && !isPhoneValid) return;

            clearTimeout(scanTimer);

            scanTimer = setTimeout(() => {

                $('#scanLoading').removeClass('d-none');
                $('#courierInfo').addClass('d-none');

                $('input[name=payment]').prop('disabled', true);
                $('#confirm_order_btn, #proceed_payment_btn').addClass('disabled').attr('disabled', true);

                $.post("{{ route('check.fraud') }}", {
                    phone: phone,
                    _token: "{{ csrf_token() }}"
                }, function(res) {

                    $('#scanLoading').addClass('d-none');

                    if (!res.success) {
                        Toast.fire({
                            icon: 'error',
                            title: res.message
                        });
                        return;
                    }

                    $('#courierInfo').removeClass('d-none');

                    $('#totalParcel').text(res.data.total);
                    $('#deliveredParcel').text(res.data.delivered);
                    $('#cancelledParcel').text(res.data.cancelled);
                    $('#successRate').text(res.data.success_rate);

                    // Success rate color logic
                    if ($featuresConfig['payment_api'] == '1') {
                        if (res.data.total === 0) {
                            $('#successRateText').css('color', 'green');
                            $('#cod').prop('disabled', false).prop('checked', true);
                            $('#prepaid').prop('disabled', false);
                            $('#confirm_order_btn,#proceed_payment_btn').removeClass('disabled')
                                .removeAttr('disabled');
                            updateOrderButtons();
                        } else if (res.data.success_rate < res.min_rate) {
                            $('#successRateText').css('color', 'red');

                            $('#cod').prop('disabled', true);
                            $('#prepaid').prop('disabled', false).prop('checked', true);
                            $('#proceed_payment_btn').removeClass('disabled').removeAttr(
                                'disabled');
                            updateOrderButtons();

                            Swal.fire({
                                icon: 'warning',
                                title: 'Low Success Rate',
                                text: 'Cash on Delivery is disabled. Please use Prepayment.'
                            });
                        } else {
                            $('#successRateText').css('color', 'green');
                            $('#cod').prop('disabled', false).prop('checked', true);
                            $('#prepaid').prop('disabled', false);
                            $('#confirm_order_btn,#proceed_payment_btn').removeClass('disabled')
                                .removeAttr('disabled');
                            updateOrderButtons();
                        }

                        // Total parcel = 0 → COD allowed
                        if (res.data.total === 0) {
                            $('#cod').prop('disabled', false);
                        }
                    } else {
                        $('#confirm_order_btn').removeClass('disabled')
                            .removeAttr('disabled');
                        $('#cod').prop('disabled', false);
                        updateOrderButtons();

                    }

                }).fail(function() {
                    $('#scanLoading').addClass('d-none');
                    $('#cod').prop('disabled', false);
                    $('#prepaid').prop('disabled', false);
                    $('#confirm_order_btn, #proceed_payment_btn').removeClass('disabled')
                        .removeAttr('disabled');
                    Toast.fire({
                        icon: 'error',
                        title: 'Courier check failed.'
                    });
                });

            }, 400); // debounce for fast typing

        });

        // SweetAlert Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
        });

        const userWalletBalance = {{ auth()->check() ? auth()->user()->wallet_balance : 0 }};

        function updateOrderButtons() {
            let selectedPayment = $('input[name="payment"]:checked').val();

            if (selectedPayment === 'cod' || selectedPayment === 'wallet') {
                $('#confirm_order_btn').show();
                $('#proceed_payment_btn').hide();
            } else if (selectedPayment === 'prepaid') {
                $('#confirm_order_btn').hide();
                $('#proceed_payment_btn').show();
            }
        }

        // Intercept calculations to check wallet balance limits dynamically
        if (typeof calculateGrandTotal === 'function') {
            let originalCalculateGrandTotal = calculateGrandTotal;
            calculateGrandTotal = function(subtotal) {
                originalCalculateGrandTotal(subtotal);

                let grandTotal = parseFloat($('#grand-total').text().replace(/[৳,]/g, '').trim()) || 0;
                if (userWalletBalance < grandTotal) {
                    $('#wallet').prop('disabled', true).parent().addClass('opacity-50');
                    if ($('#wallet').is(':checked')) {
                        $('#cod').prop('checked', true);
                        updateOrderButtons();
                    }
                } else {
                    $('#wallet').prop('disabled', false).parent().removeClass('opacity-50');
                }
            };
        }

        $(document).ready(function() {
            let currentSubtotal = $('.all-subtotal').first().text().replace(/[৳,]/g, '').trim();
            if (typeof calculateGrandTotal === 'function') {
                calculateGrandTotal(currentSubtotal);
            }
            updateOrderButtons();
        });
    </script>

    <script>
        function applyCoupon() {
            // ১. সিলেক্টরগুলো ঠিক করা
            let codeInput = $('#coupon_code'); // ইনপুট এলিমেন্ট
            let code = codeInput.val().trim(); // ইনপুটের ভ্যালু
            let subtotal = $('.all-subtotal').first().text().replace(/[৳,]/g, '').trim();

            let errorFeedback = $('#coupon_error_feedback');
            let successFeedback = $('#coupon_success_feedback');
            let btn = $('#coupon_apply_btn');
            let btnText = $('#btn_text');
            let spinner = $('#btn_spinner');

            // আগের স্টেট রিসেট করা
            codeInput.removeClass('is-invalid is-valid');
            errorFeedback.hide().text('');
            successFeedback.hide().text('');

            if (code === "") {
                codeInput.addClass('is-invalid');
                errorFeedback.text('Please enter a coupon code!').removeAttr('style').show();
                return;
            }

            $.ajax({
                url: '{{ route('coupon.apply') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    code: code,
                    subtotal: subtotal
                },
                beforeSend: function() {
                    btn.prop('disabled', true);
                    btnText.text('Applying...');
                    spinner.removeClass('d-none');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        codeInput.addClass('is-valid');
                        successFeedback.text(response.message).removeAttr('style').show();
                        $('#discount_amount').text(response.discount);
                        $('#hidden_coupon_discount').val(response.discount);
                        $('#applied_coupon_name').val(code);
                        calculateGrandTotal(subtotal);
                    } else {
                        // এখানে এরর মেসেজ দেখাবে
                        codeInput.addClass('is-invalid');
                        errorFeedback.text(response.message).removeAttr('style').show();
                        $('#discount_amount').text(0);
                        calculateGrandTotal(subtotal);
                    }
                },
                error: function() {
                    codeInput.addClass('is-invalid');
                    errorFeedback.text('Server error. Please try again!').removeAttr('style').show();
                },
                complete: function() {
                    btn.prop('disabled', false);
                    btnText.text('Apply');
                    spinner.addClass('d-none');
                }
            });
        }
    </script>
    <script>
        let incompleteTimer;

        // ইনপুট ফিল্ডগুলোতে টাইপ করলে বা মাউস সরালে অটো সেভ হবে
        $(document).on('keyup change blur', '#name, #phone, #address, #district_select', function() {
            clearTimeout(incompleteTimer);
            incompleteTimer = setTimeout(function() {
                saveIncompleteOrder();
            }, 2000); // ২ সেকেন্ড টাইপিং বিরতি পেলে সেভ হবে
        });

        function saveIncompleteOrder() {
            let name = $('#name').val();
            let phone = $('#phone').val();
            let address = $('#address').val();
            let district = $('#district_select option:selected').text().trim();

            // অন্তত নাম বা ফোন না থাকলে সেভ করার দরকার নেই
            if (name === "" && phone === "") return;
            if (phone.length < 11) return;

            let subtotal = $('#summary-subtotal').text().replace(/[৳,Tk ]/g, '').trim();
            let grand_total = $('#grand-total').text().replace(/[৳,Tk ]/g, '').trim();

            $.ajax({
                url: '{{ route('order.incomplete.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    phone: phone,
                    address: address,
                    district: district,
                    subtotal: subtotal,
                    grand_total: grand_total
                },
                success: function(response) {
                    console.log("Incomplete order updated.");
                }
            });
        }
    </script>
    <script>
        $('form').on('submit', function() {
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            $(this).find('#btn_text').text('Processing...');
            $(this).find('#btn_spinner').removeClass('d-none');
        });
    </script>
    <script>
        $(document).ready(function() {
            // ১. যখন 'Proceed to Payment' বাটনে ক্লিক হবে
            $('#proceed_payment_btn').on('click', function(e) {
                e.preventDefault(); // ফর্ম অটো সাবমিট বন্ধ করা

                // ফর্ম ভ্যালিডেশন চেক (যদি সব ডাটা ঠিক থাকে)
                let name = $('#name').val();
                let phone = $('#phone').val();
                let address = $('#address').val();
                let district = $('#district_select').val();

                if (!name || !phone || !address || district == "null") {
                    alert("অনুগ্রহ করে আপনার নাম, জেলা, ঠিকানা এবং মোবাইল নাম্বারটি দিন।");
                    return false;
                }

                // পেমেন্ট মডাল দেখানো
                $('#paymentOptionModal').modal('show');
            });
        });

        // ২. গেটওয়ে অনুযায়ী ফর্ম সাবমিট করার ফাংশন
        function submitWithGateway(gateway) {
            let form = $('#confirm_order_btn').closest('form'); // আপনার মেইন ফর্ম আইডি

            if (gateway === 'bkash') {
                form.attr('action', '{{ route('bkash.payment') }}');
            } else if (gateway === 'ssl') {
                form.attr('action', '{{ route('others.payment') }}'); // আপনার SSL রাউট
            }

            // পেমেন্ট গেটওয়েতে যাওয়ার আগে একটি লোডিং দেখানো ভালো
            Swal.fire({
                title: 'অপেক্ষা করুন...',
                text: 'আপনাকে পেমেন্ট গেটওয়েতে নিয়ে যাওয়া হচ্ছে।',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            form.submit();
        }
    </script>
    <script>
        $('#checkoutForm').on('submit', function(e) {
            let district = $('#district_select').val();

            if (!district || district === "null") {
                e.preventDefault(); // ফর্ম সাবমিট বন্ধ করবে

                Toast.fire({
                    icon: 'error',
                    title: 'দুঃখিত!',
                    text: 'দয়া করে আপনার জেলা নির্বাচন করুন।'
                });
                $('.select2-selection').css('border', '1px solid red');
            }
        });

        // জেলা সিলেক্ট করলে লাল বর্ডার চলে যাবে
        $('#district_select').on('change', function() {
            $('.select2-selection').css('border', '1px solid #ced4da');
        });
    </script>
    @if (session('error'))
        <script>
            Swal.fire({
                title: 'দুঃখিত!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'আবার চেষ্টা করুন',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
@endsection

