@extends('layouts.adminLays.master')

@section('title')
    EDIT ORDER #LM-{{ $order->id }}
@endsection

@section('content')
    @php
        $selectedDistrict = $districts->first(function($d) use ($order) {
            return strcasecmp(trim($d->name), trim($order->district)) === 0;
        });
        $selectedThana = $thanas->first(function($t) use ($order) {
            return strcasecmp(trim($t->name), trim($order->thana)) === 0;
        });
    @endphp

    <!-- Custom Premium Edit Styles -->
    <style>
        .edit-order-container {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            padding-bottom: 3rem;
        }

        .premium-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.08), 0 2px 8px -1px rgba(148, 163, 184, 0.04);
            margin-bottom: 1.75rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .premium-card .card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            font-size: 1.05rem;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .premium-card .card-body {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 0.6rem 1rem !important;
            height: auto !important;
            font-size: 0.9rem !important;
            color: #1f2937 !important;
            transition: all 0.2s ease !important;
            background-color: #ffffff !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15) !important;
            outline: none !important;
        }

        /* Select2 Style Custom Overrides */
        .select2-container--default .select2-selection--single {
            border: 1px solid #cbd5e1 !important;
            border-radius: 10px !important;
            height: 42px !important;
            padding: 6px 12px !important;
            background-color: #ffffff !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
            color: #1f2937 !important;
            font-size: 0.9rem !important;
            padding-left: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 10px !important;
        }
        .select2-dropdown {
            border: 1px solid #cbd5e1 !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.08) !important;
            overflow: hidden !important;
        }

        /* Product Table styling */
        .edit-table {
            width: 100%;
            margin-bottom: 0;
        }
        .edit-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .edit-table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Autocomplete Search styling */
        .search-results-dropdown {
            position: absolute;
            z-index: 1050;
            width: 95%;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            max-height: 280px;
            overflow-y: auto;
            display: none;
            margin-top: 2px;
        }

        .search-suggestion-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: background 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }
        .search-suggestion-item:last-child {
            border-bottom: none;
        }
        .search-suggestion-item:hover {
            background-color: #f8fafc;
        }

        .search-suggestion-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .search-suggestion-title {
            font-weight: 600;
            font-size: 0.85rem;
            color: #0f172a;
        }

        .search-suggestion-price {
            font-weight: 700;
            color: #4f46e5;
            font-size: 0.8rem;
        }

        .calculation-card {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.4rem 0;
            font-size: 0.9rem;
        }
        .calc-row.total-row {
            border-top: 1px solid #e2e8f0;
            padding-top: 0.75rem;
            margin-top: 0.5rem;
            font-weight: 700;
            font-size: 1.05rem;
            color: #0f172a;
        }
        .calc-row.due-row {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 0.6rem 0.8rem;
            margin-top: 0.5rem;
            font-weight: 800;
            font-size: 1.1rem;
            color: #b91c1c;
        }

        .btn-remove-row {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #fee2e2;
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-remove-row:hover {
            background: #fca5a5;
            color: #b91c1c;
            transform: scale(1.05);
        }

        /* Redesigned Button Actions styles */
        .action-buttons-container {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            padding: 1.25rem 2rem;
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.08);
            display: flex;
            justify-content: flex-end;
            gap: 1.25rem;
            margin-top: 2rem;
        }

        .action-btn-premium {
            padding: 0.8rem 2.2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            border: none;
            cursor: pointer;
            text-decoration: none !important;
            letter-spacing: 0.3px;
        }

        /* Cancel Button: Modern Border/Glassmorphism Style */
        .action-btn-premium.btn-back {
            background: #f8fafc;
            color: #64748b !important;
            border: 1.5px solid #cbd5e1;
        }
        .action-btn-premium.btn-back:hover {
            background: #fee2e2;
            color: #dc2626 !important;
            border-color: #fca5a5;
            transform: translateY(-2.5px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.08);
        }
        .action-btn-premium.btn-back:active {
            transform: translateY(-1px);
        }

        /* Save Updates Button: Premium Glowing Gradient Style */
        .action-btn-premium.btn-edit {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
            border: 1.5px solid transparent;
        }
        .action-btn-premium.btn-edit:hover {
            background: linear-gradient(135deg, #4338ca 0%, #2563eb 100%);
            transform: translateY(-2.5px);
            box-shadow: 0 8px 22px rgba(79, 70, 229, 0.45);
        }
        .action-btn-premium.btn-edit:active {
            transform: translateY(-1px);
        }
    </style>

    <div class="edit-order-container">
        <form action="{{ route('admin.order-update') }}" method="POST" id="editOrderForm">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" id="total-amount-input" name="total_amount" value="{{ $order->total_amount }}">
            <input type="hidden" id="coupon-discount-hidden" name="coupon_discount" value="{{ $order->coupon_discount }}">

            <div class="row">
                <!-- Customer Details Column (5 Cols) -->
                <div class="col-lg-5">
                    <div class="premium-card">
                        <div class="card-header">
                            <span><i class="fa-solid fa-user mr-2 text-primary"></i>Customer Information</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Customer Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Customer Name" value="{{ $order->name }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Customer Phone</label>
                                <input type="tel" class="form-control" name="phone" placeholder="Customer Phone" value="{{ $order->phone }}" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label">Customer District</label>
                                    <select id="SelectDistrict" class="form-select dropdown-groups" name="district_id">
                                        <option value="" disabled selected>Select District</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}" @if($selectedDistrict && $selectedDistrict->id == $district->id) selected @endif>{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Customer Upazila</label>
                                    <select id="SelectUpazila" class="form-select dropdown-groups" name="upazila_id">
                                        <option value="" disabled selected>Select Upazila</option>
                                        @if($selectedDistrict)
                                            @foreach($thanas->where('district_id', $selectedDistrict->id) as $thana)
                                                <option value="{{ $thana->id }}" @if($selectedThana && $selectedThana->id == $thana->id) selected @endif>{{ $thana->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Customer Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Customer Address" value="{{ $order->address }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Order Status</label>
                                <select class="form-select text-uppercase" name="delivery_status">
                                    <option value="new" @if($order->delivery_status == 'new') selected @endif>Hold / New</option>
                                    <option value="pending" @if($order->delivery_status == 'pending') selected @endif>Pending</option>
                                    <option value="approved" @if($order->delivery_status == 'approved') selected @endif>Approved</option>
                                    <option value="packaging" @if($order->delivery_status == 'packaging') selected @endif>Packaging</option>
                                    <option value="incourier" @if($order->delivery_status == 'incourier' || $order->delivery_status == 'in_courier') selected @endif>In Courier</option>
                                    <option value="delivered" @if($order->delivery_status == 'delivered') selected @endif>Delivered</option>
                                    <option value="cancel" @if($order->delivery_status == 'cancel' || $order->delivery_status == 'cancelled') selected @endif>Cancel</option>
                                    <option value="returned" @if($order->delivery_status == 'returned') selected @endif>Returned</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Staff Comment</label>
                                <textarea class="form-control" name="comments" rows="3" placeholder="Add comments here...">{{ $order->comments }}</textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Customer Note</label>
                                <textarea class="form-control" name="note" rows="2" placeholder="Add customer note...">{{ $order->note }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Information Column (7 Cols) -->
                <div class="col-lg-7">
                    <div class="premium-card">
                        <div class="card-header">
                            <span><i class="fa-solid fa-cart-shopping mr-2 text-primary"></i>Product Information</span>
                        </div>
                        <div class="card-body">
                            <!-- Catalog Autocomplete Search -->
                            <div class="mb-4" style="position: relative;">
                                <label class="form-label">Add Products to Order</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="search" id="productSearch" class="form-control" placeholder="Search catalog by name or code...">
                                </div>
                                <div id="searchResults" class="search-results-dropdown"></div>
                            </div>

                            <!-- Product Items Table -->
                            <div class="table-responsive mb-4">
                                <table class="table edit-table" id="products-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">SL</th>
                                            <th style="width: 25%;">Product</th>
                                            <th style="width: 15%;">Attributes</th>
                                            <th style="width: 15%;">Color</th>
                                            <th style="width: 10%;">Qty</th>
                                            <th style="width: 13%;">Price</th>
                                            <th style="width: 12%;">Total</th>
                                            <th class="text-right" style="width: 5%;">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-rows-container">
                                        @foreach ($order->orderDetails as $detail)
                                            @if ($detail->orderProduct)
                                                @php
                                                    $imageSrc = asset('frontend/assets/img/placeholder.jpg');
                                                    if ($detail->orderProduct->firstImage) {
                                                        $imgName = $detail->orderProduct->firstImage->image;
                                                        if (file_exists(public_path('adminDash/uploads/products/' . $imgName))) {
                                                            $imageSrc = asset('adminDash/uploads/products/' . $imgName);
                                                        } elseif (file_exists(public_path('adminDash/images/product/' . $imgName))) {
                                                            $imageSrc = asset('adminDash/images/product/' . $imgName);
                                                        } else {
                                                            $imageSrc = asset('adminDash/uploads/products/' . $imgName);
                                                        }
                                                    }
                                                @endphp
                                                <tr class="product-row" data-product-id="{{ $detail->product_id }}">
                                                    <td class="sl-number font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center" style="gap: 8px;">
                                                            <img style="height: 40px; width: 40px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;" src="{{ $imageSrc }}" alt="Product Image">
                                                            <div style="line-height: 1.3;">
                                                                <span class="font-weight-bold text-dark d-block text-wrap" style="font-size: 0.85rem; max-width: 150px;">{{ $detail->orderProduct->title }}</span>
                                                                <small class="text-muted" style="font-size: 0.7rem;">Code: {{ $detail->orderProduct->code }}</small>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="product_ids[]" value="{{ $detail->product_id }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-select form-select-sm spec-size" name="sizes[]" style="padding: 0.3rem 0.5rem !important; border-radius: 6px !important; width: 100%;">
                                                            <option value="N/A" @if(!$detail->product_attribute || $detail->product_attribute == 'N/A') selected @endif>N/A</option>
                                                            @php $hasSelectedAttr = false; @endphp
                                                            @if($detail->orderProduct && $detail->orderProduct->productAttributes)
                                                                @foreach($detail->orderProduct->productAttributes as $attr)
                                                                    @php
                                                                        $attrName = ($attr->attribute) ? $attr->attribute->name : 'Attribute';
                                                                        $values = explode(',', $attr->attribute_value);
                                                                    @endphp
                                                                    @foreach($values as $val)
                                                                        @php
                                                                            $val = trim($val);
                                                                            $isSelectedAttr = strcasecmp(trim($detail->product_attribute), $val) === 0;
                                                                            if ($isSelectedAttr) $hasSelectedAttr = true;
                                                                        @endphp
                                                                        <option value="{{ $val }}" @if($isSelectedAttr) selected @endif>{{ $attrName }} : {{ $val }}</option>
                                                                    @endforeach
                                                                @endforeach
                                                            @endif
                                                            @if(!$hasSelectedAttr && $detail->product_attribute && $detail->product_attribute != 'N/A')
                                                                <option value="{{ $detail->product_attribute }}" selected>Attribute : {{ $detail->product_attribute }}</option>
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-select form-select-sm spec-color" name="colors[]" style="padding: 0.3rem 0.5rem !important; border-radius: 6px !important; width: 100%;">
                                                            <option value="N/A" @if(!$detail->product_colour || $detail->product_colour == 'N/A') selected @endif>N/A</option>
                                                            @php $hasSelectedColor = false; @endphp
                                                            @if($detail->orderProduct && $detail->orderProduct->productColors)
                                                                @foreach($detail->orderProduct->productColors as $pc)
                                                                    @if($pc->color)
                                                                        @php
                                                                            $isSelectedColor = strcasecmp(trim($detail->product_colour), trim($pc->color->color_name)) === 0;
                                                                            if ($isSelectedColor) $hasSelectedColor = true;
                                                                        @endphp
                                                                        <option value="{{ $pc->color->color_name }}" @if($isSelectedColor) selected @endif>{{ $pc->color->color_name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            @if(!$hasSelectedColor && $detail->product_colour && $detail->product_colour != 'N/A')
                                                                <option value="{{ $detail->product_colour }}" selected>{{ $detail->product_colour }}</option>
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="form-control form-control-sm qty-input text-center" type="number" name="quantities[]" value="{{ $detail->product_qty }}" min="1" required style="padding: 0.3rem 0.5rem !important; border-radius: 6px !important; min-width: 60px;">
                                                    </td>
                                                    <td>
                                                        <input class="form-control form-control-sm price-input text-right" type="number" name="prices[]" value="{{ $detail->unit_price }}" step="0.01" required style="padding: 0.3rem 0.5rem !important; border-radius: 6px !important; min-width: 85px;">
                                                        <!-- Hidden total for row calculation -->
                                                        <span class="row-total d-none">{{ $detail->total_price }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="row-total-display font-weight-bold">{{ number_format($detail->total_price, 2) }}</span> ৳
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn-remove-row" title="Remove Product">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Financial Calculations Box -->
                            <div class="calculation-card">
                                <div class="calc-row">
                                    <span class="text-muted">Sub Total</span>
                                    <span class="font-weight-bold" style="font-size: 1.05rem;"><span id="subtotal-display">0.00</span> ৳</span>
                                </div>
                                <div class="calc-row">
                                    <span class="text-muted">Coupon Discount</span>
                                    <span class="font-weight-bold text-danger">- {{ number_format($order->coupon_discount, 2) }} ৳</span>
                                </div>
                                <div class="calc-row">
                                    <span class="text-muted">Admin Discount</span>
                                    <div class="input-group input-group-sm w-35">
                                        <input type="number" class="form-control text-right" id="discount-input" name="admin_discount" value="{{ $order->admin_discount ?? 0 }}" min="0" step="0.01">
                                        <span class="input-group-text bg-light">৳</span>
                                    </div>
                                </div>
                                <div class="calc-row">
                                    <span class="text-muted">Shipping / Delivery Cost</span>
                                    <div class="input-group input-group-sm w-35">
                                        <input type="number" class="form-control text-right" id="shipping-input" name="delivery_charge" value="{{ $order->delivery_charge ?? 0 }}" min="0" step="0.01">
                                        <span class="input-group-text bg-light">৳</span>
                                    </div>
                                </div>
                                <div class="calc-row total-row">
                                    <span>Grand Total</span>
                                    <span class="text-primary" style="font-size: 1.15rem;"><span id="grand-total-display">0.00</span> ৳</span>
                                </div>
                                <div class="calc-row">
                                    <span class="text-muted">Paid Amount</span>
                                    <div class="input-group input-group-sm w-35">
                                        <input type="number" class="form-control text-right" id="paid-input" name="paid_amount" value="{{ $order->paid_amount ?? 0 }}" min="0" step="0.01">
                                        <span class="input-group-text bg-light">৳</span>
                                    </div>
                                </div>
                                <div class="calc-row due-row">
                                    <span>Due Amount (COD)</span>
                                    <span><span id="due-amount-display">0.00</span> ৳</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Redesigned Form Actions Footer -->
            <div class="action-buttons-container">
                <a href="{{ route('admin.order-show', $order->id) }}" class="action-btn-premium btn-back">
                    <i class="fa-solid fa-xmark mr-1"></i> Cancel
                </a>
                <button type="submit" class="action-btn-premium btn-edit">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Save Updates
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <!-- Select2 district and upazila loader script -->
    <script>
        $(document).ready(function() {
            // Initialize Select2 on both dropdowns
            $('#SelectDistrict, #SelectUpazila').select2({
                placeholder: "Select option",
                width: '100%'
            });

            // District change listener
            $('#SelectDistrict').on('change', function() {
                var districtId = $(this).val();
                var upazilaSelect = $('#SelectUpazila');

                if(districtId) {
                    $.ajax({
                        url: "{{ url('/get-upazilas') }}/" + districtId,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            upazilaSelect.empty();
                            upazilaSelect.append('<option value="" disabled selected>Select Upazila</option>');

                            $.each(data, function(key, value) {
                                var newOption = new Option(value.name, value.id, false, false);
                                upazilaSelect.append(newOption);
                            });

                            // Trigger change to update Select2
                            upazilaSelect.trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching upazilas: " + error);
                        }
                    });
                }
            });
        });
    </script>

    <!-- Calculation Loop Script -->
    <script>
        function calculateTotals() {
            let subtotal = 0;
            $('.product-row').each(function() {
                let row = $(this);
                let qty = parseFloat(row.find('.qty-input').val()) || 0;
                let price = parseFloat(row.find('.price-input').val()) || 0;
                let total = qty * price;
                row.find('.row-total').text(total.toFixed(2));
                row.find('.row-total-display').text(total.toFixed(2));
                subtotal += total;
            });
            
            $('#subtotal-display').text(subtotal.toFixed(2));
            $('#total-amount-input').val(subtotal.toFixed(2));
            
            let discount = parseFloat($('#discount-input').val()) || 0;
            let couponDiscount = parseFloat($('#coupon-discount-hidden').val()) || 0;
            let shipping = parseFloat($('#shipping-input').val()) || 0;
            let paid = parseFloat($('#paid-input').val()) || 0;
            
            let grandTotal = subtotal - discount - couponDiscount + shipping;
            if (grandTotal < 0) grandTotal = 0;
            
            let dueAmount = grandTotal - paid;
            if (dueAmount < 0) dueAmount = 0;
            
            $('#grand-total-display').text(grandTotal.toFixed(2));
            $('#due-amount-display').text(dueAmount.toFixed(2));
        }

        // Recalculate row SL indexes
        function reindexRows() {
            $('.product-row').each(function(index) {
                $(this).find('.sl-number').text(index + 1);
            });
        }

        $(document).ready(function() {
            // Trigger initial calculation loop
            calculateTotals();

            // Bind change listeners to trigger calculations
            $(document).on('input keyup change', '.qty-input, .price-input, #discount-input, #shipping-input, #paid-input', function() {
                calculateTotals();
            });

            // Mutual exclusivity for attribute (size) and color dropdowns
            $(document).on('change', '.spec-size', function() {
                if ($(this).val() !== 'N/A') {
                    let colorSelect = $(this).closest('tr').find('.spec-color');
                    if (colorSelect.val() !== 'N/A') {
                        colorSelect.val('N/A');
                    }
                }
            });

            $(document).on('change', '.spec-color', function() {
                if ($(this).val() !== 'N/A') {
                    let sizeSelect = $(this).closest('tr').find('.spec-size');
                    if (sizeSelect.val() !== 'N/A') {
                        sizeSelect.val('N/A');
                    }
                }
            });

            // Remove product row click handler
            $(document).on('click', '.btn-remove-row', function(e) {
                e.preventDefault();
                let row = $(this).closest('tr');
                Swal.fire({
                    title: 'Remove Product?',
                    text: 'Are you sure you want to remove this item from the order?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Remove!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                        reindexRows();
                        calculateTotals();
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2000,
                            icon: 'success',
                            title: 'Product removed'
                        });
                    }
                });
            });
        });
    </script>

    <!-- Product Autocomplete and catalog loader -->
    <script>
        $(document).ready(function() {
            let searchTimer;
            const $searchField = $('#productSearch');
            const $resultsDropdown = $('#searchResults');

            $searchField.on('keyup input', function() {
                clearTimeout(searchTimer);
                let query = $(this).val().trim();

                if (query.length < 2) {
                    $resultsDropdown.hide().empty();
                    return;
                }

                searchTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('admin.product-search') }}",
                        type: "GET",
                        data: { term: query },
                        success: function(res) {
                            $resultsDropdown.empty();
                            if (res.length > 0) {
                                // Initialize or clear global catalog storage
                                window.productCatalog = window.productCatalog || {};

                                $.each(res, function(index, product) {
                                    window.productCatalog[product.id] = product;
                                    
                                    let price = product.new_price ?? 0;
                                    let imgPath = product.first_image_url ?? '{{ asset("favicon.png") }}';
                                    
                                    $resultsDropdown.append(
                                        `<div class="search-suggestion-item add-catalog-product"
                                            data-id="${product.id}"
                                            data-name="${product.title}"
                                            data-code="${product.code ?? 'N/A'}"
                                            data-price="${price}"
                                            data-image="${imgPath}">
                                            <img src="${imgPath}" onerror="this.onerror=null; this.src='{{ asset("favicon.png") }}';">
                                            <div class="flex-grow-1">
                                                <div class="search-suggestion-title">${product.title}</div>
                                                <small class="text-muted">Code: ${product.code ?? 'N/A'}</small>
                                            </div>
                                            <div class="search-suggestion-price">${price} ৳</div>
                                        </div>`
                                    );
                                });
                                $resultsDropdown.show();
                            } else {
                                $resultsDropdown.append(
                                    `<div class="p-3 text-center text-muted" style="font-size: 0.85rem;">No products found in catalog.</div>`
                                );
                                $resultsDropdown.show();
                            }
                        }
                    });
                }, 200);
            });
 
            // Close suggestions dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#productSearch, #searchResults').length) {
                    $resultsDropdown.hide();
                }
            });
 
            // Autocomplete click handler to append rows to order details
            $(document).on('click', '.add-catalog-product', function() {
                let id = $(this).attr('data-id');
                let name = $(this).attr('data-name');
                let code = $(this).attr('data-code');
                let price = parseFloat($(this).attr('data-price')) || 0;
                let image = $(this).attr('data-image');
 
                let product = window.productCatalog ? window.productCatalog[id] : null;

                // Check if product is already in the table
                let alreadyExists = false;
                $('.product-row').each(function() {
                    if ($(this).attr('data-product-id') == id) {
                        alreadyExists = true;
                        // Increment quantity of existing row
                        let qtyInput = $(this).find('.qty-input');
                        qtyInput.val(parseInt(qtyInput.val()) + 1);
                        calculateTotals();
                    }
                });
 
                if (!alreadyExists) {
                    let nextSl = $('.product-row').length + 1;

                    // Build size select option list
                    let sizesSelect = `<select class="form-select form-select-sm spec-size" name="sizes[]" style="padding: 0.3rem 0.5rem !important; border-radius: 6px !important; width: 100%;">
                        <option value="N/A" selected>N/A</option>`;
                    if (product && product.product_attributes && product.product_attributes.length > 0) {
                        product.product_attributes.forEach(function(attr) {
                            let attrName = (attr.attribute && attr.attribute.name) ? attr.attribute.name : 'Attribute';
                            if (attr.attribute_value) {
                                let values = attr.attribute_value.split(',');
                                values.forEach(function(val) {
                                    let cleanVal = val.trim();
                                    sizesSelect += `<option value="${cleanVal}">${attrName} : ${cleanVal}</option>`;
                                });
                            }
                        });
                    }
                    sizesSelect += `</select>`;

                    // Build color select option list
                    let colorsSelect = `<select class="form-select form-select-sm spec-color" name="colors[]" style="padding: 0.3rem 0.5rem !important; border-radius: 6px !important; width: 100%;">
                        <option value="N/A" selected>N/A</option>`;
                    if (product && product.product_colors && product.product_colors.length > 0) {
                        product.product_colors.forEach(function(pc) {
                            if (pc.color && pc.color.color_name) {
                                colorsSelect += `<option value="${pc.color.color_name}">${pc.color.color_name}</option>`;
                            }
                        });
                    }
                    colorsSelect += `</select>`;

                    let newRow = `
                    <tr class="product-row" data-product-id="${id}">
                        <td class="sl-number font-weight-bold text-muted">${nextSl}</td>
                        <td>
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <img style="height: 40px; width: 40px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;" src="${image}" alt="Product Image" onerror="this.onerror=null; this.src='{{ asset("favicon.png") }}';">
                                <div style="line-height: 1.3;">
                                    <span class="font-weight-bold text-dark d-block text-wrap" style="font-size: 0.85rem; max-width: 150px;">${name}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">Code: ${code}</small>
                                </div>
                            </div>
                            <input type="hidden" name="product_ids[]" value="${id}">
                        </td>
                        <td>
                            ${sizesSelect}
                        </td>
                        <td>
                            ${colorsSelect}
                        </td>
                        <td>
                            <input class="form-control form-control-sm qty-input text-center" type="number" name="quantities[]" value="1" min="1" required style="padding: 0.3rem 0.5rem !important; border-radius: 6px !important; min-width: 60px;">
                        </td>
                        <td>
                            <input class="form-control form-control-sm price-input text-right" type="number" name="prices[]" value="${price}" step="0.01" required style="padding: 0.3rem 0.5rem !important; border-radius: 6px !important; min-width: 85px;">
                            <span class="row-total d-none">${price}</span>
                        </td>
                        <td>
                            <span class="row-total-display font-weight-bold">${price.toFixed(2)}</span> ৳
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn-remove-row" title="Remove Product">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;

                    $('#product-rows-container').append(newRow);
                    calculateTotals();
                    
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                        icon: 'success',
                        title: 'Product added to list'
                    });
                }

                $resultsDropdown.hide().empty();
                $searchField.val('');
            });
        });
    </script>
@endsection
