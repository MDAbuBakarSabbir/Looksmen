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
                <a class="btn btn-primary px-4 d-flex align-items-center" href="{{route('admin.create')}}" style="border-radius: 30px; font-weight: 600; font-size: 13px; height: 38px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); gap: 8px;">
                    <i class="fa-solid fa-user-plus"></i> Add Employee
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <select id="bulkAdminAction" class="form-control mr-2" style="width: 180px; display: inline-block;">
                    <option value="">Bulk Action</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
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
            let status = action === 'activate' ? 1 : 0;
            let requests = selectedIds.map(id => {
                return fetch("{{ route('admin.status') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ id: id, status: status })
                }).then(res => res.json());
            });
            Promise.all(requests).then(results => {
                let failed = results.filter(r => !r.success).length;
                if (failed === 0) {
                    Toast.fire({ icon: 'success', title: selectedIds.length + ' admins updated' });
                    $('.admin-check:checked').closest('tr').each(function() {
                        $(this).find('.status-switch').prop('checked', status == 1);
                    });
                } else {
                    Toast.fire({ icon: 'error', title: failed + ' updates failed' });
                }
            }).catch(() => {
                Toast.fire({ icon: 'error', title: 'Network error' });
            });
            $('#adminCheckAll').prop('checked', false);
        });
    </script>
@endsection
