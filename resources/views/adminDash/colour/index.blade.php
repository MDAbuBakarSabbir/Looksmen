@extends('layouts.adminLays.master')
@section('title')
    COLOR
@endsection
@section('content')
    <div class="row">
        <div class="col-7">
            <div class="card">
                <div class="card-header">
                    <div class="text">
                        <h3>Colors List</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row ml-2 mb-2 d-flex justify-content-between">
                        <div class="buttns">
                            <button class="btn btn-primary mr-3" type="submit"><i class="fa-solid fa-thumbs-up"></i>
                                Active</button>
                            <button class="btn btn-danger" type="submit"><i class="fa-solid fa-thumbs-down"></i>
                                Deactive</button>
                        </div>
                        <div class="form-group mr-3">
                            <input type="text" class="form-control input-focus" placeholder="Search Color">
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"><input type="checkbox" id="colorCheckAll"></th>
                                <th scope="col">Colour Name</th>
                                <th scope="col">Color</th>
                                <th scope="col">Color Code</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($colors as $color)
                                <tr>
                                    <th scope="row"><input type="checkbox" class="color-check"></th>
                                    <td>{{ $color->color_name }}</td>
                                    <td>
                                        <input style="height: 30px;width: 30px;border: none;" type="color"
                                            value="{{ $color->color_code }}" disabled>
                                    </td>
                                    <td>{{ $color->color_code }}</td>
                                    <td>
                                        {{-- <form id="colorstatus" action="{{ route('color.status', $color->id) }}"
                                            method="POST">
                                            @csrf --}}
                                        {{-- onchange="document.querySelector('#colorstatus').submit()" --}}
                                        <label class="switch">
                                            <input class="status-switch" type="checkbox" data-id="{{ $color->id }}"
                                                {{ $color->status == '1' ? 'checked' : '' }}>
                                            <span class="slider round" title="Click to change status">
                                            </span>
                                        </label>
                                        {{-- </form> --}}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('color.edit', $color->id) }}" class="text-primary mr-2"
                                                title="Click to Edit"><img style="height: 20px" src="{{asset('adminDash')}}/assets/img/layouts/edit.png" title="Edit" alt=""></a>
                                            <a href="{{ route('color.destroy', $color->id) }}" class="text-danger ConfirmDelete"
                                                title="Click to Delete"><img style="height: 20px" src="{{asset('adminDash')}}/assets/img/layouts/delete.png" alt="" title="Delete"></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="6" class="text-center text-danger">No Color found.</td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header">
                    <h4>Add Color</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('color.joma') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Color Name</label>
                            <input type="text" name="color_name" class="form-control" placeholder="Enter Color Name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Color Code</label>
                            <input type="text" name="color_code" class="form-control"
                                placeholder="Enter Color Code with #">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>







    <script>
        document.querySelectorAll('.status-switch').forEach(function(btn) {
            btn.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;

                fetch("{{ route('color.status') }}", {
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
        // Master Checkbox
        $(document).on('change', '#colorCheckAll', function() {
            $('.color-check').prop('checked', $(this).prop('checked'));
        });

        // Individual Checkbox
        $(document).on('change', '.color-check', function() {
            if ($('.color-check:checked').length === $('.color-check').length) {
                $('#colorCheckAll').prop('checked', true);
            } else {
                $('#colorCheckAll').prop('checked', false);
            }
        });
    </script>
@endsection
