@extends('layouts.Backend.master')
@section('title', 'EDIT PRODUCT')


@section('style')
<style>
    /* ── Edit-page badge ── */
    .edit-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, rgba(99,102,241,0.10) 0%, rgba(79,70,229,0.18) 100%);
        border: 1px solid rgba(99,102,241,0.25);
        color: #4f46e5;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }

    /* ── Section labels ── */
    .section-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.25rem;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f5f9;
    }

    /* ── Input group prefix ── */
    .input-prefix {
        display: flex;
        align-items: center;
    }
    .input-prefix-text {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-right: none;
        border-radius: 8px 0 0 8px;
        padding: 0.6rem 0.85rem;
        font-size: 0.9rem;
        color: #94a3b8;
        font-weight: 600;
    }
    .input-prefix .form-control {
        border-radius: 0 8px 8px 0;
    }

    /* ── Advance toggle card ── */
    .toggle-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }
    .toggle-label-group {
        display: flex;
        flex-direction: column;
    }
    .toggle-label-group span:first-child {
        font-size: 0.9rem;
        font-weight: 600;
        color: #334155;
    }
    .toggle-label-group span:last-child {
        font-size: 0.78rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* ── Image section upgrade ── */
    .image-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    .image-count-badge {
        background: #e0e7ff;
        color: #4338ca;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* ── Submit bar ── */
    .submit-action-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        padding: 1.25rem 1.5rem;
        background: #ffffff;
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    /* ── Error alert upgrade ── */
    .alert-modern {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-left: 4px solid #ef4444;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .alert-modern ul {
        margin: 0;
        padding-left: 1.25rem;
        color: #b91c1c;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header-custom">
        <div class="d-flex align-items-center gap-3">
            <h1 class="page-title-custom mb-0">Edit Product</h1>
            <span class="edit-status-badge">
                <i class="fas fa-pencil-alt"></i> Editing
            </span>
        </div>
        <a href="{{ route('product.index') }}" class="btn-cancel-custom">
            <i class="fas fa-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    {{-- ── Validation Errors ── --}}
    @if ($errors->any())
        <div class="alert-modern">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- ════════════════════════════════════
                 LEFT COLUMN  (8/12)
            ════════════════════════════════════ --}}
            <div class="col-lg-8">

                {{-- ── Basic Details ── --}}
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-box text-primary me-2"></i> Basic Details
                    </div>
                    <div class="premium-card-body">

                        <div class="mb-4">
                            <label class="form-label">Product Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title"
                                   value="{{ old('title', $product->title) }}"
                                   placeholder="Enter product title..." required>
                        </div>

                        <div class="section-divider"><i class="fas fa-align-left"></i> Description</div>
                        <div class="mb-2">
                            <label class="form-label">Product Description <span class="text-danger">*</span></label>
                            <textarea id="summernote-editor" style="width:100%;">{!! $product->description !!}</textarea>
                            <input type="hidden" name="description" id="summernote-hidden" value="{!! $product->description !!}">
                        </div>

                        <div class="mt-4">
                            <div class="section-divider"><i class="fas fa-link"></i> Media</div>
                            <label class="form-label">Video URL <span class="text-muted fw-normal">(Optional)</span></label>
                            <div class="input-prefix">
                                <span class="input-prefix-text"><i class="fas fa-video"></i></span>
                                <input class="form-control" type="url" name="video"
                                       placeholder="https://youtube.com/..."
                                       value="{{ old('video', $product->video) }}">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Product Images ── --}}
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-images text-primary me-2"></i> Media &amp; Gallery
                    </div>
                    <div class="premium-card-body">

                        {{-- Current images --}}
                        <div class="mb-4">
                            <div class="image-section-header">
                                <label class="form-label mb-0">Current Images</label>
                                <span class="image-count-badge" id="existing-count-badge">
                                    {{ $productImages->count() }} / 10
                                </span>
                            </div>
                            <div class="image-upload-wrapper" id="existing-images-area">
                                @forelse($productImages as $img)
                                    <div class="existing-image-box" id="image-container-{{ $img->id }}">
                                        <img src="{{ asset('Uploads/' . $img->image) }}" alt="Product Image">
                                        <div class="img-delete-overlay"
                                             onclick="deleteExistingImage({{ $img->id }})"
                                             title="Delete Image">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small">No images uploaded for this product yet.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Add new images --}}
                        <div class="section-divider"><i class="fas fa-plus-circle"></i> Add New Images</div>
                        <p class="text-muted small mb-2">Upload additional images. Max <strong>10</strong> total across existing and new.</p>
                        <div class="image-upload-wrapper" id="image-area">
                            <label class="image-upload-box" for="image-input" title="Click to add images">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <span style="font-size:13px; font-weight:500;">Add Images</span>
                            </label>
                        </div>
                        <input type="file" name="images[]" id="image-input"
                               multiple accept="image/*" style="display:none;">

                    </div>
                </div>

            </div>

            {{-- ════════════════════════════════════
                 RIGHT COLUMN  (4/12)
            ════════════════════════════════════ --}}
            <div class="col-lg-4">

                {{-- ── Organisation ── --}}
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-sitemap text-primary me-2"></i> Organisation
                    </div>
                    <div class="premium-card-body">

                        <div class="mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control" id="category" name="category_id">
                                <option disabled value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $cat->id == $product->category_id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sub Category</label>
                            <select class="form-control" id="subcategory" name="subcategory_id">
                                <option value="">Select Sub Category</option>
                                @foreach ($subcategories as $sub)
                                    <option value="{{ $sub->id }}"
                                        {{ $sub->id == $product->subcategory_id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Child Category</label>
                            <select class="form-control" id="childcategory" name="childcategory_id">
                                <option value="">Select Child Category</option>
                                @foreach ($childcategories as $child)
                                    <option value="{{ $child->id }}"
                                        {{ $child->id == $product->childcategory_id ? 'selected' : '' }}>
                                        {{ $child->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Brand</label>
                            <select class="form-control" name="brand_id" id="brand">
                                <option value="">Select Brand</option>
                            </select>
                        </div>

                    </div>
                </div>

                {{-- ── Pricing & Inventory ── --}}
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-tags text-primary me-2"></i> Pricing &amp; Inventory
                    </div>
                    <div class="premium-card-body">

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Actual Price <span class="text-danger">*</span></label>
                                <div class="input-prefix">
                                    <span class="input-prefix-text">৳</span>
                                    <input type="number" class="form-control" name="old_price"
                                           value="{{ old('old_price', $product->old_price) }}"
                                           required min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Sale Price <span class="text-danger">*</span></label>
                                <div class="input-prefix">
                                    <span class="input-prefix-text">৳</span>
                                    <input type="number" class="form-control" name="new_price"
                                           value="{{ old('new_price', $product->new_price) }}"
                                           required min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stock"
                                       value="{{ old('stock', $product->stock) }}"
                                       required min="0">
                            </div>
                            <div class="col-6 mb-0">
                                <label class="form-label">Points</label>
                                <input type="number" class="form-control" name="points"
                                       value="{{ old('points', $product->points ?? 0) }}" min="0">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Attributes & Settings ── --}}
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="fas fa-sliders-h text-primary me-2"></i> Attributes &amp; Settings
                    </div>
                    <div class="premium-card-body">

                        <div class="mb-3">
                            <label class="form-label">Attribute</label>
                            <select id="attribute" class="form-control" name="attribute_id">
                                <option value="">Select Attribute</option>
                                @foreach ($attributes as $attribute)
                                    <option value="{{ $attribute->id }}"
                                        {{ $attribute->id == $selectedAttributeId ? 'selected' : '' }}>
                                        {{ $attribute->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Attribute Values</label>
                            <select id="AttributeValue" class="form-control" name="attributeValue[]"
                                    multiple="multiple" style="width:100%">
                                @foreach ($attributeValues as $val)
                                    <option value="{{ $val->id }}"
                                        {{ in_array($val->id, $selectedAttributes) ? 'selected' : '' }}>
                                        {{ $val->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Colors</label>
                            <select class="form-control" name="color[]" id="colorSelect"
                                    multiple="multiple" style="width:100%">
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}"
                                        {{ in_array($color->id, $selectedColors) ? 'selected' : '' }}>
                                        {{ $color->color_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Advance payment toggle --}}
                        <div class="toggle-card">
                            <div class="toggle-label-group">
                                <span>Require Advance Payment?</span>
                                <span>Enable to collect a deposit upfront</span>
                            </div>
                            <label class="switch mb-0">
                                <input value="0" type="checkbox" name="cod" id="codRequried"
                                       onchange="toggleAdvanceAmount()"
                                       {{ $product->cod == 0 ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Advance Amount</label>
                            <div class="input-prefix">
                                <span class="input-prefix-text">৳</span>
                                <input class="form-control" type="number" id="advance_amount"
                                       name="advance_amount"
                                       value="{{ old('advance_amount', $product->advance_amount) }}"
                                       placeholder="0.00" min="0" step="0.01" disabled>
                            </div>
                        </div>

                    </div>
                </div>

            </div>{{-- /col-lg-4 --}}
        </div>{{-- /row --}}

        {{-- ── Action Bar ── --}}
        <div class="submit-action-bar">
            <a href="{{ route('product.index') }}" class="btn-cancel-custom">
                <i class="fas fa-times me-1"></i> Cancel
            </a>
            <button type="submit" class="btn-premium">
                <i class="fas fa-save me-1"></i> Update Product
            </button>
        </div>

    </form>

@endsection

@section('script')
<script>
    $(document).ready(function () {

        // ── Select2 dropdowns ──────────────────────────────────────────
        $('#category').select2({ placeholder: 'Select Category', allowClear: true, width: '100%' });
        $('#subcategory').select2({ placeholder: 'Select Sub Category', allowClear: true, width: '100%' });
        $('#childcategory').select2({ placeholder: 'Select Child Category', allowClear: true, width: '100%' });
        $('#brand').select2({ placeholder: 'Select Brand', allowClear: true, width: '100%' });
        $('#attribute').select2({ placeholder: 'Select Attribute', allowClear: true, width: '100%' });
        $('#AttributeValue').select2({ placeholder: 'Select Attribute Values', allowClear: true, width: '100%' });
        $('#colorSelect').select2({ placeholder: 'Select Colors', allowClear: true, width: '100%' });

        // ── Summernote ────────────────────────────────────────────────
        if (typeof $.fn.summernote !== 'undefined') {
            $('#summernote-editor').summernote({
                height: 300,
                placeholder: 'Write product description here...',
                toolbar: [
                    ['style',  ['style']],
                    ['font',   ['bold', 'italic', 'underline', 'clear']],
                    ['color',  ['color']],
                    ['para',   ['ul', 'ol', 'paragraph']],
                    ['table',  ['table']],
                    ['insert', ['link', 'picture']],
                    ['view',   ['fullscreen', 'codeview', 'help']]
                ]
            });
            let existingDesc = {!! json_encode($product->description) !!};
            if (existingDesc) {
                $('#summernote-editor').summernote('code', existingDesc);
            }
        } else {
            console.error('Summernote is not loaded!');
        }

        // ── Form submit: sync & validate description ──────────────────
        $('form').on('submit', function (e) {
            let isEmpty = $('#summernote-editor').summernote('isEmpty');
            if (isEmpty) {
                e.preventDefault();
                Toast.fire({ icon: 'warning', title: 'Product description is required!' });
                return false;
            }
            $('#summernote-hidden').val($('#summernote-editor').summernote('code'));
        });

        // ── Session toasts ────────────────────────────────────────────
        @if (Session::has('success'))
            Toast.fire({ icon: 'success', title: '{{ Session::get('success') }}' });
        @endif
        @if (Session::has('error'))
            Toast.fire({ icon: 'error', title: '{{ Session::get('error') }}' });
        @endif
        @if ($errors->any())
            Toast.fire({ icon: 'error', title: 'Validation Failed: Please check the form fields.' });
        @endif

        // ── Cascading: Category → Sub ─────────────────────────────────
        $('#category').on('change', function () {
            var catID = $(this).val();
            $('#subcategory').html('<option disabled selected value="">Loading...</option>').trigger('change');
            $('#childcategory').html('<option value="">Select Child Category</option>').trigger('change');
            if (catID) {
                $.getJSON('/admin/get-subcategories/' + catID, function (data) {
                    var opts = '<option disabled value="">Select Sub Category</option>';
                    $.each(data, function (k, v) { opts += '<option value="' + k + '">' + v + '</option>'; });
                    $('#subcategory').html(opts).trigger('change');
                }).fail(function () {
                    $('#subcategory').html('<option value="">Error loading</option>').trigger('change');
                });
            } else {
                $('#subcategory').html('<option value="">Select Sub Category</option>').trigger('change');
            }
        });

        // ── Cascading: Sub → Child ────────────────────────────────────
        $('#subcategory').on('change', function () {
            var subID = $(this).val();
            $('#childcategory').html('<option disabled selected value="">Loading...</option>').trigger('change');
            if (subID) {
                $.getJSON('/admin/get-childcategories/' + subID, function (data) {
                    var opts = '<option value="">Select Child Category</option>';
                    $.each(data, function (k, v) { opts += '<option value="' + k + '">' + v + '</option>'; });
                    $('#childcategory').html(opts).trigger('change');
                }).fail(function () {
                    $('#childcategory').html('<option value="">Error loading</option>').trigger('change');
                });
            } else {
                $('#childcategory').html('<option value="">Select Child Category</option>').trigger('change');
            }
        });

        // ── Cascading: Attribute → Values ─────────────────────────────
        $('#attribute').on('change', function () {
            var attrID = $(this).val();
            if ($.fn.select2 && $('#AttributeValue').data('select2')) {
                $('#AttributeValue').select2('destroy');
            }
            $('#AttributeValue').html('<option disabled>Loading values...</option>');
            if (attrID) {
                $.getJSON('/admin/get-attribute-values/' + attrID, function (data) {
                    var opts = '';
                    $.each(data, function (k, v) { opts += '<option value="' + k + '">' + v + '</option>'; });
                    $('#AttributeValue').html(opts);
                    $('#AttributeValue').select2({ placeholder: 'Select Attribute Values', allowClear: true, width: '100%' });
                }).fail(function () {
                    $('#AttributeValue').html('');
                    $('#AttributeValue').select2({ placeholder: 'Error loading values', allowClear: true, width: '100%' });
                });
            } else {
                $('#AttributeValue').html('');
                $('#AttributeValue').select2({ placeholder: 'Select Attribute Values', allowClear: true, width: '100%' });
            }
        });

    });

    // ── Toggle advance amount ──────────────────────────────────────────
    function toggleAdvanceAmount() {
        const cb    = document.getElementById('codRequried');
        const input = document.getElementById('advance_amount');
        if (cb && input) {
            input.disabled = !cb.checked;
            if (!cb.checked) input.value = '';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleAdvanceAmount);

    // ── Delete existing image via AJAX ────────────────────────────────
    function deleteExistingImage(imageId) {
        Swal.fire({
            title: 'Delete this image?',
            text: 'This action is permanent and cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444',
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/products/image-delete/' + imageId,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        if (res.success) {
                            $('#image-container-' + imageId).fadeOut(350, function () {
                                $(this).remove();
                                // Update badge count
                                var remaining = $('#existing-images-area').children('.existing-image-box').length;
                                $('#existing-count-badge').text(remaining + ' / 10');
                            });
                            Toast.fire({ icon: 'success', title: res.message });
                        } else {
                            Toast.fire({ icon: 'error', title: res.message || 'Image delete failed.' });
                        }
                    },
                    error: function () {
                        Toast.fire({ icon: 'error', title: 'AJAX request failed.' });
                    }
                });
            }
        });
    }

    // ── New image picker ──────────────────────────────────────────────
    let selectedImages = [];
    const maxImages = 10;

    document.getElementById('image-input').addEventListener('change', function (event) {
        var files       = event.target.files;
        var existCount  = document.querySelectorAll('#existing-images-area .existing-image-box').length;

        if ((existCount + selectedImages.length + files.length) > maxImages) {
            Toast.fire({ icon: 'warning', title: 'Total product images cannot exceed 10!' });
            return;
        }
        for (var i = 0; i < files.length; i++) {
            selectedImages.push(files[i]);
            showPreview(files[i]);
        }
        updateInputFiles();
    });

    function showPreview(file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var box = document.createElement('div');
            box.classList.add('image-preview-item');
            box.innerHTML = '<img src="' + e.target.result + '"><div class="img-delete-btn" onclick="removeImage(this)"><i class="fas fa-times"></i></div>';
            document.getElementById('image-area').appendChild(box);
        };
        reader.readAsDataURL(file);
    }

    function removeImage(btn) {
        var box   = btn.parentElement;
        var index = Array.from(box.parentElement.children).indexOf(box) - 1; // -1 for upload label
        if (index >= 0 && index < selectedImages.length) {
            selectedImages.splice(index, 1);
            box.remove();
            updateInputFiles();
            Toast.fire({ icon: 'info', title: 'Image removed' });
        }
    }

    function updateInputFiles() {
        var dt = new DataTransfer();
        selectedImages.forEach(function (img) { dt.items.add(img); });
        document.getElementById('image-input').files = dt.files;
    }
</script>
@endsection
