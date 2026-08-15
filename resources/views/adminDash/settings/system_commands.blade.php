@extends('layouts.Backend.master')

@section('title', 'System Commands')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>System Commands</h4>
            <p class="mb-0">Developer actions and dangerous tools.</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Setup & Config</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">System Commands</a></li>
        </ol>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Maintenance Commands</h4>
            </div>
            <div class="card-body">
                <p>Use these commands to clear caches or optimize the application. Be careful not to run them during peak traffic hours.</p>
                <div class="d-flex flex-wrap mt-3" style="gap: 12px;">
                    <button class="btn btn-primary quick-action-btn" data-url="{{ route('clear.cache') }}" style="border-radius: 8px;">
                        <i class="fa fa-trash me-1"></i> Clear Cache
                    </button>
                    <button class="btn btn-info quick-action-btn" data-url="{{ route('optimize') }}" style="color: white; border-radius: 8px;">
                        <i class="fa fa-bolt me-1"></i> Optimize App
                    </button>
                    <button class="btn btn-warning quick-action-btn" data-url="{{ route('migrate') }}" style="border-radius: 8px;">
                        <i class="fa fa-database me-1"></i> Run Migration
                    </button>
                    <button class="btn btn-success quick-action-btn" data-url="{{ route('seed') }}" style="border-radius: 8px;">
                        <i class="fa fa-seedling me-1"></i> Run Seeder
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-header text-white bg-danger">
                <h4 class="card-title text-white">Danger Zone: Truncate Database Table</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger mb-4">
                    <strong>Warning!</strong> Truncating a table will permanently delete all records within it. This action cannot be undone. Please ensure you have a database backup before proceeding. Critical system tables are excluded from this list.
                </div>
                <form id="truncateTableForm">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold text-dark">Select Table to Truncate:</label>
                        <div class="col-sm-6">
                            <select name="table_name" class="form-control" id="tableSelect" required>
                                <option value="" disabled selected>-- Select a Table --</option>
                                @foreach($tables as $table)
                                    @if(!in_array($table, ['users', 'admins', 'roles', 'permissions', 'migrations', 'general_web_settings', 'feature_activations']))
                                        <option value="{{ $table }}">{{ $table }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger w-100" id="truncateBtn">
                                <i class="fa fa-triangle-exclamation me-1"></i> Clear Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Quick action buttons logic
        $('.quick-action-btn').on('click', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var btn = $(this);
            var originalText = btn.html();
            
            btn.html('<i class="fa fa-spinner fa-spin me-1"></i> Running...');
            btn.prop('disabled', true);
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr) {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong!';
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: msg,
                    });
                }
            });
        });

        // Truncate table logic
        $('#truncateBtn').on('click', function() {
            var selectedTable = $('#tableSelect').val();
            
            if (!selectedTable) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Table Selected',
                    text: 'Please select a table to truncate first.',
                });
                return;
            }

            Swal.fire({
                title: 'Are you absolutely sure?',
                text: "You are about to PERMANENTLY DELETE all data in the '" + selectedTable + "' table! This action CANNOT be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete all data!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Second confirmation
                    Swal.fire({
                        title: 'Final Confirmation',
                        text: "Type '" + selectedTable + "' to confirm the deletion:",
                        input: 'text',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Confirm Truncate',
                        showLoaderOnConfirm: true,
                        preConfirm: (inputValue) => {
                            if (inputValue !== selectedTable) {
                                Swal.showValidationMessage('Table name does not match.');
                            } else {
                                return $.ajax({
                                    url: '{{ route("system-commands.truncate") }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        table_name: selectedTable
                                    }
                                }).then(response => {
                                    if(!response.success) {
                                        throw new Error(response.message);
                                    }
                                    return response;
                                }).catch(error => {
                                    Swal.showValidationMessage(
                                      `Request failed: ${error.responseJSON ? error.responseJSON.message : error.message}`
                                    );
                                });
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((finalResult) => {
                        if (finalResult.isConfirmed) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Truncated!',
                                text: finalResult.value.message
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
