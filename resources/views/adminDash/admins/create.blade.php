@extends('layouts.Backend.master')
@section('title')
    ADD ADMIN
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark mb-0">Add New Admin Employee</h3>
                    <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 20px;">
                        <i class="fa fa-arrow-left mr-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form id="createAdminForm" action="{{ route('admin.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label font-weight-bold text-muted">Admin Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter full name" required style="border-radius: 8px;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label font-weight-bold text-muted">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email address" required style="border-radius: 8px;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="number" class="form-label font-weight-bold text-muted">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number') }}" placeholder="Enter phone number" required style="border-radius: 8px;">
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="role_id" class="form-label font-weight-bold text-muted">Select Role <span class="text-danger">*</span></label>
                                <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required style="border-radius: 8px; height: auto; padding: 10px;">
                                    <option value="" disabled selected>Choose a role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->role }}" {{ old('role_id') == $role->role ? 'selected' : '' }}>
                                            {{ ucfirst($role->role) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label font-weight-bold text-muted">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimum 8 characters" required style="border-radius: 8px;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label font-weight-bold text-muted">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repeat password" required style="border-radius: 8px;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-2">
                            <a href="{{ route('admin.index') }}" class="btn btn-light px-4 mr-2" style="border-radius: 8px;">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);">
                                <i class="fa fa-check-circle mr-1"></i> Save Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
