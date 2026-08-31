@extends('layouts.Frontend.master')

@php
    $webConfig = \Illuminate\Support\Facades\Cache::rememberForever('boot_general_web_settings_map', function () {
        return \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
    });
    $storeName = !empty($webConfig['web_name']) ? $webConfig['web_name'] : 'Looksmen';
    $contactPhone = !empty($webConfig['contact_phone']) ? $webConfig['contact_phone'] : '01568482005';
    $contactEmail = !empty($webConfig['contact_email']) ? $webConfig['contact_email'] : 'support@looksmen.com';
    $contactAddress = !empty($webConfig['contact_address']) ? $webConfig['contact_address'] : 'Dhaka, Bangladesh';
    $faqsList = (isset($faqs) && (is_iterable($faqs) || $faqs instanceof \Illuminate\Support\Collection)) ? $faqs : \App\Models\Faq::where('status', 1)->orderBy('order', 'asc')->get();
    $faqCategoriesList = (isset($faqCategories) && is_array($faqCategories)) ? $faqCategories : \App\Models\Faq::categories();
@endphp

@section('title', 'Help Center & Customer Support - ' . $storeName)

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .help-page-wrapper {
        font-family: 'Outfit', sans-serif !important;
        background-color: #f8fafc;
        color: #0f172a;
        padding-bottom: 4rem;
    }

    /* Hero Banner */
    .help-hero-banner {
        background: linear-gradient(135deg, #312e81 0%, #4338ca 45%, #4f46e5 100%);
        padding: 4.5rem 1rem 4rem;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        text-align: center;
    }
    .help-hero-banner::before {
        content: '';
        position: absolute;
        top: -40%;
        left: 50%;
        transform: translateX(-50%);
        width: 700px;
        height: 700px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .help-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 0.4rem 1.1rem;
        border-radius: 9999px;
        font-size: 0.88rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .help-hero-title {
        font-size: 2.75rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }
    .help-hero-subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.85);
        max-width: 650px;
        margin: 0 auto 2.25rem;
        line-height: 1.6;
    }

    /* Live Search Input */
    .help-search-container {
        max-width: 680px;
        margin: 0 auto;
        position: relative;
    }
    .help-search-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
        background: #ffffff;
        border-radius: 16px;
        padding: 0.4rem 0.5rem 0.4rem 1.4rem;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.25), 0 1px 3px rgba(0,0,0,0.1);
        border: 2px solid transparent;
        transition: all 0.25s ease;
    }
    .help-search-input-wrap:focus-within {
        border-color: #a5b4fc;
        box-shadow: 0 20px 45px -5px rgba(79, 70, 229, 0.35);
    }
    .help-search-icon {
        color: #6366f1;
        font-size: 1.35rem;
        margin-right: 0.85rem;
    }
    .help-search-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 1.05rem;
        font-weight: 500;
        color: #0f172a;
        background: transparent;
    }
    .help-search-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }
    .help-search-clear {
        background: #f1f5f9;
        border: none;
        color: #64748b;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-right: 0.5rem;
        transition: all 0.2s;
    }
    .help-search-clear:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Section Headers */
    .help-section-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
        margin-bottom: 0.4rem;
    }
    .help-section-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 1.75rem;
    }

    /* Quick Action Cards */
    .self-service-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
        margin-top: -2.25rem;
        position: relative;
        z-index: 10;
    }
    .service-action-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.5rem 1.35rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-decoration: none !important;
        color: inherit !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .service-action-card:hover {
        transform: translateY(-5px);
        border-color: #cbd5e1;
        box-shadow: 0 20px 30px -10px rgba(79, 70, 229, 0.12), 0 10px 15px -5px rgba(0, 0, 0, 0.04);
    }
    .service-card-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1.1rem;
        transition: transform 0.3s ease;
    }
    .service-action-card:hover .service-card-icon {
        transform: scale(1.1) rotate(4deg);
    }
    .service-card-title {
        font-weight: 700;
        font-size: 1.05rem;
        color: #0f172a;
        margin-bottom: 0.35rem;
        line-height: 1.3;
    }
    .service-card-desc {
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 0;
    }
    .service-card-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Icon Variations */
    .icon-indigo { background: #e0e7ff; color: #4338ca; }
    .icon-blue { background: #dbeafe; color: #1d4ed8; }
    .icon-emerald { background: #d1fae5; color: #047857; }
    .icon-amber { background: #fef3c7; color: #b45309; }
    .icon-purple { background: #f3e8ff; color: #7e22ce; }
    .icon-rose { background: #ffe4e6; color: #be123c; }
    .icon-teal { background: #ccfbf1; color: #0f766e; }
    .icon-cyan { background: #cffafe; color: #0e7490; }

    /* Category Filter Pills */
    .category-filter-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        margin-bottom: 2rem;
    }
    .filter-pill-btn {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 0.6rem 1.25rem;
        border-radius: 9999px;
        font-size: 0.92rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .filter-pill-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .filter-pill-btn.active {
        background: #4f46e5;
        color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }

    /* FAQ Accordion Styling */
    .faq-accordion-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        margin-bottom: 0.9rem;
        overflow: hidden;
        transition: all 0.25s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .faq-accordion-item:hover {
        border-color: #cbd5e1;
    }
    .faq-accordion-header {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        background: #ffffff;
        transition: background 0.2s;
    }
    .faq-accordion-header:hover {
        background: #fafbfc;
    }
    .faq-question-wrap {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .faq-question-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .faq-question-text {
        font-weight: 700;
        font-size: 1.02rem;
        color: #1e293b;
        margin: 0;
    }
    .faq-toggle-icon {
        color: #94a3b8;
        font-size: 1rem;
        transition: transform 0.3s ease, color 0.2s ease;
    }
    .faq-accordion-item.open .faq-toggle-icon {
        transform: rotate(180deg);
        color: #4f46e5;
    }
    .faq-accordion-body {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fafbfc;
        border-top: 1px solid #f1f5f9;
    }
    .faq-accordion-content {
        padding: 1.35rem 1.5rem;
        color: #475569;
        font-size: 0.94rem;
        line-height: 1.7;
    }
    .faq-accordion-item.open .faq-accordion-body {
        max-height: 800px;
    }
    .faq-feedback-row {
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #64748b;
    }
    .feedback-btn-group {
        display: flex;
        gap: 0.5rem;
    }
    .feedback-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.3rem 0.75rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
    }
    .feedback-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    /* Contact Channel Hub */
    .contact-channels-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 2.25rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 90px;
    }
    .contact-channel-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        margin-bottom: 0.9rem;
        text-decoration: none !important;
        color: inherit !important;
        transition: all 0.25s ease;
    }
    .contact-channel-item:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateX(4px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }
    .channel-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .channel-title {
        font-weight: 700;
        font-size: 0.96rem;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .channel-subtitle {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.4;
    }

    /* No search results card */
    .no-faq-results {
        text-align: center;
        padding: 3.5rem 1rem;
        background: #ffffff;
        border-radius: 16px;
        border: 1px dashed #cbd5e1;
        display: none;
    }
    .no-faq-results i {
        font-size: 2.75rem;
        color: #94a3b8;
        margin-bottom: 1rem;
    }

    @media (max-width: 991px) {
        .help-hero-title {
            font-size: 2.1rem;
        }
        .self-service-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
        .contact-channels-card {
            margin-top: 2.5rem;
            position: static;
        }
    }
</style>

<div class="help-page-wrapper">
    
    <!-- HERO SEARCH BANNER -->
    <div class="help-hero-banner">
        <div class="container">
            <div class="help-hero-badge">
                <i class="fa-solid fa-headset text-warning"></i> 24/7 Support & Help Center
            </div>
            <h1 class="help-hero-title">How Can We Help You Today?</h1>
            <p class="help-hero-subtitle">
                Search our knowledge base for answers, track your parcel in real-time, or connect with our support team.
            </p>

            <div class="help-search-container">
                <div class="help-search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass help-search-icon"></i>
                    <input type="text" id="helpSearchInput" class="help-search-input" placeholder="Search keywords (e.g. tracking, bKash, return, warranty)..." autocomplete="off">
                    <button type="button" id="helpSearchClear" class="help-search-clear" title="Clear search">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <!-- SELF SERVICE QUICK ACTION TILES -->
        <div class="self-service-grid mb-5">
            
            <!-- Track Order -->
            <a href="{{ route('front.trackOrder') }}" class="service-action-card">
                <span class="service-card-badge bg-primary text-white">Live</span>
                <div class="service-card-icon icon-indigo">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h3 class="service-card-title">Track My Order</h3>
                <p class="service-card-desc">Check parcel location, courier status, and estimated delivery timeline.</p>
            </a>

            <!-- AI Virtual Assistant -->
            <div onclick="if(typeof toggleAiChat === 'function'){ toggleAiChat(); } else { window.location.href='tel:{{ $contactPhone }}'; }" class="service-action-card" style="cursor: pointer;">
                <span class="service-card-badge bg-success text-white">24/7 Active</span>
                <div class="service-card-icon icon-purple">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <h3 class="service-card-title">AI Virtual Chat</h3>
                <p class="service-card-desc">Instant automated answers, order details lookup, and human escalation.</p>
            </div>

            <!-- Support Tickets / Cases -->
            <a href="@auth {{ route('supportTicket') }} @else {{ route('login') }} @endauth" class="service-action-card">
                <span class="service-card-badge bg-warning text-dark">Tickets</span>
                <div class="service-card-icon icon-amber">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <h3 class="service-card-title">My Support Cases</h3>
                <p class="service-card-desc">View submitted complaints, reply to support agents, or create a ticket.</p>
            </a>

            <!-- Return & Replacement -->
            <div onclick="filterFaqByCategory('returns')" class="service-action-card" style="cursor: pointer;">
                <div class="service-card-icon icon-rose">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <h3 class="service-card-title">Return & Refund</h3>
                <p class="service-card-desc">Read our 7-day hassle-free product return & replacement policy.</p>
            </div>

            <!-- Password & Security -->
            <a href="{{ route('password.request') }}" class="service-action-card">
                <div class="service-card-icon icon-blue">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="service-card-title">Reset Password</h3>
                <p class="service-card-desc">Securely change or recover your customer account password via email.</p>
            </a>

            <!-- Payment Options -->
            <div onclick="filterFaqByCategory('payments')" class="service-action-card" style="cursor: pointer;">
                <div class="service-card-icon icon-emerald">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h3 class="service-card-title">Payment Options</h3>
                <p class="service-card-desc">Information on bKash, SSLCommerz, cards, and Cash on Delivery.</p>
            </div>

            <!-- Vouchers & Deals -->
            <a href="{{ route('front.flashSale') }}" class="service-action-card">
                <div class="service-card-icon icon-teal">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <h3 class="service-card-title">Vouchers & Offers</h3>
                <p class="service-card-desc">Explore active discounts, flash sales, and store promo codes.</p>
            </a>

            <!-- My Account -->
            <a href="@auth {{ route('dashboard') }} @else {{ route('login') }} @endauth" class="service-action-card">
                <div class="service-card-icon icon-cyan">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <h3 class="service-card-title">Manage Account</h3>
                <p class="service-card-desc">Update delivery addresses, manage profile info, and view invoices.</p>
            </a>

        </div>

        <!-- MAIN CONTENT ROW: FAQ & CONTACT CHANNELS -->
        <div class="row pt-3">
            
            <!-- LEFT COLUMN: CATEGORIES & FAQS -->
            <div class="col-lg-8">
                
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <div>
                        <h2 class="help-section-title">Frequently Asked Questions</h2>
                        <p class="help-section-subtitle">Find quick solutions to the most common customer queries.</p>
                    </div>
                </div>

                <!-- CATEGORY PILL FILTER BUTTONS -->
                <div class="category-filter-pills">
                    <button class="filter-pill-btn active" data-cat="all" onclick="filterFaqByCategory('all')">
                        <i class="fa-solid fa-layer-group"></i> All Topics
                    </button>
                    @foreach($faqCategoriesList as $catKey => $catTitle)
                        @php
                            $catIcon = match($catKey) {
                                'shipping' => 'fa-solid fa-truck',
                                'orders' => 'fa-solid fa-bag-shopping',
                                'payments' => 'fa-solid fa-wallet',
                                'returns' => 'fa-solid fa-rotate-left',
                                'account' => 'fa-solid fa-user-shield',
                                default => 'fa-solid fa-circle-question',
                            };
                        @endphp
                        <button class="filter-pill-btn" data-cat="{{ $catKey }}" onclick="filterFaqByCategory('{{ $catKey }}')">
                            <i class="{{ $catIcon }}"></i> {{ $catTitle }}
                        </button>
                    @endforeach
                </div>

                <!-- FAQ ACCORDION LIST -->
                <div id="faqAccordionContainer">
                    @forelse($faqsList as $faq)
                        @php
                            $faqCat = is_object($faq) ? ($faq->category ?? 'general') : (is_array($faq) ? ($faq['category'] ?? 'general') : 'general');
                            $faqQ = is_object($faq) ? ($faq->question ?? '') : (is_array($faq) ? ($faq['question'] ?? '') : (string)$faq);
                            $faqA = is_object($faq) ? ($faq->answer ?? '') : (is_array($faq) ? ($faq['answer'] ?? '') : '');
                            $faqIcon = match($faqCat) {
                                'shipping' => 'fa-solid fa-truck-fast',
                                'orders' => 'fa-solid fa-bag-shopping',
                                'payments' => 'fa-solid fa-credit-card',
                                'returns' => 'fa-solid fa-rotate-left',
                                'account' => 'fa-solid fa-key',
                                default => 'fa-solid fa-circle-question',
                            };
                        @endphp
                        <div class="faq-accordion-item" data-cat="{{ $faqCat }}">
                            <div class="faq-accordion-header" onclick="toggleFaqAccordion(this)">
                                <div class="faq-question-wrap">
                                    <div class="faq-question-icon"><i class="{{ $faqIcon }}"></i></div>
                                    <h4 class="faq-question-text">{{ $faqQ }}</h4>
                                </div>
                                <i class="fa-solid fa-chevron-down faq-toggle-icon"></i>
                            </div>
                            <div class="faq-accordion-body">
                                <div class="faq-accordion-content">
                                    {!! $faqA !!}
                                    <div class="faq-feedback-row">
                                        <span>Was this article helpful?</span>
                                        <div class="feedback-btn-group">
                                            <button type="button" class="feedback-btn" onclick="rateHelpful(this, true)"><i class="fa-solid fa-thumbs-up mr-1 text-success"></i> Yes</button>
                                            <button type="button" class="feedback-btn" onclick="rateHelpful(this, false)"><i class="fa-solid fa-thumbs-down mr-1 text-danger"></i> No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted bg-white rounded-16 border">
                            <i class="fa-solid fa-circle-question text-muted mb-2" style="font-size: 36px; opacity: 0.5;"></i>
                            <p class="mb-0 font-weight-bold">No active FAQs at this moment.</p>
                        </div>
                    @endforelse
                </div>

                <!-- NO SEARCH RESULTS FALLBACK -->
                <div id="noFaqResults" class="no-faq-results">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <h4 class="font-weight-bold text-dark mb-1">No matching help articles found</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">Try different keywords or talk directly with our live support agents.</p>
                    <button class="btn btn-primary px-4 py-2 rounded-pill font-weight-bold" onclick="if(typeof toggleAiChat === 'function'){ toggleAiChat(); } else { window.location.href='tel:{{ $contactPhone }}'; }">
                        <i class="fa-solid fa-headset mr-1"></i> Contact Live Agent
                    </button>
                </div>

            </div>

            <!-- RIGHT COLUMN: CONTACT CHANNELS & ESCALATION -->
            <div class="col-lg-4">
                
                <div class="contact-channels-card">
                    <h3 class="font-weight-bold text-dark mb-1" style="font-size: 1.35rem; letter-spacing: -0.3px;">Still Need Assistance?</h3>
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">Our customer success team is available to help resolve your questions.</p>

                    <!-- Channel 1: AI Chat Assistant -->
                    <div onclick="if(typeof toggleAiChat === 'function'){ toggleAiChat(); } else { window.location.href='tel:{{ $contactPhone }}'; }" class="contact-channel-item" style="cursor: pointer;">
                        <div class="channel-icon-wrap icon-purple">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <div>
                            <div class="channel-title">24/7 AI Live Chat</div>
                            <div class="channel-subtitle">Instant response in seconds</div>
                        </div>
                    </div>

                    <!-- Channel 2: Direct Phone Support -->
                    <a href="tel:{{ $contactPhone }}" class="contact-channel-item">
                        <div class="channel-icon-wrap icon-emerald">
                            <i class="fa-solid fa-phone-volume"></i>
                        </div>
                        <div>
                            <div class="channel-title">Helpline: {{ $contactPhone }}</div>
                            <div class="channel-subtitle">9:00 AM - 10:00 PM (Daily)</div>
                        </div>
                    </a>

                    <!-- Channel 3: Support Ticket -->
                    <a href="@auth {{ route('supportTicket') }} @else {{ route('login') }} @endauth" class="contact-channel-item">
                        <div class="channel-icon-wrap icon-amber">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <div>
                            <div class="channel-title">Open Support Ticket</div>
                            <div class="channel-subtitle">Track complaints & escalations</div>
                        </div>
                    </a>

                    <!-- Channel 4: Email Support -->
                    <a href="mailto:{{ $contactEmail }}" class="contact-channel-item">
                        <div class="channel-icon-wrap icon-blue">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <div class="channel-title">Email Us</div>
                            <div class="channel-subtitle">{{ $contactEmail }}</div>
                        </div>
                    </a>

                    <!-- Office Address Note -->
                    <div class="mt-4 pt-3 border-top text-muted" style="font-size: 0.85rem; line-height: 1.5;">
                        <i class="fa-solid fa-location-dot mr-1 text-danger"></i> <strong>Headquarters:</strong> {{ $contactAddress }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection

@section('script')
<script>
    // FAQ Accordion Toggle
    function toggleFaqAccordion(headerEl) {
        const item = headerEl.parentElement;
        const isOpen = item.classList.contains('open');

        // Close all other accordions for clean UX
        document.querySelectorAll('.faq-accordion-item').forEach(el => {
            if (el !== item) {
                el.classList.remove('open');
            }
        });

        if (isOpen) {
            item.classList.remove('open');
        } else {
            item.classList.add('open');
        }
    }

    // Filter FAQs by Category Pill
    function filterFaqByCategory(category) {
        $('.filter-pill-btn').removeClass('active');
        $(`.filter-pill-btn[data-cat="${category}"]`).addClass('active');

        let visibleCount = 0;
        const searchQuery = $('#helpSearchInput').val().trim().toLowerCase();

        $('.faq-accordion-item').each(function() {
            const itemCat = $(this).data('cat') || '';
            const itemText = $(this).text().toLowerCase();

            const matchesCategory = (category === 'all' || itemCat.includes(category));
            const matchesSearch = (!searchQuery || itemText.includes(searchQuery));

            if (matchesCategory && matchesSearch) {
                $(this).fadeIn(150);
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        if (visibleCount === 0) {
            $('#noFaqResults').fadeIn(150);
        } else {
            $('#noFaqResults').hide();
        }
    }

    // Helpful feedback rating
    function rateHelpful(btn, isHelpful) {
        const parentRow = $(btn).closest('.faq-feedback-row');
        if (isHelpful) {
            parentRow.html('<span class="text-success font-weight-bold"><i class="fa-solid fa-circle-check mr-1"></i> Thank you for your feedback!</span>');
        } else {
            parentRow.html('<span class="text-muted"><i class="fa-solid fa-message mr-1"></i> We\'re sorry to hear that. Contact our support team for more details.</span>');
        }
    }

    $(document).ready(function() {
        // Real-time Live Search Filter
        $('#helpSearchInput').on('input', function() {
            const query = $(this).val().trim().toLowerCase();
            const activeCategory = $('.filter-pill-btn.active').data('cat') || 'all';

            if (query.length > 0) {
                $('#helpSearchClear').css('display', 'flex');
            } else {
                $('#helpSearchClear').hide();
            }

            let visibleCount = 0;
            $('.faq-accordion-item').each(function() {
                const itemCat = $(this).data('cat') || '';
                const itemText = $(this).text().toLowerCase();

                const matchesCategory = (activeCategory === 'all' || itemCat.includes(activeCategory));
                const matchesSearch = (!query || itemText.includes(query));

                if (matchesCategory && matchesSearch) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            if (visibleCount === 0) {
                $('#noFaqResults').show();
            } else {
                $('#noFaqResults').hide();
            }
        });

        // Clear Search Button
        $('#helpSearchClear').on('click', function() {
            $('#helpSearchInput').val('').trigger('input').focus();
        });
    });
</script>
@endsection