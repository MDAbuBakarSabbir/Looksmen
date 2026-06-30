@extends('layouts.Backend.master')
@section('title')
    COLORS
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
        .color-dot-preview {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #d1d5db, 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: inline-block;
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
    </style>

    {{-- Metrics section --}}
    <div class="row mb-4">
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Total Colors</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #1f2937;">{{ $colors->count() }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Active Colors</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #10b981;">{{ $colors->where('status', '1')->count() }}</h3>
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
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Inactive Colors</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #ef4444;">{{ $colors->where('status', '0')->count() }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Colors table --}}
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-palette text-primary mr-2"></i>Colors List</h4>
                    <div class="position-relative">
                        <i class="fa-solid fa-magnifying-glass text-muted position-absolute" style="left: 12px; top: 12px; font-size: 14px;"></i>
                        <input type="text" id="colorSearch" class="form-control" placeholder="Search colors..." style="padding-left: 36px; border-radius: 8px; width: 220px; font-size: 13px;">
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 80px;">#</th>
                                    <th scope="col">Color Name</th>
                                    <th scope="col">Preview</th>
                                    <th scope="col">Color Code</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 120px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="colorTableBody">
                                @forelse ($colors as $index => $color)
                                    <tr class="color-row">
                                        <td class="font-weight-bold">{{ $index + 1 }}</td>
                                        <td class="font-weight-bold text-dark">{{ $color->color_name }}</td>
                                        <td>
                                            <span class="color-dot-preview" style="background-color: {{ $color->color_code }};"></span>
                                        </td>
                                        <td><code style="background: #f3f4f6; color: #4b5563; padding: 4px 8px; border-radius: 4px; font-weight: 600;">{{ $color->color_code }}</code></td>
                                        <td>
                                            <label class="switch mb-0">
                                                <input class="status-switch" type="checkbox" data-id="{{ $color->id }}" {{ $color->status == '1' ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="d-inline-flex gap-2">
                                                <a href="{{ route('color.edit', $color->id) }}" class="action-icon-btn edit" title="Edit Color"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="{{ route('color.destroy', $color->id) }}" class="action-icon-btn delete" title="Delete Color" onclick="return confirm('Are you sure you want to delete this Color?');"><i class="fa-solid fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-palette text-muted mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                                            <p class="mb-0 font-weight-bold">No Colors Found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add form --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-circle-plus text-success mr-2"></i>Add Color</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('color.joma') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 13px;">Color Name<span class="text-danger">*</span></label>
                            <input type="text" name="color_name" class="form-control" placeholder="e.g. Pure Red, Navy Blue" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 13px;">Color Code (Hex/Color Picker)<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="color_code" id="colorCodeInput" class="form-control" placeholder="e.g. #ff0000" required style="border-radius: 8px 0 0 8px; padding: 10px 14px;">
                                <div class="input-group-append">
                                    <input type="color" id="colorPicker" class="form-control" style="width: 50px; height: 100%; border: 1px solid #d1d5db; border-left: none; padding: 2px; border-radius: 0 8px 8px 0; cursor: pointer;">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block" style="border-radius: 8px; font-weight: 600; padding: 11px; background: linear-gradient(135deg, #10b981, #059669); border: none;">
                            <i class="fa-solid fa-circle-check mr-2"></i>Submit Color
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sync color picker with hex input
        const colorPicker = document.getElementById('colorPicker');
        const colorInput = document.getElementById('colorCodeInput');
        if (colorPicker && colorInput) {
            colorPicker.addEventListener('input', function() {
                colorInput.value = this.value;
            });
            colorInput.addEventListener('input', function() {
                if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                    colorPicker.value = this.value;
                }
            });
        }

        // Search logic
        document.getElementById('colorSearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#colorTableBody .color-row');
            
            rows.forEach(function(row) {
                let name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                let code = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                if (name.indexOf(filter) > -1 || code.indexOf(filter) > -1) {
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

                fetch("{{ route('color.status') }}", {
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
                                title: status == 1 ? 'Color Activated' : 'Color Deactivated'
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
