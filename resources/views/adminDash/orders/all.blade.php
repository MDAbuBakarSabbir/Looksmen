@extends('layouts.Backend.master')
@section('title')
    @php
        $routeName = request()->route()->getName();
        $title = match ($routeName) {
            'order-new' => 'HOLD ORDERS',
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
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Map the current Laravel route to the active delivery status
            const routeToStatus = {
                'order-new': 'new',
                'order-pending': 'pending',
                'order-approved': 'approved',
                'order-packaging': 'packaging',
                'order-incourier': 'in_courier',
                'order-delivered': 'delivered',
                'order-canceled': 'cancel',
                'order-returned': 'returned'
            };

            let currentRoute = "{{ request()->route()->getName() }}";
            let initialStatus = routeToStatus[currentRoute] || '';
            $('.quixnav').data('active-status', initialStatus);

            function applyFilters(statusVal) {
                let tbody = $('.oldData');
                tbody.css('opacity', 0.5);

                let data = {
                    search: $('#orderSearch').val(),
                    from: $('#from_date').val(),
                    to: $('#to_date').val(),
                    days: $('.daysFilter').val(),
                    admin_id: $('.adminFilter').val()
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
                        tbody.html(response);
                        tbody.css('opacity', 1);
                        $('#orderCheckAll').prop('checked', false);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        tbody.css('opacity', 1);
                    }
                });
            }

            // Status Card Clicks
            $(document).on('click', '.order-status-btn', function(e) {
                e.preventDefault();
                applyFilters($(this).data('status'));
            });

            // Delay Search Inputs
            let delayTimer;
            $(document).on('keyup input', '#orderSearch', function() {
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function() {
                    applyFilters();
                }, 300);
            });

            // Date, Day & Admin Filters
            $(document).on('change', '#from_date, #to_date, .daysFilter, .adminFilter', function() {
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
                            Toast.fire({ icon: 'success', title: 'Orders updated successfully' });
                            applyFilters();
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Toast.fire({ icon: 'error', title: 'Failed to update orders' });
                    }
                });
            });
        });
    </script>
@endsection

