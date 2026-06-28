@extends('layouts.Backend.master')
@section('title')
    PAGES
@endsection
@section('content')
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header">
                    Pages List
                    <button id="addPage" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add</button>
                </div>
                <div class="card-body">

                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col"><input type="checkbox"></th>
                                <th scope="col">Page Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pages as $page)
                                <tr>
                                    <th scope="row"><input type="checkbox"></th>
                                    <td>{{ $page->page_name }}</td>
                                    <td>{{ $page->page_description }}</td>
                                    <td>
                                        <label class="switch">
                                            <input class="status-switch" type="checkbox" data-id="{{ $page->id }}"
                                                {{ $page->status == '1' ? 'checked' : '' }}>
                                            <span class="slider round"
                                                title="Click for {{ $page->status == '1' ? 'Deactive' : 'Active' }}">
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a title="Edit" class="mr-3" href="{{route('pages.edit',$page->id)}}">
                                            <img style="height: 20px"
                                                src="{{ asset('adminDash') }}/assets/img/layouts/edit.png" alt=""
                                                title="Edit">
                                        </a>
                                        <a class="pageDeleteBtn" data-id="{{ $page->id }}" href="javascript:void(0)"
                                            title="Delete">
                                            <img style="height: 20px"
                                                src="{{ asset('adminDash') }}/assets/img/layouts/delete.png" alt="">
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            <td colspan="5" class="text-center text-danger">No Page Found</td>
                            @endforelse

                        </tbody>
                    </table>

                    <div id="pageModel" class="modal">
                        <div class="modal-content">
                            <h3 style="margin-bottom:15px;">Add Pages</h3>
                            <form action="{{ route('pages.store') }}" method="POST">
                                @csrf
                                <label>Page Name</label>
                                <input type="text" name="page_name" placeholder="Enter Page Name" required>

                                <label>Page Description(English)</label>
                                <textarea type="text" name="english_description" placeholder="Enter Page Description in English" required></textarea>
                                <label>Page Description(বাংলা )</label>
                                <textarea type="text" name="english_description" placeholder="Enter Page Description in বাংলা"></textarea>

                                <button type="submit" class="submit-btn">Submit</button>
                                <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --------- CITY MODAL ----------
        const pageModel = document.getElementById('pageModel');
        const addPageBtn = document.getElementById('addPage');
        const cancelCityBtn = document.getElementById('cancelBtn');

        addPageBtn.onclick = function() {
            pageModel.classList.add('show');
        }

        cancelCityBtn.onclick = function() {
            pageModel.classList.remove('show');
        }

        window.addEventListener('click', function(event) {
            if (event.target === pageModel) {
                pageModel.classList.remove('show');
            }
        });
    </script>

    <script>
        document.querySelectorAll('.status-switch').forEach(function(btn) {
            btn.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;

                fetch("{{ route('pages.status') }}", {
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
                                title: status == 1 ? 'Activated Successfully' :
                                    'Deactivated Successfully'
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.pageDeleteBtn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const pageId = this.dataset.id;
                    const deleteUrl = `/admin/pages/destroy/${pageId}`;

                    // 2. SweetAlert2 কনফার্মেশন পপআপ দেখানো
                    Swal.fire({
                        title: 'Are You Sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Delete!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(deleteUrl, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content')
                                    },
                                })
                                .then(response => {

                                    if (!response.ok) {
                                        throw new Error(
                                            'নেটওয়ার্ক রেসপন্স ঠিক ছিল না');
                                    }
                                    return response
                                        .json();
                                })
                                .then(data => {

                                    if (data
                                        .success) {
                                        const Toast = Swal.mixin({
                                            toast: true,
                                            position: 'top',
                                            showConfirmButton: false,
                                            timer: 3000,
                                            timerProgressBar: true,
                                            didOpen: (toast) => {
                                                toast.addEventListener(
                                                    'mouseenter', Swal
                                                    .stopTimer);
                                                toast.addEventListener(
                                                    'mouseleave', Swal
                                                    .resumeTimer);
                                            }
                                        });

                                        Toast.fire({
                                            icon: 'success',
                                            title: 'সফলভাবে ডিলিট করা হয়েছে!'
                                        });

                                        const parentRow = button.closest(
                                            'tr');
                                        if (parentRow) {
                                            parentRow.remove();
                                        } else {
                                            window.location.reload();
                                        }


                                    } else {
                                        Swal.fire(
                                            'ব্যর্থ!',
                                            data.message ||
                                            'ডিলিট করা সম্ভব হয়নি।',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    // 5. AJAX বা নেটওয়ার্কের ত্রুটি হলে
                                    console.error('ডিলিট অপারেশনে একটি ত্রুটি হয়েছে:',
                                        error);
                                    Swal.fire(
                                        'ত্রুটি!',
                                        'ডিলিট করার সময় নেটওয়ার্ক সমস্যা হয়েছে।',
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });
        });
    </script>
@endsection
