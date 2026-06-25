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
        return view('Frontend.dashboard.conversation');
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
            ->update(['is_read' => true]);

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
        
        // Default receiver: First Admin in the system or ID 1
        $admin = Admins::where('role_id', 'admin')->first() ?? Admins::first();
        $adminId = $admin ? $admin->id : 1;

        $filePath = null;
        $fileName = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $fileName);
            
            // Ensure directory exists
            $uploadPath = public_path('uploads/chat');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $safeName);
            $filePath = 'uploads/chat/' . $safeName;
            
            // Categorize file type
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
