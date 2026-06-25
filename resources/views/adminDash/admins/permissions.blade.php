@extends('layouts.AdminLays.master')
@section('title')
    PERMISSIONS
@endsection
@section('content')
    <style>
        /* সুইচের আসল ডিজাইন */
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
            background-color: #2196F3;
        }

        input:checked+.slider:before {
            transform: translateX(18px);
        }

        /* গোল কোণা করার জন্য */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .border.rounded:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05) !important;
            border-color: #2196F3 !important;
        }
    </style>

    @php
    $permissionGroups = [
        'General' => [
            'view_dashboard' => 'View Dashboard',
        ],
        'Roles & Admins' => [
            'manage_admin' => 'Manage Admin / Employees',
            'view_role' => 'View Roles',
            'add_role' => 'Add Roles',
            'edit_role' => 'Edit Roles',
            'delete_role' => 'Delete Roles',
        ],
        'Order Management' => [
            'manage_order' => 'All Orders (View/Edit)',
            'pending_order' => 'Pending Orders View',
            'hold_order' => 'Hold Orders View',
            'approved_order' => 'Approved Orders View',
            'packaging_order' => 'Packaging Orders View',
            'shipment_order' => 'In-Courier Orders View',
            'delivered_order' => 'Delivered Orders View',
            'canceled_order' => 'Canceled Orders View',
            'return_order' => 'Returned Orders View',
            'incomplete_order' => 'Incomplete Orders View',
            'create_order' => 'POS (Create Order)',
        ],
        'Product Management' => [
            'manage_product' => 'All Products View',
            'create_product' => 'Create & Bulk Import Products',
            'manage_category' => 'Main Category View',
            'manage_subcategory' => 'Sub Category View',
            'manage_childcategory' => 'Child Category View',
            'manage_attribute' => 'Attribute & Color View',
            'manage_reviews' => 'Product Reviews View',
        ],
        'Sliders & Media' => [
            'manage_slider' => 'Manage Sliders',
            'manage_banner' => 'Manage Banners',
        ],
        'Promotions' => [
            'manage_coupons' => 'Manage Coupons',
        ],
        'Reports' => [
            'report_order' => 'Order Reports',
            'report_product' => 'Product Reports',
            'report_web_order' => 'Web Order Reports',
            'report_meta_ads' => 'Meta Ads Reports',
            'report_profit_sales' => 'Profit & Sales Reports',
            'report_employee' => 'Employee Reports',
            'report_my_limits' => 'My Limits Reports',
        ],
        'Customer Management' => [
            'view_registered_customers' => 'Registered Customers',
            'view_nonregistered_customers' => 'Non-Registered Customers',
            'manage_blocked_customers' => 'Blocked Customers List',
            'manage_ip_blocked_customers' => 'IP Blocked Customers List',
        ],
        'Support Tickets' => [
            'manage_support_tickets' => 'Manage Support Tickets',
        ],
        'Affiliate System' => [
            'manage_affiliate_configs' => 'Affiliate Configurations',
            'manage_affiliate_users' => 'Affiliate Users List',
            'manage_referral_users' => 'Referral Users List',
            'manage_affiliate_withdraw' => 'Affiliate Withdraw Requests',
            'manage_affiliate_logs' => 'Affiliate System Logs',
        ],
        'Content Pages' => [
            'manage_pages' => 'Create & Manage Pages',
        ],
        'Setup & Configurations' => [
            'setup_general_settings' => 'General Settings Control',
            'setup_feature_activation' => 'Feature Activation Config',
            'setup_address' => 'Setup Addresses (District/Thana)',
            'setup_social_links' => 'Setup Social Links',
            'setup_smtp' => 'Setup SMTP & Email Config',
            'setup_fraud_check' => 'Setup Fraud Check API',
            'setup_courier_api' => 'Setup Courier API Credentials',
            'setup_payment_api' => 'Setup Payment Gateways API',
        ]
    ];
    $adminPermissions = json_decode($admin->permission_id ?? '[]', true);
    if (!is_array($adminPermissions)) {
        $adminPermissions = [];
    }
    @endphp

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark mb-0">Assign Permissions</h3>
                    <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 20px;">
                        <i class="fa fa-arrow-left mr-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 bg-light p-3 rounded" style="border-left: 4px solid #2196F3;">
                        <label class="form-label font-weight-bold text-muted mb-1">Admin Employee Name</label>
                        <h4 class="mb-0 text-dark font-weight-bold">{{ $admin->name }} ({{ $admin->email }})</h4>
                    </div>
                    
                    <form action="{{ route('admin.permission.update', $admin->id) }}" method="post">
                        @csrf
                        @foreach($permissionGroups as $groupName => $permissions)
                            <h4 class="mt-4 mb-3 text-primary font-weight-bold" style="border-bottom: 2px solid #f1f1f1; padding-bottom: 6px;">
                                {{ $groupName }}
                            </h4>
                            <div class="row">
                                @foreach($permissions as $slug => $label)
                                    <div class="col-lg-3 col-md-6 mb-2">
                                        <div class="d-flex justify-content-between align-items-center border rounded p-2 bg-white shadow-sm" style="transition: transform 0.2s, box-shadow 0.2s;">
                                            <span style="font-size: 13px; font-weight: 600; color: #495057;">{{ $label }}</span>
                                            <label class="switch mb-0">
                                                <input type="checkbox" name="permissions[]" value="{{ $slug }}" id="permission_{{ $slug }}" {{ in_array($slug, $adminPermissions) ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        
                        <div class="form-group text-center mt-5">
                            <button type="submit" class="btn btn-primary px-5 btn-lg" style="border-radius: 30px; box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);">
                                <i class="fa fa-save mr-1"></i> Update Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
