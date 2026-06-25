@extends('layouts.AdminLays.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Affiliate Logs')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th data-breakpoints="lg">Referred By</th>
                    <th>Referral User</th>
                    <th>Amount</th>
                    <th data-breakpoints="lg">Order Id</th>
                    <th data-breakpoints="lg">Referral Type</th>
                    <th data-breakpoints="lg">Product</th>
                    <th data-breakpoints="lg">Date</th>
                </thead>
                <tbody>
                @foreach($affiliate_logs as $key => $affiliate_log)
                    <tr>
                        <td>{{ ($key+1) + ($affiliate_logs->currentPage() - 1)*$affiliate_logs->perPage() }}</td>
                        <td>
                            {{ optional(\App\Models\User::where('id', $affiliate_log->referred_by_user)->first())->name }}
                        </td>
                        <td>
                            @if($affiliate_log->user_id !== null)
                                {{ optional($affiliate_log->user)->name }}
                            @else
                                Guest ({{ $affiliate_log->guest_id }})
                            @endif
                        </td>
                        <td>{{ single_price($affiliate_log->amount) }}</td>
                        <td>
                            @if($affiliate_log->order_id != null)
                                #LM-{{ $affiliate_log->order_id }}
                            @elseif($affiliate_log->order_detail && $affiliate_log->order_detail->order_id)
                                #LM-{{ $affiliate_log->order_detail->order_id }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td> {{ ucwords(str_replace('_',' ', $affiliate_log->affiliate_type)) }}</td>
                        <td>
                            @if($affiliate_log->order_detail_id != null && $affiliate_log->order_detail)
                                {{ optional($affiliate_log->order_detail->product)->title }}
                            @endif
                        </td>
                        <td>{{ $affiliate_log->created_at->format('d, F Y') }} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $affiliate_logs->links() }}
            </div>
        </div>
    </div>
@endsection
