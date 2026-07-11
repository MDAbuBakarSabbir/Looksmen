@extends('layouts.Backend.master')
@section('title')
    CATEGORY MANAGEMENT
@endsection
@section('content')
    <style>
        /* --- Premium Admin Style Rules --- */
        .category-tab-btn {
            font-weight: 600;
            padding: 10px 22px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .category-tab-btn.active {
            background: linear-gradient(135deg, #4f46e5, #6366f1) !important;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.25);
            border: none;
        }
        .category-tab-btn:not(.active) {
            background: #f3f4f6 !important;
            color: #4b5563 !important;
            border: 1px solid #e5e7eb;
        }
        .category-tab-btn:not(.active):hover {
            background: #e5e7eb !important;
            color: #1f2937 !important;
        }
        .metrics-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
        }
        .metrics-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
        .metrics-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .table-custom th {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #374151;
            background-color: #f9fafb !important;
            border-bottom: 2px solid #e5e7eb;
            padding: 16px 20px;
        }
        .table-custom td {
            padding: 16px 20px;
            vertical-align: middle;
            font-size: 14px;
            color: #4b5563;
            border-bottom: 1px solid #f3f4f6;
        }
        .badge-type {
            font-weight: 700;
            font-size: 11px;
            padding: 5px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .badge-physical {
            background-color: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }
        .badge-digital {
            background-color: rgba(147, 51, 234, 0.1);
            color: #9333ea;
        }
        .cat-avatar {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 2px;
            background: #fff;
        }
        .action-icon-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        .action-icon-btn.edit {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        .action-icon-btn.edit:hover {
            background: #10b981;
            color: #fff;
        }
        .action-icon-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        .action-icon-btn.delete:hover {
            background: #ef4444;
            color: #fff;
        }
        /* --- Modern Pop-up Modal Customization --- */
        .modal {
            position: fixed;
            z-index: 1060;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .modal.show {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            width: 520px;
            max-width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            border: none;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal.show .modal-content {
            transform: scale(1);
        }
        .modal-header-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .modal-title-custom {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 6px;
            display: block;
        }
        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            margin-bottom: 16px;
            transition: border-color 0.2s;
        }
        .form-control-custom:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .modal-footer-custom {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
            border-top: 1px solid #f3f4f6;
            padding-top: 15px;
        }
        .btn-submit-custom {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-submit-custom:hover {
            opacity: 0.9;
        }
        .btn-cancel-custom {
            background: #f3f4f6;
            color: #4b5563;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 24px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-cancel-custom:hover {
            background: #e5e7eb;
            color: #1f2937;
        }
        
        /* Premium Icon and Image Selector styling */
        .icon-selector-item {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            cursor: pointer;
            font-size: 16px;
            color: #475569;
            transition: all 0.15s ease;
        }
        .icon-selector-item:hover {
            border-color: #4f46e5;
            color: #4f46e5;
            background-color: #f5f3ff;
            transform: translateY(-2px);
        }
        .icon-selector-item.selected {
            background-color: #4f46e5;
            border-color: #4f46e5;
            color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.25);
        }
    </style>

    {{-- Tabs Section --}}
    <div class="d-flex gap-2 mb-4 align-items-center flex-wrap">
        <button class="category-tab-btn {{ $activeTab === 'main' ? 'active' : '' }}" data-tab="main" data-url="{{ route('category.index') }}">
            <i class="fa-solid fa-layer-group"></i> Main Categories
        </button>
        <button class="category-tab-btn {{ $activeTab === 'sub' ? 'active' : '' }}" data-tab="sub" data-url="{{ route('sub-category.index') }}">
            <i class="fa-solid fa-tags"></i> Sub Categories
        </button>
        <button class="category-tab-btn {{ $activeTab === 'child' ? 'active' : '' }}" data-tab="child" data-url="{{ route('child-category.index') }}">
            <i class="fa-solid fa-diagram-project"></i> Child Categories
        </button>
    </div>

    {{-- ========================================================================= --}}
    {{-- TAB 1: MAIN CATEGORIES CONTENT --}}
    {{-- ========================================================================= --}}
    <div id="tab-content-main" class="tab-pane-content {{ $activeTab === 'main' ? '' : 'd-none' }}">
        {{-- Metrics cards section --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Total Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #1f2937;">{{ $maincategorys->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Active Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #10b981;">{{ $maincategorys->where('status', '1')->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Physical Products</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #3b82f6;">{{ $maincategorys->where('type', 'physical')->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                            <i class="fa-solid fa-shirt"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Digital Products</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #9333ea;">{{ $maincategorys->where('type', 'digital')->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(147, 51, 234, 0.1); color: #9333ea;">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-body p-0">
                        <div class="p-4 d-flex align-items-center justify-content-between flex-wrap border-bottom border-light gap-3">
                            <h4 class="mb-0 font-weight-bold" style="color: #1f2937;">Main Categories</h4>
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative">
                                    <i class="fa-solid fa-magnifying-glass text-muted position-absolute" style="left: 12px; top: 12px; font-size: 14px;"></i>
                                    <input type="text" id="categorySearch" class="form-control" placeholder="Search categories..." style="padding-left: 36px; border-radius: 8px; width: 250px; font-size: 13px;">
                                </div>
                                <button id="AddCategory" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none; padding: 9px 18px;">
                                    <i class="fa-solid fa-plus"></i> Add Category
                                </button>
                            </div>
                        </div>

                        {{-- Add Main Category Modal --}}
                        <div id="categoryModal" class="modal">
                            <div class="modal-content">
                                <div class="modal-header-custom">
                                    <h3 class="modal-title-custom">Add Main Category</h3>
                                    <button type="button" class="btn p-0 bg-transparent text-muted close-modal-btn" style="font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label class="form-label-custom">Category Name<span class="text-danger">*</span></label>
                                    <input type="text" name="category_name" class="form-control-custom" placeholder="e.g. Clothing, Accessories" required>

                                    <label class="form-label-custom">Category Type<span class="text-danger">*</span></label>
                                    <select class="form-control-custom form-select" name="type" required>
                                        <option value="" selected disabled>Select Type</option>
                                        <option value="physical">Physical</option>
                                        <option value="digital">Digital</option>
                                    </select>

                                    <label class="form-label-custom">Commission Rate (%)<span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="commission_rate" class="form-control-custom" placeholder="e.g. 5.50" required>

                                    <label class="form-label-custom">Category Banner Image<span class="text-danger">*</span></label>
                                    <div class="image-upload-wrapper border rounded p-3 text-center mb-3" style="border: 2px dashed #cbd5e1; background-color: #f8fafc; border-radius: 12px; cursor: pointer; position: relative; min-height: 120px; display: flex; align-items: center; justify-content: center;">
                                        <input type="file" id="create-image-input" name="image" class="form-control-custom" required style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;">
                                        <div class="image-upload-placeholder" id="create-image-placeholder">
                                            <i class="fa-regular fa-image text-muted mb-2" style="font-size: 32px; display: block; margin: 0 auto;"></i>
                                            <p class="mb-1 text-sm font-weight-bold" style="color: #475569;">Click or Drag & Drop Banner Image here</p>
                                            <span class="text-xs text-muted">Supports JPG, PNG, WEBP (Max 2MB)</span>
                                        </div>
                                        <div class="image-upload-preview d-none" id="create-image-preview-container" style="position: relative; z-index: 3; width: 100%;">
                                            <img id="create-image-preview" src="" alt="Preview" style="max-height: 120px; max-width: 100%; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                            <div class="mt-2">
                                                <button type="button" id="create-image-remove" class="btn btn-xs btn-danger" style="border-radius: 6px; padding: 4px 10px; font-size: 11px;"><i class="fa fa-times mr-1"></i> Remove Image</button>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="form-label-custom">Category Icon Class<span class="text-danger">*</span></label>
                                    <div class="input-group mb-2" style="display: flex;">
                                        <span class="input-group-text bg-light border-custom" id="icon-preview-container" style="border-radius: 8px 0 0 8px; border: 1px solid #cbd5e1; border-right: none; min-width: 46px; display: flex; align-items: center; justify-content: center;">
                                            <i id="create-icon-preview" class="fa-solid fa-icons" style="font-size: 18px; color: #4f46e5;"></i>
                                        </span>
                                        <input type="text" id="create-icon-input" name="icon" class="form-control-custom" placeholder="e.g. fa-solid fa-shirt" required style="border-radius: 0 8px 8px 0; border: 1px solid #cbd5e1; flex: 1;">
                                    </div>

                                    <!-- Icon Selector Grid -->
                                    <div class="icon-selector-wrapper p-3 border rounded mb-3" style="background-color: #f8fafc; max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                                        <label class="small font-weight-bold text-muted d-block mb-2">Search or select popular icon:</label>
                                        <input type="text" id="create-icon-search" class="form-control form-control-sm mb-3" placeholder="Type to filter..." style="border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px; padding: 6px 12px; height: auto;">
                                        <div class="d-flex flex-wrap" id="create-icon-grid" style="gap: 8px;"></div>
                                    </div>

                                    <label class="form-label-custom">Meta Title (Optional)</label>
                                    <input type="text" name="meta_title" class="form-control-custom" placeholder="SEO Title">

                                    <label class="form-label-custom">Meta Description (Optional)</label>
                                    <textarea name="meta_description" class="form-control-custom" rows="3" placeholder="SEO Description"></textarea>

                                    <div class="modal-footer-custom">
                                        <button type="button" class="btn-cancel-custom close-modal-btn">Cancel</button>
                                        <button type="submit" class="btn-submit-custom">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 80px;">#</th>
                                        <th scope="col">Category Name</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Icon Class</th>
                                        <th scope="col">Commission Rate</th>
                                        <th scope="col">Front View</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" style="width: 120px; text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="categoryTableBody">
                                    @forelse ($maincategorys as $index => $maincategory)
                                        <tr class="category-row">
                                            <td class="font-weight-bold">{{ $index + 1 }}</td>
                                            <td class="font-weight-bold text-dark">{{ $maincategory->name }}</td>
                                            <td>
                                                @if($maincategory->banner)
                                                    <img class="cat-avatar shadow-sm" src="{{ asset('Uploads') }}/{{ $maincategory->banner }}" alt="{{ $maincategory->name }}">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted font-weight-bold" style="width: 50px; height: 50px; border-radius: 8px;">N/A</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($maincategory->type == 'digital')
                                                    <span class="badge-type badge-digital">Digital</span>
                                                @else
                                                    <span class="badge-type badge-physical">Physical</span>
                                                @endif
                                            </td>
                                            <td><code style="background: #f3f4f6; color: #e11d48; padding: 4px 8px; border-radius: 4px; font-weight: 600;">{{ $maincategory->icon }}</code></td>
                                            <td>{{ $maincategory->commission_rate ?? 'N/A' }} %</td>
                                            <td>
                                                <label class="switch mb-0">
                                                    <input class="status-switch status-switch-main" type="checkbox" data-id="{{ $maincategory->id }}" {{ $maincategory->status == '1' ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch mb-0">
                                                    <input class="status-switch status-switch-main" type="checkbox" data-id="{{ $maincategory->id }}" {{ $maincategory->status == '1' ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td style="text-align: right;">
                                                <div class="d-inline-flex gap-2">
                                                    <a href="{{ route('category.edit', $maincategory->id) }}" class="action-icon-btn edit" title="Edit Category"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="{{ route('category.destroy', $maincategory->id) }}" class="action-icon-btn delete" title="Delete Category" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                                                <p class="mb-0 font-weight-bold">No Categories Found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- TAB 2: SUB CATEGORIES CONTENT --}}
    {{-- ========================================================================= --}}
    <div id="tab-content-sub" class="tab-pane-content {{ $activeTab === 'sub' ? '' : 'd-none' }}">
        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Total Sub Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #1f2937;">{{ $subcategories->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Active Sub Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #10b981;">{{ $subcategories->where('status', '1')->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Inactive Sub Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #ef4444;">{{ $subcategories->where('status', '0')->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Parent Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #3b82f6;">{{ $categories->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-body p-0">
                        <div class="p-4 d-flex align-items-center justify-content-between flex-wrap border-bottom border-light gap-3">
                            <h4 class="mb-0 font-weight-bold" style="color: #1f2937;">Sub Categories</h4>
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative">
                                    <i class="fa-solid fa-magnifying-glass text-muted position-absolute" style="left: 12px; top: 12px; font-size: 14px;"></i>
                                    <input type="text" id="subSearch" class="form-control" placeholder="Search subcategories..." style="padding-left: 36px; border-radius: 8px; width: 250px; font-size: 13px;">
                                </div>
                                <button id="AddSubCategory" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none; padding: 9px 18px;">
                                    <i class="fa-solid fa-plus"></i> Add Sub Category
                                </button>
                            </div>
                        </div>

                        {{-- Add Sub Category Modal --}}
                        <div id="SubCategoryModal" class="modal">
                            <div class="modal-content">
                                <div class="modal-header-custom">
                                    <h3 class="modal-title-custom">Add Sub Category</h3>
                                    <button type="button" class="btn p-0 bg-transparent text-muted close-modal-btn" style="font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <form action="{{ route('sub-category.store') }}" method="POST">
                                    @csrf
                                    <label class="form-label-custom">Select Main Category<span class="text-danger">*</span></label>
                                    <select class="form-control-custom form-select" name="category_id" required>
                                        <option value="" selected disabled>Select Main Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    <label class="form-label-custom">Sub Category Name<span class="text-danger">*</span></label>
                                    <input type="text" name="subcategory_name" class="form-control-custom" placeholder="e.g. T-shirts, Sneakers" required>

                                    <label class="form-label-custom">Meta Title (Optional)</label>
                                    <input type="text" name="meta_title" class="form-control-custom" placeholder="SEO Title">

                                    <label class="form-label-custom">Meta Description (Optional)</label>
                                    <textarea name="meta_description" class="form-control-custom" rows="3" placeholder="SEO Description"></textarea>

                                    <div class="modal-footer-custom">
                                        <button type="button" class="btn-cancel-custom close-modal-btn">Cancel</button>
                                        <button type="submit" class="btn-submit-custom">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 80px;">#</th>
                                        <th scope="col">Sub Category Name</th>
                                        <th scope="col">Parent Main Category</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" style="width: 120px; text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="subTableBody">
                                    @forelse ($subcategories as $index => $subcategory)
                                        <tr class="subcategory-row">
                                            <td class="font-weight-bold">{{ $index + 1 }}</td>
                                            <td class="font-weight-bold text-dark">{{ $subcategory->name }}</td>
                                            <td>
                                                <span class="badge" style="background: #f3f4f6; color: #4f46e5; border: 1px solid rgba(79, 70, 229, 0.15); font-weight: 600; padding: 5px 12px; border-radius: 6px;">
                                                    {{ $subcategory->category->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <label class="switch mb-0">
                                                    <input class="status-switch status-switch-sub" type="checkbox" data-id="{{ $subcategory->id }}" {{ $subcategory->status == '1' ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td style="text-align: right;">
                                                <div class="d-inline-flex gap-2">
                                                    <a href="{{ route('sub-category.edit', $subcategory->id) }}" class="action-icon-btn edit" title="Edit Subcategory"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="{{ route('sub-category.destroy', $subcategory->id) }}" class="action-icon-btn delete" title="Delete Subcategory" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                                                <p class="mb-0 font-weight-bold">No Subcategories Found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- TAB 3: CHILD CATEGORIES CONTENT --}}
    {{-- ========================================================================= --}}
    <div id="tab-content-child" class="tab-pane-content {{ $activeTab === 'child' ? '' : 'd-none' }}">
        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Total Child Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #1f2937;">{{ $childcategories->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                            <i class="fa-solid fa-diagram-project"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Active Child Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #10b981;">{{ $childcategories->where('status', '1')->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Inactive Child Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #ef4444;">{{ $childcategories->where('status', '0')->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="metrics-card card p-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Parent Sub Categories</p>
                            <h3 class="mb-0 font-weight-bold" style="color: #3b82f6;">{{ $subcategories->count() }}</h3>
                        </div>
                        <div class="metrics-icon-box" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-body p-0">
                        <div class="p-4 d-flex align-items-center justify-content-between flex-wrap border-bottom border-light gap-3">
                            <h4 class="mb-0 font-weight-bold" style="color: #1f2937;">Child Categories</h4>
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative">
                                    <i class="fa-solid fa-magnifying-glass text-muted position-absolute" style="left: 12px; top: 12px; font-size: 14px;"></i>
                                    <input type="text" id="childSearch" class="form-control" placeholder="Search child categories..." style="padding-left: 36px; border-radius: 8px; width: 250px; font-size: 13px;">
                                </div>
                                <button id="AddChildCategory" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none; padding: 9px 18px;">
                                    <i class="fa-solid fa-plus"></i> Add Child Category
                                </button>
                            </div>
                        </div>

                        {{-- Add Child Category Modal --}}
                        <div id="ChildCategoryModal" class="modal">
                            <div class="modal-content">
                                <div class="modal-header-custom">
                                    <h3 class="modal-title-custom">Add Child Category</h3>
                                    <button type="button" class="btn p-0 bg-transparent text-muted close-modal-btn" style="font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <form action="{{ route('child-category.store') }}" method="POST">
                                    @csrf
                                    <label class="form-label-custom">Select Main Category<span class="text-danger">*</span></label>
                                    <select class="form-control-custom form-select" name="category_id" required>
                                        <option value="" selected disabled>Select Main Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    <label class="form-label-custom">Select Sub Category<span class="text-danger">*</span></label>
                                    <select class="form-control-custom form-select" name="subCategory_id" required>
                                        <option value="" selected disabled>Select Sub Category</option>
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" data-parent="{{ $subcategory->category_id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>

                                    <label class="form-label-custom">Child Category Name<span class="text-danger">*</span></label>
                                    <input type="text" name="childcategory_name" class="form-control-custom" placeholder="e.g. Slim Fit Shirts" required>

                                    <label class="form-label-custom">Meta Title (Optional)</label>
                                    <input type="text" name="meta_title" class="form-control-custom" placeholder="SEO Title">

                                    <label class="form-label-custom">Meta Description (Optional)</label>
                                    <textarea name="meta_description" class="form-control-custom" rows="3" placeholder="SEO Description"></textarea>

                                    <div class="modal-footer-custom">
                                        <button type="button" class="btn-cancel-custom close-modal-btn">Cancel</button>
                                        <button type="submit" class="btn-submit-custom">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 80px;">#</th>
                                        <th scope="col">Child Category Name</th>
                                        <th scope="col">Parent Main Category</th>
                                        <th scope="col">Parent Sub Category</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" style="width: 120px; text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="childTableBody">
                                    @forelse ($childcategories as $index => $childcategory)
                                        <tr class="childcategory-row">
                                            <td class="font-weight-bold">{{ $index + 1 }}</td>
                                            <td class="font-weight-bold text-dark">{{ $childcategory->name }}</td>
                                            <td>
                                                <span class="badge" style="background: #f3f4f6; color: #4f46e5; border: 1px solid rgba(79, 70, 229, 0.15); font-weight: 600; padding: 5px 12px; border-radius: 6px;">
                                                    {{ $childcategory->category->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge" style="background: rgba(16, 185, 129, 0.08); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.15); font-weight: 600; padding: 5px 12px; border-radius: 6px;">
                                                    {{ $childcategory->subcategory->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <label class="switch mb-0">
                                                    <input class="status-switch status-switch-child" type="checkbox" data-id="{{ $childcategory->id }}" {{ $childcategory->status == '1' ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td style="text-align: right;">
                                                <div class="d-inline-flex gap-2">
                                                    <a href="{{ route('child-category.edit', $childcategory->id) }}" class="action-icon-btn edit" title="Edit Child Category"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="{{ route('child-category.destroy', $childcategory->id) }}" class="action-icon-btn delete" title="Delete Child Category" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                                                <p class="mb-0 font-weight-bold">No Child Categories Found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script definitions --}}
    <script>
        // Tab switching and history pushing logic
        document.querySelectorAll('.category-tab-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                // Deactivate all tab buttons
                document.querySelectorAll('.category-tab-btn').forEach(btn => btn.classList.remove('active'));
                // Activate clicked tab button
                this.classList.add('active');

                // Hide all tab panes
                document.querySelectorAll('.tab-pane-content').forEach(pane => pane.classList.add('d-none'));
                // Show the target tab pane
                const targetTab = this.getAttribute('data-tab');
                document.getElementById('tab-content-' + targetTab).classList.remove('d-none');

                // Push state to browser history (Reload-Free URL mapping!)
                const targetUrl = this.getAttribute('data-url');
                history.pushState(null, null, targetUrl);
            });
        });

        // Handle browser Back/Forward navigation to restore active tab
        window.addEventListener('popstate', function() {
            const currentPath = window.location.pathname;
            let targetTab = 'main';

            if (currentPath.includes('sub-category')) {
                targetTab = 'sub';
            } else if (currentPath.includes('child-category')) {
                targetTab = 'child';
            }

            // Trigger tab button click visually
            const matchingBtn = document.querySelector(`.category-tab-btn[data-tab="${targetTab}"]`);
            if (matchingBtn && !matchingBtn.classList.contains('active')) {
                matchingBtn.click();
            }
        });
    </script>

    <script>
        // Modal toggles helper
        function setupModal(modalId, btnId) {
            const modal = document.getElementById(modalId);
            const openBtn = document.getElementById(btnId);

            if (openBtn && modal) {
                openBtn.onclick = function() {
                    modal.classList.add('show');
                }
                modal.querySelectorAll('.close-modal-btn').forEach(function(closeBtn) {
                    closeBtn.onclick = function() {
                        modal.classList.remove('show');
                    }
                });
                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        modal.classList.remove('show');
                    }
                });
            }
        }

        setupModal('categoryModal', 'AddCategory');
        setupModal('SubCategoryModal', 'AddSubCategory');
        setupModal('ChildCategoryModal', 'AddChildCategory');

        // Dynamic subcategory filtering in child category modal
        $('select[name="category_id"]').on('change', function() {
            var category_id = $(this).val();
            if (category_id) {
                $.ajax({
                    url: '/admin/get-subcategories/' + category_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        var $select = $('select[name="subCategory_id"]');
                        $select.empty();
                        $select.append('<option value="" selected disabled>Select Sub Category</option>');
                        $.each(data, function(key, value) {
                            $select.append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            }
        });
    </script>

    <script>
        // Searches setup
        function setupSearch(inputId, tableBodyId, rowClass, cellIndexName, cellIndexParent, cellIndexSub) {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('keyup', function() {
                    let filter = this.value.toLowerCase();
                    let rows = document.querySelectorAll(`#${tableBodyId} .${rowClass}`);
                    
                    rows.forEach(function(row) {
                        let matches = false;
                        let textName = row.querySelector(`td:nth-child(${cellIndexName})`).textContent.toLowerCase();
                        if (textName.indexOf(filter) > -1) matches = true;

                        if (cellIndexParent) {
                            let textParent = row.querySelector(`td:nth-child(${cellIndexParent})`).textContent.toLowerCase();
                            if (textParent.indexOf(filter) > -1) matches = true;
                        }

                        if (cellIndexSub) {
                            let textSub = row.querySelector(`td:nth-child(${cellIndexSub})`).textContent.toLowerCase();
                            if (textSub.indexOf(filter) > -1) matches = true;
                        }

                        row.style.display = matches ? '' : 'none';
                    });
                });
            }
        }

        setupSearch('categorySearch', 'categoryTableBody', 'category-row', 2);
        setupSearch('subSearch', 'subTableBody', 'subcategory-row', 2, 3);
        setupSearch('childSearch', 'childTableBody', 'childcategory-row', 2, 3, 4);
    </script>

    <script>
        // Ajax status switches
        function setupStatusSwitch(selector, url, successMsg) {
            document.querySelectorAll(selector).forEach(function(btn) {
                btn.addEventListener('change', function() {
                    let id = this.getAttribute('data-id');
                    let status = this.checked ? 1 : 0;

                    fetch(url, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id: id,
                                status: status
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: status == 1 ? successMsg + ' Activated' : successMsg + ' Deactivated'
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Something went wrong'
                                });
                            }
                        })
                        .catch(err => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Server Error'
                            });
                        });
                });
            });
        }

        setupStatusSwitch('.status-switch-main', "{{ route('category.status') }}", "Category");
        setupStatusSwitch('.status-switch-sub', "{{ route('sub-category.status') }}", "Subcategory");
        setupStatusSwitch('.status-switch-child', "{{ route('child-category.status') }}", "Child Category");

        // Wrap in DOMContentLoaded to ensure elements are parsed before setup runs
        document.addEventListener('DOMContentLoaded', function() {
            // Popular icons list (Free Font Awesome Solid Icons)
            const popularIcons = [
                // Clothing & Fashion
                { class: 'fa-solid fa-shirt', tags: 'shirt clothing apparel top dress tshirt fashion wear' },
                { class: 'fa-solid fa-bag-shopping', tags: 'bag shopping handbag accessories purse case pocket cart' },
                { class: 'fa-solid fa-shoe-prints', tags: 'shoe boot prints walk sneaker feet footwear' },
                { class: 'fa-solid fa-glasses', tags: 'glasses eye sun spectacles fashion vision' },
                { class: 'fa-solid fa-hat-cowboy', tags: 'hat cowboy fashion cap headwear cap helmet' },
                { class: 'fa-solid fa-gem', tags: 'gem jewel jewelry diamond stone ring luxury gold' },
                { class: 'fa-solid fa-clock', tags: 'clock watch time timer schedule wristwatch' },
                { class: 'fa-solid fa-socks', tags: 'socks foot clothing fashion' },
                { class: 'fa-solid fa-vest', tags: 'vest clothing fashion jacket' },
                { class: 'fa-solid fa-crown', tags: 'crown king queen royal gold jewelry' },
                
                // Electronics, Devices & Gadgets
                { class: 'fa-solid fa-laptop', tags: 'laptop computer electronics monitor dev screen tech pc' },
                { class: 'fa-solid fa-mobile-screen-button', tags: 'mobile phone phone screens cellular smart mobile device tech' },
                { class: 'fa-solid fa-headphones', tags: 'headphones sound music audio listen speaker gadget headset' },
                { class: 'fa-solid fa-tv', tags: 'tv television screen display monitor video television' },
                { class: 'fa-solid fa-camera', tags: 'camera photo picture video lens capture photography' },
                { class: 'fa-solid fa-gamepad', tags: 'gamepad game controller console playstation xbox play joystick gaming' },
                { class: 'fa-solid fa-plug', tags: 'plug cable electricity charge power wire' },
                { class: 'fa-solid fa-battery-three-quarters', tags: 'battery power charge energy' },
                { class: 'fa-solid fa-print', tags: 'print printer ink paper office hardcopy' },
                { class: 'fa-solid fa-keyboard', tags: 'keyboard typing board input tech key' },
                { class: 'fa-solid fa-mouse', tags: 'mouse click pointer input tech' },
                { class: 'fa-solid fa-desktop', tags: 'desktop monitor screen pc computer' },
                { class: 'fa-solid fa-tablet-screen-button', tags: 'tablet ipad screen mobile device' },
                { class: 'fa-solid fa-sim-card', tags: 'sim card memory micro chip' },
                { class: 'fa-solid fa-wifi', tags: 'wifi internet signal connect network' },
                
                // Home, Kitchen & Living
                { class: 'fa-solid fa-house', tags: 'house home building apartment living address residency' },
                { class: 'fa-solid fa-couch', tags: 'couch sofa furniture lounge home seat chair sofa' },
                { class: 'fa-solid fa-bed', tags: 'bed sleep hotel room furniture rest' },
                { class: 'fa-solid fa-bath', tags: 'bath shower tub bathroom wash clean restroom' },
                { class: 'fa-solid fa-faucet', tags: 'faucet water tap sink plumbing' },
                { class: 'fa-solid fa-lightbulb', tags: 'lightbulb light lamp idea brainstorm bright electricity' },
                { class: 'fa-solid fa-soap', tags: 'soap bubble clean hygiene wash liquid' },
                { class: 'fa-solid fa-door-open', tags: 'door open entry gate exit home' },
                { class: 'fa-solid fa-fan', tags: 'fan wind cool air ventilation machine' },
                { class: 'fa-solid fa-key', tags: 'key lock secret security login password access' },
                { class: 'fa-solid fa-lock', tags: 'lock key security protect private safety pad' },
                
                // Grocery, Food & Beverage
                { class: 'fa-solid fa-basket-shopping', tags: 'basket shopping grocery food cart shop market supermarket container' },
                { class: 'fa-solid fa-utensils', tags: 'utensils food restaurant kitchen cooking spoon fork plate diner' },
                { class: 'fa-solid fa-bowl-food', tags: 'bowl food meal rice soup kitchen breakfast lunch dinner' },
                { class: 'fa-solid fa-cake-candles', tags: 'cake candles birthday sweet dessert bake party celebration' },
                { class: 'fa-solid fa-cookie-bite', tags: 'cookie bite sweet biscuit bakery food snack' },
                { class: 'fa-solid fa-apple-whole', tags: 'apple whole fruit healthy food agriculture fresh nature red' },
                { class: 'fa-solid fa-carrot', tags: 'carrot vegetable healthy food kitchen orange veggie' },
                { class: 'fa-solid fa-fish', tags: 'fish seafood animal ocean cooking raw water' },
                { class: 'fa-solid fa-mug-hot', tags: 'mug hot coffee tea drink cafe beverage espresso milk' },
                { class: 'fa-solid fa-wine-glass', tags: 'wine glass drink bar alcohol beverage party liquid' },
                { class: 'fa-solid fa-bottle-water', tags: 'bottle water drink beverage container mineral plastic' },
                { class: 'fa-solid fa-ice-cream', tags: 'ice cream sweet dessert cold summer cone' },
                { class: 'fa-solid fa-burger', tags: 'burger fast food hamburger cheese kitchen snack bread' },
                { class: 'fa-solid fa-pizza-slice', tags: 'pizza slice cheese fast food snack kitchen italian' },
                { class: 'fa-solid fa-egg', tags: 'egg food breakfast chicken egg protein' },
                { class: 'fa-solid fa-cheese', tags: 'cheese dairy yellow food snack' },
                { class: 'fa-solid fa-pepper-hot', tags: 'pepper chili spicy hot food vegetable spice' },
                
                // Health, Pharmacy, Beauty & Personal Care
                { class: 'fa-solid fa-heart', tags: 'heart health medical love care safety life red' },
                { class: 'fa-solid fa-stethoscope', tags: 'stethoscope doctor medical clinic hospital health nurse' },
                { class: 'fa-solid fa-briefcase-medical', tags: 'briefcase medical aid box kit firstaid rescue' },
                { class: 'fa-solid fa-pills', tags: 'pills drug medicine tablet health pharmacy pharmacy care' },
                { class: 'fa-solid fa-wand-magic-sparkles', tags: 'wand magic beauty cosmetic sparkle makeup lipstick blush' },
                { class: 'fa-solid fa-comb', tags: 'comb hair barber salon style grooming comb' },
                { class: 'fa-solid fa-spray-can-sparkles', tags: 'spray can perfume aerosol cosmetic fragrance' },
                { class: 'fa-solid fa-tooth', tags: 'tooth dental dentist health hygiene clean mouth' },
                { class: 'fa-solid fa-wheelchair', tags: 'wheelchair access patient medical health sign' },
                
                // Baby & Kids
                { class: 'fa-solid fa-baby-carriage', tags: 'baby carriage stroller kid child parenting infant toddler' },
                { class: 'fa-solid fa-child', tags: 'child kid person human family play' },
                { class: 'fa-solid fa-palette', tags: 'palette paint color art draw creative canvas artist painting craft' },
                { class: 'fa-solid fa-puzzle-piece', tags: 'puzzle piece game toy child thinking match' },
                
                // Sports, Toys & Hobbies
                { class: 'fa-solid fa-soccer-ball', tags: 'soccer ball football sport athletic game play sphere' },
                { class: 'fa-solid fa-basketball', tags: 'basketball ball sport play athletic orange hoop' },
                { class: 'fa-solid fa-dumbbell', tags: 'dumbbell gym fitness workout weight muscle train lift' },
                { class: 'fa-solid fa-bicycle', tags: 'bicycle bike wheel ride cycling travel exercise' },
                { class: 'fa-solid fa-music', tags: 'music song note sound instrument melody tune sound' },
                { class: 'fa-solid fa-guitar', tags: 'guitar music instrument audio play rock acoustic string' },
                { class: 'fa-solid fa-book-open', tags: 'book open read study novel library paper text literature' },
                { class: 'fa-solid fa-compass', tags: 'compass direction travel map navigate north find route' },
                { class: 'fa-solid fa-trophy', tags: 'trophy win award prize gold achieve champion' },
                { class: 'fa-solid fa-medal', tags: 'medal award win place prize ribbon' },
                
                // Vehicles & Transport
                { class: 'fa-solid fa-car', tags: 'car auto automobile motor vehicle travel drive ride' },
                { class: 'fa-solid fa-truck', tags: 'truck vehicle transport delivery shipping logistics cargo van freight' },
                { class: 'fa-solid fa-motorcycle', tags: 'motorcycle bike speed vehicle ride motor' },
                { class: 'fa-solid fa-plane', tags: 'plane airplane flight airport travel fly journey sky' },
                { class: 'fa-solid fa-ship', tags: 'ship boat water cruise transport travel ocean ferry' },
                { class: 'fa-solid fa-helicopter', tags: 'helicopter fly vehicle transport air chopper' },
                { class: 'fa-solid fa-bus', tags: 'bus coach vehicle travel transport school group' },
                { class: 'fa-solid fa-train', tags: 'train rail vehicle travel transport station' },
                
                // Tools & Hardware
                { class: 'fa-solid fa-wrench', tags: 'wrench tool repair hardware construct fix hammer toolbox utility' },
                { class: 'fa-solid fa-hammer', tags: 'hammer tool construction build hardware forge tool' },
                { class: 'fa-solid fa-screwdriver', tags: 'screwdriver tool repair hardware fix screwdriver' },
                { class: 'fa-solid fa-scissors', tags: 'scissors cut paper salon tailor tool office blade' },
                { class: 'fa-solid fa-box', tags: 'box package storage delivery container carton package post' },
                { class: 'fa-solid fa-toolbox', tags: 'toolbox tools storage hardware kit repair' },
                { class: 'fa-solid fa-shield-halved', tags: 'shield security protect guard safety defense armor' },
                
                // Office & Finance
                { class: 'fa-solid fa-briefcase', tags: 'briefcase work job business office bag portfolio bag' },
                { class: 'fa-solid fa-envelope', tags: 'envelope mail letter post message inbox receive send' },
                { class: 'fa-solid fa-paperclip', tags: 'paperclip clip attach file document office office link' },
                { class: 'fa-solid fa-credit-card', tags: 'credit card payment money bank finance purchase plastic' },
                { class: 'fa-solid fa-wallet', tags: 'wallet money finance cash pay card pocket cash' },
                { class: 'fa-solid fa-coins', tags: 'coins money gold cash wealth finance change gold' },
                { class: 'fa-solid fa-dollar-sign', tags: 'dollar sign money finance cash currency usd exchange' },
                { class: 'fa-solid fa-chart-line', tags: 'chart line graph growth finance business statistics up' },
                { class: 'fa-solid fa-calculator', tags: 'calculator math compute numbers office finance math' },
                { class: 'fa-solid fa-piggy-bank', tags: 'piggy bank money savings gold finance invest' },
                
                // Animals, Plants & Nature
                { class: 'fa-solid fa-paw', tags: 'paw pet animal dog cat vet footprint tracks' },
                { class: 'fa-solid fa-dog', tags: 'dog pet animal canine friend doggy' },
                { class: 'fa-solid fa-cat', tags: 'cat pet animal feline meow kitten' },
                { class: 'fa-solid fa-leaf', tags: 'leaf plant nature green tree organic bio environmental' },
                { class: 'fa-solid fa-tree', tags: 'tree nature forest wood green pine environmental' },
                { class: 'fa-solid fa-seedling', tags: 'seedling plant grow agriculture nature sprout soil farming' },
                { class: 'fa-solid fa-umbrella', tags: 'umbrella rain weather protection shade sun weather' },
                { class: 'fa-solid fa-snowflake', tags: 'snowflake ice cold winter snow weather freeze' },
                { class: 'fa-solid fa-sun', tags: 'sun summer weather bright warm day light shine' },
                { class: 'fa-solid fa-cloud', tags: 'cloud weather sky rain cloudy overcast' },
                { class: 'fa-solid fa-fire', tags: 'fire hot flame burn cook heat energy' },
                { class: 'fa-solid fa-bolt', tags: 'bolt lightning flash power energy screen storm spark' },
                
                // Miscellaneous, UI & Symbols
                { class: 'fa-solid fa-user', tags: 'user person member account profile human avatar' },
                { class: 'fa-solid fa-users', tags: 'users group people family team audience gathering social' },
                { class: 'fa-solid fa-star', tags: 'star rate favorite gold like bookmark quality review' },
                { class: 'fa-solid fa-thumbs-up', tags: 'thumbsup like good positive approve agree click' },
                { class: 'fa-solid fa-bell', tags: 'bell alert notify ring alarm sound push' },
                { class: 'fa-solid fa-map-pin', tags: 'map pin location gps address marker navigate locate destination' },
                { class: 'fa-solid fa-search', tags: 'search find zoom spy glass look detect zoom glass' },
                { class: 'fa-solid fa-share-nodes', tags: 'share send network connect social share nodes' },
                { class: 'fa-solid fa-trash-can', tags: 'trash can delete bin discard waste empty' },
                { class: 'fa-solid fa-flag', tags: 'flag nation mark indicator coordinate nation banner' },
                { class: 'fa-solid fa-heart-pulse', tags: 'heart pulse heartbeat medical ecg hospital' },
                { class: 'fa-solid fa-circle-info', tags: 'info circle details description text' },
                { class: 'fa-solid fa-circle-question', tags: 'question query help circle support faq' },
                { class: 'fa-solid fa-circle-check', tags: 'check circle correct pass success yes right' },
                { class: 'fa-solid fa-circle-xmark', tags: 'xmark circle cancel incorrect error fail cross no' }
            ];

            // 1. Image Preview Logic for Category Creation Modal
            const createImageInput = document.getElementById('create-image-input');
            const createImagePlaceholder = document.getElementById('create-image-placeholder');
            const createImagePreviewContainer = document.getElementById('create-image-preview-container');
            const createImagePreview = document.getElementById('create-image-preview');
            const createImageRemove = document.getElementById('create-image-remove');

            if (createImageInput) {
                createImageInput.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            createImagePreview.src = event.target.result;
                            createImagePlaceholder.classList.add('d-none');
                            createImagePreviewContainer.classList.remove('d-none');
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }

            if (createImageRemove) {
                createImageRemove.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    createImageInput.value = '';
                    createImagePreview.src = '';
                    createImagePreviewContainer.classList.add('d-none');
                    createImagePlaceholder.classList.remove('d-none');
                });
            }

            // 2. Icon Selection Grid & Search Logic for Category Creation Modal
            const createIconGrid = document.getElementById('create-icon-grid');
            const createIconInput = document.getElementById('create-icon-input');
            const createIconPreview = document.getElementById('create-icon-preview');
            const createIconSearch = document.getElementById('create-icon-search');

            if (createIconGrid && createIconInput && createIconPreview) {
                function renderIconGrid(filter = '') {
                    createIconGrid.innerHTML = '';
                    popularIcons.forEach(icon => {
                        if (filter === '' || icon.class.includes(filter) || icon.tags.includes(filter)) {
                            const iconBtn = document.createElement('div');
                            iconBtn.className = 'icon-selector-item';
                            if (createIconInput.value === icon.class) {
                                iconBtn.classList.add('selected');
                            }
                            iconBtn.innerHTML = `<i class="${icon.class}"></i>`;
                            iconBtn.title = icon.class;
                            
                            iconBtn.addEventListener('click', function() {
                                document.querySelectorAll('#create-icon-grid .icon-selector-item').forEach(el => el.classList.remove('selected'));
                                this.classList.add('selected');
                                createIconInput.value = icon.class;
                                createIconPreview.className = icon.class;
                            });
                            
                            createIconGrid.appendChild(iconBtn);
                        }
                    });
                }

                renderIconGrid();

                if (createIconSearch) {
                    createIconSearch.addEventListener('input', function() {
                        renderIconGrid(this.value.toLowerCase().trim());
                    });
                }

                createIconInput.addEventListener('input', function() {
                    const val = this.value.trim();
                    createIconPreview.className = val || 'fa-solid fa-icons';
                    
                    document.querySelectorAll('#create-icon-grid .icon-selector-item').forEach(el => {
                        const iconEl = el.querySelector('i');
                        if (iconEl && iconEl.className === val) {
                            el.classList.add('selected');
                        } else {
                            el.classList.remove('selected');
                        }
                    });
                });
            }
        });
    </script>
@endsection
