@extends('layouts.Backend.master')
@section('title')
    ATTRIBUTES
@endsection
@section('content')
    <style>
        /* --- Premium Admin Style Rules --- */
        .metrics-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
        }
        .metrics-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
        .metrics-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .table-custom th {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #374151;
            background-color: #f9fafb !important;
            border-bottom: 2px solid #e5e7eb;
            padding: 16px 20px;
        }
        .table-custom td {
            padding: 16px 20px;
            vertical-align: middle;
            font-size: 14px;
            color: #4b5563;
            border-bottom: 1px solid #f3f4f6;
        }
        .value-badge {
            background-color: rgba(99, 102, 241, 0.08);
            color: #4f46e5;
            border: 1px solid rgba(99, 102, 241, 0.15);
            font-weight: 600;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
            margin: 2px;
        }
        .action-icon-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        .action-icon-btn.edit {
            background: rgba(99, 102, 241, 0.1);
            color: #4f46e5;
        }
        .action-icon-btn.edit:hover {
            background: #4f46e5;
            color: #fff;
        }
        .action-icon-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        .action-icon-btn.delete:hover {
            background: #ef4444;
            color: #fff;
        }
        /* --- Modern Pop-up Modal Customization --- */
        .modal {
            position: fixed;
            z-index: 1060;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .modal.show {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            width: 460px;
            max-width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            border: none;
        }
        .modal.show .modal-content {
            transform: scale(1);
        }
        .modal-header-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .modal-title-custom {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 6px;
            display: block;
        }
        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            margin-bottom: 16px;
            transition: border-color 0.2s;
        }
        .form-control-custom:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .modal-footer-custom {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
            border-top: 1px solid #f3f4f6;
            padding-top: 15px;
        }
        .btn-submit-custom {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-submit-custom:hover {
            opacity: 0.9;
        }
        .btn-cancel-custom {
            background: #f3f4f6;
            color: #4b5563;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 24px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-cancel-custom:hover {
            background: #e5e7eb;
            color: #1f2937;
        }
    </style>

    {{-- Metrics section --}}
    <div class="row mb-4">
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Total Attributes</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #1f2937;">{{ $attributes->count() }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Active Attributes</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #10b981;">{{ $attributes->where('status', '1')->count() }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Inactive Attributes</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #ef4444;">{{ $attributes->where('status', '0')->count() }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main panel --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-0">
                    <div class="p-4 d-flex align-items-center justify-content-between flex-wrap border-bottom border-light gap-3">
                        <h4 class="mb-0 font-weight-bold" style="color: #1f2937;">Attributes List</h4>
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative">
                                <i class="fa-solid fa-magnifying-glass text-muted position-absolute" style="left: 12px; top: 12px; font-size: 14px;"></i>
                                <input type="text" id="attributeSearch" class="form-control" placeholder="Search attributes..." style="padding-left: 36px; border-radius: 8px; width: 250px; font-size: 13px;">
                            </div>
                            <button id="addAttribute" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none; padding: 9px 18px;">
                                <i class="fa-solid fa-plus"></i> Add Attribute
                            </button>
                        </div>
                    </div>

                    {{-- Dynamic Pop-up Modal --}}
                    <div id="AttributeModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header-custom">
                                <h3 class="modal-title-custom">Add Attribute</h3>
                                <button type="button" class="btn p-0 bg-transparent text-muted" id="closeModalCross" style="font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form action="{{ route('attribute.store') }}" method="POST">
                                @csrf

                                <label class="form-label-custom">Attribute Name<span class="text-danger">*</span></label>
                                <input type="text" name="attribute_name" class="form-control-custom" placeholder="e.g. Size, Material, Brand" required>

                                <div class="modal-footer-custom">
                                    <button type="button" class="btn-cancel-custom" id="cancelBtn">Cancel</button>
                                    <button type="submit" class="btn-submit-custom">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 80px;">#</th>
                                    <th scope="col">Attribute Name</th>
                                    <th scope="col">Attribute Values</th>
                                    <th scope="col" style="width: 140px;">Status</th>
                                    <th scope="col" style="width: 150px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="attributeTableBody">
                                @forelse ($attributes as $index => $attribute)
                                    <tr class="attribute-row">
                                        <td class="font-weight-bold">{{ $index + 1 }}</td>
                                        <td class="font-weight-bold text-dark">{{ $attribute->name }}</td>
                                        <td>
                                            @forelse ($attribute->AttributeValues as $value)
                                                <span class="value-badge">{{ $value->value }}</span>
                                            @empty
                                                <span class="text-muted font-italic" style="font-size: 13px;">No values defined yet</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <label class="switch mb-0">
                                                <input class="status-switch" type="checkbox" data-id="{{ $attribute->id }}" {{ $attribute->status == '1' ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="d-inline-flex gap-2">
                                                <a href="{{ route('attribute.create', $attribute->id) }}" class="action-icon-btn edit" title="Manage Values">
                                                    <i class="fa-solid fa-list-ul"></i>
                                                </a>
                                                <a href="{{ route('attribute.edit', $attribute->id) }}" class="action-icon-btn edit" style="background: rgba(16, 185, 129, 0.1); color: #10b981;" title="Edit Attribute Name">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="{{ route('attribute.destroy', $attribute->id) }}" class="action-icon-btn delete" title="Delete Attribute" onclick="return confirm('Are you sure you want to delete this Attribute? Clicking OK will delete all values too.');">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                                            <p class="mb-0 font-weight-bold">No Attributes Found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('AttributeModal');
        const addBtn = document.getElementById('addAttribute');
        const cancelBtn = document.getElementById('cancelBtn');
        const closeCross = document.getElementById('closeModalCross');

        // Show modal
        addBtn.onclick = function() {
            modal.classList.add('show');
        }

        // Hide modal
        cancelBtn.onclick = function() {
            modal.classList.remove('show');
        }
        closeCross.onclick = function() {
            modal.classList.remove('show');
        }

        // Hide modal when clicking outside
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        }

        // Search logic
        document.getElementById('attributeSearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#attributeTableBody .attribute-row');
            
            rows.forEach(function(row) {
                let name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                let values = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (name.indexOf(filter) > -1 || values.indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        document.querySelectorAll('.status-switch').forEach(function(btn) {
            btn.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;

                fetch("{{ route('attribute.status') }}", {
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
                                title: status == 1 ? 'Attribute Activated' : 'Attribute Deactivated'
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
@endsection
