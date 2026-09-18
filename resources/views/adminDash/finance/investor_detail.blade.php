@extends('layouts.Backend.master')
@section('title', 'PARTNER PROFILE: ' . strtoupper($investor->name))

@section('style')
<style>
    /* =========================================================
       INVESTOR PROFILE - MODERN THEME SYSTEM (LIGHT & DARK)
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
        --f-pill-group-bg: #f1f5f9;
        --f-badge-method-bg: #f1f5f9;
        --f-badge-method-text: #475569;
        --f-badge-method-border: #e2e8f0;
        --f-modal-bg: #ffffff;
        --f-modal-border: #e2e8f0;
        --f-modal-header-border: #e2e8f0;
        --f-btn-sec-bg: #f1f5f9;
        --f-btn-sec-border: #e2e8f0;
        --f-btn-sec-text: #334155;
        --f-btn-sec-hover-bg: #e2e8f0;
        --f-btn-sec-hover-text: #0f172a;
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
        --f-pill-group-bg: #0f172a;
        --f-badge-method-bg: #0f172a;
        --f-badge-method-text: #cbd5e1;
        --f-badge-method-border: #334155;
        --f-modal-bg: #1e293b;
        --f-modal-border: #334155;
        --f-modal-header-border: #334155;
        --f-btn-sec-bg: #1e293b;
        --f-btn-sec-border: #334155;
        --f-btn-sec-text: #cbd5e1;
        --f-btn-sec-hover-bg: #334155;
        --f-btn-sec-hover-text: #ffffff;
    }

    .finance-wrapper {
        color: var(--f-text-body);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        transition: color 0.25s ease;
    }

    .f-text-title { color: var(--f-text-title) !important; }
    .f-text-muted { color: var(--f-text-muted) !important; }

    .f-card {
        background-color: var(--f-card-bg) !important;
        border: 1px solid var(--f-card-border) !important;
        border-radius: 16px;
        box-shadow: var(--f-card-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .f-card:hover {
        border-color: var(--f-card-hover-border) !important;
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

    /* Hero Profile Card */
    .partner-profile-hero {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(16, 185, 129, 0.08) 100%);
        border: 1px solid var(--f-card-border);
        border-radius: 20px;
        padding: 26px;
        box-shadow: var(--f-card-shadow);
    }

    .partner-avatar-lg {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.85rem;
        font-weight: 800;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: #ffffff;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.38);
        flex-shrink: 0;
    }

    .partner-hero-balance-box {
        background: var(--f-card-bg);
        border: 1px solid var(--f-card-border);
        border-radius: 16px;
        padding: 16px 20px;
        display: inline-block;
        box-shadow: var(--f-card-shadow);
    }

    /* KPI Cards */
    .kpi-card {
        padding: 20px 22px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px;
    }
    .kpi-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        opacity: 0.95;
    }
    .kpi-revenue::before { background: linear-gradient(90deg, #059669, #34d399); }
    .kpi-expense::before { background: linear-gradient(90deg, #e11d48, #fb7185); }
    .kpi-ops::before { background: linear-gradient(90deg, #6366f1, #a855f7); }

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

    /* Ledger Table */
    .ledger-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--f-text-muted);
        border-top: none;
        border-bottom: 2px solid var(--f-card-border);
        padding: 14px 16px;
    }
    .ledger-table td {
        font-size: 13px;
        color: var(--f-text-body);
        border-top: 1px solid var(--f-card-border);
        padding: 14px 16px;
        vertical-align: middle;
    }
    .ledger-table tr:hover {
        background-color: var(--f-pill-group-bg);
    }
</style>
@endsection

@section('content')
<div class="finance-wrapper py-2">

    {{-- Header & Breadcrumb Row --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12 mb-3 mb-md-0">
            <div class="d-flex align-items-center">
                <div class="kpi-icon-box icon-indigo mr-3" style="width: 48px; height: 48px; border-radius: 12px;">
                    <i class="fa-solid fa-user-tie font-20"></i>
                </div>
                <div>
                    <h3 class="font-weight-bold f-text-title mb-0" style="letter-spacing: -0.02em;">Partner Profile: {{ $investor->name }}</h3>
                    <p class="f-text-muted mb-0 font-12">Detailed capital balance, lifetime inflows, payouts &amp; ledger history</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 text-md-right">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end justify-content-start" style="gap: 8px;">
                <a href="{{ route('admin.finance.investors') }}" class="f-btn f-btn-secondary">
                    <i class="fa-solid fa-arrow-left font-12"></i> All Investors
                </a>
                <a href="{{ route('admin.finance.index') }}" class="f-btn f-btn-secondary">
                    <i class="fa-solid fa-vault font-12"></i> Finance Hub
                </a>
            </div>
        </div>
    </div>

    {{-- Partner Info Hero Card --}}
    <div class="partner-profile-hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-7 col-12 mb-3 mb-lg-0">
                <div class="d-flex align-items-start">
                    @php
                        $initials = collect(explode(' ', $investor->name))->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
                    @endphp
                    <div class="partner-avatar-lg mr-3">
                        {{ $initials ?: 'P' }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <h3 class="font-weight-bold f-text-title mb-0">{{ $investor->name }}</h3>
                            <span class="badge badge-pill {{ $investor->status === 'active' ? 'badge-income' : 'badge-method' }} font-11">
                                <i class="fa-solid {{ $investor->status === 'active' ? 'fa-circle-check' : 'fa-circle-pause' }} mr-1"></i> {{ ucfirst($investor->status) }}
                            </span>
                        </div>
                        <div class="d-flex flex-wrap align-items-center mt-2 text-muted font-12" style="gap: 14px;">
                            @if($investor->phone)
                                <span><i class="fa-solid fa-phone mr-1 text-info"></i> {{ $investor->phone }}</span>
                            @endif
                            @if($investor->email)
                                <span><i class="fa-solid fa-envelope mr-1 text-info"></i> {{ $investor->email }}</span>
                            @endif
                            @if($investor->nid_or_passport)
                                <span><i class="fa-solid fa-id-card mr-1 text-info"></i> ID: {{ $investor->nid_or_passport }}</span>
                            @endif
                            <span><i class="fa-regular fa-calendar mr-1 text-info"></i> Partner since {{ $investor->created_at ? $investor->created_at->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        @if($investor->notes)
                            <div class="mt-2 font-12 f-text-muted p-2 rounded" style="background: rgba(99, 102, 241, 0.05); border-left: 3px solid #6366f1;">
                                <i class="fa-solid fa-quote-left mr-1 opacity-50"></i> {{ $investor->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-12 text-lg-right text-left">
                <div class="partner-hero-balance-box text-left">
                    <span class="font-11 text-uppercase font-weight-bold f-text-muted d-block" style="letter-spacing: 0.05em;">
                        <i class="fa-solid fa-vault mr-1 text-info"></i> Partner Net Active Capital
                    </span>
                    <div class="d-flex align-items-baseline" style="gap: 6px;">
                        <span class="font-20 font-weight-bold" style="color: #6366f1;">৳</span>
                        <strong class="font-28 font-weight-bold" style="{{ $investorBalance < 0 ? 'color: #f43f5e;' : ($investorBalance > 0 ? 'color: #10b981;' : 'color: var(--f-text-title);') }}">
                            {{ number_format(abs($investorBalance), 2) }}
                        </strong>
                        @if($investorBalance < 0)
                            <span class="badge badge-pill badge-expense font-11 ml-1">Deficit</span>
                        @endif
                    </div>
                    <small class="f-text-muted font-11 d-block mt-1">
                        Equity Share: <strong class="text-info">{{ $investorSharePct }}%</strong> of Active Treasury
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Metrics KPI Grid --}}
    <div class="row mb-4">
        {{-- Card 1: Total Capital Deposited --}}
        <div class="col-md-4 col-12 mb-3 mb-md-0">
            <div class="f-card kpi-card kpi-revenue">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Total Capital Deposited</span>
                        <div class="kpi-icon-box icon-emerald">
                            <i class="fa-solid fa-arrow-down-left"></i>
                        </div>
                    </div>
                    <div class="kpi-value" style="color: #10b981;">
                        +৳{{ number_format($investorTotalInvested, 2) }}
                    </div>
                </div>
                <div>
                    <span class="badge badge-pill badge-income font-11">
                        <i class="fa-solid fa-circle-arrow-down mr-1"></i> Lifetime Inflows
                    </span>
                </div>
            </div>
        </div>

        {{-- Card 2: Capital Withdrawn --}}
        <div class="col-md-4 col-12 mb-3 mb-md-0">
            <div class="f-card kpi-card kpi-expense">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Capital Withdrawn</span>
                        <div class="kpi-icon-box icon-rose">
                            <i class="fa-solid fa-arrow-up-right"></i>
                        </div>
                    </div>
                    <div class="kpi-value" style="color: #f43f5e;">
                        -৳{{ number_format($investorTotalWithdrawn, 2) }}
                    </div>
                </div>
                <div>
                    <span class="badge badge-pill badge-expense font-11">
                        <i class="fa-solid fa-circle-arrow-up mr-1"></i> Lifetime Pay-outs
                    </span>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Operations --}}
        <div class="col-md-4 col-12">
            <div class="f-card kpi-card kpi-ops">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Total Operations</span>
                        <div class="kpi-icon-box icon-indigo">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                    </div>
                    <div class="kpi-value" style="color: #6366f1;">
                        {{ $transactions->total() }} <span style="font-size: 1rem; color: var(--f-text-muted); font-weight: 500;">Entries</span>
                    </div>
                </div>
                <div>
                    <span class="badge badge-pill badge-method font-11">
                        <i class="fa-solid fa-list-check mr-1"></i> Deposit &amp; Withdrawal Count
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Ledger Table Card --}}
    <div class="row">
        <div class="col-12">
            <div class="f-card">
                <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="border-color: var(--f-card-border) !important;">
                    <div class="font-14 font-weight-bold f-text-title">
                        <i class="fa-solid fa-list-check mr-2 text-info"></i> Financial Ledger &amp; Transactions History
                    </div>
                    <span class="badge badge-pill badge-method font-11">
                        {{ $transactions->total() }} Records Found
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table ledger-table mb-0">
                        <thead>
                            <tr>
                                <th>Tx ID</th>
                                <th>Date &amp; Time</th>
                                <th>Operation Type</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Notes / Details</th>
                                <th>Voucher Slip</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                                @php
                                    $isDeposit = $tx->entry_type === 'INCOME';
                                    $badgeClass = $isDeposit ? 'badge-income' : 'badge-expense';
                                    $sign = $isDeposit ? '+' : '-';
                                    $amountColor = $isDeposit ? '#10b981' : '#f43f5e';
                                    $typeLabel = $isDeposit ? 'Capital Deposit' : 'Capital Withdrawn';
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge badge-pill badge-method font-11">#FT-{{ str_pad($tx->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>
                                        <span class="font-12 font-weight-bold f-text-title d-block">
                                            <i class="fa-regular fa-calendar-days mr-1 text-muted"></i>
                                            {{ $tx->transaction_date ? $tx->transaction_date->format('M d, Y · h:i A') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill {{ $badgeClass }} font-11">
                                            <i class="fa-solid {{ $isDeposit ? 'fa-arrow-down-left' : 'fa-arrow-up-right' }} mr-1"></i> {{ $typeLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="font-15 font-weight-bold" style="color: {{ $amountColor }};">
                                            {{ $sign }}৳{{ number_format($tx->amount, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-method font-11">
                                            <i class="fa-solid fa-building-columns mr-1 text-muted"></i> {{ $tx->payment_method }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-12 f-text-body">{{ $tx->notes ?: 'None' }}</span>
                                    </td>
                                    <td>
                                        @if(!empty($tx->receipt_image) && file_exists(public_path('Uploads/finance/' . $tx->receipt_image)))
                                            <button type="button" class="btn btn-sm btn-outline-info py-0 px-2 font-11 btn-preview-detail-receipt" data-url="{{ asset('Uploads/finance/' . $tx->receipt_image) }}">
                                                <i class="fa-solid fa-receipt mr-1"></i> View Slip
                                            </button>
                                        @else
                                            <span class="text-muted font-11">None</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-circle-info font-20 d-block mb-2 text-info"></i>
                                        No investment transactions found for this partner yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end" style="border-color: var(--f-card-border) !important;">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Lightbox preview for receipts --}}
<div class="modal fade f-modal" id="detailReceiptPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 600px !important;" role="document">
        <div class="modal-content" style="border-radius: 16px !important; background: #000;">
            <div class="modal-header py-2 px-3 border-0 d-flex justify-content-between align-items-center bg-dark text-white">
                <span class="font-12 font-weight-bold">Voucher / Slip Preview</span>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-2 text-center">
                <img id="detailLightboxImg" src="" alt="Voucher Slip" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.btn-preview-detail-receipt').on('click', function() {
            const url = $(this).data('url');
            if (url) {
                $('#detailLightboxImg').attr('src', url);
                $('#detailReceiptPreviewModal').modal('show');
            }
        });
    });
</script>
@endsection
