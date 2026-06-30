@extends('layouts.Backend.master')
@section('title')
    AFFILIATE USERS
@endsection
@section('content')
    <style>
        /* --- Premium Admin Style Rules --- */
        .metrics-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
        }
        .metrics-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
        .metrics-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .table-custom th {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #374151;
            background-color: #f9fafb !important;
            border-bottom: 2px solid #e5e7eb;
            padding: 16px 20px;
        }
        .table-custom td {
            padding: 16px 20px;
            vertical-align: middle;
            font-size: 14px;
            color: #4b5563;
            border-bottom: 1px solid #f3f4f6;
        }
        .action-icon-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 14px;
            margin-left: 4px;
        }
        .action-icon-btn.pay {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        .action-icon-btn.pay:hover {
            background: #10b981;
            color: #fff;
        }
        .action-icon-btn.history {
            background: rgba(99, 102, 241, 0.1);
            color: #4f46e5;
        }
        .action-icon-btn.history:hover {
            background: #4f46e5;
            color: #fff;
        }
        .badge-info-light {
            background-color: rgba(99, 102, 241, 0.1);
            color: #4f46e5;
            padding: 6px 12px;
            font-weight: 600;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .badge-info-light:hover {
            background-color: #4f46e5;
            color: #fff;
            text-decoration: none;
        }
        .pagination-container .pagination {
            justify-content: flex-end;
            margin-top: 15px;
        }
    </style>

    @php
        $totalAffiliates = \App\Models\AffiliateUser::count();
        $activeAffiliates = \App\Models\AffiliateUser::where('status', 1)->count();
        $pendingAffiliates = \App\Models\AffiliateUser::where('status', 0)->count();
    @endphp

    {{-- Metrics section --}}
    <div class="row mb-4">
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Total Affiliates</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #1f2937;">{{ $totalAffiliates }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Active Affiliates</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #10b981;">{{ $activeAffiliates }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="metrics-card card p-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 font-weight-bold fs-12 uppercase tracking-wide">Pending Verification</p>
                        <h3 class="mb-0 font-weight-bold" style="color: #ef4444;">{{ $pendingAffiliates }}</h3>
                    </div>
                    <div class="metrics-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main View Panel --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;">Affiliate Users List</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 80px;">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Email Address</th>
                                    <th scope="col">Verification Info</th>
                                    <th scope="col" style="width: 140px;">Status</th>
                                    <th scope="col">Due Amount</th>
                                    <th scope="col" style="width: 130px; text-align: right;">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($affiliate_users as $key => $affiliate_user)
                                    @if ($affiliate_user->user != null)
                                        <tr>
                                            <td class="font-weight-bold">{{ ($key+1) + ($affiliate_users->currentPage() - 1)*$affiliate_users->perPage() }}</td>
                                            <td class="font-weight-bold text-dark">{{ $affiliate_user->user->name }}</td>
                                            <td>{{ $affiliate_user->user->phone ?? 'N/A' }}</td>
                                            <td>{{ $affiliate_user->user->email }}</td>
                                            <td>
                                                @if ($affiliate_user->informations != null)
                                                    <a href="{{ route('affiliate_users.show_verification_request', $affiliate_user->id) }}" class="badge-info-light">
                                                        <i class="fa-solid fa-file-signature mr-1"></i>Show Details
                                                    </a>
                                                @else
                                                    <span class="text-muted fs-12">No data</span>
                                                @endif
                                            </td>
                                            <td>
                                                <label class="switch mb-0">
                                                    <input onchange="update_approved(this)" value="{{ $affiliate_user->id }}" type="checkbox" {{ $affiliate_user->status == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td class="font-weight-bold text-dark">
                                                @if ($affiliate_user->balance >= 0)
                                                    {{ single_price($affiliate_user->balance) }}
                                                @else
                                                    à§³0.00
                                                @endif
                                            </td>
                                            <td style="text-align: right;">
                                                <div class="d-inline-flex gap-2">
                                                    @if(auth('admin')->user()->hasPermission('manage_affiliate_withdraw'))
                                                        <button type="button" class="action-icon-btn pay" onclick="show_payment_modal('{{$affiliate_user->id}}');" title="Pay Now">
                                                            <i class="fa-solid fa-credit-card"></i>
                                                        </button>
                                                    @endif
                                                    @if(auth('admin')->user()->hasPermission('manage_affiliate_withdraw'))
                                                        <a class="action-icon-btn history" href="{{route('affiliate_user.payment_history', encrypt($affiliate_user->id))}}" title="Payment History">
                                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-users-slash mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                                            <p class="mb-0 font-weight-bold">No Affiliate Users Found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 pagination-container">
                        {{ $affiliate_users->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Modal Structure --}}
    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                {{-- Loaded Dynamically --}}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function show_payment_modal(id){
            $.post('{{ route('affiliate_user.payment_modal') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
                $('#payment_modal #modal-content').html(data);
                $('#payment_modal').modal('show');
            });
        }

        function update_approved(el){
            let status = el.checked ? 1 : 0;
            $.post('{{ route('affiliate_user.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    Toast.fire({
                        icon: 'success',
                        title: 'Affiliate approval updated successfully'
                    });
                }
                else{
                    Toast.fire({
                        icon: 'error',
                        title: 'Something went wrong'
                    });
                }
            });
        }
    </script>
@endsection
