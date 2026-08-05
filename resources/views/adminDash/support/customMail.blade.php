@extends('layouts.Backend.master')
@section('title')
    CUSTOM MAIL DISPATCHER
@endsection

@section('style')
<style>
    .custom-mail-wrapper {
        padding-bottom: 2rem;
    }
    .hero-banner {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        color: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.3);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-icon-badge {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #fff;
        margin-right: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .composer-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .composer-header {
        background: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
        padding: 1.25rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Target Audience Selector Tiles */
    .recipient-tiles {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .recipient-tile {
        position: relative;
        cursor: pointer;
    }
    .recipient-tile input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .tile-content {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.9rem 1rem;
        text-align: center;
        background: #fff;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .tile-content i {
        font-size: 1.4rem;
        color: #6b7280;
        margin-bottom: 0.4rem;
        transition: color 0.25s ease;
    }
    .tile-title {
        font-weight: 600;
        font-size: 0.88rem;
        color: #374151;
        margin-bottom: 2px;
    }
    .tile-desc {
        font-size: 0.75rem;
        color: #9ca3af;
    }
    .recipient-tile input[type="radio"]:checked + .tile-content {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
    }
    .recipient-tile input[type="radio"]:checked + .tile-content i {
        color: #2563eb;
    }
    .recipient-tile input[type="radio"]:checked + .tile-content .tile-title {
        color: #1e40af;
    }
    .recipient-tile:hover .tile-content {
        border-color: #93c5fd;
        transform: translateY(-2px);
    }

    /* Variable Tags Bar */
    .variables-bar {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
    }
    .variable-tag {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.25rem 0.6rem;
        font-size: 0.78rem;
        font-family: monospace;
        font-weight: 600;
        color: #2563eb;
        cursor: pointer;
        margin: 0.2rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .variable-tag:hover {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        transform: scale(1.05);
    }

    /* Form Field Extensions */
    .form-control-custom {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        padding: 0.65rem 1rem;
        font-size: 0.92rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control-custom:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    /* Side Cards */
    .sidebar-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .sidebar-card-header {
        padding: 1rem 1.25rem;
        background: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
        font-weight: 700;
        font-size: 0.95rem;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Template Cards */
    .preset-template-btn {
        width: 100%;
        text-align: left;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.6rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
    }
    .preset-template-btn:hover {
        background: #eff6ff;
        border-color: #93c5fd;
        transform: translateX(4px);
    }
    .preset-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #dbeafe;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* Live Preview Window Mockup */
    .email-preview-frame {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        overflow: hidden;
    }
    .email-preview-header {
        background: #e2e8f0;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .preview-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .preview-body-container {
        padding: 1.5rem;
        background: #ffffff;
        min-height: 350px;
        max-height: 550px;
        overflow-y: auto;
    }

    /* Summernote customization */
    .note-editor.note-frame {
        border-radius: 10px !important;
        border-color: #d1d5db !important;
        overflow: hidden;
    }
    .note-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    /* Pulse dot */
    .pulse-dot {
        display: inline-block;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-animation 1.6s infinite;
        margin-right: 4px;
    }
    @keyframes pulse-animation {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid custom-mail-wrapper">

    <!-- Hero Header Banner -->
    <div class="hero-banner">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="hero-icon-badge">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <h2 class="font-weight-bold text-white mb-1">Custom Email Dispatcher</h2>
                    <p class="mb-0 text-white-50" style="font-size: 0.95rem;">
                        Compose & dispatch high-converting HTML custom emails to individual customers, custom recipient lists, or broadcast to all members.
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if($isSmtpConfigured)
                    <span class="badge badge-light text-dark px-3 py-2" style="border-radius: 20px; font-weight: 600;">
                        <span class="pulse-dot"></span> SMTP Server Active
                    </span>
                @else
                    <a href="{{ route('smtp.index') }}" class="badge badge-warning text-dark px-3 py-2" style="border-radius: 20px; font-weight: 600; text-decoration: none;">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> SMTP Setup Needed
                    </a>
                @endif
                <span class="badge badge-light text-primary px-3 py-2 ml-2" style="border-radius: 20px; font-weight: 600;">
                    <i class="fa-solid fa-users mr-1"></i> {{ number_format($usersCount ?? 0) }} Registered Users
                </span>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #ecfdf5; color: #065f46; border-left: 5px solid #10b981 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fa-lg mr-3 text-success"></i>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #fef2f2; color: #991b1b; border-left: 5px solid #ef4444 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation fa-lg mr-3 text-danger"></i>
                <div>
                    <strong>Attention Required!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #fffbeb; color: #92400e; border-left: 5px solid #f59e0b !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fa-lg mr-3 text-warning"></i>
                <div>
                    <strong>Form Validation Errors:</strong>
                    <ul class="mb-0 pl-3 mt-1" style="font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Main Mail Composer Column -->
        <div class="col-lg-8">
            <div class="composer-card">
                <div class="composer-header">
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary mr-2"></i> Compose Email Message</h5>
                        <small class="text-muted">Fill in recipient details and format your message content below</small>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="resetMailComposerForm()">
                            <i class="fa-solid fa-rotate-left mr-1"></i> Clear Form
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.customMail.send') }}" method="POST" id="customMailForm">
                        @csrf

                        <!-- STEP 1: Select Target Audience -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <i class="fa-solid fa-bullseye text-danger mr-1"></i> Target Audience <span class="text-danger">*</span>
                            </label>

                            <div class="recipient-tiles">
                                <label class="recipient-tile">
                                    <input type="radio" name="recipient_type" value="user" {{ old('recipient_type', 'user') == 'user' ? 'checked' : '' }} onchange="toggleRecipientType(this.value)">
                                    <div class="tile-content">
                                        <i class="fa-solid fa-user-check"></i>
                                        <span class="tile-title">Select Customer</span>
                                        <span class="tile-desc">Single user from database</span>
                                    </div>
                                </label>

                                <label class="recipient-tile">
                                    <input type="radio" name="recipient_type" value="email" {{ old('recipient_type') == 'email' ? 'checked' : '' }} onchange="toggleRecipientType(this.value)">
                                    <div class="tile-content">
                                        <i class="fa-solid fa-at"></i>
                                        <span class="tile-title">Direct Email</span>
                                        <span class="tile-desc">Manual email address</span>
                                    </div>
                                </label>

                                <label class="recipient-tile">
                                    <input type="radio" name="recipient_type" value="multiple" {{ old('recipient_type') == 'multiple' ? 'checked' : '' }} onchange="toggleRecipientType(this.value)">
                                    <div class="tile-content">
                                        <i class="fa-solid fa-list-check"></i>
                                        <span class="tile-title">Bulk List</span>
                                        <span class="tile-desc">Multiple comma emails</span>
                                    </div>
                                </label>

                                <label class="recipient-tile">
                                    <input type="radio" name="recipient_type" value="all" {{ old('recipient_type') == 'all' ? 'checked' : '' }} onchange="toggleRecipientType(this.value)">
                                    <div class="tile-content">
                                        <i class="fa-solid fa-bullhorn"></i>
                                        <span class="tile-title">All Customers</span>
                                        <span class="tile-desc">Broadcast to everyone</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Conditional Input Containers -->
                            <!-- Option A: User Dropdown -->
                            <div id="targetUserContainer" class="target-container">
                                <label class="small text-muted font-weight-bold">Select Registered Customer:</label>
                                <select name="user_id" id="user_id_select" class="form-control select2 w-100">
                                    <option value="">-- Choose Customer --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Option B: Single Manual Email -->
                            <div id="targetEmailContainer" class="target-container d-none">
                                <label class="small text-muted font-weight-bold">Recipient Email Address:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fa-solid fa-envelope text-primary"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control form-control-custom" placeholder="e.g. customer@example.com" value="{{ old('email') }}">
                                </div>
                            </div>

                            <!-- Option C: Multiple Emails -->
                            <div id="targetMultipleContainer" class="target-container d-none">
                                <label class="small text-muted font-weight-bold">Multiple Recipient Emails (Comma Separated):</label>
                                <textarea name="multiple_emails" class="form-control form-control-custom" rows="3" placeholder="e.g. john@example.com, sara@example.com, alex@example.com">{{ old('multiple_emails') }}</textarea>
                                <small class="form-text text-muted">Separate multiple email addresses with a comma.</small>
                            </div>

                            <!-- Option D: All Registered Users Broadcast Alert -->
                            <div id="targetAllContainer" class="target-container d-none">
                                <div class="p-3 bg-light-info text-info border border-info rounded-lg d-flex align-items-center">
                                    <i class="fa-solid fa-circle-info fa-2x mr-3"></i>
                                    <div>
                                        <strong class="d-block">Broadcast Mode Selected</strong>
                                        <span style="font-size: 0.88rem;">This custom email will be sent to all <strong>{{ number_format($usersCount ?? 0) }} active registered customers</strong> who have an email address recorded.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: Subject Line -->
                        <div class="form-group mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">
                                    <i class="fa-solid fa-heading text-primary mr-1"></i> Email Subject <span class="text-danger">*</span>
                                </label>
                                <!-- Quick Subject Presets Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-link btn-sm text-primary p-0 dropdown-toggle font-weight-bold" type="button" data-toggle="dropdown">
                                        <i class="fa-solid fa-magic mr-1"></i> Subject Presets
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="applySubjectPreset('🎉 Special Exclusive Offer Just For You, {name}!')">🎉 Special Exclusive Offer</a>
                                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="applySubjectPreset('📢 Important Account & Service Announcement')">📢 Service Announcement</a>
                                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="applySubjectPreset('🛍️ Your Shopping Experience at {site_name}')">🛍️ Customer Experience Update</a>
                                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="applySubjectPreset('👋 Hello {name}, We Have Exciting News For You!')">👋 Exciting News Greeting</a>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fa-solid fa-envelope-open-text text-muted"></i></span>
                                </div>
                                <input type="text" name="subject" id="emailSubjectInput" class="form-control form-control-custom" placeholder="e.g. Important Update Regarding Your Account" value="{{ old('subject') }}" required>
                            </div>
                        </div>

                        <!-- STEP 3: Dynamic Variables & Tag Chips Bar -->
                        <div class="variables-bar">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small font-weight-bold text-dark"><i class="fa-solid fa-code text-info mr-1"></i> Dynamic Variable Placeholder Tags:</span>
                                <small class="text-muted">Click tag to insert at cursor position</small>
                            </div>
                            <div>
                                <span class="variable-tag" onclick="insertVariableTag('{name}')" title="Inserts Recipient's Full Name"><i class="fa-solid fa-user mr-1"></i> {name}</span>
                                <span class="variable-tag" onclick="insertVariableTag('{email}')" title="Inserts Recipient's Email Address"><i class="fa-solid fa-at mr-1"></i> {email}</span>
                                <span class="variable-tag" onclick="insertVariableTag('{site_name}')" title="Inserts Website / Store Name"><i class="fa-solid fa-store mr-1"></i> {site_name}</span>
                                <span class="variable-tag" onclick="insertVariableTag('{site_url}')" title="Inserts Store Website URL"><i class="fa-solid fa-link mr-1"></i> {site_url}</span>
                                <span class="variable-tag" onclick="insertVariableTag('{date}')" title="Inserts Current Date"><i class="fa-solid fa-calendar mr-1"></i> {date}</span>
                            </div>
                        </div>

                        <!-- STEP 4: Rich Text Body Editor -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <i class="fa-solid fa-file-lines text-warning mr-1"></i> Email Body Content <span class="text-danger">*</span>
                            </label>

                            <textarea name="body" id="customMailSummernote" class="form-control" rows="12">{{ old('body') }}</textarea>
                        </div>

                        <!-- STEP 5: Action Buttons -->
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <button type="button" class="btn btn-outline-info rounded-pill px-4" onclick="openLiveMailPreview()">
                                <i class="fa-solid fa-eye mr-2"></i> Live Preview Email
                            </button>

                            <div class="d-flex align-items-center gap-2">
                                <button type="submit" id="btnSubmitMail" class="btn btn-primary rounded-pill px-4 py-2 font-weight-bold shadow-sm">
                                    <i class="fa-solid fa-paper-plane mr-2"></i> Dispatch Email Now
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Column: Templates & Info -->
        <div class="col-lg-4">

            <!-- HTML Email Template Loader -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fa-solid fa-wand-magic-sparkles text-primary"></i> Quick HTML Email Layouts
                </div>
                <div class="p-3">
                    <p class="small text-muted mb-3">Load a pre-designed responsive HTML template directly into your composer editor:</p>

                    <div class="preset-template-btn" onclick="loadHtmlTemplate('promo')">
                        <div class="preset-icon"><i class="fa-solid fa-tags"></i></div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">Promotional Campaign</div>
                            <div class="small text-muted">Hero banner, discount box & CTA button</div>
                        </div>
                    </div>

                    <div class="preset-template-btn" onclick="loadHtmlTemplate('notice')">
                        <div class="preset-icon" style="background:#fee2e2; color:#ef4444;"><i class="fa-solid fa-bell"></i></div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">Important Notification</div>
                            <div class="small text-muted">Clean alert layout with callout box</div>
                        </div>
                    </div>

                    <div class="preset-template-btn" onclick="loadHtmlTemplate('welcome')">
                        <div class="preset-icon" style="background:#dcfce7; color:#16a34a;"><i class="fa-solid fa-hand-shake"></i></div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">Warm Welcome & Support</div>
                            <div class="small text-muted">Friendly customer onboarding email</div>
                        </div>
                    </div>

                    <div class="preset-template-btn" onclick="loadHtmlTemplate('blank')">
                        <div class="preset-icon" style="background:#f3f4f6; color:#6b7280;"><i class="fa-solid fa-eraser"></i></div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">Clear Canvas</div>
                            <div class="small text-muted">Reset editor to blank text field</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMTP Server Status Card -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fa-solid fa-server text-info"></i> SMTP Delivery Engine Status
                </div>
                <div class="p-3">
                    @if($isSmtpConfigured)
                        <div class="d-flex align-items-center text-success mb-2">
                            <i class="fa-solid fa-circle-check mr-2"></i>
                            <span class="font-weight-bold" style="font-size: 0.9rem;">SMTP Configured & Ready</span>
                        </div>
                        <p class="small text-muted mb-3">Your system is set up to send custom emails seamlessly using your configured mail server credentials.</p>
                    @else
                        <div class="d-flex align-items-center text-danger mb-2">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                            <span class="font-weight-bold" style="font-size: 0.9rem;">SMTP Settings Incomplete</span>
                        </div>
                        <p class="small text-muted mb-3">Please configure your SMTP hostname, port, and credentials to enable outbound email delivery.</p>
                    @endif
                    <a href="{{ route('smtp.index') }}" class="btn btn-outline-primary btn-sm btn-block rounded-pill">
                        <i class="fa-solid fa-gear mr-1"></i> Manage SMTP Settings
                    </a>
                </div>
            </div>

            <!-- Best Practices Guide -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fa-solid fa-lightbulb text-warning"></i> Email Deliverability Tips
                </div>
                <div class="p-3">
                    <ul class="pl-3 mb-0 small text-muted" style="line-height: 1.6;">
                        <li class="mb-2"><strong>Personalization:</strong> Use dynamic tags like <code>{name}</code> to increase engagement rates.</li>
                        <li class="mb-2"><strong>Clear Subject Line:</strong> Avoid spam keywords (e.g. "FREE FREE FREE", "100% Cash") in subjects.</li>
                        <li class="mb-2"><strong>Mobile Optimization:</strong> The pre-designed HTML layouts are 100% responsive on smartphones.</li>
                        <li><strong>Testing:</strong> Always preview or test sending before broadcasting to all customers.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- LIVE EMAIL PREVIEW MODAL -->
<div class="modal fade" id="emailPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-dark text-white px-4 py-3">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-desktop mr-2 text-info"></i>
                    <h5 class="modal-title font-weight-bold text-white mb-0" id="previewModalLabel">Live Email Preview</h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group btn-group-toggle mr-3" data-toggle="buttons">
                        <label class="btn btn-sm btn-outline-light active" onclick="switchPreviewMode('desktop')">
                            <input type="radio" name="options" id="optDesktop" checked> <i class="fa-solid fa-desktop mr-1"></i> Desktop
                        </label>
                        <label class="btn btn-sm btn-outline-light" onclick="switchPreviewMode('mobile')">
                            <input type="radio" name="options" id="optMobile"> <i class="fa-solid fa-mobile-screen mr-1"></i> Mobile
                        </label>
                    </div>
                    <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body bg-light p-4">
                <div class="mx-auto transition-all" id="previewContainer" style="max-width: 100%; transition: max-width 0.3s ease;">
                    <div class="email-preview-frame shadow-sm">
                        <div class="email-preview-header">
                            <span class="preview-dot bg-danger"></span>
                            <span class="preview-dot bg-warning"></span>
                            <span class="preview-dot bg-success"></span>
                            <span class="ml-2 small text-muted font-weight-bold" id="modalPreviewSubjectHeader">Subject: Custom Email</span>
                        </div>
                        <div class="preview-body-container" id="modalPreviewBody">
                            <!-- Dynamically loaded preview HTML -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top px-4 py-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Close Preview</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Initialize Summernote Rich Text Editor
        if ($('#customMailSummernote').length > 0) {
            $('#customMailSummernote').summernote({
                height: 300,
                placeholder: 'Type your email body here or select a quick template from the right sidebar...',
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }

        // Initialize Select2 for user selector if available
        if ($('.select2').length > 0) {
            $('.select2').select2({
                placeholder: "-- Search & Choose Customer --",
                allowClear: true
            });
        }

        // Set initial recipient type display
        const initialType = $('input[name="recipient_type"]:checked').val() || 'user';
        toggleRecipientType(initialType);

        // Form submit handler with loading state
        $('#customMailForm').on('submit', function() {
            const btn = $('#btnSubmitMail');
            btn.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Sending Email...');
            btn.prop('disabled', true);
        });
    });

    /**
     * Toggle Recipient Type Inputs
     */
    function toggleRecipientType(type) {
        $('.target-container').addClass('d-none');

        if (type === 'user') {
            $('#targetUserContainer').removeClass('d-none');
        } else if (type === 'email') {
            $('#targetEmailContainer').removeClass('d-none');
        } else if (type === 'multiple') {
            $('#targetMultipleContainer').removeClass('d-none');
        } else if (type === 'all') {
            $('#targetAllContainer').removeClass('d-none');
        }
    }

    /**
     * Apply Subject Preset
     */
    function applySubjectPreset(subjectText) {
        $('#emailSubjectInput').val(subjectText);
        Toast.fire({
            icon: 'info',
            title: 'Subject preset applied!'
        });
    }

    /**
     * Insert Variable Tag into Summernote Editor
     */
    function insertVariableTag(tag) {
        if ($('#customMailSummernote').length > 0) {
            $('#customMailSummernote').summernote('insertText', ' ' + tag + ' ');
            Toast.fire({
                icon: 'success',
                title: 'Inserted tag: ' + tag
            });
        }
    }

    /**
     * Reset Composer Form
     */
    function resetMailComposerForm() {
        Swal.fire({
            title: 'Clear Form?',
            text: "Are you sure you want to reset all fields in the composer?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, clear it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#customMailForm')[0].reset();
                if ($('#customMailSummernote').length > 0) {
                    $('#customMailSummernote').summernote('code', '');
                }
                if ($('#user_id_select').length > 0) {
                    $('#user_id_select').val('').trigger('change');
                }
                toggleRecipientType('user');
                Toast.fire({
                    icon: 'info',
                    title: 'Form cleared successfully.'
                });
            }
        });
    }

    /**
     * Load Pre-designed HTML Email Templates
     */
    function loadHtmlTemplate(templateType) {
        let html = '';

        if (templateType === 'promo') {
            html = `
<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
    <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); padding: 30px 20px; text-align: center; color: #ffffff;">
        <h1 style="margin: 0; font-size: 26px; font-weight: 700;">🎉 Special Exclusive Offer!</h1>
        <p style="margin-top: 8px; margin-bottom: 0; font-size: 15px; opacity: 0.9;">Curated deals just for you at {site_name}</p>
    </div>
    <div style="padding: 30px 25px; color: #334155; line-height: 1.6;">
        <p style="font-size: 16px;">Hello <strong>{name}</strong>,</p>
        <p>We are thrilled to present our exclusive seasonal sale! For a limited time, enjoy special discounts across our best-selling collections.</p>

        <div style="background-color: #f1f5f9; border-left: 4px solid #2563eb; border-radius: 6px; padding: 20px; margin: 25px 0; text-align: center;">
            <span style="display: block; font-size: 13px; color: #64748b; text-transform: uppercase; letter-weight: 600;">Promo Code</span>
            <strong style="display: block; font-size: 24px; color: #1e40af; margin-top: 5px; letter-spacing: 2px;">SPECIAL20</strong>
            <span style="display: block; font-size: 13px; color: #64748b; margin-top: 5px;">Get 20% OFF your next order!</span>
        </div>

        <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
            <a href="{site_url}" style="background-color: #2563eb; color: #ffffff; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; font-size: 15px; display: inline-block;">Shop Special Deals Now</a>
        </div>
    </div>
    <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px; text-align: center; color: #94a3b8; font-size: 12px;">
        <p style="margin: 0;">Sent with ❤️ from {site_name} | {date}</p>
        <p style="margin-top: 5px; margin-bottom: 0;"><a href="{site_url}" style="color: #64748b; text-decoration: underline;">Visit Website</a></p>
    </div>
</div>`;
        } else if (templateType === 'notice') {
            html = `
<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
    <div style="background-color: #0f172a; padding: 25px 20px; text-align: center; color: #ffffff;">
        <h2 style="margin: 0; font-size: 22px; font-weight: 700;">📢 Important Notice</h2>
        <p style="margin-top: 5px; margin-bottom: 0; font-size: 14px; color: #94a3b8;">{site_name} Official Communication</p>
    </div>
    <div style="padding: 30px 25px; color: #334155; line-height: 1.6;">
        <p>Dear <strong>{name}</strong>,</p>
        <p>We are writing to notify you regarding important updates to your account or recent transactions on our platform.</p>

        <div style="background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 18px; margin: 20px 0; color: #92400e;">
            <strong>Note:</strong> Please review your account dashboard for detailed information or contact our support team if you have any questions.
        </div>

        <p>If you need assistance, please do not hesitate to contact our dedicated support team through our portal.</p>

        <p style="margin-top: 25px;">Best regards,<br><strong>{site_name} Customer Care</strong></p>
    </div>
    <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px; text-align: center; color: #94a3b8; font-size: 12px;">
        © {date} {site_name}. All rights reserved.
    </div>
</div>`;
        } else if (templateType === 'welcome') {
            html = `
<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
    <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); padding: 30px 20px; text-align: center; color: #ffffff;">
        <h1 style="margin: 0; font-size: 26px;">👋 Welcome to {site_name}!</h1>
        <p style="margin-top: 8px; margin-bottom: 0; font-size: 15px; opacity: 0.9;">We are delighted to have you with us</p>
    </div>
    <div style="padding: 30px 25px; color: #334155; line-height: 1.6;">
        <p style="font-size: 16px;">Hello <strong>{name}</strong>,</p>
        <p>Thank you for being a valued customer of <strong>{site_name}</strong>. We are committed to providing you with the best products and service experience.</p>
        
        <p>Here is what you can do next:</p>
        <ul>
            <li>Explore our latest collections and exclusive offers</li>
            <li>Track your existing orders in real time</li>
            <li>Connect with our 24/7 customer support team</li>
        </ul>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{site_url}" style="background-color: #10b981; color: #ffffff; padding: 12px 28px; border-radius: 20px; text-decoration: none; font-weight: bold; display: inline-block;">Go to My Dashboard</a>
        </div>
    </div>
    <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px; text-align: center; color: #94a3b8; font-size: 12px;">
        Sent to {email} on {date}
    </div>
</div>`;
        } else if (templateType === 'blank') {
            html = '';
        }

        if ($('#customMailSummernote').length > 0) {
            $('#customMailSummernote').summernote('code', html);
            Toast.fire({
                icon: 'success',
                title: 'Template loaded into editor!'
            });
        }
    }

    /**
     * Open Live Mail Preview Modal
     */
    function openLiveMailPreview() {
        const subject = $('#emailSubjectInput').val() || 'No Subject';
        let bodyHtml = $('#customMailSummernote').summernote('code') || '<p class="text-muted text-center py-5">No content composed yet.</p>';

        // Replace tags in client-side preview for realistic experience
        bodyHtml = bodyHtml.replace(/{name}/g, 'John Doe')
                           .replace(/{email}/g, 'customer@example.com')
                           .replace(/{site_name}/g, '{{ config("app.name", "Looksmen") }}')
                           .replace(/{site_url}/g, '{{ url("/") }}')
                           .replace(/{date}/g, '{{ date("F j, Y") }}');

        $('#modalPreviewSubjectHeader').text('Subject: ' + subject);
        $('#modalPreviewBody').html(bodyHtml);
        $('#emailPreviewModal').modal('show');
    }

    /**
     * Switch Preview Device Viewport Mode
     */
    function switchPreviewMode(mode) {
        if (mode === 'mobile') {
            $('#previewContainer').css('max-width', '375px');
        } else {
            $('#previewContainer').css('max-width', '100%');
        }
    }
</script>
@endsection
