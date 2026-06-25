@php
    use App\Models\FeatureActivation;

    $features = FeatureActivation::all();
    $featuresConfig = $features->pluck('status', 'name')->toArray();
@endphp

@extends('layouts.AdminLays.master')
@section('title')
    INCOMPLETE ORDERS
@endsection
@section('content')
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Premium Custom Styles -->
    <style>
        .incom-container {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            padding-bottom: 2rem;
        }

        /* Stats Cards */
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.08);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(148, 163, 184, 0.15);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }
        .stat-num {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
        }

        /* Filter Section Styling */
        .filter-panel {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.08);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        .filter-form-row {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .filter-group {
            flex: 1;
            min-width: 180px;
        }
        .filter-group.search-group {
            flex: 1.5;
            min-width: 240px;
        }
        .filter-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            display: block;
        }
        .filter-control {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 0.55rem 0.9rem !important;
            font-size: 0.85rem !important;
            height: auto !important;
            color: #334155 !important;
            background-color: #ffffff !important;
            transition: all 0.2s ease !important;
            width: 100%;
        }
        .filter-control:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
            outline: none !important;
        }

        /* Action Buttons */
        .btn-premium {
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        .btn-premium-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        }
        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #2563eb 100%);
            transform: translateY(-1px);
        }
        .btn-premium-secondary {
            background: #f8fafc;
            color: #475569 !important;
            border: 1px solid #cbd5e1;
        }
        .btn-premium-secondary:hover {
            background: #f1f5f9;
        }

        /* Table Card Container */
        .premium-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .card-header-bar {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .card-title-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Bulk Action controls */
        .bulk-container {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        .bulk-select {
            background: transparent !important;
            border: none !important;
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            color: #475569 !important;
            padding: 2px 10px !important;
            height: auto !important;
            cursor: pointer;
            outline: none !important;
        }

        /* Table Styling */
        .premium-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }
        .premium-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .premium-table td {
            padding: 1.1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .premium-table tr:hover {
            background-color: #f8fafc/50;
        }

        /* Table components styling */
        .customer-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 0.92rem;
            margin-bottom: 2px;
        }
        .customer-address {
            font-size: 0.78rem;
            color: #64748b;
            line-height: 1.4;
        }
        .phone-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            background-color: #f1f5f9;
            padding: 4px 8px;
            border-radius: 6px;
            text-decoration: none !important;
        }
        .phone-badge i {
            color: #4f46e5;
        }

        /* Fraud Check design */
        .fraud-check-badge {
            padding: 4px 10px;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }
        .fraud-check-btn-idle {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .fraud-check-btn-idle:hover {
            background-color: #fca5a5;
        }
        .fraud-result-pill {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
            border-radius: 8px;
            margin-top: 6px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            border-left: 4px solid #10b981;
            transition: all 0.2s ease;
        }
        .fraud-result-pill:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        }

        .product-code-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1e40af;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            margin-right: 4px;
            margin-bottom: 4px;
            border: 1px solid #bfdbfe;
        }

        .total-amount {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
        }

        /* Action Icons */
        .icon-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none !important;
            font-size: 0.9rem;
        }
        .icon-action-confirm {
            background: #dcfce7;
            color: #15803d;
        }
        .icon-action-confirm:hover {
            background: #bbf7d0;
            transform: scale(1.08);
        }
        .icon-action-edit {
            background: #e0e7ff;
            color: #4338ca;
        }
        .icon-action-edit:hover {
            background: #c7d2fe;
            transform: scale(1.08);
        }
        .icon-action-delete {
            background: #fee2e2;
            color: #b91c1c;
        }
        .icon-action-delete:hover {
            background: #fca5a5;
            transform: scale(1.08);
        }
        .icon-action-view {
            background: #e0f2fe;
            color: #0369a1;
        }
        .icon-action-view:hover {
            background: #bae6fd;
            transform: scale(1.08);
        }

        /* Checkbox styling */
        .checkbox-custom {
            width: 18px;
            height: 18px;
            accent-color: #4f46e5;
            cursor: pointer;
        }
    </style>

    <div class="incom-container">
        <!-- Top Stats Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa-solid fa-basket-shopping"></i>
                    </div>
                    <div>
                        <div class="stat-num">{{ $incomOrders->total() }}</div>
                        <div class="stat-label">Total Abandoned Checkouts</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="filter-panel">
            <form action="{{ route('incomplete.index') }}" method="GET">
                <div class="filter-form-row">
                    <div class="filter-group search-group">
                        <label class="filter-label">Search Incomplete Order</label>
                        <div class="position-relative">
                            <input type="text" name="search" value="{{ request('search') }}" class="filter-control" placeholder="Search by name, ID or phone...">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="filter-control">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="filter-control">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Time Period</label>
                        <select name="days" class="filter-control">
                            <option value="">All Days</option>
                            <option value="today" {{ request('days') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request('days') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="7days" {{ request('days') == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="30days" {{ request('days') == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_year" {{ request('days') == 'this_year' ? 'selected' : '' }}>This Year</option>
                            <option value="last_year" {{ request('days') == 'last_year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="btn-premium btn-premium-primary">
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('incomplete.index') }}" class="btn-premium btn-premium-secondary ml-1">
                            <i class="fa-solid fa-arrows-rotate"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Orders Table Card -->
        <div class="premium-card">
            <div class="card-header-bar">
                <div class="card-title-text">
                    <i class="fa-solid fa-clock-rotate-left text-warning"></i>
                    Abandoned / Incomplete Checkout List
                </div>

                <!-- Bulk Action Container -->
                <div class="bulk-container">
                    <i class="fa-solid fa-list-check text-muted" style="font-size: 0.85rem;"></i>
                    <select id="bulkActionSelect" class="bulk-select">
                        <option value="">Bulk Actions</option>
                        <option value="confirm_pending">Confirm Selected (Pending)</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button class="btn btn-xs btn-primary px-3 py-1 font-weight-bold" id="applyBulkActionBtn" style="border-radius: 6px; font-size: 0.75rem;">
                        Apply
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table premium-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;" class="text-center">
                                <input type="checkbox" id="checkAllOrders" class="checkbox-custom">
                            </th>
                            <th>Customer Information</th>
                            <th>Contact & Fraud Check</th>
                            <th>Products</th>
                            <th>Abandoned Value</th>
                            <th>Status & Date</th>
                            <th style="width: 130px;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($incomOrders as $incomOrder)
                            <tr id="row-{{ $incomOrder->id }}">
                                <td class="text-center">
                                    <input type="checkbox" class="order-checkbox checkbox-custom" value="{{ $incomOrder->id }}">
                                </td>
                                <td>
                                    <div class="customer-name">{{ $incomOrder->name }}</div>
                                    <div class="customer-address">
                                        {{ $incomOrder->address }}<br>
                                        <span class="text-muted">{{ $incomOrder->thana }}, {{ $incomOrder->district }}</span>
                                    </div>
                                </td>
                                <td>
                                    <a href="tel:{{ $incomOrder->phone }}" class="phone-badge" title="Call customer">
                                        <i class="fa-solid fa-phone"></i> {{ $incomOrder->phone }}
                                    </a>
                                    <br>
                                    @if ($featuresConfig['fraud_check_api'] == 1)
                                        <button type="button" class="fraud-check-badge fraud-check-btn-idle fraud-check-btn mt-2"
                                            data-phone="{{ $incomOrder->phone }}" data-id="{{ $incomOrder->id }}">
                                            <i class="fa-solid fa-shield-halved"></i>
                                            <span class="btn-text">Fraud Check</span>
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                        <div class="fraud-result-container mt-1"></div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $productCodes = json_decode($incomOrder->product_id, true);
                                        $products = [];
                                        if (is_array($productCodes) && count($productCodes) > 0) {
                                            $products = \App\Models\Product::whereIn('code', $productCodes)->with('firstImage')->get();
                                        }
                                    @endphp
                                    <div class="d-flex flex-column" style="gap: 8px;">
                                        @forelse ($products as $product)
                                            @php
                                                $imageSrc = asset('favicon.png');
                                                if ($product->firstImage) {
                                                    $imgName = $product->firstImage->image;
                                                    if (file_exists(public_path('adminDash/images/product/' . $imgName))) {
                                                        $imageSrc = asset('adminDash/images/product/' . $imgName);
                                                    } else {
                                                        $imageSrc = asset('adminDash/uploads/products/' . $imgName);
                                                    }
                                                }
                                            @endphp
                                            <div class="d-flex align-items-center" style="gap: 8px;">
                                                <img src="{{ $imageSrc }}" style="width: 32px; height: 32px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff;" alt="">
                                                <span class="font-weight-bold text-dark" style="font-size: 0.8rem;">{{ $product->title }}</span>
                                            </div>
                                        @empty
                                            @if (is_array($productCodes) && count($productCodes) > 0)
                                                @foreach ($productCodes as $code)
                                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                                        <img src="{{ asset('favicon.png') }}" style="width: 32px; height: 32px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff;" alt="">
                                                        <span class="text-muted small" style="font-size: 0.8rem;">{{ $code }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted small">No items tracked</span>
                                            @endif
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    <span class="total-amount">{{ number_format($incomOrder->grand_total, 2) }} ৳</span>
                                </td>
                                <td>
                                    <span class="badge badge-warning text-uppercase font-weight-bold" style="background-color: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 6px; font-size: 0.72rem;">{{ $incomOrder->status }}</span>
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                        {{ $incomOrder->created_at->format('d M Y, h:i A') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center" style="gap: 8px;">
                                        <!-- Confirm / Convert order -->
                                        <a href="{{ route('admin.order-create', ['incomplete_id' => $incomOrder->id]) }}" class="icon-action-btn icon-action-confirm" title="Process & Confirm Order">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </a>
                                        <a href="{{ route('admin.order-create', ['incomplete_id' => $incomOrder->id]) }}" class="icon-action-btn icon-action-view" title="View Order">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <!-- Edit incomplete order -->
                                        <button type="button" class="icon-action-btn icon-action-edit edit-incom-btn" 
                                            data-id="{{ $incomOrder->id }}" 
                                            data-name="{{ $incomOrder->name }}" 
                                            data-phone="{{ $incomOrder->phone }}" 
                                            data-address="{{ $incomOrder->address }}" 
                                            data-total="{{ $incomOrder->grand_total }}"
                                            title="Quick Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Delete incomplete order -->
                                        <button type="button" class="icon-action-btn icon-action-delete delete-incom-btn" data-id="{{ $incomOrder->id }}" title="Delete Abandoned Record">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox mb-2" style="font-size: 2rem; color: #cbd5e1;"></i>
                                    <p class="mb-0">No incomplete orders matching search criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($incomOrders->hasPages())
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                    {{ $incomOrders->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Incomplete Order Modal -->
    <div class="modal fade" id="editIncomModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <form id="editIncomForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-bottom p-3">
                        <h5 class="modal-title font-weight-bold text-dark"><i class="fa-solid fa-pen-to-square mr-2 text-indigo"></i>Edit Incomplete Order</h5>
                        <button type="button" class="close text-dark" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4" style="font-family: 'Inter', sans-serif;">
                        <input type="hidden" id="edit_incom_id">
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 0.8rem;">Customer Name</label>
                            <input type="text" name="name" id="edit_incom_name" class="filter-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 0.8rem;">Customer Phone</label>
                            <input type="text" name="phone" id="edit_incom_phone" class="filter-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 0.8rem;">Grand Total Amount (৳)</label>
                            <input type="number" name="grand_total" id="edit_incom_total" class="filter-control" step="0.01" required>
                        </div>

                        <div class="mb-0">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 0.8rem;">Address</label>
                            <textarea name="address" id="edit_incom_address" class="filter-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top p-3">
                        <button type="button" class="btn-premium btn-premium-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn-premium btn-premium-primary" id="saveEditIncomBtn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Courier History Detail Modal -->
    <div class="modal fade" id="courierDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header bg-light border-bottom p-3">
                    <h5 class="modal-title font-weight-bold text-dark"><i class="fa-solid fa-circle-info mr-2 text-primary"></i>Courier History Breakdown</h5>
                    <button type="button" class="close text-dark" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" id="courier_detail_content">
                </div>
                <div class="modal-footer bg-light border-top p-3">
                    <button type="button" class="btn-premium btn-premium-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Block -->
    <script>
        var lastCourierData = {};

        $(document).ready(function() {
            // Setup CSRF header
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // 1. Check/Uncheck all
            $('#checkAllOrders').on('change', function() {
                $('.order-checkbox').prop('checked', $(this).is(':checked'));
            });

            // 2. Quick Edit button handler
            $('.edit-incom-btn').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let phone = $(this).data('phone');
                let address = $(this).data('address');
                let total = $(this).data('total');

                $('#edit_incom_id').val(id);
                $('#edit_incom_name').val(name);
                $('#edit_incom_phone').val(phone);
                $('#edit_incom_address').val(address);
                $('#edit_incom_total').val(total);

                $('#editIncomForm').attr('action', "/admin/incomplete/orders/" + id);
                $('#editIncomModal').modal('show');
            });

            // 3. Edit Form Submit AJAX Handler
            $('#editIncomForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let submitBtn = $('#saveEditIncomBtn');
                let originalText = submitBtn.text();

                submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        submitBtn.prop('disabled', false).text(originalText);
                        if (response.success) {
                            $('#editIncomModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message || 'Updated successfully!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Failed', response.message || 'Failed to update settings.', 'error');
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).text(originalText);
                        let message = 'Update failed.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        Swal.fire('Error', message, 'error');
                    }
                });
            });

            // 4. Delete Single Incomplete Order Handler
            $('.delete-incom-btn').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let row = $('#row-' + id);

                Swal.fire({
                    title: 'Delete Incomplete Order?',
                    text: 'This action is permanent and cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/incomplete/orders/" + id,
                            type: 'POST',
                            data: {
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    icon: 'success',
                                    title: 'Deleted successfully!'
                                });
                                row.fadeOut(500, function() {
                                    row.remove();
                                });
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to delete record.', 'error');
                            }
                        });
                    }
                });
            });

            // 5. Bulk Actions Apply Handler
            $('#applyBulkActionBtn').on('click', function(e) {
                e.preventDefault();
                let action = $('#bulkActionSelect').val();
                if (!action) return Swal.fire('Warning', 'Please select a bulk action.', 'warning');

                let selectedIds = [];
                $('.order-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    return Swal.fire('Warning', 'No orders selected. Check at least one order checkbox.', 'warning');
                }

                let confirmTitle = action === 'delete' ? 'Delete Selected Records?' : 'Confirm Selected Orders?';
                let confirmText = action === 'delete' ? 'Selected incomplete checkouts will be permanently deleted.' : 'Selected checkouts will be converted to pending orders.';
                let confirmBtnColor = action === 'delete' ? '#ef4444' : '#10b981';

                Swal.fire({
                    title: confirmTitle,
                    text: confirmText,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: confirmBtnColor,
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Proceed!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('incomplete.bulk-action') }}",
                            type: 'POST',
                            data: {
                                ids: selectedIds,
                                action: action
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Failed', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON?.message || 'Bulk operation failed.', 'error');
                            }
                        });
                    }
                });
            });

            // 6. Real-time Fraud Check Button Handler
            $(document).on('click', '.fraud-check-btn', function(e) {
                e.preventDefault();
                let btn = $(this);
                let phone = btn.data('phone');
                let incomId = btn.data('id');
                let resultContainer = btn.siblings('.fraud-result-container');
                let btnText = btn.find('.btn-text');
                let spinner = btn.find('.spinner-border');

                btn.prop('disabled', true);
                btnText.text('Checking...');
                spinner.removeClass('d-none');

                $.ajax({
                    url: "{{ route('fraud.check') }}",
                    method: "POST",
                    data: {
                        phone: phone
                    },
                    success: function(response) {
                        if (response.status === "success" && response.courierData) {
                            lastCourierData[incomId] = response.courierData;
                            let summary = response.courierData.summary;

                            let html = `
                            <div class="fraud-result-pill open-detail" data-id="${incomId}" style="border-left-color: ${summary.success_ratio > 70 ? '#10b981' : (summary.success_ratio > 40 ? '#f59e0b' : '#ef4444')};">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span style="font-weight: 700; color: #1e293b;">Success: ${summary.success_ratio}%</span>
                                    <span class="text-indigo" style="font-size: 0.7rem; font-weight: 700;">Details <i class="fa-solid fa-angle-right"></i></span>
                                </div>
                                <div class="text-muted small">Total Parcel: ${summary.total_parcel} | Return: ${summary.cancelled_parcel}</div>
                            </div>`;
                            resultContainer.html(html);
                        } else {
                            resultContainer.html('<small class="text-muted d-block mt-1"><i class="fa-solid fa-circle-minus mr-1"></i>No history records found</small>');
                        }
                    },
                    error: function(xhr) {
                        resultContainer.html('<small class="text-danger d-block mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>Fetch failed</small>');
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                        btnText.text('Fraud Check');
                        spinner.addClass('d-none');
                    }
                });
            });

            // 7. Open Courier Detail Modal
            $(document).on('click', '.open-detail', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let fullData = lastCourierData[id];
                let html = '';

                if (fullData) {
                    const courierKeys = ['pathao', 'redx', 'steadfast', 'paperfly', 'parceldex'];
                    courierKeys.forEach(function(key) {
                        let courier = fullData[key];
                        if (courier && courier.name) {
                            html += `
                            <div class="d-flex align-items-center mb-3 p-2 bg-light rounded" style="border: 1px solid #e2e8f0; gap: 12px;">
                                <img src="${courier.logo}" style="width: 40px; height: 40px; object-fit: contain; border-radius: 6px; background: white; padding: 2px; border: 1px solid #e2e8f0;" class="mr-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-dark" style="font-weight: 700; font-size: 0.88rem;">${courier.name}</h6>
                                    <small class="text-muted">Total: ${courier.total_parcel} | Success: <span class="text-success font-weight-bold">${courier.success_parcel}</span> | Failed: <span class="text-danger font-weight-bold">${courier.cancelled_parcel}</span></small>
                                </div>
                                <span class="badge badge-indigo font-weight-bold" style="padding: 4px 8px; font-size: 0.8rem; background-color: #e0e7ff; color: #4338ca; border-radius: 6px;">${courier.success_ratio}%</span>
                            </div>`;
                        }
                    });

                    if (!html) {
                        html = '<p class="text-muted text-center py-3">No individual courier breakdown available.</p>';
                    }

                    $('#courier_detail_content').html(html);
                    $('#courierDetailModal').modal('show');
                }
            });

            // 8. Close Modal helper
            $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"], .close', function() {
                $('.modal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });
        });
    </script>
@endsection
