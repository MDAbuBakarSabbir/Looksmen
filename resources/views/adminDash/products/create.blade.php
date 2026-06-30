@extends('layouts.Backend.master')
@section('title')
    ADD PRODUCT
@endsection
@section('content')
    <style>
        /* Premium UI Overrides */
        body {
            background-color: #f8fafc;
        }
        
        .premium-card {
            background: #ffffff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .premium-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }

        .premium-card-header {
            background-color: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
        }

        .premium-card-body {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
            color: #334155;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            outline: none;
        }

        input:disabled {
            opacity: 0.6;
            background-color: #f1f5f9;
            cursor: not-allowed;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] { -moz-appearance: textfield; }

        /* Premium Image Upload Area */
        .image-upload-wrapper {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .image-upload-box {
            width: 120px;
            height: 120px;
            border: 2px dashed #94a3b8;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            background: #f8fafc;
            transition: all 0.2s ease;
            color: #64748b;
        }

        .image-upload-box:hover {
            background: #f1f5f9;
            border-color: #6366f1;
            color: #6366f1;
        }

        .image-preview-item {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .delete-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ef4444;
            color: #fff;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 12px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.4);
            transition: background 0.2s;
            z-index: 10;
        }
        .delete-btn:hover { background: #dc2626; }

        /* Buttons */
        .btn-premium {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-premium:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn-cancel:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* Select2 Premium Overrides */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            min-height: 42px !important;
            display: flex !important;
            align-items: center !important;
            padding-left: 6px !important;
            box-shadow: none !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e0e7ff !important;
            color: #4338ca !important;
            border: 1px solid #c7d2fe !important;
            border-radius: 6px !important;
            padding: 4px 10px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            margin-top: 5px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #4338ca !important;
            margin-right: 5px !important;
            border-right: 1px solid #c7d2fe !important;
        }
        
        /* Switch Customization */
        .switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: #cbd5e1; transition: .4s; border-radius: 24px;
        }
        .slider:before {
            position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px;
            background-color: white; transition: .4s; border-radius: 50%;
        }
        input:checked + .slider { background-color: #6366f1; }
        input:checked + .slider:before { transform: translateX(22px); }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
    </style>

    <div class="page-header">
        <h1 class="page-title">Create New Product</h1>
        <a href="{{ url('/admin/products') }}" class="btn btn-cancel">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- Left Column: Core Information -->
            <div class="col-lg-8">
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-box text-primary me-2"></i> Basic Details
                    </div>
                    <div class="premium-card-body">
                        <div class="mb-4">
                            <label class="form-label">Product Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" placeholder="Enter product title..." required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Product Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="summernote" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Video URL (Optional)</label>
                                <input class="form-control" type="url" name="video" placeholder="https://youtube.com/...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-images text-primary me-2"></i> Media & Gallery
                    </div>
                    <div class="premium-card-body">
                        <label class="form-label">Product Images <span class="text-danger">*</span> (Max: 10)</label>
                        <p class="text-muted small mb-2">Upload high-quality images. The first image will be the cover.</p>
                        
                        <div class="image-upload-wrapper" id="image-area">
                            <!-- Image Select Label (Replaces buggy onClick) -->
                            <label class="image-upload-box" for="image-input" title="Click to add images">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <span style="font-size: 13px; font-weight: 500;">Add Images</span>
                                <input type="file" name="images[]" id="image-input" multiple accept="image/*" style="display:none;">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Pricing -->
            <div class="col-lg-4">
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-sitemap text-primary me-2"></i> Organization
                    </div>
                    <div class="premium-card-body">
                        <div class="mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control" id="category" name="category_id" required>
                                <option selected disabled value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub Category</label>
                            <select class="form-control" id="subcategory" name="subcategory_id">
                                <option selected disabled value="">Select Sub Category</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Child Category</label>
                            <select class="form-control" id="childcategory" name="childcategory_id">
                                <option selected disabled value="">Select Child Category</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select class="form-control" name="brand_id" id="brand">
                                <option selected disabled value="">Select Brand</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-tags text-primary me-2"></i> Pricing & Inventory
                    </div>
                    <div class="premium-card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Actual Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:#f8fafc; border-color:#cbd5e1;">$</span>
                                    <input type="number" class="form-control" name="old_price" required min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Sale Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:#f8fafc; border-color:#cbd5e1;">$</span>
                                    <input type="number" class="form-control" name="new_price" required min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stock" required min="0">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Points</label>
                                <input type="number" class="form-control" name="points" placeholder="0" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-sliders-h text-primary me-2"></i> Attributes & Settings
                    </div>
                    <div class="premium-card-body">
                        <div class="mb-3">
                            <label class="form-label">Attribute</label>
                            <select id="attribute" class="form-control" name="attribute_id">
                                <option selected disabled value="">Select Attribute</option>
                                @foreach ($attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attribute Values</label>
                            <select id="AttributeValue" class="form-control" name="attributeValue[]" multiple="multiple"></select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Colors</label>
                            <select class="form-control" name="color[]" multiple="multiple" id="colorSelect">
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="p-3 rounded mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Require Advance Payment?</label>
                                <label class="switch">
                                    <input value="0" type="checkbox" name="cod" id="codRequried" onchange="toggleAdvanceAmount()">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div>
                                <input class="form-control" type="number" id="advance_amount" name="advance_amount" placeholder="Advance Amount" disabled min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 mb-5">
            <button type="button" class="btn btn-cancel" onclick="window.history.back()">Cancel</button>
            <button type="submit" class="btn btn-premium">
                <i class="fas fa-save me-1"></i> Save Product
            </button>
        </div>
    </form>
@endsection

@section('script')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        $(document).ready(function() {
            // Initialize Select2
            $('#category').select2({ placeholder: "Select Category", allowClear: true });
            $('#subcategory').select2({ placeholder: "Select Sub Category", allowClear: true });
            $('#childcategory').select2({ placeholder: "Select Child Category", allowClear: true });
            $('#brand').select2({ placeholder: "Select Brand", allowClear: true });
            $('#attribute').select2({ placeholder: "Select Attribute", allowClear: true });
            $('#AttributeValue').select2({ placeholder: "Select Attribute Values", allowClear: true });
            $('#colorSelect').select2({ placeholder: "Select Colors", allowClear: true });

            // Initialize Summernote
            $('#summernote').summernote({
                height: 250,
                placeholder: 'Write premium product description details here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Alerts
            @if (Session::has('success'))
                Toast.fire({ icon: 'success', title: '{{ Session::get('success') }}' });
            @endif
            @if (Session::has('error'))
                Toast.fire({ icon: 'error', title: '{{ Session::get('error') }}' });
            @endif
            @if ($errors->any())
                Toast.fire({ icon: 'error', title: 'Validation Failed: Please check the form fields.' });
            @endif

            // Cascading Category
            $('#category').on('change', function() {
                var categoryID = $(this).val();
                $('#subcategory').html('<option selected disabled value="">Loading...</option>').trigger('change');
                $('#childcategory').html('<option selected disabled value="">Select Child Category</option>').trigger('change');

                if (categoryID) {
                    $.ajax({
                        url: '/admin/get-subcategories/' + categoryID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#subcategory').html('<option selected disabled value="">Select Sub Category</option>');
                            $.each(data, function(key, value) {
                                $('#subcategory').append('<option value="' + key + '">' + value + '</option>');
                            });
                            $('#subcategory').trigger('change');
                        }
                    });
                }
            });

            // Cascading Subcategory
            $('#subcategory').on('change', function() {
                var subcategoryID = $(this).val();
                $('#childcategory').html('<option selected disabled value="">Loading...</option>').trigger('change');

                if (subcategoryID) {
                    $.ajax({
                        url: '/admin/get-childcategories/' + subcategoryID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#childcategory').html('<option selected disabled value="">Select Child Category</option>');
                            $.each(data, function(key, value) {
                                $('#childcategory').append('<option value="' + key + '">' + value + '</option>');
                            });
                            $('#childcategory').trigger('change');
                        }
                    });
                }
            });

            // Cascading Attributes
            $('#attribute').on('change', function() {
                var attributeID = $(this).val();
                $('#AttributeValue').html('<option selected disabled value="">Loading...</option>').trigger('change');

                if (attributeID) {
                    $.ajax({
                        url: '/admin/get-attribute-values/' + attributeID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#AttributeValue').html('');
                            $.each(data, function(key, value) {
                                $('#AttributeValue').append('<option value="' + key + '">' + value + '</option>');
                            });
                            $('#AttributeValue').trigger('change');
                        }
                    });
                }
            });
        });

        function toggleAdvanceAmount() {
            const checkbox = document.getElementById('codRequried');
            const advanceInput = document.getElementById('advance_amount');
            if (checkbox && advanceInput) {
                advanceInput.disabled = !checkbox.checked;
                if(!checkbox.checked) advanceInput.value = '';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleAdvanceAmount);

        // Multiple Images Picker (Fixed Infinite Loop)
        let selectedImages = [];
        const maxImages = 10;

        document.getElementById('image-input').addEventListener('change', function(event) {
            const files = event.target.files;

            if ((selectedImages.length + files.length) > maxImages) {
                Toast.fire({
                    icon: 'warning',
                    title: `You can upload maximum ${maxImages} images!`
                });
                return;
            }

            for (let file of files) {
                selectedImages.push(file);
                showPreview(file);
            }

            updateInputFiles();
        });

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let box = document.createElement('div');
                box.classList.add('image-preview-item');
                box.innerHTML = `
                    <img src="${e.target.result}">
                    <div class="delete-btn" onclick="removeImage(this)"><i class="fas fa-times"></i></div>
                `;
                document.getElementById('image-area').appendChild(box);
            };
            reader.readAsDataURL(file);
        }

        function removeImage(btn) {
            let box = btn.parentElement;
            // Accounting for the first element being the add button (label)
            let index = Array.from(box.parentElement.children).indexOf(box) - 1;

            if (index >= 0 && index < selectedImages.length) {
                selectedImages.splice(index, 1);
                box.remove();
                updateInputFiles();
                
                Toast.fire({
                    icon: 'info',
                    title: 'Image removed'
                });
            }
        }

        function updateInputFiles() {
            const input = document.getElementById('image-input');
            const dt = new DataTransfer();
            selectedImages.forEach(img => dt.items.add(img));
            input.files = dt.files;
        }
    </script>
@endsection
