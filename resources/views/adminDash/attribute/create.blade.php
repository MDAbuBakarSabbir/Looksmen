@extends('layouts.Backend.master')
@section('title')
    ATTRIBUTE VALUES: {{ $attribute->name }}
@endsection
@section('content')
    <style>
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
        .value-badge {
            background-color: rgba(99, 102, 241, 0.08);
            color: #4f46e5;
            border: 1px solid rgba(99, 102, 241, 0.15);
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 14px;
            display: inline-block;
        }
    </style>

    <div class="mb-4">
        <a class="btn btn-secondary d-inline-flex align-items-center gap-2" href="{{ route('attribute.index') }}" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;">
            <i class="fa-solid fa-arrow-left"></i> Back to Attributes
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-list-ul text-primary mr-2"></i>{{ $attribute->name }} Values</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 80px;">SL</th>
                                    <th scope="col">Value</th>
                                    <th scope="col" style="width: 120px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attributeValues as $index => $attributeValue)
                                    <tr>
                                        <td class="font-weight-bold">{{ $index + 1 }}</td>
                                        <td>
                                            <span class="value-badge">{{ $attributeValue->value }}</span>
                                        </td>
                                        <td style="text-align: right;">
                                            <a class="btn btn-danger btn-sm" href="{{ route('attribute.value.destroy', $attributeValue->id) }}" style="border-radius: 6px; font-weight: 600; font-size: 12px; padding: 6px 12px;" onclick="return confirm('Are you sure you want to delete this value?');">
                                                <i class="fa-solid fa-trash mr-1"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-circle-exclamation text-muted mb-3" style="font-size: 36px; opacity: 0.5;"></i>
                                            <p class="mb-0 font-weight-bold">No values found for this attribute yet</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-white border-bottom border-light p-4">
                    <h4 class="mb-0 font-weight-bold" style="color: #1f2937;"><i class="fa-solid fa-circle-plus text-success mr-2"></i>Add Value</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('value.store', $attribute->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-muted" style="font-size: 13px;">Value Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="value" placeholder="e.g. XL, Red, 16GB" required style="border-radius: 8px; padding: 10px 14px;">
                        </div>
                        <button type="submit" class="btn btn-success btn-block" style="border-radius: 8px; font-weight: 600; padding: 11px; background: linear-gradient(135deg, #10b981, #059669); border: none;">
                            <i class="fa-solid fa-circle-check mr-2"></i>Submit Value
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
