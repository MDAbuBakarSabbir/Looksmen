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
                                    <input type="file" name="image" class="form-control-custom" required>

                                    <label class="form-label-custom">Category Icon Class<span class="text-danger">*</span></label>
                                    <input type="text" name="icon" class="form-control-custom" placeholder="e.g. fa-shirt" required>

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
                                                    <img class="cat-avatar shadow-sm" src="{{ asset('adminDash/assets/img/category') }}/{{ $maincategory->banner }}" alt="{{ $maincategory->name }}">
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
                            $select.append('<option value="' + value.id + '">' + value.name + '</option>');
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
    </script>
@endsection
