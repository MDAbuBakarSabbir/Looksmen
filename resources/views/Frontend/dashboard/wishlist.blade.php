@extends('layouts.Frontend.master')
@section('title')
    WISHLIST
@endsection
@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">

        @include('Frontend.dashboard.partials.usersideNav')

        <div class="aiz-user-panel w-100">
            <div class="aiz-titlebar mt-2 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h3 fw-700" style="color: #1e293b; font-family: 'Outfit', sans-serif;">My Wishlist</h1>
                    </div>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 gutters-10">
                @forelse ($wishlists as $wishlist)
                    @if($wishlist->product)
                        <div class="col mb-4" id="wishlist-{{ $wishlist->product_id }}">
                            <div class="premium-product-card h-100 d-flex flex-column">
                                @if($wishlist->product->discount_percentage)
                                    <span class="badge-discount">-{{ $wishlist->product->discount_percentage }}%</span>
                                @endif
                                <div class="position-relative overflow-hidden">
                                    <a href="{{ route('ProductView', [$wishlist->product->id, $wishlist->product->slug]) }}" class="d-block text-center pt-3">
                                        <img class="img-fit lazyload mx-auto h-160px h-md-210px"
                                            src="{{ asset('frontEnd') }}/assets/img/placeholder.jpg"
                                            data-src="{{ $wishlist->product->firstImage ? asset('Uploads/' . $wishlist->product->firstImage->image) : asset('frontEnd/assets/img/placeholder.jpg') }}"
                                            alt="{{ $wishlist->product->title }}"
                                            onerror="this.onerror=null;this.src='{{ asset('frontEnd') }}/assets/img/placeholder.jpg';">
                                    </a>
                                    <div class="absolute-top-right mt-2 mr-2 z-3">
                                        <a href="javascript:void(0)" onclick="removeFromWishList({{ $wishlist->product_id }})" class="action-icon-btn" data-toggle="tooltip" data-title="Remove from wishlist" style="color: #ef4444; background: rgba(254, 226, 226, 0.9); border: 1px solid #fca5a5;">
                                            <i class="las la-trash fs-18"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="p-3 text-left d-flex flex-column flex-grow-1">
                                    @php
                                        $avg = $wishlist->product->getAverageRating() ?? 0;
                                    @endphp
                                    @if ($avg > 0)
                                        <div class="rating rating-sm mb-1">
                                            @php
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
                                    @endif
                                    <h3 class="fw-600 fs-14 text-truncate-2 lh-1-4 mb-2 flex-grow-1">
                                        <a href="{{ route('ProductView', [$wishlist->product->id, $wishlist->product->slug]) }}" class="product-title-link">{{ $wishlist->product->title }}</a>
                                    </h3>
                                    <div class="fs-15 mb-3 d-flex align-items-center">
                                        <span class="price-new mr-2">&#2547;{{ $wishlist->product->new_price }}</span>
                                        @if($wishlist->product->old_price > $wishlist->product->new_price)
                                            <del class="price-old">&#2547;{{ $wishlist->product->old_price }}</del>
                                        @endif
                                    </div>
                                    <div class="product-card-btn-group">
                                        <button type="button" class="btn btn-card-cart action-add-to-cart" data-toggle="tooltip" data-title="Add to cart" data-id="{{ $wishlist->product->id }}" data-type="product" aria-label="Add to Cart">
                                            <i class="las la-shopping-cart"></i>
                                        </button>
                                        <button type="button" class="btn btn-card-buy buy-now-btn" data-title="Buy Now" data-id="{{ $wishlist->product->id }}" data-type="product">
                                            <span>Buy Now</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-12 w-100">
                        <div class="p-5 text-center w-100 bg-white shadow-sm" style="border-radius: 12px; border: 1px solid #e2e8f0;">
                            <i class="las la-heart-broken text-muted mb-3" style="font-size: 60px; opacity: 0.5;"></i>
                            <h3 class="h4 fw-700 text-dark" style="font-family: 'Outfit', sans-serif;">Your wishlist is empty</h3>
                            <p class="text-muted mb-4 fs-15">Save items you love and buy them later.</p>
                            <a href="{{ url('/') }}" class="btn btn-primary px-4 fw-600 shadow-sm" style="border-radius: 30px;">Start Shopping</a>
                        </div>
                    </div>
                @endforelse
            </div>

            </div>
        </div>
    </section>
@endsection

@section('script')
<script>
    function removeFromWishList(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to remove this product from your wishlist?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route('wishlist.remove') }}', {
                    _token: '{{ csrf_token() }}',
                    product_id: id
                }, function(response) {
                    if (response.status === 'success') {
                        $('#wishlist-' + id).fadeOut(300, function() { $(this).remove(); });
                        AIZ.plugins.notify('success', response.message);
                    } else {
                        AIZ.plugins.notify('error', response.message);
                    }
                }).fail(function() {
                    AIZ.plugins.notify('error', 'Something went wrong');
                });
            }
        });
    }
</script>
@endsection
