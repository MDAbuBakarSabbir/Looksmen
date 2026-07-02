<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // ফেসবুকের ভেরিফিকেশন হ্যান্ডেল করা (GET Request)
        if ($request->isMethod('get')) {
            $mode = $request->query('hub_mode');
            $token = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');

            $myVerifyToken = env('WHATSAPP_HOOK_VERIFY_TOKEN');

            if ($mode === 'subscribe' && $token === $myVerifyToken) {
                // ফেসবুক শুধু এই challenge লেখাটি প্লেইন টেক্সট হিসেবে ফেরত চায়
                return response($challenge, 200)->header('Content-Type', 'text/plain');
            }

            return response('Forbidden', 403);
        }

        // হোয়াটসঅ্যাপের ডাটা রিসিভ করা (POST Request)
        if ($request->isMethod('post')) {
            $data = $request->all();
            \Log::info('WhatsApp Webhook:', $data);

            return response('EVENT_RECEIVED', 200);
        }
    }
    // public function handleWhatsApp(Request $request)
    // {
    //     // ১. ফেসবুকের ভেরিফিকেশন হ্যান্ডেল করা (GET Request)
    //     if ($request->isMethod('get')) {
    //         $mode = $request->query('hub_mode');
    //         $token = $request->query('hub_verify_token');
    //         $challenge = $request->query('hub_challenge');

    //         // আপনার নিজের তৈরি করা একটি সিক্রেট টোকেন (যা .env-এ রাখবেন)
    //         $myVerifyToken = env('WHATSAPP_HOOK_VERIFY_TOKEN', 'MySecretToken123');

    //         if ($mode && $token) {
    //             if ($mode === 'subscribe' && $token === $myVerifyToken) {
    //                 return response($challenge, 200)->header('Content-Type', 'text/plain');
    //             }

    //             return response('Forbidden', 403);
    //         }
    //     }

    //     // ২. হোয়াটসঅ্যাপের রিয়েল-টাইম ডাটা/মেসেজ রিসিভ করা (POST Request)
    //     if ($request->isMethod('post')) {
    //         $data = $request->all();

    //         // ডাটা ঠিকঠাক আসছে কি না তা লারাভেল লগে দেখার জন্য
    //         \Log::info('WhatsApp Webhook Data:', $data);

    //         // এখানে আপনি ডাটাবেজে মেসেজ সেভ করার কোড লিখবেন

    //         return response('EVENT_RECEIVED', 200);
    //     }
    // }
}
