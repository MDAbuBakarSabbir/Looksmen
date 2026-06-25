@if ($order->delivery_status == 'new')
    <button class="btn btn-info" data-id="{{ $order->id }}" data-status="pending">Pending</button>
    <button class="btn btn-success" data-id="{{ $order->id }}" data-status="approved">Approved</button>

    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>

    <button class="btn btn-danger" data-id="{{ $order->id }}" data-status="cancel">Cancel</button>
@elseif ($order->delivery_status == 'pending')
    <button class="btn btn-secondary" data-id="{{ $order->id }}" data-status="new">New</button>
    <button class="btn btn-success" data-id="{{ $order->id }}" data-status="approved">Approved</button>
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
    <button class="btn btn-danger" data-id="{{ $order->id }}" data-status="cancel">Cancel</button>
@elseif ($order->delivery_status == 'approved')
    <button class="btn btn-info" data-id="{{ $order->id }}" data-status="pending">Pending</button>
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
    <button class="btn btn-danger "data-id="{{ $order->id }}" data-status="cancel">Cancel</button>
@elseif ($order->delivery_status == 'packaging')
    <button class="btn btn-info" data-id="{{ $order->id }}" data-status="pending">Pending</button>
    <button class="btn btn-primary" data-id="{{ $order->id }}" data-status="incourier">In
        Courier</button>


    <button class="btn btn-danger" data-id="{{ $order->id }}" data-status="cancel">Cancel</button>
@elseif ($order->delivery_status == 'cancelled')
    <button class="btn btn-info" data-id="{{ $order->id }}" data-status="pending">Pending</button>
    <button class="btn btn-primary" data-id="{{ $order->id }}" data-status="incourier">In
        Courier</button>
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
@elseif ($order->delivery_status == 'returned')
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
@endif
