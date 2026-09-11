@extends('layouts.Backend.master')
@section('title')
    @php
        $routeName = request()->route()->getName();
        $statusParam = request('status');
        $title = match ($statusParam ?: $routeName) {
            'hold', 'order-hold' => 'HOLD ORDERS',
            'pending', 'order-pending' => 'PENDING ORDERS',
            'approved', 'order-approved' => 'APPROVED ORDERS',
            'packaging', 'order-packaging' => 'PACKAGING ORDERS',
            'in_courier', 'incourier', 'order-incourier' => 'IN-COURIER ORDERS',
            'delivered', 'partial_delivered', 'order-delivered' => 'DELIVERED ORDERS',
            'canceled', 'cancel', 'cancelled', 'order-canceled' => 'CANCELED ORDERS',
            'returned', 'return', 'order-returned' => 'RETURNED ORDERS',
            default => 'ALL ORDERS',
        };
    @endphp
    {{ $title }}
@endsection

@section('content')
    <style>
        #actionButtonsContainer {
            display: none;
            position: absolute;

            z-index: 10;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 5px;
            border-radius: 4px;
            min-width: 150px;
        }

        .action-btn {
            width: 100%;
            text-align: left;
            margin-bottom: 2px;
        }

        .actionButtonsContainerClass {

            display: none;
            position: absolute;

            z-index: 10;
            background-color: white;

            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 5px;
            border-radius: 4px;
            min-width: 150px;
        }
    </style>
    @include('adminDash.orders.extends.status')
    @include('adminDash.orders.extends.sort')
    <div class="card">
        <div class="card-body">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="padding: 1rem 1.25rem; background: #ffffff; border-bottom: 1px solid #f1f5f9;">
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <span style="font-weight: 700; font-size: 1rem; color: #1e293b;">
                        <i class="fa-solid fa-boxes-stacked mr-2 text-primary"></i>Orders
                    </span>
                    <span class="badge" id="orderHeaderCountBadge" style="font-size: 12px; font-weight: 700; background: #eef2ff; color: #4f46e5; padding: 4px 12px; border-radius: 12px; border: 1px solid #e0e7ff;">
                        Total: <span id="orderHeaderCountVal">{{ ($orders ?? $countorders)->total() }}</span>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"><input type="checkbox" id="orderCheckAll"></th>
                            <th scope="col">Customer</th>
                            <th scope="col">Courier History</th>
                            <th scope="col">Product</th>
                            <th scope="col">Invoice ID</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="oldData">
                        @include('adminDash.orders.extends.order_rows', ['orders' => $orders ?? $countorders])
                    </tbody>
                </table>
            </div>
            <!-- Pagination Area -->
            @php $pg = $orders ?? $countorders; @endphp
            <div class="d-flex align-items-center justify-content-between flex-wrap mt-3 px-1" id="orderPaginationMeta" style="font-size:13px; color:#6c757d;">
                <span id="orderPaginationInfo">
                    Showing <strong>{{ $pg->firstItem() ?? 0 }}</strong> &ndash; <strong>{{ $pg->lastItem() ?? 0 }}</strong>
                    of <strong>{{ $pg->total() }}</strong> orders
                </span>
                <div id="orderPaginationLinks" class="d-flex align-items-center flex-wrap" style="gap:4px;">
                    @if($pg->lastPage() > 1)
                        @if($pg->currentPage() > 1)
                            <button class="btn btn-sm btn-outline-secondary order-paginator-btn" data-page="{{ $pg->currentPage() - 1 }}" style="min-width:36px; border-radius:6px;"><i class="fa-solid fa-chevron-left"></i></button>
                        @endif
                        @php
                            $start = max(1, $pg->currentPage() - 2);
                            $end   = min($pg->lastPage(), $pg->currentPage() + 2);
                        @endphp
                        @if($start > 1)
                            <button class="btn btn-sm btn-outline-secondary order-paginator-btn" data-page="1" style="min-width:36px; border-radius:6px;">1</button>
                            @if($start > 2)<span class="btn btn-sm disabled" style="min-width:36px;">…</span>@endif
                        @endif
                        @for($p = $start; $p <= $end; $p++)
                            <button class="btn btn-sm order-paginator-btn {{ $p == $pg->currentPage() ? 'btn-primary' : 'btn-outline-secondary' }}" data-page="{{ $p }}" style="min-width:36px; border-radius:6px;">{{ $p }}</button>
                        @endfor
                        @if($end < $pg->lastPage())
                            @if($end < $pg->lastPage() - 1)<span class="btn btn-sm disabled" style="min-width:36px;">…</span>@endif
                            <button class="btn btn-sm btn-outline-secondary order-paginator-btn" data-page="{{ $pg->lastPage() }}" style="min-width:36px; border-radius:6px;">{{ $pg->lastPage() }}</button>
                        @endif
                        @if($pg->currentPage() < $pg->lastPage())
                            <button class="btn btn-sm btn-outline-secondary order-paginator-btn" data-page="{{ $pg->currentPage() + 1 }}" style="min-width:36px; border-radius:6px;"><i class="fa-solid fa-chevron-right"></i></button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Map the current Laravel route to the active delivery status
            const routeToStatus = {
                'order-hold': 'hold',
                'order-pending': 'pending',
                'order-approved': 'approved',
                'order-packaging': 'packaging',
                'order-incourier': 'incourier',
                'order-delivered': 'delivered',
                'order-canceled': 'cancel',
                'order-returned': 'returned'
            };

            let currentRoute = "{{ request()->route()->getName() }}";
            let initialStatus = "{{ request('status') }}" || routeToStatus[currentRoute] || '';
            $('.quixnav').data('active-status', initialStatus);
            toggleUIBasedOnStatus(initialStatus);

            let currentPage = parseInt("{{ request('page', 1) }}") || 1;

            function updateBrowserUrl(data) {
                try {
                    let currentPath = window.location.pathname;
                    if (currentPath.includes('-orders') && currentPath !== '/admin/orders') {
                        currentPath = "{{ route('order-index') }}";
                    }

                    let urlObj = new URL(currentPath, window.location.origin);

                    // Status
                    if (data.status) {
                        urlObj.searchParams.set('status', data.status);
                    } else {
                        urlObj.searchParams.delete('status');
                    }

                    // Timeframe
                    if (data.timeframe && data.timeframe !== 'all') {
                        urlObj.searchParams.set('timeframe', data.timeframe);
                    } else {
                        urlObj.searchParams.delete('timeframe');
                    }

                    // Custom dates
                    if (data.timeframe === 'custom') {
                        if (data.start_date) urlObj.searchParams.set('start_date', data.start_date);
                        if (data.end_date) urlObj.searchParams.set('end_date', data.end_date);
                    } else {
                        urlObj.searchParams.delete('start_date');
                        urlObj.searchParams.delete('end_date');
                    }

                    // Search
                    if (data.search && data.search.trim()) {
                        urlObj.searchParams.set('search', data.search.trim());
                    } else {
                        urlObj.searchParams.delete('search');
                    }

                    // Return Sort
                    if (data.return_sort) {
                        urlObj.searchParams.set('return_sort', data.return_sort);
                    } else {
                        urlObj.searchParams.delete('return_sort');
                    }

                    // Days
                    if (data.days) {
                        urlObj.searchParams.set('days', data.days);
                    } else {
                        urlObj.searchParams.delete('days');
                    }

                    // Admin ID
                    if (data.admin_id) {
                        urlObj.searchParams.set('admin_id', data.admin_id);
                    } else {
                        urlObj.searchParams.delete('admin_id');
                    }

                    // Per page
                    if (data.per_page && data.per_page != 10) {
                        urlObj.searchParams.set('per_page', data.per_page);
                    } else {
                        urlObj.searchParams.delete('per_page');
                    }

                    // Page
                    if (data.page && data.page > 1) {
                        urlObj.searchParams.set('page', data.page);
                    } else {
                        urlObj.searchParams.delete('page');
                    }

                    window.history.replaceState({ path: urlObj.toString() }, '', urlObj.toString());
                } catch (err) {
                    console.error('Failed to update URL:', err);
                }
            }

            function buildPaginationButtons(data) {
                if (data.last_page <= 1) {
                    $('#orderPaginationLinks').html('');
                    return;
                }
                let html = '';
                if (data.current_page > 1) {
                    html += '<button class="btn btn-sm btn-outline-secondary order-paginator-btn" data-page="' + (data.current_page - 1) + '" style="min-width:36px;border-radius:6px;"><i class="fa-solid fa-chevron-left"></i></button>';
                }
                let s = Math.max(1, data.current_page - 2);
                let e = Math.min(data.last_page, data.current_page + 2);
                if (s > 1) {
                    html += '<button class="btn btn-sm btn-outline-secondary order-paginator-btn" data-page="1" style="min-width:36px;border-radius:6px;">1</button>';
                    if (s > 2) html += '<span class="btn btn-sm disabled" style="min-width:36px;">…</span>';
                }
                for (let p = s; p <= e; p++) {
                    let cls = (p === data.current_page) ? 'btn-primary' : 'btn-outline-secondary';
                    html += '<button class="btn btn-sm ' + cls + ' order-paginator-btn" data-page="' + p + '" style="min-width:36px;border-radius:6px;">' + p + '</button>';
                }
                if (e < data.last_page) {
                    if (e < data.last_page - 1) html += '<span class="btn btn-sm disabled" style="min-width:36px;">…</span>';
                    html += '<button class="btn btn-sm btn-outline-secondary order-paginator-btn" data-page="' + data.last_page + '" style="min-width:36px;border-radius:6px;">' + data.last_page + '</button>';
                }
                if (data.current_page < data.last_page) {
                    html += '<button class="btn btn-sm btn-outline-secondary order-paginator-btn" data-page="' + (data.current_page + 1) + '" style="min-width:36px;border-radius:6px;"><i class="fa-solid fa-chevron-right"></i></button>';
                }
                $('#orderPaginationLinks').html(html);
            }

            function toggleUIBasedOnStatus(statusVal) {
                // Show/hide returnSortContainer
                if (statusVal === 'returned' || statusVal === 'return') {
                    $('#returnSortContainer').attr('style', 'gap: 8px; display: flex !important;');
                } else {
                    $('#returnSortContainer').attr('style', 'gap: 8px; display: none !important;');
                    $('#returnSort').val(''); // Reset value when hiding
                }

                // Hide bulkCourierEntryBtn for specific statuses
                const hiddenStatuses = ['incourier', 'in_courier', 'delivered', 'return', 'returned', 'paid return', 'paid_return', 'unpaid return', 'unpaid_return', 'partial'];
                if (hiddenStatuses.includes(statusVal)) {
                    $('#bulkCourierEntryBtn').hide();
                } else {
                    $('#bulkCourierEntryBtn').show();
                }
            }

            window.applyFilters = function(statusVal, skipUrlUpdate) {
                let tbody = $('.oldData');
                tbody.html('<tr><td colspan="9" class="text-center py-4"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading orders...</td></tr>');

                let tf = $('#orderTimeframePills .pill-btn.active').data('time') || 'all';
                let start = (tf === 'custom') ? $('#orderFilterStartDate').val() : '';
                let end = (tf === 'custom') ? $('#orderFilterEndDate').val() : '';

                let data = {
                    search: $('#orderSearch').val(),
                    timeframe: tf,
                    start_date: start,
                    end_date: end,
                    from: start || $('#from_date').val(),
                    to: end || $('#to_date').val(),
                    days: (tf === 'all') ? $('.daysFilter').val() : '',
                    admin_id: $('.adminFilter').val(),
                    per_page: $('.perPageFilter').val() || 10,
                    page: currentPage,
                    return_sort: $('#returnSort').val()
                };

                if (statusVal !== undefined) {
                    $('.quixnav').data('active-status', statusVal);
                    data.status = statusVal;
                } else {
                    data.status = $('.quixnav').data('active-status') || '';
                }
                
                toggleUIBasedOnStatus(data.status);

                // Highlight the active status card
                $('.order-status-btn').removeClass('active-status');
                if (data.status) {
                    let st = data.status;
                    if (st === 'cancel' || st === 'cancelled') st = 'canceled';
                    if (st === 'incourier') st = 'in_courier';
                    if (st === 'partial_delivered') st = 'delivered';
                    if (st === 'return') st = 'returned';
                    $('.order-status-btn[data-status="' + st + '"]').addClass('active-status');
                }

                if (!skipUrlUpdate) {
                    updateBrowserUrl(data);
                }

                $.ajax({
                    url: "{{ route('admin.orders.filter') }}",
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        tbody.html(response.html);
                        $('#orderCheckAll').prop('checked', false);

                        $('#orderPaginationInfo').html(
                            'Showing <strong>' + response.from + '</strong> &ndash; <strong>' + response.to + '</strong> of <strong>' + response.total + '</strong> orders'
                        );
                        $('#orderHeaderCountVal').text(response.total);
                        buildPaginationButtons(response);

                        // Real-time status count update on all cards
                        if (response.status_counts) {
                            let sc = response.status_counts;
                            if (sc.pending !== undefined) $('#count-pending').text(sc.pending);
                            if (sc.hold !== undefined) $('#count-hold').text(sc.hold);
                            if (sc.approved !== undefined) $('#count-approved').text(sc.approved);
                            if (sc.packaging !== undefined) $('#count-packaging').text(sc.packaging);
                            if (sc.in_courier !== undefined) $('#count-in_courier').text(sc.in_courier);
                            if (sc.delivered !== undefined) $('#count-delivered').text(sc.delivered);
                            if (sc.canceled !== undefined) $('#count-canceled').text(sc.canceled);
                            if (sc.returned !== undefined) $('#count-returned').text(sc.returned);
                            if (sc.partial !== undefined) $('#count-partial').text(sc.partial);
                            if (sc.unpaid_return !== undefined) $('#count-unpaid_return').text(sc.unpaid_return);
                            if (sc.paid_return !== undefined) $('#count-paid_return').text(sc.paid_return);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        tbody.html('<tr><td colspan="9" class="text-center text-danger">Failed to load orders.</td></tr>');
                    }
                });
            };

            window.refreshOrderStatusCounts = function() {
                applyFilters(undefined, true);
            };

            window.addEventListener('popstate', function() {
                let urlParams = new URLSearchParams(window.location.search);
                let status = urlParams.get('status') || '';
                let tf = urlParams.get('timeframe') || 'all';

                $('#orderTimeframePills .pill-btn').removeClass('active');
                $('#orderTimeframePills .pill-btn[data-time="' + tf + '"]').addClass('active');

                if (tf === 'custom') {
                    $('#orderCustomDateRow').removeClass('d-none');
                    $('#orderFilterStartDate').val(urlParams.get('start_date') || '');
                    $('#orderFilterEndDate').val(urlParams.get('end_date') || '');
                } else {
                    $('#orderCustomDateRow').addClass('d-none');
                }

                $('#orderSearch').val(urlParams.get('search') || '');
                $('.daysFilter').val(urlParams.get('days') || '');
                $('.adminFilter').val(urlParams.get('admin_id') || '');
                $('#returnSort').val(urlParams.get('return_sort') || '');
                currentPage = parseInt(urlParams.get('page')) || 1;

                applyFilters(status, true);
            });

            // Pagination click (delegated)
            $(document).on('click', '.order-paginator-btn', function() {
                currentPage = parseInt($(this).data('page'));
                applyFilters();
                $('html, body').animate({ scrollTop: $('.oldData').offset().top - 100 }, 300);
            });

            // Status Card Clicks — toggle on/off and reset page
            $(document).on('click', '.order-status-btn', function(e) {
                e.preventDefault();
                let clickedStatus = $(this).data('status');
                let currentActive = $('.quixnav').data('active-status');

                // If clicking active status card, toggle off to view all orders
                if (clickedStatus === currentActive) {
                    currentPage = 1;
                    $('#returnSort').val('');
                    applyFilters('');
                } else {
                    currentPage = 1;
                    if (clickedStatus !== 'returned') {
                        $('#returnSort').val('');
                    }
                    applyFilters(clickedStatus);
                }
            });

            // Return Sub-badge Click Handler
            $(document).on('click', '.return-sub-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                let sub = $(this).data('return');
                $('#returnSort').val(sub);
                currentPage = 1;
                applyFilters('returned');
            });

            // Timeframe Pills Click Handler (Clean, without alerts)
            $(document).on('click', '#orderTimeframePills .pill-btn', function(e) {
                e.preventDefault();
                const time = $(this).data('time');
                $('#orderTimeframePills .pill-btn').removeClass('active');
                $(this).addClass('active');

                if (time === 'custom') {
                    $('#orderCustomDateRow').removeClass('d-none');
                    if (!$('#orderFilterStartDate').val()) {
                        const now = new Date();
                        const year = now.getFullYear();
                        const month = String(now.getMonth() + 1).padStart(2, '0');
                        const day = String(now.getDate()).padStart(2, '0');
                        $('#orderFilterStartDate').val(`${year}-${month}-01`);
                        $('#orderFilterEndDate').val(`${year}-${month}-${day}`);
                    }
                    currentPage = 1;
                    applyFilters();
                } else {
                    $('#orderCustomDateRow').addClass('d-none');
                    currentPage = 1;
                    applyFilters();
                }
            });

            // Custom Date Range Listeners
            $(document).on('change', '#orderFilterStartDate, #orderFilterEndDate', function() {
                const tf = $('#orderTimeframePills .pill-btn.active').data('time');
                if (tf === 'custom') {
                    currentPage = 1;
                    applyFilters();
                }
            });

            $(document).on('click', '#orderApplyCustomDateBtn', function(e) {
                e.preventDefault();
                currentPage = 1;
                applyFilters();
            });

            if ($('#orderSearch').val()) {
                $('#collapseFilter').addClass('show');
                const icon = $('#orderFilterHeader').find('.toggle-icon');
                icon.addClass('open').css('transform', 'rotate(90deg)');
            }

            // Delay Search Inputs — reset page
            let delayTimer;
            $(document).on('keyup input', '#orderSearch', function() {
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function() {
                    currentPage = 1;
                    applyFilters();
                }, 300);
            });

            // Date, Day, Admin & Per Page Filters — reset page
            $(document).on('change', '#from_date, #to_date, .daysFilter, .adminFilter, .perPageFilter, #returnSort', function() {
                currentPage = 1;
                applyFilters();
            });

            // Check All / Uncheck All
            $(document).on('change', '#orderCheckAll', function() {
                $('.order-check').prop('checked', $(this).prop('checked'));
            });

            // Sync check all with individual checkboxes
            $(document).on('change', '.order-check', function() {
                if ($('.order-check:checked').length === $('.order-check').length && $('.order-check').length > 0) {
                    $('#orderCheckAll').prop('checked', true);
                } else {
                    $('#orderCheckAll').prop('checked', false);
                }
            });

            // Bulk Update Handler
            $(document).on('click', '#bulkUpdateBtn', function(e) {
                e.preventDefault();
                let status = $('#bulkStatus').val();
                if (!status) {
                    Toast.fire({ icon: 'warning', title: 'Please select a status' });
                    return;
                }
                let selectedIds = [];
                $('.order-check:checked').each(function() {
                    selectedIds.push($(this).val());
                });
                if (selectedIds.length === 0) {
                    Toast.fire({ icon: 'warning', title: 'No orders selected' });
                    return;
                }

                let executeBulkUpdate = function() {
                    $.ajax({
                        url: "{{ route('admin.orders.bulk-update') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: selectedIds,
                            status: status
                        },
                        success: function(response) {
                            if (response.success) {
                                Toast.fire({ icon: 'success', title: response.message || 'Orders updated successfully' });
                                $('#orderCheckAll').prop('checked', false);
                                $('#bulkStatus').val('');
                                currentPage = 1;
                                applyFilters();
                            } else {
                                Toast.fire({ icon: 'error', title: response.message || 'Failed to update orders' });
                            }
                        },
                        error: function(xhr) {
                            let msg = 'Failed to update orders';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Toast.fire({ icon: 'error', title: msg });
                        }
                    });
                };

                if (status === 'delete' || status === 'force-delete' || status === 'bulk_delete') {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Delete Selected Orders?',
                            text: 'Are you sure you want to permanently delete ' + selectedIds.length + ' order(s)? This action cannot be reversed!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, Delete Permanent!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                executeBulkUpdate();
                            }
                        });
                    } else if (confirm('Are you sure you want to permanently delete ' + selectedIds.length + ' order(s)?')) {
                        executeBulkUpdate();
                    }
                    return;
                }

                executeBulkUpdate();
            });

            // Single Order Delete Handler
            $(document).on('click', '.delete-order-btn', function(e) {
                e.preventDefault();
                let orderId = $(this).data('id');
                if (!orderId) return;

                let executeSingleDelete = function() {
                    $.ajax({
                        url: "{{ route('admin.order-destroy') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: orderId
                        },
                        success: function(response) {
                            if (response.success) {
                                Toast.fire({ icon: 'success', title: response.message || 'Order deleted successfully' });
                                applyFilters();
                            } else {
                                Toast.fire({ icon: 'error', title: response.message || 'Failed to delete order' });
                            }
                        },
                        error: function(xhr) {
                            let msg = 'Failed to delete order';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Toast.fire({ icon: 'error', title: msg });
                        }
                    });
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Delete Order #LM-' + orderId + '?',
                        text: 'Are you sure you want to permanently delete this order? All related details and logs will be removed.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Delete Permanent!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            executeSingleDelete();
                        }
                    });
                } else if (confirm('Are you sure you want to permanently delete Order #LM-' + orderId + '?')) {
                    executeSingleDelete();
                }
            });
        });

    </script>
@endsection

