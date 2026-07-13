<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Admins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Show the user conversation page.
     */
    public function index()
    {
        $userId = Auth::id();
        
        $hasClosedTicket = \App\Models\SupportTicket::where('user_id', $userId)
            ->where('status', 'closed')
            ->exists();
            
        $hasActiveTicket = \App\Models\SupportTicket::where('user_id', $userId)
            ->whereIn('status', ['open', 'pending'])
            ->exists();

        $ticketClosed = ($hasClosedTicket && !$hasActiveTicket);

        return view('Frontend.dashboard.conversation', compact('ticketClosed'));
    }

    /**
     * Get chat messages between current user and admin.
     */
    public function getMessages()
    {
        $userId = Auth::id();

        // Retrieve messages between this user and admins
        $messages = ChatMessage::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)->where('sender_type', 'user');
        })->orWhere(function ($query) use ($userId) {
            $query->where('receiver_id', $userId)->where('receiver_type', 'user');
        })
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark incoming admin messages as read
        ChatMessage::where('receiver_id', $userId)
            ->where('receiver_type', 'user')
            ->where('is_read', false)
            ->update(['is_read', true]);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Send a new message from the user to the admin.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // max 10MB
        ]);

        if (!$request->message && !$request->hasFile('file')) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send an empty message.'
            ], 422);
        }

        $userId = Auth::id();
        
        // Check if the user has a closed ticket and no active open/pending ticket
        $hasClosedTicket = \App\Models\SupportTicket::where('user_id', $userId)
            ->where('status', 'closed')
            ->exists();
            
        $hasActiveTicket = \App\Models\SupportTicket::where('user_id', $userId)
            ->whereIn('status', ['open', 'pending'])
            ->exists();

        if ($hasClosedTicket && !$hasActiveTicket) {
            return response()->json([
                'success' => false,
                'message' => 'Your support ticket has been closed. You cannot send messages.'
            ], 403);
        }
        
        // Default receiver: First Admin in the system or ID 1
        $admin = Admins::where('role_id', 'admin')->first() ?? Admins::first();
        $adminId = $admin ? $admin->id : 1;

        $filePath = null;
        $fileName = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            
            // Ensure directory exists
            $uploadPath = public_path('Uploads');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Categorize file type & save image as webp
            $mime = $file->getClientMimeType() ?: '';
            if (str_starts_with($mime, 'image/') || in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                $safeName = 'chat_' . time() . '_' . \Illuminate\Support\Str::random(5) . '.webp';
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $image = $manager->decode($file);
                $image->save($uploadPath . '/' . $safeName, quality: 85);
                $fileType = 'image';
            } else {
                $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $fileName);
                $file->move($uploadPath, $safeName);
                if (str_starts_with($mime, 'video/')) {
                    $fileType = 'video';
                } elseif ($mime === 'application/pdf') {
                    $fileType = 'pdf';
                } else {
                    $fileType = 'document';
                }
            }
            $filePath = 'Uploads/' . $safeName;
        }

        $chatMessage = ChatMessage::create([
            'sender_id' => $userId,
            'sender_type' => 'user',
            'receiver_id' => $adminId,
            'receiver_type' => 'admin',
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
