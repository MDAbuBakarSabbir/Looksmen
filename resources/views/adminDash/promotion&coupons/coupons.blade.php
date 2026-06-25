@extends('layouts.adminLays.master')
@section('title')
    COUPONS
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-700 text-dark">
                        <i class="las la-ticket-alt mr-2 text-primary fs-24 align-middle"></i> Coupons Management
                    </h5>
                    <button id="addCoupon" class="btn btn-primary btn-sm rounded-pill px-3 fw-600 shadow-sm d-flex align-items-center">
                        <i class="las la-plus fs-16 mr-1"></i> Add New Coupon
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive custom-scrollbar">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light text-uppercase fs-12 text-muted fw-600">
                                <tr>
                                    <th class="py-3 px-4 text-left">Type</th>
                                    <th class="py-3">Code</th>
                                    <th class="py-3">Discount</th>
                                    <th class="py-3">Start Date</th>
                                    <th class="py-3">End Date</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-right px-4">Action</th>
                                </tr>
                            </thead>
                            <tbody id="couponTableBody" class="border-top-0">
                                @forelse ($coupons as $coupon)
                                    <tr class="border-bottom">
                                        <td class="text-left px-4">
                                            @if ($coupon->coupon_type == 'total_order')
                                                <span class="badge badge-soft-info px-2 py-1 rounded-pill fw-600">Total Order</span>
                                            @else
                                                <span class="badge badge-soft-warning px-2 py-1 rounded-pill fw-600">Product</span>
                                            @endif
                                        </td>
                                        <td><span class="fw-700 text-dark fs-14 bg-light px-2 py-1 rounded border">{{ $coupon->code }}</span></td>
                                        <td class="fw-600 text-primary">{{ $coupon->discount }}{{ $coupon->discount_type == 'percent' ? '%' : ' ৳' }}</td>
                                        <td class="text-muted fs-13">{{ date('d M Y', strtotime($coupon->start_date)) }}</td>
                                        <td class="text-muted fs-13">{{ date('d M Y', strtotime($coupon->end_date)) }}</td>
                                        <td>
                                            <label class="switch mb-0">
                                                <input class="status-switch" type="checkbox" data-id="{{ $coupon->id }}" {{ $coupon->status == '1' ? 'checked' : '' }}>
                                                <span class="slider round shadow-sm" title="Click to change status"></span>
                                            </label>
                                        </td>
                                        <td class="text-right px-4">
                                            <button title="Edit" class="btn btn-icon btn-sm btn-soft-primary rounded-circle mr-2 editCoupon shadow-sm" data-id="{{ $coupon->id }}">
                                                <i class="las la-pen fs-16"></i>
                                            </button>
                                            <button class="btn btn-icon btn-sm btn-soft-danger rounded-circle couponDeleteBtn shadow-sm" data-id="{{ $coupon->id }}" title="Delete">
                                                <i class="las la-trash fs-16"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="las la-box-open la-4x text-muted opacity-50 mb-3"></i>
                                            <h6 class="text-muted fw-600">No coupons found.</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Custom Modern Modals -->
            <!-- Add Coupon Modal -->
            <div id="couponModal" class="custom-modal-overlay">
                <div class="custom-modal-box shadow-lg rounded-lg bg-white">
                    <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top">
                        <h5 class="fw-700 text-dark mb-0"><i class="las la-plus-circle text-primary mr-2"></i>Add New Coupon</h5>
                        <button type="button" class="close cancel-btn bg-transparent border-0 fs-24 text-muted" id="cancelBtn">&times;</button>
                    </div>
                    <form id="addCouponForm" class="p-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-600 text-dark fs-13">User Type</label>
                                <select class="form-control form-control-sm modern-input" name="user_type" required>
                                    <option value="" selected disabled>Select User Type</option>
                                    <option value="auth">Authorised Users</option>
                                    <option value="all">All Users</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-600 text-dark fs-13">Coupon Type</label>
                                <select class="form-control form-control-sm modern-input" name="coupon_type" required>
                                    <option value="" selected disabled>Select Coupon Type</option>
                                    <option value="product" disabled>Product (Coming Soon)</option>
                                    <option value="total_order">Total Order</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-600 text-dark fs-13">Use Type</label>
                                <select class="form-control form-control-sm modern-input" name="use_type" required>
                                    <option value="" selected disabled>Select Use Type</option>
                                    <option value="single">Single Use</option>
                                    <option value="multi">Multiple Use</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-600 text-dark fs-13">Minimum Cart Amount</label>
                                <input type="number" class="form-control form-control-sm modern-input" name="min_cart_amount" placeholder="e.g. 500">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-600 text-dark fs-13">Coupon Code</label>
                            <input type="text" class="form-control form-control-sm modern-input font-weight-bold text-uppercase" name="code" placeholder="e.g. SUMMER50" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-600 text-dark fs-13">Coupon Details (Optional)</label>
                            <input type="text" class="form-control form-control-sm modern-input" name="details" placeholder="Brief description of the offer">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="fw-600 text-dark fs-13">Discount Type</label>
                                <select class="form-control form-control-sm modern-input" name="discount_type" required>
                                    <option value="" selected disabled>Select</option>
                                    <option value="percent">Percent (%)</option>
                                    <option value="static">Static Amount (৳)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-600 text-dark fs-13">Discount Value</label>
                                <input type="number" class="form-control form-control-sm modern-input" name="discount" placeholder="Value" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-600 text-dark fs-13">Quantity</label>
                                <input type="number" class="form-control form-control-sm modern-input" name="quantity" placeholder="Limit" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="fw-600 text-dark fs-13">Start Date</label>
                                <input type="date" class="form-control form-control-sm modern-input" name="start_date" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="fw-600 text-dark fs-13">End Date</label>
                                <input type="date" class="form-control form-control-sm modern-input" name="end_date" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end border-top pt-3">
                            <button type="button" class="btn btn-light btn-sm rounded-pill px-4 fw-600 mr-2 cancel-btn shadow-sm" id="cancelBtnBottom">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-600 submit-btn shadow-sm">Create Coupon</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Coupon Modal -->
            <div id="couponEditModal" class="custom-modal-overlay">
                <div class="custom-modal-box shadow-lg rounded-lg bg-white">
                    <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top">
                        <h5 class="fw-700 text-dark mb-0"><i class="las la-pen-square text-primary mr-2"></i>Edit Coupon</h5>
                        <button type="button" class="close cancel-btn bg-transparent border-0 fs-24 text-muted" id="cancelEditBtn">&times;</button>
                    </div>
                    <form id="editCouponForm" class="p-4">
                        @csrf
                        <input type="hidden" id="edit_coupon_id" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-600 text-dark fs-13">User Type</label>
                                <select class="form-control form-control-sm modern-input" name="user_type" id="edit_user_type" required>
                                    <option value="" selected disabled>Select User Type</option>
                                    <option value="auth">Authorised Users</option>
                                    <option value="all">All Users</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-600 text-dark fs-13">Coupon Type</label>
                                <select class="form-control form-control-sm modern-input" name="coupon_type" id="edit_coupon_type" required>
                                    <option value="" selected disabled>Select Coupon Type</option>
                                    <option value="product" disabled>Product (Coming Soon)</option>
                                    <option value="total_order">Total Order</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-600 text-dark fs-13">Use Type</label>
                                <select class="form-control form-control-sm modern-input" name="use_type" id="edit_use_type" required>
                                    <option value="" selected disabled>Select Use Type</option>
                                    <option value="single">Single Use</option>
                                    <option value="multi">Multiple Use</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-600 text-dark fs-13">Minimum Cart Amount</label>
                                <input type="number" class="form-control form-control-sm modern-input" name="min_cart_amount" id="edit_min_cart_amount" placeholder="e.g. 500">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-600 text-dark fs-13">Coupon Code</label>
                            <input type="text" class="form-control form-control-sm modern-input font-weight-bold text-uppercase" name="code" id="edit_code" placeholder="e.g. SUMMER50" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-600 text-dark fs-13">Coupon Details (Optional)</label>
                            <input type="text" class="form-control form-control-sm modern-input" name="details" id="edit_details" placeholder="Brief description of the offer">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="fw-600 text-dark fs-13">Discount Type</label>
                                <select class="form-control form-control-sm modern-input" name="discount_type" id="edit_discount_type" required>
                                    <option value="" selected disabled>Select</option>
                                    <option value="percent">Percent (%)</option>
                                    <option value="static">Static Amount (৳)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-600 text-dark fs-13">Discount Value</label>
                                <input type="number" class="form-control form-control-sm modern-input" name="discount" id="edit_discount" placeholder="Value" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-600 text-dark fs-13">Quantity</label>
                                <input type="number" class="form-control form-control-sm modern-input" name="quantity" id="edit_quantity" placeholder="Limit" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="fw-600 text-dark fs-13">Start Date</label>
                                <input type="date" class="form-control form-control-sm modern-input" name="start_date" id="edit_start_date" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="fw-600 text-dark fs-13">End Date</label>
                                <input type="date" class="form-control form-control-sm modern-input" name="end_date" id="edit_end_date" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end border-top pt-3">
                            <button type="button" class="btn btn-light btn-sm rounded-pill px-4 fw-600 mr-2 cancel-btn shadow-sm" id="cancelEditBtnBottom">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-600 submit-btn shadow-sm">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        
        .modern-input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            height: auto;
            background-color: #f8fafc;
            transition: all 0.3s;
        }
        .modern-input:focus {
            background-color: #fff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .custom-modal-overlay {
            visibility: hidden;
            display: flex;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .custom-modal-overlay.show {
            visibility: visible;
            opacity: 1;
        }
        .custom-modal-box {
            width: 100%;
            max-width: 650px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            transform: translateY(-20px) scale(0.95);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            max-height: 90vh;
            overflow-y: auto;
        }
        .custom-modal-overlay.show .custom-modal-box {
            transform: translateY(0) scale(1);
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            margin-bottom: 0;
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
            background-color: #cbd5e1;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #10b981;
        }
        input:checked + .slider:before {
            transform: translateX(20px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('script')
    <script>
        const couponModal = document.getElementById('couponModal');
        const addCouponBtn = document.getElementById('addCoupon');
        const cancelBtn = document.getElementById('cancelBtn');
        const cancelBtnBottom = document.getElementById('cancelBtnBottom');

        addCouponBtn.onclick = function() {
            couponModal.classList.add('show');
        }

        cancelBtn.onclick = function() {
            couponModal.classList.remove('show');
        }
        
        cancelBtnBottom.onclick = function() {
            couponModal.classList.remove('show');
        }

        window.addEventListener('click', function(event) {
            if (event.target === couponModal) {
                couponModal.classList.remove('show');
            }
        });
        
        document.getElementById('addCouponForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            let formData = new FormData(this);
            let submitBtn = this.querySelector('.submit-btn');
            submitBtn.disabled = true; 

            fetch("{{ route('coupon.store') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        let coupon = result.data;

                        let tableBody = document.getElementById('couponTableBody');
                        let typeBadge = coupon.coupon_type == 'total_order' 
                            ? '<span class="badge badge-soft-info px-2 py-1 rounded-pill fw-600">Total Order</span>' 
                            : '<span class="badge badge-soft-warning px-2 py-1 rounded-pill fw-600">Product</span>';
                        let discountVal = coupon.discount + (coupon.discount_type == 'percent' ? '%' : ' ৳');

                        let newRow = `
                        <tr class="border-bottom">
                            <td class="text-left px-4">${typeBadge}</td>
                            <td><span class="fw-700 text-dark fs-14 bg-light px-2 py-1 rounded border">${coupon.code}</span></td>
                            <td class="fw-600 text-primary">${discountVal}</td>
                            <td class="text-muted fs-13">${coupon.start_date}</td>
                            <td class="text-muted fs-13">${coupon.end_date}</td>
                            <td>
                                <label class="switch mb-0">
                                    <input class="status-switch" type="checkbox" data-id="${coupon.id}" checked>
                                    <span class="slider round shadow-sm" title="Click to change status"></span>
                                </label>
                            </td>
                            <td class="text-right px-4">
                                <button title="Edit" class="btn btn-icon btn-sm btn-soft-primary rounded-circle mr-2 editCoupon shadow-sm" data-id="${coupon.id}">
                                    <i class="las la-pen fs-16"></i>
                                </button>
                                <button class="btn btn-icon btn-sm btn-soft-danger rounded-circle couponDeleteBtn shadow-sm" data-id="${coupon.id}" title="Delete">
                                    <i class="las la-trash fs-16"></i>
                                </button>
                            </td>
                        </tr>
                        `;

                        tableBody.insertAdjacentHTML('afterbegin', newRow);

                        document.getElementById('addCouponForm').reset();
                        document.getElementById('couponModal').classList.remove('show');

                        Toast.fire({
                            icon: 'success',
                            title: result.message,
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please check all required fields.',
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                });
        });
    </script>

    <script>
        const couponEditModal = document.getElementById('couponEditModal');
        const cancelEditCouponBtn = document.getElementById('cancelEditBtn');
        const cancelEditBtnBottom = document.getElementById('cancelEditBtnBottom');

        $(document).on('click', '.editCoupon', function() {
            let id = $(this).data('id');
            console.log("Fetching data for ID:", id);

            fetch(`admin/coupon/edit/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_coupon_id').value = data.id;
                    if(document.getElementById('edit_user_type')) document.getElementById('edit_user_type').value = data.user_type || 'all';
                    document.getElementById('edit_coupon_type').value = data.type || data.coupon_type;
                    if(document.getElementById('edit_use_type')) document.getElementById('edit_use_type').value = data.use_type || 'single';
                    if(document.getElementById('edit_min_cart_amount')) document.getElementById('edit_min_cart_amount').value = data.min_cart_amount || '';
                    document.getElementById('edit_code').value = data.code;
                    if(document.getElementById('edit_details')) document.getElementById('edit_details').value = data.details || '';
                    document.getElementById('edit_discount').value = data.discount;
                    document.getElementById('edit_discount_type').value = data.discount_type;
                    if(document.getElementById('edit_quantity')) document.getElementById('edit_quantity').value = data.quantity || 1;
                    document.getElementById('edit_start_date').value = data.start_date;
                    document.getElementById('edit_end_date').value = data.end_date;

                    couponEditModal.classList.add('show');
                });

        });

        cancelEditCouponBtn.onclick = function() {
            couponEditModal.classList.remove('show');
        }
        
        cancelEditBtnBottom.onclick = function() {
            couponEditModal.classList.remove('show');
        }

        window.addEventListener('click', function(event) {
            if (event.target === couponEditModal) {
                couponEditModal.classList.remove('show');
            }
        });
        
        document.getElementById('editCouponForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let id = document.getElementById('edit_coupon_id').value;
            let formData = new FormData(this);

            let submitBtn = this.querySelector('.submit-btn');
            submitBtn.disabled = true;

            fetch(`/coupon/update/${id}`, {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        Toast.fire({
                            icon: 'success',
                            title: result.message,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please check all required fields.',
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                });
        });
    </script>

    <script>
        document.querySelectorAll('.status-switch').forEach(function(btn) {
            btn.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;

                fetch("{{ route('coupon.status') }}", {
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
                                title: status == 1 ? 'Activated Successfully' :
                                    'Deactivated Successfully'
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
    <script>
        $(document).on('click', '.couponDeleteBtn', function(e) {
            e.preventDefault();

            let id = $(this).data('id'); 
            let row = $(this).closest('tr'); 
            let url = "{{ route('coupon.delete', ':id') }}";
            url = url.replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                row.fadeOut(500, function() {
                                    $(this).remove();
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: data.message,
                                });
                            } else {
                                Swal.fire('Error!', 'Could not delete the item.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        });
                }
            });
        });
    </script>
@endsection
