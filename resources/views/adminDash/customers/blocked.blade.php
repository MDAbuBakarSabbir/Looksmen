@extends('layouts.Backend.master')
@section('title')
    BLOCKED CUSTOMERS
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark mb-0">Blocked Accounts</h3>
                    
                    <form method="GET" action="{{ route('customerBlock') }}" class="d-flex align-items-center" style="width: 250px;">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name/email..." value="{{ $search }}">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Date Blocked</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td><span class="font-weight-bold text-muted">#{{ $customer->id }}</span></td>
                                        <td><span class="font-weight-bold text-dark">{{ $customer->name }}</span></td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->updated_at->format('d M Y, h:i A') }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-success px-4 rounded-pill unblock-btn" data-id="{{ $customer->id }}">
                                                <i class="fa fa-unlock mr-1"></i> Unblock Account
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No blocked user accounts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-4">
                        {{ $customers->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Unblock button handler
            $('.unblock-btn').click(function() {
                const id = $(this).data('id');
                const btn = $(this);
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to restore access for this customer account.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Unblock'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/customer/toggle-block/${id}`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                is_blocked: 0
                            },
                            success: function(response) {
                                if (response.success) {
                                    window.Toast.fire({
                                        icon: 'success',
                                        title: 'Customer account unblocked.'
                                    });
                                    btn.closest('tr').fadeOut(400, function() {
                                        $(this).remove();
                                        if ($('tbody tr').length === 0) {
                                            $('tbody').html('<tr><td colspan="5" class="text-center py-5 text-muted">No blocked user accounts found.</td></tr>');
                                        }
                                    });
                                }
                            },
                            error: function() {
                                window.Toast.fire({
                                    icon: 'error',
                                    title: 'Failed to unblock customer'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
