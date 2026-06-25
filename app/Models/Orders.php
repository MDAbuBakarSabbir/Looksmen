<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    /** @use HasFactory<\Database\Factories\OrdersFactory> */
    use HasFactory;
    protected $guarded = [''];
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admins::class, 'updated_by');
    }

    public function getCourierHistoryData()
    {
        // If already exists in database and is not empty, decode and return it
        if (!empty($this->courier_history)) {
            $decoded = json_decode($this->courier_history, true);
            if (is_array($decoded)) {
                // If it is the full BD Courier response structure
                if (isset($decoded['status']) && $decoded['status'] == 'success') {
                    return $decoded;
                }
                // If it is the old summary-only structure
                if (isset($decoded['total'], $decoded['success'], $decoded['failed'])) {
                    $totalVal = intval($decoded['total']);
                    $successVal = intval($decoded['success']);
                    $failedVal = intval($decoded['failed']);
                    return [
                        'status' => 'success',
                        'courierData' => [
                            'summary' => [
                                'total_parcel' => $totalVal,
                                'success_parcel' => $successVal,
                                'cancelled_parcel' => $failedVal,
                                'success_ratio' => $totalVal > 0 ? round(($successVal / $totalVal) * 100) : 0
                            ]
                        ]
                    ];
                }
            }
        }

        // Check if phone number is valid
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (strlen($phone) !== 11) {
            return null;
        }

        try {
            // Fetch API key and base url from FraudCheck table (used in show.blade.php)
            $fraudCheck = \App\Models\FraudCheck::first();
            if (!$fraudCheck || empty($fraudCheck->api_key) || empty($fraudCheck->base_url)) {
                return null;
            }

            $apiKey = $fraudCheck->api_key;
            $endpoint = $fraudCheck->base_url;

            // Make the HTTP request to BD Courier API
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->timeout(3)->post($endpoint, [
                'phone' => $phone
            ]);

            if ($response->successful()) {
                $resData = $response->json();
                if (isset($resData['status']) && $resData['status'] == 'success' && isset($resData['courierData'])) {
                    // Save the full API response to the database
                    $this->courier_history = json_encode($resData);
                    $this->timestamps = false;
                    $this->save();
                    $this->timestamps = true;

                    return $resData;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('BD Courier API error in Orders model: ' . $e->getMessage());
        }

        return null;
    }
}
