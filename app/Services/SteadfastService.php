<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SteadfastService
{
    protected $baseUrl = 'https://portal.packzy.com/api/v1';
    // private $base_url = "https://portal.packzy.com/api/v1";
    protected $apiKey;
    protected $secretKey;

    public function __construct()
    {

        // নিরাপত্তা নিশ্চিত করতে এই Key গুলো .env ফাইল থেকে নেওয়া উচিত
        // $this->apiKey = '0kdq3kykufkth7t6gvw9xoyapmzzc1gx ';
        // $this->secretKey = 'mfzpyt42yilmm6yyotudeqy6';
        $this->apiKey = env('STEADFAST_API_KEY', 'API Key Here');
        $this->secretKey = env('STEADFAST_SECRET_KEY', 'Secret Key Here');
    }

    // অথেনটিকেশন হেডার তৈরি করা
    protected function getHeaders()
    {
        return [
            'Api-Key' => $this->apiKey,
            'Secret-Key' => $this->secretKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    // API-তে ডেটা পাঠানোর কমন ফাংশন
    protected function post($path, $data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->post($this->baseUrl . $path, $data);
            $response->throw(); // 4xx বা 5xx এরর হলে এক্সেপশন থ্রো করবে
            return $response->json();
        } catch (\Exception $e) {
            Log::error("Steadfast API POST Error: " . $e->getMessage() . " on Path: " . $path);
            return ['status' => 500, 'message' => 'API Error: ' . $e->getMessage()];
        }
    }

    // API থেকে ডেটা নেওয়ার কমন ফাংশন
    protected function get($path)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get($this->baseUrl . $path);
            $response->throw();
            return $response->json();
        } catch (\Exception $e) {
            Log::error("Steadfast API GET Error: " . $e->getMessage() . " on Path: " . $path);
            return ['status' => 500, 'message' => 'API Error: ' . $e->getMessage()];
        }
    }

    //---------------------------------------------------------
    // প্রয়োজনীয় API মেথডগুলো এখানে তৈরি করা হবে
    //---------------------------------------------------------

    // ধাপ ১: নতুন অর্ডার প্লেস করা
    public function createOrder($data)
    {
        return Http::withHeaders([
            'Api-Key' => env('STEADFAST_API_KEY'),
            'Secret-Key' => env('STEADFAST_SECRET_KEY'),
            'Content-Type' => 'application/json'
        ])->post($this->base_url.'/create_order', $data)->json();
    }

    public function checkStatusByInvoice($invoice)
    {
        return Http::withHeaders([
            'Api-Key' => env('STEADFAST_API_KEY'),
            'Secret-Key' => env('STEADFAST_SECRET_KEY'),
        ])->get($this->base_url.'/status_by_invoice/'.$invoice)->json();
    }

    // ধাপ ৩: ডেলিভারি স্ট্যাটাস চেক করা (Tracking Code ব্যবহার করে)
    public function checkStatusByTrackingCode($trackingCode)
    {
        $path = "/status_by_trackingcode/{$trackingCode}";
        return $this->get($path);
    }

    // ধাপ ৩: ডেলিভারি স্ট্যাটাস চেক করা (Invoice ID ব্যবহার করে)
    // public function checkStatusByInvoice($invoiceId)
    // {
    //     $path = "/status_by_invoice/{$invoiceId}";
    //     return $this->get($path);
    // }



























    public function getCurrentBalance()
    {
        $path = '/get_balance';

        // protected get() মেথড ব্যবহার করে GET রিকোয়েস্ট পাঠানো হচ্ছে
        return $this->get($path);
    }
}
