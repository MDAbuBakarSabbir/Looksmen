@extends('layouts.Frontend.master')

@section('title')
    Track Your Order
@endsection

@section('content')
<style>
    .track-wrapper {
        font-family: 'Outfit', 'Inter', sans-serif;
    }
    .track-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        overflow: hidden;
    }
    .track-header {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: #ffffff;
        padding: 30px;
        text-align: center;
    }
    .form-control-track {
        height: 50px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        font-size: 15px;
        transition: all 0.3s;
    }
    .form-control-track:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    .btn-track {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        height: 50px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-track:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: #ffffff;
    }
    
    /* Timeline styles */
    .timeline-steps {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin-top: 40px;
        margin-bottom: 40px;
    }
    .timeline-steps::after {
        content: "";
        position: absolute;
        height: 4px;
        background: #e2e8f0;
        top: 25px;
        left: 0;
        right: 0;
        z-index: 1;
    }
    .timeline-progress-bar {
        position: absolute;
        height: 4px;
        background: #10b981;
        top: 25px;
        left: 0;
        z-index: 2;
        transition: width 0.5s ease;
    }
    .timeline-step {
        position: relative;
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 20%;
    }
    .step-icon {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #ffffff;
        border: 4px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #94a3b8;
        transition: all 0.3s;
    }
    .timeline-step.active .step-icon {
        border-color: #4f46e5;
        color: #4f46e5;
        box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.15);
    }
    .timeline-step.completed .step-icon {
        border-color: #10b981;
        background: #10b981;
        color: #ffffff;
    }
    .timeline-step.canceled .step-icon {
        border-color: #ef4444;
        background: #ef4444;
        color: #ffffff;
    }
    .step-title {
        margin-top: 12px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-align: center;
    }
    .timeline-step.active .step-title {
        color: #4f46e5;
    }
    .timeline-step.completed .step-title {
        color: #0f172a;
    }
    .timeline-step.canceled .step-title {
        color: #ef4444;
    }
    
    @media (max-width: 768px) {
        .timeline-steps {
            flex-direction: column;
            align-items: flex-start;
            padding-left: 20px;
        }
        .timeline-steps::after {
            width: 4px;
            height: 100%;
            top: 0;
            bottom: 0;
            left: 45px;
        }
        .timeline-progress-bar {
            width: 4px !important;
            height: 0; /* JS will adjust height instead */
            left: 45px;
            top: 0;
        }
        .timeline-step {
            flex-direction: row;
            width: 100%;
            margin-bottom: 25px;
        }
        .step-title {
            margin-top: 0;
            margin-left: 15px;
            text-align: left;
        }
    }
</style>

