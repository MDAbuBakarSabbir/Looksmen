@php
    use App\Models\SubCategory;
    use App\Models\ChildCategory;

@endphp

@extends('layouts.Frontend.master')
@section('title')
    ALL CATEGORY
@endsection
@section('content')
    <section class="pt-4 mb-4">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-600 h4">All categories</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item opacity-50">
                            <a class="text-reset" href="{{url('/')}}">Home</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            <a class="text-reset" href="{{route('front.allCategory')}}">"All categories"</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="container">
            @foreach ($allCategories as $category)
                <div class="mb-3 bg-white shadow-sm rounded">
                    <div class="p-3 border-bottom fs-16 fw-600">
                        <a href="category/menclothing.html" class="text-reset">{{ $category->name }}</a>
                    </div>
                    <div class="p-3 p-lg-4">
                        <div class="row">
                            @foreach ($category->subcategories as $subCat)
                                <div class="col-lg-4 col-6 text-left">
                                    <h6 class="mb-3">
                                        <a class="text-reset fw-600 fs-14" href="">{{ $subCat->name }}</a>
                                    </h6>
                                    @foreach ($subCat->childcategories as $childCat)
                                        <ul class="mb-3 list-unstyled pl-2">
                                            <li class="mb-2">
                                                <a class="text-reset"
                                                    href="category/mens-shirt-rabea.html">{{ $childCat->name }}</a>
                                            </li>
                                        </ul>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

