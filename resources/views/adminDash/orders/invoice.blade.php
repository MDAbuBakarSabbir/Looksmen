@extends('layouts.adminLays.master')

@section('title')
    INVOICE #LM-{{ $order->id }}
@endsection

@section('content')
    @php
        $font_family = 'Inter';
        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
    @endphp

    <!-- Premium Invoice Styles -->
    <style>
        /* Invoice Container Preview Mode */
        .invoice-container {
            max-width: 800px;
            margin: 2rem auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(148, 163, 184, 0.12), 0 4px 12px rgba(148, 163, 184, 0.04);
            border: 1px solid #e2e8f0;
            padding: 3rem;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            position: relative;
        }

        /* Header block styling */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1.75rem;
            margin-bottom: 2.25rem;
        }

        .invoice-brand-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e1b4b;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }

        .invoice-brand-details {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.5;
        }

        .invoice-meta-side {
            text-align: right;
        }

        .invoice-main-title {
            font-size: 2rem;
            font-weight: 900;
            color: #312e81;
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .invoice-meta-info {
            font-size: 0.85rem;
            color: #334155;
            line-height: 1.5;
        }

        /* Bill To / Details Grid */
        .invoice-details-row {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .details-col {
            flex: 1;
        }

        .details-title {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #475569;
            letter-spacing: 1px;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.25rem;
        }

        .details-content {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #334155;
        }

        /* Items Listing Table */
        .invoice-items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2.5rem;
        }

        .invoice-items-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.85rem 1.25rem;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        .invoice-items-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
            vertical-align: middle;
        }

        .invoice-items-table th.text-right, .invoice-items-table td.text-right {
            text-align: right;
        }

        .invoice-items-table th.text-center, .invoice-items-table td.text-center {
            text-align: center;
        }

        .item-row-spec {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.2rem;
        }

        /* Summary Calculations section */
        .invoice-footer-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .invoice-notes-box {
            flex: 1.2;
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .invoice-summary-box {
            flex: 0.8;
        }

        .summary-calculation-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-calculation-table th, .summary-calculation-table td {
            padding: 0.45rem 0.5rem;
            font-size: 0.9rem;
        }

        .summary-calculation-table th {
            text-align: left;
            color: #475569;
            font-weight: 500;
        }

        .summary-calculation-table td {
            text-align: right;
            font-weight: 700;
            color: #0f172a;
        }

        .summary-calculation-table tr.total-row {
            border-top: 2px solid #e2e8f0;
        }

        .summary-calculation-table tr.total-row th,
        .summary-calculation-table tr.total-row td {
            padding-top: 0.75rem;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .summary-calculation-table tr.total-row td {
            color: #312e81;
        }

        .summary-calculation-table tr.due-highlight-row {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
        }

        .summary-calculation-table tr.due-highlight-row th,
        .summary-calculation-table tr.due-highlight-row td {
            font-size: 1.1rem;
            font-weight: 800;
            padding: 0.6rem 0.5rem;
        }

        .summary-calculation-table tr.due-highlight-row th {
            color: #991b1b;
        }

        .summary-calculation-table tr.due-highlight-row td {
            color: #b91c1c;
        }

        /* Print Media Stylesheet */
        @media print {
            /* Hide admin navigation sidebar, top navbar, site footer, alerts, etc. */
            .quixnav,
            .header,
            .nav-header,
            .footer,
            #preloader {
                display: none !important;
            }
            
            /* Remove main frame wrappers padding & margins */
            .content-body {
                margin-left: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
            }
            
            .container-fluid {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            
            body, html {
                background: #ffffff !important;
                color: #000000 !important;
                height: auto !important;
                overflow: visible !important;
                font-size: 10pt !important;
            }
            
            .invoice-container {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .invoice-header {
                border-bottom: 2px solid #000000 !important;
            }
            
            .invoice-items-table th {
                background: #f1f5f9 !important;
                border-bottom: 2px solid #000000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .invoice-notes-box {
                border: 1px solid #cbd5e1 !important;
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .summary-calculation-table tr.due-highlight-row {
                background-color: #fee2e2 !important;
                border: 1px solid #fca5a5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    <div class="invoice-container">
        <!-- Invoice Branding and Meta Section -->
        <div class="invoice-header">
            <div>
                <div class="invoice-brand-title">LOOKSMEN.COM</div>
                <div class="invoice-brand-details">
                    Mirpur, Dhaka, Bangladesh<br>
                    Email: info@looksmen.com<br>
                    Phone: +8801886-657788
                </div>
            </div>
            
            <div class="invoice-meta-side">
                <div class="invoice-main-title">INVOICE</div>
                <div class="invoice-meta-info">
                    Order ID: <strong>LM-{{ $order->id }}</strong><br>
                    Order Date: <strong>{{ $order->created_at->format('d-m-Y') }}</strong><br>
                    Payment Status: <span class="badge badge-success text-uppercase">Paid / COD</span>
                </div>
            </div>
        </div>

        <!-- Bill To details section -->
        <div class="invoice-details-row">
            <div class="details-col">
                <div class="details-title">Bill To:</div>
                <div class="details-content">
                    <strong>{{ $order->name }}</strong><br>
                    Phone: {{ $order->phone }}<br>
                    Address: {{ $order->address }}
                </div>
            </div>
            <div class="details-col">
                <div class="details-title">Shipping Method:</div>
                <div class="details-content">
                    Standard Delivery (Cash on Delivery)<br>
                    @if ($order->consignment_id)
                        Consignment ID: <strong>{{ $order->consignment_id }}</strong>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products table list -->
        <table class="invoice-items-table">
            <thead>
                <tr>
                    <th style="width: 8%;">SL</th>
                    <th style="width: 52%;">Product Name</th>
                    <th class="text-center" style="width: 12%;">Qty</th>
                    <th class="text-right" style="width: 14%;">Unit Price</th>
                    <th class="text-right" style="width: 14%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $orderDetail)
                    @if ($orderDetail->orderProduct != null)
                        <tr>
                            <td class="font-weight-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div><strong>{{ $orderDetail->orderProduct->title }}</strong></div>
                                <div class="item-row-spec">
                                    @if($orderDetail->product_attribute)
                                        <span class="mr-2">Size: {{ $orderDetail->product_attribute }}</span>
                                    @endif
                                    @if($orderDetail->product_colour)
                                        <span>Color: {{ $orderDetail->product_colour }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center font-weight-bold">{{ $orderDetail->product_qty }}</td>
                            <td class="text-right font-weight-bold">{{ number_format($orderDetail->unit_price, 2) }} ৳</td>
                            <td class="text-right font-weight-bold text-dark">{{ number_format($orderDetail->total_price, 2) }} ৳</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Calculations and breakdown section -->
        <div class="invoice-footer-row">
            <div class="invoice-notes-box">
                <div class="font-weight-bold text-dark mb-2" style="font-size: 0.85rem; text-transform: uppercase;">Terms & Instructions:</div>
                <p class="mb-0">
                    1. Please inspect the items upon delivery before payment.<br>
                    2. If you notice any damage or sizing issues, please report it to our customer support immediately.<br>
                    3. For tracking updates, refer to Looksmen tracking page or Steadfast courier dashboard.<br>
                    Thank you for shopping with us!
                </p>
            </div>
            
            <div class="invoice-summary-box">
                <table class="summary-calculation-table">
                    <tbody>
                        <tr>
                            <th>Sub Total</th>
                            <td>{{ number_format($order->total_amount, 2) }} ৳</td>
                        </tr>
                        @if($order->admin_discount > 0)
                            <tr class="text-danger">
                                <th>Admin Discount</th>
                                <td>- {{ number_format($order->admin_discount, 2) }} ৳</td>
                            </tr>
                        @endif
                        @if($order->coupon_discount > 0)
                            <tr class="text-danger">
                                <th>Coupon Discount</th>
                                <td>- {{ number_format($order->coupon_discount, 2) }} ৳</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Shipping Cost</th>
                            <td>{{ number_format($order->delivery_charge ?? 0, 2) }} ৳</td>
                        </tr>
                        <tr class="total-row">
                            <th>Grand Total</th>
                            <td>{{ number_format($order->total_amount - $order->admin_discount - $order->coupon_discount + ($order->delivery_charge ?? 0), 2) }} ৳</td>
                        </tr>
                        <tr class="text-success">
                            <th>Paid Amount</th>
                            <td>{{ number_format($order->paid_amount, 2) }} ৳</td>
                        </tr>
                        
                        @if ($order->grand_total > 0)
                            <tr class="due-highlight-row">
                                <th>Due / COD</th>
                                <td>{{ number_format($order->grand_total, 2) }} ৳</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2" class="text-center text-success font-weight-bold py-2">
                                    <i class="fa-solid fa-circle-check"></i> FULLY PAID
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
