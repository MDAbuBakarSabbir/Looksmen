@extends('layouts.Frontend.master')
@section('hide_everything')
@endsection
@section('title')
    CART VIEW
@endsection
@section('content')
    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row aiz-steps arrow-divider">
                        <div class="col active">
                            <div class="text-center text-primary">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">1. My Cart</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <i class="la-3x mb-2 opacity-50 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">2. Shipping info</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">4. Payment</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">5. Confirmation</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @php
        $subtotal = 0;
    @endphp

    <div class="modal-body" style="max-height: calc(75vh - 100px); overflow-y: auto;">
        @if (!empty($cart) && count($cart) > 0)
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col" class="text-center">Qty</th>
                            <th scope="col">Total</th>
                            <th scope="col" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $key => $rawItem)
                            @php
                                // ১. ডাটাবেস অবজেক্ট নাকি সেশন অ্যারে তা চেক করা
                                $isDb = is_object($rawItem);

                                // ২. আইডি সেট করা (লগইন থাকলে cart_id, না থাকলে সেশন কি $key)
                                $id = $isDb ? $rawItem->cart_id : $key;

                                // ৩. ডাটা ম্যাপিং (লগআউট অবস্থায় অ্যারে ইন্ডেক্স ব্যবহার করা হয়েছে)
                                $name = $isDb ? $rawItem->product->title ?? $rawItem->name : $rawItem['name'];
                                $image = $isDb
                                    ? ($rawItem->product?->firstImage?->image ?? $rawItem->image)
                                    : $rawItem['image'];
                                $price = $isDb ? $rawItem->product->new_price ?? $rawItem->price : $rawItem['price'];
                                $qty = $isDb ? $rawItem->quantity : $rawItem['quantity'];
                                $stock = $isDb ? $rawItem->product->stock ?? 10 : $rawItem['stock'] ?? 10;
                                $code = $isDb ? $rawItem->product->code ?? 'N/A' : $rawItem['code'] ?? 'N/A';
                                $attribute = $isDb ? ($rawItem->attributes ?? $rawItem->attribute ?? null) : ($rawItem['attribute'] ?? $rawItem['attributes'] ?? $rawItem['size'] ?? null);
                                $color = $isDb ? ($rawItem->color ?? null) : ($rawItem['color'] ?? null);

                                if (!empty($attribute) && is_numeric($attribute)) {
                                    $attrValObj = \App\Models\AttributeValues::find($attribute);
                                    if ($attrValObj) {
                                        $attribute = $attrValObj->value;
                                    }
                                }

                                $cleanPrice = (float) str_replace(',', '', $price);
                                $line_total = $cleanPrice * (int) $qty;
                                $subtotal += $line_total;
                            @endphp

                            <tr class="border-bottom cart-row-{{ $id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $image ? asset('Uploads/' . $image) : asset('frontend/assets/img/placeholder.jpg') }}" class="size-60px mr-2 rounded">
                                        <div>
                                            <span class="fs-14 fw-600 d-block text-truncate" style="max-width: 150px;" title="{{ $name }}">{{ \Illuminate\Support\Str::limit($name, 25) }}</span>
                                            <small class="text-info font-weight-bold d-block">Code: {{ $code }}</small>
                                            @if(!empty($attribute) && $attribute !== 'N/A')
                                                <small class="text-muted d-block"><span class="font-weight-bold text-dark"></span> {{ $attribute }}</small>
                                            @endif
                                            @if(!empty($color) && $color !== 'N/A')
                                                <small class="text-muted d-block"><span class="font-weight-bold text-dark">Color:</span> {{ $color }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>৳ {{ $price }}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="input-group aiz-plus-minus" style="width: 100px;">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="changeQuantity('{{ $id }}', -1)">
                                                    <i class="las la-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text"
                                                class="form-control form-control-sm text-center cart-qty-{{ $id }}"
                                                data-max="{{ $stock }}" value="{{ $qty }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="changeQuantity('{{ $id }}', 1)">
                                                    <i class="las la-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-600 text-primary">৳<span class="line-total-{{ $id }}">
                                        {{ $line_total }}</span></td>
                                <td class="text-right">
                                    <button onclick="removeguest('{{ $id }}')"
                                        class="btn btn-soft-danger btn-circle btn-sm">
                                        <i class="las la-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center p-4">
                <i class="las la-frown la-3x opacity-60 mb-3"></i>
                <h6>Your cart is empty</h6>
            </div>
        @endif
    </div>

    @if (!empty($cart) && count($cart) > 0)
        <style>
            @media (max-width: 575.98px) {
                .cart-footer-buttons {
                    width: 100% !important;
                    flex-direction: column !important;
                    gap: 8px;
                }
                .cart-footer-buttons .btn {
                    width: 100% !important;
                    margin-right: 0 !important;
                }
            }
        </style>
        <div class="modal-footer justify-content-between flex-column flex-sm-row align-items-center" style="position: sticky; bottom: 0; background: #ffffff; z-index: 10; border-top: 1px solid #e2e8f0; box-shadow: 0 -4px 12px rgba(0,0,0,0.05);">
            <div class="h5 mb-2 mb-sm-0 text-center text-sm-left">
                <strong>Subtotal:</strong>
                ৳<span class="text-primary all-subtotal" id="cart-subtotal"> {{ $subtotal }}</span>
            </div>
            <div class="cart-footer-buttons d-flex align-items-center">
                <a href="{{ url('/') }}" class="btn btn-light mr-2">Continue Shopping</a>
                <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        </div>
    @endif
@endsection

