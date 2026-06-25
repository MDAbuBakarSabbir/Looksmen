@extends('layouts.AdminLays.master')

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 font-weight-bold text-dark"><i class="las la-university mr-2"></i>Pending Manual Recharges</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Deposit Details</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pending_recharges as $req)
                        @php
                            $details = json_decode($req->payment_details, true);
                        @endphp
                        <tr>
                            <td>{{ $req->created_at->format('d M, Y h:i A') }}</td>
                            <td>
                                <span class="d-block font-weight-bold">{{ $req->user->name ?? 'Deleted User' }}</span>
                                <small class="text-muted">{{ $req->user->email ?? '' }}</small>
                            </td>
                            <td class="font-weight-bold text-primary">৳{{ number_format($req->amount, 2) }}</td>
                            <td class="fs-12">
                                <strong>Trx ID:</strong> {{ $details['trx_id'] ?? '-' }}<br>
                                <strong>Account:</strong> {{ $details['bank_info'] ?? '-' }}<br>
                                <strong>Note:</strong> {{ $details['note'] ?? '' }}
                            </td>
                            <td class="text-right">
                                <form action="{{ route('admin.wallet.recharge.approve', $req->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm px-3" onclick="return confirm('Approve this recharge request?')">Approve</button>
                                </form>
                                <form action="{{ route('admin.wallet.recharge.reject', $req->id) }}" method="POST" class="d-inline-block ml-1">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm px-3" onclick="return confirm('Reject this recharge request?')">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No pending manual recharge requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $pending_recharges->links() }}
        </div>
    </div>
</div>
@endsection
