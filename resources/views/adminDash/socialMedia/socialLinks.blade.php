@extends('layouts.AdminLays.master')
@section('title') SOCIAL MEDIA @endsection
@section('content')

<style>
    :root {
        --primary:  #6366f1;
        --primary2: #8b5cf6;
        --green:    #10b981;
        --red:      #f43f5e;
        --border:   rgba(229,231,235,0.8);
        --shadow:   0 4px 20px -4px rgba(0,0,0,0.06);
        --radius:   14px;
    }

    /* ===== Page Header ===== */
    .sm-hero {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 60%, #a855f7 100%);
        border-radius: var(--radius);
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between;
        position: relative; overflow: hidden;
    }
    .sm-hero::before {
        content:''; position:absolute; inset:0;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='0.04'%3E%3Cpath d='M20 20.5V18H0v5h5v5H0v5h20v-2.5zm-2 2.5H2v-3h16v3zM0 0h40v2H0zM0 4h40v2H0z'/%3E%3C/g%3E%3C/svg%3E");
    }
    .sm-hero-left { z-index: 1; }
    .sm-hero-icon { font-size: 26px; margin-bottom: 8px; }
    .sm-hero h1 { color:#fff; font-size:20px; font-weight:800; margin:0 0 4px; }
    .sm-hero p  { color:rgba(255,255,255,0.75); font-size:12px; margin:0; }
    .sm-add-btn {
        background: rgba(255,255,255,0.15) !important;
        border: 1.5px solid rgba(255,255,255,0.35) !important;
        color: #fff !important;
        border-radius: 11px !important;
        padding: 10px 20px !important;
        font-size: 13px !important; font-weight: 700;
        backdrop-filter: blur(6px);
        transition: all 0.2s ease;
        display: flex; align-items: center; gap: 7px;
        z-index: 1;
    }
    .sm-add-btn:hover {
        background: rgba(255,255,255,0.28) !important;
        transform: translateY(-1px);
    }

    /* ===== Social Cards Grid ===== */
    .social-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .social-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        position: relative;
    }
    .social-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 32px -8px rgba(0,0,0,0.12);
    }
    .social-card-accent {
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--primary2));
    }
    .social-card-body {
        padding: 18px 20px;
    }
    .social-card-header {
        display: flex; align-items: center; gap: 12px; margin-bottom: 14px;
    }
    .social-icon-badge {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; color: #fff;
        flex-shrink: 0;
    }
    .social-card-name { font-size: 14px; font-weight: 700; color: #111827; margin: 0; }
    .social-card-link { font-size: 11px; color: #9ca3af; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }

    .social-stats {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 8px; margin-bottom: 14px;
    }
    .stat-box {
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        border-radius: 10px;
        padding: 10px 12px;
        text-align: center;
    }
    .stat-box .stat-val { font-size: 15px; font-weight: 800; color: #111827; display: block; }
    .stat-box .stat-lbl { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.3px; }

    .social-card-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 20px;
        border-top: 1px solid #f3f4f6;
        background: #fafafa;
    }

    /* Switch */
    .switch { position:relative; display:inline-block; width:36px; height:20px; }
    .switch input { opacity:0; width:0; height:0; }
    .slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:#d1d5db; transition:.3s; }
    .slider:before { position:absolute; content:""; height:14px; width:14px; left:3px; bottom:3px; background:white; transition:.3s; }
    input:checked+.slider { background: var(--primary); }
    input:checked+.slider:before { transform: translateX(16px); }
    .slider.round { border-radius: 34px; }
    .slider.round:before { border-radius: 50%; }

    /* Action Buttons */
    .card-action-btn {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 13px; color: #6b7280;
        cursor: pointer; transition: all 0.2s ease;
        text-decoration: none;
    }
    .card-action-btn:hover { transform: scale(1.1); }
    .card-action-btn.edit-btn:hover  { color: var(--primary); border-color: var(--primary); background: #eff6ff; }
    .card-action-btn.delete-btn:hover { color: var(--red); border-color: var(--red); background: #fff1f2; }

    /* Empty State */
    .empty-state {
        text-align: center; padding: 60px 20px;
        background: #fff; border: 1px solid var(--border);
        border-radius: var(--radius); box-shadow: var(--shadow);
    }
    .empty-state .empty-icon { font-size: 48px; margin-bottom: 14px; opacity: 0.4; }
    .empty-state h4 { font-size: 16px; font-weight: 700; color: #374151; margin-bottom: 6px; }
    .empty-state p  { font-size: 13px; color: #9ca3af; margin: 0; }

    /* ===== Add Modal ===== */
    .sm-modal-overlay {
        display: none; position: fixed; inset: 0; z-index: 9999;
        background: rgba(0,0,0,0.45); backdrop-filter: blur(4px);
        align-items: center; justify-content: center;
    }
    .sm-modal-overlay.show { display: flex; animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity:0 } to { opacity:1 } }
    .sm-modal-box {
        background: #fff; border-radius: 18px;
        width: 520px; max-width: 95vw;
        box-shadow: 0 24px 60px rgba(0,0,0,0.18);
        animation: slideUp 0.3s ease;
        overflow: hidden;
    }
    @keyframes slideUp { from { transform: translateY(30px); opacity:0 } to { transform: translateY(0); opacity:1 } }
    .sm-modal-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        background: linear-gradient(to right, #f9fafb, #fff);
    }
    .sm-modal-header h4 { font-size: 16px; font-weight: 800; color: #111827; margin: 0; }
    .sm-modal-close {
        width: 32px; height: 32px; border-radius: 8px;
        background: #f3f4f6; border: none;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 14px; color: #6b7280;
        transition: all 0.2s;
    }
    .sm-modal-close:hover { background: #fee2e2; color: var(--red); }
    .sm-modal-body { padding: 22px 24px; }
    .sm-modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; gap: 10px; justify-content: flex-end; }

    /* Form */
    .sm-label { font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block; }
    .sm-input {
        width: 100%; background: #f9fafb; border: 1.5px solid #e5e7eb;
        border-radius: 10px; padding: 10px 14px;
        font-size: 13px; font-weight: 500; color: #111827;
        transition: all 0.2s ease; outline: none;
    }
    .sm-input:focus { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
    .sm-input-group { margin-bottom: 16px; }

    /* Icon Picker */
    .icon-picker-wrap {
        border: 1.5px solid #e5e7eb; border-radius: 10px;
        overflow: hidden; background: #f9fafb;
    }
    .icon-picker-search {
        width: 100%; padding: 10px 14px;
        border: none; border-bottom: 1px solid #e5e7eb;
        background: #fff; font-size: 13px; outline: none;
    }
    .icon-grid-inner {
        display: grid; grid-template-columns: repeat(8, 1fr);
        gap: 4px; padding: 10px; max-height: 150px; overflow-y: auto;
    }
    .icon-item-new {
        display: flex; align-items: center; justify-content: center;
        width: 100%; aspect-ratio: 1;
        border-radius: 8px; cursor: pointer;
        font-size: 16px; color: #6b7280;
        transition: all 0.15s ease;
    }
    .icon-item-new:hover { background: rgba(99,102,241,0.1); color: var(--primary); transform: scale(1.1); }
    .icon-item-new.selected { background: var(--primary); color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,0.35); }

    .selected-icon-preview {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px;
        background: #f0f9ff; border: 1.5px solid #bae6fd;
        border-radius: 10px; margin-bottom: 14px;
        font-size: 13px; color: #0369a1;
    }
    .selected-icon-preview i { font-size: 20px; }
    .selected-icon-preview span { font-weight: 600; }

    /* Buttons */
    .btn-save {
        background: linear-gradient(135deg, var(--primary), var(--primary2)) !important;
        border: none !important; border-radius: 10px !important;
        padding: 10px 22px !important; font-size: 13px !important;
        font-weight: 700; color: #fff !important;
        box-shadow: 0 4px 14px rgba(99,102,241,0.3) !important;
        transition: all 0.2s ease;
    }
    .btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(99,102,241,0.4) !important; }
    .btn-cancel {
        background: #f3f4f6 !important; border: none !important;
        border-radius: 10px !important; padding: 10px 18px !important;
        font-size: 13px !important; font-weight: 600; color: #6b7280 !important;
    }

    /* Platform color map */
    .bg-facebook   { background: linear-gradient(135deg, #1877f2, #0d65d9); }
    .bg-instagram  { background: linear-gradient(135deg, #e1306c, #f77737); }
    .bg-youtube    { background: linear-gradient(135deg, #ff0000, #cc0000); }
    .bg-twitter    { background: linear-gradient(135deg, #1da1f2, #0d8dd9); }
    .bg-linkedin   { background: linear-gradient(135deg, #0a66c2, #084d96); }
    .bg-tiktok     { background: linear-gradient(135deg, #010101, #69c9d0); }
    .bg-whatsapp   { background: linear-gradient(135deg, #25d366, #128c7e); }
    .bg-telegram   { background: linear-gradient(135deg, #2ca5e0, #1d84b5); }
    .bg-pinterest  { background: linear-gradient(135deg, #e60023, #c0001d); }
    .bg-discord    { background: linear-gradient(135deg, #5865f2, #4752c4); }
    .bg-github     { background: linear-gradient(135deg, #24292f, #57606a); }
    .bg-reddit     { background: linear-gradient(135deg, #ff4500, #cc3700); }
    .bg-snapchat   { background: linear-gradient(135deg, #fffc00, #f0eb00); }
    .bg-viber      { background: linear-gradient(135deg, #7360f2, #5846d6); }
    .bg-default    { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
</style>

{{-- ===== Hero Header ===== --}}
<div class="sm-hero">
    <div class="sm-hero-left">
        <div class="sm-hero-icon">🌐</div>
        <h1>Social Media Links</h1>
        <p>Manage your social media profiles displayed on the storefront</p>
    </div>
    <button id="openAddModal" class="btn sm-add-btn">
        <i class="fa-solid fa-plus"></i> Add Platform
    </button>
</div>

{{-- ===== Cards Grid ===== --}}
@if($socialLinks->count())
<div class="social-cards-grid" id="socialCardsGrid">
    @foreach ($socialLinks as $socialLink)
    @php
        $icon = $socialLink->social_icon ?? 'fa-globe';
        $platform = str_replace('fa-', '', $icon);
        $colorClass = match(true) {
            str_contains($icon, 'facebook')  => 'bg-facebook',
            str_contains($icon, 'instagram') => 'bg-instagram',
            str_contains($icon, 'youtube')   => 'bg-youtube',
            str_contains($icon, 'twitter') || str_contains($icon, 'x-twitter') => 'bg-twitter',
            str_contains($icon, 'linkedin')  => 'bg-linkedin',
            str_contains($icon, 'tiktok')    => 'bg-tiktok',
            str_contains($icon, 'whatsapp')  => 'bg-whatsapp',
            str_contains($icon, 'telegram')  => 'bg-telegram',
            str_contains($icon, 'pinterest') => 'bg-pinterest',
            str_contains($icon, 'discord')   => 'bg-discord',
            str_contains($icon, 'github')    => 'bg-github',
            str_contains($icon, 'reddit')    => 'bg-reddit',
            str_contains($icon, 'snapchat')  => 'bg-snapchat',
            str_contains($icon, 'viber')     => 'bg-viber',
            default                          => 'bg-default',
        };
    @endphp
    <div class="social-card" id="social-card-{{ $socialLink->id }}">
        <div class="social-card-accent"></div>
        <div class="social-card-body">
            <div class="social-card-header">
                <div class="social-icon-badge {{ $colorClass }}">
                    <i class="fa-brands {{ $icon }}"></i>
                </div>
                <div style="overflow:hidden;">
                    <p class="social-card-name">{{ ucfirst($platform) }}</p>
                    <a href="{{ $socialLink->social_link }}" target="_blank" class="social-card-link" title="{{ $socialLink->social_link }}">
                        {{ $socialLink->social_link }}
                    </a>
                </div>
            </div>
            <div class="social-stats">
                <div class="stat-box">
                    <span class="stat-val">{{ $socialLink->followers_count ?? '0' }}</span>
                    <span class="stat-lbl">Followers</span>
                </div>
                <div class="stat-box">
                    <span class="stat-val">{{ $socialLink->secondary_count ?? '0' }}</span>
                    <span class="stat-lbl">Likes / Posts</span>
                </div>
            </div>
        </div>
        <div class="social-card-footer">
            <div class="d-flex align-items-center gap-2">
                <label class="switch" style="margin:0;">
                    <input class="socialstatus-switch" type="checkbox"
                        data-id="{{ $socialLink->id }}"
                        {{ $socialLink->status == '1' ? 'checked' : '' }}>
                    <span class="slider round" title="Click to change status"></span>
                </label>
                <span style="font-size:11px; font-weight:600; color:#9ca3af;">
                    {{ $socialLink->status == '1' ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('social.edit') }}" class="card-action-btn edit-btn" title="Edit">
                    <i class="fa-regular fa-pen-to-square"></i>
                </a>
                <button class="card-action-btn delete-btn dataDeleteBtn"
                    data-id="{{ $socialLink->id }}" title="Delete">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="empty-state" id="emptyState">
    <div class="empty-icon">🔗</div>
    <h4>No Social Media Links Yet</h4>
    <p>Click "Add Platform" to add your first social media link.</p>
</div>
@endif

{{-- Hidden grid container for JS-added cards --}}
@if(!$socialLinks->count())
<div class="social-cards-grid" id="socialCardsGrid" style="display:none;"></div>
@endif

{{-- ===== Add Social Media Modal ===== --}}
<div class="sm-modal-overlay" id="addSocialModal">
    <div class="sm-modal-box">
        <div class="sm-modal-header">
            <div class="d-flex align-items-center gap-3">
                <div style="width:36px;height:36px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;">🌐</div>
                <h4>Add Social Platform</h4>
            </div>
            <button class="sm-modal-close" id="closeAddModal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="sm-modal-body">
            <form id="social-form" action="{{ route('social.store') }}" method="POST">
                @csrf

                {{-- Icon Picker --}}
                <div class="sm-input-group">
                    <label class="sm-label">Social Media Icon</label>
                    <div class="selected-icon-preview" id="iconPreviewBox" style="display:none;">
                        <i class="fa-brands" id="iconPreviewEl"></i>
                        <span id="iconPreviewText">None selected</span>
                    </div>
                    <input type="hidden" id="selected-icon" name="social_icon" required>
                    <div class="icon-picker-wrap">
                        <input type="text" class="icon-picker-search" id="icon-search" placeholder="🔍  Search icon (e.g. facebook, instagram)...">
                        <div class="icon-grid-inner" id="icon-grid"></div>
                    </div>
                    <div style="font-size:11px;color:#9ca3af;margin-top:5px;">Click an icon to select it</div>
                </div>

                {{-- Link --}}
                <div class="sm-input-group">
                    <label class="sm-label">Social Media URL</label>
                    <input type="url" class="sm-input" name="social_link" placeholder="https://facebook.com/yourpage" required>
                </div>

                {{-- Counts --}}
                <div class="row g-3">
                    <div class="col-6">
                        <div class="sm-input-group" style="margin-bottom:0;">
                            <label class="sm-label">Followers / Subscribers</label>
                            <input type="text" class="sm-input" name="followers_count" placeholder="e.g. 15K" value="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="sm-input-group" style="margin-bottom:0;">
                            <label class="sm-label">Likes / Posts / Videos</label>
                            <input type="text" class="sm-input" name="secondary_count" placeholder="e.g. 240" value="0">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="sm-modal-footer">
            <button type="button" class="btn btn-cancel" id="cancelAddModal">Cancel</button>
            <button type="submit" form="social-form" class="btn btn-save" id="saveBtn">
                <i class="fa-solid fa-floppy-disk me-1"></i> Save Platform
            </button>
        </div>
    </div>
</div>

<script>
// ===== Platform color helper =====
function getPlatformColor(icon) {
    const map = {
        'fa-facebook':  'bg-facebook',
        'fa-instagram': 'bg-instagram',
        'fa-youtube':   'bg-youtube',
        'fa-twitter':   'bg-twitter',
        'fa-x-twitter': 'bg-twitter',
        'fa-linkedin':  'bg-linkedin',
        'fa-tiktok':    'bg-tiktok',
        'fa-whatsapp':  'bg-whatsapp',
        'fa-telegram':  'bg-telegram',
        'fa-pinterest': 'bg-pinterest',
        'fa-discord':   'bg-discord',
        'fa-github':    'bg-github',
        'fa-reddit':    'bg-reddit',
        'fa-snapchat':  'bg-snapchat',
        'fa-viber':     'bg-viber',
    };
    return map[icon] || 'bg-default';
}

// ===== Icon list =====
const allIcons = [
    "fa-facebook","fa-instagram","fa-youtube","fa-twitter","fa-x-twitter",
    "fa-linkedin","fa-tiktok","fa-whatsapp","fa-telegram","fa-pinterest",
    "fa-discord","fa-github","fa-reddit","fa-snapchat","fa-viber",
    "fa-google","fa-apple","fa-spotify","fa-twitch","fa-slack",
    "fa-behance","fa-dribbble","fa-medium","fa-tumblr","fa-vimeo",
];

const iconGrid    = document.getElementById('icon-grid');
const iconSearch  = document.getElementById('icon-search');
const hiddenInput = document.getElementById('selected-icon');

function renderIcons(list) {
    iconGrid.innerHTML = '';
    list.forEach(name => {
        const el = document.createElement('div');
        el.className = 'icon-item-new';
        el.innerHTML = `<i class="fa-brands ${name}"></i>`;
        el.title = name.replace('fa-','');
        el.addEventListener('click', () => selectIcon(name, el));
        iconGrid.appendChild(el);
    });
}

function selectIcon(name, el) {
    document.querySelectorAll('.icon-item-new').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    hiddenInput.value = name;
    const preview = document.getElementById('iconPreviewBox');
    const previewEl = document.getElementById('iconPreviewEl');
    const previewTxt = document.getElementById('iconPreviewText');
    preview.style.display = 'flex';
    previewEl.className = `fa-brands ${name}`;
    previewTxt.textContent = name.replace('fa-','').replace(/-/g,' ').replace(/\b\w/g,c=>c.toUpperCase());
}

iconSearch.addEventListener('input', function() {
    renderIcons(allIcons.filter(n => n.includes(this.value.toLowerCase())));
});

renderIcons(allIcons);

// ===== Modal open/close =====
const modal = document.getElementById('addSocialModal');
document.getElementById('openAddModal').addEventListener('click',  () => { modal.classList.add('show'); });
document.getElementById('closeAddModal').addEventListener('click', () => { modal.classList.remove('show'); });
document.getElementById('cancelAddModal').addEventListener('click',() => { modal.classList.remove('show'); });
modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('show'); });

// ===== Add Social Form Submit =====
document.getElementById('social-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...';

    fetch("{{ route('social.store') }}", {
        method: 'POST',
        body: new FormData(this),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(result => {
        if (result.success) {
            const sl = result.data;
            const colorClass = getPlatformColor(sl.social_icon);
            const platform = (sl.social_icon || 'fa-globe').replace('fa-', '');

            const cardHtml = `
            <div class="social-card" id="social-card-${sl.id}">
                <div class="social-card-accent"></div>
                <div class="social-card-body">
                    <div class="social-card-header">
                        <div class="social-icon-badge ${colorClass}">
                            <i class="fa-brands ${sl.social_icon}"></i>
                        </div>
                        <div style="overflow:hidden;">
                            <p class="social-card-name">${platform.charAt(0).toUpperCase()+platform.slice(1)}</p>
                            <a href="${sl.social_link}" target="_blank" class="social-card-link">${sl.social_link}</a>
                        </div>
                    </div>
                    <div class="social-stats">
                        <div class="stat-box">
                            <span class="stat-val">${sl.followers_count || '0'}</span>
                            <span class="stat-lbl">Followers</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-val">${sl.secondary_count || '0'}</span>
                            <span class="stat-lbl">Likes / Posts</span>
                        </div>
                    </div>
                </div>
                <div class="social-card-footer">
                    <div class="d-flex align-items-center gap-2">
                        <label class="switch" style="margin:0;">
                            <input class="socialstatus-switch" type="checkbox" data-id="${sl.id}" checked>
                            <span class="slider round"></span>
                        </label>
                        <span style="font-size:11px;font-weight:600;color:#9ca3af;">Active</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('social.edit') }}" class="card-action-btn edit-btn" title="Edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <button class="card-action-btn delete-btn dataDeleteBtn" data-id="${sl.id}" title="Delete">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>`;

            const grid = document.getElementById('socialCardsGrid');
            const empty = document.getElementById('emptyState');
            if (empty) empty.style.display = 'none';
            grid.style.display = 'grid';
            grid.insertAdjacentHTML('afterbegin', cardHtml);

            // Bind new delete btn
            const newCard = grid.querySelector(`#social-card-${sl.id}`);
            bindDeleteBtn(newCard.querySelector('.dataDeleteBtn'));
            bindStatusSwitch(newCard.querySelector('.socialstatus-switch'));

            this.reset();
            document.getElementById('iconPreviewBox').style.display = 'none';
            document.querySelectorAll('.icon-item-new').forEach(i => i.classList.remove('selected'));
            modal.classList.remove('show');

            Toast?.fire({ icon: 'success', title: result.message || 'Added successfully!' });
        } else {
            Swal.fire('Error', result.message || 'Something went wrong', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Network error. Please try again.', 'error'))
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Save Platform'; });
});

// ===== Delete handler =====
function bindDeleteBtn(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id  = this.dataset.id;
        const url = `/admin/socialmedia/destroy/${id}`;
        Swal.fire({
            title: 'Delete this platform?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor:  '#9ca3af',
            confirmButtonText:  'Yes, Delete!',
        }).then(r => {
            if (r.isConfirmed) {
                fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`social-card-${id}`)?.remove();
                        Toast?.fire({ icon: 'success', title: 'Deleted successfully!' });
                        // Show empty state if grid is empty
                        const grid = document.getElementById('socialCardsGrid');
                        if (!grid.children.length) {
                            grid.style.display = 'none';
                            document.getElementById('emptyState') && (document.getElementById('emptyState').style.display = '');
                        }
                    } else {
                        Swal.fire('Error', data.message || 'Could not delete.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Network error.', 'error'));
            }
        });
    });
}

// ===== Status switch handler =====
function bindStatusSwitch(sw) {
    sw.addEventListener('change', function() {
        const id     = this.getAttribute('data-id');
        const status = this.checked ? 1 : 0;
        const label  = this.closest('.social-card-footer').querySelector('span');
        fetch("{{ route('social.status') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ id, status })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                label.textContent = status ? 'Active' : 'Inactive';
                Toast?.fire({ icon: 'success', title: status ? 'Activated' : 'Deactivated' });
            } else {
                this.checked = !this.checked;
                Toast?.fire({ icon: 'error', title: 'Something went wrong' });
            }
        })
        .catch(() => { this.checked = !this.checked; });
    });
}

// Bind to existing elements
document.querySelectorAll('.dataDeleteBtn').forEach(bindDeleteBtn);
document.querySelectorAll('.socialstatus-switch').forEach(bindStatusSwitch);
</script>

@endsection
@section('script')
@endsection
