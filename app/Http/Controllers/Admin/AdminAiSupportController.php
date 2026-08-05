<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportChat;
use Illuminate\Http\Request;

class AdminAiSupportController extends Controller
{
    /**
     * Display live chat threads list
     */
    public function index()
    {
        $sessions = SupportChat::select('session_id')
            ->distinct()
            ->with(['user'])
            ->get()
            ->map(function ($chat) {
                $latest = SupportChat::where('session_id', $chat->session_id)->latest()->first();
                $first = SupportChat::where('session_id', $chat->session_id)->first();
                $isTransferred = SupportChat::where('session_id', $chat->session_id)->where('is_transferred', true)->exists();
                $user = $latest->user ?? $first->user ?? null;

                return [
                    'session_id' => $chat->session_id,
                    'user' => $user,
                    'latest_message' => $latest->message ?? '',
                    'latest_time' => $latest ? $latest->created_at->diffForHumans() : '',
                    'timestamp' => $latest ? $latest->created_at->timestamp : 0,
                    'is_transferred' => $isTransferred,
                ];
            })
            ->sortByDesc('timestamp')
            ->values();

        return view('adminDash.support.aiChat', compact('sessions'));
    }

    /**
     * Get chat history for a session
     */
    public function getMessages($sessionId)
    {
        $messages = SupportChat::where('session_id', $sessionId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $firstMsg = $messages->first();
        $user = $firstMsg ? $firstMsg->user : null;
        $isTransferred = SupportChat::where('session_id', $sessionId)->where('is_transferred', true)->exists();

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'user' => $user,
            'is_transferred' => $isTransferred,
            'messages' => $messages,
        ]);
    }

    /**
     * Send Admin Reply
     */
    public function replyMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = $request->input('session_id');
        $messageText = trim($request->input('message'));

        $firstMsg = SupportChat::where('session_id', $sessionId)->first();
        $userId = $firstMsg ? $firstMsg->user_id : null;

        // Ensure session is marked as transferred
        SupportChat::where('session_id', $sessionId)->update(['is_transferred' => true]);

        $reply = SupportChat::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender' => 'admin',
            'message' => $messageText,
            'is_transferred' => true,
        ]);

        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }

    /**
     * Toggle transfer state
     */
    public function toggleTransfer(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'status' => 'required|boolean',
        ]);

        SupportChat::where('session_id', $request->session_id)
            ->update(['is_transferred' => $request->status]);

        return response()->json(['success' => true]);
    }

    /**
     * Close Chat & Delete all messages for session
     */
    public function closeChat(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $sessionId = $request->input('session_id');

        // Delete all chat messages for this session
        SupportChat::where('session_id', $sessionId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chat session closed and history cleared successfully.',
        ]);
    }

    /**
     * AI Training Settings View
     */
    public function settingsIndex()
    {
        $settings = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        return view('adminDash.support.aiSettings', compact('settings'));
    }

    /**
     * Save AI Training Settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'ai_gemini_api_key' => 'nullable|string|max:255',
            'ai_training_knowledge_base' => 'nullable|string|max:10000',
            'ai_assistant_tone' => 'nullable|string|max:255',
            'ai_max_sentences' => 'nullable|string|max:50',
            'ai_transfer_keywords' => 'nullable|string|max:500',
        ]);

        $keys = [
            'ai_gemini_api_key',
            'ai_training_knowledge_base',
            'ai_assistant_tone',
            'ai_max_sentences',
            'ai_transfer_keywords',
        ];

        foreach ($keys as $key) {
            \App\Models\GeneralWebSettings::updateOrCreate(
                ['name' => $key],
                ['value' => $request->input($key, ''), 'status' => 1]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'AI Assistant training knowledge & settings updated successfully!',
        ]);
    }
}
