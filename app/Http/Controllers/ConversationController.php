<?php

namespace App\Http\Controllers;

use App\Events\NewWhatsAppMessage;
use App\Models\WhatsappContact;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConversationController extends Controller
{
    public function facebook()
    {
        return view('adminDash.conversation.facebookChat');
    }

    public function whatsapp()
    {
        return view('adminDash.conversation.whatsappChat');
    }

    public function handleWhatsApp(Request $request)
    {
        if ($request->isMethod('get')) {
            $mode = $request->query('hub_mode');
            $token = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');

            $myVerifyToken = env('WHATSAPP_HOOK_VERIFY_TOKEN');

            if ($mode === 'subscribe' && $token === $myVerifyToken) {
                return response($challenge, 200)->header('Content-Type', 'text/plain');
            }

            return response('Forbidden', 403);
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            \Log::info('WhatsApp Webhook:', $data);

            if (isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
                $value = $data['entry'][0]['changes'][0]['value'];
                $messageData = $value['messages'][0];

                $wa_id = $messageData['from'];
                $message_id = $messageData['id'];
                $type = $messageData['type'];

                // Get contact name if available
                $contactName = $wa_id;
                if (isset($value['contacts'][0]['profile']['name'])) {
                    $contactName = $value['contacts'][0]['profile']['name'];
                }

                // Find or create contact
                $contact = WhatsappContact::firstOrCreate(
                    ['phone_number' => $wa_id],
                    ['name' => $contactName]
                );

                // Update unread count & last message time
                $contact->unread_count += 1;
                $contact->last_message_at = now();
                $contact->save();

                // Extract body based on type
                $body = '';
                if ($type === 'text' && isset($messageData['text']['body'])) {
                    $body = $messageData['text']['body'];
                } elseif ($type === 'image') {
                    $body = '[Image Received]'; // Placeholder for media handling
                } else {
                    $body = '['.ucfirst($type).' Received]';
                }

                // Save Message
                $newMessage = WhatsappMessage::firstOrCreate(
                    ['message_id' => $message_id],
                    [
                        'whatsapp_contact_id' => $contact->id,
                        'body' => $body,
                        'type' => $type,
                        'direction' => 'inbound',
                        'status' => 'received',
                    ]
                );

                if ($newMessage->wasRecentlyCreated) {
                    broadcast(new NewWhatsAppMessage($newMessage, $contact));
                }
            }

            return response('EVENT_RECEIVED', 200);
        }
    }

    // API: Get Contacts
    public function getWhatsappContacts()
    {
        $contacts = WhatsappContact::orderBy('last_message_at', 'desc')->get();

        return response()->json($contacts);
    }

    // API: Get Messages for a specific contact
    public function getWhatsappMessages($contact_id)
    {
        $contact = WhatsappContact::findOrFail($contact_id);

        // Reset unread count when opening chat
        $contact->update(['unread_count' => 0]);

        $messages = WhatsappMessage::where('whatsapp_contact_id', $contact_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'contact' => $contact,
            'messages' => $messages,
        ]);
    }

    // API: Send Message
    public function sendWhatsappMessage(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:whatsapp_contacts,id',
            'message' => 'required|string',
        ]);

        $contact = WhatsappContact::findOrFail($request->contact_id);
        $phone_number_id = env('WHATSAPP_PHONE_NUMBER_ID');
        $access_token = env('WHATSAPP_ACCESS_TOKEN');

        if (! $phone_number_id || ! $access_token) {
            return response()->json(['error' => 'WhatsApp API credentials not configured.'], 500);
        }

        // Send to WhatsApp API
        $response = Http::withToken($access_token)->post(
            "https://graph.facebook.com/v17.0/{$phone_number_id}/messages",
            [
                'messaging_product' => 'whatsapp',
                'to' => $contact->phone_number,
                'type' => 'text',
                'text' => [
                    'body' => $request->message,
                ],
            ]
        );

        if ($response->successful()) {
            $responseData = $response->json();

            // Save outbound message to DB
            $newMessage = WhatsappMessage::create([
                'whatsapp_contact_id' => $contact->id,
                'message_id' => $responseData['messages'][0]['id'] ?? uniqid('out_'),
                'body' => $request->message,
                'type' => 'text',
                'direction' => 'outbound',
                'status' => 'sent',
            ]);

            $contact->update(['last_message_at' => now()]);

            return response()->json(['success' => true, 'message' => $newMessage]);
        }

        return response()->json(['error' => 'Failed to send message: '.$response->body()], 500);
    }
}
