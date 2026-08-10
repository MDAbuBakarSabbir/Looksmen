@extends('layouts.Frontend.master')

@section('title')
    Flash Sale
@endsection

@section('content')
<style>
    .flash-wrapper {
        font-family: 'Outfit', 'Inter', sans-serif;
    }
    .flash-banner {
        background: linear-gradient(135deg, #1e1b4b, #311042);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .flash-banner::before {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(236, 72, 153, 0.15) 0%, rgba(0,0,0,0) 70%);
        top: -100px;
        right: -50px;
    }
    .flash-banner::after {
        content: "";
        position: absolute;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.15) 0%, rgba(0,0,0,0) 70%);
        bottom: -50px;
        left: -50px;
    }
    .countdown-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        width: 60px;
        height: 60px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .countdown-num {
        font-size: 20px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.1;
    }
    .countdown-label {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        font-weight: 600;
    }
</style>

<div class="py-5 bg-light flash-wrapper">
    <div class="container">
        
        <!-- Flash Sale Banner -->
        <div class="flash-banner p-4 p-md-5 mb-5 shadow-sm text-white">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-left mb-4 mb-lg-0">
                    <span class="badge badge-inline bg-danger text-white px-3 py-1 font-weight-bold uppercase mb-3" style="border-radius: 30px; letter-spacing: 1px; font-size: 11px;">
                        <i class="las la-bolt mr-1 animate-pulse"></i> Limited Time Offer
                    </span>
                    <h1 class="h2 fw-800 text-white mb-2">⚡ SUPER FLASH SALE</h1>
                    <p class="text-white-50 m-0">Grab the hottest deals of the season before they are gone forever!</p>
                </div>
                <div class="col-lg-6 d-flex flex-column align-items-center align-items-lg-end">
                    <span class="text-white-50 font-weight-bold uppercase fs-12 mb-2" style="letter-spacing: 1px;">Offer Ends In:</span>
                    <div class="d-flex align-items-center gap-2" style="gap: 8px;">
                        <div class="countdown-box">
                            <span class="countdown-num" id="hours">00</span>
                            <span class="countdown-label">Hrs</span>
                        </div>
                        <div class="countdown-box">
                            <span class="countdown-num" id="minutes">00</span>
                            <span class="countdown-label">Min</span>
                        </div>
                        <div class="countdown-box">
                            <span class="countdown-num" id="seconds">00</span>
                            <span class="countdown-label">Sec</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row gutters-10 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2">
            @forelse ($products as $product)
                @if(is_object($product))
                <div class="col mb-4">
                    <div class="premium-product-card h-100 d-flex flex-column" style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s; position: relative;">
                        @if($product->discount_percentage)
                            <span class="badge-discount" style="position: absolute; top: 12px; left: 12px; background: #ef4444; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 30px; z-index: 10;">
                                -{{ $product->discount_percentage }}%
                            </span>
                        @endif
                        <div class="position-relative overflow-hidden text-center pt-3">
                            <a href="{{ route('ProductView', [$product->id, $product->slug]) }}" class="d-block">
                                <img class="img-fit lazyload mx-auto h-160px h-md-210px"
                                    src="{{ asset('frontend') }}/assets/img/placeholder.jpg"
                                    data-src="{{ $product->firstImage ? asset('Uploads/' . $product->firstImage->image) : asset('frontend/assets/img/placeholder.jpg') }}"
                                    alt="{{ $product->title }}"
                                    onerror="this.onerror=null;this.src='{{ asset('frontend') }}/assets/img/placeholder.jpg';"
                                    style="max-width: 90%; object-fit: contain;">
                            </a>
                            <div class="absolute-top-right mt-2 mr-2 z-3" style="position: absolute; top: 10px; right: 10px; display: flex; flex-direction: column; gap: 6px;">
                                <a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})" class="action-icon-btn" data-toggle="tooltip" data-title="Add to wishlist" style="background: rgba(255,255,255,0.9); border: 1px solid #e2e8f0; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #475569; transition: all 0.2s;">
                                    <i class="la la-heart-o fs-18"></i>
                                </a>
                                <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})" class="action-icon-btn" data-toggle="tooltip" data-title="Add to compare" style="background: rgba(255,255,255,0.9); border: 1px solid #e2e8f0; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #475569; transition: all 0.2s;">
                                    <i class="las la-sync fs-18"></i>
                                </a>
                            </div>
                        </div>
                        <div class="p-3 text-left d-flex flex-column flex-grow-1">
                            <div class="rating rating-sm mb-1">
                                @php
                                    $avg = $product->getAverageRating() ?? 0;
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
                                <a href="{{ route('ProductView', [$product->id, $product->slug]) }}" class="product-title-link" style="color: #1e293b; text-decoration: none; font-size: 14.5px; font-weight: 600;">{{ $product->title }}</a>
                            </h3>
                            <div class="fs-15 mb-3 d-flex align-items-center">
                                <span class="price-new mr-2" style="font-size: 17px; font-weight: 700; color: #4f46e5;">৳{{ $product->new_price }}</span>
                                @if($product->old_price > $product->new_price)
                                    <del class="price-old" style="font-size: 13px; color: #94a3b8; text-decoration: line-through;">৳{{ $product->old_price }}</del>
                                @endif
                            </div>
                            <button type="button" class="btn-gradient-primary w-100 action-add-to-cart" data-id="{{ $product->id }}" data-type="product" style="background: linear-gradient(135deg, #4f46e5, #6366f1); border: none; color: #fff; font-weight: 600; padding: 10px; border-radius: 8px; transition: opacity 0.2s;">
                                <i class="las la-shopping-cart mr-1"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            @empty
                <div class="col">
                    <div class="premium-card p-5 text-center w-100" style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <i class="las la-frown la-4x text-muted mb-3"></i>
                        <h3 class="h5 fw-600 text-dark">No Flash Sale products found</h3>
                        <p class="text-muted mb-0">Check back later for incredible discounts!</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->count() > 0)
            <div class="aiz-pagination aiz-pagination-center mt-5 mb-4">
                <nav class="d-flex justify-content-center">
                    {{ $products->links() }}
                </nav>
            </div>
        @endif

    </div>
</div>

<script>
    // Live countdown timer to end of day
    document.addEventListener('DOMContentLoaded', function() {
        const updateTimer = () => {
            const now = new Date();
            const tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
            const diff = tomorrow - now; // Time left in ms

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('hours').innerText = String(hours).padStart(2, '0');
            document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
            document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');
        };

        setInterval(updateTimer, 1000);
        updateTimer();
    });
</script>
@endsection
