@extends('layouts.Backend.master')
@section('title')
    SUB CATEGORIES
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
            width: 500px;
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

    {{-- Tabs section --}}
    <div class="d-flex gap-2 mb-4 align-items-center flex-wrap">
        <a class="category-tab-btn" href="{{ route('category.index') }}">
            <i class="fa-solid fa-layer-group"></i> Main Categories
        </a>
        <a class="category-tab-btn active" href="{{ route('sub-category.index') }}">
            <i class="fa-solid fa-tags"></i> Sub Categories
        </a>
        <a class="category-tab-btn" href="{{ route('child-category.index') }}">
            <i class="fa-solid fa-diagram-project"></i> Child Categories
        </a>
    </div>

    {{-- Metrics cards section --}}
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

    {{-- Main view section --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-0">
                    <div class="p-4 d-flex align-items-center justify-content-between flex-wrap border-bottom border-light gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0 font-weight-bold" style="color: #1f2937;">Sub Categories</h4>
                        </div>
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

                    {{-- Dynamic Pop-up Modal --}}
                    <div id="SubCategoryModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header-custom">
                                <h3 class="modal-title-custom">Add Sub Category</h3>
                                <button type="button" class="btn p-0 bg-transparent text-muted" id="closeModalCross" style="font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
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
                                    <button type="button" class="btn-cancel-custom" id="cancelBtn">Cancel</button>
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
                                                <input class="status-switch" type="checkbox" data-id="{{ $subcategory->id }}"
                                                    {{ $subcategory->status == '1' ? 'checked' : '' }}>
                                                <span class="slider round"
                                                    title="{{ $subcategory->status == '1' ? 'Click to Deactivate' : 'Click to Activate' }}">
                                                </span>
                                            </label>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="d-inline-flex gap-2">
                                                <a href="{{ route('sub-category.edit', $subcategory->id) }}"
                                                    class="action-icon-btn edit" title="Edit Subcategory">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="{{ route('sub-category.destroy', $subcategory->id) }}" 
                                                    class="action-icon-btn delete" title="Delete Subcategory"
                                                    onclick="return confirm('Are you sure you want to delete this Subcategory?');">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
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

    <script>
        const modal = document.getElementById('SubCategoryModal');
        const addBtn = document.getElementById('AddSubCategory');
        const cancelBtn = document.getElementById('cancelBtn');
        const closeCross = document.getElementById('closeModalCross');

        // Show modal
        addBtn.onclick = function() {
            modal.classList.add('show');
        }

        // Hide modal
        cancelBtn.onclick = function() {
            modal.classList.remove('show');
        }
        closeCross.onclick = function() {
            modal.classList.remove('show');
        }

        // Hide modal when clicking outside
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        }

        // Search logic
        document.getElementById('subSearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#subTableBody .subcategory-row');
            
            rows.forEach(function(row) {
                let name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                let parent = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (name.indexOf(filter) > -1 || parent.indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });

        document.querySelectorAll('.status-switch').forEach(function(btn) {
            btn.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;

                fetch("{{ route('sub-category.status') }}", {
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
                                title: status == 1 ? 'Subcategory Activated' : 'Subcategory Deactivated'
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
    </script>
@endsection
