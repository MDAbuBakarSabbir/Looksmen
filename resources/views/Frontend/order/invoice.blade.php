<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - #{{ $order->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Outfit', sans-serif;
            color: #334155;
            margin: 0;
            padding: 40px;
            background: #ffffff;
            -webkit-print-color-adjust: exact;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 25px;
            margin-bottom: 30px;
        }
        
        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .brand-logo {
            max-height: 50px;
            width: auto;
        }
        
        .brand-name {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 8px 0;
            letter-spacing: 1px;
        }
        
        .meta-item {
            font-size: 14px;
            color: #64748b;
            margin: 4px 0;
        }
        
        .meta-item strong {
            color: #334155;
        }
        
        .addresses-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .address-box {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 20px;
        }
        
        .address-box-title {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 12px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 6px;
        }
        
        .address-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        
        .address-detail {
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .items-table td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14.5px;
            color: #334155;
            vertical-align: middle;
        }
        
        .product-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .product-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        
        .product-title {
            font-weight: 600;
            color: #1e293b;
        }
        
        .totals-container {
            display: flex;
            justify-content: flex-end;
        }
        
        .totals-box {
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14.5px;
            color: #475569;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #e2e8f0;
            margin-top: 8px;
            padding-top: 14px;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        
        .footer-note {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            margin-top: 60px;
            border-top: 1px dashed #e2e8f0;
            padding-top: 20px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .addresses-section {
                display: table;
                width: 100%;
            }
            .address-box {
                display: table-cell;
                width: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="brand-logo-container">
                @if(!empty($webConfig['web_logo']))
                    <img class="brand-logo" src="{{ asset('adminDash/assets/img/layouts') }}/{{ $webConfig['web_logo'] }}" alt="{{ $webConfig['web_name'] ?? 'Logo' }}">
                @else
                    <span class="brand-name">{{ $webConfig['web_name'] ?? 'Looksmen' }}</span>
                @endif
            </div>
            <div class="invoice-meta">
                <h1 class="invoice-title">INVOICE</h1>
                <div class="meta-item">অর্ডার আইডি: <strong>#{{ $order->id }}</strong></div>
                <div class="meta-item">তারিখ: <strong>{{ $order->created_at->format('d M, Y') }}</strong></div>
            </div>
        </div>
        
        <!-- Addresses -->
        <div class="addresses-section">
            <!-- Billing Details -->
            <div class="address-box">
                <div class="address-box-title">বিলের ঠিকানা</div>
                <div class="address-name">{{ $order->name }}</div>
                <div class="address-detail">
                    মোবাইল: {{ $order->phone }}<br>
                    ঠিকানা: {{ $order->address }}<br>
                    জেলা: {{ $order->district }}
                </div>
            </div>
            
            <!-- Company Details -->
            <div class="address-box">
                <div class="address-box-title">আমাদের তথ্য</div>
                <div class="address-name">{{ $webConfig['web_name'] ?? 'Looksmen.com' }}</div>
                <div class="address-detail">
                    মোবাইল: {{ $webConfig['web_phone'] ?? 'N/A' }}<br>
                    ইমেইল: {{ $webConfig['web_email'] ?? 'N/A' }}<br>
                    ঠিকানা: {{ $webConfig['web_address'] ?? 'N/A' }}
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>পণ্য (Product)</th>
                    <th style="text-align: center; width: 120px;">মূল্য (Price)</th>
                    <th style="text-align: center; width: 100px;">পরিমাণ (Qty)</th>
                    <th style="text-align: right; width: 120px;">মোট (Total)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderDetails as $detail)
                    @php
                        $firstImage = $detail->orderProduct?->firstImage?->image ?? '';
                        if (empty($firstImage)) {
                            $fallbackImg = \App\Models\ProductImage::where('product_id', $detail->product_id)->first();
                            $firstImage = $fallbackImg ? $fallbackImg->image : '';
                        }
                        
                        $unitPrice = $detail->unit_price > 0 ? $detail->unit_price : ($detail->orderProduct->new_price ?? 0);
                        $totalPrice = $detail->total_price > 0 ? $detail->total_price : ($detail->product_qty * $unitPrice);
                    @endphp
                    <tr>
                        <td>
                            <div class="product-info">
                                @if(!empty($firstImage))
                                    <img class="product-img" src="{{ asset('Uploads/' . $firstImage) }}" alt="Product Image">
                                @endif
                                <span class="product-title">{{ $detail->orderProduct->title ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td style="text-align: center;">{{ single_price($unitPrice) }}</td>
                        <td style="text-align: center;">{{ $detail->product_qty }}</td>
                        <td style="text-align: right;">{{ single_price($totalPrice) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="totals-container">
            <div class="totals-box">
                <div class="total-row">
                    <span>সাবটোটাল</span>
                    <strong>{{ single_price($order->total_amount) }}</strong>
                </div>
                <div class="total-row">
                    <span>ডেলিভারি চার্জ</span>
                    <strong>{{ single_price($order->delivery_charge) }}</strong>
                </div>
                <div class="total-row">
                    <span>ডিসকাউন্ট</span>
                    <strong>- {{ single_price($order->coupon_discount ?? 0) }}</strong>
                </div>
                <div class="total-row grand-total">
                    <span>মোট বিল</span>
                    <strong>{{ single_price($order->grand_total) }}</strong>
                </div>
            </div>
        </div>
        
        <!-- Footer Note -->
        <div class="footer-note">
            আমাদের সাথে থাকার জন্য ধন্যবাদ!
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</body>
</html>
