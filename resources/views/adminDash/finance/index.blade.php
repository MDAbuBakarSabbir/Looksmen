@extends('layouts.Backend.master')
@section('title', 'FINANCE LEDGER')

@section('style')
<style>
    /* =========================================================
       FINANCE DASHBOARD - DYNAMIC THEME SYSTEM (LIGHT & DARK)
       Bootstrap 4 Grid & Components with Full Dark Mode Support
       ========================================================= */
    :root {
        /* Light Theme Tokens */
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

    /* Dark Theme Tokens (active when body has .dark-mode) */
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

    .f-text-title {
        color: var(--f-text-title) !important;
    }
    .f-text-muted {
        color: var(--f-text-muted) !important;
    }

    /* Base Theme-Aware Card */
    .f-card {
        background-color: var(--f-card-bg) !important;
        border: 1px solid var(--f-card-border) !important;
        border-radius: 14px;
        box-shadow: var(--f-card-shadow);
        transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.22s ease, box-shadow 0.22s ease, background-color 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .f-card:hover {
        background-color: var(--f-card-hover-bg) !important;
        border-color: var(--f-card-hover-border) !important;
        box-shadow: 0 14px 28px -4px rgba(0, 0, 0, 0.25);
    }

    /* Top KPI Stat Cards */
    .kpi-card {
        padding: 20px 22px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .kpi-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        opacity: 0.9;
    }
    .kpi-net::before { background: linear-gradient(90deg, #10b981, #06b6d4); }
    .kpi-revenue::before { background: linear-gradient(90deg, #059669, #34d399); }
    .kpi-expense::before { background: linear-gradient(90deg, #e11d48, #fb7185); }
    .kpi-trans::before { background: linear-gradient(90deg, #7c3aed, #a78bfa); }

    .kpi-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
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
    .icon-cyan {
        background: rgba(6, 182, 212, 0.14);
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.28);
    }
    .icon-violet {
        background: rgba(139, 92, 246, 0.14);
        color: #8b5cf6;
        border: 1px solid rgba(139, 92, 246, 0.28);
    }

    body.dark-mode .icon-emerald { color: #34d399; }
    body.dark-mode .icon-rose { color: #fb7185; }
    body.dark-mode .icon-cyan { color: #38bdf8; }
    body.dark-mode .icon-violet { color: #c084fc; }

    .kpi-title {
        font-size: 0.82rem;
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
        line-height: 1.2;
        letter-spacing: -0.02em;
    }
    .kpi-sub {
        font-size: 0.78rem;
        color: var(--f-text-muted);
        margin-top: 6px;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 5px;
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
        background-color: var(--f-btn-sec-hover-bg) !important;
        color: var(--f-btn-sec-hover-text) !important;
        transform: translateY(-1px);
    }

    /* Operational Breakdown Progress Bars */
    .breakdown-row {
        padding: 10px 14px;
        border-radius: 9px;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
        margin-bottom: 8px;
    }
    .breakdown-row:hover {
        background-color: var(--f-item-hover-bg);
        border-color: var(--f-card-border);
    }
    .progress-track {
        height: 7px;
        background-color: var(--f-track-bg);
        border-radius: 999px;
        overflow: hidden;
        border: 1px solid var(--f-card-border);
        margin-top: 6px;
    }
    .progress-bar-custom {
        height: 100%;
        border-radius: 999px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Filter Controls */
    .filter-panel {
        background-color: var(--f-panel-bg) !important;
        border: 1px solid var(--f-card-border) !important;
        border-radius: 14px;
        padding: 18px;
        box-shadow: var(--f-card-shadow);
    }
    .pill-group {
        display: flex;
        background-color: var(--f-pill-group-bg);
        border: 1px solid var(--f-card-border);
        border-radius: 10px;
        padding: 3px;
        overflow-x: auto;
        white-space: nowrap;
    }
    .pill-btn {
        padding: 6px 14px;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 7px;
        color: var(--f-pill-text);
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.18s ease;
        outline: none !important;
    }
    .pill-btn:hover {
        color: var(--f-pill-hover-text);
        background-color: var(--f-pill-hover-bg);
    }
    .pill-btn.active {
        background: linear-gradient(135deg, #059669, #0d9488) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(13, 148, 136, 0.35);
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
        border-radius: 8px !important;
        padding: 8px 12px 8px 34px !important;
        height: 38px !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        color-scheme: var(--f-color-scheme);
    }
    .f-input::placeholder {
        color: var(--f-input-placeholder) !important;
        opacity: 0.8;
    }
    .f-input:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
        outline: none !important;
    }

    /* Custom Styled Select with SVG Chevron Arrow */
    .f-select {
        background-color: var(--f-input-bg) !important;
        border: 1px solid var(--f-input-border) !important;
        color: var(--f-input-text) !important;
        font-size: 0.83rem !important;
        border-radius: 8px !important;
        height: 38px !important;
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
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
        outline: none !important;
    }
    .f-select option {
        background-color: var(--f-card-bg);
        color: var(--f-text-title);
    }

    /* Transaction Ledger Card List */
    .tx-item {
        background-color: var(--f-item-bg) !important;
        border: 1px solid var(--f-item-border) !important;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        position: relative;
    }
    .tx-item:hover {
        background-color: var(--f-item-hover-bg) !important;
        border-color: var(--f-item-hover-border) !important;
        box-shadow: 0 8px 22px -3px rgba(0, 0, 0, 0.2);
        transform: translateY(-1px);
    }
    .tx-type-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .tx-badge {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 3px 8px;
        border-radius: 20px;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-income {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
        border-color: rgba(16, 185, 129, 0.35);
    }
    body.dark-mode .badge-income {
        background: rgba(6, 78, 59, 0.6);
        color: #34d399;
    }
    .badge-expense {
        background: rgba(244, 63, 94, 0.15);
        color: #e11d48;
        border-color: rgba(244, 63, 94, 0.35);
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
        padding: 3px 8px;
        border-radius: 6px;
    }

    .receipt-thumb-box {
        width: 48px;
        height: 48px;
        border-radius: 9px;
        border: 1px solid var(--f-item-border);
        background-color: var(--f-input-bg);
        overflow: hidden;
        position: relative;
        cursor: pointer;
        flex-shrink: 0;
        transition: border-color 0.2s ease;
    }
    .receipt-thumb-box:hover {
        border-color: #10b981;
    }
    .receipt-thumb-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .receipt-thumb-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        opacity: 0;
        transition: opacity 0.2s ease;
        font-size: 13px;
    }
    .receipt-thumb-box:hover .receipt-thumb-overlay {
        opacity: 1;
    }

    .tx-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: var(--f-text-muted);
        background: transparent;
        border: 1px solid transparent;
        transition: all 0.18s ease;
        cursor: pointer;
        padding: 0;
    }
    .tx-action-btn:hover {
        color: var(--f-text-title);
        background-color: var(--f-card-hover-border);
    }
    .tx-action-btn.delete-btn:hover {
        color: #fb7185;
        background: rgba(244, 63, 94, 0.18);
        border-color: rgba(244, 63, 94, 0.3);
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
        background-color: var(--f-item-hover-bg);
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

    /* Lightbox Preview Modal */
    #receiptPreviewModal .modal-content {
        background-color: var(--f-modal-bg) !important;
        border: 1px solid var(--f-modal-border) !important;
        border-radius: 16px;
    }
    #receiptPreviewModal .modal-body {
        padding: 0;
        overflow: hidden;
        text-align: center;
        background-color: var(--f-input-bg);
        border-radius: 16px;
    }
    #receiptPreviewModal img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }

    /* =========================================================
       INVESTMENT & CAPITAL PORTFOLIO HUB STYLES
       ========================================================= */
    .investment-hub-card {
        background: var(--f-card-bg);
        border: 1px solid var(--f-card-border);
        border-radius: 20px;
        box-shadow: var(--f-card-shadow);
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
        padding: 24px;
        margin-bottom: 24px;
    }
    .investment-hub-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981 0%, #06b6d4 50%, #8b5cf6 100%);
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }
    .investment-hub-bg-glow {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, rgba(6, 182, 212, 0.05) 50%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }
    .investment-pill-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }
    .investment-live-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        margin-right: 6px;
        animation: invPulse 2s infinite;
    }
    @keyframes invPulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5); }
        70% { box-shadow: 0 0 0 7px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .investment-balance-hero {
        font-size: 2.25rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.15;
        color: var(--f-text-title);
        margin: 4px 0 6px 0;
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 8px;
    }
    .investment-balance-hero .curr {
        font-size: 1.5rem;
        color: #10b981;
        font-weight: 700;
    }
    .inv-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
        gap: 14px;
        margin-top: 14px;
    }
    .inv-substat {
        background: var(--f-pill-group-bg);
        border: 1px solid var(--f-card-border);
        border-radius: 14px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
    }
    .inv-substat:hover {
        border-color: var(--f-card-hover-border);
        transform: translateY(-1px);
    }
    .inv-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .inv-progress-track {
        height: 6px;
        border-radius: 6px;
        background: var(--f-track-bg);
        overflow: hidden;
        margin-top: 8px;
    }
    .inv-progress-fill {
        height: 100%;
        border-radius: 6px;
        background: linear-gradient(90deg, #10b981, #06b6d4);
        transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .f-btn-investment-add {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        border: none;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .f-btn-investment-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
        color: #ffffff !important;
    }
    .f-btn-investment-withdraw {
        background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(244, 63, 94, 0.35);
        border: none;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .f-btn-investment-withdraw:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(244, 63, 94, 0.45);
        color: #ffffff !important;
    }
    .f-btn-investment-records {
        background: var(--f-pill-group-bg);
        border: 1px solid var(--f-card-border);
        color: var(--f-text-title) !important;
        font-weight: 600;
        padding: 10px 16px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .f-btn-investment-records:hover {
        background: var(--f-pill-hover-bg);
        border-color: var(--f-card-hover-border);
        color: var(--f-text-title) !important;
    }
</style>
@endsection

@section('content')
<div class="finance-wrapper py-2">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12 mb-3 mb-md-0">
            <div class="d-flex align-items-center">
                <div class="kpi-icon-box icon-emerald mr-3" style="width: 48px; height: 48px; border-radius: 12px;">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h3 class="font-weight-bold f-text-title mb-0" style="letter-spacing: -0.02em;">Finance &amp; Accounts Ledger</h3>
                    <p class="f-text-muted mb-0 font-12">Track real-time operational revenue, store expenditure, and net balances</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-12 text-md-right">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end justify-content-start" style="gap: 8px;">
                <button type="button" class="f-btn f-btn-income" data-toggle="modal" data-target="#transactionModal" data-mode="INCOME">
                    <i class="fa-solid fa-plus"></i> Add Income
                </button>
                <button type="button" class="f-btn f-btn-expense" data-toggle="modal" data-target="#transactionModal" data-mode="EXPENSE">
                    <i class="fa-solid fa-minus"></i> Add Expense
                </button>
                <button type="button" class="f-btn f-btn-secondary" id="reloadFinanceBtn" title="Synchronize Data">
                    <i class="fa-solid fa-rotate"></i> Reload
                </button>
                <button type="button" class="f-btn f-btn-secondary" id="exportReportBtn" title="Export Ledger Summary">
                    <i class="fa-solid fa-file-arrow-down"></i> Export Report
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         INVESTMENT & CAPITAL PORTFOLIO HUB
         Real-time working capital tracking, capital additions, withdrawals & investor ledger
         ========================================================================= --}}
    <div class="row">
        <div class="col-12">
            <div class="investment-hub-card">
                <div class="investment-hub-bg-glow"></div>
                <div class="position-relative" style="z-index: 1;">
                    <div class="row align-items-center">
                        {{-- Left Column: Active Capital Balance & Status --}}
                        <div class="col-xl-7 col-lg-7 col-12 mb-3 mb-lg-0">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2" style="gap: 8px;">
                                <span class="investment-pill-badge">
                                    <i class="fa-solid fa-vault mr-1"></i> Capital &amp; Investment Portfolio
                                </span>
                                <span class="badge badge-method font-11">
                                    <span class="investment-live-dot"></span> Active Working Treasury
                                </span>
                                <a href="{{ route('admin.finance.investors') }}" class="badge badge-pill badge-method font-11 d-inline-flex align-items-center" title="Click to manage Investors & Partners directory" style="text-decoration: none; cursor: pointer;">
                                    <i class="fa-solid fa-user-tie mr-1 text-info"></i>
                                    <span>{{ !empty($investmentInvestors) ? count($investmentInvestors) : 0 }} {{ Str::plural('Partner', !empty($investmentInvestors) ? count($investmentInvestors) : 0) }}</span>
                                </a>
                            </div>

                            <div class="d-flex flex-wrap align-items-baseline" style="gap: 12px;">
                                <div>
                                    <span class="f-text-muted font-12 font-weight-bold text-uppercase d-block" style="letter-spacing: 0.05em;">
                                        Net Active Investment Balance
                                    </span>
                                    <div class="investment-balance-hero">
                                        <span class="curr">৳</span>
                                        <span id="invNetBalanceVal" style="{{ $investmentBalance < 0 ? 'color: #f43f5e;' : ($investmentBalance > 0 ? 'color: #10b981;' : '') }}">
                                            {{ number_format(abs($investmentBalance), 2) }}
                                        </span>
                                        @if($investmentBalance < 0)
                                            <span class="badge badge-pill badge-expense font-12" id="invBalanceBadge">
                                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Net Deficit
                                            </span>
                                        @elseif($investmentBalance > 0)
                                            <span class="badge badge-pill badge-income font-12" id="invBalanceBadge">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Healthy Capital
                                            </span>
                                        @else
                                            <span class="badge badge-pill badge-method font-12" id="invBalanceBadge">
                                                <i class="fa-solid fa-circle-minus mr-1"></i> Zero Balance
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Sub-metrics: Total Invested vs Withdrawn & Retention --}}
                            <div class="inv-stats-grid">
                                {{-- 1. Injected Capital --}}
                                <div class="inv-substat">
                                    <div class="inv-stat-icon icon-emerald">
                                        <i class="fa-solid fa-arrow-down-left"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <span class="font-11 f-text-muted d-block text-truncate">Total Injected Capital</span>
                                        <div class="d-flex align-items-baseline" style="gap: 6px;">
                                            <strong class="font-15" style="color: #10b981;" id="invTotalInvestedVal">+৳{{ number_format($totalInvested, 2) }}</strong>
                                            <span class="font-11 f-text-muted" id="invInvestedCount">({{ $investedCount }})</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Withdrawn Capital --}}
                                <div class="inv-substat">
                                    <div class="inv-stat-icon icon-rose">
                                        <i class="fa-solid fa-arrow-up-right"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <span class="font-11 f-text-muted d-block text-truncate">Capital Withdrawn</span>
                                        <div class="d-flex align-items-baseline" style="gap: 6px;">
                                            <strong class="font-15" style="color: #f43f5e;" id="invTotalWithdrawnVal">-৳{{ number_format($totalWithdrawn, 2) }}</strong>
                                            <span class="font-11 f-text-muted" id="invWithdrawnCount">({{ $withdrawnCount }})</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. Capital Retention Rate --}}
                                @php
                                    $retentionPct = $totalInvested > 0 ? min(100, max(0, round(($investmentBalance / $totalInvested) * 100))) : 0;
                                @endphp
                                <div class="inv-substat">
                                    <div class="inv-stat-icon icon-cyan">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                    <div class="w-100 overflow-hidden">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="font-11 f-text-muted text-truncate">Capital Retained</span>
                                            <strong class="font-12 f-text-title" id="invRetentionPct">{{ $retentionPct }}%</strong>
                                        </div>
                                        <div class="inv-progress-track">
                                            <div class="inv-progress-fill" id="invRetentionBar" style="width: {{ $retentionPct }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Investment Action Center --}}
                        <div class="col-xl-5 col-lg-5 col-12 text-lg-right text-left">
                            <div class="d-flex flex-column align-items-lg-end align-items-start">
                                <span class="font-12 f-text-muted mb-2 font-weight-bold text-uppercase d-block" style="letter-spacing: 0.05em;">
                                    <i class="fa-solid fa-sliders mr-1"></i> Capital Actions
                                </span>
                                <div class="d-flex flex-wrap gap-2 justify-content-lg-end justify-content-start w-100" style="gap: 10px;">
                                    <a href="{{ route('admin.finance.investors') }}" class="f-btn f-btn-secondary flex-grow-1 flex-sm-grow-0" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.35); text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px;" title="Manage Investors & View Individual Ledgers">
                                        <i class="fa-solid fa-users-gear font-13"></i> Investors Directory
                                    </a>
                                    <button type="button" class="f-btn f-btn-investment-add flex-grow-1 flex-sm-grow-0" id="btnOpenAddInvestment" data-toggle="modal" data-target="#investmentModal" data-mode="ADD">
                                        <i class="fa-solid fa-circle-plus font-14"></i> Add Investment
                                    </button>
                                    <button type="button" class="f-btn f-btn-investment-withdraw flex-grow-1 flex-sm-grow-0" id="btnOpenWithdrawInvestment" data-toggle="modal" data-target="#investmentModal" data-mode="WITHDRAW">
                                        <i class="fa-solid fa-arrow-up-from-bracket font-14"></i> Withdraw Capital
                                    </button>
                                    <button type="button" class="f-btn f-btn-investment-records flex-grow-1 flex-sm-grow-0" id="btnFilterInvestmentRecords" title="Show only investment entries in table">
                                        <i class="fa-solid fa-list-check font-13 text-info"></i> View Records
                                    </button>
                                </div>
                                <div class="mt-2 text-lg-right text-left">
                                    <small class="f-text-muted font-11">
                                        <i class="fa-solid fa-lock mr-1 text-muted"></i> Entries are synchronized with Cash &amp; Bank Ledgers
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Advanced Filtering & Search Bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-panel">
                {{-- First Row: Time Range Pills & Search --}}
                <div class="row align-items-center mb-3">
                    <div class="col-lg-8 col-12 mb-3 mb-lg-0">
                        <div class="pill-group" id="timeframePills">
                            <button type="button" class="pill-btn {{ ($timeframe ?? 'month') === 'today' ? 'active' : '' }}" data-time="today">Today</button>
                            <button type="button" class="pill-btn {{ ($timeframe ?? 'month') === 'week' ? 'active' : '' }}" data-time="week">This Week</button>
                            <button type="button" class="pill-btn {{ ($timeframe ?? 'month') === 'month' ? 'active' : '' }}" data-time="month">This Month</button>
                            <button type="button" class="pill-btn {{ ($timeframe ?? 'month') === 'year' ? 'active' : '' }}" data-time="year">This Year</button>
                            <button type="button" class="pill-btn {{ ($timeframe ?? 'month') === 'custom' ? 'active' : '' }}" data-time="custom">Custom Range</button>
                            <button type="button" class="pill-btn {{ ($timeframe ?? 'month') === 'all' ? 'active' : '' }}" data-time="all">All Time</button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="f-input-group">
                            <i class="fa-solid fa-magnifying-glass f-input-icon"></i>
                            <input type="text" id="ledgerSearchInput" class="form-control f-input" placeholder="Search notes, category, staff...">
                        </div>
                    </div>
                </div>

                {{-- Second Row: Dropdown Selects & Reset --}}
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
                        <select id="filterType" class="form-control f-select">
                            <option value="ALL">All Types (Income &amp; Expense)</option>
                            <option value="INCOME">Income Only</option>
                            <option value="EXPENSE">Expense Only</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
                        <select id="filterCategory" class="form-control f-select">
                            <option value="ALL">All Categories</option>
                            <option value="ALL_INVESTMENTS">★ All Investments (Inflow &amp; Outflow)</option>
                            <option value="Online Sales">Online Sales</option>
                            <option value="Shop Sales">Shop Sales</option>
                            <option value="Wholesale / Bulk Order">Wholesale / Bulk Order</option>
                            <option value="Exchange / Refund">Exchange / Refund</option>
                            <option value="Investment / Capital">Investment / Capital (Inflow)</option>
                            <option value="Investment Withdrawal">Investment Withdrawal (Outflow)</option>
                            <option value="Product Sourcing">Product Sourcing</option>
                            <option value="Courier / Delivery">Courier / Delivery</option>
                            <option value="Dollar / Ads &amp; Marketing">Dollar / Ads &amp; Marketing</option>
                            <option value="Packaging Material">Packaging Material</option>
                            <option value="Shop Rent &amp; Maintenance">Shop Rent &amp; Maintenance</option>
                            <option value="Staff Salary &amp; Bonus">Staff Salary &amp; Bonus</option>
                            <option value="Utilities &amp; Bills">Utilities &amp; Bills</option>
                            <option value="Office Supplies &amp; Snacks">Office Supplies &amp; Snacks</option>
                            <option value="Taxes / Bank Fees">Taxes / Bank Fees</option>
                            <option value="Other Expense">Other Expense</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
                        <select id="filterStaff" class="form-control f-select">
                            <option value="ALL">All Staff (Recorded By)</option>
                            @foreach($staffList as $staff)
                                <option value="{{ $staff }}">Recorded by: {{ $staff }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <button type="button" id="resetFiltersBtn" class="btn btn-block f-btn f-btn-secondary py-2" style="height: 38px; color: #f59e0b; border-color: rgba(245, 158, 11, 0.3);">
                            <i class="fa-solid fa-rotate-left mr-1"></i> Reset Filters
                        </button>
                    </div>
                </div>

                {{-- Custom Date Range Row (Hidden by default unless Custom Range is active) --}}
                <div id="customDateRangeRow" class="row align-items-center mt-3 pt-3 border-top {{ ($timeframe ?? 'month') === 'custom' ? '' : 'd-none' }}" style="border-color: var(--f-card-border) !important;">
                    <div class="col-md-5 col-sm-6 col-12 mb-2 mb-md-0">
                        <label class="font-11 font-weight-bold f-text-muted mb-1 d-block"><i class="fa-regular fa-calendar mr-1"></i> Start Date</label>
                        <input type="date" id="filterStartDate" value="{{ request('start_date', date('Y-m-01')) }}" class="form-control f-input" style="height: 38px;">
                    </div>
                    <div class="col-md-5 col-sm-6 col-12 mb-2 mb-md-0">
                        <label class="font-11 font-weight-bold f-text-muted mb-1 d-block"><i class="fa-regular fa-calendar-check mr-1"></i> End Date</label>
                        <input type="date" id="filterEndDate" value="{{ request('end_date', date('Y-m-d')) }}" class="form-control f-input" style="height: 38px;">
                    </div>
                    <div class="col-md-2 col-12 d-flex align-items-end" style="height: 100%;">
                        <div class="w-100 mt-md-4">
                            <button type="button" id="applyCustomDateBtn" class="btn btn-block f-btn f-btn-income py-2" style="height: 38px;">
                                <i class="fa-solid fa-check mr-1"></i> Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- KPI Metric Cards Grid (4 Columns) --}}
    <div class="row mb-4">
        {{-- Card 1: Net Balance --}}
        <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
            <div class="f-card kpi-card kpi-net">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Net Balance</span>
                        <div class="kpi-icon-box icon-cyan">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <div class="kpi-value" id="kpiNetBalance" style="{{ $netBalance < 0 ? 'color: #f43f5e;' : ($netBalance > 0 ? 'color: #10b981;' : '') }}">
                        {{ $netBalance < 0 ? '-৳' . number_format(abs($netBalance), 2) : '৳' . number_format($netBalance, 2) }}
                    </div>
                </div>
                <div class="kpi-sub">
                    <span class="badge badge-pill {{ $netBalance >= 0 ? 'badge-income' : 'badge-expense' }} mr-1" id="kpiNetBadge" style="font-size: 11px;">
                        <i class="fa-solid {{ $netBalance >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i> {{ $netBalance >= 0 ? 'Net Profit' : 'Net Deficit' }}
                    </span>
                    <span class="f-text-muted">Calculated balance</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Revenue --}}
        <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
            <div class="f-card kpi-card kpi-revenue">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Total Revenue</span>
                        <div class="kpi-icon-box icon-emerald">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>
                    </div>
                    <div class="kpi-value" id="kpiTotalRevenue" style="color: #10b981;">৳{{ number_format($totalIncome, 2) }}</div>
                </div>
                <div class="kpi-sub">
                    <span class="badge badge-pill badge-income mr-1" id="kpiRevenueBadge" style="font-size: 11px;">
                        <i class="fa-regular fa-circle-check"></i> <span id="kpiRevenueCount">{{ $incomeEntriesCount }} {{ Str::plural('entry', $incomeEntriesCount) }}</span>
                    </span>
                    <span class="f-text-muted">Inflow recorded</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Expenses --}}
        <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
            <div class="f-card kpi-card kpi-expense">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Total Expense</span>
                        <div class="kpi-icon-box icon-rose">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                        </div>
                    </div>
                    <div class="kpi-value" id="kpiTotalExpense" style="color: #f43f5e;">৳{{ number_format($totalExpense, 2) }}</div>
                </div>
                <div class="kpi-sub">
                    <span class="badge badge-pill badge-expense mr-1" id="kpiExpenseBadge" style="font-size: 11px;">
                        <i class="fa-solid fa-receipt"></i> <span id="kpiExpenseCount">{{ $expenseEntriesCount }} {{ Str::plural('entry', $expenseEntriesCount) }}</span>
                    </span>
                    <span class="f-text-muted">Operational outlays</span>
                </div>
            </div>
        </div>

        {{-- Card 4: Operations / Transactions --}}
        <div class="col-xl-3 col-md-6 col-12">
            <div class="f-card kpi-card kpi-trans">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="kpi-title">Transactions</span>
                        <div class="kpi-icon-box icon-violet">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                    </div>
                    <div class="kpi-value" id="kpiTotalTransactions">{{ $totalTransactions }} <span style="font-size: 1rem; color: var(--f-text-muted); font-weight: 500;">Ops</span></div>
                </div>
                <div class="kpi-sub">
                    <span class="badge badge-pill badge-method mr-1">
                        <i class="fa-solid fa-bolt text-warning"></i> Active
                    </span>
                    <span class="f-text-muted">Total operations count</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Operational Breakdown Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="f-card p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-chart-column mr-2" style="color: #06b6d4;"></i>
                        <h5 class="font-weight-bold f-text-title mb-0 font-15">Operational Breakdown &amp; Category Distribution</h5>
                    </div>
                    <span class="badge badge-method font-11">Based on active records</span>
                </div>

                <div class="row" id="categoryBreakdownContainer">
                    @forelse($categoryBreakdown as $cat)
                        @php
                            $isIncome = $cat->entry_type === 'INCOME';
                            $dotColor = $isIncome ? '#10b981' : '#f43f5e';
                            $barGrad = $isIncome ? 'linear-gradient(90deg, #059669, #2dd4bf)' : 'linear-gradient(90deg, #e11d48, #f59e0b)';
                        @endphp
                        <div class="col-lg-4 col-12 mb-3 mb-lg-0">
                            <div class="breakdown-row" onclick="$('#filterCategory').val('{{ $cat->category }}').trigger('change');" title="Click to filter by {{ $cat->category }}">
                                <div class="d-flex align-items-center justify-content-between font-12 mb-1">
                                    <div class="d-flex align-items-center">
                                        <span class="mr-2" style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $dotColor }}; display: inline-block;"></span>
                                        <strong class="f-text-title">{{ $cat->category }}</strong>
                                        <span class="badge badge-method ml-2" style="font-size: 10px;">{{ $cat->entries_count }} {{ Str::plural('entry', $cat->entries_count) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <strong style="color: {{ $dotColor }};">৳{{ number_format($cat->total_amount, 2) }}</strong>
                                        <span class="f-text-muted font-11 ml-1">({{ $cat->percentage }}%)</span>
                                    </div>
                                </div>
                                <div class="progress-track">
                                    <div class="progress-bar-custom" style="width: {{ $cat->percentage }}%; background: {{ $barGrad }};"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-3">
                            <span class="f-text-muted font-13"><i class="fa-solid fa-chart-pie mr-1"></i> No transactions recorded yet. Add your first income or expense entry above.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions List Section --}}
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <h5 class="font-weight-bold f-text-title mb-0 mr-2 font-16">Recent Transactions</h5>
                    <span class="badge badge-pill badge-income font-11" id="txCountBadge">Showing {{ $transactions->count() }} of {{ $totalTransactions }}</span>
                </div>
                <a href="{{ route('admin.finance.history') }}" class="f-btn f-btn-secondary py-1 px-3 font-12" id="fullHistoryBtn">
                    <i class="fa-solid fa-clock-rotate-left mr-1" style="color: #10b981;"></i> History <i class="fa-solid fa-arrow-right font-10 ml-1"></i>
                </a>
            </div>

            {{-- Transactions Container --}}
            <div id="transactionsList">
                @forelse($transactions as $tx)
                    @php
                        $isInc = $tx->entry_type === 'INCOME';
                        $iconClass = $isInc ? 'icon-emerald' : 'icon-rose';
                        $iconFa = $isInc ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down';
                        $badgeClass = $isInc ? 'badge-income' : 'badge-expense';
                        $amtColor = $isInc ? '#10b981' : '#f43f5e';
                        $amtPrefix = $isInc ? '+৳' : '-৳';
                        $hasReceipt = !empty($tx->receipt_image) && file_exists(public_path('Uploads/finance/' . $tx->receipt_image));
                        $txTimestamp = $tx->transaction_date ? $tx->transaction_date->timestamp : 0;
                    @endphp
                    <div class="tx-item"
                         data-id="{{ $tx->id }}"
                         data-type="{{ $tx->entry_type }}"
                         data-category="{{ $tx->category }}"
                         data-staff="{{ $tx->staff_name }}"
                         data-amount="{{ $tx->amount }}"
                         data-method="{{ $tx->payment_method }}"
                         data-date="{{ $tx->transaction_date ? $tx->transaction_date->format('Y-m-d\TH:i') : '' }}"
                         data-notes="{{ $tx->notes ?? '' }}"
                         data-receipt="{{ $hasReceipt ? asset('Uploads/finance/' . $tx->receipt_image) : '' }}"
                         data-timestamp="{{ $txTimestamp }}">
                        <div class="row align-items-center">
                            <div class="col-lg-8 col-12 mb-3 mb-lg-0">
                                <div class="d-flex align-items-start">
                                    <div class="tx-type-icon {{ $iconClass }} mr-3">
                                        <i class="fa-solid {{ $iconFa }}"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex flex-wrap align-items-center mb-1" style="gap: 8px;">
                                            <h6 class="font-weight-bold f-text-title mb-0 font-15">{{ $tx->category }}</h6>
                                            <span class="tx-badge {{ $badgeClass }}">
                                                <i class="fa-solid {{ $isInc ? 'fa-arrow-up' : 'fa-arrow-down' }} font-9"></i> {{ ucfirst(strtolower($tx->entry_type)) }}
                                            </span>
                                            <span class="badge-method">
                                                <i class="fa-regular fa-credit-card mr-1 text-muted"></i> {{ $tx->payment_method }}
                                            </span>
                                        </div>
                                        @if($tx->notes)
                                            <p class="f-text-muted font-12 mb-1 text-truncate" style="max-width: 550px;" title="{{ $tx->notes }}">
                                                <i class="fa-regular fa-file-lines mr-1 text-muted"></i>
                                                <span>{{ $tx->notes }}</span>
                                            </p>
                                        @endif
                                        <div class="d-flex flex-wrap align-items-center font-11 f-text-muted" style="gap: 14px;">
                                            <span>
                                                <i class="fa-regular fa-calendar-days mr-1 text-muted"></i> 
                                                {{ $tx->transaction_date ? $tx->transaction_date->format('M j, Y · g:i A') : 'N/A' }}
                                            </span>
                                            <span>
                                                <i class="fa-regular fa-circle-user mr-1 text-success"></i> Added by:
                                                <strong class="text-success px-1.5 py-0.5 rounded" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.25);">{{ $tx->staff_name }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 text-lg-right text-left">
                                <div class="d-flex align-items-center justify-content-lg-end justify-content-between" style="gap: 14px;">
                                    @if($hasReceipt)
                                        <div class="receipt-thumb-box" title="View Full Receipt Photo (.webp)" data-receipt-src="{{ asset('Uploads/finance/' . $tx->receipt_image) }}">
                                            <img src="{{ asset('Uploads/finance/' . $tx->receipt_image) }}" alt="Receipt Preview">
                                            <div class="receipt-thumb-overlay">
                                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="text-left text-lg-right">
                                        <div class="font-weight-bold font-18" style="color: {{ $amtColor }};">{{ $amtPrefix }}{{ number_format($tx->amount, 2) }}</div>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 4px;">
                                        <button type="button" class="tx-action-btn edit-tx-btn" title="Edit Transaction">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="tx-action-btn delete-btn delete-tx-btn" title="Delete Transaction">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse

                {{-- Empty state container (shown when no items match filters) --}}
                <div id="emptyLedgerNotice" class="f-card text-center p-5 {{ $transactions->count() > 0 ? 'd-none' : '' }}">
                    <i class="fa-solid fa-receipt f-text-muted mb-3" style="font-size: 42px;"></i>
                    <h5 class="f-text-title font-weight-bold">No transactions found</h5>
                    <p class="f-text-muted font-13 mb-3">No ledger records match the selected filter criteria.</p>
                    <button type="button" class="f-btn f-btn-secondary" id="emptyResetBtn">
                        <i class="fa-solid fa-rotate-left mr-1"></i> Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODAL: ADD / EDIT TRANSACTION (Bootstrap 4 Modal with Income & Expense Switch)
     ========================================================================= --}}
