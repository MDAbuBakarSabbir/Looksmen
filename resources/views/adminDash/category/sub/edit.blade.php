@extends('layouts.Backend.master')
@section('title')
    EDIT SUB CATEGORY
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-pen-to-square mr-2 text-primary"></i>Edit Sub Category: {{ $subcategory->name }}</h4>
                    <a class="btn btn-secondary btn-sm" href="{{ route('sub-category.index') }}" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;"><i class="fa-solid fa-arrow-left mr-2"></i>Back</a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('sub-category.update', $subcategory->id) }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Parent Category<span class="text-danger">*</span></label>
                            <select class="form-control form-select" name="category_id" required style="border-radius: 8px; height: auto; padding: 10px 14px;">
                                <option value="" disabled>Select Main Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Sub Category Name<span class="text-danger">*</span></label>
                            <input type="text" name="subcategory_name" class="form-control" placeholder="Enter Sub Category Name" value="{{ $subcategory->name }}" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Meta Title (Optional)</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="SEO Title" value="{{ $subcategory->meta_title }}" style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold text-muted">Meta Description (Optional)</label>
                            <textarea name="meta_description" class="form-control" rows="3" placeholder="SEO Description" style="border-radius: 8px; padding: 10px 14px;">{{ $subcategory->meta_descritption }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 600; padding: 12px; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Update Sub Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
