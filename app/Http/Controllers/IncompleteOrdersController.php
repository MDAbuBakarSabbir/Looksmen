<?php

namespace App\Http\Controllers;

use App\Models\FeatureActivation;
use App\Models\FraudCheck;
use App\Models\GeneralWebSettings;
use App\Models\IncompleteOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class IncompleteOrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = IncompleteOrders::query();

        // Search by ID, Phone, or Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Date filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Days filter
        if ($request->filled('days')) {
            $days = $request->days;
            $now = \Carbon\Carbon::now();
            if ($days === 'today') {
                $query->whereDate('created_at', $now->toDateString());
            } elseif ($days === 'yesterday') {
                $query->whereDate('created_at', $now->subDay()->toDateString());
            } elseif ($days === '7days') {
                $query->where('created_at', '>=', $now->subDays(7));
            } elseif ($days === '30days') {
                $query->where('created_at', '>=', $now->subDays(30));
            } elseif ($days === 'this_year') {
                $query->whereYear('created_at', $now->year);
            } elseif ($days === 'last_year') {
                $query->whereYear('created_at', $now->subYear()->year);
            }
        }

        $incomOrders = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('adminDash.orders.incomplete', compact('incomOrders'));
    }

    public function destroy($id)
    {
        $incomOrder = IncompleteOrders::findOrFail($id);
        $incomOrder->delete();

        return redirect()->back()->with('success', 'Incomplete order deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $incomOrder = IncompleteOrders::findOrFail($id);
        $incomOrder->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'grand_total' => $request->grand_total,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Incomplete order updated successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Incomplete order updated successfully.');
    }

    public function checkFraud(Request $request)
    {
        $phone = $request->phone;

        // Check if feature is active
        $featuresConfig = FeatureActivation::pluck('status', 'name')->toArray();
        if (isset($featuresConfig['fraud_check_api']) && $featuresConfig['fraud_check_api'] == '0') {
            return response()->json(['error' => 'Fraud check is disabled.'], 403);
        }
        if (isset($featuresConfig['fraud_check_incomplete_order']) && $featuresConfig['fraud_check_incomplete_order'] == '0') {
            return response()->json(['error' => 'Incomplete order fraud check is disabled.'], 403);
        }

        $fraudCheck = FraudCheck::where('status', '1')->first();
        if (!$fraudCheck || ($fraudCheck->status ?? '0') !== '1' || empty($fraudCheck->api_key) || empty($fraudCheck->base_url)) {
            return response()->json(['error' => 'No active Fraud Check API provider enabled.'], 400);
        }

        $apiKey = $fraudCheck->api_key;
        $base_url = $fraudCheck->base_url;

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post($base_url, [
                'phone' => $phone
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:incomplete_orders,id',
            'action' => 'required|string|in:delete,confirm_pending'
        ]);

        $ids = $request->ids;
        $action = $request->action;

        if ($action === 'delete') {
            IncompleteOrders::whereIn('id', $ids)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Selected incomplete orders deleted successfully.'
            ]);
        }

        if ($action === 'confirm_pending') {
            DB::beginTransaction();
            try {
                foreach ($ids as $id) {
                    $incom = IncompleteOrders::find($id);
                    if ($incom) {
                        // Create a real order
                        $order = new \App\Models\Orders();
                        $order->user_id = 0;
                        $order->name = $incom->name;
                        $order->phone = $incom->phone;
                        $order->address = $incom->address;
                        $order->district = $incom->district;
                        $order->thana = $incom->thana;
                        $order->total_amount = $incom->subtotal ?? $incom->grand_total;
                        $order->grand_total = $incom->grand_total;
                        $order->delivery_status = 'pending';
                        $order->payment_type = 'Cash On Delivery';
                        $order->payment_status = 'pending';
                        $order->save();

                        // Create order details if we have product codes
                        $productCodes = json_decode($incom->product_id, true);
                        if (is_array($productCodes)) {
                            foreach ($productCodes as $code) {
                                // Find product by code if possible
                                $product = \App\Models\Product::where('product_code', $code)->first();
                                $detail = new \App\Models\OrderDetails();
                                $detail->order_id = $order->id;
                                $detail->product_id = $product ? $product->id : 0;
                                $detail->product_attribute = 'N/A';
                                $detail->product_colour = 'N/A';
                                $detail->product_qty = 1;
                                $detail->unit_price = $product ? (float)$product->price : (float)$incom->grand_total;
                                $detail->total_price = $detail->unit_price;
                                $detail->save();
                            }
                        }

                        // Delete incomplete order
                        $incom->delete();
                    }
                }
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Selected incomplete orders confirmed successfully.'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error confirming orders: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid action.'
        ], 400);
    }
}
