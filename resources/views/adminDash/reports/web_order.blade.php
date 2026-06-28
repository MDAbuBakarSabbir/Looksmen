@extends('layouts.Backend.master')

@section('title')
    WEB ORDER REPORTS
@endsection

@section('content')
<style>
    /* Google Fonts & Theme Variables */
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --success-gradient: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --info-gradient: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
        
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: rgba(255, 255, 255, 0.5);
        --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    .report-container {
        font-family: 'Outfit', sans-serif !important;
        background-color: #f8fafc;
        padding-top: 10px;
    }

    /* Glass Cards */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: var(--glass-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.08);
    }

    /* Stat Widgets */
    .stat-widget-premium {
        padding: 24px;
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        color: white;
        text-decoration: none;
        display: block;
        transition: all 0.4s ease;
        height: 100%;
        min-height: 130px;
    }

    .stat-widget-premium::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 60%);
        transform: scale(1);
        transition: transform 0.6s ease;
    }

    .stat-widget-premium:hover::before {
        transform: scale(1.5);
    }

    .stat-widget-premium .stat-text {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 8px;
        opacity: 0.9;
    }

    .stat-widget-premium .stat-digit {
        font-size: 2.2rem;
        font-weight: 700;
        line-height: 1.1;
    }

    .stat-widget-premium .stat-icon {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 3rem;
        opacity: 0.25;
        transition: transform 0.3s ease;
    }

    .stat-widget-premium:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    /* Buttons */
    .btn-premium {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-premium:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: white;
    }

    .btn-light-premium {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-light-premium:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Badges */
    .status-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        display: inline-block;
    }

    /* Custom Tables */
    .table-premium {
        width: 100%;
        margin-bottom: 0;
        color: var(--text-main);
    }

    .table-premium th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 16px 20px;
    }

    .table-premium td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px;
    }

    .table-premium tr:last-child td {
        border-bottom: none;
    }

    .filter-label {
        font-weight: 500;
        font-size: 13px;
        color: #475569;
        margin-bottom: 6px;
    }

    .form-control-premium {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-control-premium:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
</style>

<div class="container-fluid report-container">
    <!-- Stat Cards Row -->
    <div class="row">
        <!-- Total Web Orders -->
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: var(--primary-gradient);">
                <div class="stat-text">Total Web Orders</div>
                <div class="stat-digit">{{ number_format($summary['total_orders']) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-globe"></i></div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: var(--success-gradient);">
                <div class="stat-text">Web Sales Volume</div>
                <div class="stat-digit">৳{{ number_format($summary['total_amount'], 2) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-credit-card"></i></div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: var(--info-gradient);">
                <div class="stat-text">Completed Deliveries</div>
                <div class="stat-digit">{{ number_format($summary['completed']) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
            </div>
        </div>

        <!-- Pending Checkout Orders -->
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: var(--warning-gradient);">
                <div class="stat-text">Active Pending Orders</div>
                <div class="stat-digit">{{ number_format($summary['pending']) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-spinner"></i></div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="glass-card mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-filter mr-2 text-primary"></i>Filter Web Orders</h4>
        </div>
        <div class="card-body px-4 pb-4 pt-3">
            <form method="GET" action="{{ route('report.WebOrder') }}">
                <div class="row align-items-end">
                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <label class="filter-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control form-control-premium" value="{{ $startDate }}">
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <label class="filter-label">End Date</label>
                        <input type="date" name="end_date" class="form-control form-control-premium" value="{{ $endDate }}">
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <label class="filter-label">Delivery Status</label>
                        <select name="status" class="form-control form-control-premium">
                            <option value="">All Statuses</option>
                            <option value="new" {{ $status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="packaging" {{ $status == 'packaging' ? 'selected' : '' }}>Packaging</option>
                            <option value="in_courier" {{ $status == 'in_courier' ? 'selected' : '' }}>In Courier</option>
                            <option value="delivered" {{ $status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancel" {{ $status == 'cancel' ? 'selected' : '' }}>Canceled</option>
                            <option value="returned" {{ $status == 'returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex" style="gap: 10px;">
                            <button type="submit" class="btn btn-premium flex-grow-1"><i class="fa-solid fa-magnifying-glass mr-2"></i>Filter</button>
                            <a href="{{ route('report.WebOrder') }}" class="btn btn-light-premium"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="glass-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-globe mr-2 text-primary"></i>Web Orders Log</h4>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table-premium table">
                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Online Customer</th>
                            <th>Ordered Products</th>
                            <th>Total Spent</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            @php
                                $badgeClass = match ($order->delivery_status) {
                                    'new' => 'badge-info',
                                    'pending' => 'badge-warning text-dark',
                                    'approved' => 'badge-primary',
                                    'packaging' => 'badge-secondary',
                                    'in_courier' => 'badge-info',
                                    'delivered' => 'badge-success',
                                    'cancel', 'canceled' => 'badge-danger',
                                    default => 'badge-dark',
                                };
                            @endphp
                            <tr>
                                <td class="font-weight-bold text-primary">{{ $order->invoice_id }}</td>
                                <td>
                                    <div class="font-weight-bold">{{ $order->name }}</div>
                                    <div class="text-muted small"><i class="fa-solid fa-phone mr-1"></i>{{ $order->phone }}</div>
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 280px;">
                                        @foreach($order->orderDetails as $detail)
                                            <span class="badge badge-light border mr-1 mb-1">
                                                {{ $detail->orderProduct?->title ?? 'Deleted Product' }} (x{{ $detail->product_qty }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="font-weight-bold text-dark">৳{{ number_format($order->grand_total, 2) }}</td>
                                <td>
                                    <span class="status-badge badge {{ $badgeClass }}">
                                        {{ str_replace('_', ' ', $order->delivery_status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M, Y h:i A') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.order-show', $order->id) }}" target="_blank" class="btn btn-sm btn-light-premium py-1 px-2">
                                        <i class="fa-solid fa-eye mr-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-cart-arrow-down fa-3x mb-3 d-block opacity-50"></i>
                                    No web checkout orders matching the filter parameters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($orders->hasPages())
                <div class="card-footer bg-transparent border-0 px-4 py-4 d-flex justify-content-center">
                    {{ $orders->appends(request()->input())->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
