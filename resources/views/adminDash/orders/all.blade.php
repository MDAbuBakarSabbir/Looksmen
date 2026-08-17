@extends('layouts.Backend.master')
@section('title')
    @php
        $routeName = request()->route()->getName();
        $title = match ($routeName) {
            'order-hold' => 'HOLD ORDERS',
            'order-pending' => 'PENDING ORDERS',
            'order-approved' => 'APPROVED ORDERS',
            'order-packaging' => 'PACKAGING ORDERS',
            'order-incourier' => 'IN-COURIER ORDERS',
            'order-delivered' => 'DELIVERED ORDERS',
            'order-canceled' => 'CANCELED ORDERS',
            'order-returned' => 'RETURNED ORDERS',
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
            let initialStatus = routeToStatus[currentRoute] || '';
            $('.quixnav').data('active-status', initialStatus);

            let currentPage = 1;

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

            function applyFilters(statusVal) {
                let tbody = $('.oldData');
                tbody.html('<tr><td colspan="9" class="text-center py-4"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading orders...</td></tr>');

                let data = {
                    search: $('#orderSearch').val(),
                    from: $('#from_date').val(),
                    to: $('#to_date').val(),
                    days: $('.daysFilter').val(),
                    admin_id: $('.adminFilter').val(),
                    per_page: $('.perPageFilter').val() || 10,
                    page: currentPage
                };

                if (statusVal !== undefined) {
                    $('.quixnav').data('active-status', statusVal);
                    data.status = statusVal;
                } else {
                    data.status = $('.quixnav').data('active-status') || '';
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
                        buildPaginationButtons(response);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        tbody.html('<tr><td colspan="9" class="text-center text-danger">Failed to load orders.</td></tr>');
                    }
                });
            }

            // Pagination click (delegated)
            $(document).on('click', '.order-paginator-btn', function() {
                currentPage = parseInt($(this).data('page'));
                applyFilters();
                $('html, body').animate({ scrollTop: $('.oldData').offset().top - 100 }, 300);
            });

            // Status Card Clicks — reset page
            $(document).on('click', '.order-status-btn', function(e) {
                e.preventDefault();
                currentPage = 1;
                applyFilters($(this).data('status'));
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
            $(document).on('change', '#from_date, #to_date, .daysFilter, .adminFilter, .perPageFilter', function() {
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

