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

    <div class="modal-body">
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
                    {{-- <tbody>
                        @foreach ($cart as $key => $item)
                            @php
                                $cleanPrice = str_replace(',', '', $item['price']);
                                $line_total = (float) $cleanPrice * (int) $item['quantity'];
                                $subtotal += $line_total;
                            @endphp
                            <tr class="border-bottom">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($item['image']) }}" class="size-60px mr-2 rounded">
                                        <div>
                                            <span class="fs-14 fw-600 d-block">{{ $item['name'] }}</span>
                                            <small class="text-info font-weight-bold">code:
                                                {{ $item['code'] ?? 'N/A' }}{{ $item['attribute'] || $item['color']  ? '|' : '' }}
                                            </small>
                                            <small class="text-info font-weight-bold">
                                                {{ $item['attribute'] ?? '' }}
                                                {{ $item['attribute'] && $item['color'] ? '|' : '' }}
                                                {{ $item['color'] ?? '' }}
                                            </small>
                                            <small class="text-info font-weight-bold">| Price: ৳ {{ $item['price'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="input-group aiz-plus-minus" style="width: 100px;">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="changeQuantity('{{ $key }}', -1)">
                                                    <i class="las la-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" style="height: 20px"
                                                class="form-control form-control-sm text-center cart-qty-{{ $key }}" data-max="{{ $item['stock'] }}"
                                                value="{{ $item['quantity'] }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="changeQuantity('{{ $key }}', 1)">
                                                    <i class="las la-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-600 text-primary">৳<span class="line-total-{{ $key }}">
                                        {{ $line_total }}</span></td>
                                <td class="text-right">
                                    <button onclick="removeguest('{{ $key }}')"
                                        class="btn btn-soft-danger btn-circle btn-sm">
                                        <i class="las la-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody> --}}
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
                                    ? $rawItem->product->thumbnail_img ?? $rawItem->image
                                    : $rawItem['image'];
                                $price = $isDb ? $rawItem->product->new_price ?? $rawItem->price : $rawItem['price'];
                                $qty = $isDb ? $rawItem->quantity : $rawItem['quantity'];
                                $stock = $isDb ? $rawItem->product->stock ?? 10 : $rawItem['stock'] ?? 10;
                                $code = $isDb ? $rawItem->product->code ?? 'N/A' : $rawItem['code'] ?? 'N/A';

                                $cleanPrice = (float) str_replace(',', '', $price);
                                $line_total = $cleanPrice * (int) $qty;
                                $subtotal += $line_total;
                            @endphp

                            <tr class="border-bottom cart-row-{{ $id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($image) }}" class="size-60px mr-2 rounded">
                                        <div>
                                            <span class="fs-14 fw-600 d-block">{{ $name }}</span>
                                            <small class="text-info font-weight-bold">Code: {{ $code }}</small>
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
        <div class="modal-footer justify-content-between">
            <div class="h5 mb-0">
                <strong>Subtotal:</strong>
                ৳<span class="text-primary all-subtotal" id="cart-subtotal"> {{ $subtotal }}</span>
            </div>
            <div>
                <a href="{{ url('/') }}" class="btn btn-light">Continue Shopping</a>
                <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        </div>
    @endif
@endsection

