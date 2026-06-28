@extends('layouts.Backend.master')
@section('title')
    ATTRIBUTE
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
                <div class="card-header">
                    <h3>Attributes</h3>
                    <button id="addAttribute" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add</button>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Attributes Name</th>
                                <th scope="col">Value</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($attributes as $attribute)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" class="product-check">
                                        </div>
                                    </td>
                                    <td>{{ $attribute->name }}</td>
                                    <td>
                                        @foreach ($attribute->AttributeValues as $attributesvalue)
                                            {{ $attributesvalue->value }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input class="status-switch" type="checkbox" data-id="{{ $attribute->id }}"
                                                {{ $attribute->status == '1' ? 'checked' : '' }}>
                                            <span class="slider round"
                                                title="{{ $attribute->status == '1' ? 'Click for Deactive' : 'Click for Active' }}">
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a title="Edit" href="{{ route('attribute.create', $attribute->id) }}" class="text-primary mr-2"><img style="height: 20px" src="{{asset('adminDash')}}/assets/img/layouts/edit.png" alt="Edit"></a>
                                        <a title="Delete" href="{{ route('product.destroy', $attribute->id) }}" class="text-danger mr-2"><img style="height: 20px" src="{{asset('adminDash')}}/assets/img/layouts/delete.png" alt="" title="Delete"></a>
                                    </td>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-danger">No Attribute found.</td>
                                    </tr>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div id="AttributeModal" class="modal">
                            <div class="modal-content">
                                <h3 style="margin-bottom:15px;">Add Attribute</h3>
                                <form action="{{ route('attribute.store') }}" method="POST">
                                    @csrf
                                    <label>Attribute Name</label>
                                    <input type="text" name="attribute_name" placeholder="Enter Attribute Name" required>

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
            const attributeModal = document.getElementById('AttributeModal');
            const addAttributeBtn = document.getElementById('addAttribute');
            const cancelCityBtn = document.getElementById('cancelBtn');

            addAttributeBtn.onclick = function() {
                attributeModal.classList.add('show');
            }

            cancelCityBtn.onclick = function() {
                attributeModal.classList.remove('show');
            }

            window.addEventListener('click', function(event) {
                if (event.target === attributeModal) {
                    attributeModal.classList.remove('show');
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
            document.querySelectorAll('.status-switch').forEach(function(btn) {
                btn.addEventListener('change', function() {
                    let id = this.getAttribute('data-id');
                    let status = this.checked ? 1 : 0;

                    fetch("{{ route('attribute.status') }}", {
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
