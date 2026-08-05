<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FeatureActivation;
use App\Models\GeneralWebSettings;
use App\Models\Orders;
use App\Models\SupportChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiSupportController extends Controller
{
    /**
     * Get or initialize session ID for customer chat
     */
    private function getSessionId(Request $request)
    {
        $sessionId = $request->input('session_id') ?: session()->get('ai_support_session_id');
        if (! $sessionId) {
            $sessionId = 'chat_'.Str::random(16);
            session()->put('ai_support_session_id', $sessionId);
        }

        return $sessionId;
    }

    /**
     * Fetch chat history
     */
    public function getHistory(Request $request)
    {
        $features = FeatureActivation::pluck('status', 'name')->toArray();
        if (($features['ai_support'] ?? '0') !== '1') {
            return response()->json(['active' => false]);
        }

        $sessionId = $this->getSessionId($request);
        $userId = Auth::id();

        $query = SupportChat::where('session_id', $sessionId);
        if ($userId) {
            $query->orWhere('user_id', $userId);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        $isTransferred = SupportChat::where(function ($q) use ($sessionId, $userId) {
            $q->where('session_id', $sessionId);
            if ($userId) {
                $q->orWhere('user_id', $userId);
            }
        })->where('is_transferred', true)->exists();

        return response()->json([
            'active' => true,
            'session_id' => $sessionId,
            'is_transferred' => $isTransferred,
            'messages' => $messages,
        ]);
    }

    /**
     * Send user message and get AI response or queue for Admin
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $features = FeatureActivation::pluck('status', 'name')->toArray();
        if (($features['ai_support'] ?? '0') !== '1') {
            return response()->json(['success' => false, 'message' => 'AI Support is disabled.']);
        }

        $sessionId = $this->getSessionId($request);
        $userId = Auth::id();
        $userMsgText = trim($request->input('message'));

        // Check if session is already transferred to Live Admin
        $isAlreadyTransferred = SupportChat::where(function ($q) use ($sessionId, $userId) {
            $q->where('session_id', $sessionId);
            if ($userId) {
                $q->orWhere('user_id', $userId);
            }
        })->where('is_transferred', true)->exists();

        // 1. Save User Message
        $userChat = SupportChat::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender' => 'user',
            'message' => $userMsgText,
            'is_transferred' => $isAlreadyTransferred,
        ]);

        // If already transferred, do not trigger AI. Message remains in Admin queue.
        if ($isAlreadyTransferred) {
            return response()->json([
                'success' => true,
                'is_transferred' => true,
                'user_message' => $userChat,
            ]);
        }

        // 2. Check for Transfer to Human Agent Keywords
        $customKeywordsStr = GeneralWebSettings::where('name', 'ai_transfer_keywords')->value('value');
        $defaultKeywords = ['talk to agent', 'contact agent', 'connect agent', 'human agent', 'live agent', 'talk to human', 'live support', 'admin support', 'agent', 'admin', 'human', 'representative', 'operator'];
        if (! empty($customKeywordsStr)) {
            $customKeywords = array_map('trim', explode(',', strtolower($customKeywordsStr)));
            $transferKeywords = array_filter(array_merge($defaultKeywords, $customKeywords));
        } else {
            $transferKeywords = $defaultKeywords;
        }

        $lowerMsg = strtolower($userMsgText);

        $shouldTransfer = false;
        foreach ($transferKeywords as $keyword) {
            if ($keyword !== '' && str_contains($lowerMsg, $keyword)) {
                $shouldTransfer = true;
                break;
            }
        }

        if ($shouldTransfer) {
            // Mark session as transferred
            SupportChat::where('session_id', $sessionId)->update(['is_transferred' => true]);
            if ($userId) {
                SupportChat::where('user_id', $userId)->update(['is_transferred' => true]);
            }

            $userChat->update(['is_transferred' => true]);

            $aiReplyText = "I've transferred your conversation to our live admin support representative right now. An admin will reply to your message here shortly!";

            $aiChat = SupportChat::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'sender' => 'ai',
                'message' => $aiReplyText,
                'is_transferred' => true,
            ]);

            return response()->json([
                'success' => true,
                'is_transferred' => true,
                'user_message' => $userChat,
                'ai_reply' => $aiChat,
            ]);
        }

        // 3. Generate Response via Google Gemini API or Intelligent Knowledge Base
        $aiReplyText = $this->generateGeminiAiReply($userMsgText, $userId);

        $aiChat = SupportChat::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender' => 'ai',
            'message' => $aiReplyText,
            'is_transferred' => false,
        ]);

        return response()->json([
            'success' => true,
            'is_transferred' => false,
            'user_message' => $userChat,
            'ai_reply' => $aiChat,
        ]);
    }

    /**
     * Explicit request to transfer chat to Admin
     */
    public function transferToAgent(Request $request)
    {
        $sessionId = $this->getSessionId($request);
        $userId = Auth::id();

        SupportChat::where('session_id', $sessionId)->update(['is_transferred' => true]);
        if ($userId) {
            SupportChat::where('user_id', $userId)->update(['is_transferred' => true]);
        }

        $aiReplyText = 'Chat session transferred to live admin support. Please type your inquiry below and an admin representative will assist you!';

        $aiChat = SupportChat::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender' => 'ai',
            'message' => $aiReplyText,
            'is_transferred' => true,
        ]);

        return response()->json([
            'success' => true,
            'is_transferred' => true,
            'ai_reply' => $aiChat,
        ]);
    }

    /**
     * Database Order Lookup Helper for AI Assistant
     */
    private function fetchOrderStatusDetails(string $input, ?int $userId): array
    {
        $extractedPhone = null;
        if (preg_match('/(?:88)?(01[3-9][0-9]{8})/', $input, $pm)) {
            $extractedPhone = $pm[1];
        }

        $extractedCode = null;
        if (preg_match('/(?:#|ORD-?|code:?\s*|order:?\s*|id:?\s*)?([0-9]{3,10})/i', $input, $cm)) {
            $extractedCode = $cm[1];
        }

        $user = Auth::check() ? Auth::user() : null;
        $orders = collect();

        if ($user) {
            $userPhone = preg_replace('/[^0-9]/', '', $user->phone ?? '');
            if (strlen($userPhone) === 13 && str_starts_with($userPhone, '88')) {
                $userPhone = substr($userPhone, 2);
            }

            // If user typed specific phone or order code
            if ($extractedCode || $extractedPhone) {
                $orders = Orders::where(function ($q) use ($extractedCode, $extractedPhone) {
                    if ($extractedCode) {
                        $q->orWhere('code', $extractedCode)
                            ->orWhere('id', $extractedCode)
                            ->orWhere('invoice_id', $extractedCode);
                    }
                    if ($extractedPhone) {
                        $q->orWhere('phone', 'like', '%'.$extractedPhone.'%');
                    }
                })->latest()->take(3)->get();
            }

            // If no specific code matched or typed, query logged in user's latest orders
            if ($orders->isEmpty()) {
                $orders = Orders::where('user_id', $user->id)
                    ->when($userPhone, fn ($q) => $q->orWhere('phone', 'like', '%'.$userPhone.'%'))
                    ->latest()
                    ->take(3)
                    ->get();
            }
        } else {
            // Guest / Non-logged in User
            if ($extractedCode || $extractedPhone) {
                $orders = Orders::where(function ($q) use ($extractedCode, $extractedPhone) {
                    if ($extractedCode) {
                        $q->orWhere('code', $extractedCode)
                            ->orWhere('id', $extractedCode)
                            ->orWhere('invoice_id', $extractedCode);
                    }
                    if ($extractedPhone) {
                        $q->orWhere('phone', 'like', '%'.$extractedPhone.'%');
                    }
                })->latest()->take(3)->get();
            }
        }

        return [
            'is_logged_in' => (bool) $user,
            'extracted_phone' => $extractedPhone,
            'extracted_code' => $extractedCode,
            'orders' => $orders,
        ];
    }

    /**
     * Google Gemini AI API Response Generator
     */
    private function generateGeminiAiReply(string $input, ?int $userId): string
    {
        $settings = GeneralWebSettings::pluck('value', 'name')->toArray();
        $apiKey = trim($settings['ai_gemini_api_key'] ?? '');
        $storeName = $settings['web_name'] ?? config('app.name', 'Looksmen');
        $phone = $settings['contact_phone'] ?? '+8801568482005';
        $email = $settings['contact_email'] ?? 'info@looksmen.com';
        $customKnowledge = trim($settings['ai_training_knowledge_base'] ?? '');

        // Order Database Lookup
        $orderData = $this->fetchOrderStatusDetails($input, $userId);
        $isLoggedIn = $orderData['is_logged_in'];
        $extractedPhone = $orderData['extracted_phone'];
        $extractedCode = $orderData['extracted_code'];
        $orders = $orderData['orders'];

        $lower = strtolower($input);
        $isOrderInquiry = str_contains($lower, 'order') || str_contains($lower, 'track') || str_contains($lower, 'status') || str_contains($lower, 'অর্ডার') || str_contains($lower, 'কোথায়') || str_contains($lower, 'পাব');

        // Build live order database context snippet for AI
        $liveOrderContext = '';
        if ($orders->isNotEmpty()) {
            $liveOrderContext .= "DATABASE RECENT ORDER RECORDS FOUND FOR THIS CUSTOMER:\n";
            foreach ($orders as $ord) {
                $status = ucfirst($ord->delivery_status ?? 'Pending');
                $payStatus = ucfirst($ord->payment_status ?? 'Unpaid');
                $amount = number_format($ord->grand_total ?? 0, 2);
                $code = $ord->code ?? $ord->id;
                $date = $ord->created_at ? $ord->created_at->format('d M Y, h:i A') : 'Recently';

                $liveOrderContext .= "- Order #{$code}: Delivery Status={$status}, Payment Status={$payStatus}, Total Amount=৳{$amount}, Date={$date}\n";
            }
            $liveOrderContext .= "INSTRUCTION: Summarize the order details for the customer politely in Bengali/English. Mention Order ID, Delivery Status, Payment Status, and Total Amount clearly.\n";
        } elseif ($isOrderInquiry) {
            if (! $isLoggedIn && ! $extractedPhone && ! $extractedCode) {
                return 'অর্ডার স্ট্যাটাস জানতে আপনার ১১ ডিজিটের মোবাইল নম্বর (যেমন: 017XXXXXXXX) অথবা অর্ডার কোড/আইডি (যেমন: #1004) মেসেজে লিখুন!';
            } elseif ($extractedPhone || $extractedCode) {
                return 'দুঃখিত, আপনার দেওয়া তথ্য ('.($extractedPhone ?: $extractedCode).') দিয়ে কোনো অর্ডার পাওয়া যায়নি। অনুগ্রহ করে সঠিক মোবাইল নম্বর বা অর্ডার আইডি পুনরায় চেক করে লিখুন!';
            } elseif ($isLoggedIn) {
                return 'আপনার অ্যাকাউন্ট থেকে কোনো অর্ডার পাওয়া যায়নি। আপনি নতুন পণ্য অর্ডার করতে আমাদের ওয়েবসাইট ব্রাউজ করতে পারেন!';
            }
        }

        // If Gemini API Key is configured, make real API call
        if (! empty($apiKey)) {
            try {
                $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key='.$apiKey;

                $aiTone = $settings['ai_assistant_tone'] ?? 'Polite, friendly and helpful tone in Bengali and English';
                $maxSentences = $settings['ai_max_sentences'] ?? '2-3';

                $systemContext = "You are an intelligent, polite AI Customer Support Assistant for the e-commerce store '{$storeName}'.\n"
                    ."Store Contact Phone: {$phone}, Email: {$email}.\n"
                    ."Assistant Tone & Style: {$aiTone}. Max Response Length: {$maxSentences} sentences.\n";

                if (! empty($liveOrderContext)) {
                    $systemContext .= "\n--- LIVE DATABASE ORDER RECORDS FOR THIS CUSTOMER ---\n".$liveOrderContext."-----------------------------------------------------\n";
                }

                if (! empty($customKnowledge)) {
                    $systemContext .= "\n--- STORE TRAINING KNOWLEDGE BASE & FAQs ---\n".$customKnowledge."\n--------------------------------------------\n";
                } else {
                    $systemContext .= "Standard Info: Delivery 24-48 hrs in Dhaka, 2-4 days outside Dhaka. Payments: Cash on Delivery, bKash, Nagad, Cards. 7-day return policy.\n";
                }

                $systemContext .= "\nIf customer asks to speak with a human agent or representative, instruct them politely to type 'Talk to agent'.";

                $response = Http::timeout(10)->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $systemContext."\n\nCustomer question: ".$input],
                            ],
                        ],
                    ],
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $reply = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if (! empty($reply)) {
                        return trim($reply);
                    }
                } else {
                    Log::warning('Gemini API Error: '.$response->body());
                }
            } catch (\Exception $e) {
                Log::error('Gemini API Exception: '.$e->getMessage());
            }
        }

        // Fallback Intelligent Knowledge Engine using Training Knowledge Base directly
        return $this->generateFallbackAiReply($input, $userId, $storeName, $phone, $email, $orders, $customKnowledge);
    }

    /**
     * Fallback Knowledge Engine directly reading AI Training Knowledge Base
     */
    private function generateFallbackAiReply(string $input, ?int $userId, string $storeName, string $phone, string $email, $orders = null, string $customKnowledge = ''): string
    {
        // 1. Order Details
        if (! empty($orders) && $orders->isNotEmpty()) {
            $ord = $orders->first();
            $status = ucfirst($ord->delivery_status ?? 'Pending');
            $payStatus = ucfirst($ord->payment_status ?? 'Unpaid');
            $code = $ord->code ?? $ord->id;

            return "অর্ডার #{$code} স্ট্যাটাস: {$status}\nপেমেন্ট স্ট্যাটাস: {$payStatus}\nমোট মূল্য: ৳".number_format($ord->grand_total ?? 0, 2);
        }

        $lowerInput = strtolower($input);

        // 2. Custom Training Knowledge Base Search
        if (! empty(trim($customKnowledge))) {
            $lines = array_filter(array_map('trim', explode("\n", $customKnowledge)));
            $matchedLines = [];

            // Split input into query keywords
            $inputWords = array_filter(preg_split('/[\s,\?!\.]+/u', $lowerInput), function ($w) {
                return mb_strlen($w) > 1 && ! in_array($w, ['is', 'are', 'the', 'what', 'how', 'when', 'where', 'please', 'tell', 'me', 'about', 'and', 'for', 'এ', 'কি', 'কেমন', 'কোথায়', 'বলুন', 'দিন']);
            });

            foreach ($lines as $line) {
                $lowerLine = strtolower($line);
                foreach ($inputWords as $word) {
                    if (str_contains($lowerLine, $word)) {
                        $matchedLines[] = $line;
                        break;
                    }
                }
            }

            if (! empty($matchedLines)) {
                return implode("\n", array_unique($matchedLines));
            }

            // If no specific line matched, return the training knowledge base points
            return implode("\n", array_slice($lines, 0, 6));
        }

        // 3. Default Store Fallback
        if (str_contains($lowerInput, 'ship') || str_contains($lowerInput, 'delivery') || str_contains($lowerInput, 'charge') || str_contains($lowerInput, 'cost') || str_contains($lowerInput, 'ডেলিভারি')) {
            return "আমাদের ডেলিভারি সংক্রান্ত তথ্য:\n• ঢাকার ভেতরে: ২৪-৪৮ ঘণ্টা\n• ঢাকার বাইরে: ২-৪ কর্মদিবস\n\nডেলিভারি চার্জ চেকআউট পেজে ডিস্ট্রিক্ট অনুযায়ী নির্ধারণ করা হয়।";
        }

        if (str_contains($lowerInput, 'pay') || str_contains($lowerInput, 'bkash') || str_contains($lowerInput, 'cod') || str_contains($lowerInput, 'cash') || str_contains($lowerInput, 'পেমেন্ট')) {
            return 'আমরা ক্যাশ অন ডেলিভারি (COD), বিকাশ, নগদ এবং কার্ড পেমেন্ট গ্রহণ করি!';
        }

        if (str_contains($lowerInput, 'return') || str_contains($lowerInput, 'exchange') || str_contains($lowerInput, 'রিটার্ন')) {
            return 'রিটার্ন পলিসি: ডেলিভারির ৭ দিনের মধ্যে প্রোডাক্ট পরিবর্তন বা ফেরত দিতে পারবেন।';
        }

        if (str_contains($lowerInput, 'hi') || str_contains($lowerInput, 'hello') || str_contains($lowerInput, 'হ্যালো') || str_contains($lowerInput, 'হাই')) {
            $userName = Auth::check() ? Auth::user()->name : 'গ্রাহক';

            return "হ্যালো {$userName}! {$storeName} সাপোর্টে আপনাকে স্বাগতম। কীভাবে আপনাকে সাহায্য করতে পারি?";
        }

        return "ধন্যবাদ {$storeName} এ যোগাযোগের জন্য! আপনার প্রশ্নের সহায়তার জন্য কাস্টমার কেয়ার এজেন্টের সাথে কথা বলতে 'Talk to agent' লিখুন।";
    }
}
