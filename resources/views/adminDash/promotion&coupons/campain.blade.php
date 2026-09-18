@extends('layouts.Backend.master')

@section('title', 'Campaign Management')

@section('content')
<div class="container-fluid px-2 px-md-4 py-3">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-800 text-dark mb-1 d-flex align-items-center">
                <span class="campaign-header-icon me-2">
                    <i class="fa-solid fa-fire text-white"></i>
                </span>
                Campaign Management
            </h4>
            <p class="text-muted fs-13 mb-0">Create, manage, and monitor your promotional sales campaigns.</p>
        </div>
        <div>
            <button id="openCreateModalBtn" class="btn btn-primary rounded-pill px-4 py-2 fw-600 shadow-sm d-flex align-items-center">
                <i class="fa-solid fa-plus me-2"></i> Add New Campaign
            </button>
        </div>
    </div>

    {{-- Stats Cards Row --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="campaign-stat-card stat-total shadow-sm">
                <div class="stat-content">
                    <span class="stat-label">Total Campaigns</span>
                    <h3 class="stat-number">{{ $totalCampaigns ?? count($campaigns ?? []) }}</h3>
                    <span class="stat-subtext"><i class="fa-solid fa-layer-group me-1"></i> All created</span>
                </div>
                <div class="stat-icon-wrapper icon-blue">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="campaign-stat-card stat-active shadow-sm">
                <div class="stat-content">
                    <span class="stat-label">Active Now</span>
                    <h3 class="stat-number text-success">{{ $activeCampaigns ?? 0 }}</h3>
                    <span class="stat-subtext text-success"><i class="fa-solid fa-circle-dot me-1"></i> Running campaigns</span>
                </div>
                <div class="stat-icon-wrapper icon-green">
                    <i class="fa-solid fa-bolt"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="campaign-stat-card stat-upcoming shadow-sm">
                <div class="stat-content">
                    <span class="stat-label">Upcoming</span>
                    <h3 class="stat-number text-warning">{{ $upcomingCampaigns ?? 0 }}</h3>
                    <span class="stat-subtext text-warning"><i class="fa-solid fa-clock me-1"></i> Scheduled soon</span>
                </div>
                <div class="stat-icon-wrapper icon-orange">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="campaign-stat-card stat-expired shadow-sm">
                <div class="stat-content">
                    <span class="stat-label">Expired / Ended</span>
                    <h3 class="stat-number text-danger">{{ $expiredCampaigns ?? 0 }}</h3>
                    <span class="stat-subtext text-danger"><i class="fa-solid fa-calendar-xmark me-1"></i> Completed</span>
                </div>
                <div class="stat-icon-wrapper icon-red">
                    <i class="fa-solid fa-flag-checkered"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Campaigns Table Card --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        {{-- Card Header & Filter --}}
        <div class="card-header bg-white py-3 px-4 d-flex flex-wrap justify-content-between align-items-center border-bottom" style="gap: 12px;">
            <div class="d-flex align-items-center" style="gap: 8px;">
                <span class="fw-700 text-dark fs-16"><i class="fa-solid fa-list-check text-primary mr-2"></i> All Campaigns</span>
                <span class="badge badge-light text-muted px-2 py-1 fs-12 fw-600 border" style="border-radius: 20px;">{{ count($campaigns ?? []) }} Total</span>
            </div>
            <div class="campaign-filter-wrapper d-flex align-items-center flex-wrap">
                <div class="campaign-search-input-box position-relative mr-2 mb-1 mb-sm-0">
                    <i class="fa-solid fa-magnifying-glass campaign-search-icon"></i>
                    <input type="text" id="campaignSearch" class="campaign-filter-input" placeholder="Search campaign name...">
                </div>
                <div class="campaign-status-select-box">
                    <select id="statusFilter" class="campaign-filter-select">
                        <option value="all">All Status</option>
                        <option value="active">Active Now</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Table Body --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="campaignsTable">
                    <thead class="bg-light-subtle text-uppercase fs-11 text-muted fw-700">
                        <tr>
                            <th class="text-center py-3" style="width: 48px; vertical-align: middle;">
                                <input type="checkbox" id="selectAllCampaigns" class="campaign-table-checkbox">
                            </th>
                            <th class="py-3" style="min-width: 220px;">Campaign Details</th>
                            <th class="py-3 text-center" style="min-width: 140px;">Discount</th>
                            <th class="py-3 text-center" style="min-width: 120px;">Products</th>
                            <th class="py-3 text-center" style="min-width: 180px;">Timeline</th>
                            <th class="py-3 text-center" style="min-width: 110px;">Status</th>
                            <th class="pe-4 py-3 text-end" style="min-width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="campaignTableBody">
                        @forelse ($campaigns as $campaign)
                            @php
                                $now = now();
                                $startDate = \Carbon\Carbon::parse($campaign->start_date);
                                $endDate = \Carbon\Carbon::parse($campaign->end_date);
                                
                                $isLive = ($campaign->status == '1' && $now->between($startDate, $endDate));
                                $isUpcoming = ($startDate > $now);
                                $isExpired = ($endDate < $now);

                                $productCount = 0;
                                if (!empty($campaign->product_ids)) {
                                    $decoded = json_decode($campaign->product_ids, true);
                                    if (is_array($decoded)) {
                                        $productCount = count($decoded);
                                    } else {
                                        $productCount = count(array_filter(explode(',', $campaign->product_ids)));
                                    }
                                }
                            @endphp
                            <tr class="campaign-row" 
                                data-name="{{ strtolower($campaign->name) }}"
                                data-status="{{ $isLive ? 'active' : ($isUpcoming ? 'upcoming' : 'expired') }}"
                                id="campaign-row-{{ $campaign->id }}">
                                
                                {{-- Checkbox --}}
                                <td class="text-center" style="width: 48px; vertical-align: middle;">
                                    <input type="checkbox" class="campaign-table-checkbox campaign-checkbox" value="{{ $campaign->id }}">
                                </td>

                                {{-- Image & Info --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="campaign-thumb-wrapper me-3">
                                            @if (!empty($campaign->image) && file_exists(public_path('Uploads/' . $campaign->image)))
                                                <img src="{{ asset('Uploads/' . $campaign->image) }}" alt="{{ $campaign->name }}" class="campaign-thumb">
                                            @else
                                                <div class="campaign-thumb-placeholder">
                                                    <i class="fa-solid fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="fw-700 text-dark mb-0 fs-14">{{ $campaign->name }}</h6>
                                            <span class="badge bg-light text-secondary border px-2 py-0 rounded fs-11 mt-1">
                                                /{{ $campaign->slug }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Discount --}}
                                <td class="text-center">
                                    @if ($campaign->discount_type == 'percentage' || $campaign->discount_type == 'percent')
                                        <span class="badge badge-discount-percent">
                                            <i class="fa-solid fa-percent me-1"></i> {{ $campaign->discount_amount ?? $campaign->discount }}% OFF
                                        </span>
                                    @else
                                        <span class="badge badge-discount-flat">
                                            ৳{{ $campaign->discount_amount ?? $campaign->discount }} OFF
                                        </span>
                                    @endif
                                </td>

                                {{-- Products Included --}}
                                <td class="text-center">
                                    <span class="badge bg-light-primary text-primary px-3 py-1 rounded-pill fw-600 fs-12 border border-primary-subtle">
                                        <i class="fa-solid fa-box-open me-1"></i> {{ $productCount }} {{ Str::plural('Product', $productCount) }}
                                    </span>
                                </td>

                                {{-- Dates / Timeline --}}
                                <td class="text-center">
                                    <div class="timeline-info">
                                        <div class="fs-12 text-dark fw-600 mb-1">
                                            {{ $startDate->format('d M, Y') }} — {{ $endDate->format('d M, Y') }}
                                        </div>
                                        @if ($isLive)
                                            <span class="badge badge-pulse-active">
                                                <span class="pulse-dot"></span> Live Now
                                            </span>
                                        @elseif ($isUpcoming)
                                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1 fs-11 fw-600">
                                                <i class="fa-regular fa-clock me-1"></i> Starts in {{ $now->diffForHumans($startDate, true) }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-11 fw-600">
                                                <i class="fa-solid fa-circle-xmark me-1"></i> Ended
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Status Toggle Switch --}}
                                <td class="text-center">
                                    <label class="modern-switch mb-0">
                                        <input type="checkbox" class="campaign-status-toggle" data-id="{{ $campaign->id }}" {{ $campaign->status == '1' ? 'checked' : '' }}>
                                        <span class="modern-slider"></span>
                                    </label>
                                </td>

                                {{-- Action Buttons --}}
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <button class="btn btn-icon-action btn-action-edit editCampaignBtn" 
                                                data-id="{{ $campaign->id }}" 
                                                title="Edit Campaign">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn btn-icon-action btn-action-delete deleteCampaignBtn" 
                                                data-id="{{ $campaign->id }}" 
                                                data-name="{{ $campaign->name }}"
                                                title="Delete Campaign">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyCampaignRow">
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state-box py-4">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fa-solid fa-bullhorn fa-3x text-muted opacity-50"></i>
                                        </div>
                                        <h5 class="fw-700 text-dark mb-1">No Campaigns Created Yet</h5>
                                        <p class="text-muted fs-13 mb-3">Promote your products by creating an exciting discount campaign today.</p>
                                        <button class="btn btn-primary btn-sm rounded-pill px-4 fw-600 shadow-sm" onclick="$('#openCreateModalBtn').click();">
                                            <i class="fa-solid fa-plus me-1"></i> Create First Campaign
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modern Campaign Create & Edit Modal --}}
<div id="campaignModal" class="campaign-modal-overlay">
    <div class="campaign-modal-dialog">
        <div class="campaign-modal-content">
            {{-- Modal Header --}}
            <div class="campaign-modal-header">
                <div class="d-flex align-items-center">
                    <div class="modal-header-icon me-3">
                        <i class="fa-solid fa-fire text-primary" id="modalHeaderIcon"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-800 text-dark mb-0" id="campaignModalTitle">Create New Campaign</h5>
                        <p class="text-muted fs-12 mb-0" id="campaignModalSubtitle">Fill in the details below to launch your promotion.</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" id="closeCampaignModalBtn">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Modal Form --}}
            <form id="campaignForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="campaign_id" value="">

                <div class="campaign-modal-body">
                    {{-- Banner Cover Image Upload --}}
                    <div class="mb-4">
                        <label class="form-label-custom">
                            Campaign Banner / Cover Image
                            <span class="text-muted fw-normal fs-11">(Recommended: 1200 x 400 px, Max: 4MB)</span>
                        </label>
                        <div class="image-upload-container" id="imageUploadArea">
                            <input type="file" name="image" id="campaignImageInput" class="d-none" accept="image/*">
                            
                            {{-- Placeholder state --}}
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <div class="upload-icon-circle mb-2">
                                    <i class="fa-solid fa-cloud-arrow-up text-primary fs-20"></i>
                                </div>
                                <span class="fw-700 fs-13 text-dark d-block">Click to upload or drag and drop</span>
                                <span class="text-muted fs-11">PNG, JPG, WEBP formats supported</span>
                            </div>

                            {{-- Preview state --}}
                            <div class="upload-preview d-none" id="uploadPreviewBox">
                                <img src="" alt="Preview" id="campaignImagePreview">
                                <button type="button" class="btn-remove-preview" id="removeImageBtn" title="Remove Image">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Campaign Name --}}
                    <div class="mb-3">
                        <label class="form-label-custom" for="campaign_name">
                            Campaign Title <span class="text-danger">*</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fa-solid fa-heading input-icon text-muted"></i>
                            <input type="text" name="name" id="campaign_name" class="form-control modern-input" 
                                   placeholder="e.g. Eid Mega Sale 2026, Summer Clearance" required>
                        </div>
                    </div>

                    {{-- Dates (Start & End Date) --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom" for="campaign_start_date">
                                Start Date <span class="text-danger">*</span>
                            </label>
                            <div class="input-with-icon">
                                <i class="fa-regular fa-calendar-plus input-icon text-muted"></i>
                                <input type="datetime-local" name="start_date" id="campaign_start_date" 
                                       class="form-control modern-input" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom" for="campaign_end_date">
                                End Date <span class="text-danger">*</span>
                            </label>
                            <div class="input-with-icon">
                                <i class="fa-regular fa-calendar-check input-icon text-muted"></i>
                                <input type="datetime-local" name="end_date" id="campaign_end_date" 
                                       class="form-control modern-input" required>
                            </div>
                        </div>
                    </div>

                    {{-- Discount Settings --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom" for="campaign_discount_type">
                                Discount Type <span class="text-danger">*</span>
                            </label>
                            <select name="discount_type" id="campaign_discount_type" class="form-select modern-input" required>
                                <option value="percentage">Percentage Discount (%)</option>
                                <option value="flat">Fixed Flat Amount (৳)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom" for="campaign_discount_amount">
                                Discount Value <span class="text-danger">*</span>
                            </label>
                            <div class="input-group modern-input-group">
                                <span class="input-group-text bg-light border-0 fw-700 text-primary" id="discountTypeAddon">%</span>
                                <input type="number" step="0.01" min="0" name="discount_amount" id="campaign_discount_amount" 
                                       class="form-control modern-input border-0" placeholder="e.g. 15" required>
                            </div>
                        </div>
                    </div>

                    {{-- Products Multi-select --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label-custom mb-0" for="campaign_products">
                                Included Products
                                <span class="text-muted fw-normal fs-11">(Select which products get this campaign deal)</span>
                            </label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-link btn-sm p-0 fs-11 text-decoration-none fw-600 text-primary" id="selectAllProductsBtn">Select All</button>
                                <span class="text-muted fs-11">|</span>
                                <button type="button" class="btn btn-link btn-sm p-0 fs-11 text-decoration-none fw-600 text-muted" id="clearAllProductsBtn">Clear</button>
                            </div>
                        </div>
                        <select name="products[]" id="campaign_products" class="form-select select2-products" multiple="multiple" style="width: 100%;">
                            @foreach ($products as $prod)
                                @php
                                    $prodImg = ($prod->firstImage && !empty($prod->firstImage->image) && file_exists(public_path('Uploads/' . $prod->firstImage->image)))
                                        ? asset('Uploads/' . $prod->firstImage->image)
                                        : asset('favicon.png');
                                @endphp
                                <option value="{{ $prod->id }}" 
                                        data-price="{{ $prod->new_price }}" 
                                        data-image="{{ $prodImg }}"
                                        title="{{ $prod->title }}">
                                    {{ Str::limit($prod->title, 35) }} (Code: {{ $prod->code }} — ৳{{ $prod->new_price }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="mb-2">
                        <label class="form-label-custom" for="campaign_description">
                            Campaign Description / Terms <span class="text-muted fw-normal fs-11">(Optional)</span>
                        </label>
                        <textarea name="description" id="campaign_description" rows="2" 
                                  class="form-control modern-input" placeholder="Terms, highlights, or promo message..."></textarea>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="campaign-modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-600" id="cancelCampaignModalBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600 shadow-sm" id="saveCampaignSubmitBtn">
                        <span class="normal-state d-flex align-items-center">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Save Campaign
                        </span>
                        <span class="loading-state d-none">
                            <i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Custom CSS for Premium Design --}}
<style>
    /* Table Checkbox */
    .campaign-table-checkbox {
        width: 17px !important;
        height: 17px !important;
        cursor: pointer;
        position: static !important;
        margin: 0 auto !important;
        display: block !important;
        accent-color: #3b82f6;
        border-radius: 4px;
        vertical-align: middle;
    }

    /* Header Icon */
    .campaign-header-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #f97316 0%, #ef4444 100%);
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    /* Stat Cards */
    .campaign-stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.7);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .campaign-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06) !important;
    }
    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        display: block;
        margin-bottom: 4px;
    }
    .stat-number {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.1;
    }
    .stat-subtext {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
    }
    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .icon-blue { background: rgba(59, 130, 246, 0.12); color: #2563eb; }
    .icon-green { background: rgba(16, 185, 129, 0.12); color: #059669; }
    .icon-orange { background: rgba(245, 158, 11, 0.12); color: #d97706; }
    .icon-red { background: rgba(239, 68, 68, 0.12); color: #dc2626; }

    /* Search & Filter Controls */
    .campaign-filter-wrapper {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }
    .campaign-search-input-box {
        position: relative;
        min-width: 250px;
    }
    .campaign-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
        z-index: 2;
    }
    .campaign-filter-input {
        width: 100%;
        height: 38px;
        padding: 6px 16px 6px 38px !important;
        font-size: 13px;
        color: #1e293b;
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 30px;
        outline: none;
        transition: all 0.2s ease;
    }
    .campaign-filter-input:focus {
        background-color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }
    .campaign-filter-select {
        height: 38px;
        padding: 6px 34px 6px 16px !important;
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 30px;
        outline: none;
        cursor: pointer;
        min-width: 140px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        transition: all 0.2s ease;
    }
    .campaign-filter-select:focus {
        background-color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    /* Thumbnails */
    .campaign-thumb-wrapper {
        width: 64px;
        height: 48px;
        border-radius: 10px;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .campaign-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .campaign-thumb-placeholder {
        color: #94a3b8;
        font-size: 18px;
    }

    /* Badges */
    .badge-discount-percent {
        background: rgba(249, 115, 22, 0.12);
        color: #ea580c;
        border: 1px solid rgba(249, 115, 22, 0.25);
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 12px;
    }
    .badge-discount-flat {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.25);
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 12px;
    }
    .badge-pulse-active {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
        font-weight: 700;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pulse-dot {
        width: 7px;
        height: 7px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseEffect 1.8s infinite;
    }
    @keyframes pulseEffect {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* iOS-Style Modern Switch */
    .modern-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .modern-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .modern-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .3s;
        border-radius: 24px;
    }
    .modern-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .modern-switch input:checked + .modern-slider {
        background-color: #10b981;
    }
    .modern-switch input:checked + .modern-slider:before {
        transform: translateX(20px);
    }

    /* Action Buttons */
    .btn-icon-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        transition: all 0.2s ease;
    }
    .btn-action-edit {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }
    .btn-action-edit:hover {
        background: #2563eb;
        color: #ffffff;
    }
    .btn-action-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    .btn-action-delete:hover {
        background: #dc2626;
        color: #ffffff;
    }

    /* Custom Modal Overlay */
    .campaign-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(5px);
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s ease;
        padding: 15px;
    }
    .campaign-modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }
    .campaign-modal-dialog {
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transform: scale(0.95);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .campaign-modal-overlay.show .campaign-modal-dialog {
        transform: scale(1);
    }
    .campaign-modal-content {
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }
    .campaign-modal-header {
        padding: 18px 24px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-header-icon {
        width: 40px;
        height: 40px;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .btn-close-modal {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-close-modal:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .campaign-modal-body {
        padding: 24px;
        overflow-y: auto;
    }
    .campaign-modal-footer {
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Form Styles */
    .form-label-custom {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }
    .modern-input {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 13px;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    .modern-input:focus {
        background-color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }
    .input-with-icon {
        position: relative;
    }
    .input-with-icon .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        pointer-events: none;
    }
    .input-with-icon input {
        padding-left: 38px;
    }
    .modern-input-group {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        overflow: hidden;
        background: #f8fafc;
    }
    .modern-input-group:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        background: #ffffff;
    }

    /* Image Upload Box */
    .image-upload-container {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .image-upload-container:hover {
        border-color: #3b82f6;
        background: #f1f5f9;
    }
    .upload-placeholder {
        padding: 24px;
        text-align: center;
    }
    .upload-icon-circle {
        width: 44px;
        height: 44px;
        background: #ffffff;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .upload-preview {
        position: relative;
        width: 100%;
        max-height: 180px;
        overflow: hidden;
    }
    .upload-preview img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .btn-remove-preview {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-remove-preview:hover {
        background: #dc2626;
        transform: scale(1.05);
    }

    /* Select2 Tweaks */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        min-height: 42px !important;
        padding: 4px 8px !important;
        background-color: #f8fafc !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #3b82f6 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12) !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e0f2fe !important;
        border: 1px solid #bae6fd !important;
        color: #0284c7 !important;
        border-radius: 6px !important;
        padding: 2px 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }
</style>

{{-- Scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Select2 for products with image preview
        function formatProductOption(item) {
            if (!item.id) {
                return item.text;
            }
            const imgUrl = $(item.element).data('image') || '{{ asset("favicon.png") }}';
            const $item = $(
                '<div class="d-flex align-items-center py-1">' +
                    '<img src="' + imgUrl + '" class="rounded me-2 border flex-shrink-0" style="width: 34px; height: 34px; object-fit: cover;" onerror="this.src=\'{{ asset("favicon.png") }}\'">' +
                    '<div>' +
                        '<span class="fw-600 text-dark d-block fs-13 lh-sm">' + item.text + '</span>' +
                    '</div>' +
                '</div>'
            );
            return $item;
        }

        function formatProductSelection(item) {
            if (!item.id) {
                return item.text;
            }
            const imgUrl = $(item.element).data('image') || '{{ asset("favicon.png") }}';
            const $item = $(
                '<span class="d-inline-flex align-items-center">' +
                    '<img src="' + imgUrl + '" class="rounded me-1 border flex-shrink-0" style="width: 18px; height: 18px; object-fit: cover;" onerror="this.src=\'{{ asset("favicon.png") }}\'">' +
                    '<span>' + item.text + '</span>' +
                '</span>'
            );
            return $item;
        }

        if ($.fn.select2) {
            $('.select2-products').select2({
                placeholder: "Search and select products for this campaign...",
                allowClear: true,
                dropdownParent: $('#campaignModal'),
                templateResult: formatProductOption,
                templateSelection: formatProductSelection,
                escapeMarkup: function(m) { return m; }
            });
        }

        // Modal Controls
        const modal = document.getElementById('campaignModal');
        const openModalBtn = document.getElementById('openCreateModalBtn');
        const closeModalBtn = document.getElementById('closeCampaignModalBtn');
        const cancelModalBtn = document.getElementById('cancelCampaignModalBtn');
        const form = document.getElementById('campaignForm');
        const submitBtn = document.getElementById('saveCampaignSubmitBtn');

        function openModal(isEdit = false) {
            if (!isEdit) {
                form.reset();
                $('#campaign_id').val('');
                $('#campaignModalTitle').text('Create New Campaign');
                $('#campaignModalSubtitle').text('Fill in the details below to launch your promotion.');
                $('#uploadPreviewBox').addClass('d-none');
                $('#uploadPlaceholder').removeClass('d-none');
                $('#campaignImagePreview').attr('src', '');
                $('#discountTypeAddon').text('%');
                if ($.fn.select2) {
                    $('.select2-products').val(null).trigger('change');
                }
            }
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }

        openModalBtn.addEventListener('click', () => openModal(false));
        closeModalBtn.addEventListener('click', closeModal);
        cancelModalBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Image Upload Trigger & Preview
        const imageUploadArea = document.getElementById('imageUploadArea');
        const imageInput = document.getElementById('campaignImageInput');
        const imagePreview = document.getElementById('campaignImagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const uploadPreviewBox = document.getElementById('uploadPreviewBox');
        const removeImageBtn = document.getElementById('removeImageBtn');

        imageUploadArea.addEventListener('click', function (e) {
            if (e.target.closest('#removeImageBtn')) return;
            imageInput.click();
        });

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    uploadPlaceholder.classList.add('d-none');
                    uploadPreviewBox.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        removeImageBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            imageInput.value = '';
            imagePreview.src = '';
            uploadPreviewBox.classList.add('d-none');
            uploadPlaceholder.classList.remove('d-none');
        });

        // Discount Type Addon change
        const discountTypeSelect = document.getElementById('campaign_discount_type');
        const discountAddon = document.getElementById('discountTypeAddon');
        discountTypeSelect.addEventListener('change', function () {
            if (this.value === 'percentage') {
                discountAddon.textContent = '%';
            } else {
                discountAddon.textContent = '৳';
            }
        });

        // Select All / Clear All Products
        $('#selectAllProductsBtn').on('click', function () {
            $("#campaign_products > option").prop("selected", "selected");
            $("#campaign_products").trigger("change");
        });

        $('#clearAllProductsBtn').on('click', function () {
            $("#campaign_products").val(null).trigger("change");
        });

        // Edit Campaign Handler
        $(document).on('click', '.editCampaignBtn', function () {
            const id = $(this).data('id');
            const editUrl = "{{ route('campaign.edit', ':id') }}".replace(':id', id);

            // Fetch campaign data
            $.ajax({
                url: editUrl,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.success && res.campaign) {
                        const camp = res.campaign;
                        $('#campaign_id').val(camp.id);
                        $('#campaign_name').val(camp.name);
                        
                        // Format dates for datetime-local
                        if (camp.start_date) {
                            const sDate = new Date(camp.start_date);
                            $('#campaign_start_date').val(sDate.toISOString().slice(0, 16));
                        }
                        if (camp.end_date) {
                            const eDate = new Date(camp.end_date);
                            $('#campaign_end_date').val(eDate.toISOString().slice(0, 16));
                        }

                        $('#campaign_discount_type').val(camp.discount_type || 'percentage').trigger('change');
                        $('#campaign_discount_amount').val(camp.discount_amount || camp.discount || '');
                        $('#campaign_description').val(camp.description || '');

                        // Image preview if exists
                        if (camp.image) {
                            imagePreview.src = "{{ asset('Uploads') }}/" + camp.image;
                            uploadPlaceholder.classList.add('d-none');
                            uploadPreviewBox.classList.remove('d-none');
                        } else {
                            uploadPreviewBox.classList.add('d-none');
                            uploadPlaceholder.classList.remove('d-none');
                        }

                        // Populate selected products
                        if ($.fn.select2 && res.selected_products) {
                            $('#campaign_products').val(res.selected_products).trigger('change');
                        }

                        $('#campaignModalTitle').text('Edit Campaign');
                        $('#campaignModalSubtitle').text('Update settings and promotion parameters for this campaign.');
                        openModal(true);
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Unable to load campaign details.', 'error');
                }
            });
        });

        // Form Submit Handler (Create & Update)
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const isUpdate = Boolean($('#campaign_id').val());
            const postUrl = isUpdate ? "{{ route('campaign.update') }}" : "{{ route('campaign.store') }}";
            const formData = new FormData(form);

            // Button loading state
            submitBtn.disabled = true;
            submitBtn.querySelector('.normal-state').classList.add('d-none');
            submitBtn.querySelector('.loading-state').classList.remove('d-none');

            $.ajax({
                url: postUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (res) {
                    if (res.success) {
                        closeModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function (xhr) {
                    let errMsg = 'Something went wrong. Please check your form.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        errMsg = Object.values(errors).flat().join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errMsg
                    });
                },
                complete: function () {
                    submitBtn.disabled = false;
                    submitBtn.querySelector('.normal-state').classList.remove('d-none');
                    submitBtn.querySelector('.loading-state').classList.add('d-none');
                }
            });
        });

        // Status Toggle Handler
        $(document).on('change', '.campaign-status-toggle', function () {
            const id = $(this).data('id');
            const toggle = $(this);
            
            $.ajax({
                url: "{{ route('campaign.status') }}",
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                    }
                },
                error: function () {
                    toggle.prop('checked', !toggle.prop('checked'));
                    Swal.fire('Error', 'Unable to update status.', 'error');
                }
            });
        });

        // Delete Campaign Handler
        $(document).on('click', '.deleteCampaignBtn', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const deleteUrl = "{{ route('campaign.delete', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Delete Campaign?',
                html: `Are you sure you want to delete <strong>"${name}"</strong>?<br><small class="text-danger">This action cannot be undone.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa-solid fa-trash-can me-1"></i> Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function (res) {
                            if (res.success) {
                                $(`#campaign-row-${id}`).fadeOut(350, function () {
                                    $(this).remove();
                                    if ($('.campaign-row').length === 0) {
                                        location.reload();
                                    }
                                });
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Failed to delete campaign.', 'error');
                        }
                    });
                }
            });
        });

        // Search Filter
        $('#campaignSearch').on('keyup', function () {
            const query = $(this).val().toLowerCase().trim();
            filterTable();
        });

        // Status Filter
        $('#statusFilter').on('change', function () {
            filterTable();
        });

        function filterTable() {
            const query = $('#campaignSearch').val().toLowerCase().trim();
            const status = $('#statusFilter').val();

            $('.campaign-row').each(function () {
                const name = $(this).data('name');
                const rowStatus = $(this).data('status');

                const matchesQuery = !query || name.includes(query);
                const matchesStatus = (status === 'all') || (rowStatus === status);

                if (matchesQuery && matchesStatus) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Select All Checkboxes
        $('#selectAllCampaigns').on('change', function () {
            $('.campaign-checkbox').prop('checked', this.checked);
        });
    });
</script>
@endsection