@extends('layouts.Backend.master')
@section('title', 'INVESTORS & PARTNERS PORTFOLIO')

@section('style')
<style>
    /* =========================================================
       INVESTORS PORTFOLIO - MODERN THEME SYSTEM (LIGHT & DARK)
       Bootstrap 4 Grid & Components with Full Dark Mode Support
       ========================================================= */
    :root {
        --f-bg-page: #f8fafc;
        --f-card-bg: #ffffff;
        --f-card-hover-bg: #ffffff;
        --f-card-border: #e2e8f0;
        --f-card-hover-border: #cbd5e1;
        --f-card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.06);
        --f-text-title: #0f172a;
        --f-text-body: #334155;
        --f-text-muted: #64748b;
        --f-input-bg: #ffffff;
        --f-input-border: #cbd5e1;
        --f-input-text: #0f172a;
        --f-input-placeholder: #94a3b8;
        --f-panel-bg: #ffffff;
        --f-pill-group-bg: #f1f5f9;
        --f-pill-text: #64748b;
        --f-pill-hover-text: #0f172a;
        --f-pill-hover-bg: #e2e8f0;
        --f-item-bg: #ffffff;
        --f-item-hover-bg: #f8fafc;
        --f-item-border: #e2e8f0;
        --f-item-hover-border: #cbd5e1;
        --f-badge-method-bg: #f1f5f9;
        --f-badge-method-text: #475569;
        --f-badge-method-border: #e2e8f0;
        --f-track-bg: #e2e8f0;
        --f-modal-bg: #ffffff;
        --f-modal-border: #e2e8f0;
        --f-modal-header-border: #e2e8f0;
        --f-dropzone-bg: #f8fafc;
        --f-dropzone-border: #cbd5e1;
        --f-color-scheme: light;
        --f-btn-sec-bg: #f1f5f9;
        --f-btn-sec-border: #e2e8f0;
        --f-btn-sec-text: #334155;
        --f-btn-sec-hover-bg: #e2e8f0;
        --f-btn-sec-hover-text: #0f172a;
        --f-select-arrow: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    }

    body.dark-mode {
        --f-bg-page: #0f172a;
        --f-card-bg: #1e293b;
        --f-card-hover-bg: #24344d;
        --f-card-border: #334155;
        --f-card-hover-border: #475569;
        --f-card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.45);
        --f-text-title: #f8fafc;
        --f-text-body: #e2e8f0;
        --f-text-muted: #94a3b8;
        --f-input-bg: #0f172a;
        --f-input-border: #334155;
        --f-input-text: #f8fafc;
        --f-input-placeholder: #64748b;
        --f-panel-bg: #1e293b;
        --f-pill-group-bg: #0f172a;
        --f-pill-text: #94a3b8;
        --f-pill-hover-text: #f8fafc;
        --f-pill-hover-bg: #1e293b;
        --f-item-bg: #1e293b;
        --f-item-hover-bg: #24344d;
        --f-item-border: #334155;
        --f-item-hover-border: #475569;
        --f-badge-method-bg: #0f172a;
        --f-badge-method-text: #cbd5e1;
        --f-badge-method-border: #334155;
        --f-track-bg: #0f172a;
        --f-modal-bg: #1e293b;
        --f-modal-border: #334155;
        --f-modal-header-border: #334155;
        --f-dropzone-bg: #0f172a;
        --f-dropzone-border: #334155;
        --f-color-scheme: dark;
        --f-btn-sec-bg: #1e293b;
        --f-btn-sec-border: #334155;
        --f-btn-sec-text: #cbd5e1;
        --f-btn-sec-hover-bg: #334155;
        --f-btn-sec-hover-text: #ffffff;
        --f-select-arrow: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    }

    .finance-wrapper {
        color: var(--f-text-body);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        transition: color 0.25s ease;
    }

    .f-text-title { color: var(--f-text-title) !important; }
    .f-text-muted { color: var(--f-text-muted) !important; }

    /* Theme Card */
    .f-card {
        background-color: var(--f-card-bg) !important;
        border: 1px solid var(--f-card-border) !important;
        border-radius: 16px;
        box-shadow: var(--f-card-shadow);
        transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.22s ease, box-shadow 0.22s ease, background-color 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .f-card:hover {
        border-color: var(--f-card-hover-border) !important;
    }

    /* Top KPI Stat Cards */
    .kpi-card {
        padding: 20px 22px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 128px;
    }
    .kpi-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        opacity: 0.95;
    }
    .kpi-net::before { background: linear-gradient(90deg, #10b981, #06b6d4); }
    .kpi-investors::before { background: linear-gradient(90deg, #6366f1, #a855f7); }
    .kpi-revenue::before { background: linear-gradient(90deg, #059669, #34d399); }
    .kpi-expense::before { background: linear-gradient(90deg, #e11d48, #fb7185); }

    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .icon-cyan {
        background: rgba(6, 182, 212, 0.14);
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.28);
    }
    .icon-indigo {
        background: rgba(99, 102, 241, 0.14);
        color: #6366f1;
        border: 1px solid rgba(99, 102, 241, 0.28);
    }
    .icon-emerald {
        background: rgba(16, 185, 129, 0.14);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.28);
    }
    .icon-rose {
        background: rgba(244, 63, 94, 0.14);
        color: #f43f5e;
        border: 1px solid rgba(244, 63, 94, 0.28);
    }

    body.dark-mode .icon-cyan { color: #38bdf8; }
    body.dark-mode .icon-indigo { color: #818cf8; }
    body.dark-mode .icon-emerald { color: #34d399; }
    body.dark-mode .icon-rose { color: #fb7185; }

    .kpi-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--f-text-muted);
        margin-bottom: 4px;
    }
    .kpi-value {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--f-text-title);
        line-height: 1.15;
        letter-spacing: -0.02em;
    }

    /* Buttons */
    .f-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 18px;
        font-size: 0.84rem;
        font-weight: 600;
        border-radius: 11px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid transparent;
        text-decoration: none !important;
        white-space: nowrap;
    }
    .f-btn:active { transform: scale(0.98); }

    .f-btn-secondary {
        background-color: var(--f-btn-sec-bg) !important;
        border: 1px solid var(--f-btn-sec-border) !important;
        color: var(--f-btn-sec-text) !important;
    }
    .f-btn-secondary:hover {
        background-color: var(--f-btn-sec-hover-bg) !important;
        color: var(--f-btn-sec-hover-text) !important;
        transform: translateY(-1px);
    }

    .f-btn-investment-add {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        border: none;
    }
    .f-btn-investment-add:hover {
        background: linear-gradient(135deg, #047857 0%, #059669 100%);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45);
        transform: translateY(-1px);
        color: #ffffff !important;
    }

    /* Filter Panel */
    .filter-panel {
        background-color: var(--f-panel-bg) !important;
        border: 1px solid var(--f-card-border) !important;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: var(--f-card-shadow);
    }

    .f-input-group {
        position: relative;
    }
    .f-input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--f-text-muted);
        font-size: 13px;
        pointer-events: none;
    }
    .f-input {
        background-color: var(--f-input-bg) !important;
        border: 1px solid var(--f-input-border) !important;
        color: var(--f-input-text) !important;
        font-size: 0.83rem !important;
        border-radius: 10px !important;
        padding: 8px 12px 8px 34px !important;
        height: 40px !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        color-scheme: var(--f-color-scheme);
    }
    .f-input::placeholder {
        color: var(--f-input-placeholder) !important;
        opacity: 0.85;
    }
    .f-input:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18) !important;
        outline: none !important;
    }

    .f-select {
        background-color: var(--f-input-bg) !important;
        border: 1px solid var(--f-input-border) !important;
        color: var(--f-input-text) !important;
        font-size: 0.83rem !important;
        border-radius: 10px !important;
        height: 40px !important;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: var(--f-select-arrow);
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px !important;
        padding-left: 12px !important;
        color-scheme: var(--f-color-scheme);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .f-select:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18) !important;
        outline: none !important;
    }
    .f-select option {
        background-color: var(--f-card-bg);
        color: var(--f-text-title);
    }

    /* Investor Cards Grid */
    .investor-card {
        padding: 22px;
        border-radius: 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
        overflow: hidden;
        border-top: 3px solid transparent !important;
    }
    .investor-card:hover {
        transform: translateY(-3px);
        border-color: #6366f1 !important;
        box-shadow: 0 14px 30px -5px rgba(99, 102, 241, 0.2) !important;
    }
    .investor-card.active-partner {
        border-top-color: #10b981 !important;
    }
    .investor-card.inactive-partner {
        border-top-color: #94a3b8 !important;
        opacity: 0.85;
    }

    .investor-avatar-box {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
        flex-shrink: 0;
    }

    .investor-net-val {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }

    .investor-stat-box {
        background: var(--f-pill-group-bg);
        border-radius: 12px;
        padding: 10px 14px;
        border: 1px solid var(--f-card-border);
        transition: all 0.2s ease;
    }
    .investor-stat-box:hover {
        border-color: var(--f-card-hover-border);
    }

    .inv-progress-track {
        height: 6px;
        background-color: var(--f-track-bg);
        border-radius: 999px;
        overflow: hidden;
        margin-top: 6px;
    }
    .inv-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #6366f1, #3b82f6);
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Badges */
    .badge-income {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.35);
        padding: 4px 10px;
        font-weight: 600;
    }
    body.dark-mode .badge-income {
        background: rgba(6, 78, 59, 0.6);
        color: #34d399;
    }
    .badge-expense {
        background: rgba(244, 63, 94, 0.15);
        color: #e11d48;
        border: 1px solid rgba(244, 63, 94, 0.35);
        padding: 4px 10px;
        font-weight: 600;
    }
    body.dark-mode .badge-expense {
        background: rgba(136, 19, 55, 0.6);
        color: #fb7185;
    }
    .badge-method {
        background-color: var(--f-badge-method-bg);
        color: var(--f-badge-method-text);
        border: 1px solid var(--f-badge-method-border);
        font-size: 0.72rem;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 6px;
    }

    /* Modal Styling */
    .f-modal .modal-content {
        background-color: var(--f-modal-bg) !important;
        border: 1px solid var(--f-modal-border) !important;
        border-radius: 20px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        overflow: hidden;
    }
    .f-modal .modal-header {
        border-bottom: 1px solid var(--f-modal-header-border);
        padding: 18px 24px;
    }
    .f-modal .modal-body {
        padding: 24px;
    }
    .f-modal .modal-footer {
        border-top: 1px solid var(--f-modal-header-border);
        padding: 16px 24px;
    }
    .f-modal-input, .f-modal-select, .f-modal-textarea {
        background-color: var(--f-input-bg) !important;
        border: 1px solid var(--f-input-border) !important;
        color: var(--f-input-text) !important;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        width: 100%;
        transition: all 0.2s ease;
        color-scheme: var(--f-color-scheme);
    }
    .f-modal-input:focus, .f-modal-select:focus, .f-modal-textarea:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18) !important;
        outline: none;
    }

    .upload-dropzone {
        border: 2px dashed var(--f-dropzone-border);
        background: var(--f-dropzone-bg);
        border-radius: 14px;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    .upload-dropzone:hover {
        border-color: #6366f1;
        background: rgba(99, 102, 241, 0.04);
    }

    /* History Table inside Modal */
    .history-scroll-box {
        max-height: 420px;
        overflow-y: auto;
    }
    .history-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--f-text-muted);
        border-top: none;
        border-bottom: 2px solid var(--f-card-border);
        padding: 12px 14px;
        background-color: var(--f-card-bg);
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .history-table td {
        font-size: 13px;
        color: var(--f-text-body);
        border-top: 1px solid var(--f-card-border);
        padding: 12px 14px;
        vertical-align: middle;
    }

    /* Custom Scrollbar */
    .history-scroll-box::-webkit-scrollbar {
        width: 6px;
    }
    .history-scroll-box::-webkit-scrollbar-thumb {
        background: var(--f-card-border);
        border-radius: 999px;
    }
    .history-scroll-box::-webkit-scrollbar-thumb:hover {
        background: #6366f1;
    }
