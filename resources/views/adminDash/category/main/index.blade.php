@extends('layouts.Backend.master')
@section('title')
    MAIN CATEGORY
@endsection
@section('content')
    <style>
        /* --- General Button --- */
        .btn {
            padding: 10px 20px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #1e40af;
        }

        /* --- Modal Background --- */
        .modal {
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;

            /* Animation setup */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        /* Show modal smoothly */
        .modal.show {
            opacity: 1;
            visibility: visible;
        }

        /* --- Modal Content Box --- */
        .modal-content {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            width: 400px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-30px);
            transition: transform 0.3s ease;
        }

        /* Slide in effect */
        .modal.show .modal-content {
            transform: translateY(0);
        }

        /* --- Input Styles --- */
        .modal-content input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        /* --- Buttons --- */
        .modal-content button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .submit-btn {
            background: #16a34a;
            color: white;
        }

        .submit-btn:hover {
            background: #15803d;
        }

        .cancel-btn {
            background: #dc2626;
            color: white;
            margin-left: 10px;
        }

        .cancel-btn:hover {
            background: #b91c1c;
        }
    </style>
    <div class="row">
        <div class="col">

            <div class="card">
                <div class="card-body">
                    <h3>Main Category </h3>
                    <div class="head mb-3 d-flex justify-content-between">
                        <div class="hula">
                            <a class="btn btn-primary" href="{{ route('sub-category.index') }}"><i
                                    class="fa-solid fa-arrow-right"></i>Sub Category</a>
                            <a class="btn btn-primary" href="{{ route('child-category.index') }}"><i
                                    class="fa-solid fa-arrow-right"></i>Child Category</a>
                        </div>
                        <div class="form-group mr-3">
                            <input type="text" class="form-control input-focus" placeholder="Search Color">
                        </div>
                        <button id="AddCategory" class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                            Add</button>
                    </div>

                    <div id="categoryModal" class="modal">
                        <div class="modal-content">
                            <h3 style="margin-bottom:15px;">Add Category</h3>
                            <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <label>Category Name<span class="text-danger">*</span></label>
                                <input type="text" name="category_name" placeholder="Enter Category Name" required>

                                <label>Category Type<span class="text-danger">*</span></label>
                                <select class="dropdown-groups select2-hidden-accessible" data-select2-id="2" tabindex="-1"
                                    aria-hidden="true" name="type">
                                    <option selected disabled>Select Type</option>
                                    <option value="physical">Physical</option>
                                    <option value="digital">Digital</option>
                                </select>

                                <label>Commission Rate<span class="text-danger">*</span></label>
                                <input type="text" name="commission_rate" placeholder="Enter Commission Rate" required>

                                <label>Category Image<span class="text-danger">*</span></label>
                                <input type="file" name="image" required>

                                <label>Category Icon<span class="text-danger">*</span></label>
                                <input type="text" name="icon" placeholder="Enter Commission Rate" required>

                                <label>Meta Title (Optional)</label>
                                <input type="text" name="meta_title" placeholder="Enter Commission Rate">

                                <label>Meta Description (Optional)</label>
                                <input type="text" name="meta_description" placeholder="Enter Commission Rate">

                                <button type="submit" class="submit-btn">Submit</button>
                                <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                            </form>
                        </div>
                    </div>

                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Category Name</th>
                                <th scope="col">Image</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($maincategorys as $maincategory)
                                <tr>
                                    <th scope="row">1</th>
                                    <td>{{ $maincategory->name }}</td>
                                    <td><img height="70px" src="{{asset('adminDash/images/category')}}/{{ $maincategory->banner }}" alt=""></td>
                                    <td>
                                        <label class="switch">
                                            <input class="status-switch" type="checkbox" data-id="{{ $maincategory->id }}"
                                                {{ $maincategory->status == '1' ? 'checked' : '' }}>
                                            <span class="slider round"
                                                title="{{ $maincategory->status == '1' ? 'Click for Deactive' : 'Click for active' }}">
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('category.edit', $maincategory->id) }}"
                                                class="text-primary mr-2" title="Click to Edit"><i
                                                    class="fa-solid fa-pen-to-square fa-xl"></i></a>
                                            <a href="{{ route('category.destroy', $maincategory->id) }}" class="text-danger"
                                                title="Click to Delete"><i class="fa-solid fa-trash fa-xl"></i></a>
                                        </div>
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
        </div>
    </div>








    <script>
        const modal = document.getElementById('categoryModal');
        const addBtn = document.getElementById('AddCategory');
        const cancelBtn = document.getElementById('cancelBtn');

        // Show modal
        addBtn.onclick = function() {
            modal.classList.add('show');
        }

        // Hide modal
        cancelBtn.onclick = function() {
            modal.classList.remove('show');
        }

        // Hide modal when clicking outside
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        }
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
        document.querySelectorAll('.status-switch').forEach(function(btn) {
            btn.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;

                fetch("{{ route('category.status') }}", {
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
