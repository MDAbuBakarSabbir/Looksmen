@php
    $currentStatus = $order->delivery_status;
    $allStatuses = [
        'new' => ['label' => 'Hold / New', 'icon' => 'fa-clock', 'color' => 'text-secondary'],
        'pending' => ['label' => 'Pending', 'icon' => 'fa-hourglass-half', 'color' => 'text-warning'],
        'approved' => ['label' => 'Approved', 'icon' => 'fa-circle-check', 'color' => 'text-success'],
        'packaging' => ['label' => 'Packaging', 'icon' => 'fa-box', 'color' => 'text-primary'],
        'incourier' => ['label' => 'In Courier', 'icon' => 'fa-truck', 'color' => 'text-info'],
        'delivered' => ['label' => 'Delivered', 'icon' => 'fa-circle-dollar-to-slot', 'color' => 'text-success'],
        'cancel' => ['label' => 'Cancel', 'icon' => 'fa-circle-xmark', 'color' => 'text-danger'],
        'returned' => ['label' => 'Returned', 'icon' => 'fa-arrow-rotate-left', 'color' => 'text-dark']
    ];
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
@foreach ($allStatuses as $statusKey => $statusData)
    @if ($statusKey !== $currentStatus)
        <button class="dropdown-item d-flex align-items-center status-update-btn" data-id="{{ $order->id }}" data-status="{{ $statusKey }}">
            <i class="fa-solid {{ $statusData['icon'] }} {{ $statusData['color'] }}" style="width: 20px;"></i>
            <span>{{ $statusData['label'] }}</span>
        </button>
    @endif
@endforeach
