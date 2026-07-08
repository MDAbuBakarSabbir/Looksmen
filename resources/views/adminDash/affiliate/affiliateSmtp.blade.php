@extends('layouts.Backend.master')
@section('title','Affiliate SMTP Configuration')
    
@section('content')
<style>
    .settings-card {
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.05);
        height: 100%;
    }
    .template-item {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .template-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        transform: translateY(-2px);
    }
    .template-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .template-title {
        font-size: 14px;
        font-weight: 700;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .textarea-custom {
        width: 100%;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 14px;
        border: 1px solid #cbd5e1;
        color: #1f2937;
        resize: vertical;
        min-height: 110px;
        transition: all 0.2s ease;
        background-color: #f8fafc;
    }
    .textarea-custom:focus {
        background-color: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        outline: none;
    }
    .template-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 12px;
    }
    .btn-save-template {
        font-size: 13px;
        font-weight: 600;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #ffffff;
        border: none;
        padding: 8px 18px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
    }
    .btn-save-template:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 10px -1px rgba(99, 102, 241, 0.3);
        color: #ffffff;
    }
    .btn-save-template:active {
        transform: translateY(0);
    }
    
    /* Modern Switch Styles */
    .switch-custom {
        position: relative;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        user-select: none;
        margin-bottom: 0;
        gap: 8px;
    }
    .switch-custom input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .switch-slider {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 22px;
        background-color: #cbd5e1;
        border-radius: 34px;
        transition: background-color 0.2s ease;
    }
    .switch-slider::before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    .switch-custom input:checked + .switch-slider {
        background-color: #6366f1;
    }
    .switch-custom input:checked + .switch-slider::before {
        transform: translateX(18px);
    }
    .switch-text {
        font-size: 13px;
        font-weight: 600;
        color: #4b5563;
    }
</style>

<div class="row">
    {{-- Onboarding Templates --}}
    <div class="col-lg-6 mb-4">
        <div class="settings-card card border-0">
            <div class="card-header bg-white border-bottom border-light p-4">
                <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-user-plus text-primary mr-2"></i>Affiliate Onboarding</h4>
            </div>
            <div class="card-body p-4">
                {{-- Verification Mail --}}
                <div class="template-item">
                    <div class="template-header">
                        <span class="template-title"><i class="fa-solid fa-envelope-circle-check text-indigo"></i> Verification Mail Template</span>
                        <label class="switch-custom mb-0">
                            <input type="checkbox" name="verifyMailActive" id="verifyMailActive" checked>
                            <span class="switch-slider"></span>
                            <span class="switch-text">Active</span>
                        </label>
                    </div>
                    <textarea name="verifyMail" id="verifyMail" class="textarea-custom" placeholder="Write verification email content..."></textarea>
                    <div class="template-footer">
                        <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                    </div>
                </div>

                {{-- Registration Mail --}}
                <div class="template-item">
                    <div class="template-header">
                        <span class="template-title"><i class="fa-solid fa-id-card text-indigo"></i> Registration Mail Template</span>
                        <label class="switch-custom mb-0">
                            <input type="checkbox" name="registerMailActive" id="registerMailActive" checked>
                            <span class="switch-slider"></span>
                            <span class="switch-text">Active</span>
                        </label>
                    </div>
                    <textarea name="registerMail" id="registerMail" class="textarea-custom" placeholder="Write registration success email content..."></textarea>
                    <div class="template-footer">
                        <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Operations & Finance --}}
    <div class="col-lg-6 mb-4">
        <div class="settings-card card border-0">
            <div class="card-header bg-white border-bottom border-light p-4">
                <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-gears text-primary mr-2"></i>Affiliate Management</h4>
            </div>
            <div class="card-body p-4">
                {{-- Approval Mail --}}
                <div class="template-item">
                    <div class="template-header">
                        <span class="template-title"><i class="fa-solid fa-square-check text-indigo"></i> Approval Mail Template</span>
                        <label class="switch-custom mb-0">
                            <input type="checkbox" name="approveMailActive" id="approveMailActive" checked>
                            <span class="switch-slider"></span>
                            <span class="switch-text">Active</span>
                        </label>
                    </div>
                    <textarea name="approveMail" id="approveMail" class="textarea-custom" placeholder="Write approval email content..."></textarea>
                    <div class="template-footer">
                        <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                    </div>
                </div>

                {{-- Payment Mail --}}
                <div class="template-item">
                    <div class="template-header">
                        <span class="template-title"><i class="fa-solid fa-money-bill-transfer text-indigo"></i> Payment Mail Template</span>
                        <label class="switch-custom mb-0">
                            <input type="checkbox" name="paymentMailActive" id="paymentMailActive" checked>
                            <span class="switch-slider"></span>
                            <span class="switch-text">Active</span>
                        </label>
                    </div>
                    <textarea name="paymentMail" id="paymentMail" class="textarea-custom" placeholder="Write payment release email content..."></textarea>
                    <div class="template-footer">
                        <button type="button" class="btn-save-template"><i class="fa-solid fa-floppy-disk"></i> Save Template</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection