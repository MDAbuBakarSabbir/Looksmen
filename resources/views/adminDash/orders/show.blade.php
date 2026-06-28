@extends('layouts.Backend.master')

@section('title')
    ORDER DETAILS #LM-{{ $order->id }}
@endsection

@section('content')
    @php
        $featuresConfig = \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
        $activeCourier = \App\Models\CourierApi::where('status', '1')->first();
    @endphp

    <!-- Premium Custom Styles -->
    <style>
        /* Custom premium style variables and overrides */
        .order-details-container {
            font-family: 'Inter', 'Outfit', sans-serif;
            color: #1e293b;
            padding-bottom: 2rem;
        }

        /* Card Styling */
        .premium-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.08), 0 2px 8px -1px rgba(148, 163, 184, 0.04);
            margin-bottom: 1.75rem;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .premium-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -5px rgba(148, 163, 184, 0.15), 0 8px 16px -6px rgba(148, 163, 184, 0.08);
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

        /* Header Info styling */
        .order-header-box {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            border-radius: 16px;
            padding: 1.75rem 2rem;
            color: #ffffff;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 25px -5px rgba(49, 46, 129, 0.2);
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .order-header-title {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .order-header-meta {
            font-size: 0.9rem;
            opacity: 0.85;
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .order-header-meta span {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Actions in Header */
        .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .action-btn-premium {
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none !important;
        }
        .action-btn-premium.btn-print {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .action-btn-premium.btn-print:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }
        .action-btn-premium.btn-edit {
            background: #10b981;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        .action-btn-premium.btn-edit:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        .action-btn-premium.btn-back {
            background: #475569;
            color: #ffffff !important;
        }
        .action-btn-premium.btn-back:hover {
            background: #334155;
            transform: translateY(-1px);
        }

        /* Custom Status Badges */
        .status-badge-lg {
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.5rem 1.25rem;
            border-radius: 30px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .status-badge-lg::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: currentColor;
            display: inline-block;
            box-shadow: 0 0 8px currentColor;
        }

        .status-new-lg { background-color: #f1f5f9; color: #475569; }
        .status-pending-lg { background-color: #fef3c7; color: #d97706; }
        .status-approved-lg { background-color: #d1fae5; color: #059669; }
        .status-packaging-lg { background-color: #e0e7ff; color: #4f46e5; }
        .status-incourier-lg, .status-in_courier-lg { background-color: #e0f2fe; color: #0284c7; }
        .status-delivered-lg { background-color: #d1fae5; color: #047857; border: 1px solid #10b981; }
        .status-cancel-lg, .status-canceled-lg { background-color: #fee2e2; color: #dc2626; }
        .status-returned-lg { background-color: #f1f5f9; color: #0f172a; border: 1px solid #cbd5e1; }

        /* Product Items Table Styling */
        .items-table {
            width: 100%;
            margin-bottom: 0;
        }
        .items-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .items-table td {
            padding: 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .product-thumbnail {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: transform 0.2s ease;
        }
        .product-thumbnail:hover {
            transform: scale(1.1);
        }
        .product-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            display: block;
            margin-bottom: 0.25rem;
        }
        .product-spec {
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .spec-badge {
            background: #f1f5f9;
            padding: 0.15rem 0.5rem;
            border-radius: 6px;
            font-weight: 500;
        }

        /* Cost & Payment Summary Layout */
        .billing-summary {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border: 1px solid #e2e8f0;
        }
        .billing-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            font-size: 0.95rem;
            color: #475569;
        }
        .billing-row.total {
            border-top: 1px solid #e2e8f0;
            padding-top: 1rem;
            margin-top: 0.5rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: #0f172a;
        }
        .billing-row.due {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-top: 0.75rem;
            font-weight: 800;
            font-size: 1.15rem;
            color: #b91c1c;
        }
        .billing-row.paid-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-top: 0.75rem;
            font-weight: 800;
            font-size: 1.15rem;
            color: #047857;
        }

        /* Timeline Tracker */
        .timeline {
            position: relative;
            padding-left: 2rem;
            margin-left: 0.5rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            bottom: 5px;
            width: 2px;
            background: #e2e8f0;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }
        .timeline-item:last-child {
            padding-bottom: 0;
        }
        .timeline-dot {
            position: absolute;
            left: -2rem;
            transform: translateX(-40%);
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #cbd5e1;
            border: 3px solid #ffffff;
            box-shadow: 0 0 0 3px #e2e8f0;
            top: 4px;
            transition: all 0.2s ease;
        }
        .timeline-item:first-child .timeline-dot {
            background: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
        }
        .timeline-time {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .timeline-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        .timeline-desc {
            font-size: 0.85rem;
            color: #4b5563;
        }
        .timeline-actor {
            font-size: 0.8rem;
            color: #4f46e5;
            font-weight: 600;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Customer Avatar */
        .customer-avatar-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%);
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        /* Contact Button bar */
        .contact-hub {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .contact-btn {
            flex: 1;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff !important;
            font-size: 1.1rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            text-decoration: none !important;
        }
        .contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .contact-btn.btn-call { background-color: #0075B8; }
        .contact-btn.btn-wa { background-color: #009134; }
        .contact-btn.btn-sms { background-color: #e44a1b; }
        .contact-btn.btn-copy { background-color: #6c757d; }

        /* Status Action Buttons Grid */
        .status-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }
        .status-btn-option {
            padding: 0.75rem 0.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }
        .status-btn-option:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .status-btn-option.btn-option-pending:hover { color: #d97706; background-color: #fffbeb; border-color: #fde68a; }
        .status-btn-option.btn-option-approved:hover { color: #059669; background-color: #f0fdf4; border-color: #bbf7d0; }
        .status-btn-option.btn-option-packaging:hover { color: #4f46e5; background-color: #eef2ff; border-color: #c7d2fe; }
        .status-btn-option.btn-option-incourier:hover { color: #0284c7; background-color: #f0f9ff; border-color: #bae6fd; }
        .status-btn-option.btn-option-cancel:hover { color: #dc2626; background-color: #fef2f2; border-color: #fecaca; }
        .status-btn-option.btn-option-new:hover { color: #64748b; background-color: #f8fafc; border-color: #cbd5e1; }

        /* Fraud Meter progress styling */
        .fraud-progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e2e8f0;
            overflow: hidden;
            margin: 0.5rem 0;
        }
        .fraud-progress-bar {
            height: 100%;
            border-radius: 4px;
            transition: width 0.6s ease;
        }
        .fraud-indicator-pill {
            padding: 0.2rem 0.6rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Steadfast booking panel styling */
        .steadfast-card {
            background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);
            border: 1px solid #bfdbfe;
        }
        .btn-steadfast-book {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            border: none;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-steadfast-book:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }
        .btn-steadfast-book:active {
            transform: translateY(0);
        }
    </style>

    <div class="order-details-container">
        <!-- Modern Header Section -->
        <div class="order-header-box">
            <div>
                <div class="d-flex align-items-center flex-wrap mb-2" style="gap: 12px;">
                    <div class="order-header-title" style="margin-bottom: 0;">Invoice #LM-{{ $order->id }}</div>
                    @if ($order->consignment_id != null)
                        <span class="badge badge-primary px-3 py-2 font-weight-bold" style="border-radius: 30px; font-size: 0.85rem; background: rgba(255,255,255,0.15); color: #ffffff; border: 1px solid rgba(255,255,255,0.3); letter-spacing: 0.5px;">
                            Courier ID: {{ $order->consignment_id }}
                        </span>
                    @endif
                </div>
                <div class="order-header-meta">
                    <span>
                        <i class="fa-regular fa-calendar"></i> 
                        Placed: {{ $order->created_at->format('d M, Y | h:i A') }}
                    </span>
                    @if ($order->admin)
                        <span>
                            <i class="fa-regular fa-user"></i> 
                            Updated By: <strong>{{ $order->admin->name }}</strong>
                        </span>
                    @endif
                    <div>
                        <span class="status-badge-lg status-{{ $order->delivery_status }}-lg">
                            {{ ucfirst($order->delivery_status) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="header-actions">
                <button class="action-btn-premium btn-print">
                    <i class="fa fa-print"></i> Print Invoice
                </button>
                <a href="{{ route('admin.order-edit', $order->id) }}" class="action-btn-premium btn-edit">
                    <i class="fa-regular fa-pen-to-square"></i> Edit Order
                </a>
                <button class="action-btn-premium btn-back">
                    <i class="fa fa-arrow-left"></i> Back
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Left Side Column (8 Cols) -->
            <div class="col-lg-8">
                <!-- Order Items Card -->
                <div class="premium-card">
                    <div class="card-header">
                        <span><i class="fa-solid fa-basket-shopping mr-2 text-primary"></i>Ordered Items</span>
                        <span class="badge badge-primary px-3 py-2 font-weight-bold" style="border-radius: 30px;">
                            {{ $order->orderDetails->sum('product_qty') }} Items
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table items-table">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">SL</th>
                                        <th style="width: 80px;">Product</th>
                                        <th>Details</th>
                                        <th class="text-center" style="width: 100px;">Qty</th>
                                        <th class="text-right" style="width: 120px;">Price</th>
                                        <th class="text-right" style="width: 130px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderDetails as $item)
                                        @php
                                            $imageSrc = asset('frontend/assets/img/placeholder.jpg');
                                            if ($item->orderProduct && $item->orderProduct->firstImage) {
                                                $imgName = $item->orderProduct->firstImage->image;
                                                if (file_exists(public_path('adminDash/uploads/products/' . $imgName))) {
                                                    $imageSrc = asset('adminDash/uploads/products/' . $imgName);
                                                } elseif (file_exists(public_path('adminDash/images/product/' . $imgName))) {
                                                    $imageSrc = asset('adminDash/images/product/' . $imgName);
                                                } else {
                                                    $imageSrc = asset('adminDash/uploads/products/' . $imgName);
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <img class="product-thumbnail" src="{{ $imageSrc }}" alt="Product Image">
                                            </td>
                                            <td>
                                                <span class="product-title">{{ $item->orderProduct->title ?? 'Deleted Product' }}</span>
                                                <div class="product-spec">
                                                    @if(!empty($item->product_attribute))
                                                        <span class="spec-badge">Size: {{ $item->product_attribute }}</span>
                                                    @endif
                                                    @if(!empty($item->product_colour))
                                                        <span class="spec-badge">Color: {{ $item->product_colour }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center font-weight-bold">{{ $item->product_qty }}</td>
                                            <td class="text-right font-weight-bold">{{ number_format($item->unit_price, 2) }} ৳</td>
                                            <td class="text-right font-weight-bold text-primary">{{ number_format($item->total_price, 2) }} ৳</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Billing Summary -->
                        <div class="p-4 bg-light border-top">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($order->note)
                                        <div class="p-3 bg-white rounded border mb-3">
                                            <div class="font-weight-bold text-dark mb-1" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Customer Note:</div>
                                            <p class="text-muted mb-0" style="font-size: 0.9rem; line-height: 1.4;">{{ $order->note }}</p>
                                        </div>
                                    @endif
                                    @if($order->comments)
                                        <div class="p-3 bg-white rounded border">
                                            <div class="font-weight-bold text-dark mb-1" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Staff Comments:</div>
                                            <p class="text-muted mb-0" style="font-size: 0.9rem; line-height: 1.4;">{{ $order->comments }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="billing-summary mt-0">
                                        <div class="billing-row">
                                            <span>Subtotal</span>
                                            <span class="font-weight-bold">{{ number_format($order->total_amount, 2) }} ৳</span>
                                        </div>
                                        @if($order->admin_discount > 0)
                                            <div class="billing-row text-danger">
                                                <span>Admin Discount</span>
                                                <span class="font-weight-bold">- {{ number_format($order->admin_discount, 2) }} ৳</span>
                                            </div>
                                        @endif
                                        @if($order->coupon_discount > 0)
                                            <div class="billing-row text-danger">
                                                <span>Coupon Discount</span>
                                                <span class="font-weight-bold">- {{ number_format($order->coupon_discount, 2) }} ৳</span>
                                            </div>
                                        @endif
                                        <div class="billing-row">
                                            <span>Shipping Charge</span>
                                            <span class="font-weight-bold">{{ number_format($order->delivery_charge ?? 0, 2) }} ৳</span>
                                        </div>
                                        <div class="billing-row total">
                                            <span>Grand Total</span>
                                            <span>{{ number_format($order->total_amount - $order->admin_discount - $order->coupon_discount + ($order->delivery_charge ?? 0), 2) }} ৳</span>
                                        </div>
                                        <div class="billing-row text-success">
                                            <span>Paid Amount</span>
                                            <span class="font-weight-bold">{{ number_format($order->paid_amount, 2) }} ৳</span>
                                        </div>
                                        
                                        @if ($order->grand_total > 0)
                                            <div class="billing-row due">
                                                <span>Due Amount</span>
                                                <span>{{ number_format($order->grand_total, 2) }} ৳</span>
                                            </div>
                                        @else
                                            <div class="billing-row paid-success text-center">
                                                <span class="w-100 text-center"><i class="fa-solid fa-circle-check mr-1"></i> Fully Paid</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline Tracker -->
                <div class="premium-card">
                    <div class="card-header" id="orderStatusHistory" data-toggle="collapse"
                        data-target="#collapseOrderHistory" aria-expanded="true" aria-controls="collapseOrderHistory"
                        style="cursor: pointer;">
                        <span><i class="fas fa-history mr-2 text-primary"></i>Order Progress & Logs</span>
                        <i class="fas fa-chevron-down float-right mt-1"></i>
                    </div>

                    <div id="collapseOrderHistory" class="collapse show" aria-labelledby="orderStatusHistory">
                        <div class="card-body">
                            <div class="timeline">
                                @forelse($orderLogs as $log)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-time">
                                            {{ $log->created_at->format('d M, Y | h:i A') }}
                                        </div>
                                        <div class="timeline-title">
                                            Status updated to <span class="badge badge-light px-2 border text-uppercase font-weight-bold text-dark">{{ $log->order_status }}</span>
                                        </div>
                                        <div class="timeline-desc">
                                            {{ $log->details }}
                                        </div>
                                        <div class="timeline-actor">
                                            <i class="fa-solid fa-user-tie"></i> By: {{ $log->user?->name ?? 'System/Admin' }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-time">
                                            {{ $order->created_at->format('d M, Y | h:i A') }}
                                        </div>
                                        <div class="timeline-title">
                                            Order Created
                                        </div>
                                        <div class="timeline-desc">
                                            New order has been successfully placed.
                                        </div>
                                        <div class="timeline-actor">
                                            <i class="fa-solid fa-user"></i> By: Customer
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Column (4 Cols) -->
            <div class="col-lg-4">
                <!-- Customer Profile Card -->
                <div class="premium-card">
                    <div class="card-header">
                        <span><i class="fa-solid fa-user mr-2 text-primary"></i>Customer Profile</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <div class="customer-avatar-circle">
                                {{ strtoupper(substr($order->name ?? 'C', 0, 1)) }}
                            </div>
                            <h5 class="font-weight-bold text-dark mb-1">{{ $order->name }}</h5>
                            <span class="text-muted font-weight-medium" style="font-size: 0.9rem;">
                                <i class="fa-solid fa-phone mr-1"></i> {{ $order->phone }}
                            </span>
                            <input type="hidden" id="phoneNumber" value="{{ $order->phone }}">
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <div class="font-weight-bold text-dark mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Shipping Address:</div>
                            <div class="p-3 bg-light rounded text-muted" style="font-size: 0.9rem; line-height: 1.5; border: 1px solid #e2e8f0;">
                                {{ $order->address }}
                            </div>
                        </div>

                        <!-- Communication Hub -->
                        <div class="contact-hub">
                            <a href="tel:{{ $order->phone }}" class="contact-btn btn-call" title="Direct Call">
                                <i class="fa-solid fa-phone"></i>
                            </a>
                            <a href="https://wa.me/88{{ $order->phone }}" target="_blank" class="contact-btn btn-wa" title="WhatsApp Customer">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="sms:{{ $order->phone }}" class="contact-btn btn-sms" title="Send SMS">
                                <i class="fa-regular fa-message"></i>
                            </a>
                            <button onclick="copyNumber()" class="contact-btn btn-copy" title="Copy Number">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- BDCourier Fraud Check / History Card -->
                @php
                    $history = $order->getCourierHistoryData();
                    $courierData = $history ? ($history['courierData'] ?? null) : null;
                    $summary = $courierData ? ($courierData['summary'] ?? null) : null;
                    
                    $ratio = $summary ? intval($summary['success_ratio'] ?? 0) : 0;
                    $badgeBg = '#94a3b8';
                    $risk = 'No Data';
                    if ($summary) {
                        if ($ratio > 80) {
                            $badgeBg = '#22c55e';
                            $risk = 'Low Risk';
                        } elseif ($ratio > 50) {
                            $badgeBg = '#f59e0b';
                            $risk = 'Medium Risk';
                        } else {
                            $badgeBg = '#ef4444';
                            $risk = 'High Risk';
                        }
                    }
                @endphp
                <div class="premium-card" style="border-left: 4px solid {{ $summary ? $badgeBg : '#cbd5e1' }};">
                    <div class="card-header">
                        <div>
                            <span><i class="fa-solid fa-shield-halved mr-2 text-danger"></i>Courier History</span>
                            <span class="fraud-indicator-pill text-white ml-2" style="background-color: {{ $badgeBg }}; font-size: 0.7rem;">{{ $risk }}</span>
                        </div>
                        <button id="refreshCourierHistoryBtn" class="btn btn-xs btn-light border px-2 py-1 font-weight-bold" data-id="{{ $order->id }}" title="Sync with BD Courier API" style="border-radius: 6px; font-size: 0.75rem; cursor: pointer; transition: all 0.2s ease;">
                            <i class="fa-solid fa-arrows-rotate"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($summary)
                            <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem;">
                                <span class="text-muted">Delivery Success Rate:</span>
                                <span class="font-weight-bold text-dark">{{ $ratio }}%</span>
                            </div>
                            <div class="fraud-progress">
                                <div class="fraud-progress-bar" style="width: {{ $ratio }}%; background-color: {{ $badgeBg }};"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between text-muted mb-3" style="font-size: 0.8rem;">
                                <span>Total: <strong>{{ $summary['total_parcel'] }}</strong></span>
                                <span>Success: <strong class="text-success">{{ $summary['success_parcel'] }}</strong></span>
                                <span>Returned: <strong class="text-danger">{{ $summary['cancelled_parcel'] }}</strong></span>
                            </div>
                            
                            <!-- Collapsible detail block -->
                            <button class="btn btn-xs btn-outline-dark w-100 text-center font-weight-bold" type="button" data-toggle="collapse" data-target="#fraudDetails" aria-expanded="false" aria-controls="fraudDetails">
                                <i class="fa-solid fa-circle-info mr-1"></i> Courier Breakdown
                            </button>
                            
                            <div class="collapse mt-3" id="fraudDetails">
                                <div class="d-flex flex-column" style="gap: 10px;">
                                    @foreach ($courierData as $key => $c)
                                        @if ($key !== 'summary' && is_array($c) && isset($c['name']))
                                            <div class="d-flex align-items-center p-2 rounded bg-light" style="gap: 10px; font-size: 0.8rem;">
                                                @if(isset($c['logo']))
                                                    <img src="{{ $c['logo'] }}" style="width: 32px; height: 32px; object-fit: contain; background: white; border: 1px solid #e2e8f0; border-radius: 6px; padding: 2px;">
                                                @endif
                                                <div class="flex-grow-1">
                                                    <div class="font-weight-bold text-dark" style="font-size: 0.85rem;">{{ $c['name'] }}</div>
                                                    <span class="text-muted" style="font-size: 0.75rem;">
                                                        Total: {{ $c['total_parcel'] ?? 0 }} | 
                                                        Success: <span class="text-success font-weight-bold">{{ $c['success_parcel'] ?? 0 }}</span> | 
                                                        Failed: <span class="text-danger font-weight-bold">{{ $c['cancelled_parcel'] ?? 0 }}</span>
                                                    </span>
                                                </div>
                                                <span class="badge badge-secondary font-weight-bold">{{ $c['success_ratio'] ?? 0 }}%</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted mb-3" style="font-size: 0.85rem;">No cached courier history found for this number.</p>
                                <button type="button" onclick="$('#refreshCourierHistoryBtn').click();" class="btn btn-sm btn-primary font-weight-bold px-3 py-2" style="border-radius: 8px;">
                                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Fetch from BD Courier
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Steadfast Courier Integration Card (Conditional) -->
                @if (isset($featuresConfig['courier_api']) && $featuresConfig['courier_api'] == '1' && ($order->consignment_id != null || ($activeCourier && $activeCourier->courier_name == 'steadfast')))
                    <div class="premium-card steadfast-card">
                        <div class="card-header">
                            <span><i class="fa-solid fa-truck-ramp-box mr-2 text-primary"></i>Steadfast Courier</span>
                        </div>
                        <div class="card-body">
                            @if ($order->consignment_id == null)
                                <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.4;">Book order directly with Steadfast courier for shipping with one click.</p>
                                <button class="btn-steadfast-book" id="bookSteadfastBtn" data-id="{{ $order->id }}">
                                    <i class="fa-solid fa-truck"></i> Send to Courier
                                </button>
                            @else
                                <div class="d-flex flex-column" style="gap: 8px; font-size: 0.85rem;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Consignment ID:</span>
                                        <span class="font-weight-bold text-dark d-flex align-items-center" style="gap: 5px;">
                                            {{ $order->consignment_id }}
                                            <a href="javascript:void(0)" onclick="navigator.clipboard.writeText('{{ $order->consignment_id }}'); Swal.fire({toast:true, position:'top', showConfirmButton:false, timer:1500, icon:'success', title:'Consignment ID Copied!'});" class="text-primary"><i class="fa-regular fa-copy"></i></a>
                                        </span>
                                    </div>
                                    @if($order->tracking_code)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Tracking Code:</span>
                                            <span class="font-weight-bold text-dark d-flex align-items-center" style="gap: 5px;">
                                                {{ $order->tracking_code }}
                                                <a href="javascript:void(0)" onclick="navigator.clipboard.writeText('{{ $order->tracking_code }}'); Swal.fire({toast:true, position:'top', showConfirmButton:false, timer:1500, icon:'success', title:'Tracking Code Copied!'});" class="text-primary"><i class="fa-regular fa-copy"></i></a>
                                            </span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Courier Status:</span>
                                        <span class="badge badge-info px-2 py-1 text-uppercase">{{ $order->delivery_status }}</span>
                                    </div>
                                    <div class="border-top pt-3 mt-2 d-flex" style="gap: 8px;">
                                        <button type="button" class="btn btn-sm btn-primary w-100 font-weight-bold text-center track-courier-btn" data-id="{{ $order->id }}"><i class="fa-solid fa-magnifying-glass mr-1"></i> Track Consignment</button>
                                        <a href="https://steadfast.com.bd/" target="_blank" class="btn btn-sm btn-outline-secondary font-weight-bold text-center" style="width: 50px;" title="Steadfast Website"><i class="fa-solid fa-up-right-from-square"></i></a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Order Status Controls -->
                <div class="premium-card">
                    <div class="card-header">
                        <span><i class="fa-solid fa-arrows-spin mr-2 text-primary"></i>Update Status</span>
                    </div>
                    <div class="card-body">
                        <div class="status-grid" id="status-container-{{ $order->id }}">
                            @if ($order->delivery_status == 'new')
                                <button class="status-btn-option btn-option-pending update-status" data-id="{{ $order->id }}" data-status="pending"><i class="fa-solid fa-hourglass-half"></i> Pending</button>
                                <button class="status-btn-option btn-option-approved update-status" data-id="{{ $order->id }}" data-status="approved"><i class="fa-solid fa-circle-check"></i> Approve</button>
                                <button class="status-btn-option btn-option-packaging update-status" data-id="{{ $order->id }}" data-status="packaging"><i class="fa-solid fa-box"></i> Packaging</button>
                                <button class="status-btn-option btn-option-cancel update-status" data-id="{{ $order->id }}" data-status="cancel"><i class="fa-solid fa-circle-xmark"></i> Cancel</button>
                            @elseif ($order->delivery_status == 'pending')
                                <button class="status-btn-option btn-option-new update-status" data-id="{{ $order->id }}" data-status="new"><i class="fa-solid fa-clock"></i> Hold/New</button>
                                <button class="status-btn-option btn-option-approved update-status" data-id="{{ $order->id }}" data-status="approved"><i class="fa-solid fa-circle-check"></i> Approve</button>
                                <button class="status-btn-option btn-option-packaging update-status" data-id="{{ $order->id }}" data-status="packaging"><i class="fa-solid fa-box"></i> Packaging</button>
                                <button class="status-btn-option btn-option-cancel update-status" data-id="{{ $order->id }}" data-status="cancel"><i class="fa-solid fa-circle-xmark"></i> Cancel</button>
                            @elseif ($order->delivery_status == 'approved')
                                <button class="status-btn-option btn-option-pending update-status" data-id="{{ $order->id }}" data-status="pending"><i class="fa-solid fa-hourglass-half"></i> Pending</button>
                                <button class="status-btn-option btn-option-packaging update-status" data-id="{{ $order->id }}" data-status="packaging"><i class="fa-solid fa-box"></i> Packaging</button>
                                <button class="status-btn-option btn-option-cancel update-status" data-id="{{ $order->id }}" data-status="cancel"><i class="fa-solid fa-circle-xmark"></i> Cancel</button>
                            @elseif ($order->delivery_status == 'packaging')
                                <button class="status-btn-option btn-option-pending update-status" data-id="{{ $order->id }}" data-status="pending"><i class="fa-solid fa-hourglass-half"></i> Pending</button>
                                <button class="status-btn-option btn-option-incourier update-status" data-id="{{ $order->id }}" data-status="incourier"><i class="fa-solid fa-truck"></i> In Courier</button>
                                <button class="status-btn-option btn-option-cancel update-status" data-id="{{ $order->id }}" data-status="cancel"><i class="fa-solid fa-circle-xmark"></i> Cancel</button>
                            @elseif ($order->delivery_status == 'cancelled' || $order->delivery_status == 'cancel')
                                <button class="status-btn-option btn-option-pending update-status" data-id="{{ $order->id }}" data-status="pending"><i class="fa-solid fa-hourglass-half"></i> Pending</button>
                                <button class="status-btn-option btn-option-incourier update-status" data-id="{{ $order->id }}" data-status="incourier"><i class="fa-solid fa-truck"></i> In Courier</button>
                                <button class="status-btn-option btn-option-packaging update-status" data-id="{{ $order->id }}" data-status="packaging"><i class="fa-solid fa-box"></i> Packaging</button>
                            @elseif ($order->delivery_status == 'returned')
                                <button class="status-btn-option btn-option-packaging update-status" data-id="{{ $order->id }}" data-status="packaging"><i class="fa-solid fa-box"></i> Packaging</button>
                            @else
                                <button class="status-btn-option btn-option-pending update-status" data-id="{{ $order->id }}" data-status="pending"><i class="fa-solid fa-hourglass-half"></i> Pending</button>
                                <button class="status-btn-option btn-option-approved update-status" data-id="{{ $order->id }}" data-status="approved"><i class="fa-solid fa-circle-check"></i> Approved</button>
                                <button class="status-btn-option btn-option-packaging update-status" data-id="{{ $order->id }}" data-status="packaging"><i class="fa-solid fa-box"></i> Packaging</button>
                                <button class="status-btn-option btn-option-cancel update-status" data-id="{{ $order->id }}" data-status="cancel"><i class="fa-solid fa-circle-xmark"></i> Cancel</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Status Update AJAX Script -->
    <script>
        $(document).on('click', '.update-status', function(e) {
            e.preventDefault();

            let btn = $(this);
            let orderId = btn.data('id');
            let status = btn.data('status');

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...');

            $.ajax({
                url: "{{ route('order.update.status') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: orderId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        Swal.mixin({
                            toast: true,
                            position: "top",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        }).fire({
                            icon: "success",
                            title: "Status updated to " + response.status_text + "!"
                        });
                        
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: response.message || 'Could not update status.'
                        });
                        btn.prop('disabled', false).html(status.toUpperCase());
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong while updating status.'
                    });
                    btn.prop('disabled', false).html(status.toUpperCase());
                }
            });
        });
    </script>

    <!-- Steadfast Booking AJAX Script -->
    <script>
        $(document).on('click', '#bookSteadfastBtn', function(e) {
            e.preventDefault();
            let btn = $(this);
            let orderId = btn.data('id');

            Swal.fire({
                title: 'Send to Steadfast?',
                text: "Are you sure you want to book this order on Steadfast Courier?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Send it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Sending...');
                    
                    $.ajax({
                        url: "{{ route('entry.steadfast', $order->id) }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Successfully Sent to Courier!',
                                    text: 'Consignment ID: ' + response.consignment_id,
                                    confirmButtonText: 'Great!'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Booking Failed',
                                    text: response.message || 'Courier API returned error'
                                });
                                btn.prop('disabled', false).html('<i class="fa-solid fa-truck"></i> Send to Courier');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to communicate with courier API.'
                            });
                            btn.prop('disabled', false).html('<i class="fa-solid fa-truck"></i> Send to Courier');
                        }
                    });
                }
            });
        });

        // Refresh Courier History AJAX handler
        $(document).on('click', '#refreshCourierHistoryBtn', function(e) {
            e.preventDefault();
            let btn = $(this);
            let orderId = btn.data('id');

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Syncing...');

            $.ajax({
                url: "{{ route('admin.orders.refresh-courier-history', $order->id) }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2000,
                            icon: 'success',
                            title: response.message
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sync Failed',
                            text: response.message || 'API returned an error.'
                        });
                        btn.prop('disabled', false).html('<i class="fa-solid fa-arrows-rotate"></i> Sync');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to communicate with the sync endpoint.'
                    });
                    btn.prop('disabled', false).html('<i class="fa-solid fa-arrows-rotate"></i> Sync');
                }
            });
        });
    </script>

    <!-- Navigation and Tools Scripts -->
    <script>
        function copyNumber() {
            let phone = document.getElementById("phoneNumber").value;

            navigator.clipboard.writeText(phone).then(function() {
                Swal.mixin({
                    toast: true,
                    position: "top",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                }).fire({
                    icon: "success",
                    title: "Number Copied Successfully"
                });
            });
        }

        $(document).ready(function() {
            // Back Button Click
            $('.btn-back').on('click', function() {
                window.location.href = "{{ route('order-index') }}";
            });

            // Print Button Click
            $('.btn-print').on('click', function() {
                let printWindow = window.open("{{ route('order-invoice', $order->id) }}", '_blank');
                if (printWindow) {
                    printWindow.onload = function() {
                        printWindow.print();
                    };
                } else {
                    Swal.fire('Popup Blocked', 'Please allow popups to print the invoice.', 'warning');
                }
            });
        });
    </script>
@endsection
