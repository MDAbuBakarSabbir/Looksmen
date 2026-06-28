@extends('layouts.Backend.master')

@section('title')
    META ADS REPORTS
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

    /* Funnel styles */
    .funnel-stage {
        padding: 16px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .funnel-stage-name {
        font-weight: 600;
        font-size: 15px;
        color: #1e293b;
    }

    .funnel-stage-value {
        font-weight: 700;
        font-size: 16px;
        color: #6366f1;
    }

    .funnel-conversion-rate {
        position: absolute;
        bottom: -11px;
        left: 50%;
        transform: translateX(-50%);
        background: #e0e7ff;
        color: #4f46e5;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 20px;
        border: 1px solid #c7d2fe;
        z-index: 2;
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

<div class="container-fluid report-container">
    <!-- Stat Cards Row -->
    <div class="row">
        <!-- Purchase Conversions -->
        <div class="col-xl-4 col-lg-4 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--primary-gradient);">
                <div class="stat-text">Ads Purchases</div>
                <div class="stat-digit">{{ number_format($totalPurchases) }}</div>
                <div class="stat-icon"><i class="fa-brands fa-facebook"></i></div>
            </div>
        </div>

        <!-- Conversion Value -->
        <div class="col-xl-4 col-lg-4 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--success-gradient);">
                <div class="stat-text">Conversion Value</div>
                <div class="stat-digit">৳{{ number_format($totalValue, 2) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-money-bill-trend-up"></i></div>
            </div>
        </div>

        <!-- AOV -->
        <div class="col-xl-4 col-lg-4 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--info-gradient);">
                <div class="stat-text">Average Order Value (AOV)</div>
                <div class="stat-digit">৳{{ number_format($aov, 2) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-calculator"></i></div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="glass-card mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-filter mr-2 text-primary"></i>Filter Date Range</h4>
        </div>
        <div class="card-body px-4 pb-4 pt-3">
            <form method="GET" action="{{ route('report.MetaAds') }}">
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
                            <a href="{{ route('report.MetaAds') }}" class="btn btn-light-premium"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Graph & Funnel Row -->
    <div class="row">
        <!-- SVG area chart of conversions -->
        <div class="col-xl-7 col-lg-7 col-md-12 mb-4">
            <div class="glass-card h-100 mb-0">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-chart-line mr-2 text-primary"></i>Conversion Trend (Purchases)</h4>
                </div>
                <div class="card-body px-4 pb-4 pt-4 d-flex flex-column justify-content-center">
                    @if ($conversions->count() > 0)
                        @php
                            $maxVal = $conversions->max('count') ?: 1;
                            $numPoints = $conversions->count();
                            $pointsStr = "";
                            $areaPointsStr = "";
                            $width = 650;
                            $height = 220;
                            $paddingLeft = 40;
                            $paddingRight = 20;
                            $paddingTop = 20;
                            $paddingBottom = 30;
                            
                            $chartW = $width - $paddingLeft - $paddingRight;
                            $chartH = $height - $paddingTop - $paddingBottom;
                            
                            foreach ($conversions as $index => $point) {
                                $x = $paddingLeft + ($numPoints > 1 ? ($index / ($numPoints - 1)) * $chartW : 0);
                                $y = $paddingTop + $chartH - (($point->count / $maxVal) * $chartH);
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
                            <svg viewBox="0 0 {{ $width }} {{ $height }}" class="w-100" style="max-height: 250px;">
                                <defs>
                                    <linearGradient id="chart-grad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#6366f1" stop-opacity="0.3"/>
                                        <stop offset="100%" stop-color="#6366f1" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>
                                
                                <!-- Grid Lines -->
                                <line x1="{{ $paddingLeft }}" y1="{{ $paddingTop }}" x2="{{ $width - $paddingRight }}" y2="{{ $paddingTop }}" stroke="#f1f5f9" stroke-width="1" />
                                <line x1="{{ $paddingLeft }}" y1="{{ $paddingTop + $chartH * 0.5 }}" x2="{{ $width - $paddingRight }}" y2="{{ $paddingTop + $chartH * 0.5 }}" stroke="#f1f5f9" stroke-width="1" />
                                <line x1="{{ $paddingLeft }}" y1="{{ $paddingTop + $chartH }}" x2="{{ $width - $paddingRight }}" y2="{{ $paddingTop + $chartH }}" stroke="#cbd5e1" stroke-width="1.5" />
                                
                                <!-- Axes labels -->
                                <text x="15" y="{{ $paddingTop + 5 }}" font-size="10" fill="#94a3b8" font-family="Outfit">{{ $maxVal }}</text>
                                <text x="15" y="{{ $paddingTop + $chartH * 0.5 + 4 }}" font-size="10" fill="#94a3b8" font-family="Outfit">{{ round($maxVal * 0.5) }}</text>
                                <text x="15" y="{{ $paddingTop + $chartH + 4 }}" font-size="10" fill="#94a3b8" font-family="Outfit">0</text>
                                
                                <!-- Areas and Paths -->
                                @if ($numPoints > 1)
                                    <polygon points="{{ $areaPointsStr }}" fill="url(#chart-grad)" />
                                    <polyline points="{{ $pointsStr }}" fill="none" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                    
                                    <!-- Circles over points -->
                                    @foreach ($conversions as $index => $point)
                                        @php
                                            $cx = $paddingLeft + ($index / ($numPoints - 1)) * $chartW;
                                            $cy = $paddingTop + $chartH - (($point->count / $maxVal) * $chartH);
                                        @endphp
                                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="5" fill="#ffffff" stroke="#6366f1" stroke-width="2.5" />
                                    @endforeach
                                @else
                                    <!-- Fallback single point -->
                                    <circle cx="{{ $paddingLeft + $chartW/2 }}" cy="{{ $paddingTop + $chartH/2 }}" r="6" fill="#6366f1" />
                                    <text x="{{ $paddingLeft + $chartW/2 }}" y="{{ $paddingTop + $chartH/2 - 15 }}" text-anchor="middle" font-size="12" fill="#6366f1" font-weight="700">1 Purchase</text>
                                @endif
                                
                                <!-- X Axis dates -->
                                @if($numPoints > 1)
                                    @php
                                        $labelInterval = max(1, round($numPoints / 5));
                                    @endphp
                                    @foreach($conversions as $index => $point)
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
                            <i class="fa-solid fa-chart-line fa-3x mb-3 d-block opacity-30"></i>
                            No sales data to generate graph.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Funnel column -->
        <div class="col-xl-5 col-lg-5 col-md-12 mb-4">
            <div class="glass-card h-100 mb-0">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-filter-list mr-2 text-primary"></i>Conversions Funnel</h4>
                </div>
                <div class="card-body px-4 pb-4 pt-4">
                    <!-- Page Views -->
                    <div class="funnel-stage">
                        <span class="funnel-stage-name"><i class="fa-solid fa-eye mr-2 text-muted"></i>Page Views (Pixel)</span>
                        <span class="funnel-stage-value">{{ number_format($mockPageViews) }}</span>
                        @php
                            $cartRatio = $mockPageViews > 0 ? round(($mockAddCarts / $mockPageViews) * 100, 1) : 0;
                        @endphp
                        <span class="funnel-conversion-rate">{{ $cartRatio }}% Drop</span>
                    </div>

                    <!-- Add to Cart -->
                    <div class="funnel-stage">
                        <span class="funnel-stage-name"><i class="fa-solid fa-cart-plus mr-2 text-muted"></i>Add to Carts</span>
                        <span class="funnel-stage-value">{{ number_format($mockAddCarts) }}</span>
                        @php
                            $checkoutRatio = $mockAddCarts > 0 ? round(($mockCheckouts / $mockAddCarts) * 100, 1) : 0;
                        @endphp
                        <span class="funnel-conversion-rate">{{ $checkoutRatio }}% Drop</span>
                    </div>

                    <!-- Initiate Checkout -->
                    <div class="funnel-stage">
                        <span class="funnel-stage-name"><i class="fa-solid fa-file-invoice mr-2 text-muted"></i>Initiated Checkouts</span>
                        <span class="funnel-stage-value">{{ number_format($mockCheckouts) }}</span>
                        @php
                            $purchaseRatio = $mockCheckouts > 0 ? round(($totalPurchases / $mockCheckouts) * 100, 1) : 0;
                        @endphp
                        <span class="funnel-conversion-rate">{{ $purchaseRatio }}% Purchase</span>
                    </div>

                    <!-- Purchase -->
                    <div class="funnel-stage mb-0" style="border-color: #22c55e; background: #f0fdf4;">
                        <span class="funnel-stage-name text-success"><i class="fa-solid fa-basket-shopping mr-2"></i>Purchases Completed</span>
                        <span class="funnel-stage-value text-success font-weight-bold">{{ number_format($totalPurchases) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversion Log Table -->
    <div class="glass-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-receipt mr-2 text-primary"></i>Recent Meta Event Conversions</h4>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table-premium table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Conversions Count</th>
                            <th>Conversions Value</th>
                            <th>AOV</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($conversions as $conv)
                            <tr>
                                <td class="font-weight-bold">{{ Carbon\Carbon::parse($conv->date)->format('d F, Y (l)') }}</td>
                                <td class="font-weight-bold text-primary">{{ number_format($conv->count) }} purchases</td>
                                <td class="font-weight-bold text-success">৳{{ number_format($conv->value, 2) }}</td>
                                <td class="font-weight-bold text-dark">৳{{ $conv->count > 0 ? number_format($conv->value / $conv->count, 2) : '0.00' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-calendar-xmark fa-3x mb-3 d-block opacity-50"></i>
                                    No data points registered for the selected date filters.
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
