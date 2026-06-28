@extends('layouts.Backend.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="las la-cog mr-2"></i>Club Points System Settings</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.wallet.points-config.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label class="font-weight-600 text-dark">Point Conversion Rate</label>
                        <div class="input-group">
                            <input type="number" name="point_conversion_rate" min="1" class="form-control" value="{{ $point_conversion_rate->value ?? 100 }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold" style="background:#f1f5f9;">Points = ৳1.00 BDT</span>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">Specify how many Club Points are required to get ৳1.00 BDT in wallet balance.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-600 text-dark">Default Points Earning Rate</label>
                        <div class="input-group">
                            <input type="number" name="points_per_taka" min="0" step="any" class="form-control" value="{{ $points_per_taka->value ?? 0.1 }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold" style="background:#f1f5f9;">Points per ৳1.00 Spent</span>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">Fallback calculation: how many points customers earn per ৳1.00 spent on items that do not have custom point allocations set.</small>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold" style="border-radius:6px;">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
