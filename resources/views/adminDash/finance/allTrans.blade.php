@extends('layouts.Backend.master')
@section('title', 'ALL FINANCE TRANSACTIONS')

@section('style')
<style>
    :root {
        --f-bg-page: #f8fafc;
        --f-card-bg: #ffffff;
        --f-card-border: #e2e8f0;
        --f-card-hover-border: #cbd5e1;
        --f-card-hover-bg: #f8fafc;
        --f-text-title: #0f172a;
        --f-text-body: #334155;
        --f-text-muted: #64748b;
        --f-input-bg: #ffffff;
        --f-input-border: #cbd5e1;
        --f-input-text: #0f172a;
        --f-input-placeholder: #94a3b8;
        --f-pill-group-bg: #f1f5f9;
        --f-pill-hover-bg: #e2e8f0;
        --f-item-bg: #ffffff;
        --f-modal-bg: #ffffff;
        --f-modal-border: #e2e8f0;
        --f-modal-header-border: #e2e8f0;
        --f-dropzone-bg: #f8fafc;
        --f-dropzone-border: #cbd5e1;
        --f-color-scheme: light;
        --f-btn-sec-bg: #f1f5f9;
        --f-btn-sec-border: #e2e8f0;
        --f-btn-sec-text: #334155;
        --f-select-arrow: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    }
    body.dark-mode {
        --f-bg-page: #0f172a;
        --f-card-bg: #1e293b;
        --f-card-border: #334155;
        --f-card-hover-border: #475569;
        --f-card-hover-bg: #24344d;
        --f-text-title: #f8fafc;
        --f-text-body: #e2e8f0;
        --f-text-muted: #94a3b8;
        --f-input-bg: #0f172a;
        --f-input-border: #334155;
        --f-input-text: #f8fafc;
        --f-input-placeholder: #64748b;
        --f-pill-group-bg: #0f172a;
        --f-pill-hover-bg: #1e293b;
        --f-item-bg: #1e293b;
        --f-modal-bg: #1e293b;
        --f-modal-border: #334155;
        --f-modal-header-border: #334155;
        --f-dropzone-bg: #0f172a;
        --f-dropzone-border: #334155;
        --f-color-scheme: dark;
        --f-btn-sec-bg: #1e293b;
        --f-btn-sec-border: #334155;
        --f-btn-sec-text: #cbd5e1;
    }
    .f-card {
        background-color: var(--f-card-bg) !important;
        border: 1px solid var(--f-card-border) !important;
        border-radius: 14px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.06);
    }
    .f-text-title { color: var(--f-text-title) !important; }
    .f-text-muted { color: var(--f-text-muted) !important; }
    .table thead th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: var(--f-text-muted);
        border-top: none;
        border-bottom: 1px solid var(--f-card-border);
        background: var(--f-pill-group-bg);
    }
    .table td {
        vertical-align: middle;
        border-top: 1px solid var(--f-card-border);
        font-size: 0.86rem;
        color: var(--f-text-body);
    }
    .badge-income {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    body.dark-mode .badge-income {
        background: rgba(6, 78, 59, 0.6);
        color: #34d399;
    }
    .badge-expense {
        background: rgba(244, 63, 94, 0.15);
        color: #e11d48;
        border: 1px solid rgba(244, 63, 94, 0.3);
    }
    body.dark-mode .badge-expense {
        background: rgba(136, 19, 55, 0.6);
        color: #fb7185;
    }
    .badge-method {
        background-color: var(--f-pill-group-bg);
        color: var(--f-text-muted);
        border: 1px solid var(--f-card-border);
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .tx-thumb-sm {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid var(--f-card-border);
        transition: transform 0.2s ease;
    }
    .tx-thumb-sm:hover {
        transform: scale(1.1);
        border-color: #10b981;
    }

    /* Action Buttons */
    .f-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 9px 18px;
        border-radius: 9px;
        border: 1px solid transparent;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none !important;
        white-space: nowrap;
    }
    .f-btn:active { transform: scale(0.98); }
    .f-btn-income {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
    }
    .f-btn-income:hover {
        background: linear-gradient(135deg, #047857 0%, #059669 100%);
        color: #ffffff !important;
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45);
        transform: translateY(-1px);
    }
    .f-btn-expense {
        background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(244, 63, 94, 0.35);
    }
    .f-btn-expense:hover {
        background: linear-gradient(135deg, #be123c 0%, #e11d48 100%);
        color: #ffffff !important;
        box-shadow: 0 6px 18px rgba(244, 63, 94, 0.45);
        transform: translateY(-1px);
    }
    .f-btn-secondary {
        background-color: var(--f-btn-sec-bg) !important;
        border: 1px solid var(--f-btn-sec-border) !important;
        color: var(--f-btn-sec-text) !important;
    }
    .f-btn-secondary:hover {
        background-color: var(--f-card-hover-border) !important;
        color: var(--f-text-title) !important;
        transform: translateY(-1px);
    }

    /* Modal Customization (Bootstrap Modal Overrides) */
    .f-modal .modal-dialog {
        max-width: 780px !important;
        width: 95% !important;
        margin: 1.75rem auto !important;
    }
    .f-modal .modal-content {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        background-color: var(--f-modal-bg) !important;
        border: 1px solid var(--f-modal-border) !important;
        border-radius: 20px !important;
        color: var(--f-text-body) !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05) !important;
        overflow: hidden !important;
        transform: none !important;
    }
    .f-modal .modal-content input {
        margin-bottom: 0 !important;
    }
    .f-modal .modal-header {
        border-bottom: 1px solid var(--f-modal-header-border);
        padding: 20px 28px !important;
        background: var(--f-card-bg);
    }
    .f-modal .modal-body {
        padding: 24px 28px !important;
    }
    .f-modal .modal-footer {
        border-top: 1px solid var(--f-modal-header-border);
        padding: 16px 28px !important;
        background: var(--f-card-bg);
    }
    .f-modal .close {
        color: var(--f-text-muted);
        opacity: 0.7;
        text-shadow: none;
        font-size: 1.5rem;
        padding: 8px 12px;
        margin: -8px -12px -8px auto;
        border-radius: 8px;
        transition: all 0.2s ease;
        outline: none !important;
    }
    .f-modal .close:hover {
        color: var(--f-text-title);
        background-color: var(--f-card-hover-bg);
        opacity: 1;
    }

    /* Segmented Switcher in Modal */
    .type-switcher {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
        background-color: var(--f-pill-group-bg);
        border: 1px solid var(--f-card-border);
        border-radius: 12px;
        padding: 4px;
    }
    .type-switcher-btn {
        padding: 10px 14px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 9px;
        border: none;
        background: transparent;
        color: var(--f-text-muted);
        cursor: pointer;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .type-switcher-btn:hover:not(.active-income):not(.active-expense) {
        color: var(--f-text-title);
        background-color: var(--f-pill-hover-bg);
    }
    .type-switcher-btn.active-income {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
    }
    .type-switcher-btn.active-expense {
        background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(244, 63, 94, 0.4);
    }

    /* Hero Amount Card */
    .hero-amount-card {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.06) 0%, rgba(6, 182, 212, 0.03) 100%);
        border: 1.5px solid rgba(16, 185, 129, 0.25);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 18px;
        transition: all 0.25s ease;
    }
    .hero-amount-card.expense-mode {
        background: linear-gradient(135deg, rgba(244, 63, 94, 0.06) 0%, rgba(225, 29, 72, 0.03) 100%);
        border-color: rgba(244, 63, 94, 0.25);
    }
    .hero-amount-input-box {
        display: flex;
        align-items: center;
        background-color: var(--f-input-bg);
        border: 1.5px solid var(--f-input-border);
        border-radius: 11px;
        padding: 5px 14px;
        transition: all 0.2s ease;
    }
    .hero-amount-input-box:focus-within {
        border-color: #10b981;
        box-shadow: 0 0 0 3.5px rgba(16, 185, 129, 0.18);
    }
    .hero-amount-card.expense-mode .hero-amount-input-box:focus-within {
        border-color: #f43f5e;
        box-shadow: 0 0 0 3.5px rgba(244, 63, 94, 0.18);
    }
    .hero-currency-symbol {
        font-size: 1.55rem;
        font-weight: 800;
        color: #10b981;
        margin-right: 12px;
        user-select: none;
        transition: color 0.2s ease;
        line-height: 1;
    }
    .hero-amount-card.expense-mode .hero-currency-symbol {
        color: #f43f5e;
    }
    .hero-amount-input {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
        color: var(--f-text-title) !important;
        font-size: 1.55rem !important;
        font-weight: 800 !important;
        width: 100%;
        padding: 2px 0 !important;
        height: auto !important;
        letter-spacing: -0.02em;
    }
    .hero-amount-input::placeholder {
        color: var(--f-input-placeholder) !important;
        font-weight: 500;
        opacity: 0.6;
    }
    .amount-chip {
        font-size: 0.73rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 7px;
        background-color: var(--f-pill-group-bg);
        border: 1px solid var(--f-card-border);
        color: var(--f-text-body);
        cursor: pointer;
        transition: all 0.18s ease;
    }
    .amount-chip:hover {
        background-color: var(--f-pill-hover-bg);
        color: var(--f-text-title);
        border-color: var(--f-card-hover-border);
        transform: translateY(-1px);
    }
    .amount-chip-clear {
        font-size: 0.73rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 7px;
        background-color: transparent;
        border: 1px solid transparent;
        color: var(--f-text-muted);
        cursor: pointer;
        transition: all 0.18s ease;
    }
    .amount-chip-clear:hover {
        color: #f43f5e;
    }

    /* Modal Form Field Layout */
    .modal-label {
        font-size: 0.81rem;
        font-weight: 600;
        color: var(--f-text-title);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
    }
    .f-field-group {
        position: relative;
    }
    .f-field-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--f-text-muted);
        font-size: 13px;
        pointer-events: none;
        z-index: 2;
        transition: color 0.2s ease;
    }
    .f-field-group:focus-within .f-field-icon {
        color: #10b981;
    }
    .hero-amount-card.expense-mode ~ .row .f-field-group:focus-within .f-field-icon {
        color: #f43f5e;
    }
    .f-modal-input {
        background-color: var(--f-input-bg) !important;
        border: 1px solid var(--f-input-border) !important;
        color: var(--f-input-text) !important;
        font-size: 0.85rem !important;
        border-radius: 10px !important;
        padding: 9px 12px 9px 38px !important;
        height: 42px !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        color-scheme: var(--f-color-scheme);
        width: 100%;
    }
    .f-modal-input::placeholder {
        color: var(--f-input-placeholder) !important;
        opacity: 0.8;
    }
    .f-modal-input:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18) !important;
        outline: none !important;
    }
    .f-modal-select {
        background-color: var(--f-input-bg) !important;
        border: 1px solid var(--f-input-border) !important;
        color: var(--f-input-text) !important;
        font-size: 0.85rem !important;
        border-radius: 10px !important;
        height: 42px !important;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: var(--f-select-arrow);
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px !important;
        padding-left: 38px !important;
        color-scheme: var(--f-color-scheme);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        width: 100%;
    }
    .f-modal-select:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18) !important;
        outline: none !important;
    }
    .f-modal-select optgroup {
        font-weight: 700;
        color: var(--f-text-muted);
        background-color: var(--f-card-bg);
    }
    .f-modal-select option {
        background-color: var(--f-card-bg);
        color: var(--f-text-title);
        padding: 6px 10px;
    }
    .f-modal-textarea {
        background-color: var(--f-input-bg) !important;
        border: 1px solid var(--f-input-border) !important;
        color: var(--f-input-text) !important;
        font-size: 0.85rem !important;
        border-radius: 10px !important;
        padding: 10px 12px 10px 38px !important;
        min-height: 68px;
        height: auto !important;
        resize: vertical;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        color-scheme: var(--f-color-scheme);
        width: 100%;
    }
    .f-modal-textarea:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18) !important;
        outline: none !important;
    }

    /* Upload Dropzone & Live Preview */
    .upload-dropzone {
        border: 2px dashed var(--f-dropzone-border);
        border-radius: 12px;
        background-color: var(--f-dropzone-bg);
        padding: 18px 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.22s ease;
    }
    .upload-dropzone:hover,
    .upload-dropzone.dragover {
        border-color: #10b981;
        background-color: var(--f-card-hover-bg);
        transform: scale(1.005);
    }
    .dropzone-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background-color: var(--f-pill-group-bg);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--f-text-muted);
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    .upload-dropzone:hover .dropzone-icon-circle,
    .upload-dropzone.dragover .dropzone-icon-circle {
        color: #10b981;
        background-color: rgba(16, 185, 129, 0.12);
    }
    .receipt-preview-card {
        background-color: var(--f-item-bg);
        border: 1px solid var(--f-card-border);
        border-radius: 12px;
        padding: 10px 14px;
        transition: all 0.2s ease;
    }
    .receipt-preview-img-box {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--f-card-border);
        background-color: var(--f-input-bg);
        flex-shrink: 0;
    }
    .receipt-preview-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .btn-remove-receipt {
        width: 32px;
        height: 32px;
        border-radius: 7px;
        border: 1px solid transparent;
        background: transparent;
        color: var(--f-text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.18s ease;
    }
    .btn-remove-receipt:hover {
        color: #fb7185;
        background: rgba(244, 63, 94, 0.15);
        border-color: rgba(244, 63, 94, 0.25);
    }
</style>
@endsection

@section('content')
<div class="py-3">
    {{-- Header --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12 mb-3 mb-md-0">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.finance.index') }}" class="btn btn-light btn-sm rounded-circle mr-3 p-2 shadow-sm" title="Back to Ledger">
                    <i class="fa-solid fa-arrow-left font-14"></i>
                </a>
                <div>
                    <h4 class="font-weight-bold f-text-title mb-0">Full Transaction History</h4>
                    <small class="f-text-muted">Audit log of all income and expense records</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 text-md-right">
            <a href="{{ route('admin.finance.export') }}" class="btn btn-success btn-sm font-weight-bold px-3 py-2 shadow-sm mr-2" style="border-radius: 8px;">
                <i class="fa-solid fa-file-excel mr-1"></i> Export Excel/CSV
            </a>
            <a href="{{ route('admin.finance.index') }}" class="btn btn-secondary btn-sm font-weight-bold px-3 py-2" style="border-radius: 8px;">
                <i class="fa-solid fa-chart-pie mr-1"></i> Ledger Dashboard
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="f-card p-3 mb-4">
        <form method="GET" action="{{ route('admin.finance.history') }}">
            <div class="row">
                <div class="col-md-3 col-12 mb-2 mb-md-0">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search notes, staff, category..." style="border-radius: 8px; height: 38px;">
                </div>
                <div class="col-md-3 col-6 mb-2 mb-md-0">
                    <select name="type" class="form-control form-control-sm" style="border-radius: 8px; height: 38px;">
                        <option value="">All Types (Income &amp; Expense)</option>
                        <option value="INCOME" {{ request('type') == 'INCOME' ? 'selected' : '' }}>Income Only</option>
                        <option value="EXPENSE" {{ request('type') == 'EXPENSE' ? 'selected' : '' }}>Expense Only</option>
                    </select>
                </div>
                <div class="col-md-3 col-6 mb-2 mb-md-0">
                    <select name="staff" class="form-control form-control-sm" style="border-radius: 8px; height: 38px;">
                        <option value="">All Staff</option>
                        @foreach($staffList as $staff)
                            <option value="{{ $staff }}" {{ request('staff') == $staff ? 'selected' : '' }}>{{ $staff }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-12 d-flex" style="gap: 8px;">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 font-weight-bold" style="border-radius: 8px; height: 38px;">
                        <i class="fa-solid fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.finance.history') }}" class="btn btn-light btn-sm font-weight-bold" style="border-radius: 8px; height: 38px; line-height: 26px;">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="f-card overflow-hidden">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="py-3 px-3">ID / Date</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Category</th>
                        <th class="py-3">Payment Method</th>
                        <th class="py-3">Amount (BDT)</th>
                        <th class="py-3">Recorded By</th>
                        <th class="py-3">Receipt</th>
                        <th class="py-3 text-right px-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td class="px-3">
                                <div class="font-weight-bold font-12 f-text-title">#FT-{{ str_pad($tx->id, 5, '0', STR_PAD_LEFT) }}</div>
                                <div class="font-11 f-text-muted">{{ $tx->transaction_date ? $tx->transaction_date->format('M d, Y · h:i A') : $tx->created_at->format('M d, Y') }}</div>
                            </td>
                            <td>
                                @if($tx->entry_type === 'INCOME')
                                    <span class="badge badge-pill badge-income font-11 px-2.5 py-1">
                                        <i class="fa-solid fa-arrow-up font-9 mr-1"></i> Income
                                    </span>
                                @else
                                    <span class="badge badge-pill badge-expense font-11 px-2.5 py-1">
                                        <i class="fa-solid fa-arrow-down font-9 mr-1"></i> Expense
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong class="f-text-title font-13">{{ $tx->category }}</strong>
                                @if($tx->notes)
                                    <div class="font-11 f-text-muted text-truncate" style="max-width: 220px;" title="{{ $tx->notes }}">
                                        {{ $tx->notes }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge-method">{{ $tx->payment_method }}</span>
                            </td>
                            <td>
                                <span class="font-weight-bold font-14 {{ $tx->entry_type === 'INCOME' ? 'text-success' : 'text-danger' }}">
                                    {{ $tx->entry_type === 'INCOME' ? '+' : '-' }}৳{{ number_format($tx->amount, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="font-12 f-text-title font-weight-bold">{{ $tx->staff_name }}</span>
                            </td>
                            <td>
                                @if($tx->receipt_image && file_exists(public_path('Uploads/finance/' . $tx->receipt_image)))
                                    <img src="{{ asset('Uploads/finance/' . $tx->receipt_image) }}" alt="Receipt" class="tx-thumb-sm view-receipt-btn" data-src="{{ asset('Uploads/finance/' . $tx->receipt_image) }}" title="Click to view full receipt">
                                @else
                                    <span class="font-11 f-text-muted">None</span>
                                @endif
                            </td>
                            <td class="text-right px-3">
                                <button type="button" class="btn btn-sm btn-outline-info edit-tx-row-btn py-1 px-2" 
                                        data-id="{{ $tx->id }}"
                                        data-type="{{ $tx->entry_type }}"
                                        data-category="{{ $tx->category }}"
                                        data-method="{{ $tx->payment_method }}"
                                        data-amount="{{ $tx->amount }}"
                                        data-staff="{{ $tx->staff_name }}"
                                        data-date="{{ $tx->transaction_date ? $tx->transaction_date->format('Y-m-d\TH:i') : '' }}"
                                        data-notes="{{ $tx->notes ?? '' }}"
                                        data-receipt="{{ $tx->receipt_image && file_exists(public_path('Uploads/finance/' . $tx->receipt_image)) ? asset('Uploads/finance/' . $tx->receipt_image) : '' }}"
                                        title="Edit Transaction" 
                                        style="border-radius: 6px;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-tx-row-btn py-1 px-2" data-id="{{ $tx->id }}" title="Delete Entry" style="border-radius: 6px;">
                                    <i class="fa-solid fa-trash-can font-11"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fa-solid fa-receipt f-text-muted mb-2 font-32"></i>
                                <h6 class="font-weight-bold f-text-title">No transactions found</h6>
                                <p class="font-12 f-text-muted mb-0">Try changing your search or filter options</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Lightbox Modal --}}
<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; background: #000;">
            <div class="modal-header py-2 px-3 border-0 d-flex justify-content-between align-items-center bg-dark text-white">
                <span class="font-12 font-weight-bold">Receipt Preview</span>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-2 text-center">
                <img id="receiptModalImg" src="" alt="Receipt" class="img-fluid rounded" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

{{-- Edit Transaction Modal --}}
<div class="modal fade f-modal" id="editTransactionModal" tabindex="-1" role="dialog" aria-labelledby="editTransactionModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 780px !important; width: 95% !important;" role="document">
        <div class="modal-content" style="width: 100% !important; max-width: 100% !important; padding: 0 !important; border-radius: 20px !important;">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box mr-3" id="editModalHeaderIcon" style="width: 40px; height: 40px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <i class="fa-solid fa-pen-to-square font-16"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold f-text-title mb-0" id="editTransactionModalTitle">Edit Transaction</h5>
                        <small class="f-text-muted font-11" id="editTransactionModalSubtitle">Update financial record details</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="editTransactionForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editTxId" name="tx_id" value="">
                <input type="hidden" id="editRemoveReceipt" name="remove_receipt" value="0">
                <div class="modal-body">
                    {{-- Income / Expense Segmented Switcher --}}
                    <div class="type-switcher mb-3">
                        <button type="button" class="type-switcher-btn active-income" id="editSwitchIncomeBtn">
                            <i class="fa-solid fa-arrow-trend-up font-14"></i>
                            <span>Income (Revenue)</span>
                        </button>
                        <button type="button" class="type-switcher-btn" id="editSwitchExpenseBtn">
                            <i class="fa-solid fa-arrow-trend-down font-14"></i>
                            <span>Expense (Cost)</span>
                        </button>
                    </div>
                    <input type="hidden" id="editEntryType" name="entry_type" value="INCOME">

                    {{-- Hero Amount Card --}}
                    <div class="hero-amount-card" id="editHeroAmountCard">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="modal-label mb-0" for="editEntryAmount">
                                <i class="fa-solid fa-coins mr-1 text-muted"></i> Amount <span class="text-danger ml-0.5">*</span>
                            </label>
                            <span class="badge badge-method font-11 font-weight-bold">
                                BDT ৳ Currency
                            </span>
                        </div>
                        <div class="hero-amount-input-box">
                            <div class="hero-currency-symbol" id="editHeroCurrencySymbol">৳</div>
                            <input type="number" step="0.01" min="0.01" required placeholder="0.00" id="editEntryAmount" name="amount" class="hero-amount-input" autocomplete="off">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-2" style="gap: 6px;">
                            <span class="font-11 f-text-muted mr-1"><i class="fa-solid fa-bolt text-warning mr-1"></i>Quick Add:</span>
                            <button type="button" class="amount-chip edit-amount-chip" data-val="500">+৳500</button>
                            <button type="button" class="amount-chip edit-amount-chip" data-val="1000">+৳1,000</button>
                            <button type="button" class="amount-chip edit-amount-chip" data-val="2000">+৳2,000</button>
                            <button type="button" class="amount-chip edit-amount-chip" data-val="5000">+৳5,000</button>
                            <button type="button" class="amount-chip edit-amount-chip" data-val="10000">+৳10,000</button>
                            <button type="button" class="amount-chip-clear ml-auto" id="editClearAmountChip">Clear</button>
                        </div>
                    </div>

                    {{-- 2x2 Grid of Core Fields --}}
                    <div class="row">
                        {{-- Category Dropdown --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="modal-label" for="editEntryCategory">
                                <i class="fa-solid fa-layer-group mr-1 text-muted"></i> Category <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="f-field-group">
                                <i class="fa-solid fa-tag f-field-icon"></i>
                                <select id="editEntryCategory" name="category" class="f-modal-select" required>
                                    <optgroup label="── Income Streams ──">
                                        <option value="Online Sales">Online Sales</option>
                                        <option value="Shop Sales">Shop Sales</option>
                                        <option value="Wholesale / Bulk Order">Wholesale / Bulk Order</option>
                                        <option value="Investment / Capital">Investment / Capital</option>
                                        <option value="Other">Other / Custom</option>
                                    </optgroup>
                                    <optgroup label="── Operating Expenses ──">
                                        <option value="Product Sourcing">Product Sourcing</option>
                                        <option value="Courier / Delivery">Courier / Delivery</option>
                                        <option value="Dollar / Ads &amp; Marketing">Dollar / Ads &amp; Marketing</option>
                                        <option value="Packaging Material">Packaging Material</option>
                                        <option value="Shop Rent &amp; Maintenance">Shop Rent &amp; Maintenance</option>
                                        <option value="Staff Salary &amp; Bonus">Staff Salary &amp; Bonus</option>
                                        <option value="Utilities &amp; Bills">Utilities &amp; Bills</option>
                                        <option value="Office Supplies &amp; Snacks">Office Supplies &amp; Snacks</option>
                                        <option value="Taxes / Bank Fees">Taxes / Bank Fees</option>
                                        <option value="Exchange / Refund">Exchange / Refund</option>
                                        <option value="Other Expense">Other Expense</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="modal-label" for="editEntryPaymentMethod">
                                <i class="fa-solid fa-wallet mr-1 text-muted"></i> Payment Method <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="f-field-group">
                                <i class="fa-solid fa-credit-card f-field-icon"></i>
                                <select id="editEntryPaymentMethod" name="payment_method" class="f-modal-select" required>
                                    <option value="Cash">Cash in Hand</option>
                                    <option value="bKash/Nagad">bKash / Nagad (MFS)</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Credit Card">Credit / Debit Card</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Date & Time --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="modal-label" for="editEntryDateTime">
                                <i class="fa-regular fa-calendar-days mr-1 text-muted"></i> Date &amp; Time <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="f-field-group">
                                <i class="fa-regular fa-clock f-field-icon"></i>
                                <input type="datetime-local" required id="editEntryDateTime" name="transaction_date" class="f-modal-input" value="{{ date('Y-m-d\TH:i') }}">
                            </div>
                        </div>

                        {{-- Recorded By --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="modal-label mb-0" for="editEntryStaff">
                                    <i class="fa-regular fa-user mr-1 text-muted"></i> Recorded By <span class="text-danger ml-0.5">*</span>
                                </label>
                            </div>
                            <div class="f-field-group">
                                <i class="fa-solid fa-user-pen f-field-icon"></i>
                                <input type="text" required list="editStaffSuggestions" id="editEntryStaff" name="staff_name" class="f-modal-input" value="{{ auth('admin')->user()->name ?? 'Looksmen' }}" placeholder="e.g. Owner, Sabbir, Raju">
                            </div>
                            <datalist id="editStaffSuggestions">
                                @foreach($staffList as $staff)
                                    <option value="{{ $staff }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    {{-- Notes / Details --}}
                    <div class="form-group mb-3">
                        <label class="modal-label" for="editEntryNotes">
                            <i class="fa-regular fa-comment-dots mr-1 text-muted"></i> Notes / Reference (Optional)
                        </label>
                        <div class="f-field-group">
                            <i class="fa-solid fa-pen-nib f-field-icon" style="top: 18px;"></i>
                            <textarea id="editEntryNotes" name="notes" rows="2" class="f-modal-textarea" placeholder="e.g. Customer order #104, Supplier invoice #441"></textarea>
                        </div>
                    </div>

                    {{-- Invoice / Receipt Photo Upload --}}
                    <div class="form-group mb-0">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="modal-label mb-0">
                                <i class="fa-solid fa-receipt mr-1 text-muted"></i> Invoice / Receipt Attachment (Optional)
                            </label>
                            <span class="font-11 f-text-muted">Saved as WebP • Max 5MB</span>
                        </div>

                        <!-- Dropzone area -->
                        <div class="upload-dropzone" id="editReceiptUploadTrigger">
                            <div class="dropzone-icon-circle">
                                <i class="fa-solid fa-cloud-arrow-up font-16"></i>
                            </div>
                            <div class="font-13 font-weight-bold f-text-title mb-1">Click to upload new receipt photo</div>
                            <div class="font-11 f-text-muted">Replaces existing receipt (Converts to WebP)</div>
                        </div>
                        <input type="file" id="editReceiptFileInput" name="receipt_image" class="d-none" accept="image/*">

                        <!-- Preview Card -->
                        <div id="editReceiptPreviewCard" class="receipt-preview-card d-none mt-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center overflow-hidden mr-2">
                                    <div class="receipt-preview-img-box mr-3">
                                        <img id="editReceiptPreviewImg" src="" alt="Receipt Preview">
                                    </div>
                                    <div class="overflow-hidden">
                                        <div id="editFileUploadName" class="font-12 font-weight-bold f-text-title text-truncate">receipt.webp</div>
                                        <div id="editFileUploadSize" class="font-11 f-text-muted">Attached receipt</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-remove-receipt" id="editRemoveReceiptBtn" title="Remove attachment">
                                    <i class="fa-solid fa-trash-can font-13"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex align-items-center justify-content-end">
                    <button type="button" class="f-btn f-btn-secondary" data-dismiss="modal">
                        <i class="fa-solid fa-xmark mr-1"></i> Cancel
                    </button>
                    <button type="submit" class="f-btn f-btn-income" id="editSubmitModalBtn">
                        <i class="fa-solid fa-check mr-1"></i> <span>Update Transaction</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // 1. Lightbox receipt preview
    $('.view-receipt-btn').on('click', function() {
        const src = $(this).data('src');
        if (src) {
            $('#receiptModalImg').attr('src', src);
            $('#receiptModal').modal('show');
        }
    });

    // 2. Edit Modal Type Switcher (INCOME / EXPENSE)
    function setEditModalMode(mode) {
        if (mode === 'EXPENSE') {
            $('#editSwitchExpenseBtn').addClass('active-expense');
            $('#editSwitchIncomeBtn').removeClass('active-income');
            $('#editEntryType').val('EXPENSE');
            $('#editHeroAmountCard').addClass('expense-mode');
            $('#editHeroCurrencySymbol').css('color', '#f43f5e');
            $('#editSubmitModalBtn').removeClass('f-btn-income').addClass('f-btn-expense');
            $('#editModalHeaderIcon').css({
                'background': 'rgba(244, 63, 94, 0.15)',
                'color': '#f43f5e'
            });
        } else {
            $('#editSwitchIncomeBtn').addClass('active-income');
            $('#editSwitchExpenseBtn').removeClass('active-expense');
            $('#editEntryType').val('INCOME');
            $('#editHeroAmountCard').removeClass('expense-mode');
            $('#editHeroCurrencySymbol').css('color', '#10b981');
            $('#editSubmitModalBtn').removeClass('f-btn-expense').addClass('f-btn-income');
            $('#editModalHeaderIcon').css({
                'background': 'rgba(16, 185, 129, 0.15)',
                'color': '#10b981'
            });
        }
    }

    $('#editSwitchIncomeBtn').on('click', function() {
        setEditModalMode('INCOME');
    });

    $('#editSwitchExpenseBtn').on('click', function() {
        setEditModalMode('EXPENSE');
    });

    // 3. Amount Quick Add chips
    $('.edit-amount-chip').on('click', function() {
        const val = parseFloat($(this).data('val')) || 0;
        const currentVal = parseFloat($('#editEntryAmount').val()) || 0;
        $('#editEntryAmount').val((currentVal + val).toFixed(2));
    });

    $('#editClearAmountChip').on('click', function() {
        $('#editEntryAmount').val('');
    });

    // 4. Receipt Upload Dropzone & File Handling
    $('#editReceiptUploadTrigger').on('click', function() {
        $('#editReceiptFileInput').click();
    });

    $('#editReceiptFileInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('File too large', 'Image size cannot exceed 5MB.', 'warning');
                $(this).val('');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(evt) {
                $('#editReceiptPreviewImg').attr('src', evt.target.result);
                $('#editFileUploadName').text(file.name);
                $('#editFileUploadSize').text((file.size / 1024).toFixed(1) + ' KB · Ready to save as WebP');
                $('#editReceiptPreviewCard').removeClass('d-none');
                $('#editRemoveReceipt').val('0');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#editRemoveReceiptBtn').on('click', function() {
        $('#editReceiptFileInput').val('');
        $('#editReceiptPreviewImg').attr('src', '');
        $('#editReceiptPreviewCard').addClass('d-none');
        $('#editRemoveReceipt').val('1');
    });

    // 5. Open Edit Modal on Row Button Click
    $(document).on('click', '.edit-tx-row-btn', function(e) {
        e.preventDefault();
        const btn = $(this).closest('.edit-tx-row-btn');
        const id = btn.data('id');
        const type = btn.data('type') || 'INCOME';
        const category = String(btn.data('category') || '');
        const method = String(btn.data('method') || 'Cash');
        const amount = btn.data('amount') || '';
        const staff = btn.data('staff') || '';
        let date = btn.data('date') || '';
        const notes = btn.data('notes') || '';
        const receipt = btn.data('receipt') || '';

        $('#editTxId').val(id);
        $('#editEntryAmount').val(amount);

        // Ensure category exists in options
        if ($('#editEntryCategory option[value="' + category + '"]').length === 0 && category) {
            $('#editEntryCategory').append(new Option(category, category, true, true));
        }
        $('#editEntryCategory').val(category);

        // Ensure payment method exists in options
        if ($('#editEntryPaymentMethod option[value="' + method + '"]').length === 0 && method) {
            $('#editEntryPaymentMethod').append(new Option(method, method, true, true));
        }
        $('#editEntryPaymentMethod').val(method);

        if (!date) {
            const now = new Date();
            date = now.toISOString().slice(0, 16);
        }
        $('#editEntryDateTime').val(date);
        $('#editEntryStaff').val(staff);
        $('#editEntryNotes').val(notes);
        $('#editReceiptFileInput').val('');
        $('#editRemoveReceipt').val('0');

        // Set income/expense styling & switcher
        setEditModalMode(type);

        // Receipt preview if existing
        if (receipt) {
            $('#editReceiptPreviewImg').attr('src', receipt);
            $('#editFileUploadName').text('Current Receipt (WebP)');
            $('#editFileUploadSize').text('Attached to this transaction');
            $('#editReceiptPreviewCard').removeClass('d-none');
        } else {
            $('#editReceiptPreviewImg').attr('src', '');
            $('#editReceiptPreviewCard').addClass('d-none');
        }

        $('#editTransactionModalTitle').text('Edit Transaction #FT-' + String(id).padStart(5, '0'));
        $('#editTransactionModal').modal('show');
    });

    // 6. Submit Edit Form via AJAX
    $('#editTransactionForm').on('submit', function(e) {
        e.preventDefault();

        const id = $('#editTxId').val();
        if (!id) {
            Swal.fire('Error', 'Transaction ID missing.', 'error');
            return;
        }

        const amountVal = parseFloat($('#editEntryAmount').val());
        if (!amountVal || amountVal <= 0) {
            Swal.fire('Invalid Amount', 'Please enter an amount greater than 0.', 'warning');
            return;
        }

        const submitBtn = $('#editSubmitModalBtn');
        const originalBtnHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...');

        const formData = new FormData(this);

        $.ajax({
            url: `{{ url('admin/finance/update') }}/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(res) {
                $('#editTransactionModal').modal('hide');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: res.message || 'Transaction updated successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function() {
                    location.reload();
                }, 1200);
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalBtnHtml);
                let msg = 'Failed to update transaction.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Update Error',
                    html: msg
                });
            }
        });
    });

    // 7. Delete row handler
    $('.delete-tx-row-btn').on('click', function() {
        const id = $(this).data('id');
        const $row = $(this).closest('tr');

        Swal.fire({
            title: 'Delete Transaction?',
            text: 'Are you sure you want to permanently delete this financial record?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#334155',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/finance/delete') }}/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        $row.fadeOut(300, function() { $(this).remove(); });
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message || 'Deleted successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete record.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection
