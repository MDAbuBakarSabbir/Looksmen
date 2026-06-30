@extends('layouts.Backend.master')
@section('title')
    PRODUCT REVIEWS
@endsection
@section('content')
    <style>
        /* --- Premium Admin Style Rules --- */
        .metrics-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
        }
        .metrics-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
        .metrics-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .table-custom th {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #374151;
            background-color: #f9fafb !important;
            border-bottom: 2px solid #e5e7eb;
            padding: 16px 20px;
        }
        .table-custom td {
            padding: 16px 20px;
            vertical-align: middle;
            font-size: 14px;
            color: #4b5563;
            border-bottom: 1px solid #f3f4f6;
        }
        .star-rating {
            color: #fb513b;
            font-size: 13px;
        }
        .action-icon-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        .action-icon-btn.view {
            background: rgba(99, 102, 241, 0.1);
            color: #4f46e5;
        }
        .action-icon-btn.view:hover {
            background: #4f46e5;
            color: #fff;
        }
        .action-icon-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        .action-icon-btn.delete:hover {
            background: #ef4444;
            color: #fff;
        }
        /* --- Modern Pop-up Modal Customization --- */
        .modal {
            position: fixed;
            z-index: 1060;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .modal.show {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            width: 500px;
            max-width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            border: none;
        }
        .modal.show .modal-content {
            transform: scale(1);
        }
        .modal-header-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .modal-title-custom {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        .detail-label {
            font-size: 12px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 600;
            margin-bottom: 16px;
        }
    </style>

    {{-- Metrics section --}}
    <div class="row mb-4">
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Total Reviews</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #1f2937;">{{ $reviews->count() }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Approved Reviews</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #10b981;">{{ $reviews->where('status', '1')->count() }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Pending Reviews</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #ef4444;">{{ $reviews->where('status', '0')->count() }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main View Panel --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-0">
                    <div class="p-4 d-flex align-items-center justify-content-between flex-wrap border-bottom border-light gap-3">
                        <h4 class="mb-0 font-weight-bold" style="color: #1f2937;">Product Reviews List</h4>
                        <div class="position-relative">
                            <i class="fa-solid fa-magnifying-glass text-muted position-absolute" style="left: 12px; top: 12px; font-size: 14px;"></i>
                            <input type="text" id="reviewSearch" class="form-control" placeholder="Search reviews..." style="padding-left: 36px; border-radius: 8px; width: 250px; font-size: 13px;">
                        </div>
                    </div>

                    {{-- Review Details Modal --}}
                    <div id="reviewDetailsModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header-custom">
                                <h3 class="modal-title-custom">Review Details</h3>
                                <button type="button" class="btn p-0 bg-transparent text-muted" id="closeModalCross" style="font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="modal-body p-0">
                                <div class="detail-label">Product Name</div>
                                <div class="detail-value" id="modalProduct">N/A</div>

                                <div class="detail-label">Customer Name</div>
                                <div class="detail-value" id="modalCustomer">Anonymous</div>

                                <div class="detail-label">Rating</div>
                                <div class="detail-value" id="modalRating">
                                    <div class="star-rating" id="modalRatingStars"></div>
                                </div>

                                <div class="detail-label">Review Description</div>
                                <div class="detail-value" id="modalDescription" style="font-weight: 500; font-style: italic; background: #f9fafb; padding: 15px; border-radius: 8px; line-height: 1.6; border: 1px solid #f3f4f6;">No description...</div>
                            </div>
                            <div class="modal-footer-custom" style="border-top: none; padding-top: 0;">
                                <button type="button" class="btn-cancel-custom" id="closeBtn">Close</button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 80px;">#</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col" style="width: 130px;">Rating</th>
                                    <th scope="col">Review Content</th>
                                    <th scope="col" style="width: 140px;">Status</th>
                                    <th scope="col" style="width: 120px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="reviewsTableBody">
                                @forelse ($reviews as $index => $review)
                                    <tr class="review-row">
                                        <td class="font-weight-bold">{{ $index + 1 }}</td>
                                        <td class="font-weight-bold text-dark">
                                            <span style="display: block; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $review->product?->name ?? 'N/A' }}">
                                                {{ $review->product?->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $review->user?->name ?? 'Guest / Anonymous' }}</td>
                                        <td>
                                            <div class="star-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->review_star)
                                                        <i class="fa-solid fa-star"></i>
                                                    @else
                                                        <i class="fa-regular fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted" style="display: block; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $review->review_description ?? 'No description provided' }}
                                            </span>
                                        </td>
                                        <td>
                                            <label class="switch mb-0">
                                                <input class="status-switch" type="checkbox" data-id="{{ $review->id }}" {{ $review->status == '1' ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="d-inline-flex gap-2">
                                                <button type="button" class="action-icon-btn view view-review-details" data-id="{{ $review->id }}" title="View Details">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <a href="{{ route('reviews.admin_destroy', $review->id) }}" class="action-icon-btn delete" title="Delete Review" onclick="return confirm('Are you sure you want to delete this Review?');">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                                            <p class="mb-0 font-weight-bold">No Reviews Found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('reviewDetailsModal');
        const closeCross = document.getElementById('closeModalCross');
        const closeBtn = document.getElementById('closeBtn');

        // Hide modal
        closeCross.onclick = function() {
            modal.classList.remove('show');
        }
        closeBtn.onclick = function() {
            modal.classList.remove('show');
        }

        // Hide modal when clicking outside
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        }

        // Fetch details & show modal
        document.querySelectorAll('.view-review-details').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                let url = `/admin/reviews/view/${id}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            let review = data.review;
                            document.getElementById('modalProduct').textContent = review.product ? review.product.name : 'N/A';
                            document.getElementById('modalCustomer').textContent = review.user ? review.user.name : 'Guest / Anonymous';
                            document.getElementById('modalDescription').textContent = review.review_description ? review.review_description : 'No description provided';
                            
                            // Generate stars
                            let starsHtml = '';
                            for (let i = 1; i <= 5; i++) {
                                if (i <= review.review_star) {
                                    starsHtml += '<i class="fa-solid fa-star mr-1"></i>';
                                } else {
                                    starsHtml += '<i class="fa-regular fa-star text-muted mr-1"></i>';
                                }
                            }
                            document.getElementById('modalRatingStars').innerHTML = starsHtml;

                            modal.classList.add('show');
                        }
                    })
                    .catch(err => {
                        console.error('Error fetching review details:', err);
                    });
            });
        });

        // Search logic
        document.getElementById('reviewSearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#reviewsTableBody .review-row');
            
            rows.forEach(function(row) {
                let product = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                let customer = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                let content = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                if (product.indexOf(filter) > -1 || customer.indexOf(filter) > -1 || content.indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        document.querySelectorAll('.status-switch').forEach(function(btn) {
            btn.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;

                fetch("{{ route('reviews.status') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id: id,
                            status: status
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Toast.fire({
                                icon: 'success',
                                title: status == 1 ? 'Review Approved' : 'Review Suspended'
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong'
                            });
                        }
                    })
                    .catch(err => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Server Error'
                        });
                    });
            });
        });
    </script>
@endsection
