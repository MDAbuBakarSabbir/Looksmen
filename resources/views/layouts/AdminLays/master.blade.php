@php
    // Safe dynamic checks for missing models to prevent crashes
    $webConfig = [];
    $featuresConfig = [];
    $allorder = collect([]);
    $orders = collect([]);
    $orderpopup = null;
    $currentBalance = 0;
    $balanceStatus = 'error';
    $webinfo = (object)['web_name' => config('app.name', 'FinalEcom'), 'web_favicon' => 'favicon.png'];

    if (class_exists('App\Models\GeneralWebSettings')) {
        try {
            $firstSettings = \App\Models\GeneralWebSettings::first();
            if ($firstSettings) {
                $webinfo = $firstSettings;
                $webConfig = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
            }
        } catch (\Exception $e) {}
    }

    if (empty($webConfig)) {
        $webConfig = [
            'web_name' => config('app.name', 'FinalEcom'),
            'web_favicon' => 'favicon.png'
        ];
    }

    if (class_exists('App\Models\FeatureActivation')) {
        try {
            $featuresConfig = \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
        } catch (\Exception $e) {}
    }

    if (empty($featuresConfig)) {
        $featuresConfig = [
            'courier_api' => '0',
            'coupon' => '0',
            'affiliate' => '0',
            'email_verification' => '0',
            'sms_verification' => '0',
            'facebook_api' => '0',
            'fraud_check_api' => '0',
            'payment_api' => '0'
        ];
    }

    if (class_exists('App\Models\Orders')) {
        try {
            $allorder = \App\Models\Orders::where('delivery_status', 'pending');
            $orders = $allorder->paginate(5);

            if (auth()->check()) {
                $orderpopup = \App\Models\Orders::where('courier_updated_by', auth()->id())
                    ->where('delivery_status', 'in_courier')
                    ->where('courier_popup_shown', 0)
                    ->latest()
                    ->first();
            }
        } catch (\Exception $e) {}
    }

    $currentBalance = null;
    $balanceStatus = 'none';
    if (isset($featuresConfig['courier_api']) && $featuresConfig['courier_api'] == '1') {
        $balanceData = \Illuminate\Support\Facades\Cache::get('steadfast_balance');
        if ($balanceData) {
            $currentBalance = $balanceData['balance'];
            $balanceStatus = $balanceData['status'];
        }
    }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $webConfig['web_name'] }} | @yield('title') </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('Dashboard/images/favicon.png') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{ asset('Dashboard/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">

    <!-- Select2 & Summernote CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('Dashboard/css/style.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    </script>

    <audio id="orderAlertSound" preload="auto">
        <source src="{{ asset('Dashboard/images/notification.mp3') }}" type="audio/mpeg">
    </audio>

    <style>
        .suggestion-item {
            display: block;
            padding: 10px 15px;
            color: #333333;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .suggestion-item:last-child {
            border-bottom: none;
        }
        .suggestion-item:hover {
            background-color: #f8f9fa;
            color: #007bff;
            text-decoration: none;
        }
        .suggestion-item-empty {
            padding: 10px 15px;
            color: #6c757d;
            text-align: center;
        }

        .modal {
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            width: 400px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-30px);
            transition: transform 0.3s ease;
        }

        .modal.show .modal-content {
            transform: translateY(0);
        }

        .modal-content input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .submit-btn:hover {
            background: #15803d;
        }

        .cancel-btn {
            background: #dc2626;
            color: white;
            margin-left: 10px;
        }

        .cancel-btn:hover {
            background: #b91c1c;
        }

        /* Premium Clear Cache Button */
        .clear-cache-premium-btn {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white !important;
            border: none;
            border-radius: 30px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }

        .clear-cache-premium-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        }

        .clear-cache-premium-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .clear-cache-premium-btn i {
            font-size: 14px;
            transition: transform 0.5s ease;
        }

        .clear-cache-premium-btn:hover i {
            transform: rotate(180deg);
        }

        /* Premium Search Bar Styling */
        .search-form-premium {
            position: relative;
            display: flex;
            align-items: center;
            background: rgba(243, 244, 246, 0.85);
            border: 1px solid rgba(229, 231, 235, 0.5);
            border-radius: 30px;
            padding: 4px 14px;
            width: 240px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-form-premium:focus-within {
            background: #fff;
            width: 300px;
            border-color: #6366f1;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.15);
        }

        .search-icon-premium {
            color: #6b7280;
            font-size: 14px;
            margin-right: 8px;
            transition: color 0.3s ease;
        }

        .search-form-premium:focus-within .search-icon-premium {
            color: #6366f1;
        }

        .search-input-premium {
            border: none !important;
            background: transparent !important;
            outline: none !important;
            width: 100%;
            font-size: 13px;
            color: #1f2937;
            padding: 4px 0;
            font-weight: 500;
        }

        .search-input-premium::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        /* Premium Balance Box Styling */
        .balance-box {
            position: relative;
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 30px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.06) 0%, rgba(79, 70, 229, 0.12) 100%);
            border: 1px solid rgba(99, 102, 241, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.05);
        }

        .balance-box:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(79, 70, 229, 0.18) 100%);
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
        }

        .balance-box:active {
            transform: translateY(0);
        }

        .balance-icon {
            color: #6366f1;
            font-size: 14px;
            transition: transform 0.4s ease;
        }

        .balance-box:hover .balance-icon {
            transform: scale(1.1);
        }

        .balance-label {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
        }

        .balance-content {
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            height: 20px;
        }

        /* Initial state: Show "Tap to check" */
        .balance-click-to-show {
            font-size: 12px;
            font-weight: 500;
            color: #6366f1;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            opacity: 1;
            transform: translateX(0);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Target balance text */
        .balance-amount {
            font-size: 13px;
            font-weight: 700;
            color: #10b981;
            white-space: nowrap;
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute;
            left: 0;
        }

        /* Spinner */
        .balance-spinner {
            font-size: 12px;
            color: #6366f1;
            display: none;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Loading state styling */
        .balance-box.loading .balance-click-to-show {
            opacity: 0;
            transform: translateX(-30px);
        }

        .balance-box.loading .balance-spinner {
            display: inline-block;
        }

        /* Revealed state styling */
        .balance-box.revealed {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.06) 0%, rgba(5, 150, 105, 0.12) 100%);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .balance-box.revealed .balance-icon {
            color: #10b981;
        }

        .balance-box.revealed .balance-amount {
            opacity: 1;
            transform: translateX(0);
            position: relative;
        }

        .balance-box.revealed .balance-click-to-show {
            display: none;
        }

        .balance-box.revealed .balance-spinner {
            display: none;
        }

        /* Global Premium Toggle Switch Styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #4f46e5;
        }

        input:checked+.slider:before {
            transform: translateX(18px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        /* ===== DARK MODE GLOBAL STYLES ===== */
        body.dark-mode {
            background-color: #0f172a;
            color: #f8fafc;
        }
        body.dark-mode #main-wrapper, body.dark-mode .content-body {
            background-color: #0f172a;
        }
        body.dark-mode .header,
        body.dark-mode .nav-header,
        body.dark-mode .quixnav {
            background-color: #1e293b;
            border-color: #334155;
        }
        body.dark-mode .quixnav .metismenu>li>a {
            color: #cbd5e1;
        }
        body.dark-mode .quixnav .metismenu>li>a:hover,
        body.dark-mode .quixnav .metismenu>li>a:focus,
        body.dark-mode .quixnav .metismenu>li.mm-active>a {
            background-color: rgba(99, 102, 241, 0.15);
            color: #818cf8;
        }
        body.dark-mode .quixnav .metismenu>li>a i {
            color: #94a3b8;
        }
        body.dark-mode .card,
        body.dark-mode .modal-content,
        body.dark-mode .dropdown-menu,
        body.dark-mode .config-card,
        body.dark-mode .payment-select-card,
        body.dark-mode .courier-select-card,
        body.dark-mode .coupon-card,
        body.dark-mode .stat-card,
        body.dark-mode .custom-card,
        body.dark-mode .white-box,
        body.dark-mode .bg-white {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }
        body.dark-mode .card-header,
        body.dark-mode .card-footer,
        body.dark-mode .config-card-header,
        body.dark-mode .courier-logo-badge,
        body.dark-mode .payment-logo-badge {
            background-color: #1e293b !important;
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        body.dark-mode .payment-select-name,
        body.dark-mode .courier-select-name,
        body.dark-mode .field-label,
        body.dark-mode .config-card-header h4,
        body.dark-mode .coupon-card h4 {
            color: #f8fafc !important;
        }
        body.dark-mode h1, body.dark-mode h2, body.dark-mode h3,
        body.dark-mode h4, body.dark-mode h5, body.dark-mode h6,
        body.dark-mode .text-dark {
            color: #f8fafc !important;
        }
        body.dark-mode .text-muted {
            color: #94a3b8 !important;
        }
        body.dark-mode .table {
            color: #cbd5e1;
        }
        body.dark-mode .table thead th {
            background-color: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }
        body.dark-mode .table td,
        body.dark-mode .table th {
            border-color: #334155;
        }
        body.dark-mode .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        body.dark-mode .form-control {
            background-color: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }
        body.dark-mode .form-control:focus {
            background-color: #0f172a;
            border-color: #6366f1;
            color: #f8fafc;
        }
        body.dark-mode .modern-input,
        body.dark-mode .field-input {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        body.dark-mode .modern-input:focus,
        body.dark-mode .field-input:focus {
            background-color: #1e293b !important;
            border-color: #6366f1 !important;
        }
        body.dark-mode .dropdown-item {
            color: #cbd5e1;
        }
        body.dark-mode .dropdown-item:hover {
            background-color: rgba(99, 102, 241, 0.15);
            color: #f8fafc;
        }
        body.dark-mode .search-form-premium {
            background: rgba(15, 23, 42, 0.85);
            border-color: #334155;
        }
        body.dark-mode .search-input-premium {
            color: #f8fafc;
        }
        body.dark-mode .search-suggestions-dropdown {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        body.dark-mode .balance-box {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(79, 70, 229, 0.25) 100%);
            border-color: rgba(99, 102, 241, 0.4);
        }
        body.dark-mode .balance-label {
            color: #cbd5e1;
        }
        body.dark-mode .header-right .dropdown-menu {
            background-color: #1e293b;
        }

        /* ===== MODERN USER PROFILE DROPDOWN ===== */
        .header-profile {
            display: flex;
            align-items: center;
        }

        .profile-trigger {
            display: flex !important;
            align-items: center;
            gap: 10px;
            padding: 6px 14px !important;
            border-radius: 30px;
            background: rgba(99, 102, 241, 0.05);
            border: 1px solid rgba(99, 102, 241, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none !important;
            height: 42px;
        }

        .profile-trigger:hover,
        .profile-trigger[aria-expanded="true"] {
            background: rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.35);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
        }

        .profile-avatar-nav {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
        }

        .profile-name-nav {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
            max-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: color 0.3s ease;
        }

        .profile-trigger:hover .profile-name-nav,
        .profile-trigger[aria-expanded="true"] .profile-name-nav {
            color: #4f46e5;
        }

        .nav-chevron {
            font-size: 10px;
            color: #9ca3af;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .profile-trigger[aria-expanded="true"] .nav-chevron {
            transform: rotate(180deg);
            color: #4f46e5;
        }

        /* Dropdown Menu Container */
        .header-profile .dropdown-menu {
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 16px !important;
            padding: 0 !important;
            min-width: 240px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
            margin-top: 10px !important;
            transform-origin: top right;
            opacity: 0;
            visibility: hidden;
            display: block !important;
            transform: translateY(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .header-profile .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Profile Header inside dropdown */
        .profile-dropdown-header {
            padding: 18px 20px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.03) 0%, rgba(79, 70, 229, 0.06) 100%);
            border-bottom: 1px solid rgba(229, 231, 235, 0.7);
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-dropdown-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            font-weight: 700;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
        }

        .profile-dropdown-meta {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            text-align: left;
        }

        .profile-dropdown-name {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-dropdown-role {
            font-size: 11px;
            font-weight: 600;
            color: #6366f1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Dropdown Body Items */
        .profile-dropdown-body {
            padding: 8px;
        }

        .profile-dropdown-item {
            display: flex !important;
            align-items: center;
            gap: 12px;
            padding: 10px 16px !important;
            color: #4b5563 !important;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            text-decoration: none !important;
        }

        .profile-dropdown-item i {
            font-size: 15px;
            color: #9ca3af;
            transition: color 0.2s ease, transform 0.2s ease;
            width: 20px;
            text-align: center;
        }

        .profile-dropdown-item:hover {
            background-color: rgba(99, 102, 241, 0.06) !important;
            color: #4f46e5 !important;
        }

        .profile-dropdown-item:hover i {
            color: #4f46e5;
            transform: translateX(2px);
        }

        /* Logout Item */
        .profile-dropdown-item.logout-item:hover {
            background-color: rgba(239, 68, 68, 0.08) !important;
            color: #ef4444 !important;
        }

        .profile-dropdown-item.logout-item:hover i {
            color: #ef4444;
        }

        /* ===== DARK MODE OVERRIDES FOR PROFILE DROPDOWN ===== */
        body.dark-mode .profile-trigger {
            background: rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.3);
        }

        body.dark-mode .profile-trigger:hover,
        body.dark-mode .profile-trigger[aria-expanded="true"] {
            background: rgba(99, 102, 241, 0.18);
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .profile-name-nav {
            color: #cbd5e1;
        }

        body.dark-mode .profile-trigger:hover .profile-name-nav,
        body.dark-mode .profile-trigger[aria-expanded="true"] .profile-name-nav {
            color: #818cf8;
        }

        body.dark-mode .profile-dropdown-header {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.06) 0%, rgba(79, 70, 229, 0.12) 100%);
            border-color: #334155;
        }

        body.dark-mode .profile-dropdown-name {
            color: #f8fafc;
        }

        body.dark-mode .profile-dropdown-role {
            color: #818cf8;
        }

        body.dark-mode .profile-dropdown-item {
            color: #cbd5e1 !important;
        }

        body.dark-mode .profile-dropdown-item i {
            color: #64748b;
        }

        body.dark-mode .profile-dropdown-item:hover {
            background-color: rgba(99, 102, 241, 0.15) !important;
            color: #818cf8 !important;
        }

        body.dark-mode .profile-dropdown-item:hover i {
            color: #818cf8;
        }

        body.dark-mode .profile-dropdown-item.logout-item:hover {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #f87171 !important;
        }

        body.dark-mode .profile-dropdown-item.logout-item:hover i {
            color: #f87171;
        }

        /* ===== MODERN REDESIGNED PRELOADER ===== */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            z-index: 999999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        body.dark-mode #preloader,
        html.dark-mode #preloader {
            background-color: #0f172a;
        }

        /* Modern Preloader Container */
        .modern-loader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        /* Double Ring Spinner */
        .modern-spinner {
            position: relative;
            width: 70px;
            height: 70px;
        }

        .spinner-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid transparent;
            box-sizing: border-box;
        }

        .spinner-ring-outer {
            border-top-color: #6366f1;
            border-bottom-color: #6366f1;
            animation: spin-clockwise 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            filter: drop-shadow(0 0 6px rgba(99, 102, 241, 0.4));
        }

        .spinner-ring-inner {
            width: 80%;
            height: 80%;
            top: 10%;
            left: 10%;
            border-left-color: #10b981;
            border-right-color: #10b981;
            animation: spin-counter-clockwise 1s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            filter: drop-shadow(0 0 4px rgba(16, 185, 129, 0.3));
        }

        /* Pulsing Logo or Icon in center */
        .spinner-core {
            position: absolute;
            width: 40%;
            height: 40%;
            top: 30%;
            left: 30%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse-core 1.5s ease-in-out infinite;
        }

        .spinner-core i {
            font-size: 20px;
            color: #6366f1;
        }

        body.dark-mode .spinner-core i,
        html.dark-mode .spinner-core i {
            color: #818cf8;
        }

        /* Loading Text */
        .loader-text {
            font-family: 'Outfit', 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #4b5563;
            letter-spacing: 2px;
            text-transform: uppercase;
            animation: text-pulse 1.2s ease-in-out infinite alternate;
        }

        body.dark-mode .loader-text,
        html.dark-mode .loader-text {
            color: #94a3b8;
        }

        /* Keyframes */
        @keyframes spin-clockwise {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes spin-counter-clockwise {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-360deg); }
        }

        @keyframes pulse-core {
            0%, 100% { transform: scale(0.85); opacity: 0.7; }
            50% { transform: scale(1.1); opacity: 1; }
        }

        @keyframes text-pulse {
            0% { opacity: 0.4; }
            100% { opacity: 1; }
        }
    </style>
    <script>
        // Apply dark mode immediately to html and body elements to prevent white flash
        (function() {
            if (localStorage.getItem('admin_dark_mode') === 'true') {
                document.documentElement.classList.add('dark-mode');
                var observer = new MutationObserver(function(mutations) {
                    if (document.body) {
                        document.body.classList.add('dark-mode');
                        observer.disconnect();
                    }
                });
                observer.observe(document.documentElement, { childList: true });
            }
        })();
    </script>
