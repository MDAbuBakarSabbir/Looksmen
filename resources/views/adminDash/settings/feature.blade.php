@extends('layouts.adminLays.master')
@section('title')
    FEATURE
@endsection
@section('content')
    <style>
        /* Premium Toggle Switch Styles */
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
    </style>
    <div class="row">
        {{-- === 1. Affiliate Marketing === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>Affiliate Marketing</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="affiliate"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['affiliate']) && $featuresConfig['affiliate'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click to Change Status"></span>
                        </label>
                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>Configure your Affiliate Marketing program settings and commission rates.</p>
                        <a class="d-flex justify-content-center" href="{{ route('affiliate.index') }}">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- === 2. Courier API === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>Courier API</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="courier_api"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['courier_api']) && $featuresConfig['courier_api'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click to Change Status"></span>
                        </label>
                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>You need to configure Courier API (Redx, Steadfast, Pathao etc.) to enable this feature.</p>
                        <a class="d-flex justify-content-center" href="{{ route('courier.index') }}">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- === 3. Payment API === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>Payment API</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="payment_api"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['payment_api']) && $featuresConfig['payment_api'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click to Change Status"></span>
                        </label>
                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>You need to configure Payment API (bKash, SSLCommerz etc.) to enable this feature.</p>
                        <a class="d-flex justify-content-center" href="{{ route('payment.index') }}">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- === 4. Coupon System === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>Coupon System</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="coupon"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['coupon']) && $featuresConfig['coupon'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click for Deactive">
                            </span>
                        </label>
                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>Create and manage discount coupons for your customers.</p>
                        <a class="d-flex justify-content-center" href="{{ route('coupons') }}">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- === 5. Email Verification === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>Email Verification</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="email_verification"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['email_verification']) && $featuresConfig['email_verification'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click for Deactive">
                            </span>
                        </label>
                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>You need to configure SMTP settings to enable email verification for users.</p>
                        <a class="d-flex justify-content-center" href="{{ route('smtp.index') }}">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- === 6. SMS Verification === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>SMS Verification</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="sms_verification"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['sms_verification']) && $featuresConfig['sms_verification'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click for Deactive">
                            </span>
                        </label>
                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>You need to configure SMS Gateway API to enable SMS verification for users.</p>
                        <a class="d-flex justify-content-center" href="{{ route('websettings.index') }}">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        {{-- === 7. Social Login === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>Social Login</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="social_login_api"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['social_login_api']) && $featuresConfig['social_login_api'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click for Deactive">
                            </span>
                        </label>

                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>Configure social login (Google, Facebook) credentials to enable this feature.</p>
                        <a class="d-flex justify-content-center" href="{{ route('websettings.index') }}">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- === 8. Facebook API === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>Facebook API</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="facebook_api"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['facebook_api']) && $featuresConfig['facebook_api'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click for Deactive">
                            </span>
                        </label>
                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>Configure Facebook Pixel, domain verification and chat plugin in Web Settings.</p>
                        <a class="d-flex justify-content-center" href="{{ route('websettings.index') }}#facebook">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- === 9. Fraud Check API === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>Fraud Check API</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="fraud_check_api"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['fraud_check_api']) && $featuresConfig['fraud_check_api'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click for Deactive">
                            </span>
                        </label>
                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>You need to configure BDCourier Fraud Check API key to enable this feature.</p>
                        <a class="d-flex justify-content-center" href="{{ route('fraudCheck.index') }}">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- === 10. Wallet System === --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2>Wallet System</h2>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="wallet_system"
                                data-url="{{ route('feature.status') }}"
                                {{ isset($featuresConfig['wallet_system']) && $featuresConfig['wallet_system'] == '1' ? 'checked' : '' }}>
                            <span class="slider round" title="Click for Deactive">
                            </span>
                        </label>
                    </div>
                    <div class="alert flex-column justify-content-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <p>Configure Wallet System to allow customers to store and use balance in their account.</p>
                        <a class="d-flex justify-content-center" href="{{ route('websettings.index') }}">Configure Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. CSRF Setup (required for POST requests in Laravel) ---
            // Ensure you have <meta name="csrf-token" content="{{ csrf_token() }}"> in your <head>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // --- 2. Event Listener for all status switches ---
            $('.status-switch').on('change', function() {
                const switchElement = $(this);
                const name = switchElement.data('name');
                const url = switchElement.data('url');
                const newStatus = switchElement.is(':checked') ? 1 : 0;

                const actionText = newStatus === 1 ? 'ACTIVATE' : 'DEACTIVATE';
                const confirmColor = newStatus === 1 ? '#28a745' : '#dc3545';

                // --- 3. SweetAlert2 Confirmation ---
                Swal.fire({
                    title: `${actionText} ${name}?`,
                    text: `Are you sure you want to ${actionText.toLowerCase()} the ${name} feature?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#6c7784',
                    confirmButtonText: `Yes, ${actionText} it!`
                }).then((result) => {
                    if (result.isConfirmed) {

                        // --- 4. AJAX Request on Confirmation ---
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                name: name, // Sending the feature name
                                status: newStatus // Sending the new status (1 or 0)
                            },
                            success: function(response) {
                                // Show success notification from controller message
                                Swal.fire('Updated!', response.message, 'success');

                                // Optional: Update the switch title dynamically
                                const title = newStatus === 1 ? 'Click to Deactivate' :
                                    'Click to Activate';
                                switchElement.next('.slider').attr('title', title);
                            },
                            error: function(xhr) {
                                // AJAX failed (404, 500, validation error, etc.)

                                // Revert the switch visually to its previous state
                                switchElement.prop('checked', !newStatus);

                                Swal.fire('Error!',
                                    'Update failed. Please check the network or console.',
                                    'error');
                                console.error("AJAX Error Response:", xhr.responseText);
                            }
                        });

                    } else {
                        // --- 5. Revert Switch on Cancel ---
                        // User clicked cancel, revert the switch to its original (opposite) state
                        switchElement.prop('checked', !newStatus);
                    }
                });
            });
        });
    </script>
@endsection
