<?php

namespace App\Models;

use App\Models\Admins;
use App\Models\FeatureActivation;
use App\Models\FraudCheck;
use App\Models\OrderDetails;
use Database\Factories\OrdersFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Orders extends Model
{
    /** @use HasFactory<OrdersFactory> */
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

    protected static function booted()
    {
        static::created(function ($order) {
            try {
                dispatch(function () use ($order) {
                    $fresh = Orders::find($order->id);
                    if ($fresh) {
                        $fresh->getCourierHistoryData();
                    }
                })->afterResponse();
            } catch (\Exception $e) {
                Log::error('Courier history dispatch error in Orders model: '.$e->getMessage());
            }
        });
    }

    public function getCourierHistoryData()
    {
        // 1. Check feature activation flags
        $featuresConfig = Cache::rememberForever('feature_activations_map', function () {
            return FeatureActivation::pluck('status', 'name')->toArray();
        });

        if (isset($featuresConfig['fraud_check_api']) && $featuresConfig['fraud_check_api'] == '0') {
            return null;
        }
        if (isset($featuresConfig['fraud_check_order']) && $featuresConfig['fraud_check_order'] == '0') {
            return null;
        }

        // 2. Fetch active provider array
        $fraudCheck = FraudCheck::getActiveProvider();

        $status = is_array($fraudCheck) ? ($fraudCheck['status'] ?? '0') : ($fraudCheck->status ?? '0');
        $apiKey = is_array($fraudCheck) ? ($fraudCheck['api_key'] ?? null) : ($fraudCheck->api_key ?? null);
        $baseUrl = is_array($fraudCheck) ? ($fraudCheck['base_url'] ?? null) : ($fraudCheck->base_url ?? null);

        if (! $fraudCheck || $status !== '1' || empty($apiKey) || empty($baseUrl)) {
            return null;
        }

        // If already exists in database and is not empty, decode and return it
        if (! empty($this->courier_history)) {
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
                                'success_ratio' => $totalVal > 0 ? round(($successVal / $totalVal) * 100) : 0,
                            ],
                        ],
                    ];
                }
            }
        }

        // Extract 11-digit phone number
        $digits = preg_replace('/[^0-9]/', '', (string) $this->phone);
        $phone = strlen($digits) >= 11 ? substr($digits, -11) : $digits;
        if (strlen($phone) !== 11) {
            return null;
        }

        try {
            // Make the HTTP request to active Fraud Check API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
            ])->timeout(8)->post($baseUrl, [
                'phone' => $phone,
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
            Log::error('BD Courier API error in Orders model: '.$e->getMessage());
        }

        return null;
    }
}
