@php
    $counts = \App\Models\Orders::select('delivery_status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->groupBy('delivery_status')
        ->pluck('total', 'delivery_status')
        ->toArray();

    $realCounts = [
        'pending'   => $counts['pending'] ?? 0,
        'hold'      => $counts['hold'] ?? 0,
        'approved'  => $counts['approved'] ?? 0,
        'packaging' => $counts['packaging'] ?? 0,
        'in_courier'=> ($counts['in_courier'] ?? 0) + ($counts['incourier'] ?? 0),
        'delivered' => ($counts['delivered'] ?? 0) + ($counts['partial_delivered'] ?? 0),
        'canceled'  => ($counts['cancel'] ?? 0) + ($counts['canceled'] ?? 0) + ($counts['cancelled'] ?? 0),
        'returned'  => ($counts['returned'] ?? 0) + ($counts['return'] ?? 0),
    ];
@endphp

<div class="card mb-4">
    <div class="card-header" id="orderStatusHeader" data-toggle="collapse" data-target="#collapseOrderStatus" aria-expanded="true" aria-controls="collapseOrderStatus" style="cursor: pointer; display: flex; align-items: center; color: #000; font-weight: bold;" onclick="const icon = $(this).find('.toggle-icon'); if (icon.hasClass('open')) { icon.removeClass('open').css('transform', 'rotate(0deg)'); } else { icon.addClass('open').css('transform', 'rotate(90deg)'); }">
        <span>Order Status</span>
        <i class="fas fa-chevron-right ml-2 toggle-icon open" style="transition: transform 0.3s ease; display: inline-block; transform: rotate(90deg);"></i>
    </div>
    <div id="collapseOrderStatus" class="collapse show">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-pending') ? route('order-pending') : '#' }}" class="filter-order order-status-btn" data-status="pending">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">PENDING ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/pending.png"
                                        alt="img">
                                    <div class="stat-digit" id="count-pending">{{ $realCounts['pending'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-hold') ? route('order-hold') : '#' }}" class="filter-order order-status-btn" data-status="hold">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">HOLD ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/hold.png"
                                        alt="img">
                                    <div class="stat-digit" id="count-hold">{{ $realCounts['hold'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-approved') ? route('order-approved') : '#' }}" class="filter-order order-status-btn" data-status="approved">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">APPROVED ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/approved.png"
                                        alt="img">
                                    <div class="stat-digit" id="count-approved">{{ $realCounts['approved'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-packaging') ? route('order-packaging') : '#' }}" class="filter-order order-status-btn" data-status="packaging">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">PACKAGING ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/packaging.png"
                                        alt="img">
                                    <div class="stat-digit" id="count-packaging">{{ $realCounts['packaging'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-incourier') ? route('order-incourier') : '#' }}" class="filter-order order-status-btn" data-status="in_courier">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">IN COURIER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/incourier.png"
                                        alt="img">
                                    <div class="stat-digit" id="count-in_courier">{{ $realCounts['in_courier'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-delivered') ? route('order-delivered') : '#' }}" class="filter-order order-status-btn" data-status="delivered">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">DELIVERED ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/delivery.png"
                                        alt="img">
                                    <div class="stat-digit" id="count-delivered">{{ $realCounts['delivered'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-canceled') ? route('order-canceled') : '#' }}" class="filter-order order-status-btn" data-status="canceled">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">CANCELED ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/cancel.png"
                                        alt="img">
                                    <div class="stat-digit" id="count-canceled">{{ $realCounts['canceled'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-returned') ? route('order-returned') : '#' }}" class="filter-order order-status-btn" data-status="returned">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">RETURNED ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;"
                                        src="{{ asset('adminDash') }}/assets/img/orders/product-return.png" alt="img">
                                    <div class="stat-digit" id="count-returned">{{ $realCounts['returned'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function refreshOrderStatusCounts() {
        $.ajax({
            url: "{{ Route::has('admin.orders.status-count') ? route('admin.orders.status-count') : '/admin/orders/status-count' }}",
            type: 'GET',
            success: function(res) {
                if (res) {
                    if (res.pending !== undefined) $('#count-pending').text(res.pending);
                    if (res.hold !== undefined) $('#count-hold').text(res.hold);
                    if (res.approved !== undefined) $('#count-approved').text(res.approved);
                    if (res.packaging !== undefined) $('#count-packaging').text(res.packaging);
                    if (res.in_courier !== undefined) $('#count-in_courier').text(res.in_courier);
                    if (res.delivered !== undefined) $('#count-delivered').text(res.delivered);
                    if (res.canceled !== undefined) $('#count-canceled').text(res.canceled);
                    if (res.returned !== undefined) $('#count-returned').text(res.returned);
                }
            }
        });
    }
</script>
