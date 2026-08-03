@extends('layouts.Frontend.master')
@section('title')
    MANAGE PROFILE
@endsection
@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --dash-primary: #4f46e5;
        --dash-primary-glow: rgba(79, 70, 229, 0.3);
        --dash-bg: #f3f4f6;
        --dash-surface: #ffffff;
        --dash-border: #e2e8f0;
        --dash-text: #0f172a;
        --dash-muted: #64748b;
    }

    .dash-section {
        background-color: var(--dash-bg);
        font-family: 'Outfit', sans-serif !important;
        min-height: calc(100vh - 150px);
        padding: 40px 0;
    }

    .dash-card {
        background: var(--dash-surface);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        border: 1px solid var(--dash-border);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .dash-card-header {
        padding: 22px 28px;
        border-bottom: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .dash-card-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        flex-shrink: 0;
    }

    .icon-bg-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
    .icon-bg-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .icon-bg-danger  { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

    .dash-card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--dash-text);
    }

    .dash-card-header p {
        margin: 2px 0 0;
        font-size: 0.85rem;
        color: var(--dash-muted);
    }

    .form-control-premium {
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        padding: 12px 16px;
        font-size: 1rem;
        color: var(--dash-text);
        background: #f8fafc;
        transition: all 0.25s;
        width: 100%;
    }

    .form-control-premium:focus {
        background: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .form-label-premium {
        font-weight: 600;
        font-size: 0.9rem;
        color: #334155;
        margin-bottom: 8px;
        display: block;
    }

    .btn-save {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        color: white;
    }

    .btn-danger-outline {
        background: transparent;
        color: #dc2626;
        border: 1.5px solid #fca5a5;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .btn-danger-outline:hover {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
    }

    .success-badge {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .alert-danger-soft {
        background: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 12px;
        padding: 14px 16px;
        color: #dc2626;
        font-size: 0.9rem;
        margin-top: 6px;
    }
    .profile-avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-avatar-img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .profile-avatar-edit-btn {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
        border: 3px solid #ffffff;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 5;
    }

    .profile-avatar-edit-btn:hover {
        transform: scale(1.12);
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.6);
        color: #ffffff !important;
    }
</style>

<section class="dash-section">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4 mb-4">
                @include('Frontend.dashboard.partials.usersideNav')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">

                <!-- ===================== -->
                <!-- Update Profile Info   -->
                <!-- ===================== -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <div class="dash-card-header-icon icon-bg-primary">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <h5>Profile Information</h5>
                            <p>Update your profile photo, name and email address</p>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <!-- Circular Avatar Upload Preview -->
                            <div class="text-center mb-4">
                                <div class="profile-avatar-wrapper">
                                    @php
                                        $userAvatar = $user->profile_pic && file_exists(public_path('Uploads/' . $user->profile_pic))
                                            ? asset('Uploads/' . $user->profile_pic)
                                            : asset('frontend/assets/img/avatar-place.png');
                                    @endphp
                                    <img src="{{ $userAvatar }}" 
                                         id="profile_avatar_preview" 
                                         alt="{{ $user->name }}" 
                                         class="profile-avatar-img">
                                    
                                    <label for="profile_pic_input" class="profile-avatar-edit-btn" title="Update Profile Picture">
                                        <i class="fa-solid fa-camera"></i>
                                    </label>
                                    
                                    <input type="file" name="profile_pic" id="profile_pic_input" accept="image/*" style="display: none;">
                                </div>
                                <div class="mt-2 text-muted small">Click camera icon to select a new profile picture</div>
                                @error('profile_pic')
                                    <div class="alert-danger-soft d-inline-block mt-2"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label-premium">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control-premium" value="{{ old('name', $user->name) }}" required autofocus>
                                    @error('name')
                                        <div class="alert-danger-soft"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label-premium">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control-premium" value="{{ old('email', $user->email) }}" required autocomplete="username">
                                    @error('email')
                                        <div class="alert-danger-soft"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $message }}</div>
                                    @enderror

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div class="alert alert-warning border-0 rounded-3 mt-2 p-3 small">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                            Your email address is unverified.
                                            <button form="send-verification" class="btn btn-link btn-sm p-0 ml-1">Click here to re-send the verification email.</button>
                                        </div>
                                        @if (session('status') === 'verification-link-sent')
                                            <div class="success-badge mt-2"><i class="fa-solid fa-check-circle"></i>A verification link has been sent to your email.</div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3" style="gap: 15px;">
                                <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk mr-2"></i>Save Changes</button>
                                @if (session('status') === 'profile-updated')
                                    <span class="success-badge"><i class="fa-solid fa-check-circle"></i>Saved successfully!</span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ===================== -->
                <!-- Update Password       -->
                <!-- ===================== -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <div class="dash-card-header-icon icon-bg-warning">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div>
                            <h5>Update Password</h5>
                            <p>Use a long, random password to stay secure</p>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label class="form-label-premium">Current Password</label>
                                    <input id="update_password_current_password" name="current_password" type="password" class="form-control-premium" autocomplete="current-password">
                                    @error('current_password', 'updatePassword')
                                        <div class="alert-danger-soft"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label-premium">New Password</label>
                                    <input id="update_password_password" name="password" type="password" class="form-control-premium" autocomplete="new-password">
                                    @error('password', 'updatePassword')
                                        <div class="alert-danger-soft"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label-premium">Confirm New Password</label>
                                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control-premium" autocomplete="new-password">
                                    @error('password_confirmation', 'updatePassword')
                                        <div class="alert-danger-soft"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex align-items-center" style="gap: 15px;">
                                <button type="submit" class="btn-save"><i class="fa-solid fa-key mr-2"></i>Update Password</button>
                                @if (session('status') === 'password-updated')
                                    <span class="success-badge"><i class="fa-solid fa-check-circle"></i>Password updated!</span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ===================== -->
                <!-- Delete Account        -->
                <!-- ===================== -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <div class="dash-card-header-icon icon-bg-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h5>Delete Account</h5>
                            <p>Permanently delete your account and all data</p>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="p-4 bg-light rounded-3 mb-4" style="border: 1px solid #fee2e2;">
                            <p class="text-muted mb-0 small">
                                <i class="fa-solid fa-circle-info mr-2 text-danger"></i>
                                Once your account is deleted, all of its resources and data will be permanently erased. Before deleting your account, please download any data or information that you wish to retain.
                            </p>
                        </div>

                        <button type="button" class="btn-danger-outline" data-toggle="modal" data-target="#deleteAccountModal">
                            <i class="fa-solid fa-trash-can mr-2"></i>Delete Account
                        </button>

                        <!-- Delete Account Modal -->
                        <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
                                    <div class="modal-header" style="background: #fef2f2; border-bottom: 1px solid #fee2e2; padding: 20px 24px;">
                                        <h5 class="modal-title font-weight-bold text-danger" id="deleteAccountModalLabel">
                                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>Delete Account
                                        </h5>
                                        <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <p class="text-muted mb-4">Are you sure you want to delete your account? This action <strong class="text-danger">cannot be undone</strong>. Please enter your password to confirm.</p>
                                        <form method="post" action="{{ route('profile.destroy') }}">
                                            @csrf
                                            @method('delete')
                                            <div class="form-group">
                                                <label class="form-label-premium">Your Password</label>
                                                <input type="password" name="password" class="form-control-premium" placeholder="Enter current password to confirm" required>
                                                @error('password', 'userDeletion')
                                                    <div class="alert-danger-soft mt-2"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="d-flex justify-content-end mt-4" style="gap: 10px;">
                                                <button type="button" class="btn btn-secondary rounded-3" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger rounded-3 font-weight-bold">
                                                    <i class="fa-solid fa-trash-can mr-2"></i>Permanently Delete
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profilePicInput = document.getElementById('profile_pic_input');
        const profileAvatarPreview = document.getElementById('profile_avatar_preview');

        if (profilePicInput && profileAvatarPreview) {
            profilePicInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        profileAvatarPreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endsection
