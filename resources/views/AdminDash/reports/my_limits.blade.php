@extends('layouts.AdminLays.master')

@section('title')
    MY SYSTEM LIMITS
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

    /* Progress bar styling */
    .progress-bar-premium {
        height: 12px;
        background-color: #f1f5f9;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .progress-fill {
        height: 100%;
        border-radius: 20px;
        transition: width 0.8s ease-in-out;
    }

    .limit-value-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #334155;
    }
</style>

<div class="container-fluid report-container">
    <div class="row">
        <!-- Products Limit -->
        @php
            $prodPercent = $limits['products']['percent'];
            $prodColor = $prodPercent >= 90 ? 'var(--danger-gradient)' : ($prodPercent >= 70 ? 'var(--warning-gradient)' : 'var(--success-gradient)');
            $prodBadgeColor = $prodPercent >= 90 ? 'badge-danger' : ($prodPercent >= 70 ? 'badge-warning text-dark' : 'badge-success');
        @endphp
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="glass-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center" style="gap: 12px;">
                            <div class="p-3 bg-light rounded-circle" style="color: #6366f1; font-size: 20px;">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">Products Limit</h5>
                                <span class="text-muted small">Subscription Tier Threshold</span>
                            </div>
                        </div>
                        <span class="badge {{ $prodBadgeColor }} px-3 py-2 font-weight-bold" style="font-size: 11px;">
                            {{ $prodPercent }}% Utilized
                        </span>
                    </div>
                    <div class="progress-bar-premium">
                        <div class="progress-fill" style="width: {{ $prodPercent }}%; background: {{ $prodColor }};"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted small">Allocated Storage Space Used</span>
                        <span class="limit-value-badge">
                            {{ number_format($limits['products']['current']) }} / {{ number_format($limits['products']['max']) }} items
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Accounts Limit -->
        @php
            $staffPercent = $limits['staff']['percent'];
            $staffColor = $staffPercent >= 90 ? 'var(--danger-gradient)' : ($staffPercent >= 70 ? 'var(--warning-gradient)' : 'var(--success-gradient)');
            $staffBadgeColor = $staffPercent >= 90 ? 'badge-danger' : ($staffPercent >= 70 ? 'badge-warning text-dark' : 'badge-success');
        @endphp
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="glass-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center" style="gap: 12px;">
                            <div class="p-3 bg-light rounded-circle" style="color: #10b981; font-size: 20px;">
                                <i class="fa-solid fa-user-gear"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">Staff Accounts</h5>
                                <span class="text-muted small">Internal Staff Slots</span>
                            </div>
                        </div>
                        <span class="badge {{ $staffBadgeColor }} px-3 py-2 font-weight-bold" style="font-size: 11px;">
                            {{ $staffPercent }}% Utilized
                        </span>
                    </div>
                    <div class="progress-bar-premium">
                        <div class="progress-fill" style="width: {{ $staffPercent }}%; background: {{ $staffColor }};"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted small">Registered staff slots</span>
                        <span class="limit-value-badge">
                            {{ number_format($limits['staff']['current']) }} / {{ number_format($limits['staff']['max']) }} staff
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Order Limit -->
        @php
            $orderPercent = $limits['orders']['percent'];
            $orderColor = $orderPercent >= 90 ? 'var(--danger-gradient)' : ($orderPercent >= 70 ? 'var(--warning-gradient)' : 'var(--success-gradient)');
            $orderBadgeColor = $orderPercent >= 90 ? 'badge-danger' : ($orderPercent >= 70 ? 'badge-warning text-dark' : 'badge-success');
        @endphp
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="glass-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center" style="gap: 12px;">
                            <div class="p-3 bg-light rounded-circle" style="color: #ea580c; font-size: 20px;">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">Monthly Orders Capacity</h5>
                                <span class="text-muted small">Current Billing Month Orders</span>
                            </div>
                        </div>
                        <span class="badge {{ $orderBadgeColor }} px-3 py-2 font-weight-bold" style="font-size: 11px;">
                            {{ $orderPercent }}% Utilized
                        </span>
                    </div>
                    <div class="progress-bar-premium">
                        <div class="progress-fill" style="width: {{ $orderPercent }}%; background: {{ $orderColor }};"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted small">Orders processed this month</span>
                        <span class="limit-value-badge">
                            {{ number_format($limits['orders']['current']) }} / {{ number_format($limits['orders']['max']) }} orders
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Space Limit -->
        @php
            $storagePercent = $limits['storage']['percent'];
            $storageColor = $storagePercent >= 90 ? 'var(--danger-gradient)' : ($storagePercent >= 70 ? 'var(--warning-gradient)' : 'var(--success-gradient)');
            $storageBadgeColor = $storagePercent >= 90 ? 'badge-danger' : ($storagePercent >= 70 ? 'badge-warning text-dark' : 'badge-success');
        @endphp
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="glass-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center" style="gap: 12px;">
                            <div class="p-3 bg-light rounded-circle" style="color: #06b6d4; font-size: 20px;">
                                <i class="fa-solid fa-database"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">Hosting Asset Storage</h5>
                                <span class="text-muted small">Media & Uploads Capacity</span>
                            </div>
                        </div>
                        <span class="badge {{ $storageBadgeColor }} px-3 py-2 font-weight-bold" style="font-size: 11px;">
                            {{ $storagePercent }}% Utilized
                        </span>
                    </div>
                    <div class="progress-bar-premium">
                        <div class="progress-fill" style="width: {{ $storagePercent }}%; background: {{ $storageColor }};"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted small">Disk storage volume used</span>
                        <span class="limit-value-badge">
                            {{ number_format($limits['storage']['current'], 1) }} GB / {{ number_format($limits['storage']['max']) }} GB
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert / Recommendation Warning if limits are high -->
    @php
        $anyCriticalLimit = false;
        foreach($limits as $lim) {
            if($lim['percent'] >= 85) {
                $anyCriticalLimit = true;
            }
        }
    @endphp
    
    @if ($anyCriticalLimit)
        <div class="alert alert-warning border-0 p-4 rounded-lg d-flex align-items-start" style="background: #fffbeb; gap: 16px;">
            <i class="fa-solid fa-triangle-exclamation text-warning mt-1" style="font-size: 24px;"></i>
            <div>
                <h5 class="font-weight-bold text-dark mb-1">System Limit Threshold Breached</h5>
                <p class="text-muted mb-0 small">
                    One or more of your active system resources are approaching or exceed 85% utilization limit. 
                    We recommend upgrading your platform subscription or running storage cleanup routines to prevent service interruptions.
                </p>
            </div>
        </div>
    @else
        <div class="alert alert-success border-0 p-4 rounded-lg d-flex align-items-start" style="background: #f0fdf4; gap: 16px;">
            <i class="fa-solid fa-circle-check text-success mt-1" style="font-size: 24px;"></i>
            <div>
                <h5 class="font-weight-bold text-dark mb-1">System Health Normal</h5>
                <p class="text-muted mb-0 small">
                    All limits are within acceptable thresholds (under 70% utilization). 
                    Your backend assets, order capacity, and data indexes are functioning optimally.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
