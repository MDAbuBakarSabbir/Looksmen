@extends('layouts.Backend.master')
@section('title')
    NON-REGISTERED CUSTOMERS
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark mb-0">Non-Registered (Guest) Customers</h3>
                    
                    <form method="GET" action="{{ route('nonRegCustomer') }}" class="d-flex align-items-center" style="width: 250px;">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name/phone..." value="{{ $search }}">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Default Address</th>
                                    <th class="text-center">Total Orders</th>
                                    <th class="text-center">Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td><span class="font-weight-bold text-dark">{{ $customer->name }}</span></td>
                                        <td><span class="font-weight-bold text-primary">{{ $customer->phone }}</span></td>
                                        <td>{{ $customer->address }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-light px-3 py-2 font-weight-bold" style="border-radius:20px;">{{ $customer->total_orders }} Orders</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="font-weight-bold text-success">৳{{ number_format($customer->total_spent, 2) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No non-registered customers found.</td>
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
