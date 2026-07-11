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
</style>

<div class="success-body">
    <div class="success-card-custom">
        <div class="success-checkmark">
            <i class="las la-check"></i>
        </div>
        
        <h2 class="success-title">অর্ডার সফলভাবে সম্পন্ন হয়েছে!</h2>
        <p class="success-subtitle">অভিনন্দন! আপনার অর্ডারটি সফলভাবে গৃহীত হয়েছে। খুব শীঘ্রই আমাদের একজন প্রতিনিধি আপনার সাথে যোগাযোগ করবেন।</p>
        
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
            
            <div class="details-row">
                <span class="details-label">সর্বমোট মূল্য</span>
                <span class="details-value text-success" style="font-size: 16px;">{{ single_price($order->grand_total) }}</span>
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

