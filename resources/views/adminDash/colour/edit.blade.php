@extends('layouts.Backend.master')
@section('title')
    EDIT COLOR
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-pen-to-square text-primary mr-2"></i>Edit Color: {{ $color->color_name }}</h4>
                    <a class="btn btn-secondary btn-sm" href="{{ route('color.index') }}" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;"><i class="fa-solid fa-arrow-left mr-2"></i>Back</a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('color.update', $color->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 13px;">Color Name<span class="text-danger">*</span></label>
                            <input type="text" name="color_name" class="form-control" placeholder="Enter Color Name" value="{{ $color->color_name }}" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 13px;">Color Code<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="color_code" id="colorCodeInput" class="form-control" placeholder="Enter Color Code with #" value="{{ $color->color_code }}" required style="border-radius: 8px 0 0 8px; padding: 10px 14px;">
                                <div class="input-group-append">
                                    <input type="color" id="colorPicker" class="form-control" value="{{ $color->color_code }}" style="width: 50px; height: 100%; border: 1px solid #d1d5db; border-left: none; padding: 2px; border-radius: 0 8px 8px 0; cursor: pointer;">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 600; padding: 12px; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Update Color
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sync color picker with hex input
        const colorPicker = document.getElementById('colorPicker');
        const colorInput = document.getElementById('colorCodeInput');
        if (colorPicker && colorInput) {
            colorPicker.addEventListener('input', function() {
                colorInput.value = this.value;
            });
            colorInput.addEventListener('input', function() {
                if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                    colorPicker.value = this.value;
                }
            });
        }
    </script>
@endsection