</style>
@endsection

@section('content')
<div class="finance-wrapper py-2">

    {{-- Top Action & Header Bar --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12 mb-3 mb-md-0">
            <div class="d-flex align-items-center">
                <div class="kpi-icon-box icon-indigo mr-3" style="width: 48px; height: 48px; border-radius: 12px;">
                    <i class="fa-solid fa-users-gear font-20"></i>
                </div>
                <div>
                    <h3 class="font-weight-bold f-text-title mb-0" style="letter-spacing: -0.02em;">Investors &amp; Capital Partners</h3>
                    <p class="f-text-muted mb-0 font-12">Manage business equity partners, capital deposits, withdrawals &amp; individual ledgers</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 text-md-right">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end justify-content-start" style="gap: 8px;">
                <a href="{{ route('admin.finance.index') }}" class="f-btn f-btn-secondary">
                    <i class="fa-solid fa-arrow-left font-12"></i> Finance Hub
                </a>
                <button type="button" class="f-btn f-btn-investment-add" data-toggle="modal" data-target="#addInvestorModal">
                    <i class="fa-solid fa-user-plus font-13"></i> Add New Investor
                </button>
            </div>
        </div>
    </div>

    {{-- Top 4 KPI Portfolio Cards Grid --}}
    <div class="row mb-4">
        {{-- Card 1: Active Treasury Capital --}}
        <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
            <div class="f-card kpi-card kpi-net">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Active Treasury Capital</span>
                        <div class="kpi-icon-box icon-cyan">
                            <i class="fa-solid fa-vault"></i>
                        </div>
                    </div>
                    <div class="kpi-value" style="color: #10b981;">
                        ৳{{ number_format($investmentBalance, 2) }}
                    </div>
                </div>
                <div>
                    <span class="badge badge-pill badge-income font-11">
                        <i class="fa-solid fa-circle-check mr-1"></i> Net Working Capital
                    </span>
                </div>
            </div>
        </div>

        {{-- Card 2: Registered Partners --}}
        <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
            <div class="f-card kpi-card kpi-investors">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Registered Partners</span>
                        <div class="kpi-icon-box icon-indigo">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="kpi-value" style="color: #6366f1;">
                        {{ $totalInvestorsCount }} <span style="font-size: 1rem; color: var(--f-text-muted); font-weight: 500;">Partners</span>
                    </div>
                </div>
                <div>
                    <span class="badge badge-pill badge-method font-11">
                        <i class="fa-solid fa-handshake mr-1"></i> Equity Partners
                    </span>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Injected Capital --}}
        <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
            <div class="f-card kpi-card kpi-revenue">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Total Injected Capital</span>
                        <div class="kpi-icon-box icon-emerald">
                            <i class="fa-solid fa-arrow-down-left"></i>
                        </div>
                    </div>
                    <div class="kpi-value" style="color: #10b981;">
                        +৳{{ number_format($totalInvested, 2) }}
                    </div>
                </div>
                <div>
                    <span class="badge badge-pill badge-income font-11">
                        <i class="fa-solid fa-circle-arrow-down mr-1"></i> Cumulative Deposits
                    </span>
                </div>
            </div>
        </div>

        {{-- Card 4: Total Withdrawn Capital --}}
        <div class="col-xl-3 col-md-6 col-12">
            <div class="f-card kpi-card kpi-expense">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Capital Withdrawn</span>
                        <div class="kpi-icon-box icon-rose">
                            <i class="fa-solid fa-arrow-up-right"></i>
                        </div>
                    </div>
                    <div class="kpi-value" style="color: #f43f5e;">
                        -৳{{ number_format($totalWithdrawn, 2) }}
                    </div>
                </div>
                <div>
                    <span class="badge badge-pill badge-expense font-11">
                        <i class="fa-solid fa-circle-arrow-up mr-1"></i> Capital Pay-outs
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search Panel --}}
    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" action="{{ route('admin.finance.investors') }}" class="filter-panel" id="investorFilterForm">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-6 col-12 mb-2 mb-md-0">
                        <div class="f-input-group">
                            <i class="fa-solid fa-magnifying-glass f-input-icon"></i>
                            <input type="text" name="search" class="form-control f-input" placeholder="Search by partner name, phone, email, notes..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-6">
                        <select name="sort" class="form-control f-select" onchange="this.form.submit();">
                            <option value="highest_balance" {{ request('sort') == 'highest_balance' ? 'selected' : '' }}>Sort: Highest Capital</option>
                            <option value="highest_invested" {{ request('sort') == 'highest_invested' ? 'selected' : '' }}>Sort: Most Invested</option>
                            <option value="most_transactions" {{ request('sort') == 'most_transactions' ? 'selected' : '' }}>Sort: Most Operations</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort: Newest First</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Sort: Name (A-Z)</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <select name="status" class="form-control f-select" onchange="this.form.submit();">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Partners</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Partners</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-12 mt-2 mt-lg-0 text-right">
                        @if(request()->hasAny(['search', 'sort', 'status']))
                            <a href="{{ route('admin.finance.investors') }}" class="f-btn f-btn-secondary w-100">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                            </a>
                        @else
                            <button type="submit" class="f-btn f-btn-secondary w-100">
                                <i class="fa-solid fa-filter mr-1"></i> Filter
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Investors Grid --}}
    <div class="row">
        @forelse($allInvestors as $inv)
            @php
                $initials = collect(explode(' ', $inv->name))->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
            @endphp
            <div class="col-xl-4 col-lg-6 col-12 mb-4">
                <div class="f-card investor-card {{ $inv->status === 'active' ? 'active-partner' : 'inactive-partner' }}">
                    <div>
                        {{-- Top Header: Avatar, Name & Status --}}
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="d-flex align-items-center overflow-hidden mr-2">
                                <div class="investor-avatar-box mr-3">
                                    {{ $initials ?: 'P' }}
                                </div>
                                <div class="overflow-hidden">
                                    <h5 class="font-weight-bold f-text-title mb-0 text-truncate">
                                        <a href="{{ route('admin.finance.investors.detail', $inv->id) }}" class="f-text-title text-decoration-none" title="View partner profile">
                                            {{ $inv->name }}
                                        </a>
                                    </h5>
                                    <small class="f-text-muted font-11 d-block text-truncate mt-0.5">
                                        @if($inv->phone)
                                            <i class="fa-solid fa-phone mr-1"></i> {{ $inv->phone }}
                                        @elseif($inv->email)
                                            <i class="fa-solid fa-envelope mr-1"></i> {{ $inv->email }}
                                        @else
                                            <i class="fa-solid fa-calendar mr-1"></i> Partner since {{ $inv->created_at ? $inv->created_at->format('M Y') : 'N/A' }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <span class="badge badge-pill {{ $inv->status === 'active' ? 'badge-income' : 'badge-method' }} font-11">
                                <i class="fa-solid {{ $inv->status === 'active' ? 'fa-circle-check' : 'fa-circle-pause' }} mr-1"></i> {{ ucfirst($inv->status) }}
                            </span>
                        </div>

                        {{-- Hero Balance Section --}}
                        <div class="mb-3 pt-1">
                            <span class="f-text-muted font-11 font-weight-bold text-uppercase d-block" style="letter-spacing: 0.05em;">
                                <i class="fa-solid fa-vault mr-1 opacity-75"></i> Net Active Capital
                            </span>
                            <div class="d-flex align-items-baseline" style="gap: 5px;">
                                <span class="font-18 font-weight-bold" style="color: #6366f1;">৳</span>
                                <span class="investor-net-val" style="{{ $inv->active_balance < 0 ? 'color: #f43f5e;' : ($inv->active_balance > 0 ? 'color: #10b981;' : 'color: var(--f-text-title);') }}">
                                    {{ number_format(abs($inv->active_balance), 2) }}
                                </span>
                                @if($inv->active_balance < 0)
                                    <span class="badge badge-pill badge-expense font-10 ml-2">Deficit</span>
                                @endif
                            </div>
                        </div>

                        {{-- Injected vs Withdrawn Stats --}}
                        <div class="row no-gutters mb-3" style="gap: 8px;">
                            <div class="col investor-stat-box">
                                <span class="f-text-muted font-11 d-block mb-1">
                                    <i class="fa-solid fa-arrow-down-left mr-1 text-success"></i> Total Injected
                                </span>
                                <strong class="font-13" style="color: #10b981;">+৳{{ number_format($inv->total_invested, 2) }}</strong>
                            </div>
                            <div class="col investor-stat-box">
                                <span class="f-text-muted font-11 d-block mb-1">
                                    <i class="fa-solid fa-arrow-up-right mr-1 text-danger"></i> Withdrawn
                                </span>
                                <strong class="font-13" style="color: #f43f5e;">-৳{{ number_format($inv->total_withdrawn, 2) }}</strong>
                            </div>
                        </div>

                        {{-- Portfolio Share Progress Bar --}}
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between font-11 mb-1">
                                <span class="f-text-muted">Working Treasury Share</span>
                                <strong class="f-text-title font-12">{{ $inv->share_pct }}%</strong>
                            </div>
                            <div class="inv-progress-track">
                                <div class="inv-progress-fill" style="width: {{ min(100, $inv->share_pct) }}%;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Action Buttons --}}
                    <div class="pt-3 border-top d-flex align-items-center justify-content-between" style="border-color: var(--f-card-border) !important; gap: 8px;">
                        <button type="button" class="f-btn f-btn-secondary flex-grow-1 btn-open-history" data-id="{{ $inv->id }}" data-name="{{ $inv->name }}" title="Quick view investment ledger">
                            <i class="fa-solid fa-clock-rotate-left mr-1 text-info"></i> History ({{ $inv->tx_count }})
                        </button>
                        <a href="{{ route('admin.finance.investors.detail', $inv->id) }}" class="f-btn f-btn-secondary" title="View dedicated partner profile page">
                            <i class="fa-solid fa-arrow-up-right-from-square font-12"></i> Profile
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="f-card p-5 mx-auto" style="max-width: 520px;">
                    <div class="kpi-icon-box icon-indigo mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem; border-radius: 18px;">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <h5 class="font-weight-bold f-text-title mb-2">No Partners Found</h5>
                    <p class="f-text-muted font-13 mb-4">No registered investors match your criteria. Register your first business partner to start tracking capital and equity.</p>
                    <button type="button" class="f-btn f-btn-investment-add" data-toggle="modal" data-target="#addInvestorModal">
                        <i class="fa-solid fa-user-plus mr-1"></i> Register First Investor
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- =========================================================================
     MODAL 1: ADD NEW INVESTOR (WITH OPTIONAL INITIAL CAPITAL INJECTION & VOUCHER)
     ========================================================================= --}}
