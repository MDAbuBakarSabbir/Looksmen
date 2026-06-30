@extends('layouts.Backend.master')
@section('title')
    EDIT PRODUCT
@endsection
@section('content')
    <div class="back mb-3">
        <a href="{{ route('product.index') }}">
            <img height="30px" src="{{ asset('adminDash/images/svg/backbutton.png') }}" alt="Back">
        </a>
    </div>
    <div class="row">
        <div class="col">
            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>EDIT Product: {{ $product->title }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Product Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" value="{{ $product->title }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-control" id="category" name="category_id">
                                    <option disabled value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="subcategory" class="form-label">Sub Category</label>
                                <select class="form-control" id="subcategory" name="subcategory_id">
                                    <option value="">Select Sub Category</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" {{ $subcategory->id == $product->subcategory_id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="childcategory" class="form-label">Child Category</label>
                                <select class="form-control" id="childcategory" name="childcategory_id">
                                    <option value="">Select Child Category</option>
                                    @foreach ($childcategories as $childcategory)
                                        <option value="{{ $childcategory->id }}" {{ $childcategory->id == $product->childcategory_id ? 'selected' : '' }}>{{ $childcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="brand" class="form-label">Brand</label>
                                    <select class="form-control" name="brand_id" id="brand">
                                        <option value="">Select Brand</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Actual Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="old_price" value="{{ $product->old_price }}" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Sale Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="new_price" value="{{ $product->new_price }}" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="stock" value="{{ $product->stock }}" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Custom Points</label>
                                    <input type="number" class="form-control" name="points" value="{{ $product->points ?? 0 }}" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="attribute" class="form-label">Attribute</label>
                                    <select id="attribute" class="form-control" name="attribute_id">
                                        <option value="">Select Attribute</option>
                                        @foreach ($attributes as $attribute)
                                            <option value="{{ $attribute->id }}" {{ $attribute->id == $selectedAttributeId ? 'selected' : '' }}>{{ $attribute->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <label for="AttributeValue" class="form-label">Attribute Values</label>
                                <div class="">
                                    <select id="AttributeValue" class="form-control js-example-theme-multiple" name="attributeValue[]" multiple="multiple">
                                        @foreach ($attributeValues as $val)
                                            <option value="{{ $val->id }}" {{ in_array($val->id, $selectedAttributes) ? 'selected' : '' }}>{{ $val->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">Color</label>
                                <div class="">
                                    <select class="form-control js-example-theme-multiple" name="color[]" multiple="multiple">
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}" {{ in_array($color->id, $selectedColors) ? 'selected' : '' }}>{{ $color->color_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Video (Optional)</label>
                                <input class="form-control" type="text" name="video" placeholder="Video URL" value="{{ $product->video }}">
                            </div>
                            <div class="col d-flex flex-column">
                                <label class="form-label">Advance (Optional)</label>
                                <label class="switch">
                                    <input value="0" class="product-status" type="checkbox" name="cod" id="codRequried" onchange="toggleAdvanceAmount()" {{ $product->cod == 0 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col">
                                <label class="form-label">Advance Amount</label>
                                <input class="form-control" type="number" id="advance_amount" name="advance_amount" placeholder="Advance Amount" value="{{ $product->advance_amount }}" disabled>
                            </div>
                        </div>

                        <!-- Product Images Area -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Current Product Images</label>
                                    <div class="d-flex gap-2 flex-wrap mb-3" id="existing-images-area">
                                        @forelse($productImages as $img)
                                            <div class="existing-image-box" id="image-container-{{ $img->id }}">
                                                <img src="{{ asset('adminDash/images/product/' . $img->image) }}" alt="Product Image">
                                                <div class="img-delete-overlay" onclick="deleteExistingImage({{ $img->id }})" title="Delete Image">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">No images uploaded for this product yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add New Images Area -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Add New Images (Max Total: 10)</label>
                                    <div class="d-flex gap-2 flex-wrap" id="image-area">
                                        <label class="image-upload-box" for="image-input" title="Click to add images">
                                            <span style="font-size: 40px; color:#94a3b8;">+</span>
                                        </label>
                                        <input type="file" name="images[]" id="image-input" multiple accept="image/*" style="display:none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="row mb-3">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Product Description</h4>
                            </div>
                            <div class="card-body">
                                <textarea name="description" id="summernote" required>{!! $product->description !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 mr-2">Update Product</button>
                    <a href="{{ route('product.index') }}" class="btn btn-secondary px-4 py-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize Select2 dropdown elements
            $('#category').select2({ placeholder: "Select Category", allowClear: true, width: '100%' });
            $('#subcategory').select2({ placeholder: "Select Sub Category", allowClear: true, width: '100%' });
            $('#childcategory').select2({ placeholder: "Select Child Category", allowClear: true, width: '100%' });
            $('#brand').select2({ placeholder: "Select Brand", allowClear: true, width: '100%' });
            $('#attribute').select2({ placeholder: "Select Attribute", allowClear: true, width: '100%' });
            $('#AttributeValue').select2({ placeholder: "Select Attribute Values", allowClear: true, width: '100%' });
            $('select[name="color[]"]').select2({ placeholder: "Select Colors", allowClear: true, width: '100%' });

            // Initialize Summernote Rich Text Editor
            $('#summernote').summernote({
                height: 250,
                placeholder: 'Write product description details here...',
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

        // Delete Existing Image via AJAX
        function deleteExistingImage(imageId) {
            Swal.fire({
                title: "Are you sure?",
                text: "This will delete the image from the product permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/products/image-delete/' + imageId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#image-container-' + imageId).fadeOut(400, function() {
                                    $(this).remove();
                                });
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: response.message || 'Image delete failed.'
                                });
                            }
                        },
                        error: function() {
                            Toast.fire({
                                            icon: 'error',
                                            title: 'AJAX request failed'
                            });
                        }
                    });
                }
            });
        }

        // Multiple Images Picker & Preview Handling (for new uploads)
        let selectedImages = [];
        const maxImages = 10;


        document.getElementById('image-input').addEventListener('change', function(event) {
            const files = event.target.files;

            // Calculate total images including existing ones
            let currentCount = $('#existing-images-area').children('.existing-image-box').length;

            if ((currentCount + selectedImages.length + files.length) > maxImages) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Total product images cannot exceed 10!'
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
