@extends('layouts.AdminLays.master')
@section('title')
    REGISTERED CUSTOMERS
@endsection
@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 42px;
            height: 22px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #cbd5e1;
            transition: .3s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #ef4444; /* red for blocked */
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark mb-0">Registered Customers</h3>
                    
                    <form method="GET" action="{{ route('regCustomer') }}" class="d-flex align-items-center" style="width: 250px;">
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
                                    <th>Date Registered</th>
                                    <th class="text-center">Block Account</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td><span class="font-weight-bold text-muted">#{{ $customer->id }}</span></td>
                                        <td><span class="font-weight-bold text-dark">{{ $customer->name }}</span></td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->created_at->format('d M Y, h:i A') }}</td>
                                        <td class="text-center">
                                            <label class="switch mb-0">
                                                <input type="checkbox" class="block-toggle" data-id="{{ $customer->id }}" {{ $customer->is_blocked ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No registered customers found.</td>
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
            // Block/Unblock toggle handler
            $('.block-toggle').change(function() {
                const id = $(this).data('id');
                const isBlocked = $(this).is(':checked') ? 1 : 0;
                
                $.ajax({
                    url: `/admin/customer/toggle-block/${id}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_blocked: isBlocked
                    },
                    success: function(response) {
                        if (response.success) {
                            window.Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                        }
                    },
                    error: function() {
                        window.Toast.fire({
                            icon: 'error',
                            title: 'Failed to update block status'
                        });
                    }
                });
            });
        });
    </script>
@endsection
