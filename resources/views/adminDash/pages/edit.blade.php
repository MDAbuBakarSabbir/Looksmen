@extends('layouts.Backend.master')
@section('title')
    EDIT CUSTOM PAGE
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-pen-to-square text-primary mr-2"></i>Edit Page: {{ $pageData->page_name }}</h4>
                    <a class="btn btn-secondary btn-sm" href="{{ route('pages.index') }}" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;"><i class="fa-solid fa-arrow-left mr-2"></i>Back</a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('pages.update', $pageData->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 13px;">Page Name<span class="text-danger">*</span></label>
                            <input type="text" name="page_name" class="form-control" placeholder="Enter Page Name" value="{{ $pageData->page_name }}" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 13px;">Page Description (English)<span class="text-danger">*</span></label>
                            <textarea name="english_description" class="form-control" rows="5" placeholder="Enter Page Content in English" required style="border-radius: 8px; padding: 10px 14px;">{{ $pageData->english_description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 13px;">Page Description (বাংলা )</label>
                            <textarea name="bangla_description" class="form-control" rows="5" placeholder="Enter Page Content in Bangla" style="border-radius: 8px; padding: 10px 14px;">{{ $pageData->bangla_description }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 600; padding: 12px; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Update Page
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