<div class="modal fade f-modal" id="addInvestorModal" tabindex="-1" role="dialog" aria-labelledby="addInvestorModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 720px !important; width: 95% !important;" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box icon-indigo mr-3" style="width: 44px; height: 44px; border-radius: 12px;">
                        <i class="fa-solid fa-user-plus font-18"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold f-text-title mb-0" id="addInvestorModalTitle">Register New Investor / Partner</h5>
                        <small class="f-text-muted font-11">Record partnership credentials and optional initial capital injection</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: var(--f-text-muted);">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="addInvestorForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Row 1: Name & Phone --}}
                    <div class="row">
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="font-12 font-weight-bold f-text-title mb-1">
                                Investor / Partner Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="newInvName" class="f-modal-input" required placeholder="e.g. Sabbir Ahamed">
                        </div>
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="font-12 font-weight-bold f-text-title mb-1">
                                Contact Phone Number (Optional)
                            </label>
                            <input type="text" name="phone" id="newInvPhone" class="f-modal-input" placeholder="e.g. 017XXXXXXXX">
                        </div>
                    </div>

                    {{-- Row 2: Email & NID --}}
                    <div class="row">
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="font-12 font-weight-bold f-text-title mb-1">
                                Email Address (Optional)
                            </label>
                            <input type="email" name="email" id="newInvEmail" class="f-modal-input" placeholder="e.g. partner@example.com">
                        </div>
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="font-12 font-weight-bold f-text-title mb-1">
                                NID / Passport / Legal ID (Optional)
                            </label>
                            <input type="text" name="nid_or_passport" id="newInvNid" class="f-modal-input" placeholder="e.g. 199XXXXXXXXXX">
                        </div>
                    </div>

                    {{-- Row 3: Initial Capital Injection Card --}}
                    <div class="f-card p-3 mb-3" style="background: rgba(99, 102, 241, 0.04); border: 1px dashed rgba(99, 102, 241, 0.35) !important; border-radius: 14px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-12 font-weight-bold text-uppercase" style="color: #6366f1; letter-spacing: 0.05em;">
                                <i class="fa-solid fa-coins mr-1"></i> Initial Capital Deposit (Optional)
                            </span>
                            <span class="badge badge-income font-10">Optional Inflow</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12 form-group mb-2 mb-md-0">
                                <label class="font-11 f-text-muted mb-1">Deposit Amount (৳ BDT)</label>
                                <input type="number" step="0.01" min="0" name="initial_investment" id="newInvInitialAmount" class="f-modal-input" placeholder="0.00">
                            </div>
                            <div class="col-md-6 col-12 form-group mb-0">
                                <label class="font-11 f-text-muted mb-1">Deposit Method</label>
                                <select name="payment_method" class="f-modal-select">
                                    <option value="Bank Transfer">Bank Transfer (Corporate / Personal)</option>
                                    <option value="Cash">Cash in Hand</option>
                                    <option value="bKash/Nagad">bKash / Nagad (MFS)</option>
                                    <option value="Cheque">Cheque / Pay Order</option>
                                </select>
                            </div>
                        </div>

                        {{-- Voucher Slip Attachment --}}
                        <div class="mt-3">
                            <label class="font-11 f-text-muted mb-1">Deposit Receipt / Voucher Slip (Optional)</label>
                            <div class="upload-dropzone" onclick="document.getElementById('newInvReceiptFile').click();">
                                <input type="file" name="receipt_image" id="newInvReceiptFile" accept="image/*" class="d-none" onchange="previewNewInvReceipt(this)">
                                <div id="newInvReceiptPlaceholder">
                                    <i class="fa-solid fa-cloud-arrow-up font-20 text-muted mb-1 d-block"></i>
                                    <span class="font-12 f-text-body font-weight-bold d-block">Click to upload slip or voucher</span>
                                    <small class="f-text-muted font-11">JPG, PNG, WebP up to 5MB</small>
                                </div>
                                <div id="newInvReceiptPreview" class="d-none">
                                    <img id="newInvReceiptPreviewImg" src="" alt="Voucher Slip Preview" style="max-height: 80px; border-radius: 8px;" class="mb-1">
                                    <small class="text-info d-block font-11"><i class="fa-solid fa-check mr-1"></i> File selected. Click to replace.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Row 4: Notes / Terms --}}
                    <div class="form-group mb-0">
                        <label class="font-12 font-weight-bold f-text-title mb-1">
                            Partnership Notes &amp; Terms (Optional)
                        </label>
                        <textarea name="notes" rows="2" class="f-modal-textarea" placeholder="e.g. Seed investor, 20% equity agreement, monthly profit distribution..."></textarea>
                    </div>
                </div>

                <div class="modal-footer d-flex align-items-center justify-content-end" style="gap: 8px;">
                    <button type="button" class="f-btn f-btn-secondary" data-dismiss="modal">
                        <i class="fa-solid fa-xmark mr-1"></i> Cancel
                    </button>
                    <button type="submit" class="f-btn f-btn-investment-add" id="btnSubmitNewInvestor">
                        <i class="fa-solid fa-check mr-1"></i> <span>Confirm &amp; Register Partner</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODAL 2: INSTANT INVESTOR HISTORY & TIMELINE MODAL
     ========================================================================= --}}
