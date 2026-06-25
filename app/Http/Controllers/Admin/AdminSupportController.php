<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminSupportController extends Controller
{
    /* =========================================================================
     * SUPPORT TICKETS METHODS
     * ======================================================================= */

    /**
     * Display a listing of all support tickets.
     */
    public function tickets(Request $request)
    {
        $status = $request->status;
        $priority = $request->priority;

        $query = SupportTicket::with('user')->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }
        if ($priority) {
            $query->where('priority', $priority);
        }

        $tickets = $query->paginate(15);

        return view('adminDash.support.tickets', compact('tickets', 'status', 'priority'));
    }

    /**
     * Update the status or priority of a support ticket.
     */
    public function updateTicket(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'status' => 'nullable|string|in:open,pending,resolved,closed',
            'priority' => 'nullable|string|in:low,medium,high',
        ]);

        if ($request->has('status')) {
            $ticket->status = $request->status;
        }
        if ($request->has('priority')) {
            $ticket->priority = $request->priority;
        }

        $ticket->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket updated successfully!',
                'ticket' => $ticket
            ]);
        }

        return back()->with('success', 'Ticket updated successfully!');
    }

    /* =========================================================================
     * CHAT SYSTEM METHODS
     * ======================================================================= */

    /**
     * Display the WhatsApp style chat dashboard.
     */
    public function chatDashboard(Request $request)
    {
        // If coming from ticket "Open Chat" link, a user_id may be passed
        $selectedUserId = $request->query('user_id');
        
        return view('adminDash.support.chat', compact('selectedUserId'));
    }

    /**
     * Get list of users with chat histories, sorted by last message time, including unread counts.
     */
    public function getChatUsers(Request $request)
    {
        $search = $request->search;
        $adminId = Auth::guard('admin')->id() ?? 1;

        // Get subquery of latest message per user to enable sorting
        $lastMessagesSub = ChatMessage::select('sender_id', 'receiver_id', 'message', 'file_path', 'created_at', 'sender_type')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('chat_messages')
                    ->groupBy(DB::raw('CASE WHEN sender_type = "user" THEN sender_id ELSE receiver_id END'));
            });

        // Query users
        $usersQuery = User::select('users.id', 'users.name', 'users.email');

        if ($search) {
            $usersQuery->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->get()->map(function ($user) use ($adminId) {
            // Find last message exchanged with this user
            $lastMessage = ChatMessage::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->where('sender_type', 'user');
            })->orWhere(function($q) use ($user) {
                $q->where('receiver_id', $user->id)->where('receiver_type', 'user');
            })
            ->latest()
            ->first();

            if (!$lastMessage && !$user->chats_count) {
                // If there's no chat history and we are not searching, we might filter them out or keep them.
                // For WhatsApp, we list users who have at least one message.
                return null;
            }

            // Get unread count from this user to admin
            $unreadCount = ChatMessage::where('sender_id', $user->id)
                ->where('sender_type', 'user')
                ->where('receiver_type', 'admin')
                ->where('is_read', false)
                ->count();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->profile_pic ? asset('uploads/profile/' . $user->profile_pic) : null,
                'last_message' => $lastMessage ? ($lastMessage->message ?? '[Attachment]') : '',
                'last_message_time' => $lastMessage ? $lastMessage->created_at->toISOString() : null,
                'last_message_timestamp' => $lastMessage ? $lastMessage->created_at->timestamp : 0,
                'unread_count' => $unreadCount
            ];
        })->filter()->values();

        // Sort users by last message timestamp desc
        $sortedUsers = $users->sortByDesc('last_message_timestamp')->values();

        return response()->json([
            'success' => true,
            'users' => $sortedUsers
        ]);
    }

    /**
     * Get all chat messages for a specific user.
     */
    public function getUserMessages($userId)
    {
        $adminId = Auth::guard('admin')->id() ?? 1;

        $messages = ChatMessage::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)->where('sender_type', 'user');
        })->orWhere(function ($query) use ($userId) {
            $query->where('receiver_id', $userId)->where('receiver_type', 'user');
        })
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark user's messages to admin as read
        ChatMessage::where('sender_id', $userId)
            ->where('sender_type', 'user')
            ->where('receiver_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Send a response chat message from admin to a user.
     */
    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // max 10MB
        ]);

        if (!$request->message && !$request->hasFile('file')) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send an empty message.'
            ], 422);
        }

        $adminId = Auth::guard('admin')->id() ?? 1;
        $userId = $request->user_id;

        $filePath = null;
        $fileName = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $fileName);
            
            $uploadPath = public_path('uploads/chat');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $safeName);
            $filePath = 'uploads/chat/' . $safeName;
            
            $mime = $file->getClientMimeType();
            if (str_starts_with($mime, 'image/')) {
                $fileType = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $fileType = 'video';
            } elseif ($mime === 'application/pdf') {
                $fileType = 'pdf';
            } else {
                $fileType = 'document';
            }
        }

        $chatMessage = ChatMessage::create([
            'sender_id' => $adminId,
            'sender_type' => 'admin',
            'receiver_id' => $userId,
            'receiver_type' => 'user',
            'message' => $request->message,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => $chatMessage
        ]);
    }
}
