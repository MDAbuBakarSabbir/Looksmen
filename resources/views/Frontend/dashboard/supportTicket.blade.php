@extends('layouts.Frontend.master')
@section('title')
    SUPPORT TICKET
@endsection
@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">

                @include('Frontend.dashboard.partials.usersideNav')

                <div class="aiz-user-panel" style="flex: 1; margin-left: 20px;">
                    <div class="aiz-titlebar mt-2 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="h3">Support Tickets</h1>
                            </div>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row gutters-10">
                        <div class="col-lg">
                            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                    <h5 class="mb-0 font-weight-bold text-dark">Your Tickets</h5>
                                    <a onclick="createSupport()" href="javascript:void(0)"
                                        class="btn btn-primary btn-sm rounded-pill px-4">Create Ticket</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Ticket ID</th>
                                                    <th>Subject</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                    <th>Date Created</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($tickets as $ticket)
                                                    <tr>
                                                        <td><span class="font-weight-bold text-primary">{{ $ticket->ticket_id }}</span></td>
                                                        <td>{{ $ticket->subject }}</td>
                                                        <td>
                                                            @if($ticket->priority == 'high')
                                                                <span class="badge badge-danger">High</span>
                                                            @elseif($ticket->priority == 'medium')
                                                                <span class="badge badge-warning text-white">Medium</span>
                                                            @else
                                                                <span class="badge badge-info">Low</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($ticket->status == 'open')
                                                                <span class="badge badge-success">Open</span>
                                                            @elseif($ticket->status == 'pending')
                                                                <span class="badge badge-warning text-white">Pending</span>
                                                            @elseif($ticket->status == 'resolved')
                                                                <span class="badge badge-info">Resolved</span>
                                                            @else
                                                                <span class="badge badge-secondary">Closed</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('conversation') }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">
                                                                <i class="las la-comment mr-1"></i> Chat
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-4">No support tickets found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="SupportTicketModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title font-weight-bold text-dark" id="modal_title">Create Support Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <form id="supportTicketForm" method="POST" action="{{ route('supportTicket.store') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-muted small">Subject</label>
                            <input class="form-control" name="subject" required placeholder="e.g. Missing refund payment">
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-muted small">Priority</label>
                            <select class="form-control" name="priority">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small">Description</label>
                            <textarea class="form-control" name="description" rows="4" required placeholder="Detail your issue here..."></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light mr-2 rounded-pill px-4" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function createSupport() {
            $('#SupportTicketModal').modal('show');
        }
    </script>
@endsection


