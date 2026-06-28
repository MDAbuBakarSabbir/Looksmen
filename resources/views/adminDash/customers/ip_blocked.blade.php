@extends('layouts.Backend.master')
@section('title')
    BLOCKED IPS
@endsection
@section('content')
    <div class="row">
        <!-- Block New IP Form -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white pt-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Block New IP Address</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ip_block.store') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label small text-muted font-weight-bold">IP Address</label>
                            <input type="text" name="ip_address" class="form-control" placeholder="e.g. 192.168.1.1" required value="{{ old('ip_address') }}">
                            <small class="form-text text-muted">Supports IPv4 and IPv6 addresses.</small>
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label small text-muted font-weight-bold">Reason for Block</label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="e.g. Suspicious checkout behavior">{{ old('reason') }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-danger btn-block rounded-pill py-2">
                            <i class="fa fa-ban mr-1"></i> Block IP Address
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Blocked IPs List -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold text-dark mb-0">IP Blacklist</h5>
                    
                    <form method="GET" action="{{ route('customeripBlock') }}" class="d-flex align-items-center" style="width: 250px;">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search IP/Reason..." value="{{ $search }}">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>IP Address</th>
                                    <th>Reason</th>
                                    <th>Date Blocked</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blockedIps as $ip)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold text-danger">{{ $ip->ip_address }}</span>
                                        </td>
                                        <td>{{ $ip->reason ?? 'No reason provided' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($ip->created_at)->format('d M Y, h:i A') }}</td>
                                        <td class="text-center">
                                            <form method="POST" action="{{ route('ip_block.destroy', $ip->id) }}" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-success px-3 rounded-pill unblock-ip-btn">
                                                    <i class="fa fa-unlock-keyhole mr-1"></i> Unblock IP
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No IP addresses blocked currently.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-4">
                        {{ $blockedIps->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Confirm unblock
            $('.delete-form').submit(function(e) {
                e.preventDefault();
                const form = this;
                
                Swal.fire({
                    title: 'Remove IP block?',
                    text: "This IP address will regain full access to the website.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Unblock'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
