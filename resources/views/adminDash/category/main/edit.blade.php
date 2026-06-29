@extends('layouts.Backend.master')
@section('title')
    EDIT MAIN CATEGORY
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-pen-to-square mr-2 text-primary"></i>Edit Category: {{ $category->name }}</h4>
                    <a class="btn btn-secondary btn-sm" href="{{ route('category.index') }}" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;"><i class="fa-solid fa-arrow-left mr-2"></i>Back</a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Category Name<span class="text-danger">*</span></label>
                            <input type="text" name="category_name" class="form-control" placeholder="Enter Category Name" value="{{ $category->name }}" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Category Type<span class="text-danger">*</span></label>
                            <select class="form-control form-select" name="type" required style="border-radius: 8px; height: auto; padding: 10px 14px;">
                                <option value="physical" {{ $category->type == 'physical' ? 'selected' : '' }}>Physical</option>
                                <option value="digital" {{ $category->type == 'digital' ? 'selected' : '' }}>Digital</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Commission Rate (%)<span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="commission_rate" class="form-control" placeholder="Enter Commission Rate" value="{{ $category->commission_rate }}" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold text-muted">Category Icon Class<span class="text-danger">*</span></label>
                            <input type="text" name="icon" class="form-control" placeholder="e.g. fa-shirt" value="{{ $category->icon }}" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Category Image (Select only to update)</label>
                            <input type="file" name="image" class="form-control" style="border-radius: 8px; padding: 8px 12px; height: auto;">
                            @if ($category->banner)
                                <div class="mt-3">
                                    <label class="d-block font-weight-bold text-muted fs-12 uppercase">Current Banner Image:</label>
                                    <img src="{{ asset('adminDash/assets/img/category') }}/{{ $category->banner }}" alt="{{ $category->name }}" class="img-thumbnail" style="max-height: 120px; border-radius: 8px;">
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Meta Title (Optional)</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="SEO Title" value="{{ $category->meta_title }}" style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold text-muted">Meta Description (Optional)</label>
                            <textarea name="meta_description" class="form-control" rows="3" placeholder="SEO Description" style="border-radius: 8px; padding: 10px 14px;">{{ $category->meta_descritption }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 600; padding: 12px; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Update Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
