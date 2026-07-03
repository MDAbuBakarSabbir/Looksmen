@extends('layouts.Backend.master')
@section('title', 'ALL UPLOADS')

@section('content')

<style>
    .uploads-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .uploads-header h4 {
        font-weight: 700;
        font-size: 1.3rem;
        color: #2d3748;
        margin: 0;
    }
    .uploads-count-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 20px;
    }
    .uploads-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 18px;
    }
    .upload-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
    }
    .upload-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.13);
    }
    .upload-card:hover .upload-overlay {
        opacity: 1;
    }
    .upload-img-wrap {
        width: 100%;
        height: 150px;
        overflow: hidden;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .upload-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .upload-card:hover .upload-img-wrap img {
        transform: scale(1.06);
    }
    .upload-overlay {
        position: absolute;
        inset: 0;
        background: rgba(102, 126, 234, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    .overlay-btn {
        background: #fff;
        border: none;
        border-radius: 8px;
        padding: 7px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #5a4fcf;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }
    .overlay-btn:hover {
        background: #f0edff;
        color: #5a4fcf;
        text-decoration: none;
    }
    .upload-footer {
        padding: 8px 10px;
        border-top: 1px solid #f3f4f6;
    }
    .upload-filename {
        font-size: 0.72rem;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: monospace;
    }
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #9ca3af;
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 16px;
        display: block;
        color: #d1d5db;
    }
    .empty-state p {
        font-size: 1rem;
        margin: 0;
    }
    #copy-toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #1a202c;
        color: #fff;
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        z-index: 9999;
    }
    #copy-toast.show {
        opacity: 1;
    }
</style>

<div class="card shadow-sm" style="border-radius: 16px; border: none;">
    <div class="card-body" style="padding: 28px;">

        <div class="uploads-header">
            <h4><i class="fa-solid fa-images mr-2" style="color:#667eea;"></i> All Uploads</h4>
            <span class="uploads-count-badge">{{ count($images) }} {{ Str::plural('file', count($images)) }}</span>
        </div>

        @if(count($images) > 0)
            <div class="uploads-grid">
                @foreach($images as $image)
                    @php
                        $url = asset('uploads/' . $image);
                    @endphp
                    <div class="upload-card">
                        <div class="upload-img-wrap">
                            <img src="{{ $url }}" alt="{{ $image }}" loading="lazy">
                        </div>
                        <div class="upload-overlay">
                            <a href="{{ $url }}" target="_blank" class="overlay-btn">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                            <button class="overlay-btn" onclick="copyUrl('{{ $url }}')">
                                <i class="fa-solid fa-copy"></i> Copy
                            </button>
                        </div>
                        <div class="upload-footer">
                            <div class="upload-filename" title="{{ $image }}">{{ $image }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-folder-open"></i>
                <p>No images found in the uploads folder.</p>
            </div>
        @endif

    </div>
</div>

<div id="copy-toast">✓ URL copied to clipboard!</div>

<script>
    function copyUrl(url) {
        navigator.clipboard.writeText(url).then(function () {
            var toast = document.getElementById('copy-toast');
            toast.classList.add('show');
            setTimeout(function () { toast.classList.remove('show'); }, 2000);
        });
    }
</script>

@endsection