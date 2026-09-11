@php
    $initQuery = \App\Models\Orders::query();
    if (request()->filled('timeframe')) {
        $tf = request('timeframe');
        if ($tf === 'today') {
            $initQuery->whereDate('created_at', \Carbon\Carbon::today());
        } elseif ($tf === 'week') {
            $initQuery->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
        } elseif ($tf === 'month') {
            $initQuery->whereBetween('created_at', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()]);
        } elseif ($tf === 'year') {
            $initQuery->whereBetween('created_at', [\Carbon\Carbon::now()->startOfYear(), \Carbon\Carbon::now()->endOfYear()]);
        } elseif ($tf === 'custom') {
            $s = request('start_date') ?? request('from');
            $e = request('end_date') ?? request('to');
            if ($s && $e) {
                $initQuery->whereBetween('created_at', [\Carbon\Carbon::parse($s)->startOfDay(), \Carbon\Carbon::parse($e)->endOfDay()]);
            } elseif ($s) {
                $initQuery->where('created_at', '>=', \Carbon\Carbon::parse($s)->startOfDay());
            } elseif ($e) {
                $initQuery->where('created_at', '<=', \Carbon\Carbon::parse($e)->endOfDay());
            }
        }
    } elseif (request()->filled('from') && request()->filled('to')) {
        $initQuery->whereBetween('created_at', [\Carbon\Carbon::parse(request('from'))->startOfDay(), \Carbon\Carbon::parse(request('to'))->endOfDay()]);
    }

    $counts = (clone $initQuery)->select('delivery_status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->groupBy('delivery_status')
        ->pluck('total', 'delivery_status')
        ->toArray();

    $returnCounts = (clone $initQuery)->select('return_status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->whereNotNull('return_status')
        ->groupBy('return_status')
        ->pluck('total', 'return_status')
        ->toArray();

    $deliveredCount = (clone $initQuery)->where(function ($q) {
        $q->where(function ($sub) {
            $sub->whereIn('delivery_status', ['delivered', 'partial_delivered'])
                ->where(function ($s) {
                    $s->whereNull('return_status')
                        ->orWhereNotIn('return_status', ['paid return', 'unpaid return']);
                });
        })->orWhere('return_status', 'partial');
    })->count();

    $returnedCount = (clone $initQuery)->where(function ($q) {
        $q->whereIn('delivery_status', ['returned', 'return'])
            ->orWhereIn('return_status', ['partial', 'unpaid return', 'paid return']);
    })->count();

    $realCounts = [
        'pending'   => $counts['pending'] ?? 0,
        'hold'      => $counts['hold'] ?? 0,
        'approved'  => $counts['approved'] ?? 0,
        'packaging' => $counts['packaging'] ?? 0,
        'in_courier'=> ($counts['in_courier'] ?? 0) + ($counts['incourier'] ?? 0),
        'delivered' => $deliveredCount,
        'canceled'  => ($counts['cancel'] ?? 0) + ($counts['canceled'] ?? 0) + ($counts['cancelled'] ?? 0),
        'returned'  => $returnedCount,
        'partial' => $returnCounts['partial'] ?? 0,
        'unpaid_return' => $returnCounts['unpaid return'] ?? 0,
        'paid_return' => $returnCounts['paid return'] ?? 0,
    ];

    $routeName = request()->route() ? request()->route()->getName() : '';
    $routeToStatus = [
        'order-hold' => 'hold',
        'order-pending' => 'pending',
        'order-approved' => 'approved',
        'order-packaging' => 'packaging',
        'order-incourier' => 'in_courier',
        'order-delivered' => 'delivered',
        'order-canceled' => 'canceled',
        'order-returned' => 'returned'
    ];
    $activeStatus = request('status') ?: ($routeToStatus[$routeName] ?? '');
@endphp

<style>
    .pill-group {
        display: inline-flex;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 3px;
        overflow-x: auto;
        white-space: nowrap;
    }
    body.dark-mode .pill-group {
        background-color: #0f172a;
        border-color: #334155;
    }
    .pill-btn {
        padding: 6px 14px;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 7px;
        color: #64748b;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.18s ease;
        outline: none !important;
    }
    body.dark-mode .pill-btn {
        color: #94a3b8;
    }
    .pill-btn:hover {
        color: #0f172a;
        background-color: #e2e8f0;
    }
    body.dark-mode .pill-btn:hover {
        color: #f8fafc;
        background-color: #1e293b;
    }
    .pill-btn.active {
        background: linear-gradient(135deg, #059669, #0d9488) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(13, 148, 136, 0.35);
    }
    .order-status-btn {
        display: block;
        text-decoration: none !important;
    }
    .order-status-btn .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        border: 2px solid transparent !important;
        border-radius: 12px;
        position: relative;
    }
    .order-status-btn:hover .card {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
    }
    .order-status-btn.active-status .card {
        border-color: #0ea5e9 !important;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.25), 0 8px 20px rgba(0, 0, 0, 0.12) !important;
        background: #f8fafc;
    }
    body.dark-mode .order-status-btn.active-status .card {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25), 0 8px 20px rgba(0, 0, 0, 0.5) !important;
        background: #1e293b;
    }
    .return-sub-btn {
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
        transition: background-color 0.15s ease;
        display: inline-block;
    }
    .return-sub-btn:hover {
        background-color: rgba(0, 0, 0, 0.08);
    }
    body.dark-mode .return-sub-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    body.dark-mode #orderStatusHeader {
        color: #f8fafc !important;
    }
    body.dark-mode .return-sub-container {
        color: #94a3b8 !important;
        border-color: #334155 !important;
    }
    body.dark-mode #orderFilterStartDate,
    body.dark-mode #orderFilterEndDate {
        background-color: #0f172a;
        border-color: #334155;
        color: #f8fafc;
    }
