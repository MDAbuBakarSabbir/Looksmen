<div class="p-3">
    @if(isset($categories) && count($categories) > 0)
        <div class="mb-2 pb-2 border-bottom">
            <div class="fs-11 text-muted text-uppercase fw-600 mb-2">Categories</div>
            <div class="d-flex flex-wrap gap-1">
                @foreach($categories as $cat)
                    <a href="{{ route('catProductView', [$cat->id, $cat->slug]) }}" class="badge badge-soft-primary px-2 py-1 fs-12 fw-500 rounded">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="fs-11 text-muted text-uppercase fw-600 mb-2">Products</div>
    <ul class="list-group list-group-flush mb-0">
        @foreach($products as $product)
            @php
                $imgUrl = $product->firstImage ? asset('Uploads/' . $product->firstImage->image) : asset('frontEnd/assets/img/placeholder.jpg');
            @endphp
            <li class="list-group-item px-0 py-2 border-0">
                <a href="{{ route('ProductView', [$product->id, $product->slug]) }}" class="d-flex align-items-center text-reset text-decoration-none">
                    <img src="{{ $imgUrl }}" 
                         alt="{{ $product->title }}" 
                         class="size-40px rounded object-fit-cover mr-3 border"
                         onerror="this.onerror=null;this.src='{{ asset('frontEnd/assets/img/placeholder.jpg') }}';">
                    <div class="flex-grow-1 minw-0">
                        <div class="text-truncate fs-13 fw-600 text-dark">{{ $product->title }}</div>
                        <div class="fs-12 text-primary fw-700">
                            ৳{{ number_format($product->new_price, 0) }}
                            @if($product->old_price > $product->new_price)
                                <del class="fs-11 text-muted ml-1 fw-400">৳{{ number_format($product->old_price, 0) }}</del>
                            @endif
                        </div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>

    @if(isset($totalCount) && $totalCount > count($products))
        <div class="mt-2 pt-2 border-top text-center">
            <a href="{{ route('front.search', ['keyword' => $keyword]) }}" class="btn btn-sm btn-soft-primary btn-block fw-600 fs-13">
                See all {{ $totalCount }} results
            </a>
        </div>
    @endif
</div>
