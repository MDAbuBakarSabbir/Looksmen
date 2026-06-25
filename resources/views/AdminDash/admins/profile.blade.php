@extends('layouts.adminLays.master')
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
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white" style="width: 100px; height: 100px; font-size: 40px; font-weight: 700; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
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
                    <form action="{{ route('admin.profile.update') }}" method="POST" class="settingsUpdateForm">
                        @csrf
                        <div class="row">
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
@endsection
