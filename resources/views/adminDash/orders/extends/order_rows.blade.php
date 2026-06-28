@forelse ($orders as $order)
    <tr>
        <td scope="row" style="vertical-align: middle;">
            <input type="checkbox" class="order-check" value="{{ $order->id }}">
        </td>
        
        <!-- Customer Column -->
        <td style="vertical-align: middle;">
            <div class="d-flex flex-column gap-1">
                <div class="d-flex align-items-center mb-1" style="gap: 8px;">
                    <div class="customer-avatar">
                        {{ strtoupper(substr($order->name ?? 'C', 0, 1)) }}
                    </div>
                    <a class="text-dark font-weight-bold" href="{{ route('admin.order-show', $order->id) }}" target="_blank" style="font-size: 13.5px; text-decoration: none;">
                        {{ $order->name }}
                    </a>
                </div>
                <div class="pl-1">
                    <span class="d-flex align-items-center text-muted" style="font-size: 12px; gap: 4px;">
                        <i class="fa-solid fa-phone" style="font-size: 10px; width: 14px; opacity: 0.7;"></i>
                        <span>{{ $order->phone }}</span>
                    </span>
                    <span class="d-flex align-items-center text-muted mt-1" style="font-size: 11.5px; gap: 4px; line-height: 1.4;">
                        <i class="fa-solid fa-location-dot" style="font-size: 10px; width: 14px; opacity: 0.7;"></i>
                        <span class="text-wrap" style="max-width: 240px;">{{ $order->address }}</span>
                    </span>
                </div>
            </div>
        </td>

        <!-- Courier History Column -->
        <td style="vertical-align: middle;">
            @php
                $history = $order->getCourierHistoryData();
                $courierData = $history ? ($history['courierData'] ?? null) : null;
                $summary = $courierData ? ($courierData['summary'] ?? null) : null;
                $total = $summary ? intval($summary['total_parcel'] ?? 0) : 0;
                $success = $summary ? intval($summary['success_parcel'] ?? 0) : 0;
                $failed = $summary ? intval($summary['cancelled_parcel'] ?? 0) : 0;
                
                $ratio = 0;
                if ($total > 0) {
                    $ratio = round(($success / $total) * 100);
                }
                
                $barColor = '#94a3b8'; // Default grey for 0 total
                if ($total > 0) {
                    if ($ratio >= 80) {
                        $barColor = '#22c55e'; // Green
                    } elseif ($ratio >= 50) {
                        $barColor = '#f59e0b'; // Amber/Orange
                    } else {
                        $barColor = '#ef4444'; // Red
                    }
                }
            @endphp
            @if ($history)
                <div class="d-flex flex-column" style="min-width: 155px; font-family: sans-serif;">
                    <!-- Ratio Progress Bar -->
                    <div style="height: 10px; background-color: #e2e8f0; border-radius: 20px; overflow: hidden; margin-bottom: 5px; width: 100%; border: 1px solid #cbd5e1;">
                        <div style="width: {{ $ratio }}%; height: 100%; background-color: {{ $barColor }}; border-radius: 20px; transition: width 0.4s ease;"></div>
                    </div>
                    <!-- Stats Labels -->
                    <div class="d-flex align-items-center justify-content-between" style="font-size: 11px; font-weight: 700; padding: 0 2px;">
                        <span style="color: #0f172a;">To: <span style="font-weight: 500; color: #475569;">{{ $total }}</span></span>
                        <span style="color: #16a34a; margin-left: 5px;">Su: <span style="font-weight: 500; color: #475569;">{{ $success }}</span></span>
                        <span style="color: #dc2626; margin-left: 5px;">Fa: <span style="font-weight: 500; color: #475569;">{{ $failed }}</span></span>
                    </div>
                </div>
            @else
                <span class="text-muted" style="font-size: 11.5px; font-style: italic;">N/A</span>
            @endif
        </td>
        <td style="vertical-align: middle;">
            <div class="d-flex flex-column" style="gap: 10px;">
                @forelse ($order->orderDetails as $detail)
                    @php
                        $product = $detail->orderProduct;
                        $firstImg = $product?->firstImage;
                        $imgPath = $firstImg ? asset('adminDash/images/product/' . $firstImg->image) : asset('favicon.png');
                    @endphp
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <img style="height: 50px; width: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f8fafc;" 
                             src="{{ $imgPath }}" 
                             alt="{{ $product?->title ?? 'Product' }}"
                             onerror="this.onerror=null; this.src='{{ asset('favicon.png') }}';">
                        <div style="line-height: 1.3;">
                            <span class="text-dark font-weight-bold d-block text-wrap" style="font-size: 12.5px; max-width: 220px;">
                                {{ $product?->title ?? 'Deleted Product' }}
                            </span>
                            <div class="d-flex flex-wrap mt-1" style="gap: 4px; font-size: 10px;">
                                <span class="badge badge-light px-2 py-1 text-dark" style="border: 1px solid #e2e8f0;">Qty: {{ $detail->product_qty }}</span>
                                @if(!empty($detail->product_attribute))
                                    <span class="badge badge-light px-2 py-1 text-dark" style="border: 1px solid #e2e8f0;">Size: {{ $detail->product_attribute }}</span>
                                @endif
                                @if(!empty($detail->product_colour))
                                    <span class="badge badge-light px-2 py-1 text-dark" style="border: 1px solid #e2e8f0;">Color: {{ $detail->product_colour }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <span class="text-muted" style="font-size: 11.5px; font-style: italic;">No items associated</span>
                @endforelse
            </div>
        </td>

        <!-- Invoice Column -->
        <td style="vertical-align: middle;">
            <div class="d-flex flex-column" style="gap: 6px; align-items: flex-start;" id="courier-info-{{ $order->id }}">
                <span class="badge badge-light text-dark font-weight-bold" style="font-size: 11px; padding: 5px 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-family: monospace; display: inline-block;">
                    Invoice: #LM-{{ $order->id }}
                </span>
                @if ($order->consignment_id != null)
                    <div class="d-flex align-items-center" style="gap: 4px;">
                        <span class="badge badge-info text-white font-weight-bold" style="font-size: 11px; padding: 5px 8px; border-radius: 6px; background-color: #0284c7; border: 1px solid #0284c7; font-family: monospace; display: inline-block;">
                            Courier: {{ $order->consignment_id }}
                        </span>
                        <button type="button" class="btn btn-xs btn-outline-info track-courier-btn" data-id="{{ $order->id }}" style="padding: 2px 5px; border-radius: 4px; cursor: pointer;" title="Track Order">
                            <i class="fa-solid fa-magnifying-glass" style="font-size: 10px;"></i>
                        </button>
                    </div>
                @else
                    <span class="badge badge-light text-muted font-weight-normal" style="font-size: 11px; padding: 5px 8px; border-radius: 6px; border: 1px solid #e2e8f0; font-family: monospace; display: inline-block;">
                        Courier: N/A
                    </span>
                @endif
            </div>
        </td>

        <!-- Amount Column -->
        <td style="vertical-align: middle;">
            <div class="d-flex flex-column" style="gap: 4px; font-size: 12px; min-width: 110px;">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total:</span>
                    <span class="font-weight-bold text-dark">{{ $order->total_amount }} ৳</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Paid:</span>
                    <span class="text-success font-weight-bold">{{ $order->paid_amount ?? 0 }} ৳</span>
                </div>
                <div class="d-flex justify-content-between border-top pt-1" style="border-top: 1px solid #e2e8f0 !important;">
                    <span class="text-muted font-weight-bold">Due:</span>
                    <span class="font-weight-bold text-danger">{{ $order->grand_total }} ৳</span>
                </div>
            </div>
        </td>

        <!-- Status Column -->
        <td style="vertical-align: middle;">
            <div class="d-flex flex-column" style="gap: 6px;">
                <div>
                    <span id="status-badge-{{ $order->id }}" class="status-pill status-{{ $order->delivery_status }}">
                        {{ ucfirst($order->delivery_status) }}
                    </span>
                </div>
                <div class="text-muted" style="font-size: 11px; line-height: 1.5; min-width: 135px;">
                    <div class="d-flex align-items-center" style="gap: 4px;">
                        <i class="fa-regular fa-calendar-plus" style="font-size: 10px; opacity: 0.7;"></i>
                        <span>Created: {{ $order->created_at->format('d M, Y') }}</span>
                    </div>
                    @if ($order->delivery_status != 'new' && $order->delivery_status != 'hold')
                        <div class="d-flex align-items-center mt-1" style="gap: 4px;">
                            <i class="fa-regular fa-calendar-check" style="font-size: 10px; opacity: 0.7;"></i>
                            <span>Updated: {{ $order->updated_at->format('d M, Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center mt-1 text-primary" style="gap: 4px; font-weight: 500;">
                            <i class="fa-regular fa-user" style="font-size: 10px; opacity: 0.7;"></i>
                            <span>By: {{ $order->admin?->name ?? 'System' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </td>

        <!-- Comments Column -->
        <td style="vertical-align: middle;">
            @if (empty($order->comments))
                <span class="text-muted" style="font-size: 11.5px; font-style: italic;">N/A</span>
            @else
                <span class="text-dark d-inline-block text-wrap" style="font-size: 11.5px; max-width: 140px; line-height: 1.4;">
                    {{ $order->comments }}
                </span>
            @endif
        </td>

        <!-- Action Dropdown Column -->
        <td style="vertical-align: middle;">
            <div class="basic-dropdown">
                <div class="dropdown">
                    <button type="button" class="btn btn-action-dropdown dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <div class="dropdown-menu" id="dropdown-menu-{{ $order->id }}" x-placement="bottom-start"
                        style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                        @include('adminDash.orders.extends.buttons')
                    </div>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center text-danger p-4" style="font-size: 13.5px; font-weight: 600;">No Orders found.</td>
    </tr>
@endforelse

<!-- Global styles and scripts for dynamic elements -->
@once
<style>
    /* Custom Status Pills */
    .status-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 30px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 0.3px;
    }
    
    .status-pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    /* Status Colors */
    .status-new, .status-hold {
        background-color: #f3f4f6;
        color: #4b5563;
    }
    .status-new::before, .status-hold::before { background-color: #4b5563; }

    .status-pending {
        background-color: #fef3c7;
        color: #d97706;
    }
    .status-pending::before { background-color: #d97706; }

    .status-approved {
        background-color: #d1fae5;
        color: #059669;
    }
    .status-approved::before { background-color: #059669; }

    .status-packaging {
        background-color: #e0e7ff;
        color: #4f46e5;
    }
    .status-packaging::before { background-color: #4f46e5; }

    .status-incourier, .status-in_courier {
        background-color: #e0f2fe;
        color: #0284c7;
    }
    .status-incourier::before, .status-in_courier::before { background-color: #0284c7; }

    .status-delivered {
        background-color: #d1fae5;
        color: #047857;
        border: 1px solid #10b981;
    }
    .status-delivered::before { background-color: #047857; }

    .status-cancel, .status-canceled {
        background-color: #fee2e2;
        color: #dc2626;
    }
    .status-cancel::before, .status-canceled::before { background-color: #dc2626; }

    .status-returned {
        background-color: #f3f4f6;
        color: #1f2937;
        border: 1px solid #9ca3af;
    }
    .status-returned::before { background-color: #1f2937; }

    /* Customer Avatar */
    .customer-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: rgba(99, 102, 241, 0.1);
        color: #4f46e5;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }

    /* Premium Dropdown Menu */
    .basic-dropdown .dropdown-menu {
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        padding: 8px !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        min-width: 180px;
        transition: all 0.2s ease;
        z-index: 1050;
        max-height: none !important;
        overflow: visible !important;
    }

    .basic-dropdown .dropdown-item {
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 12.5px !important;
        font-weight: 600;
        color: #4b5563 !important;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        border: none !important;
        background: none !important;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .basic-dropdown .dropdown-item:hover {
        background-color: #f3f4f6 !important;
        color: #111827 !important;
    }

    .basic-dropdown .dropdown-item i {
        font-size: 13.5px;
        opacity: 0.85;
    }

    /* Action toggle button */
    .btn-action-dropdown {
        background-color: #f3f4f6 !important;
        color: #4b5563 !important;
        border: 1px solid #e5e7eb !important;
        width: 36px;
        height: 36px;
        border-radius: 10px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        padding: 0 !important;
        box-shadow: none !important;
    }

    .btn-action-dropdown:hover {
        background-color: #e5e7eb !important;
        color: #111827 !important;
    }

    .btn-action-dropdown::after {
        display: none !important; /* Hide default caret */
    }

    /* Prevent table responsive container from clipping the dropdown menu */
    .table-responsive {
        overflow: visible !important;
    }
</style>
@endonce

<script>
    $(document).ready(function() {
        // Remove any duplicate event handlers before binding to prevent double firing
        $(document).off('click', '.status-update-btn');

        $(document).on('click', '.status-update-btn', function(e) {
            e.preventDefault();

            let orderId = $(this).data('id');
            let status = $(this).data('status');
            let badge = $('#status-badge-' + orderId);

            $.ajax({
                url: "/admin/orders/status",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: orderId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        
                        badge.text(response.status_text);
                        
                        $('#dropdown-menu-' + response.order_id).html(response.new_dropdown);
                        
                        badge.removeClass('status-new status-hold status-pending status-approved status-packaging status-incourier status-in_courier status-delivered status-cancel status-canceled status-returned');
                        badge.addClass('status-' + response.status);

                        
                        if (response.consignment_id) {
                            $('#courier-info-' + response.order_id).html(`
                                <span class="badge badge-light text-dark font-weight-bold" style="font-size: 11px; padding: 5px 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-family: monospace; display: inline-block;">
                                    Invoice: #LM-${response.order_id}
                                </span>
                                <div class="d-flex align-items-center" style="gap: 4px;">
                                    <span class="badge badge-info text-white font-weight-bold" style="font-size: 11px; padding: 5px 8px; border-radius: 6px; background-color: #0284c7; border: 1px solid #0284c7; font-family: monospace; display: inline-block;">
                                        Courier: ${response.consignment_id}
                                    </span>
                                    <button type="button" class="btn btn-xs btn-outline-info track-courier-btn" data-id="${response.order_id}" style="padding: 2px 5px; border-radius: 4px; cursor: pointer;" title="Track Order">
                                        <i class="fa-solid fa-magnifying-glass" style="font-size: 10px;"></i>
                                    </button>
                                </div>
                            `);
                        } else {
                            $('#courier-info-' + response.order_id).html(`
                                <span class="badge badge-light text-dark font-weight-bold" style="font-size: 11px; padding: 5px 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-family: monospace; display: inline-block;">
                                    Invoice: #LM-${response.order_id}
                                </span>
                                <span class="badge badge-light text-muted font-weight-normal" style="font-size: 11px; padding: 5px 8px; border-radius: 6px; border: 1px solid #e2e8f0; font-family: monospace; display: inline-block;">
                                    Courier: N/A
                                </span>
                            `);
                        }

                        Toast.fire({
                            icon: 'success',
                            title: 'Status updated to ' + response.status_text
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error: Could not update status'
                    });
                }
            });
        });
    });
</script>
