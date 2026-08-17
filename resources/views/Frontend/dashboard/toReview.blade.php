@extends('layouts.Frontend.master')

@section('title')
    TO REVIEW - CUSTOMER REVIEWS
@endsection

@php
    if (!function_exists('getReviewProductImg')) {
        function getReviewProductImg($prod) {
            if (!$prod) {
                return asset('frontEnd/assets/img/placeholder.jpg');
            }
            
            // 1. Try firstImage relation
            if ($prod->firstImage && !empty($prod->firstImage->image)) {
                $file = $prod->firstImage->image;
                if (file_exists(public_path('Uploads/' . $file))) {
                    return asset('Uploads/' . $file);
                }
            }

            // 2. Try productImages relation
            if ($prod->productImages && $prod->productImages->count() > 0) {
                foreach ($prod->productImages as $pi) {
                    if (!empty($pi->image) && file_exists(public_path('Uploads/' . $pi->image))) {
                        return asset('Uploads/' . $pi->image);
                    }
                }
            }

            // 3. Try direct query fallback
            $fallbackImg = \App\Models\ProductImage::where('product_id', $prod->id)->first();
            if ($fallbackImg && !empty($fallbackImg->image) && file_exists(public_path('Uploads/' . $fallbackImg->image))) {
                return asset('Uploads/' . $fallbackImg->image);
            }

            return asset('frontEnd/assets/img/placeholder.jpg');
        }
    }
@endphp

