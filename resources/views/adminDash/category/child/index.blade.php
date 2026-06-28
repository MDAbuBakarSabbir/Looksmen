@extends('layouts.Backend.master')
@section('title')
    CHILD CATEGORY
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
    <div class="card">
        <div class="card-body">
            <h3>Child Category </h3>
            <div class="head mb-3 d-flex justify-content-between">
                <div class="hula">
                    <a class="btn btn-primary" href="{{ route('category.index') }}"><i class="fa-solid fa-arrow-right"></i>Main
                        Category</a>
                    <a class="btn btn-primary" href="{{ route('sub-category.index') }}"><i
                            class="fa-solid fa-arrow-right"></i>Child Category</a>
                </div>
                <div class="form-group mr-3">
                    <input type="text" class="form-control input-focus" placeholder="Search Color">
                </div>
                <button class="btn btn-primary" id="AddChildCategory"><i class="fa-solid fa-plus"></i>
                    Add</button>
            </div>
            <div id="ChildCategoryModal" class="modal">
                <div class="modal-content">
                    <h3 style="margin-bottom:15px;">Add Sub Category</h3>
                    <form action="{{ route('child-category.store') }}" method="POST">
                        @csrf
                        <label for="exampleInputCatrgory1" class="form-label">Category<span
                                class="text-danger">*</span></label>
                        <select class="dropdown-groups select2-hidden-accessible" data-select2-id="2" tabindex="-1"
                            aria-hidden="true" name="category_id">
                            <optgroup data-select2-id="125">
                                <option selected disabled>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <label for="exampleInputCatrgory1" class="form-label">SubCategory<span
                                class="text-danger">*</span></label>
                        <select class="dropdown-groups select2-hidden-accessible" data-select2-id="1" tabindex="-1"
                            aria-hidden="true" name="subCategory_id">
                            <optgroup data-select2-id="130">
                                <option selected disabled>Select Sub Category</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>

                        <label>Child Category Name<span class="text-danger">*</span></label>
                        <input type="text" name="childcategory_name" placeholder="Enter Child Category Name">

                        <label>Meta Title (Optional)</label>
                        <input type="text" name="meta_title" placeholder="Enter Meta Title">

                        <label>Meta Description (Optional)</label>
                        <input type="text" name="meta_description" placeholder="Enter Meta Description">

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
                    @forelse ($childcategories as $childcategory)
                    <tr>
                            <th scope="row">1</th>
                            <td>{{ $childcategory->name }}</td>
                            <td>{{ $childcategory->banner }}</td>
                            <td>
                                <label class="switch">
                                    <input class="status-switch" type="checkbox" data-id="{{ $childcategory->id }}"
                                        {{ $childcategory->status == '1' ? 'checked' : '' }}>
                                    <span class="slider round" title="Click for Deactive">
                                    </span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('category.edit', $childcategory->id) }}" class="text-primary mr-2"
                                        title="Click to Edit"><i class="fa-solid fa-pen-to-square fa-xl"></i></a>
                                    <a href="{{ route('category.destroy', $childcategory->id) }}" class="text-danger"
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
    <script>
        const modal = document.getElementById('ChildCategoryModal');
        const addChildCategoryBtn = document.getElementById('AddChildCategory');
        const cancelBtn = document.getElementById('cancelBtn');

        // Show modal
        addChildCategoryBtn.onclick = function() {
            ChildCategoryModal.classList.add('show');
        }

        // Hide ChildCategoryModal
        cancelBtn.onclick = function() {
            ChildCategoryModal.classList.remove('show');
        }

        // Hide ChildCategoryModal when clicking outside
        window.onclick = function(event) {
            if (event.target === ChildCategoryModal) {
                ChildCategoryModal.classList.remove('show');
            }
        }
    </script>
@endsection
