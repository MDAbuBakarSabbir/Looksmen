@if ($order->delivery_status == 'hold')
    <button class="btn btn-info" data-id="{{ $order->id }}" data-status="pending">Pending</button>
    <button class="btn btn-success" data-id="{{ $order->id }}" data-status="approved">Approved</button>
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
    <button class="btn btn-danger" data-id="{{ $order->id }}" data-status="cancel">Cancel</button>
@elseif ($order->delivery_status == 'pending')
    <button class="btn btn-secondary" data-id="{{ $order->id }}" data-status="hold">Hold</button>
    <button class="btn btn-success" data-id="{{ $order->id }}" data-status="approved">Approved</button>
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
    <button class="btn btn-danger" data-id="{{ $order->id }}" data-status="cancel">Cancel</button>
@elseif ($order->delivery_status == 'approved')
    <button class="btn btn-info" data-id="{{ $order->id }}" data-status="pending">Pending</button>
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
    <button class="btn btn-danger" data-id="{{ $order->id }}" data-status="cancel">Cancel</button>
@elseif ($order->delivery_status == 'packaging')
    <button class="btn btn-info" data-id="{{ $order->id }}" data-status="pending">Pending</button>
    <button class="btn btn-primary" data-id="{{ $order->id }}" data-status="incourier">In Courier</button>
    <button class="btn btn-danger" data-id="{{ $order->id }}" data-status="cancel">Cancel</button>
@elseif ($order->delivery_status == 'incourier' || $order->delivery_status == 'in_courier')
    <button class="btn btn-success" data-id="{{ $order->id }}" data-status="delivered">Delivered</button>
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
    <button class="btn btn-danger" data-id="{{ $order->id }}" data-status="cancel">Cancel</button>
@elseif ($order->delivery_status == 'cancel' || $order->delivery_status == 'cancelled' || $order->delivery_status == 'canceled')
    <button class="btn btn-info" data-id="{{ $order->id }}" data-status="pending">Pending</button>
    <button class="btn btn-primary" data-id="{{ $order->id }}" data-status="incourier">In Courier</button>
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
@elseif ($order->delivery_status == 'returned')
    <button class="btn btn-warning" data-id="{{ $order->id }}" data-status="packaging">Packaging</button>
@endif
