@extends('layouts.frontEnd.master')

@section('title')
    AFFILIATE PAYMENTS
@endsection

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --dash-primary: #6366f1;
        --dash-bg: #f8fafc;
        --dash-surface: #ffffff;
        --dash-border: #e2e8f0;
        --dash-text: #1e293b;
        --dash-muted: #64748b;
    }

    .dash-section {
        background-color: var(--dash-bg);
        font-family: 'Outfit', sans-serif !important;
        min-height: calc(100vh - 150px);
        padding: 40px 0;
    }

    .dash-card {
        background: var(--dash-surface);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid var(--dash-border);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .dash-card-header {
        background: #ffffff;
        padding: 20px 24px;
        border-bottom: 1px solid var(--dash-border);
    }

    .dash-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--dash-text);
    }

    .tab-link {
        font-weight: 600;
        color: var(--dash-muted);
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.2s;
        text-decoration: none !important;
    }

    .tab-link.active {
        background: #e0e7ff;
        color: var(--dash-primary);
    }
</style>

<section class="dash-section">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('frontEnd.dashboard.partials.usersideNav')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <!-- Navigation Tabs for Affiliate Panel -->
                <div class="d-flex mb-4 flex-wrap" style="gap: 10px;">
                    <a href="{{ route('affiliate.user.index') }}" class="tab-link">Dashboard</a>
                    <a href="{{ route('affiliate.user.payment_history') }}" class="tab-link active">Payments</a>
                    <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="tab-link">Withdraw requests</a>
                    <a href="{{ route('affiliate.user.payment_settings') }}" class="tab-link">Payment Settings</a>
                </div>

                <!-- Payments list -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5>Payment History</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Since $affiliate_payments is a relation query builder or collection, let's paginate it or get it
                                        $payments = $affiliate_payments instanceof \Illuminate\Database\Eloquent\Builder 
                                                    ? $affiliate_payments->orderBy('id', 'desc')->paginate(12) 
                                                    : $affiliate_payments;
                                    @endphp
                                    @forelse($payments as $key => $payment)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $payment->created_at->format('d M, Y h:i A') }}</td>
                                            <td class="font-weight-bold text-success">{{ single_price($payment->amount) }}</td>
                                            <td>
                                                <span class="badge badge-light border">
                                                    {{ strtoupper($payment->payment_method) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="fa-solid fa-credit-card fa-2x mb-2 opacity-50"></i><br>
                                                No payments processed yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($payments instanceof \Illuminate\Pagination\LengthAwarePaginator && $payments->hasPages())
                            <div class="px-4 py-3 d-flex justify-content-center">
                                {{ $payments->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
