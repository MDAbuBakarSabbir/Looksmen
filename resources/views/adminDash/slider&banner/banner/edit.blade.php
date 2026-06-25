@extends('layouts.adminLays.master')
@section('title')
    EDIT BANNER
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
        padding: 30px;
    }

    .premium-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding-bottom: 20px;
        margin-bottom: 25px;
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
        overflow: hidden;
    }

    .image-upload-box:hover {
        border-color: #6366f1;
        background-color: #eef2ff;
    }

    .image-upload-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-upload-box .overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .image-upload-box:hover .overlay {
        opacity: 1;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card">
            <div class="premium-header">
                <h3>Edit Banner</h3>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Update Image <small class="text-muted">(Optional)</small></label>
                        <div class="image-upload-box" onclick="document.getElementById('image-input').click()" style="border: none;">
                            <img src="{{ asset('adminDash/uploads/slider&banner/' . $banner->image) }}" alt="Current Image" id="preview-img">
                            <div class="overlay">
                                <i class="fa fa-camera me-2"></i> Click to change image
                            </div>
                        </div>
                        <input type="file" id="image-input" class="d-none" name="image" accept="image/*">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Target URL</label>
                        <input type="url" class="form-control form-control-lg" name="url" value="{{ $banner->url }}" placeholder="https://example.com" style="border-radius: 12px;">
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('banner.index') }}" class="btn btn-light px-4 py-2" style="border-radius: 50px; font-weight: 600;">Cancel</a>
                        <button type="submit" class="btn btn-gradient-primary ml-2">Update Banner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('preview-img').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
