@extends('layouts.AdminLays.master')


@section('content')

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 h6">Basic Affiliate</h6>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('affiliate.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <input type="hidden" name="type" value="user_registration_first_purchase">
                        <div class="col-lg-4">
                            <label class="control-label">User Registration & First Purchase</label>
                        </div>
                        <div class="col-lg-6">
                            @php
                            $percentage = null;
                            $status = 0;
                            $first_purchase_option = \App\Models\AffiliateOption::where('type', 'user_registration_first_purchase')->first();
                            if($first_purchase_option != null){
                                $percentage = $first_purchase_option->percentage;
                                $status = $first_purchase_option->status;
                            }
                            @endphp
                            <input type="number" min="0" step="0.01" max="100" class="form-control" name="percentage" value="{{ $percentage }}" placeholder="Percentage of Order Amount" required>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label">%</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="control-label">Status</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="status" type="checkbox" @if ($status)
                                       checked
                                       @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6">Product Sharing Affiliate</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('affiliate.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <input type="hidden" name="type" value="product_sharing">
                        <label class="col-lg-3 col-from-label">Product Sharing and Purchasing</label>
                        <div class="col-lg-6">
                            @php
                            $commission_product_sharing = null;
                            $commission_type_product_sharing = null;
                            $status = 0;
                            $product_sharing_option = \App\Models\AffiliateOption::where('type', 'product_sharing')->first();
                            if($product_sharing_option != null){
                                $status = $product_sharing_option->status;
                                if($product_sharing_option->details != null){
                                    $details = json_decode($product_sharing_option->details);
                                    $commission_product_sharing = $details->commission ?? null;
                                    $commission_type_product_sharing = $details->commission_type ?? null;
                                }
                            }
                            @endphp
                            <input type="number" min="0" step="0.01" max="100" class="form-control" name="amount" value="{{ $commission_product_sharing }}" placeholder="Percentage of Order Amount" required>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control aiz-selectpicker" name="amount_type">
                                <option value="amount" @if ($commission_type_product_sharing == "amount") selected @endif>$</option>
                                <option value="percent" @if ($commission_type_product_sharing == "percent") selected @endif>%</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="control-label">Status</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="status" type="checkbox" @if ($status) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6">Product Sharing Affiliate (Category Wise)</h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('affiliate.store') }}" method="POST">
                    @csrf
                    @php
                    $category_wise_affiliate_status = 0;
                    $cat_option = \App\Models\AffiliateOption::where('type', 'category_wise_affiliate')->first();
                    if($cat_option != null){
                        $category_wise_affiliate_status = $cat_option->status;
                    }
                    @endphp
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="control-label">Status</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="status" type="checkbox" @if ($category_wise_affiliate_status) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    @if (\App\Models\AffiliateOption::where('type', 'category_wise_affiliate')->first() != null)
                        <input type="hidden" name="type" value="category_wise_affiliate">
                        @foreach (\App\Models\Category::all() as $key => $category)
                            @php
                                $found = false;
                            @endphp
                            @if(\App\Models\AffiliateOption::where('type', 'category_wise_affiliate')->first()->details != null)
                                @foreach (json_decode(\App\Models\AffiliateOption::where('type', 'category_wise_affiliate')->first()->details) as $key => $data)
                                    @if($data->category_id == $category->id)
                                        @php
                                            $found = true;
                                            $value = $data;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            @if ($found)
                                <div class="form-group row">
                                    <div class="col-lg-5">
                                        <input type="hidden" name="categories_id_{{ $value->category_id }}" value="{{ $value->category_id }}">
                                        <input type="text" class="form-control" value="{{ \App\Models\Category::find($value->category_id)->category_name }}" readonly>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="number" min="0" step="0.01" class="form-control" name="commison_amounts_{{ $value->category_id }}" value="{{ $value->commission }}">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control aiz-selectpicker" name="commison_types_{{ $value->category_id }}">
                                            <option value="amount" @if($value->commission_type == 'amount') selected @endif>$</option>
                                            <option value="percent" @if($value->commission_type == 'percent') selected @endif>%</option>
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="form-group row">
                                    <div class="col-lg-5">
                                        <input type="hidden" name="categories_id_{{ $category->id }}" value="{{ $category->id }}">
                                        <input type="text" class="form-control" value="{{ $category->category_name }}" readonly>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="number" min="0" step="0.01" class="form-control" name="commison_amounts_{{ $category->id }}" value="0">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control aiz-selectpicker" name="commison_types_{{ $category->id }}">
                                            <option value="amount">$</option>
                                            <option value="percent">%</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-gray-light">
            <div class="card-header">
                <h5 class="mb-0 h6">
                    <i>N:B: You can not enable Single Product Sharing Affiliate and Category Wise Affiliate at a time.</i>
                </h5>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-gray-light">
            <div class="card-header">
                <h3 class="mb-0 h6">Affiliate Link Validatin Time (Days)</h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('affiliate.configs.store') }}" method="POST">
                    @csrf
                    @php
                        $validation_time_info = \App\Models\AffiliateConfig::where('type', 'validation_time')->first();
                        $validation_time = '';
                        if($validation_time_info) {
                            $validation_time = $validation_time_info->value;
                        }
                    @endphp
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <input type="hidden" class="form-control" name="type" value="validation_time">
                            <label class="control-label">Validation Time</label>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="No of Days" name="validation_time" value="{{$validation_time}}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Days</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
