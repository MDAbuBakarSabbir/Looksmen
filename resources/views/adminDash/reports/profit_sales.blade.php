@extends('layouts.AdminLays.master')

@section('title')
    PROFIT & SALES REPORTS
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
</style>

@php
    $netSales = $grossSales - $deliveryCharge;
@endphp

<div class="container-fluid report-container">
    <!-- Summary Row -->
    <div class="row">
        <!-- Gross Sales -->
        <div class="col-xl-4 col-md-6 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--primary-gradient);">
                <div class="stat-text">Gross Sales</div>
                <div class="stat-digit">৳{{ number_format($grossSales, 2) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
            </div>
        </div>

        <!-- Net Sales -->
        <div class="col-xl-4 col-md-6 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--info-gradient);">
                <div class="stat-text">Net Sales (Excl. Shipping)</div>
                <div class="stat-digit">৳{{ number_format($netSales, 2) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
            </div>
        </div>

        <!-- Estimated Profit -->
        <div class="col-xl-4 col-md-12 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--success-gradient);">
                <div class="stat-text">Estimated Net Profit (35% Margin)</div>
                <div class="stat-digit">৳{{ number_format($estimatedProfit, 2) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-piggy-bank"></i></div>
            </div>
        </div>

        <!-- Orders Count -->
        <div class="col-xl-4 col-md-4 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: #475569;">
                <div class="stat-text">Active Orders Count</div>
                <div class="stat-digit">{{ number_format($ordersCount) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-receipt"></i></div>
            </div>
        </div>

        <!-- Delivery Fees -->
        <div class="col-xl-4 col-md-4 col-sm-6 mb-4">
            <div class="stat-widget-premium" style="background: var(--warning-gradient);">
                <div class="stat-text">Courier Delivery Fees</div>
                <div class="stat-digit">৳{{ number_format($deliveryCharge, 2) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-truck-ramp-box"></i></div>
            </div>
        </div>

        <!-- Discounts Applied -->
        <div class="col-xl-4 col-md-4 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--danger-gradient);">
                <div class="stat-text">Deducted Discounts</div>
                <div class="stat-digit">৳{{ number_format($discounts, 2) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-tags"></i></div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="glass-card mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-filter mr-2 text-primary"></i>Filter Report</h4>
        </div>
        <div class="card-body px-4 pb-4 pt-3">
            <form method="GET" action="{{ route('report.Profit&sales') }}">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="filter-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control form-control-premium" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="filter-label">End Date</label>
                        <input type="date" name="end_date" class="form-control form-control-premium" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex" style="gap: 10px;">
                            <button type="submit" class="btn btn-premium flex-grow-1"><i class="fa-solid fa-magnifying-glass mr-2"></i>Filter</button>
                            <a href="{{ route('report.Profit&sales') }}" class="btn btn-light-premium"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sales Curve Graph -->
    <div class="glass-card mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-chart-area mr-2 text-primary"></i>Revenue Growth Timeline</h4>
        </div>
        <div class="card-body px-4 pb-4 pt-4">
            @if ($salesByDate->count() > 0)
                @php
                    $maxVal = $salesByDate->max('sales') ?: 1;
                    $numPoints = $salesByDate->count();
                    $pointsStr = "";
                    $areaPointsStr = "";
                    $width = 900;
                    $height = 250;
                    $paddingLeft = 50;
                    $paddingRight = 20;
                    $paddingTop = 25;
                    $paddingBottom = 30;
                    
                    $chartW = $width - $paddingLeft - $paddingRight;
                    $chartH = $height - $paddingTop - $paddingBottom;
                    
                    foreach ($salesByDate as $index => $point) {
                        $x = $paddingLeft + ($numPoints > 1 ? ($index / ($numPoints - 1)) * $chartW : 0);
                        $y = $paddingTop + $chartH - (($point->sales / $maxVal) * $chartH);
                        $pointsStr .= "$x,$y ";
                        if ($index == 0) {
                            $areaPointsStr .= "$x,".($paddingTop + $chartH)." ";
                        }
                        $areaPointsStr .= "$x,$y ";
                        if ($index == $numPoints - 1) {
                            $areaPointsStr .= "$x,".($paddingTop + $chartH)." ";
                        }
                    }
                @endphp
                <div class="w-100 text-center">
                    <svg viewBox="0 0 {{ $width }} {{ $height }}" class="w-100" style="max-height: 300px;">
                        <defs>
                            <linearGradient id="sales-grad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#10b981" stop-opacity="0.35"/>
                                <stop offset="100%" stop-color="#10b981" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>
                        
                        <!-- Grid Lines -->
                        <line x1="{{ $paddingLeft }}" y1="{{ $paddingTop }}" x2="{{ $width - $paddingRight }}" y2="{{ $paddingTop }}" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="{{ $paddingLeft }}" y1="{{ $paddingTop + $chartH * 0.5 }}" x2="{{ $width - $paddingRight }}" y2="{{ $paddingTop + $chartH * 0.5 }}" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="{{ $paddingLeft }}" y1="{{ $paddingTop + $chartH }}" x2="{{ $width - $paddingRight }}" y2="{{ $paddingTop + $chartH }}" stroke="#cbd5e1" stroke-width="1.5" />
                        
                        <!-- Axes values -->
                        <text x="15" y="{{ $paddingTop + 5 }}" font-size="10" fill="#94a3b8" font-family="Outfit">৳{{ number_format($maxVal) }}</text>
                        <text x="15" y="{{ $paddingTop + $chartH * 0.5 + 4 }}" font-size="10" fill="#94a3b8" font-family="Outfit">৳{{ number_format(round($maxVal * 0.5)) }}</text>
                        <text x="15" y="{{ $paddingTop + $chartH + 4 }}" font-size="10" fill="#94a3b8" font-family="Outfit">৳0</text>
                        
                        @if ($numPoints > 1)
                            <polygon points="{{ $areaPointsStr }}" fill="url(#sales-grad)" />
                            <polyline points="{{ $pointsStr }}" fill="none" stroke="#10b981" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
                            
                            <!-- Circles -->
                            @foreach ($salesByDate as $index => $point)
                                @php
                                    $cx = $paddingLeft + ($index / ($numPoints - 1)) * $chartW;
                                    $cy = $paddingTop + $chartH - (($point->sales / $maxVal) * $chartH);
                                @endphp
                                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="5" fill="#ffffff" stroke="#10b981" stroke-width="2.5" />
                            @endforeach
                        @else
                            <!-- Single Point fallback -->
                            <circle cx="{{ $paddingLeft + $chartW/2 }}" cy="{{ $paddingTop + $chartH/2 }}" r="6" fill="#10b981" />
                        @endif
                        
                        <!-- Dates Labels -->
                        @if($numPoints > 1)
                            @php
                                $labelInterval = max(1, round($numPoints / 7));
                            @endphp
                            @foreach($salesByDate as $index => $point)
                                @if($index % $labelInterval == 0 || $index == $numPoints - 1)
                                    @php
                                        $lx = $paddingLeft + ($index / ($numPoints - 1)) * $chartW;
                                    @endphp
                                    <text x="{{ $lx }}" y="{{ $height - 5 }}" text-anchor="middle" font-size="10" fill="#94a3b8" font-family="Outfit">
                                        {{ Carbon\Carbon::parse($point->date)->format('d M') }}
                                    </text>
                                @endif
                            @endforeach
                        @endif
                    </svg>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-chart-area fa-3x mb-3 d-block opacity-35"></i>
                    No sales recorded for the selected date range.
                </div>
            @endif
        </div>
    </div>

    <!-- Daily Breakdown Table -->
    <div class="glass-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-calendar-days mr-2 text-primary"></i>Daily Sales & Revenue Log</h4>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table-premium table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Active Orders</th>
                            <th>Gross Income</th>
                            <th>Estimated Margin Contribution (35%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salesByDate as $sale)
                            @php
                                $marginContribution = ($sale->sales * 0.35);
                            @endphp
                            <tr>
                                <td class="font-weight-bold">{{ Carbon\Carbon::parse($sale->date)->format('d M, Y (l)') }}</td>
                                <td class="font-weight-bold text-primary">{{ number_format($sale->count) }} orders</td>
                                <td class="font-weight-bold text-success">৳{{ number_format($sale->sales, 2) }}</td>
                                <td class="font-weight-bold text-dark">৳{{ number_format($marginContribution, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                                    No data points registered for this scope.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
