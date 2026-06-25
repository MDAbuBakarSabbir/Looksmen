@extends('layouts.AdminLays.master')

@section('title')
    PRODUCT REPORTS
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

@php
    $totalProducts = \App\Models\Product::count();
    $outOfStock = \App\Models\Product::where('stock', '<=', 0)->count();
    $lowStock = \App\Models\Product::whereBetween('stock', [1, 5])->count();
    $totalSalesVolume = \App\Models\Product::sum('num_of_sale');
@endphp

<div class="container-fluid report-container">
    <!-- Stat Cards Row -->
    <div class="row">
        <!-- Total Products -->
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: var(--primary-gradient);">
                <div class="stat-text">Total Products</div>
                <div class="stat-digit">{{ number_format($totalProducts) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            </div>
        </div>

        <!-- Total Sales Volume -->
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: var(--success-gradient);">
                <div class="stat-text">Total Units Sold</div>
                <div class="stat-digit">{{ number_format($totalSalesVolume) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-fire"></i></div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: var(--warning-gradient);">
                <div class="stat-text">Low Stock (1-5)</div>
                <div class="stat-digit">{{ number_format($lowStock) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: var(--danger-gradient);">
                <div class="stat-text">Out of Stock</div>
                <div class="stat-digit">{{ number_format($outOfStock) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-circle-xmark"></i></div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="glass-card mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-filter mr-2 text-primary"></i>Filter Products</h4>
        </div>
        <div class="card-body px-4 pb-4 pt-3">
            <form method="GET" action="{{ route('report.Product') }}">
                <div class="row align-items-end">
                    <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                        <label class="filter-label">Category</label>
                        <select name="category_id" class="form-control form-control-premium">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                        <label class="filter-label">Stock Status</label>
                        <select name="stock_status" class="form-control form-control-premium">
                            <option value="">All Stock Statuses</option>
                            <option value="in_stock" {{ $stockStatus == 'in_stock' ? 'selected' : '' }}>In Stock (>5)</option>
                            <option value="low_stock" {{ $stockStatus == 'low_stock' ? 'selected' : '' }}>Low Stock (1-5)</option>
                            <option value="out_of_stock" {{ $stockStatus == 'out_of_stock' ? 'selected' : '' }}>Out of Stock (<=0)</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="d-flex" style="gap: 10px;">
                            <button type="submit" class="btn btn-premium flex-grow-1"><i class="fa-solid fa-magnifying-glass mr-2"></i>Filter</button>
                            <a href="{{ route('report.Product') }}" class="btn btn-light-premium"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="glass-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-list mr-2 text-primary"></i>Product Performance & Inventory</h4>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table-premium table">
                    <thead>
                        <tr>
                            <th>Product Info</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Stock Status</th>
                            <th>Stock Count</th>
                            <th>Unit Price</th>
                            <th>Sales count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            @php
                                $firstImg = $product->firstImage;
                                $imgPath = $firstImg ? asset('adminDash/images/product/' . $firstImg->image) : asset('favicon.png');
                                
                                if ($product->stock <= 0) {
                                    $stockBadge = 'badge-danger';
                                    $stockText = 'Out of Stock';
                                } elseif ($product->stock <= 5) {
                                    $stockBadge = 'badge-warning text-dark';
                                    $stockText = 'Low Stock';
                                } else {
                                    $stockBadge = 'badge-success';
                                    $stockText = 'In Stock';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <img style="height: 50px; width: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f8fafc;" 
                                             src="{{ $imgPath }}" 
                                             alt="{{ $product->title }}"
                                             onerror="this.onerror=null; this.src='{{ asset('favicon.png') }}';">
                                        <div class="font-weight-bold text-dark text-wrap" style="max-width: 320px;">
                                            {{ $product->title }}
                                        </div>
                                    </div>
                                </td>
                                <td class="font-weight-bold">{{ $product->sku ?? 'N/A' }}</td>
                                <td>{{ $product->category?->category_name ?? 'Uncategorized' }}</td>
                                <td>
                                    <span class="status-badge badge {{ $stockBadge }}">
                                        {{ $stockText }}
                                    </span>
                                </td>
                                <td class="font-weight-bold {{ $product->stock <= 5 ? 'text-danger' : 'text-dark' }}">{{ $product->stock }}</td>
                                <td class="font-weight-bold">৳{{ number_format($product->new_price, 2) }}</td>
                                <td class="font-weight-bold text-success">{{ number_format($product->num_of_sale) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-box-open fa-3x mb-3 d-block opacity-50"></i>
                                    No products found matching the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($products->hasPages())
                <div class="card-footer bg-transparent border-0 px-4 py-4 d-flex justify-content-center">
                    {{ $products->appends(request()->input())->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
