@extends('layouts.Backend.master')
@section('title')
    ADMIN PROFILE
@endsection
@section('content')
    <div class="row">
        <!-- Profile Card -->
        <div class="col-xl-4 col-xxl-4 col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="profile-photo mt-3">
                        <div class="position-relative d-inline-block">
                            @if ($admin->profile_pic)
                                <img src="{{ asset('Uploads/' . $admin->profile_pic) }}" id="profile-pic-preview" class="rounded-circle img-fluid" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);">
                            @else
                                <div id="profile-initials-preview" class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white" style="width: 120px; height: 120px; font-size: 48px; font-weight: 700; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <h3 class="mt-4 mb-1">{{ $admin->name }}</h3>
                    <p class="text-muted text-capitalize mb-3">{{ $admin->role_id ?? 'Administrator' }}</p>
                    <ul class="list-group list-group-flush text-left">
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <span class="mb-0 text-muted">Email:</span>
                            <strong>{{ $admin->email }}</strong>
                        </li>
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <span class="mb-0 text-muted">Phone:</span>
                            <strong>{{ $admin->number ?? 'N/A' }}</strong>
                        </li>
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <span class="mb-0 text-muted">Status:</span>
                            <span class="badge badge-success">Active</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Profile Settings Forms -->
        <div class="col-xl-8 col-xxl-8 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Profile Settings</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" class="settingsUpdateForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <label class="form-label font-weight-bold">Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="profile_pic_input" name="profile_pic" accept="image/*">
                                    <label class="custom-file-label" for="profile_pic_input">Choose profile picture...</label>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label font-weight-bold">Admin Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $admin->name }}" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label font-weight-bold">Phone Number</label>
                                <input type="text" class="form-control" name="number" value="{{ $admin->number }}" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label font-weight-bold">Email Address</label>
                                <input type="email" class="form-control" name="email" value="{{ $admin->email }}" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h4 class="mb-3 text-primary">Change Password (Leave blank to keep current)</h4>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label font-weight-bold">New Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Min 8 characters">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label font-weight-bold">Confirm New Password</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profilePicInput = document.getElementById('profile_pic_input');
            if (profilePicInput) {
                profilePicInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const label = this.nextElementSibling;
                        if (label) {
                            label.textContent = file.name;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            let previewImg = document.getElementById('profile-pic-preview');
                            if (previewImg) {
                                previewImg.src = event.target.result;
                            } else {
                                const initials = document.getElementById('profile-initials-preview');
                                if (initials) {
                                    initials.style.display = 'none';
                                    const img = document.createElement('img');
                                    img.id = 'profile-pic-preview';
                                    img.src = event.target.result;
                                    img.className = 'rounded-circle img-fluid';
                                    img.style.cssText = 'width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);';
                                    initials.parentNode.appendChild(img);
                                }
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Catch successful profile update ajax events to dynamically update header nav avatar
            $(document).ajaxSuccess(function(event, xhr, settings) {
                if (settings.url && settings.url.includes('profile/update')) {
                    const response = xhr.responseJSON;
                    if (response && response.success && response.profile_pic_url) {
                        // Update navbar avatar
                        const navbarAvatar = document.getElementById('navbar-profile-avatar');
                        if (navbarAvatar) {
                            navbarAvatar.src = response.profile_pic_url;
                        } else {
                            const navbarInitials = document.getElementById('navbar-profile-initials');
                            if (navbarInitials) {
                                navbarInitials.style.display = 'none';
                                const img = document.createElement('img');
                                img.id = 'navbar-profile-avatar';
                                img.src = response.profile_pic_url;
                                img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
                                navbarInitials.parentNode.appendChild(img);
                            }
                        }

                        // Update dropdown avatar
                        const dropdownAvatar = document.getElementById('dropdown-profile-avatar');
                        if (dropdownAvatar) {
                            dropdownAvatar.src = response.profile_pic_url;
                        } else {
                            const dropdownInitials = document.getElementById('dropdown-profile-initials');
                            if (dropdownInitials) {
                                dropdownInitials.style.display = 'none';
                                const img = document.createElement('img');
                                img.id = 'dropdown-profile-avatar';
                                img.src = response.profile_pic_url;
                                img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
                                dropdownInitials.parentNode.appendChild(img);
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
