<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\CourierApi;
use App\Models\FeatureActivation;
use App\Services\SteadfastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourierApiController extends Controller
{

    public function index(){
        $features = FeatureActivation::all();
        $featuresConfig = $features->pluck('status', 'name')->toArray();
        $courierApi = CourierApi::all();
        $courierStatusConfig = $courierApi->keyBy('courier_name')->toArray();
        if ($featuresConfig['courier_api'] == '1') {
        return view('adminDash.settings.api.courier.index',compact('courierStatusConfig'));
        // return $courierStatusConfig;
        }else{
            abort(404);
        }
    }

    public function updateStatus(Request $request)
    {
        // 1. ইনপুট ডেটা যাচাই করুন
        $validated = $request->validate([
            'courier_name' => 'required|string|in:steadfast,pathao,paperfly,cityfast,redx,ecourier',
        ]);

        $courierName = $validated['courier_name'];

        DB::beginTransaction();

        try {
            // 2. সবগুলোর স্ট্যাটাস 0 করুন (একচেটিয়া নির্বাচন নিশ্চিত করার জন্য)
            DB::table('courier_apis')->update(['status' => 0]);

            // 3. নির্বাচিত কুরিয়ারটির স্ট্যাটাস 1 করুন
            $updated = DB::table('courier_apis')
                        ->where('courier_name', $courierName)
                        ->update(['status' => 1]);

            DB::commit();

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => "{$courierName} কে সফলভাবে প্রধান কুরিয়ার হিসেবে সেট করা হয়েছে।"
                ], 200);
            } else {
                // যদি no rows updated হয় (ডাটাবেসে কুরিয়ারের নাম না থাকলে)
                return response()->json([
                    'success' => false,
                    'message' => "কুরিয়ার ডাটাবেসে খুঁজে পাওয়া যায়নি।"
                ], 404);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            // ডাটাবেস ত্রুটি হলে
            return response()->json([
                'success' => false,
                'message' => 'ডাটাবেস আপডেটে ব্যর্থতা: ' . $e->getMessage()
            ], 500);
        }
    }






public function checkCustomerStatus(Request $request)
{
    $invoiceId = $request->input('invoice');

    $order = Orders::where('invoice_id', $invoiceId)->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found in your system.'], 404);
    }

    $steadfastService = new SteadfastService();
    $response = $steadfastService->checkStatusByInvoice($order->invoice_id);

    if (isset($response['status']) && $response['status'] == 200) {
        return response()->json([
            'status' => 'success',
            'delivery_status' => $response['delivery_status'],
            'tracking_code' => $order->tracking_code,
            'customer_name' => $order->customer_name,
        ]);
    }

    return response()->json(['status' => 'error', 'message' => 'Could not fetch status from Steadfast.']);
}

public function updateCredentials(Request $request)
{
    $validated = $request->validate([
        'courier_name' => 'required|string|in:steadfast,pathao,paperfly,cityfast,redx,ecourier',
        'api_key' => 'nullable|string|max:255',
        'secret_key' => 'nullable|string|max:255',
        'base_url' => 'nullable|string|max:255',
        'username' => 'nullable|string|max:255',
        'password' => 'nullable|string|max:255',
    ]);

    $courier = CourierApi::where('courier_name', $validated['courier_name'])->first();
    if (!$courier) {
        return response()->json(['success' => false, 'message' => 'Courier not found'], 404);
    }

    $courier->update([
        'api_key' => $validated['api_key'],
        'secret_key' => $validated['secret_key'],
        'base_url' => $validated['base_url'],
        'username' => $validated['username'] ?? null,
        'password' => $validated['password'] ?? null,
    ]);

    return response()->json(['success' => true, 'message' => ucfirst($validated['courier_name']) . ' credentials updated successfully!']);
}

public function getBalance()
{
    try {
        $activeCourier = CourierApi::where('status', '1')->first();
        if (!$activeCourier) {
            return response()->json([
                'success' => false,
                'message' => 'No active courier API configured.'
            ], 400);
        }

        if ($activeCourier->courier_name == 'steadfast') {
            $steadfastService = new SteadfastService();
            $balanceResponse = $steadfastService->getCurrentBalance();
            if (isset($balanceResponse['status']) && $balanceResponse['status'] == 200) {
                // Cache the balance under a generic key for layouts/header
                \Illuminate\Support\Facades\Cache::put('active_courier_balance', [
                    'status' => 'success',
                    'courier' => 'steadfast',
                    'balance' => $balanceResponse['current_balance']
                ], 300);

                return response()->json([
                    'success' => true,
                    'balance' => $balanceResponse['current_balance']
                ]);
            }

            $message = isset($balanceResponse['message']) ? $balanceResponse['message'] : 'Could not fetch balance from Steadfast API.';
            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'Balance check for ' . ucfirst($activeCourier->courier_name) . ' is not implemented yet.'
        ], 400);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

}
