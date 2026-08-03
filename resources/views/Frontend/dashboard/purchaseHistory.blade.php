@extends('layouts.Frontend.master')
@section('title')
    PURCHASE HISTORY
@endsection
@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">

                @include('Frontend.dashboard.partials.usersideNav')

                <div class="aiz-user-panel w-100">
                    <div class="aiz-titlebar mt-2 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="h3 fw-700" style="color: #1e293b; font-family: 'Outfit', sans-serif;">Purchase History</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row gutters-10">
                        <div class="col-lg-12">
                            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                                <div class="card-header bg-white" style="border-bottom: 1px solid #e2e8f0;">
                                    <h5 class="mb-0 h6 fw-600">Order History</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" style="font-size: 14px;">
                                            <thead style="background-color: #f8fafc; color: #475569;">
                                                <tr>
                                                    <th class="border-0 px-4 py-3">Order ID</th>
                                                    <th class="border-0 px-4 py-3">Date</th>
                                                    <th class="border-0 px-4 py-3">Amount</th>
                                                    <th class="border-0 px-4 py-3">Payment Status</th>
                                                    <th class="border-0 px-4 py-3">Delivery Status</th>
                                                    <th class="border-0 px-4 py-3 text-right">Options</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($orders as $order)
                                                    <tr>
                                                        <td class="px-4 py-3 align-middle">
                                                            <a href="{{ route('order.invoice', $order->id) }}" class="fw-600 text-primary">
                                                                #{{ $order->id }}
                                                            </a>
                                                        </td>
                                                        <td class="px-4 py-3 align-middle text-muted">{{ $order->created_at->format('d-m-Y h:i A') }}</td>
                                                        <td class="px-4 py-3 align-middle fw-600 text-dark">&#2547;{{ $order->grand_total }}</td>
                                                        <td class="px-4 py-3 align-middle">
                                                            @if($order->payment_status == 'paid')
                                                                <span class="badge" style="background-color: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 6px; font-weight: 500;">Paid</span>
                                                            @elseif($order->payment_status == 'partial_paid')
                                                                <span class="badge" style="background-color: #fef08a; color: #854d0e; padding: 6px 12px; border-radius: 6px; font-weight: 500;">Partial Paid</span>
                                                            @else
                                                                <span class="badge" style="background-color: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 6px; font-weight: 500;">Unpaid</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3 align-middle">
                                                            <span class="badge" style="background-color: #f1f5f9; color: #334155; padding: 6px 12px; border-radius: 6px; font-weight: 500;">{{ ucfirst($order->delivery_status) }}</span>
                                                        </td>
                                                        <td class="px-4 py-3 align-middle text-right">
                                                            <a href="{{ route('order.invoice', $order->id) }}" class="btn btn-sm btn-primary" style="border-radius: 6px; padding: 6px 16px;">
                                                                <i class="las la-file-invoice mr-1"></i> Invoice
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-5">
                                                            <i class="las la-box-open text-muted mb-3" style="font-size: 60px; opacity: 0.5;"></i>
                                                            <h3 class="h5 fw-600 text-dark" style="font-family: 'Outfit', sans-serif;">No orders found</h3>
                                                            <p class="text-muted mb-0 fs-14">You haven't placed any orders yet.</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="aiz-pagination p-3 border-top" style="border-color: #e2e8f0 !important;">
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


