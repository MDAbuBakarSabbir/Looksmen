@extends('layouts.AdminLays.master')
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
                        @include('AdminDash.orders.extends.order_rows', ['orders' => $orders ?? $countorders])
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
        });
    </script>
@endsection

