@php
    use App\Models\Address;
    use App\Models\District;

    $addresses = Address::where('user_id', Auth::user()->id)
        ->with('district')
        ->get();
    $districts = District::where('status', 1)->with('thanas')->get();
@endphp

@extends('layouts.Frontend.master')

@section('title')
    ACCOUNT DASHBOARD
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

    .dash-metric-card {
        background: var(--dash-surface);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .dash-metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .metric-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        margin-right: 20px;
        flex-shrink: 0;
    }

    .metric-bg-1 { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
    .metric-bg-2 { background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%); }
    .metric-bg-3 { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    
    .metric-info h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dash-text);
        line-height: 1.2;
    }
    
    .metric-info p {
        margin: 0;
        color: var(--dash-muted);
        font-size: 0.95rem;
        font-weight: 500;
    }

    .address-section-card {
        background: var(--dash-surface);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid var(--dash-border);
        overflow: hidden;
    }

    .address-section-header {
        background: #ffffff;
        padding: 20px 24px;
        border-bottom: 1px solid var(--dash-border);
    }

    .address-section-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--dash-text);
    }

    .address-card-modern {
        background: #ffffff;
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 20px;
        position: relative;
        transition: all 0.2s;
        height: 100%;
    }

    .address-card-modern.is-default {
        border-color: var(--dash-primary);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.1);
        background: #fdfdff;
    }

    .address-actions {
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .address-actions button {
        background: transparent;
        border: none;
        color: var(--dash-muted);
        font-size: 1.2rem;
        cursor: pointer;
    }

    .add-new-address {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        height: 100%;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--dash-muted);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none !important;
        background: transparent;
    }

    .add-new-address:hover {
        border-color: var(--dash-primary);
        color: var(--dash-primary);
        background: rgba(99, 102, 241, 0.05);
    }

    .dash-modal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .dash-modal .modal-header {
        border-bottom: 1px solid var(--dash-border);
        padding: 20px 24px;
    }
    
    .dash-modal .modal-title {
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
    }
    
    .dash-modal .form-control {
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        padding: 10px 14px;
    }

    .dash-modal .form-control:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .dash-modal label {
        font-weight: 500;
        color: var(--dash-text);
        margin-bottom: 8px;
    }
</style>

