@extends('layouts.Backend.master')
@section('title')
    ADD PRODUCT
@endsection
@section('content')

    <div class="page-header-custom">
        <h1 class="page-title-custom">Create New Product</h1>
        <a href="{{ url('/admin/products') }}" class="btn-cancel-custom">
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
                            <!-- Image Select Label — input is OUTSIDE the label to prevent double-trigger -->
                            <label class="image-upload-box" for="image-input" title="Click to add images">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <span style="font-size: 13px; font-weight: 500;">Add Images</span>
                            </label>
                        </div>
                        <!-- Hidden file input outside label to avoid double-trigger bug -->
                        <input type="file" name="images[]" id="image-input" multiple accept="image/*" style="display:none;">
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
                            <div style="width:100%">
                                <select id="AttributeValue" class="form-control" name="attributeValue[]" multiple="multiple" style="width:100%"></select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Colors</label>
                            <div style="width:100%">
                                <select class="form-control" name="color[]" multiple="multiple" id="colorSelect" style="width:100%">
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                                    @endforeach
                                </select>
                            </div>
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
            <button type="button" class="btn-cancel-custom" onclick="window.history.back()">Cancel</button>
            <button type="submit" class="btn-premium">
                <i class="fas fa-save me-1"></i> Save Product
            </button>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for single selects
            $('#category').select2({ placeholder: "Select Category", allowClear: true, width: '100%' });
            $('#subcategory').select2({ placeholder: "Select Sub Category", allowClear: true, width: '100%' });
            $('#childcategory').select2({ placeholder: "Select Child Category", allowClear: true, width: '100%' });
            $('#brand').select2({ placeholder: "Select Brand", allowClear: true, width: '100%' });
            $('#attribute').select2({ placeholder: "Select Attribute", allowClear: true, width: '100%' });

            // Initialize Select2 for multi-selects
            $('#AttributeValue').select2({ placeholder: "Select Attribute Values", allowClear: true, width: '100%' });
            $('#colorSelect').select2({ placeholder: "Select Colors", allowClear: true, width: '100%' });

            // Initialize Summernote rich text editor
            $('#summernote').summernote({
                height: 300,
                placeholder: 'Write product description here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // FIX: sync Summernote content into the real textarea before form submit
            $('form').on('submit', function() {
                $('#summernote').val($('#summernote').summernote('code'));
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

            // Cascading Category → Subcategory
            $('#category').on('change', function() {
                var categoryID = $(this).val();
                $('#subcategory').html('<option selected disabled value="">Loading...</option>').trigger('change');
                $('#childcategory').html('<option selected disabled value="">Select Child Category</option>').trigger('change');

                if (categoryID) {
                    $.ajax({
                        url: '/admin/get-subcategories/' + categoryID,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#subcategory').html('<option selected disabled value="">Select Sub Category</option>');
                            $.each(data, function(key, value) {
                                $('#subcategory').append('<option value="' + key + '">' + value + '</option>');
                            });
                        }
                    });
                }
            });

            // Cascading Subcategory → Child Category
            $('#subcategory').on('change', function() {
                var subcategoryID = $(this).val();
                $('#childcategory').html('<option selected disabled value="">Loading...</option>').trigger('change');

                if (subcategoryID) {
                    $.ajax({
                        url: '/admin/get-childcategories/' + subcategoryID,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#childcategory').html('<option selected disabled value="">Select Child Category</option>');
                            $.each(data, function(key, value) {
                                $('#childcategory').append('<option value="' + key + '">' + value + '</option>');
                            });
                        }
                    });
                }
            });

            // Cascading Attribute → Attribute Values
            // FIX: destroy Select2, repopulate options, then re-initialize Select2
            $('#attribute').on('change', function() {
                var attributeID = $(this).val();

                // Reset and show loading state
                if ($.fn.select2 && $('#AttributeValue').data('select2')) {
                    $('#AttributeValue').select2('destroy');
                }
                $('#AttributeValue').html('<option disabled>Loading values...</option>');

                if (attributeID) {
                    $.ajax({
                        url: '/admin/get-attribute-values/' + attributeID,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#AttributeValue').html('');
                            $.each(data, function(key, value) {
                                $('#AttributeValue').append('<option value="' + key + '">' + value + '</option>');
                            });
                            // Re-initialize Select2 after populating options
                            $('#AttributeValue').select2({ placeholder: 'Select Attribute Values', allowClear: true, width: '100%' });
                        },
                        error: function() {
                            $('#AttributeValue').html('');
                            $('#AttributeValue').select2({ placeholder: 'Error loading values', allowClear: true, width: '100%' });
                        }
                    });
                } else {
                    $('#AttributeValue').html('');
                    $('#AttributeValue').select2({ placeholder: 'Select Attribute Values', allowClear: true, width: '100%' });
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
                    <div class="img-delete-btn" onclick="removeImage(this)"><i class="fas fa-times"></i></div>
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
