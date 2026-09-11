@php
    $deliveryStatus = strtolower($order->delivery_status ?? 'pending');
    $returnStatus = strtolower($order->return_status ?? '');
    $currentStatus = $returnStatus ?: $deliveryStatus;

    $orderGrandTotal = (float) ($order->total_amount ?? 0) - (float) ($order->admin_discount ?? 0) - (float) ($order->coupon_discount ?? 0) + (float) ($order->delivery_charge ?? 0);
    if ($orderGrandTotal <= 0) {
        $orderGrandTotal = ((float) ($order->paid_amount ?? 0) + (float) ($order->grand_total ?? 0)) ?: (float) ($order->total_amount ?? 0);
    }
    $orderTotal = (float) $orderGrandTotal;
    $orderPaid = (float) ($order->paid_amount ?? 0);
    $orderDue = (float) ($order->grand_total ?? 0);

    $allStatuses = [
        'pending' => ['label' => 'Pending', 'icon' => 'fa-hourglass-half', 'color' => 'text-warning'],
        'hold' => ['label' => 'Hold', 'icon' => 'fa-clock', 'color' => 'text-secondary'],
        'approved' => ['label' => 'Approved', 'icon' => 'fa-circle-check', 'color' => 'text-success'],
        'packaging' => ['label' => 'Packaging', 'icon' => 'fa-box', 'color' => 'text-primary'],
        'incourier' => ['label' => 'In Courier', 'icon' => 'fa-truck', 'color' => 'text-info'],
        'delivered' => ['label' => 'Delivered', 'icon' => 'fa-circle-dollar-to-slot', 'color' => 'text-success'],
        'cancel' => ['label' => 'Cancel', 'icon' => 'fa-circle-xmark', 'color' => 'text-danger'],
        'returned' => ['label' => 'Returned', 'icon' => 'fa-arrow-rotate-left', 'color' => 'text-dark'],
        'partial' => ['label' => 'Partial Delivery', 'icon' => 'fa-box-open', 'color' => 'text-warning'],
        'unpaid return' => ['label' => 'Unpaid Return', 'icon' => 'fa-arrow-rotate-left', 'color' => 'text-danger'],
        'paid return' => ['label' => 'Paid Return', 'icon' => 'fa-arrow-rotate-left', 'color' => 'text-success']
    ];

    // Allowed status transitions based on statusButton.blade.php and return workflow
    $allowedStatuses = [];

    if (in_array($deliveryStatus, ['returned', 'return']) || in_array($returnStatus, ['unpaid return', 'paid return'])) {
        // Return section orders (statusButton: returned -> packaging)
        $allowedStatuses = ['packaging', 'paid return', 'unpaid return', 'partial', 'cancel'];
    } elseif ($returnStatus === 'partial') {
        // Partial delivery order (available in both delivered & return)
        $allowedStatuses = ['delivered', 'paid return', 'unpaid return', 'packaging', 'cancel'];
    } elseif ($deliveryStatus === 'hold' || $deliveryStatus === 'new') {
        // statusButton: hold -> pending, approved, packaging, cancel
        $allowedStatuses = ['pending', 'approved', 'packaging', 'cancel'];
    } elseif ($deliveryStatus === 'pending') {
        // statusButton: pending -> hold, approved, packaging, cancel
        $allowedStatuses = ['hold', 'approved', 'packaging', 'cancel'];
    } elseif ($deliveryStatus === 'approved') {
        // statusButton: approved -> pending, packaging, cancel
        $allowedStatuses = ['pending', 'packaging', 'cancel'];
    } elseif ($deliveryStatus === 'packaging') {
        // statusButton: packaging -> pending, incourier, cancel
        $allowedStatuses = ['pending', 'incourier', 'cancel'];
    } elseif ($deliveryStatus === 'incourier' || $deliveryStatus === 'in_courier') {
        // statusButton: incourier -> delivered, packaging, cancel (+ partial & returned for courier outcomes)
        $allowedStatuses = ['delivered', 'partial', 'returned', 'packaging', 'cancel'];
    } elseif ($deliveryStatus === 'delivered' || $deliveryStatus === 'partial_delivered') {
        // Delivered orders can transition to partial, returned, packaging, cancel
        $allowedStatuses = ['partial', 'returned', 'packaging', 'cancel'];
    } elseif (in_array($deliveryStatus, ['cancel', 'cancelled', 'canceled'])) {
        // statusButton: cancel -> pending, incourier, packaging
        $allowedStatuses = ['pending', 'incourier', 'packaging'];
    } else {
        $allowedStatuses = array_keys($allStatuses);
    }
@endphp

<a class="dropdown-item d-flex align-items-center" href="{{ route('admin.order-show', $order->id) }}">
    <i class="fa-solid fa-eye text-info" style="width: 20px;"></i>
    <span>View Details</span>
</a>
<a class="dropdown-item d-flex align-items-center" href="{{ route('admin.order-edit', $order->id) }}">
    <i class="fa-solid fa-pen-to-square text-primary" style="width: 20px;"></i>
    <span>Edit Order</span>
</a>
<div class="dropdown-divider" style="border-top: 1px solid #e5e7eb; margin: 6px 0;"></div>
<h6 class="dropdown-header text-muted font-weight-bold" style="font-size: 10px; padding: 4px 12px; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Change Status</h6>
@foreach ($allowedStatuses as $statusKey)
    @if ($statusKey !== $currentStatus && isset($allStatuses[$statusKey]))
        @php $statusData = $allStatuses[$statusKey]; @endphp
        <button class="dropdown-item d-flex align-items-center status-update-btn" 
                data-id="{{ $order->id }}" 
                data-status="{{ $statusKey }}"
                data-total="{{ $orderTotal }}"
                data-paid="{{ $orderPaid }}"
                data-due="{{ $orderDue }}"
                data-delivery-charge="{{ (float) ($order->delivery_charge ?? 0) }}"
                data-is-return="{{ $order->return_status === 'partial' ? 1 : 0 }}"
                data-customer="{{ $order->name ?? 'Customer' }}">
            <i class="fa-solid {{ $statusData['icon'] }} {{ $statusData['color'] }}" style="width: 20px;"></i>
            <span>{{ $statusData['label'] }}</span>
        </button>
    @endif
@endforeach

@if(auth()->guard('admin')->user()?->hasPermission('delete_order'))
    <div class="dropdown-divider" style="border-top: 1px solid #e5e7eb; margin: 6px 0;"></div>
    <button type="button" class="dropdown-item d-flex align-items-center text-danger delete-order-btn" data-id="{{ $order->id }}">
        <i class="fa-solid fa-trash text-danger" style="width: 20px;"></i>
        <span>Delete Order</span>
    </button>
@endif
