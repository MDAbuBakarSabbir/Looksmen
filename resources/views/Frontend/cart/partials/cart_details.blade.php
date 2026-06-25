{{-- <div class="model-content" id="cart-modal">
    <div class="modal-header">
        <h6 class="modal-title fw-600">Order confirmation</h6>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
    </div>

    <div class="modal-body">
        @if (count($cart) > 0)
            <ul class="list-group list-group-flush">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Attribute</th>
                            <th scope="col">Color</th>
                            <th scope="col">Price</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $key => $item)
                            <tr>
                                <td>
                                    <div class="col-md-5 d-flex align-items-center">
                                        <img src="{{ asset($item['image']) }}" class="size-60px mr-2">
                                        <span class="fs-14">{{ $item['name'] }}</span>
                                    </div>
                                </td>
                                <td scope="row"> M</td>
                                <td>Red</td>
                                <td>৳ {{ $item['price'] }}</td>
                                <td>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="input-group aiz-plus-minus" style="width: 110px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                                        onclick="changeQuantity('{{ $key }}', -1)">
                                                        <i class="las la-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text"
                                                    class="form-control form-control-sm text-center cart-qty-{{ $key }}"
                                                    value="{{ $item['quantity'] }}" readonly
                                                    style="border-top: 1px solid #ced4da; border-bottom: 1px solid #ced4da;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                                        onclick="changeQuantity('{{ $key }}', 1)">
                                                        <i class="las la-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>৳ {{ $item['price'] }}</td>
                                <td>
                                    <button
                                        onclick="removeguest('{{ $key }}')"class="btn btn-soft-danger btn-circle btn-sm"><i
                                            class="las la-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </ul>
        @else
            <div class="text-center p-4">
                <i class="las la-frown la-3x opacity-60"></i>
                <h6>Your cart is empty</h6>
            </div>
        @endif
    </div>
    <div class="modal-footer">
        <span><strong>Subtotal : </strong>৳ 100</span>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Continue Shopping</button>
        @if (count($cart) > 0)
            <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
        @endif
    </div>
</div> --}}

@php
    $subtotal = 0;
@endphp

<div class="modal-header">
    <h6 class="modal-title fw-600">Order confirmation</h6>
    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
</div>

<div class="modal-body">
    @if (!empty($cart) && count($cart) > 0)
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col" class="text-center">Qty</th>
                        <th scope="col">Total</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                </thead>
                {{-- <tbody>
                    @foreach ($cart as $key => $item)
                        @php
                            $cleanPrice = str_replace(',', '', $item['price']);
                            $line_total = (float)$cleanPrice * (int)$item['quantity'];
                            $subtotal += $line_total;
                        @endphp
                        <tr class="border-bottom cart-row-{{ $key }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset($item['image']) }}" class="size-60px mr-2 rounded">
                                    <div>
                                        <span class="fs-14 fw-600 d-block">{{ $item['name'] }}</span>
                                        <small class="text-info font-weight-bold">code: {{ $item['code'] ?? 'N/A' }}{{ $item['attribute'] || $item['color'] ? '|' : '' }} </small>
                                        <small class="text-info font-weight-bold">
                                            {{ $item['attribute'] ?? '' }}
                                            {{ $item['attribute'] && $item['color'] ? '|' : '' }}
                                            {{ $item['color'] ?? '' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>৳ {{ $item['price'] }}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="input-group aiz-plus-minus" style="width: 100px;">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="changeQuantity('{{ $key }}', -1)">
                                                <i class="las la-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text"
                                            class="form-control form-control-sm text-center cart-qty-{{ $key }}" data-max="{{ $item['stock'] }}"
                                            value="{{ $item['quantity'] }}" readonly >
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
                            $image = $isDb ? $rawItem->product->thumbnail_img ?? $rawItem->image : $rawItem['image'];
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
        <div class="mb-0">
            <h5><strong>Subtotal:</strong>
                ৳<span class="all-subtotal"> {{ $subtotal }}</span><br></h5>
            <p>Shipping charge And Discount Calculated AT Checkout</p>
        </div>
        <div>
            <button type="button" class="btn btn-light" data-dismiss="modal">Continue Shopping</button>
            <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
        </div>
    </div>
@endif
