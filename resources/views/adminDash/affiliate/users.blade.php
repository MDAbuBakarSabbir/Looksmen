@extends('layouts.AdminLays.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">Affiliate Users</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th data-breakpoints="lg">Phone</th>
                <th data-breakpoints="lg">Email Address</th>
                <th data-breakpoints="lg">Verification Info</th>
                <th>Approval</th>
                <th data-breakpoints="lg">Due Amount</th>
                <th width="10%" class="text-right">Options</th>
            </tr>
            </thead>
            <tbody>
            @foreach($affiliate_users as $key => $affiliate_user)
                @if($affiliate_user->user != null)
                    <tr>
                        <td>{{ ($key+1) + ($affiliate_users->currentPage() - 1)*$affiliate_users->perPage() }}</td>
                        <td>{{$affiliate_user->user->name}}</td>
                        <td>{{$affiliate_user->user->phone}}</td>
                        <td>{{$affiliate_user->user->email}}</td>
                        <td>
                            @if ($affiliate_user->informations != null)
                                <a href="{{ route('affiliate_users.show_verification_request', $affiliate_user->id) }}">
                                    <span class="badge badge-inline badge-info">Show</span>
                                </a>
                            @endif
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_approved(this)" value="{{ $affiliate_user->id }}" type="checkbox" <?php if($affiliate_user->status == 1) echo "checked";?> >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            @if ($affiliate_user->balance >= 0)
                                {{ single_price($affiliate_user->balance) }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if(auth('admin')->user()->hasPermission('manage_affiliate_withdraw'))
                                <a href="#" class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="show_payment_modal('{{$affiliate_user->id}}');" title="Pay Now">
                                    <i class="las la-money-bill"></i>
                                </a>
                            @endif
                            @if(auth('admin')->user()->hasPermission('manage_affiliate_withdraw'))
                                <a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="{{route('affiliate_user.payment_history', encrypt($affiliate_user->id))}}" title="Payment History">
                                    <i class="las la-history"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
          {{ $affiliate_users->appends(request()->input())->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="payment_modal">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>
@endsection

{{-- @section('modal')

    @include('modals.delete_modal')

		<div class="modal fade" id="payment_modal">
		    <div class="modal-dialog">
		        <div class="modal-content" id="modal-content">

		        </div>
		    </div>
		</div>

@endsection --}}

@section('script')
    <script type="text/javascript">
        function show_payment_modal(id){
            $.post('{{ route('affiliate_user.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment_modal #modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }

        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('affiliate_user.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', 'addPageApproved sellers updated successfully');
                }
                else{
                    AIZ.plugins.notify('danger', 'addPageSomething went wrong');
                }
            });
        }
    </script>
@endsection