</head>

<body>

    <!-- Preloader -->
    <div id="preloader">
        <div class="modern-loader-container">
            <div class="modern-spinner">
                <div class="spinner-ring spinner-ring-outer"></div>
                <div class="spinner-ring spinner-ring-inner"></div>
                <div class="spinner-core">
                    <i class="fa-solid fa-store"></i>
                </div>
            </div>
            <div class="loader-text">Loading {{ $webinfo->web_name }} System</div>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div id="main-wrapper">

        <!-- Nav Header -->
        <div class="nav-header">
            <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                <img class="logo-abbr" src="{{ asset('adminDash/assets/img/layouts/favicon.png') }}" alt="Logo">
                <img class="logo-compact" style="height: 25px; width: auto;" src="{{ asset('adminDash/assets/img/layouts/logo.png') }}" alt="Logo Compact">
                <img class="brand-title" style="height: 25px; width: auto;" src="{{ asset('adminDash/assets/img/layouts/logo.png') }}" alt="Brand Title">
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        <!-- Header navbar -->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    @if(auth()->guard('admin')->user()?->hasPermission('setup_general_settings'))
                    <div class="d-flex align-items-center mr-3">
                        <a id="clear-cache-btn" class="clear-cache-premium-btn"
                            href="javascript:void(0)" data-url="{{ Route::has('clear.cache') ? route('clear.cache') : '#' }}">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span class="d-none d-md-inline">Clear Cache</span>
                        </a>
                    </div>
                    @endif

                    <div class="collapse navbar-collapse justify-content-between">
                        @if(auth()->guard('admin')->user()?->hasPermission('manage_order') || auth()->guard('admin')->user()?->hasPermission('pending_order') || auth()->guard('admin')->user()?->hasPermission('hold_order') || auth()->guard('admin')->user()?->hasPermission('approved_order') || auth()->guard('admin')->user()?->hasPermission('packaging_order') || auth()->guard('admin')->user()?->hasPermission('shipment_order') || auth()->guard('admin')->user()?->hasPermission('delivered_order') || auth()->guard('admin')->user()?->hasPermission('canceled_order') || auth()->guard('admin')->user()?->hasPermission('return_order'))
                        <div class="search_bar" style="position: relative;">
                            <form action="{{ Route::has('admin.order-search') ? route('admin.order-search') : '#' }}" method="GET" class="search-form-premium" autocomplete="off">
                                <i class="fa-solid fa-magnifying-glass search-icon-premium"></i>
                                <input class="search-input-premium" id="headerOrderSearch" type="search" name="query" placeholder="Search orders..." aria-label="Search" autocomplete="off">
                            </form>
                            <div id="searchSuggestions" class="search-suggestions-dropdown" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; background: #ffffff; border: 1px solid rgba(0,0,0,0.1); border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1050; max-height: 300px; overflow-y: auto; padding: 5px 0;"></div>
                        </div>
                        @else
                        <div></div>
                        @endif

                        <ul class="navbar-nav header-right">
                            <li class="nav-item d-flex align-items-center mr-3">
                                <button id="darkModeToggle" class="btn btn-sm shadow-sm" style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; color: #6366f1; transition: all 0.3s ease;">
                                    <i class="fa-solid fa-moon fs-18" id="darkModeIcon"></i>
                                </button>
                            </li>
                            @if (isset($featuresConfig['courier_api']) && $featuresConfig['courier_api'] == '1')
                                <div class="container d-flex justify-content-center align-items-center">
                                    <div class="balance-box {{ $balanceStatus == 'success' ? 'revealed' : '' }}" id="balanceBox" data-url="{{ route('courier.balance') }}">
                                        <i class="fa-solid fa-wallet balance-icon"></i>
                                        <span class="balance-label">Balance:</span>
                                        <div class="balance-content">
                                            <span class="balance-click-to-show"><i class="fa-solid fa-eye mr-1"></i> Tap to check</span>
                                            <span class="balance-spinner"><i class="fa-solid fa-spinner"></i></span>
                                            <span class="balance-amount">
                                                @if ($balanceStatus == 'success')
                                                    {{ number_format((float)$currentBalance) }} Tk
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="fa-solid fa-bell fa-xl"></i>
                                    @if ($allorder->count() > 0)
                                        <div class="pulse-css"></div>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="list-unstyled">
                                        <li class="media dropdown-item">
                                            <div class="media-body">
                                                @forelse ($orders as $order)
                                                    <a href="{{ url('/admin/orders/details', $order->id) }}" class="mb-2 d-block">
                                                        <p class="mb-0"><strong>{{ $order->name }}</strong></p>
                                                        <span class="notify-time text-muted" style="font-size: 0.75rem;">
                                                            {{ $order->created_at->format('h:i A, d M y') }}
                                                        </span>
                                                    </a>
                                                @empty
                                                    <p class="text-center text-muted py-2 mb-0">No pending orders.</p>
                                                @endforelse
                                            </div>
                                        </li>
                                    </ul>
                                    <a class="all-notification" href="{{ Route::has('order-new') ? route('order-new') : '#' }}">
                                        See all new Orders <i class="ti-arrow-right"></i>
                                    </a>
                                </div>
                            </li>

                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link profile-trigger" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="profile-avatar-nav">
                                        {{ strtoupper(substr(auth()->guard('admin')->user()?->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <span class="profile-name-nav d-none d-md-inline-block">{{ auth()->guard('admin')->user()?->name ?? 'Admin' }}</span>
                                    <i class="fa-solid fa-chevron-down nav-chevron"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <div class="profile-dropdown-header">
                                        <div class="profile-dropdown-avatar">
                                            {{ strtoupper(substr(auth()->guard('admin')->user()?->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div class="profile-dropdown-meta">
                                            <span class="profile-dropdown-name">{{ auth()->guard('admin')->user()?->name ?? 'Admin' }}</span>
                                            <span class="profile-dropdown-role">{{ auth()->guard('admin')->user()?->role_id ?? 'Administrator' }}</span>
                                        </div>
                                    </div>
                                    <div class="profile-dropdown-body">
                                        <a href="{{ route('admin.profile') }}" class="profile-dropdown-item">
                                            <i class="fa-solid fa-user-gear"></i>
                                            <span>Profile Settings</span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="profile-dropdown-item logout-item">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                            <span>Logout</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    @php $user = auth()->guard('admin')->user(); @endphp
                    <li class="nav-label first">Main Menu</li>
                    @if($user?->hasPermission('view_dashboard'))
                    <li>
                        <a href="{{ route('admin.dashboard') }}" aria-expanded="false">
                            <i class="fa-solid fa-chart-line mr-2"></i><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-label">Operations</li>

                    @if($user?->hasPermission('manage_order') || $user?->hasPermission('pending_order') || $user?->hasPermission('hold_order') || $user?->hasPermission('approved_order') || $user?->hasPermission('packaging_order') || $user?->hasPermission('shipment_order') || $user?->hasPermission('delivered_order') || $user?->hasPermission('canceled_order') || $user?->hasPermission('return_order') || $user?->hasPermission('incomplete_order') || $user?->hasPermission('create_order'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa-solid fa-boxes-stacked mr-2"></i><span class="nav-text">Order Manage</span></a>
                        <ul aria-expanded="false">
                            @if($user?->hasPermission('manage_order'))
                            <li><a href="{{ Route::has('order-index') ? route('order-index') : '#' }}">All Orders</a></li>
                            @endif
                            @if($user?->hasPermission('pending_order'))
                            <li><a href="{{ Route::has('order-pending') ? route('order-pending') : '#' }}">Pending Orders</a></li>
                            @endif
                            @if($user?->hasPermission('hold_order'))
                            <li><a href="{{ Route::has('order-new') ? route('order-new') : '#' }}">Hold Orders</a></li>
                            @endif
                            @if($user?->hasPermission('approved_order'))
                            <li><a href="{{ Route::has('order-approved') ? route('order-approved') : '#' }}">Approved Orders</a></li>
                            @endif
                            @if($user?->hasPermission('packaging_order'))
                            <li><a href="{{ Route::has('order-packaging') ? route('order-packaging') : '#' }}">Packaging Orders</a></li>
                            @endif
                            @if($user?->hasPermission('shipment_order'))
                            <li><a href="{{ Route::has('order-incourier') ? route('order-incourier') : '#' }}">In-Courier Orders</a></li>
                            @endif
                            @if($user?->hasPermission('delivered_order'))
                            <li><a href="{{ Route::has('order-delivered') ? route('order-delivered') : '#' }}">Delivered Orders</a></li>
                            @endif
                            @if($user?->hasPermission('canceled_order'))
                            <li><a href="{{ Route::has('order-canceled') ? route('order-canceled') : '#' }}">Canceled Orders</a></li>
                            @endif
                            @if($user?->hasPermission('return_order'))
                            <li><a href="{{ Route::has('order-returned') ? route('order-returned') : '#' }}">Returned Orders</a></li>
                            @endif
                            @if($user?->hasPermission('incomplete_order'))
                            <li><a href="{{ Route::has('incomplete.index') ? route('incomplete.index') : '#' }}">Incomplete Orders</a></li>
                            @endif
                            @if($user?->hasPermission('create_order'))
                            <li><a href="{{ Route::has('admin.order-create') ? route('admin.order-create') : '#' }}">POS</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if($user?->hasPermission('manage_product') || $user?->hasPermission('create_product') || $user?->hasPermission('manage_category') || $user?->hasPermission('manage_subcategory') || $user?->hasPermission('manage_childcategory') || $user?->hasPermission('manage_attribute') || $user?->hasPermission('manage_reviews'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa-solid fa-box mr-2"></i><span class="nav-text">Product Manage</span></a>
                        <ul aria-expanded="false">
                            @if($user?->hasPermission('manage_product'))
                            <li><a href="{{ Route::has('product.index') ? route('product.index') : '#' }}">All Products</a></li>
                            @endif
                            @if($user?->hasPermission('create_product'))
                            <li><a href="{{ Route::has('product.bulk') ? route('product.bulk') : '#' }}">Bulk Import</a></li>
                            @endif
                            @if($user?->hasPermission('manage_category') || $user?->hasPermission('manage_subcategory') || $user?->hasPermission('manage_childcategory'))
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Category</a>
                                <ul aria-expanded="false">
                                    @if($user?->hasPermission('manage_category'))
                                    <li><a href="{{ Route::has('category.index') ? route('category.index') : '#' }}">Main Category</a></li>
                                    @endif
                                    @if($user?->hasPermission('manage_subcategory'))
                                    <li><a href="{{ Route::has('sub-category.index') ? route('sub-category.index') : '#' }}">Sub Category</a></li>
                                    @endif
                                    @if($user?->hasPermission('manage_childcategory'))
                                    <li><a href="{{ Route::has('child-category.index') ? route('child-category.index') : '#' }}">Child Category</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if($user?->hasPermission('manage_attribute'))
                            <li><a href="{{ Route::has('attribute.index') ? route('attribute.index') : '#' }}">Attribute</a></li>
                            <li><a href="{{ Route::has('color.index') ? route('color.index') : '#' }}">Color</a></li>
                            @endif
                            @if($user?->hasPermission('manage_reviews'))
                            <li><a href="{{ Route::has('reviews.index') ? route('reviews.index') : '#' }}">Reviews</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if($user?->hasPermission('manage_slider') || $user?->hasPermission('manage_banner'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa-solid fa-sliders mr-2"></i><span class="nav-text">Slider & Banner</span></a>
                        <ul aria-expanded="false">
                            @if($user?->hasPermission('manage_slider'))
                            <li><a href="{{ Route::has('slider.index') ? route('slider.index') : '#' }}">Slider</a></li>
                            @endif
                            @if($user?->hasPermission('manage_banner'))
                            <li><a href="{{ Route::has('banner.index') ? route('banner.index') : '#' }}">Banner</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if (isset($featuresConfig['coupon']) && $featuresConfig['coupon'] == '1')
                        @if($user?->hasPermission('manage_coupons'))
                        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                    class="fa-solid fa-hand-holding-dollar mr-2"></i><span class="nav-text">Promotion & Coupons</span></a>
                            <ul aria-expanded="false">
                                <li><a href="{{ Route::has('coupons') ? route('coupons') : '#' }}">Coupons</a></li>
                            </ul>
                        </li>
                        @endif
                    @endif

                    @if($user?->hasPermission('report_order') || $user?->hasPermission('report_product') || $user?->hasPermission('report_web_order') || $user?->hasPermission('report_meta_ads') || $user?->hasPermission('report_profit_sales') || $user?->hasPermission('report_employee') || $user?->hasPermission('report_my_limits'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa-solid fa-chart-pie mr-2"></i><span class="nav-text">Reports</span></a>
                        <ul aria-expanded="false">
                            @if($user?->hasPermission('report_order'))
                            <li><a href="{{ Route::has('report.order') ? route('report.order') : '#' }}">Order Reports</a></li>
                            @endif
                            @if($user?->hasPermission('report_product'))
                            <li><a href="{{ Route::has('report.Product') ? route('report.Product') : '#' }}">Product Reports</a></li>
                            @endif
                            @if($user?->hasPermission('report_web_order'))
                            <li><a href="{{ Route::has('report.WebOrder') ? route('report.WebOrder') : '#' }}">Web Order Reports</a></li>
                            @endif
                            @if($user?->hasPermission('report_meta_ads'))
                            <li><a href="{{ Route::has('report.MetaAds') ? route('report.MetaAds') : '#' }}">Meta Ads Reports</a></li>
                            @endif
                            @if($user?->hasPermission('report_profit_sales'))
                            <li><a href="{{ Route::has('report.Profit&sales') ? route('report.Profit&sales') : '#' }}">Profit & sales</a></li>
                            @endif
                            @if($user?->hasPermission('report_employee'))
                            <li><a href="{{ Route::has('report.Employee') ? route('report.Employee') : '#' }}">Employee Reports</a></li>
                            @endif
                            @if($user?->hasPermission('report_my_limits'))
                            <li><a href="{{ Route::has('report.MyLimits') ? route('report.MyLimits') : '#' }}">My Limits</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if($user?->hasPermission('view_registered_customers') || $user?->hasPermission('view_nonregistered_customers') || $user?->hasPermission('manage_blocked_customers') || $user?->hasPermission('manage_ip_blocked_customers'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa-solid fa-user-group mr-2"></i><span class="nav-text">Customer</span></a>
                        <ul aria-expanded="false">
                            @if($user?->hasPermission('view_nonregistered_customers'))
                            <li><a href="{{ Route::has('nonRegCustomer') ? route('nonRegCustomer') : '#' }}">Non-Registered</a></li>
                            @endif
                            @if($user?->hasPermission('view_registered_customers'))
                            <li><a href="{{ Route::has('regCustomer') ? route('regCustomer') : '#' }}">Registered</a></li>
                            @endif
                            @if($user?->hasPermission('manage_blocked_customers'))
                            <li><a href="{{ Route::has('customerBlock') ? route('customerBlock') : '#' }}">Customer Block</a></li>
                            @endif
                            @if($user?->hasPermission('manage_ip_blocked_customers'))
                            <li><a href="{{ Route::has('customeripBlock') ? route('customeripBlock') : '#' }}">Customer IP Block</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if($user?->hasPermission('manage_support_tickets'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="fa-solid fa-headset mr-2"></i><span class="nav-text">Support</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.chat') }}">Customer Chat</a></li>
                            <li><a href="{{ route('admin.tickets') }}">Ticket</a></li>
                        </ul>
                    </li>
                    @endif

                    @if (isset($featuresConfig['affiliate']) && $featuresConfig['affiliate'] == '1')
                        @if($user?->hasPermission('manage_affiliate_configs') || $user?->hasPermission('manage_affiliate_users') || $user?->hasPermission('manage_referral_users') || $user?->hasPermission('manage_affiliate_withdraw') || $user?->hasPermission('manage_affiliate_logs'))
                        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                    class="fa-solid fa-handshake mr-2"></i><span class="nav-text">Affiliate Manage</span></a>
                            <ul aria-expanded="false">
                                @if($user?->hasPermission('manage_affiliate_configs'))
                                <li><a href="{{ Route::has('affiliate.configs') ? route('affiliate.configs') : '#' }}">Configurations</a></li>
                                @endif
                                @if($user?->hasPermission('manage_affiliate_users'))
                                <li><a href="{{ Route::has('affiliate.users') ? route('affiliate.users') : '#' }}">Affiliate Users</a></li>
                                @endif
                                @if($user?->hasPermission('manage_referral_users'))
                                <li><a href="{{ Route::has('refferals.users') ? route('refferals.users') : '#' }}">Referral Users</a></li>
                                @endif
                                @if($user?->hasPermission('manage_affiliate_withdraw'))
                                <li><a href="{{ Route::has('affiliate.withdraw_requests') ? route('affiliate.withdraw_requests') : '#' }}">Withdraw Requests</a></li>
                                @endif
                                @if($user?->hasPermission('manage_affiliate_logs'))
                                <li><a href="{{ Route::has('affiliate.logs') ? route('affiliate.logs') : '#' }}">Affiliate Logs</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    @endif

                    @if ((isset($featuresConfig['wallet_system']) && $featuresConfig['wallet_system'] == '1') || (isset($featuresConfig['point_system']) && $featuresConfig['point_system'] == '1'))
                        @if($user?->hasPermission('manage_wallet'))
                        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                    class="fa-solid fa-wallet mr-2"></i><span class="nav-text">Wallet & Points</span></a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('admin.wallet.transactions') }}">Transactions & Adjust</a></li>
                                <li><a href="{{ route('admin.wallet.manual-recharges') }}">Manual Recharges</a></li>
                                @if (isset($featuresConfig['point_system']) && $featuresConfig['point_system'] == '1')
                                <li><a href="{{ route('admin.wallet.points-config') }}">Club Points Settings</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    @endif

                    @if($user?->hasPermission('manage_admin'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa-solid fa-users-gear mr-2"></i><span class="nav-text">Admin/Employee</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ Route::has('admin.index') ? route('admin.index') : '#' }}">Employee List</a></li>
                            <li><a href="{{ Route::has('admin.role') ? route('admin.role') : '#' }}">Employee Role</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($user?->hasPermission('manage_pages'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa-solid fa-file-lines mr-2"></i><span class="nav-text">Pages</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ Route::has('pages.index') ? route('pages.index') : '#' }}">Create Pages</a></li>
                            <li><a href="{{ Route::has('pages.index') ? route('pages.index') : '#' }}">All Pages</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($user?->hasPermission('setup_general_settings') || $user?->hasPermission('setup_feature_activation') || $user?->hasPermission('setup_address') || $user?->hasPermission('setup_social_links') || $user?->hasPermission('setup_smtp') || $user?->hasPermission('setup_fraud_check') || $user?->hasPermission('setup_courier_api') || $user?->hasPermission('setup_payment_api'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa-solid fa-gears mr-2"></i><span class="nav-text">Setup & Config</span></a>
                        <ul aria-expanded="false">
                            @if($user?->hasPermission('setup_general_settings'))
                            <li><a href="{{ Route::has('websettings.index') ? route('websettings.index') : '#' }}">General Settings</a></li>
                            @endif
                            @if($user?->hasPermission('setup_feature_activation'))
                            <li><a href="{{ Route::has('feature.index') ? route('feature.index') : '#' }}">Feature Activation</a></li>
                            @endif
                            @if($user?->hasPermission('setup_address'))
                            <li><a href="{{ Route::has('address.index') ? route('address.index') : '#' }}">Address</a></li>
                            @endif
                            @if($user?->hasPermission('setup_social_links'))
                            <li><a href="{{ Route::has('social.index') ? route('social.index') : '#' }}">Social Links</a></li>
                            @endif
                            @if($user?->hasPermission('setup_smtp'))
                                @if (isset($featuresConfig['email_verification']) && $featuresConfig['email_verification'] == '1')
                                <li><a href="{{ Route::has('smtp.index') ? route('smtp.index') : '#' }}">SMTP Settings</a></li>
                                @endif
                            @endif
                            @if($user?->hasPermission('setup_fraud_check'))
                                @if (isset($featuresConfig['fraud_check_api']) && $featuresConfig['fraud_check_api'] == '1')
                                <li><a href="{{ Route::has('fraudCheck.index') ? route('fraudCheck.index') : '#' }}">Fraud Check API</a></li>
                                @endif
                            @endif
                            @if($user?->hasPermission('setup_courier_api'))
                                @if (isset($featuresConfig['courier_api']) && $featuresConfig['courier_api'] == '1')
                                <li><a href="{{ Route::has('courier.index') ? route('courier.index') : '#' }}">Courier API</a></li>
                                @endif
                            @endif
                            @if($user?->hasPermission('setup_payment_api'))
                                @if (isset($featuresConfig['payment_api']) && $featuresConfig['payment_api'] == '1')
                                <li><a href="{{ Route::has('payment.index') ? route('payment.index') : '#' }}">Payment API</a></li>
                                @endif
                            @endif
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Content Body -->
        <div class="content-body">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © <span class="footer-web-name">{{ $webinfo->web_name }}</span> {{ date('Y') }}</p>
            </div>
        </div>

    </div>

    <!-- Required vendors -->
    <script src="{{ asset('Dashboard/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('Dashboard/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('Dashboard/js/custom.min.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/morris/morris.min.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/circle-progress/circle-progress.min.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/gaugeJS/dist/gauge.min.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/jqvmap/js/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/jqvmap/js/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('Dashboard/vendor/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('Dashboard/js/dashboard/dashboard-1.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>

    <!-- AJAX Order Alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const soundElement = document.getElementById('orderAlertSound');
            const checkInterval = 10000; // checking interval (10s)
            const soundDuration = 5000;

            function playOrderAlert(count) {
                if (soundElement) {
                    soundElement.play().catch(error => {
                        console.error("Audio playback blocked:", error);
                    });

                    setTimeout(() => {
                        soundElement.pause();
                        soundElement.currentTime = 0;
                    }, soundDuration);

                    Swal.fire({
                        title: `🎉 ${count} New Orders!`,
                        text: 'Please review new orders immediately.',
                        icon: 'success',
                        timer: soundDuration + 1000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        position: 'center'
                    });
                }
            }

            function checkForNewOrders() {
                const checkUrl = "{{ Route::has('check.new.orders') ? route('check.new.orders') : '#' }}";
                if (checkUrl === '#') return;

                $.ajax({
                    url: checkUrl,
                    method: 'GET',
                    success: function(response) {
                        if (response.new_count > 0) {
                            playOrderAlert(response.new_count);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error checking new orders:", xhr);
                    }
                });
            }

            @if (Route::has('check.new.orders'))
                setInterval(checkForNewOrders, checkInterval);
                checkForNewOrders();
            @endif

            document.body.addEventListener('click', function() {
                const sound = document.getElementById('orderAlertSound');
                if (sound) sound.load();
            }, { once: true });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.settingsUpdateForm').on('submit', function(event) {
                event.preventDefault();
                const currentForm = this;
                const form = $(this);
                const url = form.attr('action');

                if (!url) return;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to update these settings?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Confirm!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Saving Settings...',
                            text: 'Please wait...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const formData = new FormData(currentForm);

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // Dynamically update document title and footer brand name if changed
                                const webNameInput = form.find('input[name="webName"]');
                                if (webNameInput.length > 0) {
                                    const newWebName = webNameInput.val();

                                    // Update browser window/tab title
                                    const currentTitle = document.title;
                                    const separatorIndex = currentTitle.indexOf('|');
                                    if (separatorIndex !== -1) {
                                        document.title = newWebName + ' ' + currentTitle.substring(separatorIndex);
                                    } else {
                                        document.title = newWebName;
                                    }

                                    // Update footer copyright website name
                                    $('.footer-web-name').text(newWebName);
                                }

                                Swal.fire({
                                    toast: true,
                                    position: "top",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    icon: "success",
                                    title: "Settings updated successfully!"
                                });
                            },
                            error: function(xhr) {
                                console.error("Error updating settings:", xhr.responseText);
                                Swal.fire({
                                    icon: "error",
                                    title: "Error!",
                                    text: "Failed to update settings. Please check console."
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: "top",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: "success",
                title: "{{ session('success') }}"
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            let autoDeactivateTimer;
            $('#balanceBox').on('click', function() {
                const $box = $(this);
                if (!$box.hasClass('active')) {
                    $box.addClass('active');
                    if (autoDeactivateTimer) clearTimeout(autoDeactivateTimer);
                    autoDeactivateTimer = setTimeout(function() {
                        $box.removeClass('active');
                    }, 120000);
                } else {
                    $box.removeClass('active');
                    if (autoDeactivateTimer) clearTimeout(autoDeactivateTimer);
                }
            });
        });
    </script>

    @if ($orderpopup)
        <script>
            Swal.fire({
                title: "Courier Created!",
                text: "Consignment ID: {{ $orderpopup->consignment_id }}",
                icon: "info",
                confirmButtonText: "OK"
            }).then(function() {
                $.ajax({
                    url: "/order/popup-seen/{{ $orderpopup->id }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    }
                });
            });
        </script>
    @endif

    <script>
        const clearBtn = document.getElementById('clear-cache-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                let btn = this;
                let url = btn.getAttribute('data-url');
                if (url === '#') return;

                let originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin fs-20"></i> <span class="ml-1">Clearing...</span>';
                btn.style.pointerEvents = 'none';

                fetch(url, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        icon: data.success ? 'success' : 'warning',
                        title: data.success ? data.message : 'Failed to clear cache.'
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        icon: 'error',
                        title: 'Something went wrong!.'
                    });
                })
                .finally(() => {
                    btn.innerHTML = originalContent;
                    btn.style.pointerEvents = 'auto';
                });
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#balanceBox').on('click', function() {
                var $box = $(this);
                if ($box.hasClass('loading')) return;

                $box.addClass('loading');
                $box.removeClass('revealed');

                var url = $box.data('url');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $box.removeClass('loading');
                        if (response.success) {
                            var val = parseFloat(response.balance);
                            var formattedBalance = isNaN(val) ? response.balance + ' Tk' : val.toLocaleString('en-US') + ' Tk';
                            $box.find('.balance-amount').html(formattedBalance);
                            $box.addClass('revealed');
                        } else {
                            $box.addClass('revealed');
                            $box.find('.balance-amount').html('N/A');
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'error',
                                title: response.message || 'Failed to fetch balance'
                            });
                        }
                    },
                    error: function(xhr) {
                        $box.removeClass('loading');
                        $box.find('.balance-amount').html('N/A');
                        $box.addClass('revealed');
                        var errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error fetching balance';
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'error',
                            title: errorMsg
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let searchTimer;
            const $searchField = $('#headerOrderSearch');
            const $suggestionsBox = $('#searchSuggestions');

            $searchField.on('keyup input', function() {
                clearTimeout(searchTimer);
                let query = $(this).val().trim();

                if (query.length < 1) {
                    $suggestionsBox.hide().empty();
                    return;
                }

                searchTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('admin.orders.autocomplete') }}",
                        type: "GET",
                        data: { query: query },
                        success: function(data) {
                            $suggestionsBox.empty();
                            if (data.length > 0) {
                                $.each(data, function(index, item) {
                                    $suggestionsBox.append(
                                        `<a href="${item.url}" class="suggestion-item">
                                            <i class="fa-solid fa-file-invoice mr-2 text-primary"></i>${item.label}
                                         </a>`
                                    );
                                });
                                $suggestionsBox.show();
                            } else {
                                $suggestionsBox.append(
                                    `<div class="suggestion-item-empty">No matching orders found.</div>`
                                );
                                $suggestionsBox.show();
                            }
                        }
                    });
                }, 250);
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search_bar').length) {
                    $suggestionsBox.hide();
                }
            });

            $searchField.on('focus', function() {
                if ($(this).val().trim().length >= 1 && $suggestionsBox.children().length > 0) {
                    $suggestionsBox.show();
                }
            });
        });
    </script>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v24.0"></script>

    <script>
        // Dark Mode Toggle Logic
        $(document).ready(function() {
            const toggleBtn = $('#darkModeToggle');
            const icon = $('#darkModeIcon');
            const body = $('body');

            // Check for saved preference
            const isDarkMode = localStorage.getItem('admin_dark_mode') === 'true';

            // Initial state
            if (isDarkMode) {
                body.addClass('dark-mode');
                icon.removeClass('fa-moon').addClass('fa-sun');
            }

            // Toggle event
            toggleBtn.on('click', function() {
                body.toggleClass('dark-mode');
                const isDark = body.hasClass('dark-mode');

                // Save preference
                localStorage.setItem('admin_dark_mode', isDark);

                // Update icon
                if (isDark) {
                    icon.removeClass('fa-moon').addClass('fa-sun');
                } else {
                    icon.removeClass('fa-sun').addClass('fa-moon');
                }
            });
        });
    </script>

    @yield('script')
</body>
</html>
