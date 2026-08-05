<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
                'ticket' => $ticket,
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
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('chat_messages')
                    ->groupBy(DB::raw('CASE WHEN sender_type = "user" THEN sender_id ELSE receiver_id END'));
            });

        // Query users
        $usersQuery = User::select('users.id', 'users.name', 'users.email');

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->get()->map(function ($user) {
            // Find last message exchanged with this user
            $lastMessage = ChatMessage::where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)->where('sender_type', 'user');
            })->orWhere(function ($q) use ($user) {
                $q->where('receiver_id', $user->id)->where('receiver_type', 'user');
            })
                ->latest()
                ->first();

            if (! $lastMessage && ! $user->chats_count) {
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
                'avatar' => $user->profile_pic ? asset('Uploads/'.$user->profile_pic) : null,
                'last_message' => $lastMessage ? ($lastMessage->message ?? '[Attachment]') : '',
                'last_message_time' => $lastMessage ? $lastMessage->created_at->toISOString() : null,
                'last_message_timestamp' => $lastMessage ? $lastMessage->created_at->timestamp : 0,
                'unread_count' => $unreadCount,
            ];
        })->filter()->values();

        // Sort users by last message timestamp desc
        $sortedUsers = $users->sortByDesc('last_message_timestamp')->values();

        return response()->json([
            'success' => true,
            'users' => $sortedUsers,
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
            'messages' => $messages,
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

        if (! $request->message && ! $request->hasFile('file')) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send an empty message.',
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

            $uploadPath = public_path('Uploads');
            if (! file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $mime = $file->getClientMimeType() ?: '';
            if (str_starts_with($mime, 'image/') || in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                $safeName = 'support_'.time().'_'.Str::random(5).'.webp';
                $manager = new ImageManager(new Driver);
                $image = $manager->decode($file);
                $image->save($uploadPath.'/'.$safeName, quality: 85);
                $fileType = 'image';
            } else {
                $safeName = time().'_'.preg_replace('/[^A-Za-z0-9\._-]/', '', $fileName);
                $file->move($uploadPath, $safeName);
                if (str_starts_with($mime, 'video/')) {
                    $fileType = 'video';
                } elseif ($mime === 'application/pdf') {
                    $fileType = 'pdf';
                } else {
                    $fileType = 'document';
                }
            }
            $filePath = 'Uploads/'.$safeName;
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
            'message' => $chatMessage,
        ]);
    }

    public function customMail()
    {
        $usersCount = User::whereNotNull('email')->count();
        $users = User::select('id', 'name', 'email')->orderBy('name', 'asc')->get();
        $smtpSettings = \Illuminate\Support\Facades\Cache::rememberForever('boot_general_web_settings_map', function () {
            return \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        });
        $isSmtpConfigured = !empty($smtpSettings['mailhost']) && !empty($smtpSettings['mailusername']);

        return view('adminDash.support.customMail', compact('usersCount', 'users', 'isSmtpConfigured'));
    }

    /**
     * Send custom email to targeted user(s).
     */
    public function sendCustomMail(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:user,email,all,multiple',
            'user_id' => 'required_if:recipient_type,user|nullable|integer',
            'email' => 'required_if:recipient_type,email|nullable|email',
            'multiple_emails' => 'required_if:recipient_type,multiple|nullable|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $subject = $request->subject;
        $body = $request->body;
        $recipientType = $request->recipient_type;

        $recipients = [];

        if ($recipientType === 'user') {
            $u = User::find($request->user_id);
            if ($u && $u->email) {
                $recipients[] = $u->email;
            }
        } elseif ($recipientType === 'email') {
            $recipients[] = $request->email;
        } elseif ($recipientType === 'multiple') {
            $rawEmails = array_map('trim', explode(',', $request->multiple_emails));
            foreach ($rawEmails as $e) {
                if (filter_var($e, FILTER_VALIDATE_EMAIL)) {
                    $recipients[] = $e;
                }
            }
        } elseif ($recipientType === 'all') {
            $recipients = User::whereNotNull('email')->where('email', '!=', '')->pluck('email')->toArray();
        }

        $recipients = array_unique(array_filter($recipients));

        if (empty($recipients)) {
            return back()->with('error', 'No valid recipient email address found for the selected option.')->withInput();
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($recipients as $toEmail) {
            $userObj = User::where('email', $toEmail)->first();
            $recipientName = $userObj ? $userObj->name : (explode('@', $toEmail)[0] ?? 'Valued Customer');

            $parsedBody = parse_template($body, [
                'name' => $recipientName,
                'email' => $toEmail,
                'site_name' => config('app.name', 'Looksmen'),
                'site_url' => url('/'),
                'date' => date('F j, Y'),
            ]);

            $parsedSubject = parse_template($subject, [
                'name' => $recipientName,
                'site_name' => config('app.name', 'Looksmen'),
            ]);

            $sent = send_custom_mail($toEmail, $parsedSubject, $parsedBody);
            if ($sent) {
                $sentCount++;
            } else {
                $failedCount++;
            }
        }

        if ($sentCount > 0) {
            $msg = "Email successfully dispatched to {$sentCount} recipient(s).";
            if ($failedCount > 0) {
                $msg .= " Failed to send to {$failedCount} recipient(s).";
            }
            return back()->with('success', $msg);
        } else {
            return back()->with('error', 'Failed to dispatch email. Please check your SMTP mail server settings under Settings -> SMTP Settings.')->withInput();
        }
    }

    /**
     * Display the Custom SMS Dispatcher page.
     */
    public function customSMS()
    {
        $users = User::select('id', 'name', 'email')->orderBy('name', 'asc')->get();
        $usersCount = User::count();

        $settings = \Illuminate\Support\Facades\Cache::rememberForever('boot_general_web_settings_map', function () {
            return \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        });

        $isSmsConfigured = !empty($settings['sms_api_key']) || !empty($settings['sms_api_url']);
        $smsProvider = ucfirst($settings['sms_gateway_provider'] ?? 'Generic HTTP API');

        return view('adminDash.support.customSMS', compact('users', 'usersCount', 'isSmsConfigured', 'smsProvider'));
    }

    /**
     * Dispatch custom SMS to targeted phone number(s).
     */
    public function sendCustomSMS(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:user,phone,all,multiple',
            'user_id' => 'required_if:recipient_type,user|nullable',
            'phone' => 'required_if:recipient_type,phone|nullable',
            'multiple_phones' => 'required_if:recipient_type,multiple|nullable|string',
            'message' => 'required|string|max:1000',
        ]);

        $rawMessage = $request->message;
        $recipientType = $request->recipient_type;
        $recipients = [];

        if ($recipientType === 'user') {
            $u = User::find($request->user_id);
            if ($u) {
                $userPhone = $u->phone ?? $u->phone_number ?? $u->mobile ?? null;
                if (!$userPhone && class_exists('App\Models\Orders')) {
                    $lastOrder = \App\Models\Orders::where('user_id', $u->id)->latest()->first();
                    $userPhone = $lastOrder ? $lastOrder->phone : null;
                }
                if ($userPhone) {
                    $recipients[] = [
                        'phone' => $userPhone,
                        'name' => $u->name,
                    ];
                }
            }
        } elseif ($recipientType === 'phone') {
            $recipients[] = [
                'phone' => $request->phone,
                'name' => 'Valued Customer',
            ];
        } elseif ($recipientType === 'multiple') {
            $lines = preg_split('/[\s,\n]+/', $request->multiple_phones);
            foreach ($lines as $line) {
                $cleaned = preg_replace('/[^0-9\+]/', '', trim($line));
                if (!empty($cleaned)) {
                    $recipients[] = [
                        'phone' => $cleaned,
                        'name' => 'Valued Customer',
                    ];
                }
            }
        } elseif ($recipientType === 'all') {
            $phoneList = [];
            if (class_exists('App\Models\Orders')) {
                $phoneList = \App\Models\Orders::whereNotNull('phone')->where('phone', '!=', '')->pluck('phone', 'name')->toArray();
            }
            foreach ($phoneList as $name => $ph) {
                $recipients[] = [
                    'phone' => $ph,
                    'name' => is_string($name) ? $name : 'Valued Customer',
                ];
            }
        }

        if (empty($recipients)) {
            return back()->with('error', 'No valid phone numbers found for the selected option.')->withInput();
        }

        $sentCount = 0;
        $failedCount = 0;
        $processedPhones = [];

        foreach ($recipients as $item) {
            $toPhone = preg_replace('/[^0-9]/', '', $item['phone']);
            if (empty($toPhone) || in_array($toPhone, $processedPhones)) {
                continue;
            }
            $processedPhones[] = $toPhone;

            $parsedMsg = parse_template($rawMessage, [
                'name' => $item['name'] ?: 'Customer',
                'phone' => $toPhone,
                'site_name' => config('app.name', 'Looksmen'),
                'site_url' => url('/'),
                'date' => date('d M, Y'),
            ]);

            $sent = send_custom_sms($toPhone, $parsedMsg);
            if ($sent) {
                $sentCount++;
            } else {
                $failedCount++;
            }
        }

        if ($sentCount > 0) {
            $msg = "SMS successfully dispatched to {$sentCount} recipient(s).";
            if ($failedCount > 0) {
                $msg .= " Failed to deliver to {$failedCount} number(s).";
            }
            return back()->with('success', $msg);
        } else {
            return back()->with('error', 'Failed to dispatch SMS. Please verify your SMS API settings under Settings -> SMS Settings.')->withInput();
        }
    }
}


