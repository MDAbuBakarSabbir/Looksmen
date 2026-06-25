<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">District Name</th>
            <th scope="col">Delivery Charge</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($districts as $district)
            <tr>
                <th scope="row">{{ $districts->firstItem() + $loop->index }}</th>
                <td>{{ $district->name }}</td>
                <td>{{ $district->delivery_charge }}</td>
                <td>
                    <label class="switch">
                        <input class="districtstatus-switch" type="checkbox" data-id="{{ $district->id }}"
                            {{ $district->status == '1' ? 'checked' : '' }}>
                        <span class="slider round" title="Click to change status">
                        </span>
                    </label>
                </td>
                <td>
                    <a class="mr-2 text-primary edit-district-btn" href="javascript:void(0)" data-id="{{ $district->id }}" data-name="{{ $district->name }}" data-charge="{{ $district->delivery_charge }}" title="Edit District"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a class="text-danger delete-district-btn" href="javascript:void(0)" data-id="{{ $district->id }}" data-name="{{ $district->name }}" title="Delete District"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
        @empty
        @endforelse

    </tbody>
</table>
<div class="mt-2">
    {{ $districts->appends(['district_page' => request('district_page')])->links() }}
</div>
<script>
    document.querySelectorAll('.districtstatus-switch').forEach(function(btn) {
        btn.addEventListener('change', function() {
            let id = this.getAttribute('data-id');
            let status = this.checked ? 1 : 0;

            fetch("{{ route('district.status') }}", {
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
