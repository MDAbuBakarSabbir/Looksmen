@extends('layouts.Backend.master')
@section('title')
    EMPLOYEES
@endsection
@section('content')
    <style>
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

        /* Search input styling */
        .search-box-container:focus-within {
            width: 320px !important;
        }
        .search-box-container:focus-within .search-icon {
            color: #4f46e5 !important;
        }
        #search:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.15) !important;
            outline: none !important;
        }
    </style>

    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
            <h3 class="card-title font-weight-bold text-dark mb-0">Employee List</h3>
            <div class="d-flex align-items-center flex-wrap" style="gap: 15px;">
                <div class="position-relative search-box-container" style="width: 260px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                    <i class="fa-solid fa-magnifying-glass search-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; transition: color 0.3s ease;"></i>
                    <input class="form-control" type="search" name="search" id="search" placeholder="Search name, email, ID, phone..." style="padding-left: 38px; border-radius: 30px; font-size: 13px; font-weight: 500; border: 1px solid rgba(0,0,0,0.15); box-shadow: 0 2px 4px rgba(0,0,0,0.01); transition: all 0.3s ease; height: 38px;">
                </div>
                <button type="button" class="btn btn-primary px-4 d-flex align-items-center" data-toggle="modal" data-target="#addEmployeeModal" data-bs-toggle="modal" data-bs-target="#addEmployeeModal" style="border-radius: 30px; font-weight: 600; font-size: 13px; height: 38px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); gap: 8px;">
                    <i class="fa-solid fa-user-plus"></i> Add Employee
                </button>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <select id="bulkAdminAction" class="form-control mr-2" style="width: 180px; display: inline-block;">
                    <option value="">Bulk Action</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="delete">Delete</option>
                </select>
                <button class="btn btn-danger" id="bulkAdminBtn" style="height: 38px; border-radius: 4px; padding: 0 20px;">
                    Apply Action
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-uppercase" style="font-size: 12px; letter-spacing: 0.5px; color: #4b5563;">
                            <th scope="col" style="width: 50px;"><input type="checkbox" id="adminCheckAll"></th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th scope="col">Activity</th>
                            <th scope="col" style="text-align: right; padding-right: 25px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                        @include('adminDash.admins.extends.admin_rows')
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
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

        // Dynamic keyup search with debounce
        let searchTimeout;
        $('#search').on('input', function() {
            clearTimeout(searchTimeout);
            let term = $(this).val();

            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: "{{ route('admin.search') }}",
                    method: "GET",
                    data: { search: term },
                    success: function(html) {
                        $('#employeeTableBody').html(html);
                        $('#adminCheckAll').prop('checked', false);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Search failed'
                        });
                    }
                });
            }, 300); // 300ms debounce
        });

        // Delegated status toggle using jQuery event delegation
        $(document).on('change', '.status-switch', function() {
            let id = $(this).data('id');
            let status = this.checked ? 1 : 0;

            fetch("{{ route('admin.status') }}", {
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
                        title: status == 1 ? 'Activated Successfully' : 'Deactivated Successfully'
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

        // Check All / Uncheck All
        $(document).on('change', '#adminCheckAll', function() {
            $('.admin-check').prop('checked', $(this).prop('checked'));
        });

        // Sync check all with individual checkboxes
        $(document).on('change', '.admin-check', function() {
            if ($('.admin-check:checked').length === $('.admin-check').length && $('.admin-check').length > 0) {
                $('#adminCheckAll').prop('checked', true);
            } else {
                $('#adminCheckAll').prop('checked', false);
            }
        });

        // Bulk Admin Action Handler
        $(document).on('click', '#bulkAdminBtn', function(e) {
            e.preventDefault();
            let action = $('#bulkAdminAction').val();
            if (!action) {
                Toast.fire({ icon: 'warning', title: 'Please select an action' });
                return;
            }
            let selectedIds = [];
            $('.admin-check:checked').each(function() {
                selectedIds.push($(this).val());
            });
            if (selectedIds.length === 0) {
                Toast.fire({ icon: 'warning', title: 'No admins selected' });
                return;
            }
            let url = action === 'delete' ? "{{ route('admin.bulk-delete') }}" : "{{ route('admin.bulk-status') }}";
            let payload = action === 'delete' ? { ids: selectedIds } : { ids: selectedIds, status: action === 'activate' ? 1 : 0 };

            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(payload)
            }).then(res => res.json())
            .then(response => {
                if (response.success) {
                    Toast.fire({ icon: 'success', title: response.message || selectedIds.length + ' admins updated' });
                    if (action === 'delete') {
                        setTimeout(() => { window.location.reload(); }, 1000);
                    } else {
                        $('.admin-check:checked').closest('tr').each(function() {
                            $(this).find('.status-switch').prop('checked', action === 'activate');
                        });
                    }
                } else {
                    Toast.fire({ icon: 'error', title: response.message || 'Action failed' });
                }
                $('#adminCheckAll').prop('checked', false);
            }).catch(() => {
                Toast.fire({ icon: 'error', title: 'Network error' });
                $('#adminCheckAll').prop('checked', false);
            });
        });


        // Edit Employee Modal Open
        $(document).on('click', '.edit-admin-btn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let name = $(this).data('name');
            let email = $(this).data('email');
            let number = $(this).data('number');
            let role = $(this).data('role');

            $('#edit_admin_id').val(id);
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_number').val(number);
            $('#edit_role_id').val(role);
            
            // Clear previous errors
            $('#editEmployeeForm').find('.is-invalid').removeClass('is-invalid');
            $('#editEmployeeForm').find('.invalid-feedback').html('');

            let editModal = $('#editEmployeeModal');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                new bootstrap.Modal(editModal[0]).show();
            } else {
                editModal.addClass('show').css({display: 'block'});
                if(!$('.modal-backdrop').length) {
                    $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
                }
            }
        });

        // Edit Employee AJAX Submit
        $('#editEmployeeForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let btn = form.find('button[type="submit"]');
            let originalText = btn.html();
            let id = $('#edit_admin_id').val();
            btn.html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Updating...').prop('disabled', true);
            
            // Clear previous errors
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').html('');

            $.ajax({
                url: '/admin/admins/update/' + id,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    let editModal = $('#editEmployeeModal');
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        let modalInstance = bootstrap.Modal.getInstance(editModal[0]);
                        if(modalInstance) modalInstance.hide();
                    } else {
                        editModal.modal('hide');
                    }
                    
                    Toast.fire({ icon: 'success', title: response.message || 'Admin employee updated successfully!' });
                    setTimeout(() => { window.location.reload(); }, 1000);
                },
                error: function(xhr) {
                    btn.html(originalText).prop('disabled', false);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            form.find('#edit_' + field).addClass('is-invalid');
                            form.find('.error-' + field).html(errors[field][0]).show();
                        }
                    } else {
                        Toast.fire({ icon: 'error', title: 'Something went wrong!' });
                    }
                }
            });
        });

        // Add Employee AJAX Submit
        $('#addEmployeeForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let btn = form.find('button[type="submit"]');
            let originalText = btn.html();
            btn.html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...').prop('disabled', true);
            
            // Clear previous errors
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').html('');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    let addModal = $('#addEmployeeModal');
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        let modalInstance = bootstrap.Modal.getInstance(addModal[0]);
                        if(modalInstance) modalInstance.hide();
                    } else {
                        addModal.modal('hide');
                    }
                    
                    Toast.fire({ icon: 'success', title: response.message || 'Admin employee created successfully!' });
                    setTimeout(() => { window.location.reload(); }, 1000);
                },
                error: function(xhr) {
                    btn.html(originalText).prop('disabled', false);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            form.find('#' + field).addClass('is-invalid');
                            form.find('.error-' + field).html(errors[field][0]).show();
                        }
                    } else {
                        Toast.fire({ icon: 'error', title: 'Something went wrong!' });
                    }
                }
            });
        });

        // Close Modals Fallback
        $('[data-dismiss="modal"], [data-bs-dismiss="modal"]').on('click', function(e) {
            e.preventDefault();
            if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                $(this).closest('.modal').removeClass('show').css({display: 'none'});
                $('.modal-backdrop').remove();
            }
        });

        // Individual Delete Admin
        window.deleteAdmin = function(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/admins/destroy/' + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Toast.fire({ icon: 'success', title: response.message });
                                setTimeout(() => { window.location.reload(); }, 1000);
                            } else {
                                Toast.fire({ icon: 'error', title: response.message });
                            }
                        },
                        error: function() {
                            Toast.fire({ icon: 'error', title: 'Something went wrong!' });
                        }
                    });
                }
            });
        };
    </script>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-sm border-0" style="border-radius: 12px;">
                <div class="modal-header border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title font-weight-bold text-dark mb-0" id="addEmployeeModalLabel">
                        <i class="fa-solid fa-user-plus me-2 text-primary"></i> Add New Admin Employee
                    </h5>
                    <button type="button" class="btn-close text-muted" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none; font-size: 1.5rem; cursor: pointer;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <form id="addEmployeeForm" action="{{ route('admin.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label font-weight-bold text-muted">Admin Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required style="border-radius: 8px;">
                                <div class="invalid-feedback error-name"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label font-weight-bold text-muted">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required style="border-radius: 8px;">
                                <div class="invalid-feedback error-email"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="number" class="form-label font-weight-bold text-muted">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="number" name="number" placeholder="Enter phone number" required style="border-radius: 8px;">
                                <div class="invalid-feedback error-number"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role_id" class="form-label font-weight-bold text-muted">Select Role <span class="text-danger">*</span></label>
                                <select class="form-control" id="role_id" name="role_id" required style="border-radius: 8px; height: auto; padding: 10px;">
                                    <option value="" disabled selected>Choose a role...</option>
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->role }}">{{ ucfirst($role->role) }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback error-role_id"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label font-weight-bold text-muted">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Minimum 8 characters" required style="border-radius: 8px;">
                                <div class="invalid-feedback error-password"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label font-weight-bold text-muted">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repeat password" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-light px-4 mr-2" style="border-radius: 8px;" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);">
                                <i class="fa fa-check-circle mr-1"></i> Save Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-sm border-0" style="border-radius: 12px;">
                <div class="modal-header border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title font-weight-bold text-dark mb-0" id="editEmployeeModalLabel">
                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Admin Employee
                    </h5>
                    <button type="button" class="btn-close text-muted" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none; font-size: 1.5rem; cursor: pointer;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <form id="editEmployeeForm">
                        @csrf
                        <input type="hidden" id="edit_admin_id" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_name" class="form-label font-weight-bold text-muted">Admin Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" placeholder="Enter full name" required style="border-radius: 8px;">
                                <div class="invalid-feedback error-name"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_email" class="form-label font-weight-bold text-muted">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="edit_email" name="email" placeholder="Enter email address" required style="border-radius: 8px;">
                                <div class="invalid-feedback error-email"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_number" class="form-label font-weight-bold text-muted">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_number" name="number" placeholder="Enter phone number" required style="border-radius: 8px;">
                                <div class="invalid-feedback error-number"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_role_id" class="form-label font-weight-bold text-muted">Select Role <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_role_id" name="role_id" required style="border-radius: 8px; height: auto; padding: 10px;">
                                    <option value="" disabled>Choose a role...</option>
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->role }}">{{ ucfirst($role->role) }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback error-role_id"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="edit_password" class="form-label font-weight-bold text-muted">New Password <small>(Leave blank to keep current)</small></label>
                                <input type="password" class="form-control" id="edit_password" name="password" placeholder="Minimum 8 characters" style="border-radius: 8px;">
                                <div class="invalid-feedback error-password"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="edit_password_confirmation" class="form-label font-weight-bold text-muted">Confirm New Password</label>
                                <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation" placeholder="Repeat password" style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-light px-4 mr-2" style="border-radius: 8px;" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);">
                                <i class="fa fa-save mr-1"></i> Update Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