<div class="modal fade f-modal" id="investorHistoryModal" tabindex="-1" role="dialog" aria-labelledby="invHistoryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 960px !important; width: 95% !important;" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box icon-indigo mr-3" style="width: 44px; height: 44px; border-radius: 12px;">
                        <i class="fa-solid fa-clock-rotate-left font-18"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold f-text-title mb-0" id="invHistoryPartnerName">Investor Timeline</h5>
                        <small class="f-text-muted font-11" id="invHistoryPartnerMeta">Capital deposits, withdrawals, and vouchers</small>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <a href="#" id="btnHistoryFullProfileLink" class="f-btn f-btn-secondary font-12 py-1 px-2.5">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Full Profile
                    </a>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: var(--f-text-muted);">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>

            <div class="modal-body p-3 p-md-4">
                {{-- Quick Summary Strip --}}
                <div class="row mb-3" style="gap: 0;">
                    <div class="col-md-4 col-12 mb-2 mb-md-0">
                        <div class="investor-stat-box">
                            <span class="f-text-muted font-11 d-block mb-1">
                                <i class="fa-solid fa-vault mr-1 text-info"></i> Net Active Capital
                            </span>
                            <strong class="font-18" id="modalNetBalanceVal" style="color: #10b981;">৳0.00</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="investor-stat-box">
                            <span class="f-text-muted font-11 d-block mb-1">
                                <i class="fa-solid fa-arrow-down-left mr-1 text-success"></i> Total Deposited
                            </span>
                            <strong class="font-15" id="modalTotalInvestedVal" style="color: #10b981;">৳0.00</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="investor-stat-box">
                            <span class="f-text-muted font-11 d-block mb-1">
                                <i class="fa-solid fa-arrow-up-right mr-1 text-danger"></i> Total Withdrawn
                            </span>
                            <strong class="font-15" id="modalTotalWithdrawnVal" style="color: #f43f5e;">৳0.00</strong>
                        </div>
                    </div>
                </div>

                {{-- Transactions History Table --}}
                <div class="f-card overflow-hidden">
                    <div class="history-scroll-box">
                        <table class="table history-table mb-0">
                            <thead>
                                <tr>
                                    <th>Date &amp; ID</th>
                                    <th>Operation</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Notes / Details</th>
                                    <th>Voucher</th>
                                </tr>
                            </thead>
                            <tbody id="investorHistoryTableBody">
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Loading transactions...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer d-flex align-items-center justify-content-end">
                <button type="button" class="f-btn f-btn-secondary" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox preview for receipts --}}
