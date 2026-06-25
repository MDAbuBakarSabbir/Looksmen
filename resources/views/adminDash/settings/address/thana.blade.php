<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Thana Name</th>
            <th scope="col">District Name</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($thanas as $thana)
            <tr>
                <th scope="row">{{ $thanas->firstItem() + $loop->index }}</th>
                <td>{{ $thana->name }}</td>
                <td>{{ $thana->district->name }}</td>
                <td>
                    <label class="switch">
                        <input class="Thanastatus-switch" type="checkbox" data-id="{{ $thana->id }}"
                            {{ $thana->status == '1' ? 'checked' : '' }}>
                        <span class="slider round" title="Click to change status">
                        </span>
                    </label>
                </td>
                <td>
                    <a class="mr-2 text-primary edit-thana-btn" href="javascript:void(0)" data-id="{{ $thana->id }}" data-name="{{ $thana->name }}" data-district-id="{{ $thana->district_id }}" title="Edit Thana"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a class="text-danger delete-thana-btn" href="javascript:void(0)" data-id="{{ $thana->id }}" data-name="{{ $thana->name }}" title="Delete Thana"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
        @empty
        @endforelse

    </tbody>
</table>
<div class="mt-2">
    {{ $thanas->appends(['thana_page' => request('thana_page')])->links() }}
</div>


<script>
        document.querySelectorAll('.Thanastatus-switch').forEach(function(btn) {
            btn.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;

                fetch("{{ route('thana.status') }}", {
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
