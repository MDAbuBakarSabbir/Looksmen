@extends('layouts.Backend.master')

@section('title')
    INVOICE #LM-{{ $order->id }}
@endsection

@section('content')
@php
    $siteName    = $webSettings['web_name']        ?? 'LOOKSMEN.COM';
    $siteAddress = $webSettings['contact_address'] ?? 'Mirpur, Dhaka, Bangladesh';
    $sitePhone   = $webSettings['contact_phone']   ?? '+8801886-657788';
    $siteEmail   = $webSettings['contact_email']   ?? 'info@looksmen.com';
    $logoFile    = $webSettings['web_logo']        ?? null;
    $logoUrl     = $logoFile ? asset('adminDash/assets/img/layouts/' . $logoFile) : null;

    $subtotal   = $order->total_amount;
    $adminDisc  = $order->admin_discount  ?? 0;
    $couponDisc = $order->coupon_discount ?? 0;
    $shipping   = $order->delivery_charge ?? 0;
    $grandTotal = $subtotal - $adminDisc - $couponDisc + $shipping;
    $paidAmt    = $order->paid_amount ?? 0;
    $dueAmt     = $order->grand_total ?? 0;

    $statusColors = [
        'hold'       => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Hold'],
        'pending'    => ['bg' => '#fef9c3', 'text' => '#92400e', 'label' => 'Pending'],
        'approved'   => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Approved'],
        'packaging'  => ['bg' => '#ede9fe', 'text' => '#5b21b6', 'label' => 'Packaging'],
        'incourier'  => ['bg' => '#ffedd5', 'text' => '#9a3412', 'label' => 'In Courier'],
        'in_courier' => ['bg' => '#ffedd5', 'text' => '#9a3412', 'label' => 'In Courier'],
        'delivered'  => ['bg' => '#d1fae5', 'text' => '#064e3b', 'label' => 'Delivered'],
        'cancel'     => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Cancelled'],
        'cancelled'  => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Cancelled'],
        'canceled'   => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Cancelled'],
        'returned'   => ['bg' => '#f3f4f6', 'text' => '#374151', 'label' => 'Returned'],
        // backward compat
        'new'        => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Hold'],
    ];
    $statusStyle = $statusColors[$order->delivery_status] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'label' => ucfirst($order->delivery_status)];
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
    .inv-wrap { max-width:860px; margin:2rem auto 3rem; font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; color:#1e293b; }
    .inv-actions { display:flex; justify-content:flex-end; gap:.6rem; margin-bottom:1.25rem; }
    .inv-actions .btn { font-size:.82rem; font-weight:600; letter-spacing:.3px; border-radius:8px; padding:.45rem 1.1rem; }
    .inv-card { background:#fff; border-radius:18px; box-shadow:0 8px 32px rgba(100,116,139,.13),0 2px 8px rgba(100,116,139,.07); border:1px solid #e2e8f0; overflow:hidden; }
    .inv-banner { background:linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#4338ca 100%); padding:2rem 2.5rem 1.75rem; display:flex; align-items:center; justify-content:space-between; gap:1.5rem; position:relative; }
    .inv-banner::after { content:''; position:absolute; bottom:0; left:0; right:0; height:4px; background:linear-gradient(90deg,#f59e0b,#ec4899,#6366f1,#06b6d4); }
    .inv-brand { display:flex; align-items:center; gap:1rem; }
    .inv-logo-img { height:52px; max-width:160px; object-fit:contain; filter:brightness(0) invert(1); border-radius:6px; }
    .inv-logo-text { font-size:1.55rem; font-weight:900; color:#fff; letter-spacing:-0.5px; line-height:1; }
    .inv-logo-tagline { font-size:.72rem; color:rgba(255,255,255,.65); margin-top:.3rem; letter-spacing:.5px; }
    .inv-title-block { text-align:right; }
    .inv-title-label { font-size:2.4rem; font-weight:900; color:#fff; letter-spacing:4px; text-transform:uppercase; opacity:.92; line-height:1; }
    .inv-order-id { display:inline-block; margin-top:.5rem; font-size:.8rem; font-weight:700; color:rgba(255,255,255,.85); background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.22); padding:.3rem .8rem; border-radius:6px; letter-spacing:1px; }
    .inv-meta-strip { background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1rem 2.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.6rem; }
    .inv-meta-item { display:flex; align-items:center; gap:.4rem; font-size:.8rem; color:#475569; }
    .inv-meta-item strong { color:#1e293b; font-weight:700; }
    .inv-meta-item i { color:#6366f1; font-size:.85rem; }
    .inv-body { padding:2rem 2.5rem; }
    .inv-contact-row { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem; }
    .inv-contact-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem 1.5rem; }
    .inv-box-label { font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:1.2px; color:#6366f1; margin-bottom:.65rem; display:flex; align-items:center; gap:.4rem; }
    .inv-box-label::after { content:''; flex:1; height:1px; background:#e2e8f0; }
    .inv-box-content { font-size:.875rem; line-height:1.7; color:#334155; }
    .inv-box-content strong { color:#0f172a; font-weight:700; }
    .inv-divider { border:none; border-top:1.5px solid #f1f5f9; margin:0 0 2rem; }
    .inv-table { width:100%; border-collapse:collapse; margin-bottom:2rem; }
    .inv-table thead tr { background:linear-gradient(90deg,#1e1b4b,#4338ca); }
    .inv-table thead th { color:#fff; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.7px; padding:.85rem 1.1rem; }
    .inv-table thead th:first-child { border-radius:8px 0 0 8px; }
    .inv-table thead th:last-child  { border-radius:0 8px 8px 0; }
    .inv-table tbody tr { border-bottom:1px solid #f1f5f9; }
    .inv-table tbody tr:last-child { border-bottom:none; }
    .inv-table td { padding:.95rem 1.1rem; font-size:.875rem; color:#334155; vertical-align:middle; }
    .inv-table td.sl-col { font-size:.75rem; font-weight:700; color:#94a3b8; width:48px; }
    .inv-table .product-title { font-weight:700; color:#1e293b; }
    .inv-table .product-spec { font-size:.72rem; color:#64748b; margin-top:.15rem; }
    .inv-table .product-spec span { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:4px; padding:.1rem .4rem; margin-right:.3rem; }
    .text-r { text-align:right; } .text-c { text-align:center; } .fw7 { font-weight:700; }
    .inv-footer-grid { display:grid; grid-template-columns:1.3fr .9fr; gap:1.75rem; align-items:start; }
    .inv-notes { background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:1.25rem 1.4rem; font-size:.82rem; line-height:1.7; color:#78350f; }
    .inv-notes-title { font-weight:800; font-size:.75rem; text-transform:uppercase; letter-spacing:.8px; color:#92400e; margin-bottom:.65rem; display:flex; align-items:center; gap:.4rem; }
    .inv-summary { border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
    .inv-summary table { width:100%; border-collapse:collapse; }
    .inv-summary table tr { border-bottom:1px solid #f1f5f9; }
    .inv-summary table tr:last-child { border-bottom:none; }
    .inv-summary table th { padding:.65rem 1.1rem; font-size:.82rem; font-weight:500; color:#475569; text-align:left; }
    .inv-summary table td { padding:.65rem 1.1rem; font-size:.85rem; font-weight:700; color:#1e293b; text-align:right; }
    .inv-summary .row-grand { background:#1e1b4b; }
    .inv-summary .row-grand th, .inv-summary .row-grand td { color:#fff; font-size:.95rem; font-weight:800; padding:.85rem 1.1rem; }
    .inv-summary .row-paid th { color:#047857; }
    .inv-summary .row-paid td { color:#059669; }
    .inv-summary .row-due { background:#fef2f2; }
    .inv-summary .row-due th { color:#991b1b; font-weight:700; }
    .inv-summary .row-due td { color:#b91c1c; font-weight:800; }
    .inv-summary .row-disc th, .inv-summary .row-disc td { color:#dc2626; }
    .inv-fully-paid { background:#d1fae5; padding:.75rem 1rem; text-align:center; font-size:.85rem; font-weight:800; color:#064e3b; display:flex; align-items:center; justify-content:center; gap:.4rem; }
    .inv-stamp-row { margin-top:2rem; border-top:1px solid #f1f5f9; padding-top:1.25rem; display:flex; justify-content:space-between; align-items:flex-end; }
    .inv-website-info { font-size:.75rem; color:#94a3b8; line-height:1.6; }
    .inv-website-info strong { color:#475569; }
    .inv-signature-box { text-align:center; font-size:.75rem; color:#94a3b8; }
    .inv-signature-line { width:160px; border-top:2px solid #cbd5e1; margin:0 auto .4rem; }
    @media print {
        .quixnav,.header,.nav-header,.footer,#preloader,.inv-actions { display:none !important; }
        .content-body { margin-left:0 !important; padding:0 !important; background:#fff !important; }
        .container-fluid { padding:0 !important; margin:0 !important; }
        .inv-wrap { margin:0 !important; max-width:100% !important; }
        .inv-card { border:none !important; box-shadow:none !important; border-radius:0 !important; }
        .inv-banner { background:#1e1b4b !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        .inv-table thead tr { background:#1e1b4b !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        .inv-summary .row-grand { background:#1e1b4b !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        .inv-notes { background:#fffbeb !important; border:1px solid #fde68a !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        .inv-logo-img { filter:none !important; }
        @page { margin:1cm; }
    }
</style>

<div class="inv-wrap">
    <div class="inv-actions">
        <a href="{{ route('admin.order-show', $order->id) }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Order
        </a>
        <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="fa-solid fa-print mr-1"></i> Print Invoice
        </button>
    </div>

    <div class="inv-card">
        <div class="inv-banner">
            <div class="inv-brand">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="inv-logo-img">
                @else
                    <div>
                        <div class="inv-logo-text">{{ $siteName }}</div>
                        <div class="inv-logo-tagline">Fashion &amp; Lifestyle</div>
                    </div>
                @endif
            </div>
            <div class="inv-title-block">
                <div class="inv-title-label">Invoice</div>
                <div class="inv-order-id">#LM-{{ $order->id }}</div>
            </div>
        </div>

        <div class="inv-meta-strip">
            <div class="inv-meta-item">
                <i class="fa-solid fa-calendar-days"></i>
                Issue Date: <strong>{{ $order->created_at->format('d M, Y') }}</strong>
            </div>
            <div class="inv-meta-item">
                <i class="fa-solid fa-hashtag"></i>
                Order: <strong>LM-{{ $order->id }}</strong>
            </div>
            <div class="inv-meta-item">
                <i class="fa-solid fa-credit-card"></i>
                Payment: <strong>{{ $order->payment_type ?? 'Cash On Delivery' }}</strong>
            </div>
            <div>
                <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:20px;font-size:.75rem;font-weight:700;letter-spacing:.4px;text-transform:uppercase;background:{{ $statusStyle['bg'] }};color:{{ $statusStyle['text'] }};">
                    <i class="fa-solid fa-circle" style="font-size:.5rem;"></i>
                    {{ $statusStyle['label'] }}
                </span>
            </div>
        </div>

        <div class="inv-body">
            <div class="inv-contact-row">
                <div class="inv-contact-box">
                    <div class="inv-box-label"><i class="fa-solid fa-building"></i> From</div>
                    <div class="inv-box-content">
                        <strong>{{ $siteName }}</strong><br>
                        @if($siteAddress)<i class="fa-solid fa-location-dot" style="color:#6366f1;font-size:.75rem;"></i> {{ $siteAddress }}<br>@endif
                        @if($sitePhone)<i class="fa-solid fa-phone" style="color:#6366f1;font-size:.75rem;"></i> {{ $sitePhone }}<br>@endif
                        @if($siteEmail)<i class="fa-solid fa-envelope" style="color:#6366f1;font-size:.75rem;"></i> {{ $siteEmail }}@endif
                    </div>
                </div>
                <div class="inv-contact-box">
                    <div class="inv-box-label"><i class="fa-solid fa-user"></i> Bill To</div>
                    <div class="inv-box-content">
                        <strong>{{ $order->name }}</strong><br>
                        <i class="fa-solid fa-phone" style="color:#6366f1;font-size:.75rem;"></i> {{ $order->phone }}<br>
                        <i class="fa-solid fa-location-dot" style="color:#6366f1;font-size:.75rem;"></i> {{ $order->address }}{{ $order->district ? ', '.$order->district : '' }}{{ $order->thana ? ', '.$order->thana : '' }}
                        @if($order->consignment_id)<br><i class="fa-solid fa-truck" style="color:#6366f1;font-size:.75rem;"></i> Consignment: <strong>{{ $order->consignment_id }}</strong>@endif
                    </div>
                </div>
            </div>

            <hr class="inv-divider">

            <table class="inv-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th class="text-c">Qty</th>
                        <th class="text-r">Unit Price</th>
                        <th class="text-r">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderDetails as $detail)
                        @if($detail->orderProduct)
                        <tr>
                            <td class="sl-col">{{ $loop->iteration }}</td>
                            <td>
                                <div class="product-title">{{ $detail->orderProduct->title }}</div>
                                @if($detail->product_attribute || $detail->product_colour)
                                    <div class="product-spec">
                                        @if($detail->product_attribute)<span>Size: {{ $detail->product_attribute }}</span>@endif
                                        @if($detail->product_colour)<span>Color: {{ $detail->product_colour }}</span>@endif
                                    </div>
                                @endif
                            </td>
                            <td class="text-c fw7">{{ $detail->product_qty }}</td>
                            <td class="text-r fw7">&#2547; {{ number_format($detail->unit_price, 2) }}</td>
                            <td class="text-r fw7" style="color:#1e1b4b;">&#2547; {{ number_format($detail->total_price, 2) }}</td>
                        </tr>
                        @endif
                    @empty
                        <tr><td colspan="5" class="text-c" style="color:#94a3b8;padding:2rem;">No items found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="inv-footer-grid">
                <div class="inv-notes">
                    <div class="inv-notes-title"><i class="fa-solid fa-circle-info"></i> Terms &amp; Instructions</div>
                    <p class="mb-0">
                        1. Please inspect items upon delivery before payment.<br>
                        2. Report any damage or sizing issues to our support immediately.<br>
                        3. For tracking updates, contact us via phone or email.<br>
                        4. This is a computer-generated invoice. No signature required.<br><br>
                        <strong>Thank you for shopping with {{ $siteName }}! &#127881;</strong>
                    </p>
                </div>
                <div class="inv-summary">
                    <table>
                        <tbody>
                            <tr><th>Sub Total</th><td>&#2547; {{ number_format($subtotal, 2) }}</td></tr>
                            @if($adminDisc > 0)<tr class="row-disc"><th>Admin Discount</th><td>&minus; &#2547; {{ number_format($adminDisc, 2) }}</td></tr>@endif
                            @if($couponDisc > 0)<tr class="row-disc"><th>Coupon Discount</th><td>&minus; &#2547; {{ number_format($couponDisc, 2) }}</td></tr>@endif
                            <tr><th>Shipping</th><td>&#2547; {{ number_format($shipping, 2) }}</td></tr>
                            <tr class="row-grand"><th>Grand Total</th><td>&#2547; {{ number_format($grandTotal, 2) }}</td></tr>
                            @if($paidAmt > 0)<tr class="row-paid"><th>Paid Amount</th><td>&#2547; {{ number_format($paidAmt, 2) }}</td></tr>@endif
                            @if($dueAmt > 0)
                                <tr class="row-due"><th>Due / COD</th><td>&#2547; {{ number_format($dueAmt, 2) }}</td></tr>
                            @else
                                <tr><td colspan="2"><div class="inv-fully-paid"><i class="fa-solid fa-circle-check"></i> FULLY PAID</div></td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="inv-stamp-row">
                <div class="inv-website-info">
                    <strong>{{ $siteName }}</strong><br>
                    @if($siteAddress){{ $siteAddress }}<br>@endif
                    @if($sitePhone)Tel: {{ $sitePhone }}@endif @if($siteEmail)&nbsp;|&nbsp;{{ $siteEmail }}@endif
                </div>
                <div class="inv-signature-box">
                    <div class="inv-signature-line"></div>
                    Authorized Signature
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