<div class="modal fade f-modal" id="historyReceiptPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 600px !important;" role="document">
        <div class="modal-content" style="border-radius: 16px !important; background: #000;">
            <div class="modal-header py-2 px-3 border-0 d-flex justify-content-between align-items-center bg-dark text-white">
                <span class="font-12 font-weight-bold">Voucher / Slip Preview</span>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-2 text-center">
                <img id="historyLightboxImg" src="" alt="Voucher Slip" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function previewNewInvReceipt(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#newInvReceiptPreviewImg').attr('src', e.target.result);
                $('#newInvReceiptPlaceholder').addClass('d-none');
                $('#newInvReceiptPreview').removeClass('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // =========================================================
        // 1. Submit New Investor Form via AJAX
        // =========================================================
        $('#addInvestorForm').on('submit', function(e) {
            e.preventDefault();

            const name = $('#newInvName').val().trim();
            if (!name) {
                Swal.fire('Name Required', 'Please enter investor or partner name.', 'warning');
                return;
            }

            const $btn = $('#btnSubmitNewInvestor');
            const origHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...');

            const formData = new FormData(this);

            $.ajax({
                url: `{{ route('admin.finance.investors.store') }}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#addInvestorModal').modal('hide');
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: res.message || 'Investor registered successfully!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(origHtml);
                    let msg = 'Failed to register investor.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Error',
                        html: msg
                    });
                }
            });
        });

        // =========================================================
        // 2. Open Instant Investor History Modal via AJAX
        // =========================================================
        $('.btn-open-history').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            $('#invHistoryPartnerName').text(`${name} · Investment History`);
            $('#invHistoryPartnerMeta').text('Loading ledger entries and vouchers...');
            $('#btnHistoryFullProfileLink').attr('href', `{{ url('admin/finance/investors') }}/${id}`);

            const $tbody = $('#investorHistoryTableBody');
            $tbody.html(`
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Fetching records for ${name}...
                    </td>
                </tr>
            `);

            $('#investorHistoryModal').modal('show');

            $.ajax({
                url: `{{ url('admin/finance/investors') }}/${id}/history-ajax`,
                type: 'GET',
                success: function(res) {
                    $('#invHistoryPartnerMeta').text(`${res.metrics.count} total transactions · Phone: ${res.investor.phone}`);
                    $('#modalNetBalanceVal').text(res.metrics.formattedBalance);
                    $('#modalTotalInvestedVal').text(res.metrics.formattedInvested);
                    $('#modalTotalWithdrawnVal').text(res.metrics.formattedWithdrawn);

                    if (!res.transactions || res.transactions.length === 0) {
                        $tbody.html(`
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-circle-info mr-1"></i> No transactions recorded yet for this partner.
                                </td>
                            </tr>
                        `);
                        return;
                    }

                    let html = '';
                    res.transactions.forEach(function(tx) {
                        const isDeposit = tx.entry_type === 'INCOME';
                        const badgeClass = isDeposit ? 'badge-income' : 'badge-expense';
                        const sign = isDeposit ? '+' : '-';
                        const amountColor = isDeposit ? '#10b981' : '#f43f5e';
                        const typeLabel = isDeposit ? 'Capital Deposit' : 'Capital Withdrawn';

                        let receiptHtml = '<span class="text-muted font-11">None</span>';
                        if (tx.has_receipt && tx.receipt_url) {
                            receiptHtml = `
                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-2 font-11 btn-preview-receipt" data-url="${tx.receipt_url}">
                                    <i class="fa-solid fa-receipt mr-1"></i> View Slip
                                </button>
                            `;
                        }

                        html += `
                            <tr>
                                <td>
                                    <span class="font-12 font-weight-bold f-text-title d-block">${tx.date_formatted}</span>
                                    <small class="text-muted">ID: #FT-${String(tx.id).padStart(5, '0')}</small>
                                </td>
                                <td>
                                    <span class="badge badge-pill ${badgeClass} font-10">
                                        <i class="fa-solid ${isDeposit ? 'fa-arrow-down-left' : 'fa-arrow-up-right'} mr-1"></i> ${typeLabel}
                                    </span>
                                </td>
                                <td>
                                    <strong class="font-14" style="color: ${amountColor};">${sign}${tx.formatted_amount}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-method font-11">
                                        <i class="fa-solid fa-building-columns mr-1 text-muted"></i> ${tx.payment_method}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-12 f-text-body">${tx.notes || '<span class="text-muted italic font-11">None</span>'}</span>
                                </td>
                                <td>
                                    ${receiptHtml}
                                </td>
                            </tr>
                        `;
                    });

                    $tbody.html(html);
                },
                error: function() {
                    $tbody.html(`
                        <tr>
                            <td colspan="6" class="text-center py-4 text-danger">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Failed to load investment timeline. Please try again.
                            </td>
                        </tr>
                    `);
                }
            });
        });

        // Preview voucher slip in lightbox
        $(document).on('click', '.btn-preview-receipt', function() {
            const url = $(this).data('url');
            if (url) {
                $('#historyLightboxImg').attr('src', url);
                $('#historyReceiptPreviewModal').modal('show');
            }
        });
    });
</script>
@endsection
