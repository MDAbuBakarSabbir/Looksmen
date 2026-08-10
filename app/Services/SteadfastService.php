<?php

namespace App\Services;

use App\Models\CourierApi;
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
        $courier = CourierApi::where('courier_name', 'steadfast')->first();
        if ($courier) {
            $this->apiKey = $courier->api_key ?? env('STEADFAST_API_KEY', 'API Key Here');
            $this->secretKey = $courier->secret_key ?? env('STEADFAST_SECRET_KEY', 'Secret Key Here');
            if ($courier->base_url) {
                $this->baseUrl = rtrim($courier->base_url, '/');
            }
        } else {
            $this->apiKey = env('STEADFAST_API_KEY', 'API Key Here');
            $this->secretKey = env('STEADFAST_SECRET_KEY', 'Secret Key Here');
        }
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
            $response = Http::withHeaders($this->getHeaders())->post($this->baseUrl.$path, $data);
            $response->throw(); // 4xx বা 5xx এরর হলে এক্সেপশন থ্রো করবে

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Steadfast API POST Error: '.$e->getMessage().' on Path: '.$path);

            return ['status' => 500, 'message' => 'API Error: '.$e->getMessage()];
        }
    }

    // API থেকে ডেটা নেওয়ার কমন ফাংশন
    protected function get($path)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get($this->baseUrl.$path);
            $response->throw();

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Steadfast API GET Error: '.$e->getMessage().' on Path: '.$path);

            return ['status' => 500, 'message' => 'API Error: '.$e->getMessage()];
        }
    }

    // ---------------------------------------------------------
    // প্রয়োজনীয় API মেথডগুলো এখানে তৈরি করা হবে
    // ---------------------------------------------------------

    // নতুন অর্ডার প্লেস করা
    // নতুন অর্ডার প্লেস করা (Single)
    public function createOrder($data)
    {
        return $this->post('/create_order', $data);
    }

    // নতুন অর্ডার প্লেস করা (Bulk)
    public function bulkCreate($data)
    {
        return $this->post('/create_order/bulk-order', [
            'data' => json_encode($data),
        ]);
    }

    public function checkStatusByInvoice($invoice)
    {
        return $this->get('/status_by_invoice/'.$invoice);
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
