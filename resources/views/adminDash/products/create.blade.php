@extends('layouts.Backend.master')
@section('title')
    ADD PRODUCT
@endsection
@section('content')
    <style>
        input:disabled {
            opacity: 0.5;
            border: 1px dashed rgba(0,0,0,0.15);
            background-color: #f1f5f9;
            cursor: not-allowed;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .image-upload-box {
            width: 120px;
            height: 120px;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            position: relative;
            margin-right: 10px;
            margin-bottom: 5px;
            background: #f8fafc;
        }

        .image-upload-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .delete-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: red;
            color: #fff;
            padding: 4px 7px;
            border-radius: 50%;
            font-size: 12px;
            cursor: pointer;
        }

        /* Premium Select2 Styling Override */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            border: 1px solid rgba(0, 0, 0, 0.15) !important;
            border-radius: 6px !important;
            min-height: 40px !important;
            display: flex !important;
            align-items: center !important;
            padding-left: 6px !important;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.02) !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057 !important;
            font-size: 14px !important;
            font-weight: 500 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #4f46e5 !important;
            color: white !important;
            border: none !important;
            border-radius: 20px !important;
            padding: 2px 10px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            margin-top: 5px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important;
            margin-right: 5px !important;
        }
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="back mb-3">
        <a href="{{ url('/admin/products') }}">
            <img height="30px" src="{{ asset('adminDash/images/svg/backbutton.png') }}" alt="">
        </a>
    </div>
    <div class="row">
        <div class="col">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>ADD Product</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col">

                                <label class="form-label">Product Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" aria-describedby="emailHelp" name="title"
                                    title="Product Title">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="exampleInputCatrgory1" class="form-label">Category <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="category" name="category_id">
                                    <option selected disabled value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="exampleInputCatrgory1" class="form-label">Sub Category</label>
                                <select class="form-control" id="subcategory"
                                    name="subcategory_id">
                                    <option selected disabled value="">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="exampleInputCatrgory1" class="form-label">Child Category</label>
                                <select class="form-control" id="childcategory"
                                    name="childcategory_id">
                                    <option selected disabled value="">Select Child Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="exampleInputCatrgory1" class="form-label">Brand</label>
                                    <select class="form-control" name="brand_id" id="brand">
                                        <option selected disabled value="">Select Brand</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="exampleInputCatrgory1" class="form-label">Actual Price<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="old_price">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="exampleInputCatrgory1" class="form-label">Sale Price<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="new_price">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="exampleInputCatrgory1" class="form-label">Stock<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="stock">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Custom Points</label>
                                    <input type="number" class="form-control" name="points" placeholder="0" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="exampleInputCatrgory1" class="form-label">Attribute</label>
                                    <select id="attribute" class="form-control" name="attribute_id">
                                        <option selected disabled value="">Select Attribute</option>
                                        @foreach ($attributes as $attribute)
                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <label for="exampleInputCatrgory1" class="form-label">Attribute Values <span
                                        class="text-danger">*</span></label>
                                <div class="">
                                    <select id="AttributeValue" class="form-control js-example-theme-multiple" name="attributeValue[]"
                                        multiple="multiple">
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <label for="exampleInputCatrgory1" class="form-label">Color <span
                                        class="text-danger">*</span></label>
                                <div class="">
                                    <select class="form-control js-example-theme-multiple" name="color[]" multiple="multiple">
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="exampleInputCatrgory1" class="form-label">Video (Optional)</label>
                                <input class="form-control" type="text" name="video" id=""
                                    placeholder="Video URL" title="Video URL">
                            </div>
                            <div class="col d-flex flex-column">
                                <label for="exampleInputCatrgory1" class="form-label">Advance (Optional)</label>
                                <label class="switch">
                                    <input value="0" class="product-status" type="checkbox" name="cod"
                                        id="codRequried" title="YES" onchange="toggleAdvanceAmount()">
                                    <span class="slider round" title="YES"></span>
                                </label>
                            </div>
                            <div class="col">
                                <label for="exampleInputCatrgory1" class="form-label">Advance</label>
                                <input class="form-control" type="number" id="advance_amount" name="advance_amount"
                                    placeholder="Advance Amount" title="Advance Amount" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Product Images<span class="text-danger">*</span> (Max:
                                        10)</label>
                                    <div class="d-flex gap-2 flex-wrap" id="image-area">

                                        <!-- Image Select Template -->
                                        <div class="image-upload-box" onclick="openImagePicker()">
                                            <span style="font-size: 40px; color:#94a3b8;">+</span>
                                            <input type="file" name="images[]" id="image-input" multiple
                                                accept="image/*" style="display:none;">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Product Description</h4>
                            </div>
                            <div class="card-body">
                                <textarea name="description" id="summernote" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="submit" class="btn btn-primary">Cancel</button>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // SweetAlert Toast Mixin
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
            // Initialize Select2 dropdown elements
            $('#category').select2({ placeholder: "Select Category", allowClear: true });
            $('#subcategory').select2({ placeholder: "Select Sub Category", allowClear: true });
            $('#childcategory').select2({ placeholder: "Select Child Category", allowClear: true });
            $('#brand').select2({ placeholder: "Select Brand", allowClear: true });
            $('#attribute').select2({ placeholder: "Select Attribute", allowClear: true });
            $('#AttributeValue').select2({ placeholder: "Select Attribute Values", allowClear: true });
            $('select[name="color[]"]').select2({ placeholder: "Select Colors", allowClear: true });

            // Initialize Summernote Rich Text Editor
            $('#summernote').summernote({
                height: 200,
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

            // AJAX Alerts
            @if (Session::has('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ Session::get('success') }}'
                });
            @endif

            @if (Session::has('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ Session::get('error') }}'
                });
            @endif

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Validation Failed: Please check the form fields.'
                });
            @endif

            // Dynamic category to subcategory dropdown cascading
            $('#category').on('change', function() {
                var categoryID = $(this).val();

                $('#subcategory').html('<option selected disabled value="">Loading...</option>');
                $('#childcategory').html('<option selected disabled value="">Select Child Category</option>');
                
                $('#subcategory').trigger('change');
                $('#childcategory').trigger('change');

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
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading subcategories:", error);
                            $('#subcategory').html('<option selected disabled value="">Error loading</option>');
                            $('#subcategory').trigger('change');
                        }
                    });
                } else {
                    $('#subcategory').html('<option selected disabled value="">Select Sub Category</option>');
                    $('#subcategory').trigger('change');
                }
            });

            // Dynamic subcategory to childcategory dropdown cascading
            $('#subcategory').on('change', function() {
                var subcategoryID = $(this).val();
                $('#childcategory').html('<option selected disabled value="">Loading...</option>');
                $('#childcategory').trigger('change');

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
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading child categories:", error);
                            $('#childcategory').html('<option selected disabled value="">Error loading</option>');
                            $('#childcategory').trigger('change');
                        }
                    });
                } else {
                    $('#childcategory').html('<option selected disabled value="">Select Child Category</option>');
                    $('#childcategory').trigger('change');
                }
            });

            // Dynamic attribute values dropdown cascading
            $('#attribute').on('change', function() {
                var attributeID = $(this).val();
                $('#AttributeValue').html('<option selected disabled value="">Loading...</option>');
                $('#AttributeValue').trigger('change');

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
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading Attribute Value:", error);
                            $('#AttributeValue').html('<option selected disabled value="">Error loading</option>');
                            $('#AttributeValue').trigger('change');
                        }
                    });
                } else {
                    $('#AttributeValue').html('<option selected disabled value="">Select Attribute Value</option>');
                    $('#AttributeValue').trigger('change');
                }
            });
        });

        // Toggle advance payment amount input
        function toggleAdvanceAmount() {
            const checkbox = document.getElementById('codRequried');
            const advanceInput = document.getElementById('advance_amount');

            if (checkbox && advanceInput) {
                advanceInput.disabled = !checkbox.checked;
            }
        }

        document.addEventListener('DOMContentLoaded', toggleAdvanceAmount);

        // Multiple Images Picker & Preview Handling
        let selectedImages = [];
        const maxImages = 10;

        function openImagePicker() {
            document.getElementById('image-input').click();
        }

        document.getElementById('image-input').addEventListener('change', function(event) {
            const files = event.target.files;

            if ((selectedImages.length + files.length) > maxImages) {
                Toast.fire({
                    icon: 'warning',
                    title: 'You can upload maximum 10 images!'
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
                box.classList.add('image-upload-box');
                box.innerHTML = `
                    <img src="${e.target.result}">
                    <div class="delete-btn" onclick="removeImage(this)">x</div>
                `;
                document.getElementById('image-area').appendChild(box);
            };
            reader.readAsDataURL(file);
        }

        function removeImage(btn) {
            let box = btn.parentElement;
            let index = Array.from(box.parentElement.children).indexOf(box) - 1;

            selectedImages.splice(index, 1);
            box.remove();
            updateInputFiles();

            Toast.fire({
                icon: 'info',
                title: 'Image removed'
            });
        }

        function updateInputFiles() {
            const input = document.getElementById('image-input');
            const dt = new DataTransfer();

            selectedImages.forEach(img => dt.items.add(img));
            input.files = dt.files;
        }
    </script>
@endsection
