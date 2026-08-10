@extends('layouts.Frontend.master')
@section('title', 'ORDER SUCCESS')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    
    .success-body {
        font-family: 'Outfit', sans-serif;
        background: #f8fafc;
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
    }
    
    .success-card-custom {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 40px -15px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.02);
        border: 1px solid rgba(226, 232, 240, 0.8);
        padding: 50px 40px;
        max-width: 600px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .success-card-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #10b981, #34d399);
    }
    
    .success-checkmark {
        width: 80px;
        height: 80px;
        margin: 0 auto 30px;
        background: #ecfdf5;
        color: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        position: relative;
        animation: scaleUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
    }
    
    .success-checkmark::after {
        content: '';
        position: absolute;
        top: -8px;
        left: -8px;
        right: -8px;
        bottom: -8px;
        border: 2px solid #a7f3d0;
        border-radius: 50%;
        opacity: 0;
        animation: pulseCheckmark 2s infinite;
    }
    
    .success-title {
        color: #1e293b;
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 12px;
        line-height: 1.4;
    }
    
    .success-subtitle {
        color: #64748b;
        font-size: 15px;
        margin-bottom: 35px;
        line-height: 1.5;
    }
    
    .order-details-box {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 24px;
        text-align: left;
        margin-bottom: 35px;
    }
    
    .details-title {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #94a3b8;
        margin-bottom: 16px;
        border-bottom: 1px dashed #e2e8f0;
        padding-bottom: 8px;
    }
    
    .details-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14.5px;
        color: #334155;
    }
    
    .details-row:last-child {
        margin-bottom: 0;
        font-weight: 600;
        border-top: 1px solid #e2e8f0;
        padding-top: 12px;
        margin-top: 12px;
    }
    
    .details-label {
        color: #64748b;
    }
    
    .details-value {
        font-weight: 600;
        color: #1e293b;
    }
    
    .order-id-badge {
        background: #e0f2fe;
        color: #0284c7;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 13.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .order-id-badge:hover {
        background: #bae6fd;
    }
    
    .btn-success-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-bottom: 12px;
        text-decoration: none !important;
    }
    
    .btn-success-primary {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    
    .btn-success-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
    }
    
    .btn-success-secondary {
        background: #f1f5f9;
        color: #475569 !important;
    }
    
    .btn-success-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }
    
    .button-group-custom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    
    @media (max-width: 576px) {
        .success-card-custom {
            padding: 35px 20px;
        }
        .button-group-custom {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }
    
    @keyframes scaleUp {
        0% { transform: scale(0.6); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    @keyframes pulseCheckmark {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(1.3); opacity: 0; }
    }

    .order-items-box {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 24px;
        text-align: left;
        margin-bottom: 24px;
    }
    
    .success-product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .success-product-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .success-product-item:first-of-type {
        padding-top: 0;
    }
    
    .success-item-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }
    
    .success-item-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        flex-shrink: 0;
    }

    .success-item-img-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #94a3b8;
        flex-shrink: 0;
    }
    
    .success-item-details {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    
    .success-item-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .success-item-qty-price {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }
    
    .success-item-right {
        text-align: right;
        margin-left: 12px;
        flex-shrink: 0;
    }
    
    .success-item-total {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
    }
</style>

<div class="success-body">
    <div class="success-card-custom">
        <div class="success-checkmark">
            <i class="las la-check"></i>
        </div>
        
        <h2 class="success-title">অর্ডার সফলভাবে সম্পন্ন হয়েছে!</h2>
        <p class="success-subtitle">অভিনন্দন! আপনার অর্ডারটি সফলভাবে গৃহীত হয়েছে। খুব শীঘ্রই আমাদের একজন প্রতিনিধি আপনার সাথে যোগাযোগ করবেন।</p>
        
        <!-- Product Items -->
        <div class="order-items-box">
            <div class="details-title">Ordered Items</div>
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
                <div class="success-product-item">
                    <div class="success-item-left">
                        @if(!empty($firstImage))
                            <img class="success-item-img" src="{{ asset('Uploads/' . $firstImage) }}" alt="Product Image">
                        @else
                            <div class="success-item-img-placeholder">
                                <i class="las la-image"></i>
                            </div>
                        @endif
                        <div class="success-item-details">
                            <span class="success-item-title" title="{{ $detail->orderProduct->title ?? 'N/A' }}">{{ $detail->orderProduct->title ?? 'N/A' }}</span>
                            <span class="success-item-qty-price">{{ $detail->product_qty }} x {{ single_price($unitPrice) }}</span>
                        </div>
                    </div>
                    <div class="success-item-right">
                        <span class="success-item-total">{{ single_price($totalPrice) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="order-details-box">
            <div class="details-title">Order Information</div>
            
            <div class="details-row">
                <span class="details-label">অর্ডার আইডি</span>
                <span class="details-value">
                    <span class="order-id-badge" onclick="copyOrderId('{{ $order->id }}')" title="ক্লিক করে আইডি কপি করুন">
                        #{{ $order->id }} <i class="las la-copy"></i>
                    </span>
                </span>
            </div>
            
            <div class="details-row">
                <span class="details-label">গ্রাহকের নাম</span>
                <span class="details-value">{{ $order->name }}</span>
            </div>
            
            <div class="details-row">
                <span class="details-label">মোবাইল নম্বর</span>
                <span class="details-value">{{ $order->phone }}</span>
            </div>
            
            <div class="details-row">
                <span class="details-label">পেমেন্ট মেথড</span>
                <span class="details-value">{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</span>
            </div>
            
            <div class="details-row">
                <span class="details-label">ডেলিভারি ঠিকানা</span>
                <span class="details-value text-right" style="max-width: 250px;">{{ $order->address }}</span>
            </div>
            
            <div class="details-row" style="border-top: 1px dashed #e2e8f0; padding-top: 12px; margin-top: 12px;">
                <span class="details-label">সাবটোটাল</span>
                <span class="details-value">{{ single_price($order->total_amount) }}</span>
            </div>
            
            <div class="details-row">
                <span class="details-label">ডেলিভারি চার্জ</span>
                <span class="details-value">{{ single_price($order->delivery_charge) }}</span>
            </div>
            
            @if(($order->coupon_discount ?? 0) > 0)
            <div class="details-row">
                <span class="details-label">ডিসকাউন্ট</span>
                <span class="details-value text-danger">- {{ single_price($order->coupon_discount) }}</span>
            </div>
            @endif
            
            <div class="details-row" style="border-top: 1px solid #e2e8f0; padding-top: 12px; font-weight: 700;">
                <span class="details-label" style="color: #0f172a; font-weight: 700;">মোট বিল</span>
                <span class="details-value text-success" style="font-size: 18px;">{{ single_price($order->grand_total) }}</span>
            </div>
        </div>
        
        <div class="button-group-custom">
            <a href="{{ url('/') }}" class="btn-success-action btn-success-primary">
                <i class="las la-shopping-bag"></i> আরও কেনাকাটা করুন
            </a>
            <button onclick="printInvoice('{{ route('order.print', $order->id) }}')" class="btn-success-action btn-success-secondary">
                <i class="las la-print"></i> ইনভয়েস প্রিন্ট করুন
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function printInvoice(url) {
        window.open(url, '_blank', 'height=600,width=800');
    }

    function copyOrderId(orderId) {
        navigator.clipboard.writeText(orderId).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'অর্ডার আইডি কপি করা হয়েছে!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    }
</script>
@if(session('order_placed'))
<script>
    Swal.fire({
        title: 'অর্ডার সফল!',
        text: 'আপনার অর্ডারটি আমরা পেয়েছি।',
        icon: 'success',
        confirmButtonText: 'ঠিক আছে',
        confirmButtonColor: '#10b981'
    });
</script>
@endif
@endsection

@section('script')
@php
    $itemsArr = [];
    $productNames = [];
    $productIds = [];
    if (!empty($order->orderDetails)) {
        foreach($order->orderDetails as $detail) {
            $pTitle = $detail->orderProduct->title ?? ('Product #' . $detail->product_id);
            $productNames[] = $pTitle;
            $productIds[] = (string) $detail->product_id;
            $uPrice = $detail->unit_price > 0 ? $detail->unit_price : ($detail->orderProduct->new_price ?? 0);
            $itemsArr[] = [
                'item_id' => (string) $detail->product_id,
                'item_name' => $pTitle,
                'price' => (float) $uPrice,
                'quantity' => (int) $detail->product_qty
            ];
        }
    }
    $productNamesStr = implode(', ', $productNames);
    $purchaseEventId = 'purchase_' . ($order->id ?? time());
@endphp
<script>
document.addEventListener("DOMContentLoaded", function () {
    var eventId = '{{ $purchaseEventId }}';
    var orderId = '{{ $order->id ?? "" }}';
    var totalValue = {{ (float) ($order->grand_total ?? 0) }};

    // 1. Google Tag Manager (DataLayer) Event
    try {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });
        window.dataLayer.push({
            'event': 'purchase',
            'event_id': eventId,
            'order_id': orderId,
            'ecommerce': {
                'transaction_id': orderId,
                'value': totalValue,
                'tax': 0,
                'shipping': {{ (float) ($order->delivery_charge ?? 0) }},
                'currency': 'BDT',
                'items': {!! json_encode($itemsArr) !!}
            }
        });
    } catch (e) {
        console.error("GTM Purchase Error:", e);
    }

    // 2. Direct Meta Pixel Event
    try {
        if (typeof window.fbq === 'function') {
            window.fbq('setUserProperties', {
                'ph': '{{ preg_replace("/[^0-9]/", "", $order->phone ?? "") }}',
                'fn': {!! json_encode(strtolower(trim($order->name ?? ""))) !!},
                'st': 'BD'
            });

            window.fbq('track', 'Purchase', {
                content_type: 'product',
                content_name: {!! json_encode($productNamesStr) !!},
                content_ids: {!! json_encode($productIds) !!},
                value: totalValue,
                currency: 'BDT',
                order_id: orderId
            }, {
                eventID: eventId
            });
        }
    } catch (e) {
        console.error("Meta Pixel Purchase Error:", e);
    }
});
</script>
@endsection

