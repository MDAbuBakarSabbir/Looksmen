@extends('layouts.Backend.master')
@section('title')
    Roles Management
@endsection

@section('style')
<style>
    .role-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(99, 102, 241, 0.1);
        color: #4f46e5;
    }
    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }
    .modern-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }
    .table-modern th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6b7280;
        border-bottom: 2px solid rgba(0,0,0,0.05);
    }
    .table-modern td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
        border-bottom: 1px solid rgba(0,0,0,0.02);
    }
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    .modern-input-group {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.08);
    }
    .modern-input-group .input-group-text {
        background-color: #fff;
        border: none;
        color: #9ca3af;
    }
    .modern-input-group .form-control {
        border: none;
        box-shadow: none;
        padding-left: 0;
    }
    .modern-input-group .form-control:focus {
        background-color: #fff;
    }
    .modern-input-group:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    body.dark-mode .modern-card {
        background-color: #1e293b;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    body.dark-mode .table-modern th {
        border-color: #334155;
    }
    body.dark-mode .table-modern td {
        border-color: #334155;
    }
    body.dark-mode .modern-input-group {
        border-color: #334155;
    }
    body.dark-mode .modern-input-group .input-group-text {
        background-color: #0f172a;
    }
    body.dark-mode .modern-input-group .form-control {
        background-color: #0f172a;
        color: #f8fafc;
    }
    body.dark-mode .modern-input-group:focus-within {
        border-color: #4f46e5;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-users-gear fs-4"></i>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold">Roles Management</h3>
                    <p class="text-muted mb-0 small">Manage roles and permissions for system users</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Roles Table -->
        <div class="col-lg-12">
            <div class="card modern-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-list-check me-2 text-primary"></i> Current Roles</h5>
                    <button type="button" class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 12px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#addRoleModal" data-toggle="modal" data-target="#addRoleModal">
                        <i class="fa-solid fa-plus me-2"></i> Add Role
                    </button>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table table-modern table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50%;">Role Name</th>
                                    <th scope="col" style="width: 25%;">Employee Count</th>
                                    <th scope="col" style="width: 25%;">Status</th>
                                    <th scope="col" class="text-end" style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="role-avatar me-3 shadow-sm">
                                                    <i class="fa-solid fa-shield-halved"></i>
                                                </div>
                                                <span class="fw-bold fs-6">{{ $role->role }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php 
                                                $employees = \App\Models\Admins::where('role_id', $role->role)->get(['name', 'email', 'number']);
                                                $employeeCount = $employees->count(); 
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-dark border me-2 fs-6 px-3 py-2 shadow-sm" style="border-radius: 20px;">{{ $employeeCount }}</span>
                                                <button type="button" class="btn btn-info btn-sm shadow-sm view-employees-btn" style="border-radius: 20px; font-weight: 600;" data-role="{{ $role->role }}" data-employees="{{ htmlspecialchars(json_encode($employees)) }}">
                                                    <i class="fa-solid fa-users me-1"></i> View Employees
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <label class="switch mb-0">
                                                <input class="status-switch" type="checkbox" data-id="{{ $role->id }}"
                                                    {{ $role->status == '1' ? 'checked' : '' }}>
                                                <span class="slider round" title="Click to Change Status"></span>
                                            </label>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-light btn-action text-primary shadow-sm edit-role" data-id="{{ $role->id }}" data-role="{{ $role->role }}" title="Edit Role">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <a href="{{ route('admin.role.permission', $role->id) }}" class="btn btn-light btn-action text-info shadow-sm" title="Manage Permissions">
                                                    <i class="fa-solid fa-key"></i>
                                                </a>   
                                                <button class="btn btn-light btn-action text-danger shadow-sm delete-role" data-id="{{ $role->id }}" title="Delete Role">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <div class="mb-3">
                                                <i class="fa-solid fa-folder-open text-muted opacity-50" style="font-size: 3rem;"></i>
                                            </div>
                                            <h6 class="fw-bold mb-1">No Roles Found</h6>
                                            <p class="small mb-0">Create a new role using the form to get started.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Role Modal -->
        <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modern-card border-0" style="width: 100%; max-width: 500px; padding: 0;">
                    <div class="modal-header border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title fw-bold" id="addRoleModalLabel">
                            <i class="fa-solid fa-plus-circle me-2 text-primary"></i> Create New Role
                        </h5>
                        <button type="button" class="btn-close text-muted" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none; font-size: 1.5rem; cursor: pointer;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="addRoleForm">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted small text-uppercase letter-spacing-1 mb-2">Role Name</label>
                                <div class="input-group modern-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-tag"></i>
                                    </span>
                                    <input type="text" name="role_name" class="form-control py-2" placeholder="e.g. Manager, Editor" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-light py-2 px-4 shadow-sm" style="border-radius: 12px; font-weight: 600;" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary py-2 px-4 shadow-sm" style="border-radius: 12px; font-weight: 600;">
                                    <i class="fa-solid fa-check me-2"></i> Submit Role
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Role Modal -->
        <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modern-card border-0" style="width: 100%; max-width: 500px; padding: 0;">
                    <div class="modal-header border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title fw-bold" id="editRoleModalLabel">
                            <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Role
                        </h5>
                        <button type="button" class="btn-close text-muted" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none; font-size: 1.5rem; cursor: pointer;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="editRoleForm">
                            @csrf
                            <input type="hidden" id="edit_role_id" name="id">
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted small text-uppercase letter-spacing-1 mb-2">Role Name</label>
                                <div class="input-group modern-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-tag"></i>
                                    </span>
                                    <input type="text" id="edit_role_name" name="role_name" class="form-control py-2" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-light py-2 px-4 shadow-sm" style="border-radius: 12px; font-weight: 600;" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary py-2 px-4 shadow-sm" style="border-radius: 12px; font-weight: 600;">
                                    <i class="fa-solid fa-check me-2"></i> Update Role
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- View Employees Modal -->
        <div class="modal fade" id="viewEmployeesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content modern-card border-0" style="border-radius: 16px;">
                    <div class="modal-header border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-users me-2 text-info"></i> Employees in <span id="employeeModalRoleName" class="text-primary text-capitalize"></span> Role
                        </h5>
                        <button type="button" class="btn-close text-muted" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none; font-size: 1.5rem; cursor: pointer;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="employeesListContainer">
                            <!-- Employees will be appended here -->
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-light py-2 px-4 shadow-sm" style="border-radius: 12px; font-weight: 600;" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Add Role
    $('#addRoleForm').on('submit', function(e) {
        e.preventDefault();
        let btn = $(this).find('button[type="submit"]');
        let originalText = btn.html();
        btn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Submitting...').prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.role.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Toast.fire({ icon: 'success', title: response.message });
                    setTimeout(() => { window.location.reload(); }, 1000);
                }
            },
            error: function() {
                btn.html(originalText).prop('disabled', false);
                Toast.fire({ icon: 'error', title: 'Something went wrong!' });
            }
        });
    });

    // Open Edit Modal
    $(document).on('click', '.edit-role', function(e) {
        e.preventDefault();
        $('#edit_role_id').val($(this).data('id'));
        $('#edit_role_name').val($(this).data('role'));
        
        let editModal = $('#editRoleModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            new bootstrap.Modal(editModal[0]).show();
        } else {
            editModal.addClass('show').css({display: 'block'});
            if(!$('.modal-backdrop').length) {
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
            }
        }
    });

    // View Employees Modal
    $(document).on('click', '.view-employees-btn', function(e) {
        e.preventDefault();
        let role = $(this).data('role');
        let employees = $(this).data('employees');
        
        // Handle parsing if it's a string, although jQuery usually auto-parses JSON in data attributes
        if (typeof employees === 'string') {
            try { employees = JSON.parse(employees); } catch(e) { employees = []; }
        }
        
        $('#employeeModalRoleName').text(role);
        
        let container = $('#employeesListContainer');
        container.empty();
        
        if (employees.length === 0) {
            container.html('<div class="text-center py-4 text-muted"><i class="fa-solid fa-user-xmark fs-1 mb-3 opacity-50"></i><h5>No employees found</h5><p class="small mb-0">There are no employees assigned to this role yet.</p></div>');
        } else {
            let list = $('<ul class="list-group list-group-flush" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);"></ul>');
            employees.forEach(function(emp) {
                list.append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-light text-primary d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 40px; height: 40px; font-weight: bold; font-size: 1.2rem;">
                                ${emp.name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">${emp.name}</h6>
                                <small class="text-muted"><i class="fa-solid fa-envelope me-1"></i> ${emp.email}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-dark border p-2"><i class="fa-solid fa-phone me-1"></i> ${emp.number}</span>
                        </div>
                    </li>
                `);
            });
            container.append(list);
        }
        
        let modal = $('#viewEmployeesModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            new bootstrap.Modal(modal[0]).show();
        } else {
            modal.addClass('show').css({display: 'block'});
            if(!$('.modal-backdrop').length) {
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
            }
        }
    });

    // Close Modals Fallback
    $('[data-dismiss="modal"], [data-bs-dismiss="modal"]').on('click', function(e) {
        e.preventDefault();
        if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
            $(this).closest('.modal').removeClass('show').css({display: 'none'});
            $('.modal-backdrop').remove();
        }
    });

    // Edit Role
    $('#editRoleForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_role_id').val();
        let btn = $(this).find('button[type="submit"]');
        let originalText = btn.html();
        btn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Updating...').prop('disabled', true);

        $.ajax({
            url: '/admin/admins/role/update/' + id,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Toast.fire({ icon: 'success', title: response.message });
                    setTimeout(() => { window.location.reload(); }, 1000);
                }
            },
            error: function() {
                btn.html(originalText).prop('disabled', false);
                Toast.fire({ icon: 'error', title: 'Update failed!' });
            }
        });
    });

    // Delete Role
    $(document).on('click', '.delete-role', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let row = $(this).closest('tr');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/admins/role/destroy/' + id,
                    type: 'POST',
                    data: { _method: 'POST' },
                    success: function(response) {
                        if (response.success) {
                            row.fadeOut(400, function() { $(this).remove(); });
                            Toast.fire({ icon: 'success', title: response.message });
                        } else {
                            Toast.fire({ icon: 'error', title: response.message });
                        }
                    }
                });
            }
        });
    });

    // Status Change
    $(document).on('change', '.status-switch', function() {
        let $switch = $(this);
        let id = $switch.data('id');
        let status = $switch.is(':checked') ? 1 : 0;
        
        $.ajax({
            url: '{{ route("admin.role.status") }}',
            type: 'POST',
            data: { id: id, status: status },
            success: function(response) {
                if (response.success) {
                    Toast.fire({ icon: 'success', title: 'Status updated successfully' });
                } else {
                    Toast.fire({ icon: 'error', title: response.message || 'Update failed' });
                    $switch.prop('checked', !status);
                }
            },
            error: function() {
                Toast.fire({ icon: 'error', title: 'Something went wrong!' });
                $switch.prop('checked', !status);
            }
        });
    });
</script>
@endsection
