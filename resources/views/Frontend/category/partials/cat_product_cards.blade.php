@forelse ($catProducts as $catProduct)
    <div class="col mb-4">
        <div class="premium-product-card">
            @if($catProduct->discount_percentage)
                <span class="badge-discount">-{{ $catProduct->discount_percentage }}%</span>
            @endif
            <div class="position-relative overflow-hidden">
                <a href="{{ route('ProductView', [$catProduct->id, $catProduct->slug]) }}" class="d-block text-center pt-3">
                    <img class="img-fit lazyload mx-auto h-160px h-md-210px"
                        src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                        data-src="{{ $catProduct->firstImage ? asset('Uploads/' . $catProduct->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
                        alt="{{ $catProduct->title }}"
                        onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder.jpg';">
                </a>
                <div class="absolute-top-right mt-2 mr-2 z-3">
                    <a href="javascript:void(0)" onclick="addToWishList({{ $catProduct->id }})" class="action-icon-btn" data-toggle="tooltip" data-title="Add to wishlist">
                        <i class="la la-heart-o fs-18"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="addToCompare({{ $catProduct->id }})" class="action-icon-btn" data-toggle="tooltip" data-title="Add to compare">
                        <i class="las la-sync fs-18"></i>
                    </a>
                </div>
            </div>
            <div class="p-3 text-left d-flex flex-column flex-grow-1">
                <div class="rating rating-sm mb-1">
                    @php
                        $avg = $catProduct->getAverageRating() ?? 0;
                        $fullStars = floor($avg);
                        $fraction = $avg - $fullStars;
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $fullStars)
                            <i class="las la-star text-warning"></i>
                        @elseif ($i == $fullStars + 1 && $fraction >= 0.5)
                            <i class="las la-star-half-alt text-warning"></i>
                        @else
                            <i class="las la-star text-secondary opacity-30"></i>
                        @endif
                    @endfor
                </div>
                <h3 class="fw-600 fs-14 text-truncate-2 lh-1-4 mb-2 flex-grow-1">
                    <a href="{{ route('ProductView', [$catProduct->id, $catProduct->slug]) }}" class="product-title-link">{{ $catProduct->title }}</a>
                </h3>
                <div class="fs-15 mb-3 d-flex align-items-center">
                    <span class="price-new mr-2">&#2547;{{ $catProduct->new_price }}</span>
                    @if($catProduct->old_price > $catProduct->new_price)
                        <del class="price-old">&#2547;{{ $catProduct->old_price }}</del>
                    @endif
                </div>
                <button type="button" class="btn btn-primary w-100 action-add-to-cart" data-id="{{ $catProduct->id }}" data-type="product">
                    <i class="las la-shopping-cart mr-1"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="premium-card p-5 text-center w-100">
            <i class="las la-frown la-4x text-muted mb-3"></i>
            <h3 class="h5 fw-600 text-dark">No products found</h3>
            <p class="text-muted mb-0">Try checking out our other categories!</p>
        </div>
    </div>
@endforelse
