@extends('layouts.Frontend.master')
@section('title')
    COMPARE
@endsection
@section('content')
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-lg-6 text-center text-lg-left">
                <h1 class="fw-600 h3">Product Compare</h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item opacity-50">
                        <a class="text-reset" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        <a class="text-reset" href="{{ route('ProductCompare') }}">Compare</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-light">
                <div class="fs-18 fw-700 text-dark">Comparison List</div>
                @if(count($products) > 0)
                    <a href="{{ route('compare.reset') }}" class="btn btn-outline-danger btn-sm fw-600 rounded-pill px-3 shadow-sm">
                        <i class="las la-trash-alt mr-1"></i> Reset Compare List
                    </a>
                @endif
            </div>

            <div class="p-4">
                @if(count($products) > 0)
                    <div class="table-responsive compare-table-container custom-scrollbar">
                        <table class="table table-bordered table-hover text-center align-middle m-0" style="min-width: 800px;">
                            <thead class="bg-soft-primary">
                                <tr>
                                    <th class="py-3 text-left w-25 fw-600 text-uppercase tracking-wider" style="min-width: 200px;">Product Features</th>
                                    @foreach($products as $product)
                                        <th class="py-3 px-2 w-25 position-relative" style="min-width: 250px;">
                                            <button type="button" class="btn btn-sm btn-icon btn-danger position-absolute shadow-sm" style="top: 10px; right: 10px; z-index: 2; border-radius: 50%;" onclick="removeFromCompare({{ $product->id }})" title="Remove">
                                                <i class="las la-times"></i>
                                            </button>
                                            <div class="mb-3 mt-3">
                                                <img src="{{ $product->firstImage ? asset('adminDash/uploads/products/' . $product->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}" alt="{{ $product->title }}" class="img-fluid rounded mx-auto d-block" style="height: 150px; object-fit: contain;">
                                            </div>
                                            <h5 class="fs-15 fw-600 text-dark text-truncate-2 px-3" style="line-height: 1.4; height: 42px;">{{ $product->title }}</h5>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Price -->
                                <tr>
                                    <td class="text-left fw-600 bg-light">Price</td>
                                    @foreach($products as $product)
                                        <td class="fs-16 fw-700 text-primary">
                                            ৳{{ $product->new_price }}
                                            @if($product->old_price > $product->new_price && $product->old_price > 0)
                                                <br><del class="fs-13 fw-500 text-muted">৳{{ $product->old_price }}</del>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                <!-- Add to Cart -->
                                <tr>
                                    <td class="text-left fw-600 bg-light">Action</td>
                                    @foreach($products as $product)
                                        <td>
                                            <a href="{{ route('ProductView', [$product->slug, $product->id]) }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm fw-600">View Product</a>
                                        </td>
                                    @endforeach
                                </tr>
                                <!-- Short Description -->
                                <tr>
                                    <td class="text-left fw-600 bg-light">Description</td>
                                    @foreach($products as $product)
                                        <td class="text-muted fs-13 text-left">
                                            {!! Str::limit(strip_tags($product->long_desc), 100) !!}
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="las la-balance-scale la-4x text-muted mb-3 opacity-50"></i>
                        <h4 class="fs-18 fw-600 text-dark mb-2">Your comparison list is empty</h4>
                        <p class="text-muted mb-4 fs-14">Add products to compare their features and prices.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-600">Browse Products</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .compare-table-container th, .compare-table-container td { vertical-align: middle; }
        .tracking-wider { letter-spacing: 1px; }
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        .btn-icon { width: 30px; height: 30px; line-height: 30px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
    </style>
@endsection