<div class="py-5 bg-light track-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                
                <!-- Track Form Card -->
                <div class="card track-card mb-5">
                    <div class="track-header">
                        <i class="las la-truck-loading la-3x mb-2"></i>
                        <h2 class="h3 fw-600 m-0 text-white">Order Tracking</h2>
                        <p class="text-white-50 m-0 mt-1">Get real-time updates on your shipment status</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('front.trackOrder') }}" method="GET" onsubmit="return validateTrackForm()">
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label class="form-label text-muted fw-600 fs-13 uppercase">Order ID</label>
                                    <input type="number" id="track-order-id" name="order_id" class="form-control-track w-100" placeholder="e.g. 1024" value="{{ request('order_id') }}">
                                    <span class="text-xs text-muted" style="display:block; margin-top:4px;">Search by Order ID</span>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label class="form-label text-muted fw-600 fs-13 uppercase">Phone Number</label>
                                    <input type="text" id="track-phone" name="phone" class="form-control-track w-100" placeholder="e.g. 017xxxxxxxx" value="{{ request('phone') }}">
                                    <span class="text-xs text-muted" style="display:block; margin-top:4px;">Or search by Phone Number</span>
                                </div>
                                <div class="col-md-2 mb-3 d-flex align-items-end" style="padding-bottom: 22px;">
                                    <button type="submit" class="btn-track w-100"><i class="las la-search mr-1"></i> Track</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if($searched)
                    @if($order)
                        <!-- Tracking Results -->
                        <div class="card track-card">
                            <div class="card-body p-4 p-md-5">
                                
                                <!-- Summary -->
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-4 border-bottom border-gray-200">
                                    <div>
                                        <h4 class="h5 fw-600 text-dark mb-1">Order ID: <span class="text-primary">#{{ $order->id }}</span></h4>
                                        <p class="text-muted mb-0 fs-13">Placed on: {{ $order->created_at->format('d M, Y \a\t h:i A') }}</p>
                                    </div>
                                    @if($order->grand_total > 0)
                                        <div class="text-md-right mt-3 mt-md-0">
                                            <span class="d-block text-muted fs-13">Grand Total:</span>
                                            <span class="h4 fw-700 text-dark">৳{{ number_format($order->grand_total, 2) }}</span>
                                        </div>
                                    @elseif(isset($order->paid_amount) && $order->paid_amount > 0)
                                        <div class="text-md-right mt-3 mt-md-0">
                                            <span class="d-block text-muted fs-13">Paid Amount:</span>
                                            <span class="h4 fw-700 text-success">৳{{ number_format($order->paid_amount, 2) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Timeline Tracking -->
                                @php
                                    $status = $order->delivery_status;
                                    $isCanceled = in_array($status, ['cancel', 'canceled', 'cancelled']);
                                    $isReturned = in_array($status, ['return', 'returned']);
                                    
                                    // Map current status to step index (0 to 4)
                                    $stepIndex = 0;
                                    if ($isCanceled || $isReturned) {
                                        $stepIndex = -1;
                                    } else {
                                        switch($status) {
                                            case 'new':
                                                $stepIndex = 0;
                                                break;
                                            case 'hold':
                                            case 'approved':
                                                $stepIndex = 1;
                                                break;
                                            case 'packaging':
                                                $stepIndex = 2;
                                                break;
                                            case 'shipment':
                                                $stepIndex = 3;
                                                break;
                                            case 'delivered':
                                                $stepIndex = 4;
                                                break;
                                        }
                                    }
                                    
                                    // Progress bar width
                                    $progressPercent = $stepIndex >= 0 ? ($stepIndex / 4) * 100 : 0;
                                @endphp

                                @if($isCanceled)
                                    <div class="alert alert-danger rounded-lg p-3 text-center mb-4">
                                        <i class="las la-times-circle fs-20 mr-2 align-middle"></i>
                                        <span class="align-middle fw-600">This order has been Canceled.</span>
                                    </div>
                                @elseif($isReturned)
                                    <div class="alert alert-warning rounded-lg p-3 text-center mb-4">
                                        <i class="las la-undo-alt fs-20 mr-2 align-middle"></i>
                                        <span class="align-middle fw-600">This order has been Returned.</span>
                                    </div>
                                @endif

                                @if(!$isCanceled && !$isReturned)
                                    <div class="timeline-steps">
                                        <!-- Progress line for desktop -->
                                        <div class="timeline-progress-bar d-none d-md-block" style="width: {{ $progressPercent }}%;"></div>
                                        
                                        <!-- Step 1: Placed -->
                                        <div class="timeline-step {{ $stepIndex >= 0 ? 'completed' : '' }} {{ $stepIndex == 0 ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="las la-receipt"></i>
                                            </div>
                                            <div class="step-title">Order Placed</div>
                                        </div>

                                        <!-- Step 2: Confirmed -->
                                        <div class="timeline-step {{ $stepIndex >= 1 ? 'completed' : '' }} {{ $stepIndex == 1 ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="las la-check-circle"></i>
                                            </div>
                                            <div class="step-title">Confirmed</div>
                                        </div>

                                        <!-- Step 3: Packaging -->
                                        <div class="timeline-step {{ $stepIndex >= 2 ? 'completed' : '' }} {{ $stepIndex == 2 ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="las la-box"></i>
                                            </div>
                                            <div class="step-title">Packaging</div>
                                        </div>

                                        <!-- Step 4: Shipped -->
                                        <div class="timeline-step {{ $stepIndex >= 3 ? 'completed' : '' }} {{ $stepIndex == 3 ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="las la-truck"></i>
                                            </div>
                                            <div class="step-title">Shipped</div>
                                        </div>

                                        <!-- Step 5: Delivered -->
                                        <div class="timeline-step {{ $stepIndex >= 4 ? 'completed' : '' }} {{ $stepIndex == 4 ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="las la-hand-holding-heart"></i>
                                            </div>
                                            <div class="step-title">Delivered</div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Details Table -->
                                <div class="mt-4">
                                    <h5 class="fw-600 text-dark mb-3">Shipping & Status Info</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0" style="border-radius: 8px; overflow: hidden;">
                                            <tbody>
                                                <tr>
                                                    <td class="w-30 bg-light text-muted fw-600">Customer Name</td>
                                                    <td>{{ $order->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bg-light text-muted fw-600">Phone</td>
                                                    <td>{{ $order->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bg-light text-muted fw-600">Shipping Address</td>
                                                    <td>{{ $order->address }}, {{ $order->thana }}, {{ $order->district }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bg-light text-muted fw-600">Payment Status</td>
                                                    <td>
                                                        <span class="badge badge-inline px-2 py-1 uppercase fw-600 fs-11" style="border-radius: 4px; 
                                                            @if($order->payment_status === 'paid') background: rgba(16, 185, 129, 0.1); color: #10b981;
                                                            @elseif($order->payment_status === 'partial_paid') background: rgba(245, 158, 11, 0.1); color: #f59e0b;
                                                            @else background: rgba(239, 68, 68, 0.1); color: #ef4444; @endif">
                                                            {{ str_replace('_', ' ', $order->payment_status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bg-light text-muted fw-600">Payment Method</td>
                                                    <td>{{ strtoupper($order->payment_type) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @else
                        <!-- No Order Found -->
                        <div class="card track-card">
                            <div class="card-body p-5 text-center">
                                <i class="las la-frown-open la-4x text-danger mb-3"></i>
                                <h4 class="h5 fw-600 text-dark mb-2">Order Not Found</h4>
                                <p class="text-muted mb-0">
                                    We couldn't find any matching order details.
                                    @if(request('order_id') && request('phone'))
                                        Checked ID <strong>#{{ request('order_id') }}</strong> and Phone <strong>{{ request('phone') }}</strong>.
                                    @elseif(request('order_id'))
                                        Checked ID <strong>#{{ request('order_id') }}</strong>.
                                    @else
                                        Checked Phone <strong>{{ request('phone') }}</strong>.
                                    @endif
                                </p>
                                <p class="text-muted mt-1 fs-13">Please check your details and try again.</p>
                            </div>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</div>

<script>
    // Form validation before submit
    function validateTrackForm() {
        const orderId = document.getElementById('track-order-id').value.trim();
        const phone = document.getElementById('track-phone').value.trim();
        if (!orderId && !phone) {
            alert('Please enter either an Order ID or a Phone Number to track.');
            return false;
        }
        return true;
    }

    // Responsive dynamic progress line for mobile
    document.addEventListener('DOMContentLoaded', function() {
        const resizeProgressLine = () => {
            if (window.innerWidth <= 768) {
                const steps = document.querySelectorAll('.timeline-step');
                const completedSteps = document.querySelectorAll('.timeline-step.completed');
                const progressBar = document.querySelector('.timeline-progress-bar');
                
                if (steps.length && progressBar && completedSteps.length) {
                    const totalHeight = (steps.length - 1) * 79; // Distance approximate
                    const activeHeight = (completedSteps.length - 1) * 79;
                    progressBar.style.height = activeHeight + 'px';
                }
            }
        };
        
        window.addEventListener('resize', resizeProgressLine);
        resizeProgressLine();
    });
</script>
@endsection
