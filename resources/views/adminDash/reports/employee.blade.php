@extends('layouts.Backend.master')

@section('title')
    EMPLOYEE REPORTS
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

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-gradient);
        color: white;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
    }
</style>

@php
    $totalEmployees = $employees->count();
    $totalOrdersProcessed = $employees->sum('orders_count');
    $totalLogs = $employees->sum('actions_count');
@endphp

<div class="container-fluid report-container">
    <!-- Stat Cards Row -->
    <div class="row">
        <!-- Total Employees -->
        <div class="col-xl-4 col-md-6 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--primary-gradient);">
                <div class="stat-text">Total Staff / Admins</div>
                <div class="stat-digit">{{ number_format($totalEmployees) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-user-tie"></i></div>
            </div>
        </div>

        <!-- Total Orders Processed -->
        <div class="col-xl-4 col-md-6 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--success-gradient);">
                <div class="stat-text">Orders Managed / Processed</div>
                <div class="stat-digit">{{ number_format($totalOrdersProcessed) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-arrows-spin"></i></div>
            </div>
        </div>

        <!-- Total Activity Logs -->
        <div class="col-xl-4 col-md-12 col-sm-12 mb-4">
            <div class="stat-widget-premium" style="background: var(--info-gradient);">
                <div class="stat-text">Logged Operations / Actions</div>
                <div class="stat-digit">{{ number_format($totalLogs) }}</div>
                <div class="stat-icon"><i class="fa-solid fa-list-check"></i></div>
            </div>
        </div>
    </div>

    <!-- Employee Performance Grid -->
    <div class="glass-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="font-weight-bold text-dark m-0"><i class="fa-solid fa-users-gear mr-2 text-primary"></i>Staff Members and Operations Tracking</h4>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table-premium table">
                    <thead>
                        <tr>
                            <th>Staff Info</th>
                            <th>Email Address</th>
                            <th>Role Status</th>
                            <th>Orders Processed</th>
                            <th>System Action Logs</th>
                            <th>Performance Badge</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            @php
                                $roleLabel = 'Staff';
                                $roleBadge = 'badge-secondary';
                                if ($employee['role'] == 'admin' || $employee['role'] == '1') {
                                    $roleLabel = 'Administrator';
                                    $roleBadge = 'badge-danger';
                                } elseif ($employee['role'] == 'manager' || $employee['role'] == '2') {
                                    $roleLabel = 'Manager';
                                    $roleBadge = 'badge-warning text-dark';
                                }
                                
                                // Determine performance index
                                $activityWeight = $employee['orders_count'] + ($employee['actions_count'] * 0.1);
                                if ($activityWeight >= 100) {
                                    $perfLabel = 'Elite Performer';
                                    $perfBadge = 'badge-success';
                                } elseif ($activityWeight >= 20) {
                                    $perfLabel = 'Highly Active';
                                    $perfBadge = 'badge-primary';
                                } else {
                                    $perfLabel = 'Standard Access';
                                    $perfBadge = 'badge-light border';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <div class="employee-avatar">
                                            {{ strtoupper(substr($employee['name'] ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $employee['name'] }}</div>
                                            <div class="text-muted small">ID: #{{ $employee['id'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-weight-bold text-muted">{{ $employee['email'] }}</td>
                                <td>
                                    <span class="status-badge badge {{ $roleBadge }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td class="font-weight-bold text-success">{{ number_format($employee['orders_count']) }} orders</td>
                                <td class="font-weight-bold text-info">{{ number_format($employee['actions_count']) }} operations</td>
                                <td>
                                    <span class="status-badge badge {{ $perfBadge }}">
                                        {{ $perfLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-users-slash fa-3x mb-3 d-block opacity-50"></i>
                                    No staff accounts found in system registry.
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
