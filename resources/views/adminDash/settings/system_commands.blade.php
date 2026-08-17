@extends('layouts.Backend.master')

@section('title', 'System Commands')

@section('style')
<style>
    /* System Commands Modern Styling */
    .command-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        background: #ffffff;
        margin-bottom: 24px;
        transition: all 0.25s ease;
    }
    .command-card:hover {
        box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.07);
    }
    .command-card-header {
        padding: 18px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .command-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Danger Hero Alert */
    .db-alert-banner {
        background: linear-gradient(135deg, #fff1f2 0%, #fee2e2 100%);
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }
    .db-alert-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #ef4444;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Database Stats Overview */
    .db-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .db-stat-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .db-stat-box:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1;
    }
    .db-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .db-stat-info h5 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
    }
    .db-stat-info span {
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Toolbar & Presets */
    .db-toolbar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .db-search-wrapper {
        position: relative;
        min-width: 260px;
        flex-grow: 1;
        max-width: 380px;
    }
    .db-search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .db-search-input {
        padding-left: 38px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 0.9rem;
        height: 40px;
    }
    .db-search-input:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }
    .preset-btn {
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .preset-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .preset-btn.active-preset {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    /* Group Card */
    .db-group-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        margin-bottom: 20px;
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }
    .db-group-card:hover {
        box-shadow: 0 6px 14px -2px rgba(0, 0, 0, 0.05);
    }
    .db-group-header {
        padding: 12px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .db-group-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .db-group-badge-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .db-group-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .db-group-desc {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0;
    }

    /* Table Grid Items */
    .table-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 12px;
        padding: 16px;
    }
    .table-checkbox-item {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
    }
    .table-checkbox-item:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        transform: translateY(-1px);
    }
    .table-checkbox-item.is-selected {
        border-color: #ef4444;
        background: #fff5f5;
    }
    .table-checkbox-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-grow: 1;
        min-width: 0;
    }
    .table-checkbox-info input[type="checkbox"] {
        width: 17px;
        height: 17px;
        cursor: pointer;
        accent-color: #ef4444;
        flex-shrink: 0;
    }
    .table-text {
        min-width: 0;
    }
    .table-title {
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    .table-slug {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.73rem;
        color: #94a3b8;
        display: block;
        margin-top: 1px;
    }
    .table-count-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 20px;
        flex-shrink: 0;
    }
    .badge-has-data {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }
    .badge-empty {
        background: #f1f5f9;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
    }
    .badge-high-data {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fef3c7;
    }

    /* Floating / Bottom Sticky Action Bar */
    .db-clear-action-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 24px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        position: sticky;
        bottom: 20px;
        z-index: 100;
    }
    .selected-summary {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .selected-count-pill {
        background: #ef4444;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .btn-clear-db {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        border: none;
        padding: 10px 24px;
        font-size: 0.95rem;
        font-weight: 700;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-clear-db:hover:not(:disabled) {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.45);
        color: #ffffff;
    }
    .btn-clear-db:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        box-shadow: none;
    }
</style>
@endsection

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>System Commands</h4>
            <p class="mb-0">Developer tools, cache management, and database cleaner.</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Setup & Config</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">System Commands</a></li>
        </ol>
    </div>
</div>

