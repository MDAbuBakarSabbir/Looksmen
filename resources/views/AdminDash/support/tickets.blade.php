@extends('layouts.AdminLays.master')
@section('title')
    SUPPORT TICKETS
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark mb-0">Support Tickets</h3>
                </div>
                
                <!-- Filters -->
                <div class="card-body pb-0 pt-3">
                    <form method="GET" action="{{ route('admin.tickets') }}" class="row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label class="form-label small text-muted font-weight-bold">Filter Status</label>
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="open" {{ $status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="resolved" {{ $status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small text-muted font-weight-bold">Filter Priority</label>
                            <select name="priority" class="form-control" onchange="this.form.submit()">
                                <option value="">All Priorities</option>
                                <option value="low" {{ $priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $priority == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('admin.tickets') }}" class="btn btn-light w-100"><i class="fa fa-refresh mr-1"></i> Reset</a>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Customer Info</th>
                                    <th>Subject & details</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr id="ticket-row-{{ $ticket->id }}">
                                        <td>
                                            <span class="font-weight-bold text-primary">{{ $ticket->ticket_id }}</span>
                                        </td>
                                        <td>
                                            @if($ticket->user)
                                                <div class="font-weight-bold text-dark">{{ $ticket->user->name }}</div>
                                                <small class="text-muted">{{ $ticket->user->email }}</small>
                                            @else
                                                <span class="text-muted">Anonymous User</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $ticket->subject }}</div>
                                            <div class="text-muted small text-truncate" style="max-width: 250px;" title="{{ $ticket->details }}">
                                                {{ $ticket->details }}
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm border-0 font-weight-bold text-center priority-select" 
                                                data-id="{{ $ticket->id }}" 
                                                style="width: 100px; border-radius: 20px; 
                                                @if($ticket->priority == 'high') background-color: #fee2e2; color: #ef4444; 
                                                @elseif($ticket->priority == 'medium') background-color: #fef3c7; color: #f59e0b; 
                                                @else background-color: #ecfeff; color: #06b6d4; @endif">
                                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm border-0 font-weight-bold text-center status-select" 
                                                data-id="{{ $ticket->id }}" 
                                                style="width: 110px; border-radius: 20px;
                                                @if($ticket->status == 'open') background-color: #d1fae5; color: #10b981;
                                                @elseif($ticket->status == 'pending') background-color: #fef3c7; color: #f59e0b;
                                                @elseif($ticket->status == 'resolved') background-color: #e0f2fe; color: #0284c7;
                                                @else background-color: #f3f4f6; color: #6b7280; @endif">
                                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </td>
                                        <td>
                                            <span>{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- View Details trigger -->
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-icon" 
                                                    onclick="showDetails('{{ $ticket->ticket_id }}', '{{ addslashes($ticket->user ? $ticket->user->name : 'Anonymous') }}', '{{ addslashes($ticket->subject) }}', '{{ addslashes(preg_replace("/\r|\n/", " ", $ticket->details)) }}')"
                                                    title="View Details" style="border-radius: 20px 0 0 20px;">
                                                    <i class="fa fa-eye"></i>
                                                </button>

                                                <!-- Open Chat Link -->
                                                @if($ticket->user_id)
                                                    <a href="{{ route('admin.chat', ['user_id' => $ticket->user_id]) }}" 
                                                        class="btn btn-sm btn-outline-primary btn-icon" 
                                                        title="Open Chat with Customer" style="border-radius: 0 20px 20px 0;">
                                                        <i class="fa-solid fa-message"></i> Chat
                                                    </a>
                                                @else
                                                    <button class="btn btn-sm btn-outline-primary btn-icon" disabled style="border-radius: 0 20px 20px 0;"><i class="fa-solid fa-message"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">No tickets match your filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-4">
                        {{ $tickets->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Detail Modal -->
    <div class="modal fade" id="TicketDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header bg-light border-0 pt-4">
                    <h5 class="modal-title font-weight-bold text-dark">Ticket <span id="modalTicketId"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted mb-0">Customer</label>
                        <div class="font-weight-bold text-dark" id="modalCustomerName"></div>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted mb-0">Subject</label>
                        <div class="font-weight-bold text-dark" id="modalSubject"></div>
                    </div>
                    <div class="mb-2">
                        <label class="small font-weight-bold text-muted mb-0">Issue Details</label>
                        <p class="text-secondary bg-light p-3 rounded" id="modalDetails" style="white-space: pre-wrap; font-size:14px; line-height: 1.5;"></p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" style="border-radius: 20px;">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Open Modal and Populate
        function showDetails(ticketId, customer, subject, details) {
            $('#modalTicketId').text(ticketId);
            $('#modalCustomerName').text(customer);
            $('#modalSubject').text(subject);
            $('#modalDetails').text(details);
            $('#TicketDetailsModal').modal('show');
        }

        $(document).ready(function() {
            // Update Ticket Priority via AJAX
            $('.priority-select').change(function() {
                const id = $(this).data('id');
                const priority = $(this).val();
                const selectElement = $(this);

                $.ajax({
                    url: `/admin/tickets/${id}/update`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        priority: priority
                    },
                    success: function(response) {
                        if (response.success) {
                            window.Toast.fire({
                                icon: 'success',
                                title: 'Priority updated successfully!'
                            });

                            // Dynamically update background color
                            if (priority === 'high') {
                                selectElement.css({'background-color': '#fee2e2', 'color': '#ef4444'});
                            } else if (priority === 'medium') {
                                selectElement.css({'background-color': '#fef3c7', 'color': '#f59e0b'});
                            } else {
                                selectElement.css({'background-color': '#ecfeff', 'color': '#06b6d4'});
                            }
                        }
                    },
                    error: function() {
                        window.Toast.fire({
                            icon: 'error',
                            title: 'Failed to update priority'
                        });
                    }
                });
            });

            // Update Ticket Status via AJAX
            $('.status-select').change(function() {
                const id = $(this).data('id');
                const status = $(this).val();
                const selectElement = $(this);

                $.ajax({
                    url: `/admin/tickets/${id}/update`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            window.Toast.fire({
                                icon: 'success',
                                title: 'Status updated successfully!'
                            });

                            // Dynamically update background color
                            if (status === 'open') {
                                selectElement.css({'background-color': '#d1fae5', 'color': '#10b981'});
                            } else if (status === 'pending') {
                                selectElement.css({'background-color': '#fef3c7', 'color': '#f59e0b'});
                            } else if (status === 'resolved') {
                                selectElement.css({'background-color': '#e0f2fe', 'color': '#0284c7'});
                            } else {
                                selectElement.css({'background-color': '#f3f4f6', 'color': '#6b7280'});
                            }
                        }
                    },
                    error: function() {
                        window.Toast.fire({
                            icon: 'error',
                            title: 'Failed to update status'
                        });
                    }
                });
            });
        });
    </script>
@endsection