@section('content')
    <style>
        .review-card-item {
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            background: #ffffff;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            padding: 18px 20px;
            margin-bottom: 16px;
        }

        .review-card-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }

        .review-pro-img {
            width: 72px;
            height: 72px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .review-nav-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.2s ease;
            background: #f8fafc;
            margin-right: 8px;
        }

        .review-nav-tabs .nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        /* Interactive Star Rating in Modal */
        .star-rating-box {
            display: inline-flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 6px;
            font-size: 32px;
            cursor: pointer;
        }

        .star-rating-box input {
            display: none;
        }

        .star-rating-box label {
            color: #cbd5e1;
            margin-bottom: 0;
            cursor: pointer;
            transition: color 0.15s ease, transform 0.15s ease;
        }

        .star-rating-box label:hover,
        .star-rating-box label:hover ~ label,
        .star-rating-box input:checked ~ label {
            color: #f59e0b;
        }

        .star-rating-box label:active {
            transform: scale(1.2);
        }

        .rating-feedback-badge {
            font-size: 13px;
            font-weight: 600;
            color: #f59e0b;
            min-height: 20px;
        }

        .review-text-bubble {
            background: #f8fafc;
            border-left: 3px solid #6366f1;
            border-radius: 8px;
            padding: 12px 16px;
            color: #334155;
            font-size: 14px;
            line-height: 1.5;
            margin-top: 10px;
        }
    </style>

    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">

                @include('Frontend.dashboard.partials.usersideNav')

                <div class="aiz-user-panel w-100">
                    <div class="aiz-titlebar mt-2 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="h3 fw-700" style="color: #1e293b; font-family: 'Outfit', sans-serif;">Product Reviews</h1>
                            </div>
                        </div>
                    </div>

                    <div class="row gutters-10">
                        <div class="col-lg-12">
                            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                                <div class="card-header bg-white d-flex align-items-center justify-content-between flex-wrap" style="border-bottom: 1px solid #e2e8f0; padding: 14px 20px;">
                                    <div class="d-flex align-items-center" style="gap: 10px;">
                                        <a href="{{ route('purchaseHistory') }}" class="btn btn-sm fw-600 rounded-pill px-4 py-2" style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; text-decoration: none;">
                                            <i class="las la-history mr-1"></i> Order History
                                        </a>
                                        <a href="{{ route('toReview') }}" class="btn btn-sm fw-600 rounded-pill px-4 py-2" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none;">
                                            <i class="las la-star-half-alt mr-1 text-warning"></i> To Review
                                            @if($toReviewItems->count() > 0)
                                                <span class="badge badge-danger ml-1" style="background: #ef4444; border-radius: 50px; font-size: 11px;">{{ $toReviewItems->count() }}</span>
                                            @endif
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                    <!-- Inner Tabs: Unsubmitted vs Reviewed -->
                                    <ul class="nav nav-tabs review-nav-tabs mb-4 border-0" id="reviewTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="to-review-tab" data-toggle="tab" href="#to-review" role="tab" aria-controls="to-review" aria-selected="true">
                                                <i class="las la-clock mr-1"></i> To Review ({{ $toReviewItems->count() }})
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="reviewed-tab" data-toggle="tab" href="#reviewed" role="tab" aria-controls="reviewed" aria-selected="false">
                                                <i class="las la-check-circle mr-1 text-success"></i> Reviewed History ({{ $reviewedItems->count() }})
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="reviewTabContent">
                                        <!-- Tab 1: To Review (Delivered items not reviewed yet) -->
                                        <div class="tab-pane fade show active" id="to-review" role="tabpanel" aria-labelledby="to-review-tab">
                                            @forelse($toReviewItems as $item)
                                                @php
                                                    $product = $item['product'];
                                                    $order = $item['order'];
                                                    $productImg = getReviewProductImg($product);
                                                    $productTitle = $product ? ($product->title ?? 'Product') : 'Product';
                                                @endphp
                                                <div class="review-card-item d-flex align-items-center justify-content-between flex-wrap" style="gap: 16px;" id="to-review-item-{{ $product->id }}">
                                                    <div class="d-flex align-items-center" style="gap: 16px;">
                                                        <img src="{{ $productImg }}" alt="{{ $productTitle }}" class="review-pro-img" onerror="this.onerror=null;this.src='{{ asset('frontEnd/assets/img/placeholder.jpg') }}';">
                                                        <div>
                                                            <h5 class="mb-1 font-weight-bold" style="font-size: 15px;">
                                                                <a href="{{ route('ProductView', [$product->id, $product->slug ?? 'product']) }}" class="text-dark" target="_blank">
                                                                    {{ $productTitle }}
                                                                </a>
                                                            </h5>
                                                            <div class="d-flex align-items-center flex-wrap" style="gap: 12px; font-size: 13px; color: #64748b;">
                                                                @if($order)
                                                                    <span><strong>Order:</strong> #{{ $order->id }}</span>
                                                                    <span>•</span>
                                                                    <span><strong>Delivered:</strong> {{ $order->updated_at ? $order->updated_at->format('d M, Y') : $order->created_at->format('d M, Y') }}</span>
                                                                    <span>•</span>
                                                                @endif
                                                                <span class="text-success font-weight-bold">&#2547;{{ $product->new_price }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <button type="button" class="btn btn-primary open-review-modal-btn font-weight-bold" 
                                                            style="border-radius: 8px; padding: 8px 18px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;"
                                                            data-product-id="{{ $product->id }}"
                                                            data-product-name="{{ $productTitle }}"
                                                            data-product-img="{{ $productImg }}"
                                                            data-order-id="{{ $order ? $order->id : '' }}"
                                                            data-mode="create">
                                                            <i class="las la-star mr-1"></i> Write a Review
                                                        </button>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-5">
                                                    <i class="las la-clipboard-check text-muted mb-3" style="font-size: 56px; opacity: 0.5;"></i>
                                                    <h4 class="h5 fw-600 text-dark" style="font-family: 'Outfit', sans-serif;">No products to review</h4>
                                                    <p class="text-muted mb-3 fs-14">You have reviewed all products from your delivered orders, or haven't received a delivered order yet.</p>
                                                    <a href="{{ route('home') }}" class="btn btn-sm btn-primary rounded-pill px-4 py-2 font-weight-bold">
                                                        <i class="las la-shopping-bag mr-1"></i> Continue Shopping
                                                    </a>
                                                </div>
                                            @endforelse
                                        </div>

                                        <!-- Tab 2: Reviewed History (Submitted reviews with edit option) -->
                                        <div class="tab-pane fade" id="reviewed" role="tabpanel" aria-labelledby="reviewed-tab">
                                            @forelse($reviewedItems as $item)
                                                @php
                                                    $product = $item['product'];
                                                    $review = $item['review'];
                                                    $order = $item['order'];
                                                    $productImg = getReviewProductImg($product);
                                                    $productTitle = $product ? ($product->title ?? 'Product') : 'Product';
                                                @endphp
                                                <div class="review-card-item" id="reviewed-item-{{ $review->id }}">
                                                    <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap: 16px;">
                                                        <div class="d-flex align-items-start" style="gap: 16px;">
                                                            <img src="{{ $productImg }}" alt="{{ $productTitle }}" class="review-pro-img" onerror="this.onerror=null;this.src='{{ asset('frontEnd/assets/img/placeholder.jpg') }}';">
                                                            <div>
                                                                <h5 class="mb-1 font-weight-bold" style="font-size: 15px;">
                                                                    @if($product)
                                                                        <a href="{{ route('ProductView', [$product->id, $product->slug ?? 'product']) }}" class="text-dark" target="_blank">
                                                                            {{ $productTitle }}
                                                                        </a>
                                                                    @else
                                                                        <span class="text-dark">Product</span>
                                                                    @endif
                                                                </h5>
                                                                
                                                                <!-- Star Rating Display -->
                                                                <div class="d-flex align-items-center mt-1" style="gap: 4px;">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        @if($i <= (int)$review->review_star)
                                                                            <i class="las la-star text-warning" style="font-size: 16px;"></i>
                                                                        @else
                                                                            <i class="lar la-star text-muted" style="font-size: 16px; opacity: 0.4;"></i>
                                                                        @endif
                                                                    @endfor
                                                                    <span class="ml-2 font-weight-bold text-dark" style="font-size: 13px;">{{ $review->review_star }}.0</span>
                                                                    <span class="text-muted mx-2">•</span>
                                                                    <span class="text-muted" style="font-size: 12px;">Reviewed on {{ $review->updated_at ? $review->updated_at->format('d M, Y') : $review->created_at->format('d M, Y') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <button type="button" class="btn btn-sm btn-outline-primary open-review-modal-btn font-weight-bold" 
                                                                style="border-radius: 8px; padding: 6px 16px;"
                                                                data-product-id="{{ $product ? $product->id : $review->product_id }}"
                                                                data-product-name="{{ $productTitle }}"
                                                                data-product-img="{{ $productImg }}"
                                                                data-order-id="{{ $order ? $order->id : '' }}"
                                                                data-review-id="{{ $review->id }}"
                                                                data-review-star="{{ $review->review_star }}"
                                                                data-review-desc="{{ $review->review_description }}"
                                                                data-mode="edit">
                                                                <i class="las la-edit mr-1"></i> Edit Review
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Review Description Bubble -->
                                                    <div class="review-text-bubble">
                                                        {{ $review->review_description }}
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-5">
                                                    <i class="lar la-star text-muted mb-3" style="font-size: 56px; opacity: 0.5;"></i>
                                                    <h4 class="h5 fw-600 text-dark" style="font-family: 'Outfit', sans-serif;">No submitted reviews yet</h4>
                                                    <p class="text-muted mb-0 fs-14">When you review your purchased products, they will be shown here.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Modal (Handles both Submit & Edit) -->
    <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden;">
                <div class="modal-header bg-light" style="border-bottom: 1px solid #e2e8f0; padding: 16px 24px;">
                    <h5 class="modal-title font-weight-bold" id="reviewModalLabel" style="font-size: 16px; color: #1e293b;">
                        <i class="las la-star text-warning mr-1"></i> <span id="modalTitleText">Write a Review</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form id="reviewForm">
                    @csrf
                    <input type="hidden" name="product_id" id="modal_product_id">
                    <input type="hidden" name="review_id" id="modal_review_id">
                    
                    <div class="modal-body p-4">
                        <!-- Product Preview Card -->
                        <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; gap: 14px;">
                            <img src="{{ asset('frontEnd/assets/img/placeholder.jpg') }}" id="modal_product_img" class="review-pro-img" style="width: 56px; height: 56px; object-fit: cover;" alt="Product" onerror="this.onerror=null;this.src='{{ asset('frontEnd/assets/img/placeholder.jpg') }}';">
                            <div>
                                <h6 class="mb-1 font-weight-bold text-dark" id="modal_product_title" style="font-size: 14px; line-height: 1.3;">Product Name</h6>
                                <span class="badge badge-info" id="modal_order_badge" style="font-size: 11px; background: #e0e7ff; color: #3730a3;">Order Verified</span>
                            </div>
                        </div>

                        <!-- Star Rating Selector -->
                        <div class="text-center mb-3">
                            <label class="font-weight-bold d-block mb-1 text-dark" style="font-size: 14px;">Your Overall Rating</label>
                            
                            <div class="star-rating-box my-1">
                                <input type="radio" name="review_star" id="star5" value="5" checked><label for="star5" title="5 stars" class="las la-star"></label>
                                <input type="radio" name="review_star" id="star4" value="4"><label for="star4" title="4 stars" class="las la-star"></label>
                                <input type="radio" name="review_star" id="star3" value="3"><label for="star3" title="3 stars" class="las la-star"></label>
                                <input type="radio" name="review_star" id="star2" value="2"><label for="star2" title="2 stars" class="las la-star"></label>
                                <input type="radio" name="review_star" id="star1" value="1"><label for="star1" title="1 star" class="las la-star"></label>
                            </div>
                            
                            <div class="rating-feedback-badge mt-1" id="ratingTextFeedback">5 - Excellent!</div>
                        </div>

                        <!-- Review Textarea -->
                        <div class="form-group mb-0">
                            <label for="modal_review_description" class="font-weight-bold text-dark" style="font-size: 14px;">
                                Detailed Review <span class="text-danger">*</span>
                            </label>
                            <textarea name="review_description" id="modal_review_description" rows="4" class="form-control" 
                                style="border-radius: 10px; border-color: #cbd5e1; font-size: 14px; resize: vertical;" 
                                placeholder="Share your experience with this product... What did you like or dislike about it?" required minlength="3" maxlength="2000"></textarea>
                            <small class="text-muted float-right mt-1" id="charCount">0/2000</small>
                        </div>
                    </div>

                    <div class="modal-footer bg-light" style="border-top: 1px solid #e2e8f0; padding: 14px 24px;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal" style="font-size: 14px; font-weight: 600;">Cancel</button>
                        <button type="submit" id="submitReviewBtn" class="btn btn-primary rounded-pill px-4 font-weight-bold" 
                            style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; font-size: 14px;">
                            <span id="submitBtnText"><i class="las la-paper-plane mr-1"></i> Submit Review</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const ratingLabels = {
                1: '1 - Very Poor',
                2: '2 - Poor',
                3: '3 - Average',
                4: '4 - Good',
                5: '5 - Excellent!'
            };

            // Update rating helper text when radio changed
            $('input[name="review_star"]').on('change', function() {
                let starVal = $(this).val();
                $('#ratingTextFeedback').text(ratingLabels[starVal] || (starVal + ' Stars'));
            });

            // Character counter for review textarea
            $('#modal_review_description').on('input', function() {
                let len = $(this).val().length;
                $('#charCount').text(len + '/2000');
            });

            // Open Modal for Create or Edit
            $(document).on('click', '.open-review-modal-btn', function() {
                let mode = $(this).attr('data-mode') || $(this).data('mode');
                let productId = $(this).attr('data-product-id') || $(this).data('product-id');
                let productName = $(this).attr('data-product-name') || $(this).data('product-name');
                let productImg = $(this).attr('data-product-img') || $(this).data('product-img');
                let orderId = $(this).attr('data-order-id') || $(this).data('order-id');
                let reviewId = $(this).attr('data-review-id') || $(this).data('review-id');
                let reviewStar = $(this).attr('data-review-star') || $(this).data('review-star') || 5;
                let reviewDesc = $(this).attr('data-review-desc') || $(this).data('review-desc') || '';

                $('#modal_product_id').val(productId);
                $('#modal_review_id').val(reviewId || '');
                $('#modal_product_title').text(productName);
                $('#modal_product_img').attr('src', productImg);
                
                if (orderId) {
                    $('#modal_order_badge').text('Order #' + orderId).show();
                } else {
                    $('#modal_order_badge').hide();
                }

                // Set Star Rating
                $('input[name="review_star"][value="' + reviewStar + '"]').prop('checked', true);
                $('#ratingTextFeedback').text(ratingLabels[reviewStar] || (reviewStar + ' Stars'));

                // Set Description
                $('#modal_review_description').val(reviewDesc);
                $('#charCount').text(reviewDesc.length + '/2000');

                if (mode === 'edit') {
                    $('#modalTitleText').text('Edit Your Review');
                    $('#submitBtnText').html('<i class="las la-save mr-1"></i> Update Review');
                } else {
                    $('#modalTitleText').text('Write a Review');
                    $('#submitBtnText').html('<i class="las la-paper-plane mr-1"></i> Submit Review');
                }

                $('#reviewModal').modal('show');
            });

            // Submit / Update Review Form AJAX Handler
            $('#reviewForm').on('submit', function(e) {
                e.preventDefault();
                
                let reviewId = $('#modal_review_id').val();
                let isEdit = reviewId ? true : false;
                let postUrl = isEdit ? "{{ route('review.update') }}" : "{{ route('review.store') }}";
                
                let submitBtn = $('#submitReviewBtn');
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span> Processing...');

                $.ajax({
                    url: postUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        submitBtn.prop('disabled', false).html(isEdit ? '<i class="las la-save mr-1"></i> Update Review' : '<i class="las la-paper-plane mr-1"></i> Submit Review');
                        
                        if (response.success) {
                            $('#reviewModal').modal('hide');
                            
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                alert(response.message);
                                window.location.reload();
                            }
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({ icon: 'error', title: 'Oops...', text: response.message || 'Something went wrong.' });
                            } else {
                                alert(response.message || 'Something went wrong.');
                            }
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(isEdit ? '<i class="las la-save mr-1"></i> Update Review' : '<i class="las la-paper-plane mr-1"></i> Submit Review');
                        let errMsg = 'Failed to submit review.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'error', title: 'Error', html: errMsg });
                        } else {
                            alert(errMsg);
                        }
                    }
                });
            });
        });
    </script>
@endsection
