@extends('layouts.Backend.master')
@section('title')
    WEB SETTINGS
@endsection
@section('content')
    <style>
        /* Premium Settings CSS Variables */
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --bg-card: #ffffff;
            --border-color: rgba(229, 231, 235, 0.7);
            --text-dark: #1f2937;
            --text-muted: #64748b;
            --focus-ring: rgba(99, 102, 241, 0.15);
        }

        /* Premium Settings Card Layouts */
        .settings-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color) !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .settings-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -4px rgba(0, 0, 0, 0.08) !important;
        }

        .settings-card .card-header {
            background: linear-gradient(to right, #f9fafb, #ffffff);
            border-bottom: 1px solid rgba(229, 231, 235, 0.6) !important;
            padding: 18px 24px;
        }

        .settings-card .card-header h3, 
        .settings-card .card-header h4 {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 15px;
            margin: 0;
            letter-spacing: -0.2px;
        }

        .settings-card .card-body {
            padding: 24px;
        }

        /* Form styling enhancements */
        .form-label {
            font-weight: 600;
            color: #4b5563;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px !important;
            border: 1px solid #d1d5db !important;
            padding: 10px 14px !important;
            font-size: 13px !important;
            font-weight: 500;
            color: var(--text-dark);
            background: #fafafa;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px var(--focus-ring) !important;
            outline: none;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        /* Buttons styling */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%) !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 10px 20px !important;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.2px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
            transition: all 0.3s ease;
            width: 100%;
            color: #ffffff !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, #4338ca 100%) !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3) !important;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Premium Drag & Drop Upload Widget */
        .image-upload-box {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            position: relative;
            background: #f8fafc;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            height: 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: 8px;
        }

        .image-upload-box:hover {
            border-color: var(--primary-color);
            background: #f1f5f9;
        }

        .image-upload-box .upload-icon-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .image-upload-box .plus-icon {
            font-size: 26px;
            color: var(--primary-color);
            background: rgba(99, 102, 241, 0.08);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .image-upload-box:hover .plus-icon {
            transform: scale(1.1);
            background: rgba(99, 102, 241, 0.15);
        }

        .image-upload-box .upload-text {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
        }

        .image-upload-box.has-image .upload-icon-container {
            display: none !important;
        }

        .image-preview {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            border-radius: 8px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
            z-index: 2;
        }

        .image-upload-box:hover .image-preview {
            transform: translate(-50%, -50%) scale(1.03);
        }

        /* Maintenance Mode Card Accent */
        .maintenance-card-active {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.03) 0%, rgba(220, 38, 38, 0.05) 100%) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
        }

        .maintenance-card-inactive {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.03) 0%, rgba(5, 150, 105, 0.05) 100%) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }
    </style>

    <div class="row mb-2">
        <!-- Website Details -->
        <div class="col-lg-4 col-md-6">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-globe text-primary mr-2" style="font-size: 16px;"></i>
                    <h4>Website Details</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('websettings.webDetails') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">WebSite Name</label>
                            <input type="text" class="form-control" value="{{ $webConfig['web_name']['value'] ?? '' }}"
                                placeholder="Enter Website Name" name="webName">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Website Description</label>
                            <textarea class="form-control" name="webDescription" placeholder="Enter Website Description">{{ $webConfig['web_description']['value'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">WebSite Tags</label>
                            <input type="text" class="form-control" value="{{ $webConfig['web_tags']['value'] ?? '' }}"
                                placeholder="Enter Website Tags" name="webTags">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Details</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="col-lg-4 col-md-6">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-address-book text-primary mr-2" style="font-size: 16px;"></i>
                    <h4>Contact Details</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('websettings.webContact') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Contact Address</label>
                            <input type="text" class="form-control" value="{{ $webConfig['contact_address']['value'] ?? '' }}"
                                placeholder="Enter Address" name="contact_address">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" value="{{ $webConfig['contact_phone']['value'] ?? '' }}"
                                placeholder="Enter Phone Number" name="contact_phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-control" value="{{ $webConfig['contact_email']['value'] ?? '' }}"
                                placeholder="Enter Email Address" name="contact_email">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Contacts</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Meta Details -->
        <div class="col-lg-4 col-md-12">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-search text-primary mr-2" style="font-size: 16px;"></i>
                    <h4>Meta Details (SEO)</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('websettings.webMeta') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" class="form-control" value="{{ $webConfig['meta_title']['value'] ?? '' }}"
                                placeholder="Enter Meta Title" name="meta_title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea class="form-control" placeholder="Enter Meta Description" name="meta_description">{{ $webConfig['meta_description']['value'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Tags (Keywords)</label>
                            <input type="text" class="form-control" value="{{ $webConfig['meta_keyword']['value'] ?? '' }}"
                                placeholder="Enter Meta Tags" name="meta_keyword">
                        </div>
                        <button type="submit" class="btn btn-primary">Save SEO Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Media File Uploads -->
    <div class="row">
        <!-- Header Logo -->
        <div class="col-lg-4 col-md-6">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-images text-primary mr-2" style="font-size: 16px;"></i>
                    <h4>Header Logo</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('websettings.headerLogo') }}" method="POST" enctype="multipart/form-data"
                        class="settingsUpdateForm">
                        @csrf
                        <input type="file" name="header_logo_image" id="image-input-1" accept="image/*" hidden>
                        <div class="image-upload-box {{ !empty($webConfig['web_logo']['value']) ? 'has-image' : '' }}" id="upload-box-1">
                            <div class="upload-icon-container">
                                <i class="fa-solid fa-cloud-upload-alt plus-icon mb-2"></i>
                                <span class="upload-text">Drag & Drop or Click to Upload</span>
                                <span style="font-size: 10px; color: #94a3b8;">Max size: 2MB</span>
                            </div>
                            <img class="image-preview" id="image-preview-1"
                                src="{{ !empty($webConfig['web_logo']['value']) ? asset('adminDash/assets/img/layouts/' . $webConfig['web_logo']['value']) : '#' }}"
                                alt="Header Logo Preview"
                                style="{{ !empty($webConfig['web_logo']['value']) ? 'display:block;' : 'display:none;' }}"
                                onerror="this.style.display='none'; this.closest('.image-upload-box').classList.remove('has-image');">
                        </div>
                        @error('header_logo_image')
                            <div class="text-danger mt-2" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-primary mt-3">Upload Header Logo</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer Logo -->
        <div class="col-lg-4 col-md-6">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-images text-primary mr-2" style="font-size: 16px;"></i>
                    <h4>Footer Logo</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('websettings.footerLogo') }}" method="POST" enctype="multipart/form-data"
                        class="settingsUpdateForm">
                        @csrf
                        <input type="file" name="footer_logo_image" id="image-input-2" accept="image/*" hidden>
                        <div class="image-upload-box {{ !empty($webConfig['footer_logo']['value']) ? 'has-image' : '' }}" id="upload-box-2">
                            <div class="upload-icon-container">
                                <i class="fa-solid fa-cloud-upload-alt plus-icon mb-2"></i>
                                <span class="upload-text">Drag & Drop or Click to Upload</span>
                                <span style="font-size: 10px; color: #94a3b8;">Max size: 2MB</span>
                            </div>
                            <img class="image-preview" id="image-preview-2" 
                                src="{{ !empty($webConfig['footer_logo']['value']) ? asset('adminDash/assets/img/layouts/' . $webConfig['footer_logo']['value']) : '#' }}" 
                                alt="Footer Logo Preview" 
                                style="{{ !empty($webConfig['footer_logo']['value']) ? 'display:block;' : 'display:none;' }}"
                                onerror="this.style.display='none'; this.closest('.image-upload-box').classList.remove('has-image');">
                        </div>
                        @error('footer_logo_image')
                            <div class="text-danger mt-2" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-primary mt-3">Upload Footer Logo</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Favicon -->
        <div class="col-lg-4 col-md-12">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-face-smile text-primary mr-2" style="font-size: 16px;"></i>
                    <h4>Favicon</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('websettings.favicon') }}" method="POST" enctype="multipart/form-data"
                        class="settingsUpdateForm">
                        @csrf
                        <input type="file" name="favicon_image" id="image-input-3" accept="image/*" hidden>
                        <div class="image-upload-box {{ !empty($webConfig['web_favicon']['value']) ? 'has-image' : '' }}" id="upload-box-3">
                            <div class="upload-icon-container">
                                <i class="fa-solid fa-cloud-upload-alt plus-icon mb-2"></i>
                                <span class="upload-text">Drag & Drop or Click to Upload</span>
                                <span style="font-size: 10px; color: #94a3b8;">Max size: 1MB</span>
                            </div>
                            <img class="image-preview" id="image-preview-3" 
                                src="{{ !empty($webConfig['web_favicon']['value']) ? asset('adminDash/assets/img/layouts/' . $webConfig['web_favicon']['value']) : '#' }}" 
                                alt="Favicon Preview" 
                                style="{{ !empty($webConfig['web_favicon']['value']) ? 'display:block;' : 'display:none;' }}"
                                onerror="this.style.display='none'; this.closest('.image-upload-box').classList.remove('has-image');">
                        </div>
                        @error('favicon_image')
                            <div class="text-danger mt-2" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-primary mt-3">Upload Favicon</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Integrations -->
    <div class="row">
        <!-- Google Tag -->
        <div class="col-lg-6">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-code text-primary mr-2" style="font-size: 16px;"></i>
                    <h4>Google TAG</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('websettings.webGtag') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">GOOGLE TAG MANAGER ID</label>
                            <input type="text" class="form-control" name="gtagid" value="{{ $webConfig['gtagid']['value'] ?? '' }}" placeholder="GTM-XXXXXX">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">GOOGLE Domain Verification</label>
                            <textarea class="form-control" name="gdomainverify" placeholder="Paste verification meta tag or token">{{ $webConfig['gdomainverify']['value'] ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Google Scripts</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- FB Pixel -->
        <div class="col-lg-6">
            <div class="settings-card card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-brands fa-facebook text-primary mr-2" style="font-size: 16px;"></i>
                    <h4>FB Pixel & SDK</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('websettings.webFbpixel') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Facebook Pixel Code</label>
                            <textarea class="form-control" name="fb_pixel" placeholder="Paste Facebook Pixel Javascript Code here">{{ $webConfig['fb_pixel']['value'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Facebook Domain Verification</label>
                            <textarea class="form-control" name="fbdomainverify" placeholder="Paste verification meta code">{{ $webConfig['fbdomainverify']['value'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Facebook Page Iframe</label>
                            <textarea class="form-control" name="fbiframe" placeholder="Paste page plugin iframe code">{{ $webConfig['fbiframe']['value'] ?? '' }}</textarea>
                        </div>
                        @if (isset($featuresConfig['facebook_api']) && $featuresConfig['facebook_api'] == '1')
                            <div class="mb-3">
                                <label class="form-label">Facebook Chat Plugin Code</label>
                                <textarea class="form-control" name="fbchatplugin" placeholder="Paste Chat Plugin code">{{ $webConfig['fbchatplugin']['value'] ?? '' }}</textarea>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary">Save Facebook Scripts</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== Domain Configuration ==================== --}}
    <div class="row mb-2">
        <div class="col-lg-12">
            <div class="settings-card card" style="border: 1px solid rgba(99,102,241,0.2) !important; background: linear-gradient(135deg, rgba(99,102,241,0.02) 0%, rgba(139,92,246,0.03) 100%) !important;">
                <div class="card-header d-flex align-items-center justify-content-between" style="background: linear-gradient(to right, rgba(99,102,241,0.06), rgba(139,92,246,0.04)) !important; border-bottom: 1px solid rgba(99,102,241,0.15) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-network-wired" style="color: #fff; font-size: 15px;"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #1f2937;">Domain Configuration</h4>
                            <p style="margin: 0; font-size: 11px; color: #6366f1; font-weight: 500;">Storefront & Admin Panel Domains</p>
                        </div>
                    </div>
                    <span style="background: rgba(99,102,241,0.1); color: #6366f1; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 20px; letter-spacing: 0.5px;">MULTI-DOMAIN SETUP</span>
                </div>
                <div class="card-body" style="padding: 28px 28px 20px;">
                    <form action="{{ route('websettings.webDomain') }}" method="POST" id="domainSettingsForm">
                        @csrf
                        <div class="row g-4">

                            {{-- Main Domain --}}
                            <div class="col-lg-6">
                                <div style="background: #fff; border: 1px solid rgba(229,231,235,0.8); border-radius: 14px; padding: 20px; position: relative; transition: all 0.3s ease;" class="domain-input-card" id="mainDomainCard">
                                    <div style="position: absolute; top: -1px; left: 20px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; font-size: 10px; font-weight: 700; padding: 3px 12px; border-radius: 0 0 8px 8px; letter-spacing: 0.5px;">
                                        🌐 STOREFRONT
                                    </div>
                                    <div style="margin-top: 10px;">
                                        <label class="form-label" style="color: #374151; font-weight: 600; font-size: 13px;">Main Website Domain</label>
                                        <p style="font-size: 11px; color: #9ca3af; margin-bottom: 10px;">Your customer-facing storefront URL</p>
                                        <div style="position: relative;">
                                            <div style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); background: #f3f4f6; border-radius: 6px; padding: 2px 8px; font-size: 11px; font-weight: 600; color: #6b7280; z-index: 1; pointer-events: none;">https://</div>
                                            <input type="text"
                                                class="form-control"
                                                name="app_domain"
                                                id="appDomainInput"
                                                value="{{ $webConfig['app_domain']['value'] ?? config('app.domain', 'looksmen.com') }}"
                                                placeholder="looksmen.com"
                                                style="padding-left: 80px !important; font-weight: 600; font-size: 14px; color: #111827; background: #f9fafb; border-color: #e5e7eb !important;"
                                                oninput="updateDomainPreview()">
                                        </div>
                                        {{-- Live Preview --}}
                                        <div style="margin-top: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 10px 14px; display: flex; align-items: center; gap: 8px;" id="mainDomainPreview">
                                            <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; flex-shrink: 0; box-shadow: 0 0 0 3px rgba(16,185,129,0.2);"></div>
                                            <span style="font-size: 12px; color: #065f46; font-weight: 500; word-break: break-all;" id="mainDomainPreviewText">
                                                https://{{ $webConfig['app_domain']['value'] ?? config('app.domain', 'looksmen.com') }}
                                            </span>
                                            <button type="button" onclick="copyDomain('main')" style="margin-left: auto; background: none; border: none; color: #10b981; font-size: 12px; cursor: pointer; padding: 2px 6px; border-radius: 4px;" title="Copy URL">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Admin Domain --}}
                            <div class="col-lg-6">
                                <div style="background: #fff; border: 1px solid rgba(229,231,235,0.8); border-radius: 14px; padding: 20px; position: relative; transition: all 0.3s ease;" class="domain-input-card" id="adminDomainCard">
                                    <div style="position: absolute; top: -1px; left: 20px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; font-size: 10px; font-weight: 700; padding: 3px 12px; border-radius: 0 0 8px 8px; letter-spacing: 0.5px;">
                                        🔒 ADMIN PANEL
                                    </div>
                                    <div style="margin-top: 10px;">
                                        <label class="form-label" style="color: #374151; font-weight: 600; font-size: 13px;">Admin Panel Domain</label>
                                        <p style="font-size: 11px; color: #9ca3af; margin-bottom: 10px;">Only this domain can access the admin panel</p>
                                        <div style="position: relative;">
                                            <div style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); background: #f3f4f6; border-radius: 6px; padding: 2px 8px; font-size: 11px; font-weight: 600; color: #6b7280; z-index: 1; pointer-events: none;">https://</div>
                                            <input type="text"
                                                class="form-control"
                                                name="admin_domain"
                                                id="adminDomainInput"
                                                value="{{ $webConfig['admin_domain']['value'] ?? config('app.admin_domain', 'admin.looksmen.com') }}"
                                                placeholder="admin.looksmen.com"
                                                style="padding-left: 80px !important; font-weight: 600; font-size: 14px; color: #111827; background: #f9fafb; border-color: #e5e7eb !important;"
                                                oninput="updateDomainPreview()">
                                        </div>
                                        {{-- Live Preview --}}
                                        <div style="margin-top: 12px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 10px 14px; display: flex; align-items: center; gap: 8px;" id="adminDomainPreview">
                                            <div style="width: 8px; height: 8px; background: #6366f1; border-radius: 50%; flex-shrink: 0; box-shadow: 0 0 0 3px rgba(99,102,241,0.2);"></div>
                                            <span style="font-size: 12px; color: #1e40af; font-weight: 500; word-break: break-all;" id="adminDomainPreviewText">
                                                https://{{ $webConfig['admin_domain']['value'] ?? config('app.admin_domain', 'admin.looksmen.com') }}/admin/dashboard
                                            </span>
                                            <button type="button" onclick="copyDomain('admin')" style="margin-left: auto; background: none; border: none; color: #6366f1; font-size: 12px; cursor: pointer; padding: 2px 6px; border-radius: 4px;" title="Copy URL">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Warning box --}}
                        <div style="margin-top: 20px; background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1px solid #fde68a; border-radius: 12px; padding: 14px 18px; display: flex; gap: 12px; align-items: flex-start;">
                            <i class="fa-solid fa-triangle-exclamation" style="color: #d97706; font-size: 16px; margin-top: 1px; flex-shrink: 0;"></i>
                            <div>
                                <p style="margin: 0 0 4px; font-size: 12px; font-weight: 700; color: #92400e;">Important</p>
                                <p style="margin: 0; font-size: 11px; color: #78350f; line-height: 1.6;">
                                    Domain পরিবর্তন করলে আপনাকে cPanel এ subdomain point করতে হবে। যদি আপনি admin domain ভুল দেন তাহলে admin panel access হারাবেন। নিশ্চিত হয়ে save করুন।
                                </p>
                            </div>
                        </div>

                        {{-- Save Button --}}
                        <div class="d-flex align-items-center gap-3 mt-4">
                            <button type="submit" class="btn btn-primary" style="width: auto; padding: 10px 28px !important; display: flex; align-items: center; gap: 8px;" id="saveDomainBtn">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Domain Settings
                            </button>
                            <span style="font-size: 11px; color: #9ca3af;">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                Changes update .env file and clear config cache automatically
                            </span>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Maintenance Mode --}}

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="settings-card card {{ (isset($webConfig['maintainance']['status']) && $webConfig['maintainance']['status'] == '1') ? 'maintenance-card-active' : 'maintenance-card-inactive' }}" id="maintenanceCard">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-primary mr-2" style="font-size: 16px;"></i>
                    <h4>Maintenance Mode</h4>
                </div>
                <div class="card-body text-center p-4">
                    <p class="mb-3 text-muted" id="maintenanceText">
                        System Status: 
                        @if (isset($webConfig['maintainance']['status']) && $webConfig['maintainance']['status'] == '1')
                            <span class="badge bg-danger text-white px-3 py-2" id="maintenanceBadge" style="border-radius: 30px; font-weight: 700;">Active (Public Offline)</span>
                        @else
                            <span class="badge bg-success text-white px-3 py-2" id="maintenanceBadge" style="border-radius: 30px; font-weight: 700;">Inactive (Public Online)</span>
                        @endif
                    </p>
                    <div class="d-flex justify-content-center mt-3">
                        <label class="switch">
                            <input class="status-switch" type="checkbox" data-name="maintainance"
                                data-url="{{ route('websettings.maintainance') }}"
                                {{ (isset($webConfig['maintainance']['status']) && $webConfig['maintainance']['status'] == '1') ? 'checked' : '' }}>
                            <span class="slider round" title="Click to Toggle">
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to set up the preview logic for a single element set
            function setupImageUploader(index) {
                const uploadBox = document.getElementById(`upload-box-${index}`);
                const imageInput = document.getElementById(`image-input-${index}`);
                const imagePreview = document.getElementById(`image-preview-${index}`);

                if (!uploadBox || !imageInput || !imagePreview) {
                    console.error(`Missing elements for index ${index}`);
                    return;
                }

                // 1. Make the Box Clickable
                uploadBox.addEventListener('click', function() {
                    imageInput.click();
                });

                // 2. Handle Image Selection and Preview
                imageInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                            uploadBox.classList.add('has-image');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.src = "#";
                        imagePreview.style.display = 'none';
                        uploadBox.classList.remove('has-image');
                    }
                });
            }

            // Initialize the setup for each image field (1, 2, and 3)
            setupImageUploader(1);
            setupImageUploader(2);
            setupImageUploader(3);
        });
    </script>
    <script>
        // ===== Domain Settings Live Preview =====
        function updateDomainPreview() {
            const mainVal  = document.getElementById('appDomainInput')?.value.trim()  || 'looksmen.com';
            const adminVal = document.getElementById('adminDomainInput')?.value.trim() || 'admin.looksmen.com';
            const cleanMain  = mainVal.replace(/^https?:\/\//i, '').replace(/\/+$/, '');
            const cleanAdmin = adminVal.replace(/^https?:\/\//i, '').replace(/\/+$/, '');
            const pt = document.getElementById('mainDomainPreviewText');
            const at = document.getElementById('adminDomainPreviewText');
            if (pt) pt.textContent = 'https://' + cleanMain;
            if (at) at.textContent = 'https://' + cleanAdmin + '/admin/dashboard';
        }

        function copyDomain(type) {
            const el   = type === 'admin' ? document.getElementById('adminDomainPreviewText') : document.getElementById('mainDomainPreviewText');
            const text = el ? el.textContent.trim() : '';
            navigator.clipboard.writeText(text).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = text; document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); document.body.removeChild(ta);
            });
            // Tick feedback
            document.querySelectorAll('[onclick="copyDomain(\'' + type + '\')"] i').forEach(i => {
                i.className = 'fa-solid fa-check';
                setTimeout(() => { i.className = 'fa-regular fa-copy'; }, 1500);
            });
        }

        // Strip https:// if user pastes it
        ['appDomainInput', 'adminDomainInput'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('blur', function () {
                this.value = this.value.replace(/^https?:\/\//i, '').replace(/\/+$/, '');
                updateDomainPreview();
            });
        });

        // SweetAlert2 confirmation before saving domains
        document.getElementById('domainSettingsForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form      = this;
            const mainVal   = (document.getElementById('appDomainInput')?.value  || '').replace(/^https?:\/\//i,'').trim();
            const adminVal  = (document.getElementById('adminDomainInput')?.value || '').replace(/^https?:\/\//i,'').trim();
            Swal.fire({
                title: 'Save Domain Settings?',
                html: `<div style="text-align:left;font-size:13px;line-height:1.7;">
                    <div style="background:#f0fdf4;border-radius:8px;padding:10px 14px;margin-bottom:10px;">
                        <strong>🌐 Storefront:</strong><br><code style="color:#065f46;">https://${mainVal}</code>
                    </div>
                    <div style="background:#eff6ff;border-radius:8px;padding:10px 14px;">
                        <strong>🔒 Admin Panel:</strong><br><code style="color:#1e40af;">https://${adminVal}/admin/dashboard</code>
                    </div></div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Yes, Save!',
                cancelButtonText: 'Cancel'
            }).then(r => { if (r.isConfirmed) form.submit(); });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF Token setup for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.status-switch').on('change', function() {
                const switchElement = $(this);
                const name = switchElement.data('name');
                const url = switchElement.data('url');
                const newStatus = switchElement.is(':checked') ? 1 : 0;

                const actionText = newStatus === 1 ? 'ACTIVATE' : 'DEACTIVATE';
                const confirmColor = newStatus === 1 ? '#dc3545' : '#28a745';

                // --- SweetAlert2 Confirmation ---
                Swal.fire({
                    title: `${actionText} Maintenance Mode?`,
                    text: `Are you sure you want to ${actionText.toLowerCase()} the site?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#6c7784',
                    confirmButtonText: `Yes, ${actionText} it!`
                }).then((result) => {
                    if (result.isConfirmed) {
                        // --- AJAX Request ---
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                name: name,
                                status: newStatus
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Updated!', response.message, 'success');

                                    // Update the switch title dynamically
                                    const title = newStatus === 1 ?
                                        'Click to Deactivate' : 'Click to Activate';
                                    switchElement.next('.slider').attr('title', title);

                                    // Dynamic page style feedback
                                    const badge = $('#maintenanceBadge');
                                    const card = $('#maintenanceCard');
                                    if (newStatus === 1) {
                                        badge.text('Active (Public Offline)').removeClass('bg-success').addClass('bg-danger');
                                        card.removeClass('maintenance-card-inactive').addClass('maintenance-card-active');
                                    } else {
                                        badge.text('Inactive (Public Online)').removeClass('bg-danger').addClass('bg-success');
                                        card.removeClass('maintenance-card-active').addClass('maintenance-card-inactive');
                                    }
                                } else {
                                    Swal.fire('Error!', 'Could not update status.', 'error');
                                    switchElement.prop('checked', !newStatus);
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', 'An error occurred. Check console for details.', 'error');
                                // Revert the switch visually on failure
                                switchElement.prop('checked', !newStatus);
                                console.error(xhr.responseText);
                            }
                        });

                    } else {
                        // User clicked cancel, revert the switch to its original state
                        switchElement.prop('checked', !newStatus);
                    }
                });
            });
        });
    </script>
@endsection
