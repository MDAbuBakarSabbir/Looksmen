@extends('layouts.Backend.master')

@section('title')
    FAQ & HELP CENTER MANAGEMENT
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

    /* Category Badges */
    .badge-cat {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .badge-shipping { background: #e0e7ff; color: #4338ca; }
    .badge-orders   { background: #dbeafe; color: #1d4ed8; }
    .badge-payments { background: #d1fae5; color: #047857; }
    .badge-returns  { background: #ffe4e6; color: #be123c; }
    .badge-account  { background: #fef3c7; color: #b45309; }
    .badge-general  { background: #f1f5f9; color: #475569; }

    /* Custom Pop-up Modal */
    .custom-modal {
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
    .custom-modal.show {
        opacity: 1;
        visibility: visible;
    }
    .custom-modal-content {
        background: #fff;
        padding: 28px;
        border-radius: 16px;
        width: 600px;
        max-width: 92%;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transform: scale(0.9);
        transition: transform 0.3s ease;
        max-height: 90vh;
        overflow-y: auto;
    }
    .custom-modal.show .custom-modal-content {
        transform: scale(1);
    }
    .custom-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 14px;
        margin-bottom: 18px;
    }
    .custom-modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    .form-label-custom {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
        display: block;
    }
    .form-control-custom {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        color: #1f2937;
        margin-bottom: 14px;
        transition: border-color 0.2s;
    }
    .form-control-custom:focus {
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .custom-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
        border-top: 1px solid #f3f4f6;
        padding-top: 15px;
    }
</style>

{{-- Metrics Cards --}}
<div class="row mb-4">
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="metrics-card card p-3 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Total FAQs</p>
                    <h3 class="mb-0 font-weight-bold" style="color: #1f2937;">{{ $totalFaqs }}</h3>
                </div>
                <div class="metrics-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="metrics-card card p-3 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Active Questions</p>
                    <h3 class="mb-0 font-weight-bold" style="color: #10b981;">{{ $activeFaqs }}</h3>
                </div>
                <div class="metrics-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="metrics-card card p-3 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Inactive Questions</p>
                    <h3 class="mb-0 font-weight-bold" style="color: #ef4444;">{{ $totalFaqs - $activeFaqs }}</h3>
                </div>
                <div class="metrics-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="metrics-card card p-3 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Categories</p>
                    <h3 class="mb-0 font-weight-bold" style="color: #4f46e5;">{{ count($categories) }}</h3>
                </div>
                <div class="metrics-icon-box" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main FAQ Table Panel --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-body p-0">
                
                {{-- Header Filter Bar --}}
                <div class="p-4 d-flex align-items-center justify-content-between flex-wrap border-bottom border-light gap-3">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;">FAQ List</h4>
                    
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        {{-- Category Filter Form --}}
                        <form method="GET" action="{{ route('admin.faq.index') }}" class="d-flex align-items-center gap-2 m-0">
                            <select name="category" class="form-control" style="border-radius: 8px; font-size: 13px; height: 38px; width: 170px;" onchange="this.form.submit()">
                                <option value="all">All Categories</option>
                                @foreach($categories as $key => $catName)
                                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $catName }}</option>
                                @endforeach
                            </select>

                            <div class="position-relative">
                                <i class="fa-solid fa-magnifying-glass text-muted position-absolute" style="left: 12px; top: 12px; font-size: 13px;"></i>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search questions..." style="padding-left: 34px; border-radius: 8px; width: 200px; font-size: 13px; height: 38px;">
                            </div>
                        </form>

                        <button type="button" id="btnOpenAddFaq" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none; padding: 9px 18px;">
                            <i class="fa-solid fa-plus"></i> Add New FAQ
                        </button>
                    </div>
                </div>

                {{-- Table Component --}}
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 60px;">Order</th>
                                <th scope="col" style="width: 150px;">Category</th>
                                <th scope="col">Question & Answer</th>
                                <th scope="col" style="width: 120px; text-align: center;">Status</th>
                                <th scope="col" style="width: 140px; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="faqsTableBody">
                            @forelse ($faqs as $faq)
                                <tr class="faq-row" id="faq-row-{{ $faq->id }}">
                                    <td>
                                        <span class="badge badge-light font-weight-bold text-dark" style="font-size: 13px;">#{{ $faq->order }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $catClass = 'badge-' . ($faq->category ?? 'general');
                                            $catLabel = $categories[$faq->category] ?? ucfirst($faq->category);
                                        @endphp
                                        <span class="badge-cat {{ $catClass }}">{{ $catLabel }}</span>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark mb-1" style="font-size: 14.5px;">
                                            {{ $faq->question }}
                                        </div>
                                        <div class="text-muted fs-12" style="max-width: 500px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ strip_tags($faq->answer) }}
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <label class="switch mb-0">
                                            <input class="faq-status-switch" type="checkbox" data-id="{{ $faq->id }}" {{ $faq->status == 1 ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="action-icon-btn edit btn-edit-faq" data-id="{{ $faq->id }}" title="Edit FAQ">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" class="action-icon-btn delete btn-delete-faq" data-id="{{ $faq->id }}" title="Delete FAQ">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-circle-question text-muted mb-3" style="font-size: 44px; opacity: 0.4;"></i>
                                        <p class="mb-0 font-weight-bold">No FAQ Questions Found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($faqs->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $faqs->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- ADD FAQ MODAL --}}
<div id="addFaqModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h3 class="custom-modal-title"><i class="fa-solid fa-plus text-primary mr-1"></i> Add New FAQ</h3>
            <button type="button" class="btn p-0 bg-transparent text-muted close-modal-btn" style="font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.faq.store') }}" method="POST">
            @csrf

            <label class="form-label-custom">Question Title <span class="text-danger">*</span></label>
            <input type="text" name="question" class="form-control-custom" placeholder="e.g. How do I track my order status?" required>

            <div class="row">
                <div class="col-md-7">
                    <label class="form-label-custom">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-control-custom" required>
                        @foreach($categories as $key => $catName)
                            <option value="{{ $key }}">{{ $catName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label-custom">Display Order</label>
                    <input type="number" name="order" class="form-control-custom" value="{{ $totalFaqs + 1 }}" min="0">
                </div>
            </div>

            <label class="form-label-custom">Answer Content <span class="text-danger">*</span></label>
            <textarea name="answer" class="form-control-custom" rows="5" placeholder="Detailed answer explanation (HTML supported)..." required></textarea>

            <div class="d-flex align-items-center gap-2 mb-3">
                <input type="checkbox" name="status" id="add_status" value="1" checked style="width: 18px; height: 18px;">
                <label for="add_status" class="mb-0 font-weight-bold text-dark" style="font-size: 13.5px; cursor: pointer;">Active and visible in Help Center</label>
            </div>

            <div class="custom-modal-footer">
                <button type="button" class="btn btn-light px-4 close-modal-btn font-weight-bold">Cancel</button>
                <button type="submit" class="btn btn-primary px-4 font-weight-bold" style="background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">Save FAQ</button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT FAQ MODAL --}}
<div id="editFaqModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h3 class="custom-modal-title"><i class="fa-solid fa-pen-to-square text-primary mr-1"></i> Edit FAQ Question</h3>
            <button type="button" class="btn p-0 bg-transparent text-muted close-modal-btn" style="font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editFaqForm" method="POST">
            @csrf

            <label class="form-label-custom">Question Title <span class="text-danger">*</span></label>
            <input type="text" name="question" id="edit_question" class="form-control-custom" required>

            <div class="row">
                <div class="col-md-7">
                    <label class="form-label-custom">Category <span class="text-danger">*</span></label>
                    <select name="category" id="edit_category" class="form-control-custom" required>
                        @foreach($categories as $key => $catName)
                            <option value="{{ $key }}">{{ $catName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label-custom">Display Order</label>
                    <input type="number" name="order" id="edit_order" class="form-control-custom" min="0">
                </div>
            </div>

            <label class="form-label-custom">Answer Content <span class="text-danger">*</span></label>
            <textarea name="answer" id="edit_answer" class="form-control-custom" rows="5" required></textarea>

            <div class="d-flex align-items-center gap-2 mb-3">
                <input type="checkbox" name="status" id="edit_status" value="1" style="width: 18px; height: 18px;">
                <label for="edit_status" class="mb-0 font-weight-bold text-dark" style="font-size: 13.5px; cursor: pointer;">Active and visible in Help Center</label>
            </div>

            <div class="custom-modal-footer">
                <button type="button" class="btn btn-light px-4 close-modal-btn font-weight-bold">Cancel</button>
                <button type="submit" class="btn btn-primary px-4 font-weight-bold" style="background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">Update FAQ</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    const addModal = document.getElementById('addFaqModal');
    const editModal = document.getElementById('editFaqModal');

    // Open Add Modal
    document.getElementById('btnOpenAddFaq').onclick = function() {
        addModal.classList.add('show');
    };

    // Close Modals
    document.querySelectorAll('.close-modal-btn').forEach(btn => {
        btn.onclick = function() {
            addModal.classList.remove('show');
            editModal.classList.remove('show');
        };
    });

    window.onclick = function(event) {
        if (event.target === addModal) addModal.classList.remove('show');
        if (event.target === editModal) editModal.classList.remove('show');
    };

    // Edit Modal Loader
    $(document).on('click', '.btn-edit-faq', function() {
        const id = $(this).data('id');
        const url = `/admin/faq/edit/${id}`;

        $.get(url, function(res) {
            if (res.success) {
                const faq = res.faq;
                $('#editFaqForm').attr('action', `/admin/faq/update/${faq.id}`);
                $('#edit_question').val(faq.question);
                $('#edit_category').val(faq.category);
                $('#edit_order').val(faq.order);
                $('#edit_answer').val(faq.answer);
                $('#edit_status').prop('checked', faq.status == 1);

                editModal.classList.add('show');
            } else {
                Toast.fire({ icon: 'error', title: 'Could not load FAQ details' });
            }
        }).fail(function() {
            Toast.fire({ icon: 'error', title: 'Network error' });
        });
    });

    // Toggle Status
    $(document).on('change', '.faq-status-switch', function() {
        const id = $(this).data('id');
        const status = this.checked ? 1 : 0;

        fetch("{{ route('admin.faq.status') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id: id, status: status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Toast.fire({
                    icon: 'success',
                    title: status === 1 ? 'FAQ Activated' : 'FAQ Deactivated'
                });
            } else {
                Toast.fire({ icon: 'error', title: 'Could not update status' });
            }
        })
        .catch(() => {
            Toast.fire({ icon: 'error', title: 'Server Error' });
        });
    });

    // Delete FAQ
    $(document).on('click', '.btn-delete-faq', function() {
        const id = $(this).data('id');
        const deleteUrl = `/admin/faq/destroy/${id}`;

        Swal.fire({
            title: 'Delete this FAQ question?',
            text: "This question will be removed from the Help Center.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({ icon: 'success', title: 'Deleted successfully!' });
                        $(`#faq-row-${id}`).fadeOut(300, function() { $(this).remove(); });
                    } else {
                        Swal.fire('Error', data.message || 'Could not delete FAQ.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Network issue occurred.', 'error');
                });
            }
        });
    });
</script>
@endsection