</style>

<div class="card mb-4">
    <div class="card-header" id="orderStatusHeader" data-toggle="collapse" data-target="#collapseOrderStatus" aria-expanded="true" aria-controls="collapseOrderStatus" style="cursor: pointer; display: flex; align-items: center; justify-content: space-between; color: #000; font-weight: bold;" onclick="const icon = $(this).find('.toggle-icon'); if (icon.hasClass('open')) { icon.removeClass('open').css('transform', 'rotate(0deg)'); } else { icon.addClass('open').css('transform', 'rotate(90deg)'); }">
        <div class="d-flex align-items-center">
            <span>Order Status</span>
        </div>
        <i class="fas fa-chevron-right ml-2 toggle-icon open" style="transition: transform 0.3s ease; display: inline-block; transform: rotate(90deg);"></i>
    </div>
    <div id="collapseOrderStatus" class="collapse show">
        <div class="card-body">
            {{-- Modern Timeframe Filtering Bar (Matching Finance style) --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-4 pb-3 border-bottom" style="gap: 12px; border-color: rgba(0, 0, 0, 0.07) !important;">
                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <div class="pill-group" id="orderTimeframePills">
                        <button type="button" class="pill-btn {{ request('timeframe', 'all') === 'all' ? 'active' : '' }}" data-time="all">All Time</button>
                        <button type="button" class="pill-btn {{ request('timeframe') === 'today' ? 'active' : '' }}" data-time="today">Today</button>
                        <button type="button" class="pill-btn {{ request('timeframe') === 'week' ? 'active' : '' }}" data-time="week">This Week</button>
                        <button type="button" class="pill-btn {{ request('timeframe') === 'month' ? 'active' : '' }}" data-time="month">This Month</button>
                        <button type="button" class="pill-btn {{ request('timeframe') === 'year' ? 'active' : '' }}" data-time="year">This Year</button>
                        <button type="button" class="pill-btn {{ request('timeframe') === 'custom' ? 'active' : '' }}" data-time="custom">Custom Range</button>
                    </div>
                </div>
                <div id="orderCustomDateRow" class="d-flex align-items-center flex-wrap {{ request('timeframe') === 'custom' ? '' : 'd-none' }}" style="gap: 8px;">
                    <input type="date" id="orderFilterStartDate" class="form-control form-control-sm" value="{{ request('start_date', request('from')) }}" style="height: 32px; font-size: 12px; border-radius: 6px; width: 140px;">
                    <span class="text-muted font-11">to</span>
                    <input type="date" id="orderFilterEndDate" class="form-control form-control-sm" value="{{ request('end_date', request('to')) }}" style="height: 32px; font-size: 12px; border-radius: 6px; width: 140px;">
                    <button type="button" id="orderApplyCustomDateBtn" class="btn btn-primary btn-sm px-3" style="height: 32px; font-size: 12px; border-radius: 6px; font-weight: 600;">
                        Apply
                    </button>
                </div>
            </div>

            {{-- Status Metric Cards (8 statuses) --}}
            <div class="row">
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-pending') ? route('order-pending') : '#' }}" class="filter-order order-status-btn {{ $activeStatus === 'pending' ? 'active-status' : '' }}" data-status="pending">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">PENDING ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/pending.png" alt="img">
                                    <div class="stat-digit" id="count-pending">{{ $realCounts['pending'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-hold') ? route('order-hold') : '#' }}" class="filter-order order-status-btn {{ $activeStatus === 'hold' ? 'active-status' : '' }}" data-status="hold">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">HOLD ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/hold.png" alt="img">
                                    <div class="stat-digit" id="count-hold">{{ $realCounts['hold'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-approved') ? route('order-approved') : '#' }}" class="filter-order order-status-btn {{ $activeStatus === 'approved' ? 'active-status' : '' }}" data-status="approved">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">APPROVED ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/approved.png" alt="img">
                                    <div class="stat-digit" id="count-approved">{{ $realCounts['approved'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-packaging') ? route('order-packaging') : '#' }}" class="filter-order order-status-btn {{ $activeStatus === 'packaging' ? 'active-status' : '' }}" data-status="packaging">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">PACKAGING ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/packaging.png" alt="img">
                                    <div class="stat-digit" id="count-packaging">{{ $realCounts['packaging'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-incourier') ? route('order-incourier') : '#' }}" class="filter-order order-status-btn {{ in_array($activeStatus, ['in_courier', 'incourier']) ? 'active-status' : '' }}" data-status="in_courier">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">IN COURIER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/incourier.png" alt="img">
                                    <div class="stat-digit" id="count-in_courier">{{ $realCounts['in_courier'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-delivered') ? route('order-delivered') : '#' }}" class="filter-order order-status-btn {{ in_array($activeStatus, ['delivered', 'partial_delivered']) ? 'active-status' : '' }}" data-status="delivered">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">DELIVERED ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/delivery.png" alt="img">
                                    <div class="stat-digit" id="count-delivered">{{ $realCounts['delivered'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-returned') ? route('order-returned') : '#' }}" class="filter-order order-status-btn {{ in_array($activeStatus, ['returned', 'return']) ? 'active-status' : '' }}" data-status="returned">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body pb-2">
                                <div class="stat-content">
                                    <div class="stat-text">RETURNED ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/product-return.png" alt="img">
                                    <div class="stat-digit" id="count-returned">{{ $realCounts['returned'] }}</div>
                                </div>
                                <div class="mt-2 pt-2 border-top text-center return-sub-container" style="font-size: 11px; font-weight: bold; color: #555;">
                                    <span class="text-warning return-sub-btn" data-return="partial" title="Filter Partial Return">PARTIAL: <span id="count-partial">{{ $realCounts['partial'] }}</span></span> | 
                                    <span class="text-danger return-sub-btn" data-return="unpaid_return" title="Filter Unpaid Return">UNPAID: <span id="count-unpaid_return">{{ $realCounts['unpaid_return'] }}</span></span> | 
                                    <span class="text-success return-sub-btn" data-return="paid_return" title="Filter Paid Return">PAID: <span id="count-paid_return">{{ $realCounts['paid_return'] }}</span></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <a href="{{ Route::has('order-canceled') ? route('order-canceled') : '#' }}" class="filter-order order-status-btn {{ in_array($activeStatus, ['canceled', 'cancel', 'cancelled']) ? 'active-status' : '' }}" data-status="canceled">
                        <div class="card shadow">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">CANCELED ORDER</div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <img style="height: 50px;" src="{{ asset('adminDash') }}/assets/img/orders/cancel.png" alt="img">
                                    <div class="stat-digit" id="count-canceled">{{ $realCounts['canceled'] }}</div>
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
        if (typeof applyFilters === 'function') {
            applyFilters();
            return;
        }

        const tf = $('#orderTimeframePills .pill-btn.active').data('time') || 'all';
        const start = $('#orderFilterStartDate').val();
        const end = $('#orderFilterEndDate').val();
        const search = $('#orderSearch').val();
        const adminId = $('.adminFilter').val();

        $.ajax({
            url: "{{ Route::has('admin.orders.status-count') ? route('admin.orders.status-count') : '/admin/orders/status-count' }}",
            type: 'GET',
            data: {
                timeframe: tf,
                start_date: start,
                end_date: end,
                from: start || $('#from_date').val(),
                to: end || $('#to_date').val(),
                search: search,
                admin_id: adminId
            },
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
                    if (res.partial !== undefined) $('#count-partial').text(res.partial);
                    if (res.unpaid_return !== undefined) $('#count-unpaid_return').text(res.unpaid_return);
                    if (res.paid_return !== undefined) $('#count-paid_return').text(res.paid_return);
                }
            }
        });
    }
</script>
