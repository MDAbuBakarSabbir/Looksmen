@extends('layouts.adminLays.master')

@section('title')
    CREATE NEW ORDER
@endsection

@section('content')
    <!-- Custom Premium Create Styles -->
    <style>
        .create-order-container {
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

        /* Submit Button: Premium Glowing Gradient Style */
        .action-btn-premium.btn-submit {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
            border: 1.5px solid transparent;
        }
        .action-btn-premium.btn-submit:hover {
            background: linear-gradient(135deg, #4338ca 0%, #2563eb 100%);
            transform: translateY(-2.5px);
            box-shadow: 0 8px 22px rgba(79, 70, 229, 0.45);
        }
        .action-btn-premium.btn-submit:active {
            transform: translateY(-1px);
        }
    </style>

    <div class="create-order-container">
        <form action="{{ route('admin.order-store') }}" method="POST" id="createOrderForm">
            @csrf
            <input type="hidden" name="incomplete_id" value="{{ $incompleteOrder ? $incompleteOrder->id : '' }}">
            <input type="hidden" id="total-amount-input" name="total_amount" value="0">
            <input type="hidden" id="coupon-discount-hidden" name="coupon_discount" value="0">

            <div class="row">
                <!-- Customer Details Column (5 Cols) -->
                <div class="col-lg-5">
                    <div class="premium-card">
                        <div class="card-header">
                            <span><i class="fa-solid fa-user mr-2 text-primary"></i>Customer Information</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customerName" name="name" value="{{ old('name', $incompleteOrder ? $incompleteOrder->name : '') }}" placeholder="Enter name" required>
                            </div>

                            <div class="mb-3">
                                <label for="customerPhone" class="form-label">Customer Number</label>
                                <input type="tel" class="form-control" id="customerPhone" name="phone" value="{{ old('phone', $incompleteOrder ? $incompleteOrder->phone : '') }}" placeholder="Enter 11 digit number" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="district" class="form-label">Customer District</label>
                                    <select class="form-select dropdown-groups" id="district" name="district_id" style="width: 100%;">
                                        <option value="" selected disabled>Select District</option>
                                        @foreach ($districts as $d)
                                            <option value="{{ $d->id }}" {{ ($incompleteOrder && strtolower($incompleteOrder->district) == strtolower($d->name)) ? 'selected' : '' }}>{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="upazila" class="form-label">Customer Upazila</label>
                                    <select class="form-select dropdown-groups" id="upazila" name="upazila_id" style="width: 100%;">
                                        <option value="" selected disabled>Select Upazila</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="customerAddress" class="form-label">Customer Address</label>
                                <textarea class="form-control" id="customerAddress" name="address" rows="3" placeholder="Enter detailed address" required>{{ old('address', $incompleteOrder ? $incompleteOrder->address : '') }}</textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="delivery_status" class="form-label">Delivery Status</label>
                                    <select class="form-select" id="delivery_status" name="delivery_status">
                                        <option value="new" selected>New (Hold)</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="packaging">Packaging</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="payment_type" class="form-label">Payment Type</label>
                                    <select class="form-select" id="payment_type" name="payment_type">
                                        <option value="Cash On Delivery" selected>Cash On Delivery</option>
                                        <option value="Bkash">Bkash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Card">Card</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label for="customerComments" class="form-label">Comments / Staff Notes</label>
                                <textarea class="form-control" id="customerComments" name="comments" rows="2" placeholder="Comments or notes"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Details Column (7 Cols) -->
                <div class="col-lg-7">
                    <div class="premium-card">
                        <div class="card-header">
                            <span><i class="fa-solid fa-basket-shopping mr-2 text-primary"></i>Ordered Products</span>
                        </div>
                        <div class="card-body">
                            <!-- Catalog Autocomplete Search -->
                            <div class="position-relative mb-4">
                                <label for="productSearch" class="form-label">Search Product by Name or Code</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white" style="border-radius: 10px 0 0 10px; border-right: none; border-color: #cbd5e1;"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="productSearch" placeholder="Type at least 2 characters to search..." style="border-radius: 0 10px 10px 0 !important; border-left: none;">
                                </div>
                                <div id="searchResults" class="search-results-dropdown"></div>
                            </div>

                            <!-- Selected Products Table -->
                            <div class="table-responsive mb-4" style="max-height: 400px; overflow-y: auto;">
                                <table class="table edit-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">SL</th>
                                            <th>Product</th>
                                            <th style="width: 120px;">Size</th>
                                            <th style="width: 120px;">Color</th>
                                            <th style="width: 75px;" class="text-center">Qty</th>
                                            <th style="width: 105px;" class="text-right">Price</th>
                                            <th style="width: 110px;" class="text-right">Total</th>
                                            <th style="width: 50px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-rows-container">
                                        <!-- Dynamically injected items -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Financial Summary -->
                            <div class="calculation-card">
                                <div class="calc-row">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="font-weight-bold text-dark"><span id="subtotal-display">0.00</span> ৳</span>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-4">
                                        <label class="form-label font-weight-normal text-muted mb-1" style="font-size: 0.8rem;">Admin Discount</label>
                                        <input type="number" class="form-control form-control-sm text-right" id="admin_discount" name="admin_discount" value="0" min="0" step="0.01" style="font-weight: 700;">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label font-weight-normal text-muted mb-1" style="font-size: 0.8rem;">Shipping Charge</label>
                                        <input type="number" class="form-control form-control-sm text-right" id="delivery_charge" name="delivery_charge" value="0" min="0" step="0.01" style="font-weight: 700;">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label font-weight-normal text-muted mb-1" style="font-size: 0.8rem;">Paid Amount</label>
                                        <input type="number" class="form-control form-control-sm text-right" id="paid_amount" name="paid_amount" value="0" min="0" step="0.01" style="font-weight: 700; color: #047857;">
                                    </div>
                                </div>
                                <div class="calc-row due-row mt-3">
                                    <span>Due Amount:</span>
                                    <span><span id="due-display">0.00</span> ৳</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Action Buttons -->
            <div class="action-buttons-container">
                <a href="{{ route('order-index') }}" class="action-btn-premium btn-back">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </a>
                <button type="submit" class="action-btn-premium btn-submit">
                    <i class="fa-regular fa-floppy-disk"></i> Create Order
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <!-- Select2 and District Upazila dynamic link handler -->
    <script>
        $(document).ready(function() {
            // Apply Select2 selectors
            $('#district').select2({
                placeholder: "Select District",
                allowClear: true
            });
            $('#upazila').select2({
                placeholder: "Select Upazila",
                allowClear: true
            });

            // District change AJAX fetch for Upazilas
            $('#district').on('change', function() {
                let districtId = $(this).val();
                
                // Clear upazila dropdown
                $('#upazila').empty().append('<option value="" selected disabled>Select Upazila</option>').trigger('change');

                if (districtId) {
                    $.ajax({
                        url: "/get-upazilas/" + districtId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $.each(data, function(index, upazila) {
                                $('#upazila').append(new Option(upazila.name, upazila.id));
                            });
                            // Trigger select2 update
                            $('#upazila').trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading upazilas:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to fetch upazilas matching the selected district.'
                            });
                        }
                    });
                }
            });

            @if ($incompleteOrder)
                // If incomplete order is present, trigger load for district and pre-select upazila
                let initialDistrictId = $('#district').val();
                if (initialDistrictId) {
                    $.ajax({
                        url: "/get-upazilas/" + initialDistrictId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $.each(data, function(index, upazila) {
                                let isSelected = "{{ strtolower($incompleteOrder->thana ?? '') }}" === upazila.name.toLowerCase();
                                let option = new Option(upazila.name, upazila.id, isSelected, isSelected);
                                $('#upazila').append(option);
                            });
                            $('#upazila').trigger('change');
                        }
                    });
                }
            @endif
        });
    </script>

    <!-- Calculation core logic -->
    <script>
        function calculateTotals() {
            let subtotal = 0;

            $('.product-row').each(function() {
                let qty = parseInt($(this).find('.qty-input').val()) || 1;
                let unitPrice = parseFloat($(this).find('.price-input').val()) || 0;
                let rowTotal = qty * unitPrice;
                
                // Update row values
                $(this).find('.row-total').text(rowTotal);
                $(this).find('.row-total-display').text(rowTotal.toFixed(2));
                
                subtotal += rowTotal;
            });

            // Read modifiers
            let adminDiscount = parseFloat($('#admin_discount').val()) || 0;
            let shippingCharge = parseFloat($('#delivery_charge').val()) || 0;
            let paidAmount = parseFloat($('#paid_amount').val()) || 0;

            // Grand Total (Due computation in system)
            let grandTotal = subtotal - adminDiscount + shippingCharge;
            let dueAmount = grandTotal - paidAmount;

            // Set values
            $('#subtotal-display').text(subtotal.toFixed(2));
            $('#total-amount-input').val(subtotal.toFixed(2));
            $('#due-display').text(Math.max(0, dueAmount).toFixed(2));

            // Visual due styling warning
            if (dueAmount > 0) {
                $('.due-row').css({
                    'background-color': '#fee2e2',
                    'border-color': '#fca5a5',
                    'color': '#b91c1c'
                });
            } else {
                $('.due-row').css({
                    'background-color': '#d1fae5',
                    'border-color': '#6ee7b7',
                    'color': '#047857'
                });
            }
        }

        $(document).ready(function() {
            // Live modification bindings
            $(document).on('input change', '.qty-input, .price-input, #admin_discount, #delivery_charge, #paid_amount', function() {
                calculateTotals();
            });

            // Reindex table row numbers
            function reindexRows() {
                $('.product-row').each(function(index) {
                    $(this).find('.sl-number').text(index + 1);
                });
            }

            // Exclusivity between Size and Color selection
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

            // Remove product row
            $(document).on('click', '.btn-remove-row', function(e) {
                e.preventDefault();
                let row = $(this).closest('tr');
                Swal.fire({
                    title: 'Remove Product?',
                    text: 'Are you sure you want to remove this item from the list?',
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

            // Validate form before submitting
            $('#createOrderForm').on('submit', function(e) {
                if ($('.product-row').length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Product List Empty',
                        text: 'You must add at least one product to create an order.'
                    });
                }
            });
            
            // Expose reindex function
            window.reindexRows = reindexRows;
        });
    </script>

    <!-- Product Autocomplete catalog search script -->
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

            // Close suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#productSearch, #searchResults').length) {
                    $resultsDropdown.hide();
                }
            });

            // Click search suggestion to add to lists
            $(document).on('click', '.add-catalog-product', function() {
                let id = $(this).attr('data-id');
                let name = $(this).attr('data-name');
                let code = $(this).attr('data-code');
                let price = parseFloat($(this).attr('data-price')) || 0;
                let image = $(this).attr('data-image');

                let product = window.productCatalog ? window.productCatalog[id] : null;

                // Check if already in the list
                let alreadyExists = false;
                $('.product-row').each(function() {
                    if ($(this).attr('data-product-id') == id) {
                        alreadyExists = true;
                        let qtyInput = $(this).find('.qty-input');
                        qtyInput.val(parseInt(qtyInput.val()) + 1);
                        calculateTotals();
                    }
                });

                if (!alreadyExists) {
                    let nextSl = $('.product-row').length + 1;

                    // Size/Attribute dynamic options builder
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

                    // Color dynamic options builder
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
