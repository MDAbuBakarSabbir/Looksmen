@php
    $subtotal = 0;
@endphp

<div class="modal-header">
    <h6 class="modal-title fw-600">Order confirmation</h6>
    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
</div>

<div class="modal-body" style="max-height: calc(75vh - 120px); overflow-y: auto;">
    @if (!empty($cart) && count($cart) > 0)
        @if(!empty($freeDelivery['has_offer']))
            @php
                $isFree = !empty($freeDelivery['is_free']);
                $percent = $freeDelivery['progress_percent'] ?? 0;
                $current = $freeDelivery['current_qty'] ?? 0;
                $threshold = $freeDelivery['threshold'] ?? 0;
            @endphp
            <div class="free-delivery-progress-container mb-3 p-3" style="background: {{ $isFree ? 'linear-gradient(135deg, #ecfdf5, #f0fdf4)' : '#f8fafc' }}; border: 1px solid {{ $isFree ? '#10b981' : '#e2e8f0' }}; border-radius: 12px;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle mr-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 50%; background: {{ $isFree ? '#d1fae5' : '#e0e7ff' }}; color: {{ $isFree ? '#059669' : '#4f46e5' }};">
                            <i class="las {{ $isFree ? 'la-check-circle' : 'la-truck' }} fs-16"></i>
                        </div>
                        <span class="fs-13 fw-600 {{ $isFree ? 'text-success' : 'text-dark' }}" id="modal_fd_progress_title">
                            {{ $freeDelivery['progress_message'] ?? '' }}
                        </span>
                    </div>
                    <span class="badge {{ $isFree ? 'badge-success' : 'badge-primary' }} px-2 py-1" style="width: auto !important; height: auto !important; min-width: auto !important; font-size: 11px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;">
                        <span id="modal_fd_progress_qty">{{ $current }}/{{ $threshold }}</span>
                    </span>
                </div>

                <div class="progress" style="height: 8px; border-radius: 10px; background-color: #e2e8f0; overflow: hidden;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         id="modal_fd_progress_bar"
                         role="progressbar" 
                         style="width: {{ $percent }}%; border-radius: 10px; transition: width 0.4s ease; background: {{ $isFree ? 'linear-gradient(90deg, #10b981, #059669)' : 'linear-gradient(90deg, #6366f1, #4f46e5)' }};" 
                         aria-valuenow="{{ $percent }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        @endif
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
                            $name = $isDb ? ($rawItem->product?->title ?? $rawItem->name ?? 'N/A') : $rawItem['name'];
                            $image = $isDb ? ($rawItem->product?->firstImage?->image ?? $rawItem->image ?? '') : $rawItem['image'];
                            $price = $isDb ? ($rawItem->product?->new_price ?? $rawItem->price ?? 0) : $rawItem['price'];
                            $qty = $isDb ? $rawItem->quantity : $rawItem['quantity'];
                            $stock = $isDb ? ($rawItem->product?->stock ?? 10) : ($rawItem['stock'] ?? 10);
                            $code = $isDb ? ($rawItem->product?->code ?? 'N/A') : ($rawItem['code'] ?? 'N/A');

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
                                        <small class="text-info font-weight-bold">Code: {{ $code }}</small><br>
                                        <small class="text-info font-weight-bold">Price: ৳ {{ $price }}</small>
                                    </div>
                                </div>
                            </td>
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
            
            /* Responsive Cart Table Fixes for Mobile */
            .table td, .table th {
                padding: 0.3rem 0.15rem !important;
                vertical-align: middle !important;
            }
            .table th {
                font-size: 12px !important;
            }
            .size-60px {
                width: 45px !important;
                height: 45px !important;
                margin-right: 0.3rem !important;
            }
            .fs-14 {
                font-size: 12px !important;
                max-width: 110px !important;
                white-space: normal !important;
                line-height: 1.2;
            }
            .text-info {
                font-size: 11px !important;
            }
            .aiz-plus-minus {
                width: 80px !important;
            }
            .aiz-plus-minus .btn {
                padding: 0.1rem 0.3rem !important;
                height: 28px !important;
            }
            .aiz-plus-minus .form-control {
                padding: 0.1rem !important;
                height: 28px !important;
                font-size: 12px !important;
            }
            .btn-circle.btn-sm {
                width: 28px !important;
                height: 28px !important;
                padding: 0 !important;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto;
            }
            .fw-600.text-primary {
                font-size: 12px !important;
            }
        }
    </style>
    <div class="modal-footer justify-content-between flex-column flex-sm-row align-items-center" style="position: sticky; bottom: 0; background: #ffffff; z-index: 10; border-top: 1px solid #e2e8f0; box-shadow: 0 -4px 12px rgba(0,0,0,0.05);">
        <div class="mb-2 mb-sm-0 text-center text-sm-left">
            <h5 class="mb-1"><strong>Subtotal:</strong>
                ৳<span class="all-subtotal"> {{ $subtotal }}</span></h5>
            <p class="mb-0 fs-12 text-muted">Shipping charge And Discount Calculated AT Checkout</p>
        </div>
        <div class="cart-footer-buttons d-flex align-items-center">
            <button type="button" class="btn btn-light mr-2" data-dismiss="modal">Continue Shopping</button>
            <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
        </div>
    </div>
@endif
