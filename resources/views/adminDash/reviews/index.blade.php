@extends('layouts.Backend.master')
@section('title')
    REVIEWS
@endsection
@section('content')
    <div class="row">
        <div class="col">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col"><input type="checkbox" id="productCheckAll"></th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Review</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td scope="row">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="product-check">
                                </div>
                            </td>
                            <td>
                                {{ $review->id }}
                            </td>
                            <td>
                                {{ $review->review_description }}
                            </td>
                            <td>
                                <label class="switch">
                                    <input class="reviews-status" type="checkbox" data-id="{{ $review->id }}"
                                        {{ $review->status == '1' ? 'checked' : '' }}>
                                    <span class="slider round"
                                        title="{{ $review->status == '1' ? 'Click for Deactive' : 'Click for Active' }}">
                                    </span>
                                </label>
                            </td>
                            <td>
                                <a title="View" href="{{ route('reviews.view', $review->id) }}"class="text-info mr-2"><i
                                        class="fa-solid fa-eye fa-lg"></i></a>
                                <a title="Delete" href="{{ route('reviews.admin_destroy', $review->id) }}"
                                    class="text-danger mr-2"><i class="fa-solid fa-trash fa-lg"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-danger">No Category found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


<script>
        // Master Checkbox
        $(document).on('change', '#productCheckAll', function() {
            $('.product-check').prop('checked', $(this).prop('checked'));
        });

        // Individual Checkbox
        $(document).on('change', '.product-check', function() {
            if ($('.product-check:checked').length === $('.product-check').length) {
                $('#productCheckAll').prop('checked', true);
            } else {
                $('#productCheckAll').prop('checked', false);
            }
        });
    </script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });
    </script>

    <script>
        document.querySelectorAll('.reviews-status').forEach(function(btn) {
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
                                title: status == 1 ? 'Status Activated Successfully' :
                                    'Status Deactivated Successfully'
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
