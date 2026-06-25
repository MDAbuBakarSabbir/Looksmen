<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the user's support tickets.
     */
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Frontend.dashboard.supportTicket', compact('tickets'));
    }

    /**
     * Store a newly created support ticket in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|string|in:low,medium,high',
        ]);

        // Generate unique Ticket ID (e.g. ST-8F92D1)
        $ticketId = 'ST-' . strtoupper(Str::random(6));
        while (SupportTicket::where('ticket_id', $ticketId)->exists()) {
            $ticketId = 'ST-' . strtoupper(Str::random(6));
        }

        SupportTicket::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticketId,
            'subject' => $request->subject,
            'details' => $request->description,
            'priority' => $request->priority ?? 'medium',
            'status' => 'open',
        ]);

        return redirect()->route('supportTicket')->with('success', 'Support Ticket created successfully!');
    }
}
