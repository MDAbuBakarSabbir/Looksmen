@php
    use App\Models\ProductImage;
@endphp
@extends('layouts.Backend.master')
@section('title')
    PRODUCTS
@endsection
@section('content')
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .5);
        }

        .modal-dialog {
            margin: 10% auto;
            max-width: 400px;
        }

        /* Premium Toggle Switch Styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #4f46e5;
        }

        input:checked+.slider:before {
            transform: translateX(18px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <div class="row">
        <div class="col">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <h5 class="font-weight-bold text-dark mb-0"><i class="fa-solid fa-filter mr-2 text-primary"></i>Advanced Filter Options</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Search term -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Search Text</label>
                            <input class="form-control" type="text" id="filterSearch" placeholder="Search title, slug, code...">
                        </div>
                        <!-- Category -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Category</label>
                            <select class="form-control" id="filterCategory">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Subcategory -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Sub Category</label>
                            <select class="form-control" id="filterSubCategory">
                                <option value="">All Sub Categories</option>
                            </select>
                        </div>
                        <!-- Childcategory -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Child Category</label>
                            <select class="form-control" id="filterChildCategory">
                                <option value="">All Child Categories</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Status -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Status</label>
                            <select class="form-control" id="filterStatus">
                                <option value="">All Statuses</option>
                                <option value="1">Published</option>
                                <option value="0">Draft / UnPublished</option>
                            </select>
                        </div>
                        <!-- Todays Deal -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Todays Deal</label>
                            <select class="form-control" id="filterTodaysDeal">
                                <option value="">All Products</option>
                                <option value="1">Active Deals</option>
                                <option value="0">Normal Products</option>
                            </select>
                        </div>
                        <!-- Stock Level -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Stock Level</label>
                            <select class="form-control" id="filterStockStatus">
                                <option value="">All Levels</option>
                                <option value="in_stock">In Stock</option>
                                <option value="low_stock">Low Stock (&lt; 10)</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                        <!-- Sort By -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Sort By</label>
                            <select class="form-control" id="filterSortBy">
                                <option value="latest">Latest Created</option>
                                <option value="price_asc">Price: Low to High</option>
                                <option value="price_desc">Price: High to Low</option>
                                <option value="stock_asc">Stock: Low to High</option>
                                <option value="stock_desc">Stock: High to Low</option>
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-end">
                        <!-- Min Price -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Min Price (BDT)</label>
                            <input class="form-control" type="number" id="filterMinPrice" min="0" placeholder="Min BDT">
                        </div>
                        <!-- Max Price -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary font-weight-bold" style="font-size: 12px;">Max Price (BDT)</label>
                            <input class="form-control" type="number" id="filterMaxPrice" min="0" placeholder="Max BDT">
                        </div>
                        <!-- Reset Button -->
                        <div class="col-md-6 mb-3 text-right">
                            <button type="button" class="btn btn-secondary px-4 mr-2" id="btnResetFilters" style="border-radius: 30px;"><i class="fa-solid fa-arrows-rotate mr-2"></i>Reset Filters</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <span>All Products</span>
                    <a href="{{ route('product.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus mr-2"></i>Add Product</a>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <select id="bulkProductAction" class="form-control mr-2" style="width: 180px; display: inline-block;">
                            <option value="">Bulk Action</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        <button class="btn btn-danger" id="bulkProductBtn" style="height: 38px; border-radius: 4px; padding: 0 20px;">
                            Apply Action
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th scope="col"><input type="checkbox" id="productCheckAll"></th>
                                    <th scope="col">Basic Info</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Sales Info</th>
                                    <th scope="col">Stock</th>
                                    <th scope="col">Todays Deal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                @include('adminDash.products.extends.product_rows')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for deleting products -->
    <form id="Productdelete" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Copy Modal (single instance outside table loop) -->
    <div id="copyModal" class="modal" style="display:none; z-index: 1050;">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <h5>How many copies?</h5>
                <input type="number" id="copy_number" class="form-control mt-2" min="1" required>
                <input type="hidden" id="copy_product_id">
                <div class="mt-3 text-right">
                    <button class="btn btn-secondary" onclick="closeCopyModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="submitCopy()">Copy</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        function openCopyModal(productId) {
            $('#copy_product_id').val(productId);
            $('#copy_number').val(1);
            $('#copyModal').show();
        }

        function closeCopyModal() {
            $('#copyModal').hide();
        }

        function submitCopy() {
            let productId = $('#copy_product_id').val();
            let copies = $('#copy_number').val();

            if (!copies || copies < 1) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please enter a valid number of copies.'
                });
                return;
            }

            fetch("{{ route('product.copy') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    product_id: productId,
                    copies: copies
                })
            })
            .then(res => res.json())
            .then(data => {
                closeCopyModal();
                if (data.message && data.message.includes('Successfully')) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message || 'Copy failed.'
                    });
                }
            })
            .catch(err => {
                closeCopyModal();
                Toast.fire({
                    icon: 'error',
                    title: 'Server Error'
                });
            });
        }

        $(document).ready(function() {
            // Initialize Select2 dropdown elements
            $('#filterCategory').select2({ placeholder: "Select Category", allowClear: true });
            $('#filterSubCategory').select2({ placeholder: "Select Sub Category", allowClear: true });
            $('#filterChildCategory').select2({ placeholder: "Select Child Category", allowClear: true });
            $('#filterStatus').select2({ placeholder: "Select Status", allowClear: true });
            $('#filterTodaysDeal').select2({ placeholder: "Select Today's Deal", allowClear: true });
            $('#filterStockStatus').select2({ placeholder: "Select Stock Level", allowClear: true });
            $('#filterSortBy').select2({ placeholder: "Sort By", allowClear: true });

            // Dynamic Category cascade
            $('#filterCategory').on('change', function() {
                var categoryID = $(this).val();
                $('#filterSubCategory').html('<option value="">Loading...</option>').trigger('change');
                $('#filterChildCategory').html('<option value="">All Child Categories</option>').trigger('change');

                if (categoryID) {
                    $.ajax({
                        url: '/admin/get-subcategories/' + categoryID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#filterSubCategory').html('<option value="">All Sub Categories</option>');
                            $.each(data, function(key, value) {
                                $('#filterSubCategory').append('<option value="' + key + '">' + value + '</option>');
                            });
                            $('#filterSubCategory').trigger('change');
                        }
                    });
                } else {
                    $('#filterSubCategory').html('<option value="">All Sub Categories</option>').trigger('change');
                }
            });

            // Dynamic Subcategory cascade
            $('#filterSubCategory').on('change', function() {
                var subcategoryID = $(this).val();
                $('#filterChildCategory').html('<option value="">Loading...</option>').trigger('change');

                if (subcategoryID) {
                    $.ajax({
                        url: '/admin/get-childcategories/' + subcategoryID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#filterChildCategory').html('<option value="">All Child Categories</option>');
                            $.each(data, function(key, value) {
                                $('#filterChildCategory').append('<option value="' + key + '">' + value + '</option>');
                            });
                            $('#filterChildCategory').trigger('change');
                        }
                    });
                } else {
                    $('#filterChildCategory').html('<option value="">All Child Categories</option>').trigger('change');
                }
            });

            // Trigger filter when dropdowns change
            $('#filterCategory, #filterSubCategory, #filterChildCategory, #filterStatus, #filterTodaysDeal, #filterStockStatus, #filterSortBy').on('change', function() {
                filterProducts();
            });

            // Trigger filter with debounce on text inputs
            let filterTimeout;
            $('#filterSearch, #filterMinPrice, #filterMaxPrice').on('input', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(filterProducts, 300);
            });

            // Reset filters
            $('#btnResetFilters').on('click', function() {
                $('#filterSearch').val('');
                $('#filterCategory').val('').trigger('change.select2');
                $('#filterSubCategory').html('<option value="">All Sub Categories</option>').trigger('change.select2');
                $('#filterChildCategory').html('<option value="">All Child Categories</option>').trigger('change.select2');
                $('#filterStatus').val('').trigger('change.select2');
                $('#filterTodaysDeal').val('').trigger('change.select2');
                $('#filterStockStatus').val('').trigger('change.select2');
                $('#filterMinPrice').val('');
                $('#filterMaxPrice').val('');
                $('#filterSortBy').val('latest').trigger('change.select2');
                filterProducts();
            });

            function filterProducts() {
                let search = $('#filterSearch').val();
                let category_id = $('#filterCategory').val();
                let subcategory_id = $('#filterSubCategory').val();
                let childcategory_id = $('#filterChildCategory').val();
                let status = $('#filterStatus').val();
                let todays_deal = $('#filterTodaysDeal').val();
                let stock_status = $('#filterStockStatus').val();
                let min_price = $('#filterMinPrice').val();
                let max_price = $('#filterMaxPrice').val();
                let sort_by = $('#filterSortBy').val();

                $.ajax({
                    url: "{{ route('product.index') }}",
                    type: "GET",
                    data: {
                        search: search,
                        category_id: category_id,
                        subcategory_id: subcategory_id,
                        childcategory_id: childcategory_id,
                        status: status,
                        todays_deal: todays_deal,
                        stock_status: stock_status,
                        min_price: min_price,
                        max_price: max_price,
                        sort_by: sort_by
                    },
                    success: function(html) {
                        $('#productTableBody').html(html);
                        $('#productCheckAll').prop('checked', false);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to filter products.'
                        });
                    }
                });
            }
        });

        // Master Checkbox
        $(document).on('change', '#productCheckAll', function() {
            $('.product-check').prop('checked', $(this).prop('checked'));
        });

        // Individual Checkbox
        $(document).on('change', '.product-check', function() {
            if ($('.product-check:checked').length === $('.product-check').length) {
                $('#productCheckAll').prop('checked', true);
            } else {
                $('#productCheckAll').prop('checked', false);
            }
        });

        // Bulk Delete Handler
        $(document).on('click', '#bulkProductBtn', function(e) {
            e.preventDefault();
            let action = $('#bulkProductAction').val();
            if (!action) {
                Toast.fire({ icon: 'warning', title: 'Please select an action' });
                return;
            }
            let selectedIds = [];
            $('.product-check:checked').each(function() {
                selectedIds.push($(this).val());
            });
            if (selectedIds.length === 0) {
                Toast.fire({ icon: 'warning', title: 'No products selected' });
                return;
            }
            if (!confirm('Are you sure you want to delete the selected products?')) {
                return;
            }
            $.ajax({
                url: "{{ route('product.bulk-delete') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: selectedIds
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({ icon: 'success', title: response.message || 'Products deleted' });
                        $('.product-check:checked').closest('tr').remove();
                        $('#productCheckAll').prop('checked', false);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Toast.fire({ icon: 'error', title: 'Failed to delete products' });
                }
            });
        });

        // Delegated todaysdeal-status toggle using jQuery
        $(document).on('change', '.todaysdeal-status', function() {
            let id = $(this).data('id');
            let status = this.checked ? 1 : 0;

            fetch("{{ route('product.todays_deal_status') }}", {
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
                        title: status == 1 ? 'Todays Deal Status Activated' : 'Todays Deal Status Deactivated'
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

        // Delegated product-status toggle using jQuery
        $(document).on('change', '.product-status', function() {
            let id = $(this).data('id');
            let status = this.checked ? 1 : 0;

            fetch("{{ route('product.status') }}", {
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
                        title: status == 1 ? 'Published Successfully' : 'UnPublished Successfully'
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

        // Delete Product SweetAlert
        function deleteProduct(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This will delete product + images + attributes + colors!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('Productdelete');
                    form.action = "/admin/products/destroy/" + id;
                    form.submit();
                }
            });
        }
    </script>
@endsection