<div class="modal fade f-modal" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 780px !important; width: 95% !important;" role="document">
        <div class="modal-content" style="width: 100% !important; max-width: 100% !important; padding: 0 !important; border-radius: 20px !important;">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box icon-emerald mr-3" id="modalHeaderIcon" style="width: 40px; height: 40px; border-radius: 10px;">
                        <i class="fa-solid fa-plus font-16"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold f-text-title mb-0" id="transactionModalTitle">Add Finance Entry</h5>
                        <small class="f-text-muted font-11">Business Tracking System</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="transactionForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editTxId" name="tx_id" value="">
                <div class="modal-body">
                    {{-- Income / Expense Segmented Switcher --}}
                    <div class="type-switcher mb-3">
                        <button type="button" class="type-switcher-btn active-income" id="switchIncomeBtn">
                            <i class="fa-solid fa-arrow-trend-up font-14"></i>
                            <span>Income (Revenue)</span>
                        </button>
                        <button type="button" class="type-switcher-btn" id="switchExpenseBtn">
                            <i class="fa-solid fa-arrow-trend-down font-14"></i>
                            <span>Expense (Cost)</span>
                        </button>
                    </div>
                    <input type="hidden" id="entryType" name="entry_type" value="INCOME">

                    {{-- Hero Amount Card --}}
                    <div class="hero-amount-card" id="heroAmountCard">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="modal-label mb-0" for="entryAmount">
                                <i class="fa-solid fa-coins mr-1.5 text-muted"></i> Amount <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <span class="badge badge-pill badge-income font-11 d-none" id="txAvailableFundsPill">
                                    <i class="fa-solid fa-vault mr-1"></i> Available: ৳<span id="txAvailableFundsVal">0.00</span>
                                </span>
                                <span class="badge badge-method font-11 font-weight-bold">
                                    BDT ৳ Currency
                                </span>
                            </div>
                        </div>
                        <div class="hero-amount-input-box">
                            <div class="hero-currency-symbol" id="heroCurrencySymbol">৳</div>
                            <input type="number" step="0.01" min="0.01" required placeholder="0.00" id="entryAmount" class="hero-amount-input" autocomplete="off">
                        </div>
                        <div id="txAmountLimitNotice" class="d-none mt-2 font-12 font-weight-bold p-2" style="color: #f43f5e; background: rgba(244, 63, 94, 0.1); border-radius: 8px; border-left: 3px solid #f43f5e;">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> <span id="txAmountLimitMsg"></span>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-2" style="gap: 6px;">
                            <span class="font-11 f-text-muted mr-1"><i class="fa-solid fa-bolt text-warning mr-1"></i>Quick Add:</span>
                            <button type="button" class="amount-chip" data-val="500">+৳500</button>
                            <button type="button" class="amount-chip" data-val="1000">+৳1,000</button>
                            <button type="button" class="amount-chip" data-val="2000">+৳2,000</button>
                            <button type="button" class="amount-chip" data-val="5000">+৳5,000</button>
                            <button type="button" class="amount-chip" data-val="10000">+৳10,000</button>
                            <button type="button" class="amount-chip-clear ml-auto" id="clearAmountChip">Clear</button>
                        </div>
                    </div>

                    {{-- 2x2 Grid of Core Fields --}}
                    <div class="row">
                        {{-- Category Dropdown --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="modal-label" for="entryCategory">
                                <i class="fa-solid fa-layer-group mr-1.5 text-muted"></i> Category <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="f-field-group">
                                <i class="fa-solid fa-tag f-field-icon"></i>
                                <select id="entryCategory" class="f-modal-select" required>
                                    <optgroup label="── Income Streams &amp; Capital Inflow ──">
                                        <option value="Online Sales">Online Sales</option>
                                        <option value="Shop Sales">Shop Sales</option>
                                        <option value="Wholesale / Bulk Order">Wholesale / Bulk Order</option>
                                        <option value="Investment / Capital">Investment / Capital</option>
                                        <option value="Other">Other / Custom</option>
                                    </optgroup>
                                    <optgroup label="── Operating Expenses &amp; Capital Outflow ──">
                                        <option value="Investment Withdrawal">Investment Withdrawal</option>
                                        <option value="Product Sourcing">Product Sourcing</option>
                                        <option value="Courier / Delivery">Courier / Delivery</option>
                                        <option value="Dollar / Ads &amp; Marketing">Dollar / Ads &amp; Marketing</option>
                                        <option value="Packaging Material">Packaging Material</option>
                                        <option value="Shop Rent &amp; Maintenance">Shop Rent &amp; Maintenance</option>
                                        <option value="Staff Salary &amp; Bonus">Staff Salary &amp; Bonus</option>
                                        <option value="Utilities &amp; Bills">Utilities &amp; Bills</option>
                                        <option value="Office Supplies &amp; Snacks">Office Supplies &amp; Snacks</option>
                                        <option value="Exchange / Refund">Exchange / Refund</option>
                                        <option value="Other Expense">Other Expense</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="modal-label" for="entryPaymentMethod">
                                <i class="fa-solid fa-wallet mr-1.5 text-muted"></i> Payment Method <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="f-field-group">
                                <i class="fa-solid fa-credit-card f-field-icon"></i>
                                <select id="entryPaymentMethod" class="f-modal-select" required>
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
                            <label class="modal-label" for="entryDateTime">
                                <i class="fa-regular fa-calendar-days mr-1.5 text-muted"></i> Date &amp; Time <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="f-field-group">
                                <i class="fa-regular fa-clock f-field-icon"></i>
                                <input type="datetime-local" required id="entryDateTime" class="f-modal-input" value="{{ date('Y-m-d\TH:i') }}">
                            </div>
                        </div>

                        {{-- Recorded By --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="modal-label mb-0" for="entryStaff">
                                    <i class="fa-regular fa-user mr-1.5 text-muted"></i> Recorded By <span class="text-danger ml-0.5">*</span>
                                </label>
                                <span class="badge badge-income font-10">
                                    <i class="fa-solid fa-circle-check font-9 mr-1"></i> Current User
                                </span>
                            </div>
                            <div class="f-field-group">
                                <i class="fa-solid fa-user-pen f-field-icon"></i>
                                <input type="text" disabled required list="staffSuggestions" id="entryStaff" class="f-modal-input" value="{{ auth('admin')->user()->name ?? 'Looksmen' }}" placeholder="e.g. Owner, Sabbir, Raju">
                            </div>
                            <datalist id="staffSuggestions">
                                @foreach($staffList as $staff)
                                    <option value="{{ $staff }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    {{-- Notes / Details --}}
                    <div class="form-group mb-3">
                        <label class="modal-label" for="entryNotes">
                            <i class="fa-regular fa-comment-dots mr-1.5 text-muted"></i> Notes / Reference (Optional)
                        </label>
                        <div class="f-field-group">
                            <i class="fa-solid fa-pen-nib f-field-icon" style="top: 18px;"></i>
                            <textarea id="entryNotes" rows="2" class="f-modal-textarea" placeholder="e.g. Customer order #104, Supplier invoice #441, Friday cash settlement"></textarea>
                        </div>
                    </div>

                    {{-- Invoice / Receipt Photo Upload --}}
                    <div class="form-group mb-0">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="modal-label mb-0">
                                <i class="fa-solid fa-receipt mr-1.5 text-muted"></i> Invoice / Receipt Attachment (Optional)
                            </label>
                            <span class="font-11 f-text-muted">Auto-saved as WebP • Max 5MB</span>
                        </div>

                        <!-- Dropzone area -->
                        <div class="upload-dropzone" id="receiptUploadTrigger">
                            <div class="dropzone-icon-circle">
                                <i class="fa-solid fa-cloud-arrow-up font-16"></i>
                            </div>
                            <div class="font-13 font-weight-bold f-text-title mb-1">Click or drag &amp; drop invoice receipt photo</div>
                            <div class="font-11 f-text-muted">Upload proof screenshot or physical paper slip (Saved in WebP)</div>
                        </div>
                        <input type="file" id="receiptFileInput" name="receipt_image" class="d-none" accept="image/*">

                        <!-- Preview Card (shown when an image is selected) -->
                        <div id="receiptPreviewCard" class="receipt-preview-card d-none mt-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center overflow-hidden mr-2">
                                    <div class="receipt-preview-img-box mr-3">
                                        <img id="receiptPreviewImg" src="" alt="Receipt Preview">
                                    </div>
                                    <div class="overflow-hidden">
                                        <div id="fileUploadName" class="font-12 font-weight-bold f-text-title text-truncate">receipt.png</div>
                                        <div id="fileUploadSize" class="font-11 f-text-muted">Ready to attach</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-remove-receipt" id="removeReceiptBtn" title="Remove attachment">
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
                    <button type="submit" class="f-btn f-btn-income" id="submitModalBtn">
                        <i class="fa-solid fa-check mr-1"></i> <span>Confirm Entry</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODAL: CAPITAL INVESTMENT & WITHDRAWAL (Dedicated Executive Modal)
     ========================================================================= --}}
<div class="modal fade f-modal" id="investmentModal" tabindex="-1" role="dialog" aria-labelledby="invModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 750px !important; width: 95% !important;" role="document">
        <div class="modal-content" style="width: 100% !important; max-width: 100% !important; padding: 0 !important; border-radius: 20px !important;">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box icon-emerald mr-3" id="invModalHeaderIcon" style="width: 42px; height: 42px; border-radius: 12px;">
                        <i class="fa-solid fa-vault font-16"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold f-text-title mb-0" id="invModalTitle">Add Capital Investment</h5>
                        <small class="f-text-muted font-11">FreshEcom Working Capital Portfolio &amp; Treasury</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="investmentForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="invMode" value="ADD">
                <input type="hidden" id="invEntryType" name="entry_type" value="INCOME">
                <input type="hidden" id="invCategory" name="category" value="Investment / Capital">

                <div class="modal-body">
                    {{-- Capital Action Switcher (Deposit vs Withdrawal) --}}
                    <div class="type-switcher mb-3">
                        <button type="button" class="type-switcher-btn active-income" id="invSwitchAddBtn">
                            <i class="fa-solid fa-vault font-14"></i>
                            <span>Deposit / Add Capital</span>
                        </button>
                        <button type="button" class="type-switcher-btn" id="invSwitchWithdrawBtn">
                            <i class="fa-solid fa-money-bill-transfer font-14"></i>
                            <span>Withdraw Capital</span>
                        </button>
                    </div>

                    {{-- Hero Amount Card --}}
                    <div class="hero-amount-card" id="invHeroAmountCard">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="modal-label mb-0" for="invEntryAmount" id="invAmountLabel">
                                <i class="fa-solid fa-coins mr-1.5 text-muted"></i> Investment Amount <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <span class="badge badge-pill badge-income font-11 d-none" id="invAvailableCapPill">
                                    <i class="fa-solid fa-vault mr-1"></i> Active Cap: ৳<span id="invAvailableCapVal">{{ number_format(max(0, $investmentBalance), 2) }}</span>
                                </span>
                                <span class="badge badge-method font-11 font-weight-bold">
                                    BDT ৳ Currency
                                </span>
                            </div>
                        </div>
                        <div class="hero-amount-input-box">
                            <div class="hero-currency-symbol" id="invCurrencySymbol">৳</div>
                            <input type="number" step="0.01" min="0.01" required placeholder="0.00" id="invEntryAmount" name="amount" class="hero-amount-input" autocomplete="off">
                        </div>
                        <div id="invAmountLimitNotice" class="d-none mt-2 font-12 font-weight-bold p-2" style="color: #f43f5e; background: rgba(244, 63, 94, 0.1); border-radius: 8px; border-left: 3px solid #f43f5e;">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> <span id="invAmountLimitMsg"></span>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-2" style="gap: 6px;">
                            <span class="font-11 f-text-muted mr-1"><i class="fa-solid fa-bolt text-warning mr-1"></i>Quick Add:</span>
                            <button type="button" class="amount-chip inv-withdraw-all-chip d-none" id="invWithdrawAllBtn" style="color: #6366f1; border-color: rgba(99, 102, 241, 0.4); font-weight: 600;">Withdraw All</button>
                            <button type="button" class="amount-chip inv-amount-chip" data-val="10000">+৳10,000</button>
                            <button type="button" class="amount-chip inv-amount-chip" data-val="25000">+৳25,000</button>
                            <button type="button" class="amount-chip inv-amount-chip" data-val="50000">+৳50,000</button>
                            <button type="button" class="amount-chip inv-amount-chip" data-val="100000">+৳100,000</button>
                            <button type="button" class="amount-chip inv-amount-chip" data-val="500000">+৳500,000</button>
                            <button type="button" class="amount-chip-clear ml-auto" id="invClearAmountChip">Clear</button>
                        </div>
                    </div>

                    {{-- 2x2 Grid of Core Fields --}}
                    <div class="row">
                        {{-- Investor / Partner Name --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="modal-label mb-0" for="invEntryStaffSelect">
                                    <i class="fa-solid fa-user-tie mr-1.5 text-muted"></i> Investor / Partner Name <span class="text-danger ml-0.5">*</span>
                                </label>
                                <span class="badge badge-income font-10">
                                    <i class="fa-solid fa-handshake font-9 mr-1"></i> Partner
                                </span>
                            </div>
                            <div class="f-field-group">
                                <i class="fa-solid fa-user-tie f-field-icon"></i>
                                <select id="invEntryStaffSelect" class="f-modal-select" required>
                                    <option value="" disabled selected>-- Select an Investor / Partner --</option>
                                    @if(!empty($registeredInvestors) && $registeredInvestors->count() > 0)
                                        @foreach($registeredInvestors as $regInv)
                                            <option value="{{ $regInv->name }}" data-balance="{{ $regInv->active_balance }}">
                                                {{ $regInv->name }} (Capital: ৳{{ number_format($regInv->active_balance, 2) }})
                                            </option>
                                        @endforeach
                                    @elseif(!empty($investmentInvestors) && count($investmentInvestors) > 0)
                                        @foreach($investmentInvestors as $invName)
                                            <option value="{{ $invName }}">{{ $invName }}</option>
                                        @endforeach
                                    @endif
                                    <option value="__NEW__">+ Enter New Partner Name...</option>
                                </select>
                            </div>

                            {{-- Hidden actual staff input passed to backend --}}
                            <input type="hidden" id="invEntryStaff" name="staff_name" value="">

                            {{-- Custom Name Input (Toggled when __NEW__ is selected) --}}
                            <div id="newInvestorFieldWrap" class="mt-2 d-none">
                                <div class="f-field-group">
                                    <i class="fa-solid fa-user-plus f-field-icon text-info"></i>
                                    <input type="text" id="invEntryStaffCustom" class="f-modal-input" placeholder="Type new investor name here...">
                                </div>
                                <small class="f-text-muted font-11 d-block mt-1">
                                    <i class="fa-solid fa-circle-info mr-1 text-info"></i> This partner will be registered in your Investor Directory.
                                </small>
                            </div>

                            {{-- Selected Partner Specific Balance Indicator --}}
                            <div id="invSelectedPartnerNotice" class="mt-1 d-none">
                                <small class="font-11 font-weight-bold" style="color: #10b981;">
                                    <i class="fa-solid fa-circle-info mr-1"></i> Partner Working Capital: <span id="invPartnerSpecificBalanceVal">৳0.00</span>
                                </small>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="modal-label" for="invEntryPaymentMethod">
                                <i class="fa-solid fa-wallet mr-1.5 text-muted"></i> Payment / Transfer Method <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="f-field-group">
                                <i class="fa-solid fa-building-columns f-field-icon"></i>
                                <select id="invEntryPaymentMethod" name="payment_method" class="f-modal-select" required>
                                    <option value="Bank Transfer">Bank Transfer (Corporate / Personal)</option>
                                    <option value="Cash">Cash in Hand</option>
                                    <option value="bKash/Nagad">bKash / Nagad (MFS)</option>
                                    <option value="Cheque">Cheque / Pay Order</option>
                                    <option value="Credit Card">Credit / Debit Card</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Date & Time --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="modal-label" for="invEntryDateTime">
                                <i class="fa-regular fa-calendar-days mr-1.5 text-muted"></i> Date &amp; Time <span class="text-danger ml-0.5">*</span>
                            </label>
                            <div class="f-field-group">
                                <i class="fa-regular fa-clock f-field-icon"></i>
                                <input type="datetime-local" required id="invEntryDateTime" name="transaction_date" class="f-modal-input" value="{{ date('Y-m-d\TH:i') }}">
                            </div>
                        </div>

                        {{-- Ledger Category Display (Locked / Indicative) --}}
                        <div class="col-md-6 col-12 form-group mb-3">
                            <label class="modal-label">
                                <i class="fa-solid fa-tags mr-1.5 text-muted"></i> Ledger Category Tag
                            </label>
                            <div class="f-field-group">
                                <i class="fa-solid fa-shield-halved f-field-icon text-success"></i>
                                <input type="text" id="invCategoryDisplay" class="f-modal-input" readonly value="Investment / Capital (Inflow)" style="background: var(--f-pill-group-bg); cursor: default;">
                            </div>
                        </div>
                    </div>

                    {{-- Notes / Details --}}
                    <div class="form-group mb-3">
                        <label class="modal-label" for="invEntryNotes">
                            <i class="fa-regular fa-comment-dots mr-1.5 text-muted"></i> Reference / Terms / Notes (Optional)
                        </label>
                        <div class="f-field-group">
                            <i class="fa-solid fa-pen-nib f-field-icon" style="top: 18px;"></i>
                            <textarea id="invEntryNotes" name="notes" rows="2" class="f-modal-textarea" placeholder="e.g. Initial seed equity, 25% partnership buy-in, Store expansion fund, Dividend withdrawal..."></textarea>
                        </div>
                    </div>

                    {{-- Voucher / Cheque / Bank Slip Upload --}}
                    <div class="form-group mb-0">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="modal-label mb-0">
                                <i class="fa-solid fa-receipt mr-1.5 text-muted"></i> Bank Deposit Slip / Voucher Attachment (Optional)
                            </label>
                            <span class="font-11 f-text-muted">Auto-saved as WebP • Max 5MB</span>
                        </div>

                        <!-- Dropzone area -->
                        <div class="upload-dropzone" id="invReceiptUploadTrigger">
                            <div class="dropzone-icon-circle">
                                <i class="fa-solid fa-cloud-arrow-up font-16"></i>
                            </div>
                            <div class="font-13 font-weight-bold f-text-title mb-1">Click or drag &amp; drop bank slip or voucher</div>
                            <div class="font-11 f-text-muted">Upload cheque photo, bank wire confirmation, or signed slip (Saved in WebP)</div>
                        </div>
                        <input type="file" id="invReceiptFileInput" name="receipt_image" class="d-none" accept="image/*">

                        <!-- Preview Card -->
                        <div id="invReceiptPreviewCard" class="receipt-preview-card d-none mt-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center overflow-hidden mr-2">
                                    <div class="receipt-preview-img-box mr-3">
                                        <img id="invReceiptPreviewImg" src="" alt="Voucher Preview">
                                    </div>
                                    <div class="overflow-hidden">
                                        <div id="invFileUploadName" class="font-12 font-weight-bold f-text-title text-truncate">slip.png</div>
                                        <div id="invFileUploadSize" class="font-11 f-text-muted">Ready to attach</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-remove-receipt" id="invRemoveReceiptBtn" title="Remove attachment">
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
                    <button type="submit" class="f-btn f-btn-income" id="invSubmitModalBtn">
                        <i class="fa-solid fa-vault mr-1"></i> <span>Confirm Capital Deposit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODAL: RECEIPT LIGHTBOX (Bootstrap 4 Modal for Image Preview)
     ========================================================================= --}}
<div class="modal fade f-modal" id="receiptPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 600px !important; width: 95% !important;" role="document">
        <div class="modal-content" style="width: 100% !important; max-width: 100% !important; padding: 0 !important; border-radius: 16px !important;">
            <div class="modal-header py-2 px-3 border-0 d-flex justify-content-between align-items-center">
                <span class="font-12 font-weight-bold f-text-title">
                    <i class="fa-solid fa-receipt mr-1 text-success"></i> Invoice / Receipt Preview
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-2 text-center">
                <img id="lightboxImage" src="" alt="Receipt Photo" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // CSRF Setup for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // =========================================================
    // 0. Fund Limits & Capital Tracking
    // =========================================================
    let currentInvestmentBalance = {{ (float) max(0, $investmentBalance) }};
    let currentAvailableWorkingBalance = {{ (float) max(0, $availableWorkingBalance ?? $netBalance) }};

    function validateTxAmount() {
        const entryType = $('#entryType').val();
        const amount = parseFloat($('#entryAmount').val()) || 0;
        const $notice = $('#txAmountLimitNotice');
        const $msg = $('#txAmountLimitMsg');
        const $submitBtn = $('#submitModalBtn');

        if (entryType === 'EXPENSE') {
            const cat = $('#entryCategory').val();
            const isCapWithdrawal = ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital'].includes(cat);
            const limit = isCapWithdrawal ? currentInvestmentBalance : currentAvailableWorkingBalance;
            const limitLabel = isCapWithdrawal ? 'Active Capital Balance' : 'Available Capital / Balance';

            $('#txAvailableFundsPill').removeClass('d-none');
            $('#txAvailableFundsVal').text(limit.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            if (amount > limit) {
                $notice.removeClass('d-none');
                $msg.html(`Expense amount (৳${amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}) exceeds ${limitLabel} (৳${limit.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}). Capital এর চেয়ে বেশি খরচ করা যাবে না।`);
                $submitBtn.prop('disabled', true);
                return false;
            } else {
                $notice.addClass('d-none');
                $submitBtn.prop('disabled', false);
                return true;
            }
        } else {
            $('#txAvailableFundsPill').addClass('d-none');
            $notice.addClass('d-none');
            $submitBtn.prop('disabled', false);
            return true;
        }
    }

    function validateInvAmount() {
        const mode = $('#invMode').val();
        const amount = parseFloat($('#invEntryAmount').val()) || 0;
        const $notice = $('#invAmountLimitNotice');
        const $msg = $('#invAmountLimitMsg');
        const $submitBtn = $('#invSubmitModalBtn');

        if (mode === 'WITHDRAW') {
            $('#invAvailableCapPill').removeClass('d-none');
            $('#invAvailableCapVal').text(currentInvestmentBalance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            if (currentInvestmentBalance <= 0) {
                $notice.removeClass('d-none');
                $msg.html(`Active capital balance is ৳0.00. Capital-এ কোনো ফান্ড নেই, উত্তোলন করা সম্ভব নয়।`);
                $submitBtn.prop('disabled', true);
                return false;
            } else if (amount > currentInvestmentBalance) {
                $notice.removeClass('d-none');
                $msg.html(`Withdrawal amount (৳${amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}) exceeds active capital (৳${currentInvestmentBalance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}). Capital এর চেয়ে বেশি তোলা যাবে না।`);
                $submitBtn.prop('disabled', true);
                return false;
            } else {
                $notice.addClass('d-none');
                $submitBtn.prop('disabled', false);
                return true;
            }
        } else {
            $('#invAvailableCapPill').addClass('d-none');
            $notice.addClass('d-none');
            $submitBtn.prop('disabled', false);
            return true;
        }
    }

    // Real-time validation listeners for transaction modal
    $('#entryAmount').on('input keyup change', validateTxAmount);
    $('#entryCategory').on('change', validateTxAmount);

    // Real-time validation listener for investment modal
    $('#invEntryAmount').on('input keyup change', validateInvAmount);

    // =========================================================
    // 1. Transaction Modal Mode Switching & Interactions
    // =========================================================
    function setModalMode(mode, preserveCategory = false) {
        const $entryType = $('#entryType');
        const $switchIncome = $('#switchIncomeBtn');
        const $switchExpense = $('#switchExpenseBtn');
        const $headerIcon = $('#modalHeaderIcon');
        const $modalTitle = $('#transactionModalTitle');
        const $submitBtn = $('#submitModalBtn');
        const $heroCard = $('#heroAmountCard');

        if (mode === 'EXPENSE') {
            $entryType.val('EXPENSE');
            $switchIncome.removeClass('active-income');
            $switchExpense.addClass('active-expense');
            $headerIcon.removeClass('icon-emerald').addClass('icon-rose');
            $headerIcon.find('i').removeClass('fa-plus').addClass('fa-minus');
            $modalTitle.text($('#editTxId').val() ? 'Edit Expense Entry' : 'Add Expense (Cost)');
            $submitBtn.removeClass('f-btn-income').addClass('f-btn-expense');
            $submitBtn.html(`<i class="fa-solid fa-arrow-trend-down mr-1"></i> <span>${$('#editTxId').val() ? 'Update Expense' : 'Confirm Expense'}</span>`);
            $heroCard.addClass('expense-mode');

            if (!preserveCategory) {
                const curCat = $('#entryCategory').val();
                const incomeCats = ['Online Sales', 'Shop Sales', 'Wholesale / Bulk Order', 'Investment / Capital'];
                if (incomeCats.includes(curCat)) {
                    $('#entryCategory').val('Product Sourcing');
                }
            }
        } else {
            $entryType.val('INCOME');
            $switchExpense.removeClass('active-expense');
            $switchIncome.addClass('active-income');
            $headerIcon.removeClass('icon-rose').addClass('icon-emerald');
            $headerIcon.find('i').removeClass('fa-minus').addClass('fa-plus');
            $modalTitle.text($('#editTxId').val() ? 'Edit Income Entry' : 'Add Income (Revenue)');
            $submitBtn.removeClass('f-btn-expense').addClass('f-btn-income');
            $submitBtn.html(`<i class="fa-solid fa-arrow-trend-up mr-1"></i> <span>${$('#editTxId').val() ? 'Update Income' : 'Confirm Income'}</span>`);
            $heroCard.removeClass('expense-mode');

            if (!preserveCategory) {
                const curCat = $('#entryCategory').val();
                const expenseCats = ['Investment Withdrawal', 'Product Sourcing', 'Courier / Delivery', 'Dollar / Ads & Marketing', 'Packaging Material', 'Shop Rent & Maintenance', 'Staff Salary & Bonus', 'Utilities & Bills', 'Office Supplies & Snacks'];
                if (expenseCats.includes(curCat)) {
                    $('#entryCategory').val('Online Sales');
                }
            }
        }
        validateTxAmount();
    }

    // Modal Trigger Buttons
    $('#transactionModal').on('show.bs.modal', function(e) {
        const triggerBtn = $(e.relatedTarget);
        if (triggerBtn && triggerBtn.length && triggerBtn.data('mode')) {
            $('#editTxId').val('');
            const mode = triggerBtn.data('mode') || 'INCOME';
            setModalMode(mode);
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('#entryDateTime').val(now.toISOString().slice(0, 16));
            $('#entryAmount').val('');
            $('#entryNotes').val('');
            resetReceiptUpload();
        }
        validateTxAmount();
    });

    // Switcher Inside Modal
    $('#switchIncomeBtn').on('click', function() {
        setModalMode('INCOME');
    });
    $('#switchExpenseBtn').on('click', function() {
        setModalMode('EXPENSE');
    });

    // Quick Amount Chips
    $('.amount-chip').on('click', function(e) {
        e.preventDefault();
        const addVal = parseFloat($(this).data('val')) || 0;
        const $amount = $('#entryAmount');
        const current = parseFloat($amount.val()) || 0;
        $amount.val((current + addVal).toFixed(2)).trigger('input').focus();
    });

    $('#clearAmountChip').on('click', function(e) {
        e.preventDefault();
        $('#entryAmount').val('').trigger('input').focus();
    });

    // Reset Receipt Upload State
    function resetReceiptUpload() {
        $('#receiptFileInput').val('');
        $('#receiptPreviewImg').attr('src', '');
        $('#receiptPreviewCard').addClass('d-none');
        $('#receiptUploadTrigger').removeClass('d-none dragover');
        $('#fileUploadName').text('receipt.webp');
        $('#fileUploadSize').text('Ready to attach');
    }

    // Receipt File Upload Click Handler
    $('#receiptUploadTrigger').on('click', function() {
        $('#receiptFileInput').trigger('click');
    });

    $('#receiptFileInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (!file.type.match('image.*')) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Please select an image file (PNG, JPG, WebP)',
                    showConfirmButton: false,
                    timer: 3000
                });
                resetReceiptUpload();
                return;
            }

            $('#fileUploadName').text(file.name + ' (Will convert to .webp)');
            const sizeKb = (file.size / 1024).toFixed(1);
            $('#fileUploadSize').text(`${sizeKb} KB • Will be converted to WebP on save`);

            const reader = new FileReader();
            reader.onload = function(evt) {
                $('#receiptPreviewImg').attr('src', evt.target.result);
                $('#receiptPreviewCard').removeClass('d-none');
                $('#receiptUploadTrigger').addClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#removeReceiptBtn').on('click', function(e) {
        e.stopPropagation();
        resetReceiptUpload();
    });

    // Drag & Drop Support for Dropzone
    const $dropzone = $('#receiptUploadTrigger');
    $dropzone.on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });
    $dropzone.on('dragleave dragend drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });
    $dropzone.on('drop', function(e) {
        const files = e.originalEvent.dataTransfer.files;
        if (files && files.length > 0) {
            const inputEl = document.getElementById('receiptFileInput');
            inputEl.files = files;
            $('#receiptFileInput').trigger('change');
        }
    });

    // Form Submit (AJAX Create & Update with WebP Image Upload)
    $('#transactionForm').on('submit', function(e) {
        e.preventDefault();
        const amount = parseFloat($('#entryAmount').val());

        if (isNaN(amount) || amount <= 0) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: 'Please enter a valid amount',
                showConfirmButton: false,
                timer: 3000
            });
            $('#entryAmount').focus();
            return;
        }

        if ($('#entryType').val() === 'EXPENSE') {
            const cat = $('#entryCategory').val();
            const isCapWithdrawal = ['Investment Withdrawal', 'Capital Withdrawal', 'Investment / Capital'].includes(cat);
            const limit = isCapWithdrawal ? currentInvestmentBalance : currentAvailableWorkingBalance;
            const limitLabel = isCapWithdrawal ? 'Active Capital Balance' : 'Available Capital / Balance';

            if (amount > limit) {
                Swal.fire({
                    icon: 'error',
                    title: 'Expense Exceeds Capital',
                    html: `Expense amount (৳${amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}) exceeds ${limitLabel} (৳${limit.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}).<br><br><strong>Capital এর চেয়ে বেশি expense করা যাবে না।</strong>`,
                    confirmButtonColor: '#0f172a'
                });
                $('#entryAmount').focus();
                return;
            }
        }

        const editId = $('#editTxId').val();
        const url = editId ? `{{ url('admin/finance/update') }}/${editId}` : `{{ route('admin.finance.store') }}`;

        const formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('entry_type', $('#entryType').val());
        formData.append('amount', $('#entryAmount').val());
        formData.append('category', $('#entryCategory').val());
        formData.append('payment_method', $('#entryPaymentMethod').val());
        formData.append('transaction_date', $('#entryDateTime').val());
        formData.append('staff_name', $('#entryStaff').val());
        formData.append('notes', $('#entryNotes').val() || '');

        const fileInput = document.getElementById('receiptFileInput');
        if (fileInput.files && fileInput.files[0]) {
            formData.append('receipt_image', fileInput.files[0]);
        }

        const $submitBtn = $('#submitModalBtn');
        const originalBtnHtml = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> <span>Saving...</span>');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#transactionModal').modal('hide');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: response.message || 'Transaction recorded successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                setTimeout(function() {
                    window.location.reload();
                }, 700);
            },
            error: function(xhr) {
                $submitBtn.prop('disabled', false).html(originalBtnHtml);
                let errorMsg = 'An error occurred while saving the transaction.';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errs = xhr.responseJSON.errors;
                    errorMsg = Object.values(errs).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Error',
                    html: errorMsg,
                    confirmButtonColor: '#e11d48'
                });
            }
        });
    });

    // Reset when modal is closed
    $('#transactionModal').on('hidden.bs.modal', function() {
        $('#transactionForm')[0].reset();
        $('#editTxId').val('');
        resetReceiptUpload();
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        $('#entryDateTime').val(now.toISOString().slice(0, 16));
        $('#transactionModalTitle').text('Add Finance Entry');
        $('#submitModalBtn').html('<i class="fa-solid fa-check mr-1"></i> <span>Confirm Entry</span>');
    });

    // =========================================================
    // 2. Lightbox Receipt Preview
    // =========================================================
    $(document).on('click', '.receipt-thumb-box', function() {
        const src = $(this).data('receipt-src') || $(this).find('img').attr('src');
        if (src) {
            $('#lightboxImage').attr('src', src);
            $('#receiptPreviewModal').modal('show');
        }
    });

    // =========================================================
    // 3. Live AJAX Filtering & Search (KPIs, Categories & Ledger)
    // =========================================================
    let activeTimeframe = '{{ $timeframe ?? "month" }}';
    let filterAjaxRequest = null;
    let searchDebounceTimer = null;

    function applyFilters() {
        const query = $('#ledgerSearchInput').val().trim();
        const typeFilter = $('#filterType').val();
        const categoryFilter = $('#filterCategory').val();
        const staffFilter = $('#filterStaff').val();
        const startDate = activeTimeframe === 'custom' ? $('#filterStartDate').val() : '';
        const endDate = activeTimeframe === 'custom' ? $('#filterEndDate').val() : '';

        // Validate custom date range
        if (activeTimeframe === 'custom') {
            if (startDate && endDate && startDate > endDate) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Start date cannot be after end date',
                    showConfirmButton: false,
                    timer: 2500
                });
                return;
            }
        }

        // Visual loading cues
        $('#txCountBadge').html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Filtering...');
        $('.kpi-card .kpi-value').css('opacity', '0.45');
        $('#categoryBreakdownContainer').css('opacity', '0.45');

        // Cancel pending request if rapid switching
        if (filterAjaxRequest && filterAjaxRequest.readyState !== 4) {
            filterAjaxRequest.abort();
        }

        filterAjaxRequest = $.ajax({
            url: `{{ route('admin.finance.index') }}`,
            type: 'GET',
            data: {
                timeframe: activeTimeframe,
                start_date: startDate,
                end_date: endDate,
                type: typeFilter,
                category: categoryFilter,
                staff: staffFilter,
                search: query
            },
            success: function(res) {
                $('.kpi-card .kpi-value').css('opacity', '1');
                $('#categoryBreakdownContainer').css('opacity', '1');

                // 1. Update KPI Card 1: Net Balance
                const net = parseFloat(res.metrics.netBalance) || 0;
                const netFormatted = Math.abs(net).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                if (net < 0) {
                    $('#kpiNetBalance').css('color', '#f43f5e').text('-৳' + netFormatted);
                    $('#kpiNetBadge').attr('class', 'badge badge-pill badge-expense mr-1')
                        .html('<i class="fa-solid fa-arrow-trend-down"></i> Net Deficit');
                } else {
                    $('#kpiNetBalance').css('color', '#10b981').text('৳' + netFormatted);
                    $('#kpiNetBadge').attr('class', 'badge badge-pill badge-income mr-1')
                        .html('<i class="fa-solid fa-arrow-trend-up"></i> Net Profit');
                }

                // 2. Update KPI Card 2: Total Revenue
                const rev = parseFloat(res.metrics.totalIncome) || 0;
                $('#kpiTotalRevenue').text('৳' + rev.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                const incCount = parseInt(res.metrics.incomeEntriesCount) || 0;
                $('#kpiRevenueCount').text(`${incCount} ${incCount === 1 ? 'entry' : 'entries'}`);

                // 3. Update KPI Card 3: Total Expenses
                const exp = parseFloat(res.metrics.totalExpense) || 0;
                $('#kpiTotalExpense').text('৳' + exp.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                const expCount = parseInt(res.metrics.expenseEntriesCount) || 0;
                $('#kpiExpenseCount').text(`${expCount} ${expCount === 1 ? 'entry' : 'entries'}`);

                // 4. Update KPI Card 4: Operations / Transactions
                const totalOps = parseInt(res.metrics.totalTransactions) || 0;
                $('#kpiTotalTransactions').html(`${totalOps} <span style="font-size: 1rem; color: var(--f-text-muted); font-weight: 500;">Ops</span>`);

                // Update Investment Hub Card
                if (res.investment) {
                    const invBal = parseFloat(res.investment.balance) || 0;
                    const invTotal = parseFloat(res.investment.totalInvested) || 0;
                    const invWithdrawn = parseFloat(res.investment.totalWithdrawn) || 0;
                    const invCount = parseInt(res.investment.investedCount) || 0;
                    const wCount = parseInt(res.investment.withdrawnCount) || 0;
                    const formattedBal = Math.abs(invBal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    $('#invNetBalanceVal').text(formattedBal);
                    if (invBal < 0) {
                        $('#invNetBalanceVal').css('color', '#f43f5e');
                        $('#invBalanceBadge').attr('class', 'badge badge-pill badge-expense font-12')
                            .html('<i class="fa-solid fa-triangle-exclamation mr-1"></i> Net Deficit');
                    } else if (invBal > 0) {
                        $('#invNetBalanceVal').css('color', '#10b981');
                        $('#invBalanceBadge').attr('class', 'badge badge-pill badge-income font-12')
                            .html('<i class="fa-solid fa-circle-check mr-1"></i> Healthy Capital');
                    } else {
                        $('#invNetBalanceVal').css('color', 'var(--f-text-title)');
                        $('#invBalanceBadge').attr('class', 'badge badge-pill badge-method font-12')
                            .html('<i class="fa-solid fa-circle-minus mr-1"></i> Zero Balance');
                    }

                    $('#invTotalInvestedVal').text('+৳' + invTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    $('#invTotalWithdrawnVal').text('-৳' + invWithdrawn.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    $('#invInvestedCount').text(`(${invCount})`);
                    $('#invWithdrawnCount').text(`(${wCount})`);

                    const retention = invTotal > 0 ? Math.min(100, Math.max(0, Math.round((invBal / invTotal) * 100))) : 0;
                    $('#invRetentionPct').text(`${retention}%`);
                    $('#invRetentionBar').css('width', `${retention}%`);

                    currentInvestmentBalance = Math.max(0, invBal);
                    $('#invAvailableCapVal').text(currentInvestmentBalance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    if ($('#invMode').val() === 'WITHDRAW') {
                        validateInvAmount();
                    }
                }

                if (res.metrics && res.metrics.availableWorkingBalance !== undefined) {
                    currentAvailableWorkingBalance = Math.max(0, parseFloat(res.metrics.availableWorkingBalance) || 0);
                    if ($('#entryType').val() === 'EXPENSE') {
                        validateTxAmount();
                    }
                }

                // 5. Update Operational Breakdown & Category Distribution
                const $breakdown = $('#categoryBreakdownContainer');
                $breakdown.empty();
                if (!res.categoryBreakdown || res.categoryBreakdown.length === 0) {
                    $breakdown.html(`
                        <div class="col-12 text-center py-3">
                            <span class="f-text-muted font-13"><i class="fa-solid fa-chart-pie mr-1"></i> No transactions recorded for the selected filter.</span>
                        </div>
                    `);
                } else {
                    res.categoryBreakdown.forEach(function(cat) {
                        const isInc = cat.entry_type === 'INCOME';
                        const dotColor = isInc ? '#10b981' : '#f43f5e';
                        const barGrad = isInc ? 'linear-gradient(90deg, #059669, #2dd4bf)' : 'linear-gradient(90deg, #e11d48, #f59e0b)';
                        const catTotal = parseFloat(cat.total_amount) || 0;
                        const catCount = parseInt(cat.entries_count) || 0;
                        const pct = cat.percentage || 0;
                        const formattedCatTotal = catTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                        const rowHtml = `
                            <div class="col-lg-4 col-12 mb-3 mb-lg-0">
                                <div class="breakdown-row" onclick="$('#filterCategory').val('${cat.category.replace(/'/g, "\\'")}').trigger('change');" title="Click to filter by ${cat.category}">
                                    <div class="d-flex align-items-center justify-content-between font-12 mb-1">
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2" style="width: 8px; height: 8px; border-radius: 50%; background-color: ${dotColor}; display: inline-block;"></span>
                                            <strong class="f-text-title">${cat.category}</strong>
                                            <span class="badge badge-method ml-2" style="font-size: 10px;">${catCount} ${catCount === 1 ? 'entry' : 'entries'}</span>
                                        </div>
                                        <div class="text-right">
                                            <strong style="color: ${dotColor};">৳${formattedCatTotal}</strong>
                                            <span class="f-text-muted font-11 ml-1">(${pct}%)</span>
                                        </div>
                                    </div>
                                    <div class="progress-track">
                                        <div class="progress-bar-custom" style="width: ${pct}%; background: ${barGrad};"></div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $breakdown.append(rowHtml);
                    });
                }

                // 6. Update Recent Transactions List
                const $list = $('#transactionsList');
                $list.find('.tx-item').remove();

                if (!res.transactions || res.transactions.length === 0) {
                    $('#emptyLedgerNotice').removeClass('d-none');
                    $('#txCountBadge').text('Showing 0 of 0');
                } else {
                    $('#emptyLedgerNotice').addClass('d-none');
                    $('#txCountBadge').text(`Showing ${res.count} of ${res.totalMatching}`);

                    res.transactions.forEach(function(tx) {
                        const isInc = tx.entry_type === 'INCOME';
                        const iconClass = isInc ? 'icon-emerald' : 'icon-rose';
                        const iconFa = isInc ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down';
                        const badgeClass = isInc ? 'badge-income' : 'badge-expense';
                        const amtColor = isInc ? '#10b981' : '#f43f5e';
                        const amtPrefix = isInc ? '+৳' : '-৳';
                        const formattedAmt = parseFloat(tx.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        const receiptHtml = tx.has_receipt ? `
                            <div class="receipt-thumb-box" title="View Full Receipt Photo (.webp)" data-receipt-src="${tx.receipt_url}">
                                <img src="${tx.receipt_url}" alt="Receipt Preview">
                                <div class="receipt-thumb-overlay">
                                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                                </div>
                            </div>
                        ` : '';
                        const notesHtml = tx.notes ? `
                            <p class="f-text-muted font-12 mb-1 text-truncate" style="max-width: 550px;" title="${$('<div>').text(tx.notes).html()}">
                                <i class="fa-regular fa-file-lines mr-1 text-muted"></i>
                                <span>${$('<div>').text(tx.notes).html()}</span>
                            </p>
                        ` : '';

                        const itemHtml = `
                            <div class="tx-item"
                                 data-id="${tx.id}"
                                 data-type="${tx.entry_type}"
                                 data-category="${$('<div>').text(tx.category).html()}"
                                 data-staff="${$('<div>').text(tx.staff_name).html()}"
                                 data-amount="${tx.amount}"
                                 data-method="${$('<div>').text(tx.payment_method).html()}"
                                 data-date="${tx.date_raw}"
                                 data-notes="${$('<div>').text(tx.notes || '').html()}"
                                 data-receipt="${tx.receipt_url || ''}"
                                 data-timestamp="${tx.timestamp}">
                                <div class="row align-items-center">
                                    <div class="col-lg-8 col-12 mb-3 mb-lg-0">
                                        <div class="d-flex align-items-start">
                                            <div class="tx-type-icon ${iconClass} mr-3">
                                                <i class="fa-solid ${iconFa}"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex flex-wrap align-items-center mb-1" style="gap: 8px;">
                                                    <h6 class="font-weight-bold f-text-title mb-0 font-15">${$('<div>').text(tx.category).html()}</h6>
                                                    <span class="tx-badge ${badgeClass}">
                                                        <i class="fa-solid ${isInc ? 'fa-arrow-up' : 'fa-arrow-down'} font-9"></i> ${tx.entry_type.charAt(0).toUpperCase() + tx.entry_type.slice(1).toLowerCase()}
                                                    </span>
                                                    <span class="badge-method">
                                                        <i class="fa-regular fa-credit-card mr-1 text-muted"></i> ${$('<div>').text(tx.payment_method).html()}
                                                    </span>
                                                </div>
                                                ${notesHtml}
                                                <div class="d-flex flex-wrap align-items-center font-11 f-text-muted" style="gap: 14px;">
                                                    <span>
                                                        <i class="fa-regular fa-calendar-days mr-1 text-muted"></i> 
                                                        ${tx.date_formatted}
                                                    </span>
                                                    <span>
                                                        <i class="fa-regular fa-circle-user mr-1 text-success"></i> Added by:
                                                        <strong class="text-success px-1.5 py-0.5 rounded" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.25);">${$('<div>').text(tx.staff_name).html()}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12 text-lg-right text-left">
                                        <div class="d-flex align-items-center justify-content-lg-end justify-content-between" style="gap: 14px;">
                                            ${receiptHtml}
                                            <div class="text-left text-lg-right">
                                                <div class="font-weight-bold font-18" style="color: ${amtColor};">${amtPrefix}${formattedAmt}</div>
                                            </div>
                                            <div class="d-flex align-items-center" style="gap: 4px;">
                                                <button type="button" class="tx-action-btn edit-tx-btn" title="Edit Transaction">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button type="button" class="tx-action-btn delete-btn delete-tx-btn" title="Delete Transaction">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#emptyLedgerNotice').before(itemHtml);
                    });
                }
            },
            error: function(xhr, status) {
                if (status === 'abort') return;
                $('.kpi-card .kpi-value').css('opacity', '1');
                $('#categoryBreakdownContainer').css('opacity', '1');
                $('#txCountBadge').text('Filter update error');
            }
        });
    }

    // Input & Select Listeners
    $('#ledgerSearchInput').on('input', function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(function() {
            applyFilters();
        }, 300);
    });

    $('#filterType, #filterCategory, #filterStaff').on('change', function() {
        applyFilters();
    });

    // Timeframe Pills Click
    $('#timeframePills .pill-btn').on('click', function() {
        const time = $(this).data('time');
        $('#timeframePills .pill-btn').removeClass('active');
        $(this).addClass('active');
        activeTimeframe = time;

        if (time === 'custom') {
            $('#customDateRangeRow').removeClass('d-none');
            // If empty, prefill start of month and today
            if (!$('#filterStartDate').val()) {
                const d = new Date();
                $('#filterStartDate').val(new Date(d.getFullYear(), d.getMonth(), 2).toISOString().slice(0, 10));
            }
            if (!$('#filterEndDate').val()) {
                $('#filterEndDate').val(new Date().toISOString().slice(0, 10));
            }
            applyFilters();
        } else {
            $('#customDateRangeRow').addClass('d-none');
            applyFilters();
        }
    });

    // Custom Date Range Listeners
    $('#filterStartDate, #filterEndDate').on('change', function() {
        if (activeTimeframe === 'custom') {
            applyFilters();
        }
    });

    $('#applyCustomDateBtn').on('click', function() {
        applyFilters();
    });

    // Reset Filters Handler
    function resetAllFilters() {
        $('#ledgerSearchInput').val('');
        $('#filterType').val('ALL');
        $('#filterCategory').val('ALL');
        $('#filterStaff').val('ALL');
        $('#filterStartDate').val('{{ date("Y-m-01") }}');
        $('#filterEndDate').val('{{ date("Y-m-d") }}');
        $('#timeframePills .pill-btn').removeClass('active');
        $('#timeframePills .pill-btn[data-time="month"]').addClass('active');
        activeTimeframe = 'month';
        $('#customDateRangeRow').addClass('d-none');
        applyFilters();

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: 'Filters reset to This Month',
            showConfirmButton: false,
            timer: 1500
        });
    }

    $('#resetFiltersBtn, #emptyResetBtn').on('click', resetAllFilters);

    // =========================================================
    // 4. Quick Action Button Feedback & Handlers
    // =========================================================
    $('#reloadFinanceBtn').on('click', function() {
        const $icon = $(this).find('i');
        $icon.addClass('fa-spin');
        setTimeout(function() {
            window.location.reload();
        }, 400);
    });

    // Export Finance Report
    $('#exportReportBtn').on('click', function() {
        const type = $('#filterType').val();
        const cat = $('#filterCategory').val();
        const staff = $('#filterStaff').val();
        const search = $('#ledgerSearchInput').val().trim();
        const start = activeTimeframe === 'custom' ? $('#filterStartDate').val() : '';
        const end = activeTimeframe === 'custom' ? $('#filterEndDate').val() : '';
        const url = `{{ route('admin.finance.export') }}?timeframe=${encodeURIComponent(activeTimeframe)}&start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}&type=${encodeURIComponent(type)}&category=${encodeURIComponent(cat)}&staff=${encodeURIComponent(staff)}&search=${encodeURIComponent(search)}`;
        window.location.href = url;
    });

    // Delete Transaction Confirmation & AJAX
    $(document).on('click', '.delete-tx-btn', function() {
        const $item = $(this).closest('.tx-item');
        const id = $item.data('id');

        Swal.fire({
            title: 'Delete Transaction?',
            text: `Are you sure you want to permanently remove transaction #FT-${String(id).padStart(5, '0')}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#334155',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/finance/delete') }}/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $item.fadeOut(300, function() {
                            $(this).remove();
                            applyFilters();
                        });
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message || 'Transaction deleted successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 700);
                    },
                    error: function(xhr) {
                        let errMsg = 'Could not delete transaction record. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed',
                            text: errMsg,
                            confirmButtonColor: '#e11d48'
                        });
                    }
                });
            }
        });
    });

    // Edit Transaction Modal Populator
    $(document).on('click', '.edit-tx-btn', function() {
        const $item = $(this).closest('.tx-item');
        const id = $item.data('id');
        const type = $item.data('type') || 'EXPENSE';
        const category = $item.data('category');
        const staff = $item.data('staff');
        const amount = $item.data('amount');
        const method = $item.data('method');
        const date = $item.data('date');
        const notes = $item.data('notes');
        const receipt = $item.data('receipt');

        $('#editTxId').val(id);
        setModalMode(type, true);

        if (category) $('#entryCategory').val(category);
        if (staff) $('#entryStaff').val(staff);
        if (amount) $('#entryAmount').val(amount);
        if (method) $('#entryPaymentMethod').val(method);
        if (date) $('#entryDateTime').val(date);
        if (notes) $('#entryNotes').val(notes);

        if (receipt) {
            $('#receiptPreviewImg').attr('src', receipt);
            $('#fileUploadName').text('Attached Receipt (Saved in .webp)');
            $('#fileUploadSize').text('Existing image preserved');
            $('#receiptPreviewCard').removeClass('d-none');
            $('#receiptUploadTrigger').addClass('d-none');
        } else {
            resetReceiptUpload();
        }

        $('#transactionModalTitle').text(`Edit Transaction (#FT-${String(id).padStart(5, '0')})`);
        $('#submitModalBtn span').text('Update Entry');
        $('#transactionModal').modal('show');
    });

    // =========================================================
    // 5. Dedicated Investment & Capital Modal Logic
    // =========================================================
    function setInvestmentModalMode(mode) {
        const $invMode = $('#invMode');
        const $invEntryType = $('#invEntryType');
        const $invCategory = $('#invCategory');
        const $switchAdd = $('#invSwitchAddBtn');
        const $switchWithdraw = $('#invSwitchWithdrawBtn');
        const $modalTitle = $('#invModalTitle');
        const $headerIcon = $('#invModalHeaderIcon');
        const $heroCard = $('#invHeroAmountCard');
        const $amountLabel = $('#invAmountLabel');
        const $categoryDisplay = $('#invCategoryDisplay');
        const $submitBtn = $('#invSubmitModalBtn');

        if (mode === 'WITHDRAW') {
            $invMode.val('WITHDRAW');
            $invEntryType.val('EXPENSE');
            $invCategory.val('Investment Withdrawal');
            $switchAdd.removeClass('active-income');
            $switchWithdraw.addClass('active-expense');
            $modalTitle.text('Withdraw Investment / Capital');
            $headerIcon.removeClass('icon-emerald').addClass('icon-rose');
            $headerIcon.find('i').removeClass('fa-vault').addClass('fa-money-bill-transfer');
            $heroCard.addClass('expense-mode');
            $amountLabel.html('<i class="fa-solid fa-coins mr-1.5 text-muted"></i> Withdrawal Amount <span class="text-danger ml-0.5">*</span>');
            $categoryDisplay.val('Investment Withdrawal (Outflow)');
            $categoryDisplay.prev('i').removeClass('text-success').addClass('text-danger');
            $submitBtn.removeClass('f-btn-income').addClass('f-btn-expense');
            $submitBtn.html('<i class="fa-solid fa-arrow-up-from-bracket mr-1"></i> <span>Confirm Capital Withdrawal</span>');
            $('#invWithdrawAllBtn').removeClass('d-none').text(`Withdraw All (৳${currentInvestmentBalance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })})`);
        } else {
            $invMode.val('ADD');
            $invEntryType.val('INCOME');
            $invCategory.val('Investment / Capital');
            $switchWithdraw.removeClass('active-expense');
            $switchAdd.addClass('active-income');
            $modalTitle.text('Add Capital Investment');
            $headerIcon.removeClass('icon-rose').addClass('icon-emerald');
            $headerIcon.find('i').removeClass('fa-money-bill-transfer').addClass('fa-vault');
            $heroCard.removeClass('expense-mode');
            $amountLabel.html('<i class="fa-solid fa-coins mr-1.5 text-muted"></i> Investment Amount <span class="text-danger ml-0.5">*</span>');
            $categoryDisplay.val('Investment / Capital (Inflow)');
            $categoryDisplay.prev('i').removeClass('text-danger').addClass('text-success');
            $submitBtn.removeClass('f-btn-expense').addClass('f-btn-income');
            $submitBtn.html('<i class="fa-solid fa-vault mr-1"></i> <span>Confirm Capital Deposit</span>');
            $('#invWithdrawAllBtn').addClass('d-none');
        }
        validateInvAmount();
    }

    // Investment Modal Trigger
    $('#investmentModal').on('show.bs.modal', function(e) {
        const triggerBtn = $(e.relatedTarget);
        const mode = (triggerBtn && triggerBtn.data('mode')) ? triggerBtn.data('mode') : 'ADD';
        setInvestmentModalMode(mode);
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        $('#invEntryDateTime').val(now.toISOString().slice(0, 16));
        $('#invEntryAmount').val('');
        $('#invEntryNotes').val('');
        resetInvReceiptUpload();

        // Initialize investor select
        const $invSelect = $('#invEntryStaffSelect');
        const customInvestor = (triggerBtn && triggerBtn.data('investor')) ? triggerBtn.data('investor') : null;
        if (customInvestor) {
            $invSelect.val(customInvestor).trigger('change');
        } else {
            const firstAvailable = $invSelect.find('option:not([disabled]):not([value="__NEW__"]):first').val();
            if (firstAvailable && !$invSelect.val()) {
                $invSelect.val(firstAvailable).trigger('change');
            } else if ($invSelect.val()) {
                $invSelect.trigger('change');
            }
        }

        validateInvAmount();
    });

    // Investor Selection in Investment Modal
    $('#invEntryStaffSelect').on('change', function() {
        const val = $(this).val();
        if (val === '__NEW__') {
            $('#newInvestorFieldWrap').removeClass('d-none');
            $('#invEntryStaffCustom').focus();
            $('#invEntryStaff').val($('#invEntryStaffCustom').val().trim());
            $('#invSelectedPartnerNotice').addClass('d-none');
        } else {
            $('#newInvestorFieldWrap').addClass('d-none');
            $('#invEntryStaff').val(val || '');
            
            const selectedOpt = $(this).find('option:selected');
            const bal = selectedOpt.data('balance');
            if (bal !== undefined && val) {
                $('#invPartnerSpecificBalanceVal').text('৳' + parseFloat(bal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#invSelectedPartnerNotice').removeClass('d-none');
            } else {
                $('#invSelectedPartnerNotice').addClass('d-none');
            }
        }
        validateInvAmount();
    });

    $('#invEntryStaffCustom').on('input keyup', function() {
        if ($('#invEntryStaffSelect').val() === '__NEW__') {
            $('#invEntryStaff').val($(this).val().trim());
        }
        validateInvAmount();
    });

    // Switcher inside Investment Modal
    $('#invSwitchAddBtn').on('click', function() {
        setInvestmentModalMode('ADD');
    });
    $('#invSwitchWithdrawBtn').on('click', function() {
        setInvestmentModalMode('WITHDRAW');
    });

    // Guard on Open Withdraw Capital Button in card
    $('#btnOpenWithdrawInvestment').on('click', function(e) {
        if (currentInvestmentBalance <= 0) {
            e.preventDefault();
            e.stopPropagation();
            Swal.fire({
                icon: 'warning',
                title: 'No Capital Available',
                text: 'Active capital balance is ৳0.00. You cannot withdraw capital until investment funds are deposited.',
                confirmButtonColor: '#0f172a'
            });
            return false;
        }
    });

    // Withdraw All Button Handler
    $('#invWithdrawAllBtn').on('click', function(e) {
        e.preventDefault();
        $('#invEntryAmount').val(currentInvestmentBalance.toFixed(2)).trigger('input').focus();
    });

    // Quick Amount Chips for Investment
    $('.inv-amount-chip').on('click', function(e) {
        e.preventDefault();
        const chipVal = parseFloat($(this).data('val')) || 0;
        const currentVal = parseFloat($('#invEntryAmount').val()) || 0;
        $('#invEntryAmount').val((currentVal + chipVal).toFixed(2)).trigger('input');
    });
    $('#invClearAmountChip').on('click', function(e) {
        e.preventDefault();
        $('#invEntryAmount').val('').trigger('input');
    });

    // Investment Dropzone & Receipt Upload
    function resetInvReceiptUpload() {
        const $fileInput = $('#invReceiptFileInput');
        $fileInput.val('');
        $('#invReceiptPreviewImg').attr('src', '');
        $('#invReceiptPreviewCard').addClass('d-none');
        $('#invReceiptUploadTrigger').removeClass('d-none');
    }

    $('#invReceiptUploadTrigger').on('click', function() {
        $('#invReceiptFileInput').trigger('click');
    });

    $('#invReceiptFileInput').on('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'File exceeds 5MB limit',
                    showConfirmButton: false,
                    timer: 3000
                });
                resetInvReceiptUpload();
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#invReceiptPreviewImg').attr('src', e.target.result);
                $('#invFileUploadName').text(file.name);
                $('#invFileUploadSize').text((file.size / 1024).toFixed(1) + ' KB (Will auto-convert to WebP)');
                $('#invReceiptPreviewCard').removeClass('d-none');
                $('#invReceiptUploadTrigger').addClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#invRemoveReceiptBtn').on('click', function(e) {
        e.stopPropagation();
        resetInvReceiptUpload();
    });

    // Investment Dropzone Drag & Drop
    const $invDropzone = $('#invReceiptUploadTrigger');
    $invDropzone.on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });
    $invDropzone.on('dragleave dragend drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });
    $invDropzone.on('drop', function(e) {
        const files = e.originalEvent.dataTransfer.files;
        if (files && files.length > 0) {
            const inputEl = document.getElementById('invReceiptFileInput');
            inputEl.files = files;
            $('#invReceiptFileInput').trigger('change');
        }
    });

    // Submit Investment Form (AJAX Store)
    $('#investmentForm').on('submit', function(e) {
        e.preventDefault();
        const amount = parseFloat($('#invEntryAmount').val());

        if (isNaN(amount) || amount <= 0) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: 'Please enter a valid amount',
                showConfirmButton: false,
                timer: 3000
            });
            $('#invEntryAmount').focus();
            return;
        }

        if ($('#invMode').val() === 'WITHDRAW') {
            if (currentInvestmentBalance <= 0 || amount > currentInvestmentBalance) {
                Swal.fire({
                    icon: 'error',
                    title: 'Withdrawal Exceeds Capital',
                    html: `Withdrawal amount (৳${amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}) exceeds Active Capital Balance (৳${currentInvestmentBalance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}).<br><br><strong>Capital এর চেয়ে বেশি উত্তোলন করা যাবে না।</strong>`,
                    confirmButtonColor: '#0f172a'
                });
                $('#invEntryAmount').focus();
                return;
            }
        }

        let staff = ($('#invEntryStaff').val() || '').trim();
        if (!staff) {
            const selectVal = $('#invEntryStaffSelect').val();
            if (selectVal === '__NEW__') {
                staff = ($('#invEntryStaffCustom').val() || '').trim();
            } else if (selectVal) {
                staff = selectVal.trim();
            }
        }

        if (!staff) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: 'Please select or enter an investor or partner name',
                showConfirmButton: false,
                timer: 3000
            });
            $('#invEntryStaffSelect').focus();
            return;
        }

        const formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('entry_type', $('#invEntryType').val());
        formData.append('amount', $('#invEntryAmount').val());
        formData.append('category', $('#invCategory').val());
        formData.append('payment_method', $('#invEntryPaymentMethod').val());
        formData.append('transaction_date', $('#invEntryDateTime').val());
        formData.append('staff_name', staff);
        formData.append('notes', $('#invEntryNotes').val() || '');

        const fileInput = document.getElementById('invReceiptFileInput');
        if (fileInput.files && fileInput.files[0]) {
            formData.append('receipt_image', fileInput.files[0]);
        }

        const $submitBtn = $('#invSubmitModalBtn');
        const originalBtnHtml = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> <span>Recording...</span>');

        $.ajax({
            url: `{{ route('admin.finance.store') }}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#investmentModal').modal('hide');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: response.message || 'Investment entry recorded successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                setTimeout(function() {
                    window.location.reload();
                }, 700);
            },
            error: function(xhr) {
                $submitBtn.prop('disabled', false).html(originalBtnHtml);
                let errorMsg = 'An error occurred while recording the investment entry.';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errs = xhr.responseJSON.errors;
                    errorMsg = Object.values(errs).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Error',
                    html: errorMsg,
                    confirmButtonColor: '#e11d48'
                });
            }
        });
    });

    // Reset Investment Modal on close
    $('#investmentModal').on('hidden.bs.modal', function() {
        $('#investmentForm')[0].reset();
        resetInvReceiptUpload();
        setInvestmentModalMode('ADD');
    });

    // Quick Filter Button: View Investment Records in Ledger
    $('#btnFilterInvestmentRecords').on('click', function(e) {
        e.preventDefault();
        $('#filterCategory').val('ALL_INVESTMENTS').trigger('change');
        if ($('#transactionsList').length) {
            $('html, body').animate({
                scrollTop: $('#transactionsList').offset().top - 140
            }, 400);
        }
    });
});
</script>
@endsection
