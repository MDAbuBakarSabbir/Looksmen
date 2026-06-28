@extends('layouts.Backend.master')
@section('title')
    SUB CATEGORY
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
            <h3>Sub Category </h3>
            <div class="head mb-3 d-flex justify-content-between">
                <div class="hula">
                    <a class="btn btn-primary" href="{{ route('category.index') }}"><i class="fa-solid fa-arrow-right"></i>Main
                        Category</a>
                    <a class="btn btn-primary" href="{{ route('child-category.index') }}"><i
                            class="fa-solid fa-arrow-right"></i>Child Category</a>
                </div>
                <div class="form-group mr-3">
                    <input type="text" class="form-control input-focus" placeholder="Search Color">
                </div>
                <button id="AddSubCategory" class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                    Add</button>
            </div>

            <div id="SubCategoryModal" class="modal">
                <div class="modal-content">
                    <h3 style="margin-bottom:15px;">Add Sub Category</h3>
                    <form action="{{ route('sub-category.store') }}" method="POST">
                        @csrf
                        <label for="exampleInputCatrgory1" class="form-label">Category<span
                                class="text-danger">*</span></label>
                        <select class="dropdown-groups select2-hidden-accessible" data-select2-id="1" tabindex="-1"
                            aria-hidden="true" name="category_id" required>
                            <optgroup data-select2-id="118">
                                <option selected disabled>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>

                        <label>SubCategory Name<span
                                class="text-danger">*</span></label>
                        <input type="text" name="subcategory_name" placeholder="Enter SubCategory Name" required>
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
                    @forelse ($subcategories as $subcategory)
                    <tr>
                            <th scope="row">1</th>
                            <td>{{ $subcategory->name }}</td>
                            <td>{{ $subcategory->banner }}</td>
                            <td>
                                <label class="switch">
                                    <input class="status-switch" type="checkbox" data-id="{{ $subcategory->id }}"
                                        {{ $subcategory->status == '1' ? 'checked' : '' }}>
                                    <span class="slider round"
                                        title="{{ $subcategory->status == '1' ? 'Click for Deactive' : 'Click for Active' }}Click for Deactive">
                                    </span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('category.edit', $subcategory->id) }}" class="text-primary mr-2"
                                        title="Click to Edit"><i class="fa-solid fa-pen-to-square fa-xl"></i></a>
                                    <a href="{{ route('category.destroy', $subcategory->id) }}" class="text-danger"
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
        const modal = document.getElementById('SubCategoryModal');
        const addBtn = document.getElementById('AddSubCategory');
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
@endsection
