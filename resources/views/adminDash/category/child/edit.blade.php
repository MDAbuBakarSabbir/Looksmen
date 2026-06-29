@extends('layouts.Backend.master')
@section('title')
    EDIT CHILD CATEGORY
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-pen-to-square mr-2 text-primary"></i>Edit Child Category: {{ $childcategory->name }}</h4>
                    <a class="btn btn-secondary btn-sm" href="{{ route('child-category.index') }}" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;"><i class="fa-solid fa-arrow-left mr-2"></i>Back</a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('child-category.update', $childcategory->id) }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Parent Main Category<span class="text-danger">*</span></label>
                            <select class="form-control form-select" name="category_id" required style="border-radius: 8px; height: auto; padding: 10px 14px;">
                                <option value="" disabled>Select Main Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $childcategory->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Parent Sub Category<span class="text-danger">*</span></label>
                            <select class="form-control form-select" name="subCategory_id" required style="border-radius: 8px; height: auto; padding: 10px 14px;">
                                <option value="" disabled>Select Sub Category</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" data-parent="{{ $subcategory->category_id }}" {{ $childcategory->subcategory_id == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Child Category Name<span class="text-danger">*</span></label>
                            <input type="text" name="childcategory_name" class="form-control" placeholder="Enter Child Category Name" value="{{ $childcategory->name }}" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-muted">Meta Title (Optional)</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="SEO Title" value="{{ $childcategory->meta_title }}" style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold text-muted">Meta Description (Optional)</label>
                            <textarea name="meta_description" class="form-control" rows="3" placeholder="SEO Description" style="border-radius: 8px; padding: 10px 14px;">{{ $childcategory->meta_descritption }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 600; padding: 12px; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Update Child Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dynamic subcategory filtering when main category is changed
        $('select[name="category_id"]').on('change', function() {
            var category_id = $(this).val();
            if (category_id) {
                $.ajax({
                    url: '/admin/get-subcategories/' + category_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        var $select = $('select[name="subCategory_id"]');
                        $select.empty();
                        $select.append('<option value="" selected disabled>Select Sub Category</option>');
                        $.each(data, function(key, value) {
                            $select.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            }
        });
    </script>
@endsection
