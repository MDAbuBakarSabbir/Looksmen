<div class="card mb-4">
    <div class="card-header" id="orderStatusHeader" data-toggle="collapse" data-target="#collapseOrderStatus" aria-expanded="false" aria-controls="collapseOrderStatus" style="cursor: pointer; display: flex; align-items: center; color: #000; font-weight: bold;" onclick="const icon = $(this).find('.toggle-icon'); if (icon.hasClass('open')) { icon.removeClass('open').css('transform', 'rotate(0deg)'); } else { icon.addClass('open').css('transform', 'rotate(90deg)'); }">
        <span>Order Status</span>
        <i class="fas fa-chevron-right ml-2 toggle-icon" style="transition: transform 0.3s ease; display: inline-block;"></i>
    </div>
    <div id="collapseOrderStatus" class="collapse">
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
                                    <div class="stat-digit" id="count-pending">{{ $countorders->where('delivery_status', 'pending')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <a href="{{ Route::has('order-new') ? route('order-new') : '#' }}" class="filter-order order-status-btn" data-status="new">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">HOLD ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/hold.png"
                                        alt="img">
                                    <div class="stat-digit" id="count-new">{{ $countorders->where('delivery_status', 'new')->count() }}</div>
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
                                    <div class="stat-digit" id="count-approved">{{ $countorders->where('delivery_status', 'approved')->count() }}</div>
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
                                    <div class="stat-digit" id="count-packaging">{{ $countorders->where('delivery_status', 'packaging')->count() }}
                                    </div>
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
                                    <div class="stat-digit" id="count-in_courier">{{ $countorders->where('delivery_status', 'in_courier')->count() }}
                                    </div>
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
                                    <div class="stat-digit" id="count-delivered">{{ $countorders->where('delivery_status', 'delivered')->count() }}
                                    </div>
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
                                    <div class="stat-digit" id="count-canceled">{{ $countorders->where('delivery_status', 'canceled')->count() }}</div>
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
                                    <div class="stat-digit" id="count-returned">{{ $countorders->where('delivery_status', 'returned')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>