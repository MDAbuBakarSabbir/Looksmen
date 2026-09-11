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
</style>
@endsection

@section('content')
<div class="finance-wrapper py-2">

    {{-- Top Action & Header Bar --}}
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
                    <div class="kpi-value" style="{{ $netBalance < 0 ? 'color: #f43f5e;' : ($netBalance > 0 ? 'color: #10b981;' : '') }}">
                        {{ $netBalance < 0 ? '-৳' . number_format(abs($netBalance), 2) : '৳' . number_format($netBalance, 2) }}
                    </div>
                </div>
                <div class="kpi-sub">
                    <span class="badge badge-pill {{ $netBalance >= 0 ? 'badge-income' : 'badge-expense' }} mr-1" style="font-size: 11px;">
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
                    <div class="kpi-value" style="color: #10b981;">৳{{ number_format($totalIncome, 2) }}</div>
                </div>
                <div class="kpi-sub">
                    <span class="badge badge-pill badge-income mr-1" style="font-size: 11px;">
                        <i class="fa-regular fa-circle-check"></i> {{ $incomeEntriesCount }} {{ Str::plural('entry', $incomeEntriesCount) }}
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
                    <div class="kpi-value" style="color: #f43f5e;">৳{{ number_format($totalExpense, 2) }}</div>
                </div>
                <div class="kpi-sub">
                    <span class="badge badge-pill badge-expense mr-1" style="font-size: 11px;">
                        <i class="fa-solid fa-receipt"></i> {{ $expenseEntriesCount }} {{ Str::plural('entry', $expenseEntriesCount) }}
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
                    <div class="kpi-value">{{ $totalTransactions }} <span style="font-size: 1rem; color: var(--f-text-muted); font-weight: 500;">Ops</span></div>
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

                <div class="row">
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

    {{-- Advanced Filtering & Search Bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-panel">
                {{-- First Row: Time Range Pills & Search --}}
                <div class="row align-items-center mb-3">
                    <div class="col-lg-8 col-12 mb-3 mb-lg-0">
                        <div class="pill-group" id="timeframePills">
                            <button type="button" class="pill-btn" data-time="today">Today</button>
                            <button type="button" class="pill-btn" data-time="week">This Week</button>
                            <button type="button" class="pill-btn" data-time="month">This Month</button>
                            <button type="button" class="pill-btn" data-time="year">This Year</button>
                            <button type="button" class="pill-btn" data-time="custom">Custom Range</button>
                            <button type="button" class="pill-btn active" data-time="all">All Time</button>
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
                            <option value="Online Sales">Online Sales</option>
                            <option value="Shop Sales">Shop Sales</option>
                            <option value="Wholesale / Bulk Order">Wholesale / Bulk Order</option>
                            <option value="Exchange / Refund">Exchange / Refund</option>
                            <option value="Investment / Capital">Investment / Capital</option>
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
                        <small class="f-text-muted font-11">FreshEcom Business Tracking System</small>
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
                            <span class="badge badge-method font-11 font-weight-bold">
                                BDT ৳ Currency
                            </span>
                        </div>
                        <div class="hero-amount-input-box">
                            <div class="hero-currency-symbol" id="heroCurrencySymbol">৳</div>
                            <input type="number" step="0.01" min="0.01" required placeholder="0.00" id="entryAmount" class="hero-amount-input" autocomplete="off">
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
                                        <option value="Exchange / Refund">Exchange / Refund</option>
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
                                <input type="text" required list="staffSuggestions" id="entryStaff" class="f-modal-input" value="{{ auth('admin')->user()->name ?? 'Looksmen' }}" placeholder="e.g. Owner, Sabbir, Raju">
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
                const expenseCats = ['Product Sourcing', 'Courier / Delivery', 'Dollar / Ads & Marketing', 'Packaging Material', 'Shop Rent & Maintenance', 'Staff Salary & Bonus', 'Utilities & Bills', 'Office Supplies & Snacks'];
                if (expenseCats.includes(curCat)) {
                    $('#entryCategory').val('Online Sales');
                }
            }
        }
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
    // 3. Live Client-Side Filtering & Search
    // =========================================================
    let activeTimeframe = 'all';

    function applyFilters() {
        const query = $('#ledgerSearchInput').val().toLowerCase().trim();
        const typeFilter = $('#filterType').val();
        const categoryFilter = $('#filterCategory').val();
        const staffFilter = $('#filterStaff').val();

        // Date calculations for timeframe filter
        const now = new Date();
        const startOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime() / 1000;
        const startOfWeek = new Date(now.setDate(now.getDate() - now.getDay())).setHours(0, 0, 0, 0) / 1000;
        const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1).getTime() / 1000;
        const startOfYear = new Date(now.getFullYear(), 0, 1).getTime() / 1000;

        let visibleCount = 0;
        const $items = $('#transactionsList .tx-item');

        $items.each(function() {
            const $item = $(this);
            const textContent = $item.text().toLowerCase();
            const itemType = $item.data('type') || '';
            const itemCategory = $item.data('category') || '';
            const itemStaff = $item.data('staff') || '';
            const itemTimestamp = parseInt($item.data('timestamp')) || 0;

            const matchesQuery = query === '' || textContent.indexOf(query) !== -1;
            const matchesType = typeFilter === 'ALL' || itemType === typeFilter;
            const matchesCategory = categoryFilter === 'ALL' || itemCategory === categoryFilter;
            const matchesStaff = staffFilter === 'ALL' || itemStaff === staffFilter;

            let matchesTimeframe = true;
            if (activeTimeframe === 'today') {
                matchesTimeframe = itemTimestamp >= startOfToday;
            } else if (activeTimeframe === 'week') {
                matchesTimeframe = itemTimestamp >= startOfWeek;
            } else if (activeTimeframe === 'month') {
                matchesTimeframe = itemTimestamp >= startOfMonth;
            } else if (activeTimeframe === 'year') {
                matchesTimeframe = itemTimestamp >= startOfYear;
            }

            if (matchesQuery && matchesType && matchesCategory && matchesStaff && matchesTimeframe) {
                $item.removeClass('d-none');
                visibleCount++;
            } else {
                $item.addClass('d-none');
            }
        });

        $('#txCountBadge').text(`Showing ${visibleCount} of ${$items.length}`);

        if (visibleCount === 0) {
            $('#emptyLedgerNotice').removeClass('d-none');
        } else {
            $('#emptyLedgerNotice').addClass('d-none');
        }
    }

    $('#ledgerSearchInput').on('input', applyFilters);
    $('#filterType, #filterCategory, #filterStaff').on('change', applyFilters);

    // Timeframe Pills Click
    $('#timeframePills .pill-btn').on('click', function() {
        $('#timeframePills .pill-btn').removeClass('active');
        $(this).addClass('active');
        activeTimeframe = $(this).data('time') || 'all';
        applyFilters();

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: `Filtered by: ${$(this).text()}`,
            showConfirmButton: false,
            timer: 1200
        });
    });

    // Reset Filters Handler
    function resetAllFilters() {
        $('#ledgerSearchInput').val('');
        $('#filterType').val('ALL');
        $('#filterCategory').val('ALL');
        $('#filterStaff').val('ALL');
        $('#timeframePills .pill-btn').removeClass('active');
        $('#timeframePills .pill-btn[data-time="all"]').addClass('active');
        activeTimeframe = 'all';
        applyFilters();

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: 'Filters have been reset',
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
        const url = `{{ route('admin.finance.export') }}?type=${encodeURIComponent(type)}&category=${encodeURIComponent(cat)}&staff=${encodeURIComponent(staff)}`;
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
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed',
                            text: 'Could not delete transaction record. Please try again.',
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
});
</script>
@endsection
