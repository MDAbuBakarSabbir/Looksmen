@extends('layouts.Backend.master')
@section('title')
    SLIDER
@endsection
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
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

    .image-upload-box {
        width: 100%;
        height: 200px;
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

    .thumbnail-img {
        height: 70px;
        width: 140px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        border: 2px solid white;
    }

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
                <h3>Sliders Management</h3>
                <a id="addSlider" href="javascript:void(0)" class="btn-gradient-primary">
                    <i class="fa fa-plus me-1"></i> Add Slider
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
                        <tbody id="sliderTableBody">
                            @forelse ($sliders as $index => $slider)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img class="thumbnail-img" src="{{ asset('uploads/' . $slider->image) }}" alt="Slider">
                                    </td>
                                    <td>
                                        <a href="{{ $slider->url }}" target="_blank" class="text-primary text-decoration-none">
                                            {{ $slider->url ?: 'No Link' }}
                                        </a>
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input class="status-switch" type="checkbox" data-id="{{ $slider->id }}" {{ $slider->status == '1' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a class="action-icon mr-2" href="{{ route('slider.edit', $slider->id) }}">
                                                <img src="{{ asset('adminDash/assets/img/layouts/edit.png') }}" alt="Edit">
                                            </a>
                                            <a class="action-icon delete-btn" href="{{ route('slider.destroy', $slider->id) }}" onclick="return confirm('Are you sure you want to delete this slider?');">
                                                <img src="{{ asset('adminDash/assets/img/layouts/delete.png') }}" alt="Delete">
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No Sliders Found. Click "Add Slider" to create one.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Slider Modal -->
<div id="sliderModal" class="modal modal-glass">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <h3 class="mb-4 fw-bold">Add New Slider</h3>
            <form id="addSliderForm">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold">Slider Image <span class="text-danger">*</span> <small class="text-muted">(1687x656 px)</small></label>
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
                    <button type="submit" class="btn btn-gradient-primary submit-btn ml-2">Save Slider</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // ১. মোডাল ওপেন করা (Add Slider Button Click)
    $('#addSlider').on('click', function() {
        $('#sliderModal').addClass('show');
    });

    // ২. মোডাল বন্ধ করা (Cancel Button Click)
    $('#cancelBtn').on('click', function() {
        closeModal();
    });

    // মোডালের বাইরে ক্লিক করলে বন্ধ হবে
    $(window).on('click', function(e) {
        if ($(e.target).is('#sliderModal')) {
            closeModal();
        }
    });

    function closeModal() {
        $('#sliderModal').removeClass('show');
        $('#addSliderForm')[0].reset(); // ফর্মের ডাটা ক্লিয়ার করবে
        $('.image-upload-box').html('<i class="fa fa-cloud-upload-alt"></i>'); // ইমেজ বক্স রিসেট
    }

    // ৩. ইমেজ সিলেক্ট করলে বক্সের ভেতর প্রিভিউ দেখানো
    $('#image-input').on('change', function() {
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('.image-upload-box').html(`<img src="${event.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 14px;">`);
            }
            reader.readAsDataURL(file);
        }
    });

    // ৪. রিলোড ছাড়া ফর্ম সাবমিশন (AJAX)
    $('#addSliderForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        let submitBtn = $('.submit-btn');
        
        // সাবমিট বাটন লোডিং স্টেট
        submitBtn.prop('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ route('slider.store') }}", // আপনার কন্ট্রোলারের স্টোর রাউটটি এখানে দিন
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                submitBtn.prop('disabled', false).text('Save Slider');

                if(response.success) {
                    // মোডাল বন্ধ করা
                    closeModal();

                    // টেবিলে যদি "No Sliders Found" লেখা থাকে তা রিমুভ করা
                    if ($('#sliderTableBody tr td').hasClass('text-center')) {
                        $('#sliderTableBody').empty();
                    }

                    // সিরিয়াল নাম্বার হিসাব করা
                    let nextIndex = $('#sliderTableBody tr').length + 1;
                    
                    // এডিট ও ডিলিট রাউট ডাইনামিক করা (জাভাস্ক্রিপ্ট ভ্যারিয়েবল ট্রিক)
                    let editUrl = "{{ route('slider.edit', ':id') }}".replace(':id', response.data.id);
                    let deleteUrl = "{{ route('slider.destroy', ':id') }}".replace(':id', response.data.id);
                    let assetUrl = "{{ asset('uploads') }}/" + response.data.image;
                    let editIcon = "{{ asset('adminDash/assets/img/layouts/edit.png') }}";
                    let deleteIcon = "{{ asset('adminDash/assets/img/layouts/delete.png') }}";

                    // নতুন স্লাইডারের ডাটা টেবিলে অ্যাপেন্ড (Append) করা
                    let newRow = `
                        <tr>
                            <td>${nextIndex}</td>
                            <td>
                                <img class="thumbnail-img" src="${assetUrl}" alt="Slider">
                            </td>
                            <td>
                                <a href="${response.data.url ? response.data.url : '#'}" target="_blank" class="text-primary text-decoration-none">
                                    ${response.data.url ? response.data.url : 'No Link'}
                                </a>
                            </td>
                            <td>
                                <label class="switch">
                                    <input class="status-switch" type="checkbox" data-id="${response.data.id}" ${response.data.status == '1' ? 'checked' : ''}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a class="action-icon mr-2" href="${editUrl}">
                                        <img src="${editIcon}" alt="Edit">
                                    </a>
                                    <a class="action-icon delete-btn" href="${deleteUrl}" onclick="return confirm('Are you sure you want to delete this slider?');">
                                        <img src="${deleteIcon}" alt="Delete">
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;

                    $('#sliderTableBody').append(newRow);

                    // SweetAlert সাকসেস নোটিফিকেশন
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Slider added successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).text('Save Slider');
                
                // ভ্যালিডেশন এরর মেসেজ হ্যান্ডেলিং
                let errors = xhr.responseJSON.errors;
                let errorMsg = 'Validation Error!';
                if(errors) {
                    errorMsg = Object.values(errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorMsg,
                });
            }
        });
    });
});
</script>
@endsection