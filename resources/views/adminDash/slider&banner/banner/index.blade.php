@extends('layouts.adminLays.master')
@section('title')
    BANNER
@endsection
@section('content')
<style>
    /* Google Fonts for a modern look */
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: rgba(255, 255, 255, 0.5);
        --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    body, .content-body {
        font-family: 'Outfit', sans-serif !important;
        background-color: #f8fafc;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: var(--glass-shadow);
        overflow: hidden;
        margin-bottom: 24px;
        transition: transform 0.3s ease;
    }

    .premium-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .premium-header h3 {
        margin: 0;
        font-weight: 700;
        color: var(--text-main);
    }

    .btn-gradient-primary {
        background: var(--primary-gradient);
        border: none;
        color: white;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
        color: white;
    }

    /* Modern Table */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .table-modern th {
        background-color: #f8fafc;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-modern td {
        padding: 16px 24px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-main);
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .table-modern tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* Modern Modal */
    .modal-glass {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(5px);
        z-index: 9999;
    }

    .modal-glass .modal-content {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        padding: 30px;
    }

    /* Image Upload Box */
    .image-upload-box {
        width: 100%;
        height: 150px;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        background-color: #f8fafc;
        transition: all 0.3s ease;
    }

    .image-upload-box:hover {
        border-color: #6366f1;
        background-color: #eef2ff;
    }

    .image-upload-box i {
        font-size: 40px;
        color: #94a3b8;
        transition: color 0.3s ease;
    }

    .image-upload-box:hover i {
        color: #6366f1;
    }

    /* Thumbnail Styles */
    .thumbnail-img {
        height: 70px;
        width: 140px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        border: 2px solid white;
    }

    /* Action Icons */
    .action-icon {
        height: 32px;
        width: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9;
        transition: all 0.3s ease;
    }

    .action-icon:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }
    
    .action-icon img {
        height: 18px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="glass-card">
            <div class="premium-header">
                <h3>Banners Management</h3>
                <a id="addBanner" href="javascript:void(0)" class="btn-gradient-primary">
                    <i class="fa fa-plus me-1"></i> Add Banner
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>URL Link</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bannerTableBody">
                            @forelse ($banners as $index => $banner)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img class="thumbnail-img" src="{{ asset('adminDash/uploads/slider&banner/' . $banner->image) }}" alt="Banner">
                                    </td>
                                    <td>
                                        <a href="{{ $banner->url }}" target="_blank" class="text-primary text-decoration-none">
                                            {{ $banner->url ?: 'No Link' }}
                                        </a>
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input class="status-switch" type="checkbox" data-id="{{ $banner->id }}" {{ $banner->status == '1' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a class="action-icon mr-2" href="{{ route('banner.edit', $banner->id) }}">
                                                <img src="{{ asset('adminDash/assets/img/layouts/edit.png') }}" alt="Edit">
                                            </a>
                                            <a class="action-icon delete-btn" href="{{ route('banner.destroy', $banner->id) }}" onclick="return confirm('Are you sure you want to delete this banner?');">
                                                <img src="{{ asset('adminDash/assets/img/layouts/delete.png') }}" alt="Delete">
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No Banners Found. Click "Add Banner" to create one.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Banner Modal -->
<div id="bannerModal" class="modal modal-glass">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <h3 class="mb-4 fw-bold">Add New Banner</h3>
            <form id="addBannerForm">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold">Banner Image <span class="text-danger">*</span> <small class="text-muted">(Max-Height: 100px)</small></label>
                    <div class="image-upload-box" onclick="document.getElementById('image-input').click()">
                        <i class="fa fa-cloud-upload-alt"></i>
                    </div>
                    <input type="file" id="image-input" class="d-none" name="image" accept="image/*" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-semibold">Target URL</label>
                    <input type="url" class="form-control form-control-lg" name="url" placeholder="https://example.com" style="border-radius: 12px;">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light px-4 py-2" id="cancelBtn" style="border-radius: 50px; font-weight: 600;">Cancel</button>
                    <button type="submit" class="btn btn-gradient-primary submit-btn ml-2">Save Banner</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: 'rgba(255, 255, 255, 0.95)',
        customClass: { popup: 'glass-card' }
    });

    // Status Switcher
    document.querySelectorAll('.status-switch').forEach(function(btn) {
        btn.addEventListener('change', function() {
            let id = this.getAttribute('data-id');
            let status = this.checked ? 1 : 0;

            fetch("{{ route('banner.status') }}", {
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
                    Toast.fire({ icon: 'success', title: status == 1 ? 'Banner Activated' : 'Banner Deactivated' });
                } else {
                    Toast.fire({ icon: 'error', title: 'Action failed' });
                }
            })
            .catch(err => Toast.fire({ icon: 'error', title: 'Server Error' }));
        });
    });

    // Modal Logic
    const bannerModal = document.getElementById('bannerModal');
    const addBannerBtn = document.getElementById('addBanner');
    const cancelBtn = document.getElementById('cancelBtn');

    addBannerBtn.onclick = () => bannerModal.classList.add('show');
    cancelBtn.onclick = () => {
        bannerModal.classList.remove('show');
        document.getElementById('addBannerForm').reset();
        document.querySelector('.image-upload-box').innerHTML = '<i class="fa fa-cloud-upload-alt"></i>';
        document.querySelector('.image-upload-box').style.border = "2px dashed #cbd5e1";
    };
    window.onclick = (e) => { if (e.target == bannerModal) cancelBtn.onclick(); };

    // Form Submit
    document.getElementById('addBannerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let submitBtn = this.querySelector('.submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Saving...';

        fetch("{{ route('banner.store') }}", {
            method: "POST",
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                location.reload(); 
            } else {
                Toast.fire({ icon: 'error', title: 'Failed to save banner' });
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save Banner';
            }
        })
        .catch(err => {
            Toast.fire({ icon: 'error', title: 'Please provide a valid image' });
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Banner';
        });
    });

    // Image Preview
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const uploadBox = document.querySelector('.image-upload-box');
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                uploadBox.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 14px;">`;
                uploadBox.style.border = "none";
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<script>
    @if(session('success'))
    Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
    });
    @endif
</script>
@endsection
