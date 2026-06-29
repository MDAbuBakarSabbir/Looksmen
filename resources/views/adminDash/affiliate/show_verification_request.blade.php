@extends('layouts.Backend.master')
@section('title')
    AFFILIATE USER VERIFICATION DETAILS
@endsection
@section('content')
    <style>
        .details-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }
        .details-section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            border-bottom: 2px solid #6366f1;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .info-label {
            font-size: 12px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 18px;
        }
        .table-custom-details th {
            font-weight: 700;
            font-size: 13px;
            color: #4b5563;
            background-color: #f9fafb !important;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
        }
        .table-custom-details td {
            padding: 12px 16px;
            font-size: 14px;
            color: #1f2937;
            font-weight: 600;
            border: 1px solid #e5e7eb;
        }
        .action-btn-custom {
            border-radius: 8px;
            font-weight: 700;
            padding: 10px 24px;
            font-size: 14px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="details-card card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-file-shield text-primary mr-2"></i>Affiliate Verification Request</h4>
                    <a class="btn btn-secondary btn-sm" href="{{ route('affiliate_users.index') }}" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;"><i class="fa-solid fa-arrow-left mr-2"></i>Back</a>
                </div>
                <div class="card-body p-4 row">
                    <div class="col-md-5 border-right border-light pr-md-4">
                        <div class="details-section-title">User Account Info</div>
                        
                        <div class="info-label">Customer Name</div>
                        <div class="info-value">{{ $affiliate_user->user->name }}</div>

                        <div class="info-label">Email Address</div>
                        <div class="info-value">{{ $affiliate_user->user->email }}</div>

                        <div class="info-label">Phone Number</div>
                        <div class="info-value">{{ $affiliate_user->user->phone ?? 'N/A' }}</div>

                        <div class="info-label">Postal Address</div>
                        <div class="info-value">{{ $affiliate_user->user->address ?? 'No address registered' }}</div>
                    </div>
                    
                    <div class="col-md-7 pl-md-4 mt-4 mt-md-0">
                        <div class="details-section-title">Submitted Form Details</div>
                        @if ($affiliate_user->informations != null)
                            <table class="table table-custom-details mb-4">
                                <tbody>
                                    @foreach (json_decode($affiliate_user->informations) as $info)
                                        <tr>
                                            <th style="width: 40%;">{{ $info->label }}</th>
                                            <td>
                                                @if ($info->type == 'text' || $info->type == 'select' || $info->type == 'radio')
                                                    {{ $info->value }}
                                                @elseif ($info->type == 'multi_select')
                                                    {{ implode(json_decode($info->value), ', ') }}
                                                @elseif ($info->type == 'file')
                                                    <a href="{{ asset($info->value) }}" target="_blank" class="btn btn-primary btn-sm" style="border-radius: 6px; font-weight: 600;">
                                                        <i class="fa-solid fa-download mr-1"></i>View Document
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-warning py-3 text-center" style="border-radius: 8px; font-weight: 600;">
                                No verification information details submitted.
                            </div>
                        @endif

                        <div class="d-flex align-items-center justify-content-end gap-3 mt-4 pt-3 border-top border-light">
                            <a href="{{ route('affiliate_user.reject', $affiliate_user->id) }}" class="action-btn-custom btn btn-outline-danger mr-2" onclick="return confirm('Are you sure you want to reject this verification request?');">
                                <i class="fa-solid fa-circle-xmark mr-1"></i>Reject Request
                            </a>
                            <a href="{{ route('affiliate_user.approve', $affiliate_user->id) }}" class="action-btn-custom btn btn-success" style="background: linear-gradient(135deg, #10b981, #059669); border: none;" onclick="return confirm('Are you sure you want to approve this affiliate user?');">
                                <i class="fa-solid fa-circle-check mr-1"></i>Accept & Approve
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