<section class="dash-section">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('Frontend.dashboard.partials.usersideNav')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <h3 class="fw-700 mb-4" style="color: #1e293b; font-family: 'Outfit', sans-serif;">My Dashboard</h3>
                
                <!-- Metric Cards -->
                <div class="row mb-4">
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-1">
                                <i class="las la-shopping-cart"></i>
                            </div>
                            <div class="metric-info">
                                <h3>{{ \App\Models\Cart::where('user_id', auth()->id())->count() }}</h3>
                                <p>Products in Cart</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-2">
                                <i class="la la-heart"></i>
                            </div>
                            <div class="metric-info">
                                <h3>1</h3>
                                <p>In Wishlist</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="dash-metric-card">
                            <div class="metric-icon metric-bg-3">
                                <i class="las la-box"></i>
                            </div>
                            <div class="metric-info">
                                <h3>{{ \App\Models\Orders::where('user_id', auth()->id())->count() }}</h3>
                                <p>Total Orders</p>
                            </div>
                        </div>
                    </div>

                    @if (\App\Models\FeatureActivation::where('name', 'wallet_system')->first()?->status == '1')
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="dash-metric-card">
                            <div class="metric-icon" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white;">
                                <i class="las la-wallet"></i>
                            </div>
                            <div class="metric-info">
                                <h3>৳{{ number_format(Auth::user()->wallet_balance, 2) }}</h3>
                                <p>Wallet Balance</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (\App\Models\FeatureActivation::where('name', 'point_system')->first()?->status == '1')
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="dash-metric-card">
                            <div class="metric-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                                <i class="las la-coins"></i>
                            </div>
                            <div class="metric-info">
                                <h3>{{ number_format(Auth::user()->points) }} Pts</h3>
                                <p>Club Points</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Address Section -->
                <div class="address-section-card">
                    <div class="address-section-header">
                        <h5>Shipping Addresses</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            @foreach ($addresses as $address)
                                <div class="col-xl-6 col-lg-12 mb-4" id="address-card-{{ $address->id }}">
                                    <div class="address-card-modern {{ $address->set_default == 1 ? 'is-default' : '' }}">
                                        <div class="d-flex align-items-center mb-3">
                                            <input type="radio" name="address_id" value="{{ $address->id }}" class="address-radio mr-3" style="accent-color: var(--dash-primary); width: 18px; height: 18px; cursor: pointer;" onchange="set_default({{ $address->id }})" {{ $address->set_default == 1 ? 'checked' : '' }}>
                                            <h6 class="mb-0 fw-600" style="font-size: 1.1rem; color: #1e293b;">{{ $address->name }}</h6>
                                        </div>
                                        
                                        <div class="pl-4 ml-2">
                                            <div class="text-muted fs-14 mb-2 d-flex align-items-start">
                                                <i class="las la-map-marker fs-18 mr-2 mt-1" style="color: #94a3b8;"></i> 
                                                <span>{{ $address->address }}, <br> {{ $address->thana->name ?? 'N/A' }}, {{ $address->district->name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="text-muted fs-14 mb-3 d-flex align-items-center">
                                                <i class="las la-phone fs-18 mr-2" style="color: #94a3b8;"></i> 
                                                <span>{{ $address->phone }}</span>
                                            </div>

                                            <div class="default-badge-container {{ $address->set_default == 1 ? '' : 'd-none' }}">
                                                <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--dash-primary); padding: 6px 12px; border-radius: 6px; font-weight: 600;">Default Address</span>
                                            </div>
                                        </div>

                                        <div class="dropdown address-actions">
                                            <button type="button" data-toggle="dropdown">
                                                <i class="las la-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                                                <a href="javascript:void(0)" class="dropdown-item py-2" onclick="set_default({{ $address->id }})"><i class="las la-check-circle mr-2"></i> Mark as Default</a>
                                                <a href="javascript:void(0)" class="dropdown-item py-2" onclick="editAddress({{ $address->id }})"><i class="las la-edit mr-2"></i> Edit</a>
                                                <div class="dropdown-divider m-0"></div>
                                                <a href="javascript:void(0)" class="dropdown-item py-2 text-danger" onclick="delete_address({{ $address->id }})"><i class="las la-trash-alt mr-2"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Add New Address Button -->
                            <div class="col-xl-6 col-lg-12 mb-4">
                                <a href="javascript:void(0)" onclick="addAddress()" class="add-new-address">
                                    <i class="las la-plus-circle" style="font-size: 40px; margin-bottom: 12px;"></i>
                                    <span class="fw-600" style="font-size: 1.1rem;">Add New Address</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Address Modal -->
<div class="modal fade dash-modal" id="addressModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add New Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="address_form" method="POST" action="">
                    @csrf
                    <div id="method_field"></div>
                    
                    <div class="form-group mb-3">
                        <label>Receiver Name</label>
                        <input id="address_value" class="form-control" name="receiver_name" placeholder="John Doe">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label>Phone</label>
                        <input type="text" id="phone_value" class="form-control" name="phone" placeholder="+880 1..." required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label>Address</label>
                        <textarea id="address_value" class="form-control" name="address" rows="3" placeholder="House 123, Road 4..." required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label>District</label>
                        <select class="form-control select2" name="district_id" id="district_id" required>
                            <option value="">Select District</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label>Thana</label>
                        <select class="form-control select2" name="thana_id" id="thana_id" required>
                            <option value="">Select District First</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light mr-3" data-dismiss="modal" style="border-radius: 8px; font-weight: 500;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background: var(--dash-primary); border: none; border-radius: 8px; font-weight: 500; padding: 10px 24px;">Save Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // ডিস্ট্রিক্ট পরিবর্তনের লজিক
            $(document).on('change', '#district_id', function() {
                let district_id = $(this).val();
                let thanaSelect = $('#thana_id');

                if (district_id) {
                    $.ajax({
                        url: "{{ url('/get-thanas') }}/" + district_id,
                        type: "GET",
                        success: function(data) {
                            thanaSelect.empty().append('<option value="">Select Thana</option>');
                            $.each(data, function(key, value) {
                                thanaSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            thanaSelect.trigger('change');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        function addAddress() {
            $('#modal_title').text('Add New Address');
            $('#address_form').attr('action', "{{ route('addresses.store') }}"); 
            $('#method_field').html(''); 

            $('#address_value').val('');
            $('#phone_value').val('');
            $('#state_id').val('').selectpicker('refresh');

            $('#addressModal').modal('show');
        }

        function editAddress(id) {
            $('#modal_title').text('Edit Address');
            let url = "{{ url('addresses/update') }}/" + id;
            $('#address_form').attr('action', url);
            $('#method_field').html('<input type="hidden" name="_method" value="POST">');

            $.get("{{ url('addresses/edit') }}/" + id, function(data) {
                $('#address_value').val(data.address);
                $('#phone_value').val(data.phone);
                $('#state_id').val(data.state_id).selectpicker('refresh');
                $('#addressModal').modal('show');
            });
        }

        function set_default(id) {
            $.post('{{ route('addresses.default') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                if (data.status === 'success') {
                    $('.default-badge-container').addClass('d-none');
                    $('.address-radio').prop('checked', false);
                    $('.address-card-modern').removeClass('is-default');

                    let selectedCard = $('#address-card-' + id);
                    selectedCard.find('.address-card-modern').addClass('is-default');
                    selectedCard.find('.default-badge-container').removeClass('d-none');
                    selectedCard.find('.address-radio').prop('checked', true);

                    if (typeof AIZ !== 'undefined') {
                        AIZ.plugins.notify('success', data.message);
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            }).fail(function() {
                alert('Something went wrong!');
            });
        }

        function delete_address(id) {
            Swal.fire({
                title: 'আপনি কি নিশ্চিত?',
                text: "এই ঠিকানাটি চিরতরে মুছে ফেলা হবে!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'হ্যাঁ, মুছে ফেলুন!',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/addresses/destroy') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#address-card-' + id).fadeOut(500, function() {
                                    $(this).remove();
                                });
                                if (typeof AIZ !== 'undefined') {
                                    AIZ.plugins.notify('success', response.message);
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            }
                        },
                        error: function() {
                            alert('কিছু একটা ভুল হয়েছে!');
                        }
                    });
                }
            })
        }
    </script>
@endsection


