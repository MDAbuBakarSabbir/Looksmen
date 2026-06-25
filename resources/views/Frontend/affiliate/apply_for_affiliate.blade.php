@extends('layouts.Frontend.master')

@section('title')
    APPLY FOR AFFILIATE
@endsection

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    .apply-section {
        font-family: 'Outfit', sans-serif !important;
        background: #f8fafc;
        padding: 50px 0;
    }

    .form-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        padding: 40px;
    }

    .form-label-premium {
        font-weight: 600;
        font-size: 14px;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control-premium {
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        padding: 12px 16px;
        font-size: 14.5px;
        transition: all 0.3s;
    }

    .form-control-premium:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .btn-apply-submit {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 28px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-apply-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        color: white;
    }
</style>

<div class="apply-section">
    <div class="container" style="max-width: 800px;">
        <div class="text-center mb-5">
            <h2 class="font-weight-bold">Affiliate Registration Form</h2>
            <p class="text-muted">Register as our affiliate partner by completing the verification steps below.</p>
        </div>

        <div class="form-card">
            <form action="{{ route('affiliate.apply.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section 1: Account Information (For guest visitors) -->
                @if (!Auth::check())
                    <h4 class="font-weight-bold text-dark mb-4 border-bottom pb-2"><i class="fa-solid fa-user-plus mr-2 text-primary"></i>1. Create Partner Account</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Full Name</label>
                            <input type="text" name="name" class="form-control form-control-premium" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-premium" placeholder="Enter email address" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Password</label>
                            <input type="password" name="password" class="form-control form-control-premium" placeholder="Choose secure password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-premium" placeholder="Repeat chosen password" required>
                        </div>
                    </div>
                    <div class="mb-4"></div>
                @endif

                <!-- Section 2: Verification Details -->
                <h4 class="font-weight-bold text-dark mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-address-card mr-2 text-primary"></i>
                    {{ !Auth::check() ? '2. Verification Information' : 'Partner Verification details' }}
                </h4>

                @php
                    $verificationConfig = \App\Models\AffiliateConfig::where('type', 'verification_form')->first();
                    $formFields = $verificationConfig ? json_decode($verificationConfig->value) : [];
                @endphp

                @forelse ($formFields as $key => $field)
                    <div class="form-group mb-4">
                        <label class="form-label-premium">{{ $field->label }}</label>

                        @if ($field->type == 'text')
                            <input type="text" name="element_{{ $key }}" class="form-control form-control-premium" placeholder="Enter details" required>
                        
                        @elseif ($field->type == 'select')
                            <select name="element_{{ $key }}" class="form-control form-control-premium" required>
                                <option value="">Select option</option>
                                @if (is_array(json_decode($field->options)))
                                    @foreach (json_decode($field->options) as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                @endif
                            </select>

                        @elseif ($field->type == 'multi_select')
                            <select name="element_{{ $key }}[]" class="form-control form-control-premium" multiple required style="min-height: 100px;">
                                @if (is_array(json_decode($field->options)))
                                    @foreach (json_decode($field->options) as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted mt-1 d-block"><i class="fa-solid fa-circle-info mr-1"></i>Hold Ctrl (Cmd on Mac) to select multiple choices.</small>

                        @elseif ($field->type == 'radio')
                            <div class="mt-2 pl-1">
                                @if (is_array(json_decode($field->options)))
                                    @foreach (json_decode($field->options) as $idx => $option)
                                        <div class="custom-control custom-radio custom-control-inline mr-4">
                                            <input type="radio" id="radio_{{ $key }}_{{ $idx }}" name="element_{{ $key }}" value="{{ $option }}" class="custom-control-input" required>
                                            <label class="custom-control-label" for="radio_{{ $key }}_{{ $idx }}">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                        @elseif ($field->type == 'file')
                            <div class="custom-file">
                                <input type="file" name="element_{{ $key }}" class="custom-file-input" id="file_{{ $key }}" required>
                                <label class="custom-file-label" for="file_{{ $key }}">Choose file</label>
                            </div>
                            <script>
                                document.getElementById("file_{{ $key }}").addEventListener("change", function(e) {
                                    var fileName = e.target.files[0].name;
                                    var nextSibling = e.target.nextElementSibling;
                                    nextSibling.innerText = fileName;
                                });
                            </script>
                        @endif
                    </div>
                @empty
                    <div class="alert alert-info border-0 rounded-lg p-3">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i>No custom verification fields are configured. Click submit directly to apply.
                    </div>
                @endforelse

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-apply-submit w-100 py-3"><i class="fa-solid fa-paper-plane mr-2"></i>Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