<!-- Maintenance Commands Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="command-card">
            <div class="command-card-header">
                <h4 class="command-card-title">
                    <i class="fa-solid fa-screwdriver-wrench text-primary"></i> Maintenance Commands
                </h4>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-3">Execute essential optimization and caching commands. Run these safely after deployments or configuration updates.</p>
                <div class="d-flex flex-wrap" style="gap: 12px;">
                    <button class="btn btn-primary quick-action-btn" data-url="{{ route('clear.cache') }}" style="border-radius: 8px; font-weight: 600;">
                        <i class="fa-solid fa-broom mr-1"></i> Clear Application Cache
                    </button>
                    <button class="btn btn-info quick-action-btn" data-url="{{ route('optimize') }}" style="color: white; border-radius: 8px; font-weight: 600;">
                        <i class="fa-solid fa-bolt mr-1"></i> Optimize App & Config
                    </button>
                    <button class="btn btn-warning quick-action-btn" data-url="{{ route('migrate') }}" style="border-radius: 8px; font-weight: 600; color: #1e293b;">
                        <i class="fa-solid fa-database mr-1"></i> Run Pending Migrations
                    </button>
                    <button class="btn btn-success quick-action-btn" data-url="{{ route('seed') }}" style="border-radius: 8px; font-weight: 600;">
                        <i class="fa-solid fa-seedling mr-1"></i> Run Database Seeders
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Redesigned Database Clear Function -->
<div class="row">
    <div class="col-12">
        <div class="command-card">
            <div class="command-card-header">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="command-card-title text-danger">
                        <i class="fa-solid fa-dumpster-fire"></i> Database Environment Data Cleaner
                    </h4>
                </div>
                <div>
                    <span class="badge badge-danger px-3 py-2" style="border-radius: 6px; font-size: 0.85rem;">
                        <i class="fa-solid fa-shield-halved mr-1"></i> Protected Tables Excluded
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Warning Alert Banner -->
                <div class="db-alert-banner">
                    <div class="db-alert-icon">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold text-danger mb-1" style="font-size: 1rem;">Caution: Permanent Data Deletion</h6>
                        <p class="text-danger mb-0" style="font-size: 0.88rem; opacity: 0.9;">
                            This utility allows you to selectively truncate multiple tables across different modules (orders, products, users, logs, etc.).
                            <strong>This action cannot be undone.</strong> Please download a complete SQL database backup before clearing data.
                        </p>
                    </div>
                </div>

                <!-- Database Metrics Overview -->
                <div class="db-stats-row">
                    <div class="db-stat-box">
                        <div class="db-stat-icon" style="background: #eff6ff; color: #3b82f6;">
                            <i class="fa-solid fa-table-list"></i>
                        </div>
                        <div class="db-stat-info">
                            <h5 id="totalTablesCount">{{ $totalClearableTables ?? count($tables) }}</h5>
                            <span>Clearable Tables</span>
                        </div>
                    </div>
                    <div class="db-stat-box">
                        <div class="db-stat-icon" style="background: #ecfdf5; color: #10b981;">
                            <i class="fa-solid fa-database"></i>
                        </div>
                        <div class="db-stat-info">
                            <h5 id="totalRecordsCount">{{ number_format($totalRecords ?? 0) }}</h5>
                            <span>Total Stored Records</span>
                        </div>
                    </div>
                    <div class="db-stat-box">
                        <div class="db-stat-icon" style="background: #fff1f2; color: #ef4444;">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <div class="db-stat-info">
                            <h5 id="selectedTablesCount">0</h5>
                            <span>Selected Tables</span>
                        </div>
                    </div>
                    <div class="db-stat-box">
                        <div class="db-stat-icon" style="background: #fdf4ff; color: #c026d3;">
                            <i class="fa-solid fa-trash-arrow-up"></i>
                        </div>
                        <div class="db-stat-info">
                            <h5 id="selectedRecordsCount">0</h5>
                            <span>Records to Purge</span>
                        </div>
                    </div>
                </div>

                <!-- Interactive Toolbar & Presets -->
                <div class="db-toolbar">
                    <div class="db-search-wrapper">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="dbTableSearch" class="form-control db-search-input" placeholder="Search tables or modules (e.g. orders, products)...">
                    </div>
                    <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                        <span class="text-muted font-weight-bold mr-1" style="font-size: 0.8rem;">QUICK PRESETS:</span>
                        <button type="button" class="preset-btn" data-preset="orders">
                            <i class="fa-solid fa-cart-shopping mr-1 text-primary"></i> Orders & Sales
                        </button>
                        <button type="button" class="preset-btn" data-preset="catalog">
                            <i class="fa-solid fa-box-open mr-1 text-success"></i> Products & Catalog
                        </button>
                        <button type="button" class="preset-btn" data-preset="customers">
                            <i class="fa-solid fa-users mr-1 text-purple" style="color: #8b5cf6;"></i> Customers
                        </button>
                        <button type="button" class="preset-btn" data-preset="messages">
                            <i class="fa-solid fa-comments mr-1" style="color: #ec4899;"></i> Messages & Support
                        </button>
                        <button type="button" class="preset-btn" data-preset="logs">
                            <i class="fa-solid fa-file-lines mr-1 text-secondary"></i> Logs
                        </button>
                        <button type="button" class="preset-btn btn-dark text-white" id="selectAllTablesBtn" style="background: #1e293b; border-color: #1e293b;">
                            <i class="fa-solid fa-square-check mr-1"></i> Select All
                        </button>
                        <button type="button" class="preset-btn" id="deselectAllTablesBtn">
                            <i class="fa-solid fa-square mr-1"></i> Deselect All
                        </button>
                    </div>
                </div>

                <!-- Grouped Categories Grid -->
                <form id="bulkClearDatabaseForm">
                    @csrf
                    <div id="tableGroupsContainer">
                        @if(isset($tableGroups) && count($tableGroups) > 0)
                            @foreach($tableGroups as $groupName => $group)
                                <div class="db-group-card" data-group-slug="{{ Str::slug($groupName) }}">
                                    <div class="db-group-header">
                                        <div class="db-group-header-left">
                                            <div class="db-group-badge-icon" style="background-color: {{ $group['color'] }}15; color: {{ $group['color'] }};">
                                                <i class="{{ $group['icon'] }}"></i>
                                            </div>
                                            <div>
                                                <h5 class="db-group-title">{{ $groupName }}</h5>
                                                <p class="db-group-desc">{{ $group['description'] }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge badge-light group-total-records px-3 py-2" style="font-size: 0.8rem; font-weight: 700; color: #475569;">
                                                <span class="group-count-val">{{ number_format($group['total_count']) }}</span> records
                                            </span>
                                            <div class="custom-control custom-checkbox ml-2">
                                                <input type="checkbox" class="custom-control-input group-select-all" id="group_chk_{{ Str::slug($groupName) }}" data-group="{{ Str::slug($groupName) }}">
                                                <label class="custom-control-label font-weight-bold text-dark cursor-pointer user-select-none" for="group_chk_{{ Str::slug($groupName) }}">
                                                    Select Group
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-grid">
                                        @foreach($group['items'] as $item)
                                            <div class="table-checkbox-item" data-table="{{ $item['table'] }}" data-group="{{ Str::slug($groupName) }}" data-count="{{ $item['count'] }}">
                                                <div class="table-checkbox-info">
                                                    <input type="checkbox" name="tables[]" value="{{ $item['table'] }}" class="table-checkbox" id="tbl_{{ $item['table'] }}" data-count="{{ $item['count'] }}">
                                                    <div class="table-text">
                                                        <label class="table-title mb-0 cursor-pointer" for="tbl_{{ $item['table'] }}">{{ $item['label'] }}</label>
                                                        <span class="table-slug">{{ $item['table'] }}</span>
                                                    </div>
                                                </div>
                                                <span class="table-count-badge {{ $item['count'] > 1000 ? 'badge-high-data' : ($item['count'] > 0 ? 'badge-has-data' : 'badge-empty') }}" id="badge_{{ $item['table'] }}">
                                                    {{ number_format($item['count']) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fa-solid fa-circle-check text-success fa-3x mb-3"></i>
                                <h5>No clearable tables found.</h5>
                            </div>
                        @endif
                    </div>

                    <!-- Bottom Floating Actions Bar -->
                    <div class="db-clear-action-bar mt-4">
                        <div class="selected-summary">
                            <span class="selected-count-pill" id="actionPillCount">0 Selected</span>
                            <span class="text-muted font-weight-bold" style="font-size: 0.9rem;">
                                Ready to purge: <span class="text-danger font-weight-bold" id="actionRecordsCount">0</span> records
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" class="btn btn-outline-secondary" id="resetSelectionBtn" style="border-radius: 8px; font-weight: 600;">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                            </button>
                            <button type="button" class="btn-clear-db" id="bulkClearSubmitBtn" disabled>
                                <i class="fa-solid fa-trash-can"></i>
                                <span id="clearBtnText">Clear Selected Tables</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Initialize Select2 if present
        if ($.fn.select2) {
            $('#tableSelect').select2({
                placeholder: "-- Select a Table --",
                allowClear: true,
                width: '100%'
            });
        }

        // Quick action buttons logic (Cache, Optimize, Migrate, Seed)
        $('.quick-action-btn').on('click', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var btn = $(this);
            var originalText = btn.html();
            
            btn.html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Running...');
            btn.prop('disabled', true);
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                            confirmButtonColor: '#d33'
                        });
                    }
                },
                error: function(xhr) {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong!';
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: msg,
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });

        // Truncate Single Table Logic
        $('#truncateBtn').on('click', function() {
            var selectedTable = $('#tableSelect').val();
            
            if (!selectedTable) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Table Selected',
                    text: 'Please select a table to truncate first.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            Swal.fire({
                title: 'Are you absolutely sure?',
                html: "You are about to <strong class='text-danger'>PERMANENTLY DELETE ALL DATA</strong> in the <code class='text-danger font-weight-bold'>" + selectedTable + "</code> table!<br><br><span class='text-muted' style='font-size: 0.9rem;'>This action cannot be rolled back.</span>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, proceed to confirmation',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Second confirmation by typing table name
                    Swal.fire({
                        title: 'Confirm Table Name',
                        html: "Type <strong>" + selectedTable + "</strong> below to confirm truncation:",
                        input: 'text',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Truncate Table Now',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#64748b',
                        showLoaderOnConfirm: true,
                        preConfirm: (inputValue) => {
                            if (inputValue !== selectedTable) {
                                Swal.showValidationMessage('Table name does not match.');
                            } else {
                                return $.ajax({
                                    url: '{{ route("system-commands.truncate") }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        table_name: selectedTable
                                    }
                                }).then(response => {
                                    if(!response.success) {
                                        throw new Error(response.message);
                                    }
                                    return response;
                                }).catch(error => {
                                    Swal.showValidationMessage(
                                      `Request failed: ${error.responseJSON ? error.responseJSON.message : (error.message || 'Server error')}`
                                    );
                                });
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((finalResult) => {
                        if (finalResult.isConfirmed && finalResult.value) {
                            var resp = finalResult.value;
                            // Update badge count in UI if table exists in grid
                            var badge = $('#badge_' + selectedTable);
                            if (badge.length) {
                                badge.text('0').removeClass('badge-has-data badge-high-data').addClass('badge-empty');
                                var chk = $('#tbl_' + selectedTable);
                                chk.data('count', 0);
                                chk.closest('.table-checkbox-item').data('count', 0);
                            }
                            recalculateSelection();

                            Swal.fire({
                                icon: 'success',
                                title: 'Table Truncated!',
                                text: resp.message || 'Table data has been successfully cleared.',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                }
            });
        });

        // ==========================================
        // DATABASE BULK CLEAR INTERACTIVE LOGIC
        // ==========================================

        function recalculateSelection() {
            var selectedCheckboxes = $('.table-checkbox:checked');
            var selectedCount = selectedCheckboxes.length;
            var totalRecordsToPurge = 0;

            selectedCheckboxes.each(function() {
                var cnt = parseInt($(this).data('count')) || 0;
                totalRecordsToPurge += cnt;
            });

            // Update badges and UI counters
            $('#selectedTablesCount').text(selectedCount);
            $('#selectedRecordsCount').text(totalRecordsToPurge.toLocaleString());
            $('#actionPillCount').text(selectedCount + ' Selected');
            $('#actionRecordsCount').text(totalRecordsToPurge.toLocaleString());

            // Enable or disable clear button
            if (selectedCount > 0) {
                $('#bulkClearSubmitBtn').prop('disabled', false);
                $('#clearBtnText').text('Clear (' + selectedCount + ') Tables');
            } else {
                $('#bulkClearSubmitBtn').prop('disabled', true);
                $('#clearBtnText').text('Clear Selected Tables');
            }

            // Sync card active states
            $('.table-checkbox').each(function() {
                var itemCard = $(this).closest('.table-checkbox-item');
                if ($(this).is(':checked')) {
                    itemCard.addClass('is-selected');
                } else {
                    itemCard.removeClass('is-selected');
                }
            });

            // Sync Group checkboxes
            $('.db-group-card').each(function() {
                var groupCard = $(this);
                var totalInGroup = groupCard.find('.table-checkbox:visible').length;
                var checkedInGroup = groupCard.find('.table-checkbox:visible:checked').length;
                var groupSelectAll = groupCard.find('.group-select-all');

                if (totalInGroup > 0 && totalInGroup === checkedInGroup) {
                    groupSelectAll.prop('checked', true).prop('indeterminate', false);
                } else if (checkedInGroup > 0) {
                    groupSelectAll.prop('checked', false).prop('indeterminate', true);
                } else {
                    groupSelectAll.prop('checked', false).prop('indeterminate', false);
                }
            });
        }

        // Toggle checkbox on card click
        $(document).on('click', '.table-checkbox-item', function(e) {
            if ($(e.target).is('input[type="checkbox"]') || $(e.target).is('label')) {
                return; // default behavior for checkbox or label
            }
            var chk = $(this).find('.table-checkbox');
            chk.prop('checked', !chk.prop('checked'));
            recalculateSelection();
        });

        $(document).on('change', '.table-checkbox', function() {
            recalculateSelection();
        });

        // Group Select All toggle
        $(document).on('change', '.group-select-all', function() {
            var isChecked = $(this).is(':checked');
            var groupCard = $(this).closest('.db-group-card');
            groupCard.find('.table-checkbox:visible').prop('checked', isChecked);
            recalculateSelection();
        });

        // Master Select All
        $('#selectAllTablesBtn').on('click', function() {
            $('.table-checkbox:visible').prop('checked', true);
            recalculateSelection();
        });

        // Master Deselect All & Reset
        $('#deselectAllTablesBtn, #resetSelectionBtn').on('click', function() {
            $('.table-checkbox').prop('checked', false);
            $('.group-select-all').prop('checked', false).prop('indeterminate', false);
            $('.preset-btn').removeClass('active-preset');
            recalculateSelection();
        });

        // Live Search Filter
        $('#dbTableSearch').on('input', function() {
            var query = $(this).val().toLowerCase().trim();
            $('.db-group-card').each(function() {
                var groupCard = $(this);
                var hasVisibleItem = false;

                groupCard.find('.table-checkbox-item').each(function() {
                    var item = $(this);
                    var tableName = item.data('table').toLowerCase();
                    var tableTitle = item.find('.table-title').text().toLowerCase();

                    if (tableName.indexOf(query) !== -1 || tableTitle.indexOf(query) !== -1) {
                        item.show();
                        hasVisibleItem = true;
                    } else {
                        item.hide();
                    }
                });

                if (hasVisibleItem) {
                    groupCard.show();
                } else {
                    groupCard.hide();
                }
            });
            recalculateSelection();
        });

        // Presets Logic
        $('.preset-btn[data-preset]').on('click', function() {
            var preset = $(this).data('preset');
            $('.preset-btn').removeClass('active-preset');
            $(this).addClass('active-preset');

            // Map presets to group slugs
            var targetGroup = '';
            if (preset === 'orders') targetGroup = 'orders-sales';
            else if (preset === 'catalog') targetGroup = 'products-catalog';
            else if (preset === 'customers') targetGroup = 'customers-accounts';
            else if (preset === 'messages') targetGroup = 'communications-support';
            else if (preset === 'logs') targetGroup = 'logs-system-activity';

            // Uncheck all first, then check the target group
            $('.table-checkbox').prop('checked', false);
            if (targetGroup) {
                $('[data-group-slug="' + targetGroup + '"]').find('.table-checkbox').prop('checked', true);
            }
            recalculateSelection();
        });

        // ==========================================
        // SUBMIT BULK CLEAR WITH SWEETALERT2
        // ==========================================
        $('#bulkClearSubmitBtn').on('click', function() {
            var selectedBoxes = $('.table-checkbox:checked');
            if (selectedBoxes.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Tables Selected',
                    text: 'Please select at least one database table to clear.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            var selectedTables = [];
            var totalRecords = 0;
            var tableListHtml = "<div style='max-height: 220px; overflow-y: auto; text-align: left; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin: 15px 0;'>";
            tableListHtml += "<table class='table table-sm table-borderless mb-0' style='font-size: 0.85rem;'>";
            tableListHtml += "<thead style='border-bottom: 1px solid #e2e8f0;'><tr><th>Table Name</th><th class='text-right'>Records</th></tr></thead><tbody>";

            selectedBoxes.each(function() {
                var tbl = $(this).val();
                var cnt = parseInt($(this).data('count')) || 0;
                selectedTables.push(tbl);
                totalRecords += cnt;
                tableListHtml += "<tr><td><code class='text-danger'>" + tbl + "</code></td><td class='text-right font-weight-bold'>" + cnt.toLocaleString() + "</td></tr>";
            });

            tableListHtml += "</tbody></table></div>";

            // Step 1: Warning Overview Modal
            Swal.fire({
                title: 'Confirm Database Clear',
                html: "You are about to clear <strong>" + selectedTables.length + " table(s)</strong> with total <strong class='text-danger'>" + totalRecords.toLocaleString() + " records</strong>!" + tableListHtml + "<span class='text-danger font-weight-bold' style='font-size: 0.9rem;'><i class='fa-solid fa-triangle-exclamation mr-1'></i> This action is permanent and irreversible!</span>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Proceed to Confirmation',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Step 2: Final Confirmation by typing CLEAR
                    Swal.fire({
                        title: 'Type CLEAR to Confirm',
                        html: "To permanently purge the selected <strong>" + selectedTables.length + " table(s)</strong>, please type <strong class='text-danger'>CLEAR</strong> below:",
                        input: 'text',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Purge Selected Database Tables',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#64748b',
                        showLoaderOnConfirm: true,
                        preConfirm: (inputValue) => {
                            if (inputValue !== 'CLEAR') {
                                Swal.showValidationMessage('Please type CLEAR in capital letters to confirm.');
                            } else {
                                return $.ajax({
                                    url: '{{ route("system-commands.clear-database") }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        tables: selectedTables
                                    }
                                }).then(response => {
                                    if (!response.success) {
                                        throw new Error(response.message);
                                    }
                                    return response;
                                }).catch(error => {
                                    Swal.showValidationMessage(
                                        `Purge failed: ${error.responseJSON ? error.responseJSON.message : (error.message || 'Server error')}`
                                    );
                                });
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((finalResult) => {
                        if (finalResult.isConfirmed && finalResult.value) {
                            var resp = finalResult.value;
                            var cleared = resp.cleared_tables || selectedTables;

                            // Update badges and UI counts
                            cleared.forEach(function(tbl) {
                                var badge = $('#badge_' + tbl);
                                badge.text('0').removeClass('badge-has-data badge-high-data').addClass('badge-empty');
                                var chk = $('#tbl_' + tbl);
                                chk.data('count', 0);
                                chk.prop('checked', false);
                                chk.closest('.table-checkbox-item').data('count', 0).removeClass('is-selected');
                            });

                            // Recalculate group totals
                            $('.db-group-card').each(function() {
                                var groupCard = $(this);
                                var groupTotal = 0;
                                groupCard.find('.table-checkbox-item').each(function() {
                                    groupTotal += parseInt($(this).data('count')) || 0;
                                });
                                groupCard.find('.group-count-val').text(groupTotal.toLocaleString());
                            });

                            // Recalculate global records count
                            var newGlobalTotal = 0;
                            $('.table-checkbox-item').each(function() {
                                newGlobalTotal += parseInt($(this).data('count')) || 0;
                            });
                            $('#totalRecordsCount').text(newGlobalTotal.toLocaleString());

                            recalculateSelection();

                            Swal.fire({
                                icon: 'success',
                                title: 'Database Purged!',
                                text: resp.message || 'Selected tables have been cleared successfully.',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                }
            });
        });

        // Initialize state
        recalculateSelection();
    });
</script>
@endsection
