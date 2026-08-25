<?php

namespace App\Http\Controllers;

use App\Jobs\CheckCourierHistory;
use App\Jobs\SendMetaCapiPurchaseJob;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupons;
use App\Models\District;
use App\Models\FeatureActivation;
use App\Models\FraudCheck;
use App\Models\GeneralWebSettings;
use App\Models\IncompleteOrders;
use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\Payment;
use App\Models\Thana;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class CheckoutController extends Controller
{
    public function checkout()
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->with('product')->get();
            $cartEmpty = $cart->isEmpty();
        } else {
            $cart = session()->get('cart', []);
            $cartEmpty = empty($cart);
        }
        $districts = Cache::rememberForever('active_districts_list', function () {
            return District::where('status', '1')->get();
        });
        // Guard: if cache is corrupted (not a collection of objects), refresh from DB
        if (! ($districts instanceof Collection) || ($districts->isNotEmpty() && ! is_object($districts->first()))) {
            Cache::forget('active_districts_list');
            $districts = District::where('status', '1')->get();
            Cache::forever('active_districts_list', $districts);
        }
        $addresses = auth()->check() ? Address::where('user_id', auth()->id())->get() : collect();
        $featuresConfig = Cache::rememberForever('feature_activations_map', function () {
            return FeatureActivation::pluck('status', 'name')->toArray();
        });

        $activePaymentMethods = DB::table('payment_apis')->where('status', '1')->pluck('paymentapi_name')->toArray();
        if ($cartEmpty) {
            return redirect()->route('cartView')->with('error', 'Your cart is empty! Please add products first.');
        }

        $freeDelivery = check_free_delivery($cart);

        return view('Frontend.checkout', compact('districts', 'cart', 'addresses', 'featuresConfig', 'activePaymentMethods', 'freeDelivery'));
    }

    public function checkFraud(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:11',
        ]);

        $features = Cache::rememberForever('feature_activations_map', function () {
            return FeatureActivation::pluck('status', 'name')->toArray();
        });

        // 1. Check if Fraud Check API & Frontend Fraud Check are enabled
        $apiEnabled = ($features['fraud_check_api'] ?? '1') === '1';
        $frontendEnabled = ($features['fraud_check_frontend'] ?? '1') === '1';
        if (! $apiEnabled || ! $frontendEnabled) {
            return response()->json([
                'success' => false,
                'disabled' => true,
                'message' => 'Frontend Fraud Check is disabled.',
            ]);
        }

        // 2. Fetch active provider (must have status == 1)
        $fraudCheck = FraudCheck::getActiveProvider();

        $status = is_array($fraudCheck) ? ($fraudCheck['status'] ?? '0') : ($fraudCheck->status ?? '0');
        $apiKey = is_array($fraudCheck) ? ($fraudCheck['api_key'] ?? null) : ($fraudCheck->api_key ?? null);
        $endpoint = is_array($fraudCheck) ? ($fraudCheck['base_url'] ?? null) : ($fraudCheck->base_url ?? null);

        if (! $fraudCheck || $status !== '1' || empty($apiKey) || empty($endpoint)) {
            return response()->json([
                'success' => false,
                'disabled' => true,
                'message' => 'No active Fraud Check API provider enabled or configuration missing.',
            ]);
        }

        $phone = $request->phone;
        $cacheKey = 'fraud_check_phone_'.$phone;
        $cachedResult = Cache::get($cacheKey);
        if ($cachedResult) {
            return response()->json($cachedResult);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
            ])->timeout(4)->post($endpoint, [
                'phone' => $phone,
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Courier API failed.',
                ], 400);
            }

            $data = $response->json();
            $summary = $data['courierData']['summary'] ?? null;

            if (! $summary) {
                return response()->json([
                    'success' => false,
                    'message' => 'No courier history found.',
                ]);
            }

            $minRate = 60;

            $resultData = [
                'success' => true,
                'data' => [
                    'total' => (int) $summary['total_parcel'],
                    'delivered' => (int) $summary['success_parcel'],
                    'cancelled' => (int) $summary['cancelled_parcel'],
                    'success_rate' => (int) $summary['success_ratio'],
                ],
                'min_rate' => $minRate,
            ];

            // Cache successful result for 30 minutes to make subsequent lookups instantaneous
            Cache::put($cacheKey, $resultData, 1800);

            return response()->json($resultData);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while checking courier.',
            ], 500);
        }
    }

    public function testrun(Request $request)
    {
        $courierApi = Cache::rememberForever('fraud_check_settings_first', function () {
            return FraudCheck::where('status', '1')->first();
        });
        $api_key = $courierApi ? [$courierApi->api_key] : ['api_key' => null];

        return $courierApi ? $courierApi->api_key : null;
    }

    public function applyCoupon(Request $request)
    {
        $today = date('Y-m-d');

        // ১. প্রথমে চেক করুন কোডটি ডাটাবেসে আছে কি না
        $coupon = Coupons::where('code', $request->code)->where('status', 1)->first();

        if (! $coupon) {
            return response()->json(['status' => 'error', 'message' => 'This coupon code does not exist!']);
        }

        if ($coupon->quantity > 0 && $coupon->used >= $coupon->quantity) {
            return response()->json(['status' => 'error', 'message' => 'এই কুপনটির লিমিট শেষ হয়ে গেছে!']);
        }
        // ২. মেয়াদ চেক করা
        if ($today < $coupon->start_date) {
            return response()->json(['status' => 'error', 'message' => 'This coupon offer has not started yet!']);
        }
        if ($today > $coupon->end_date) {
            return response()->json(['status' => 'error', 'message' => 'Sorry, this coupon has expired!']);
        }

        // ৩. বাকি লজিক (মিনিমাম এমাউন্ট ও ইউজড চেক)
        if ($coupon->use_type == 'single' && Auth::check()) {
            $alreadyUsed = Orders::where('user_id', Auth::id())
                ->where('coupon_code', $request->code)
                ->exists();
            if ($alreadyUsed) {
                return response()->json(['status' => 'error', 'message' => 'You have already used this coupon!']);
            }
        }
        if ($request->subtotal < $coupon->min_cart_amount) {
            return response()->json(['status' => 'error', 'message' => 'Min. order amount ৳'.$coupon->min_cart_amount.' required']);
        }
        $discount = 0;
        if ($coupon->discount_type == 'percent') {
            $discount = ($request->subtotal * (float) $coupon->discount) / 100;
        } else {
            $discount = (float) $coupon->discount;
        }
        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $discount,
        ]);
        // ... আপনার আগের লজিক এখানে থাকবে ...

        return response()->json([
            'status' => 'success',
            'message' => 'Congrats! Coupon applied successfully.',
            'discount' => $discount,
        ]);
    }

    public function storeIncompleteOrder(Request $request)
    {
        $rawPhone = $request->phone ?? '';
        $digits = preg_replace('/[^0-9]/', '', (string) $rawPhone);
        $phone = strlen($digits) >= 11 ? substr($digits, -11) : $digits;

        if (empty($phone) || strlen($phone) < 11) {
            return response()->json(['status' => 'error', 'message' => 'Valid 11-digit phone number required'], 422);
        }

        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->with('product')->get();
        } else {
            $cart = session()->get('cart', []);
        }

        $cartData = [];
        foreach ($cart as $key => $item) {
            if (is_object($item)) {
                $pId = $item->product_id;
                $code = $item->product->code ?? 'N/A';
                $name = $item->product->title ?? 'N/A';
                $price = $item->product->new_price ?? $item->price ?? 0;
                $qty = $item->quantity ?? 1;
                $size = $item->attribute ?? 'N/A';
                $color = $item->color ?? 'N/A';
            } else {
                $pId = $item['id'] ?? $item['product_id'] ?? null;
                $code = $item['code'] ?? 'N/A';
                $name = $item['name'] ?? 'N/A';
                $price = $item['price'] ?? 0;
                $qty = $item['quantity'] ?? 1;
                $size = $item['attribute'] ?? $item['size'] ?? 'N/A';
                $color = $item['color'] ?? 'N/A';
            }

            $cartData[] = [
                'product_id' => $pId,
                'code' => $code,
                'name' => $name,
                'price' => (float) $price,
                'quantity' => (int) $qty,
                'size' => $size,
                'color' => $color,
            ];
        }

        $district = $request->district;
        if ($request->district_id) {
            $resolvedDistrict = District::getNameById($request->district_id);
            if ($resolvedDistrict && $resolvedDistrict !== 'N/A') {
                $district = $resolvedDistrict;
            }
        }

        $thana = $request->thana;
        if ($request->thana_id) {
            $resolvedThana = Thana::getNameById($request->thana_id);
            if ($resolvedThana && $resolvedThana !== 'N/A') {
                $thana = $resolvedThana;
            }
        }

        $subtotal = (string) ($request->subtotal ?? '0');
        $grand_total = (string) ($request->grand_total ?? $subtotal);

        // ফোন নম্বর দিয়ে আপডেট বা নতুন তৈরি
        $incomplete = IncompleteOrders::updateOrCreate(
            ['phone' => $phone],
            [
                'name' => $request->name ?: 'Customer',
                'address' => $request->address ?: 'N/A',
                'district' => $district ?: null,
                'thana' => $thana ?: null,
                'product_id' => json_encode($cartData),
                'subtotal' => $subtotal,
                'grand_total' => $grand_total,
                'status' => 'incomplete',
            ]
        );

        return response()->json(['status' => 'success', 'id' => $incomplete->id]);
    }

    public function storeOrder(Request $request)
    {
        // ১. ভ্যালিডেশন (প্রয়োজনীয় ফিল্ড চেক)
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'district_id' => 'required',
            'address' => 'required',
        ]);

        // Logged-in user হলে DB থেকে, না হলে session থেকে cart নেওয়া
        if (auth()->check()) {
            $dbCart = Cart::where('user_id', auth()->id())->with('product')->get();
            if ($dbCart->isEmpty()) {
                return redirect()->back()->with('error', 'Cart is empty!');
            }
            // DB cart কে array format এ convert করা
            $cart = [];
            foreach ($dbCart as $item) {
                $cart[] = [
                    'id' => $item->product_id,
                    'price' => $item->product->new_price,
                    'quantity' => $item->quantity,
                    'attribute' => $item->attributes ?? 'N/A',
                    'color' => $item->color ?? 'N/A',
                ];
            }
        } else {
            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return redirect()->back()->with('error', 'Cart is empty!');
            }
        }

        try {
            DB::beginTransaction();

            $freeDelivery = check_free_delivery($cart);
            $deliveryCharge = ($freeDelivery['is_free'] ?? false) ? 0 : (float) $request->delivery_charge;
            $subtotal = (float) $request->total_amount;
            $couponDiscount = (float) ($request->coupon_discount ?? 0);
            $grandTotal = max(0, ($subtotal - $couponDiscount) + $deliveryCharge);

            // ২. Orders টেবিলে ডাটা ইনসার্ট
            $rawPhone = $request->phone ?? '';
            $digits = preg_replace('/[^0-9]/', '', (string) $rawPhone);
            $cleanPhone = strlen($digits) >= 11 ? substr($digits, -11) : $digits;

            $order = new Orders;
            $order->user_id = auth()->id() ?? 0;
            $order->ip_address = $request->ip();
            $order->name = $request->name;
            $order->phone = $cleanPhone;
            $order->district = District::getNameById($request->district_id);
            $order->thana = Thana::getNameById($request->thana_id);
            $order->address = $request->address;
            $order->total_amount = $subtotal; // Subtotal
            $order->coupon_discount = $couponDiscount;
            $order->delivery_charge = $deliveryCharge;
            $order->grand_total = $grandTotal;
            $order->coupon_code = $request->coupon_code;
            $order->payment_type = $request->payment;
            $order->note = $request->note;
            $order->payment_status = 'pending';
            $order->delivery_status = 'pending';
            $order->save();

            // Wallet Payment Process
            if ($request->payment === 'wallet') {
                if (! auth()->check()) {
                    throw new \Exception('Please login to pay using your wallet.');
                }
                $user = auth()->user();
                $grandTotal = (float) $request->grand_total;
                if ($user->wallet_balance < $grandTotal) {
                    throw new \Exception('Insufficient wallet balance to pay for this order.');
                }

                // Deduct balance
                $user->wallet_balance -= $grandTotal;
                $user->save();

                // Create Wallet Transaction
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $grandTotal,
                    'payment_method' => 'wallet',
                    'type' => 'debit',
                    'status' => 'approved',
                    'payment_details' => json_encode(['order_id' => $order->id]),
                ]);

                // Update order payment status
                $order->payment_status = 'paid';
                $order->save();
            }

            // ৩. OrderDetails টেবিলে লুপ চালিয়ে প্রোডাক্ট সেভ (Bulk Insert)
            $orderDetails = [];
            $now = now();
            $referralCode = request()->cookie('referral_code');

            foreach ($cart as $item) {
                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_attribute' => $item['attribute'] ?? 'N/A',
                    'product_colour' => $item['color'] ?? 'N/A',
                    'unit_price' => (float) $item['price'],
                    'product_qty' => $item['quantity'],
                    'total_price' => (float) $item['price'] * (int) $item['quantity'],
                    'product_referral_code' => $referralCode,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            OrderDetails::insert($orderDetails);

            // ৪. ইনকমপ্লিট অর্ডার ডিলিট করা (ফোন নম্বর দিয়ে ম্যাচ করে)
            IncompleteOrders::where(function ($q) use ($cleanPhone, $rawPhone, $digits) {
                if (! empty($cleanPhone)) {
                    $q->where('phone', $cleanPhone)
                        ->orWhere('phone', 'like', "%{$cleanPhone}")
                        ->orWhere('phone', '88'.$cleanPhone)
                        ->orWhere('phone', '+88'.$cleanPhone);
                }
                if (! empty($rawPhone)) {
                    $q->orWhere('phone', $rawPhone);
                }
                if (! empty($digits)) {
                    $q->orWhere('phone', $digits);
                }
            })->delete();
            if ($request->coupon_code) {
                Coupons::where('code', $request->coupon_code)->increment('used');
            }

            DB::commit();

            // Dispatch job to check courier history and send email asynchronously
            $orderId = $order->id;
            $userEmail = auth()->check() ? auth()->user()->email : null;
            $customerName = $order->name;
            $grandTotal = $order->grand_total;

            CheckCourierHistory::dispatch($orderId, $userEmail, $customerName, $grandTotal)->afterResponse();

            // Dispatch Meta Conversions API (CAPI) Purchase Event (Server-Side Tracking)
            $capiContext = [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'fbp' => request()->cookie('_fbp'),
                'fbc' => request()->cookie('_fbc'),
            ];
            SendMetaCapiPurchaseJob::dispatchSync($order->id, 'purchase_'.$order->id, $capiContext);

            // ৫. কার্ট ক্লিয়ার করা
            session()->forget('cart');
            if (auth()->check()) {
                Cart::where('user_id', auth()->id())->delete();
            }

            return redirect()->route('order.invoice', $order->id)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Something went wrong: '.$e->getMessage());
        }
    }

    // ---------------Bkash Payment -------------

    private function getToken()
    {
        $response = Http::withoutVerifying()
            ->withHeaders([
                'username' => env('BKASH_USERNAME'),
                'password' => env('BKASH_PASSWORD'),
            ])
            ->post(env('BKASH_BASE_URL').'/tokenized/checkout/token/grant', [
                'app_key' => env('BKASH_APP_KEY'),
                'app_secret' => env('BKASH_APP_SECRET'),
            ]);

        $result = $response->json();

        if ($response->successful() && isset($result['id_token'])) {
            return $result['id_token'];
        }

        // যদি এরর আসে তবে সেটি সুন্দরভাবে দেখাবে
        $message = $result['statusMessage'] ?? 'Unknown Error';
        $code = $result['statusCode'] ?? '9999';

        throw new \Exception("bKash Error ($code): $message");
    }

    public function bkashPayment(Request $request)
    {
        // কাস্টমারের ইনফরমেশন সেশনে সেভ করে রাখা (অর্ডার প্লেস করার জন্য)
        session()->put('pending_order_data', $request->all());

        $token = $this->getToken();
        $payableAmount = $request->delivery_charge; // আপনার লজিক অনুযায়ী ডেলিভারি চার্জ

        $response = Http::withHeaders([
            'Authorization' => $token,
            'X-APP-Key' => env('BKASH_APP_KEY'),
        ])->post(env('BKASH_BASE_URL').'/tokenized/checkout/create', [
            'amount' => $payableAmount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => 'INV-'.time(),
            'callbackURL' => route('bkash.callback'),
        ]);

        return redirect($response->json()['bkashURL']);
    }

    public function bkashCallback(Request $request)
    {
        if ($request->status === 'success') {
            $token = $this->getToken();
            $response = Http::withHeaders([
                'Authorization' => $token,
                'X-APP-Key' => env('BKASH_APP_KEY'),
            ])->post(env('BKASH_BASE_URL').'/tokenized/checkout/execute', [
                'paymentID' => $request->paymentID,
            ]);

            $result = $response->json();

            if (isset($result['transactionStatus']) && $result['transactionStatus'] === 'Completed') {

                return $this->finalizeOrder($result);
            }
        }

        return redirect()->route('checkout')->with('error', 'Payment failed or cancelled.');
    }

    private function finalizeOrder($paymentData)
    {
        try {
            DB::beginTransaction();

            // ক. Payments টেবিলে ডাটা স্টোর
            $payment = new Payment;
            $payment->user_id = auth()->id() ?? 0;
            $payment->amount = $paymentData['amount'];
            $payment->currency = $paymentData['currency'];
            $payment->paymentID = $paymentData['paymentID'];
            $payment->trxID = $paymentData['trxID'];
            $payment->merchantInvoiceNumber = $paymentData['merchantInvoiceNumber'];
            $payment->payerReference = $paymentData['customerMsisdn'];
            $payment->save();

            // খ. সেশন থেকে অর্ডার ডাটা নেওয়া
            $orderData = session()->get('pending_order_data');
            $cart = session()->get('cart', []);
            $freeDelivery = check_free_delivery($cart);
            $deliveryCharge = ($freeDelivery['is_free'] ?? false) ? 0 : (float) ($orderData['delivery_charge'] ?? 0);
            $subtotal = (float) ($orderData['total_amount'] ?? 0);
            $couponDiscount = (float) ($orderData['coupon_discount'] ?? 0);
            $grandTotal = max(0, ($subtotal - $couponDiscount) + $deliveryCharge);

            // গ. Orders টেবিলে ডাটা ইনসার্ট
            $order = new Orders;
            $order->user_id = auth()->id() ?? 0;
            $order->ip_address = request()->ip();
            $order->name = $orderData['name'];
            $order->phone = $orderData['phone'];
            $order->address = $orderData['address'];
            $order->district = District::getNameById($orderData['district_id'] ?? 0);
            $order->thana = Thana::getNameById($orderData['thana_id'] ?? 0);
            $order->total_amount = $subtotal;
            $order->delivery_charge = $deliveryCharge;
            $order->coupon_discount = $couponDiscount;
            $order->grand_total = $grandTotal;
            $order->payment_type = 'prepaid';
            $order->payment_status = 'partial_paid';
            $order->payment_id = $payment->id;
            $order->delivery_status = 'pending';
            $order->save();

            // ঘ. Order Details সেভ (Bulk Insert)
            $orderDetails = [];
            $now = now();
            $referralCode = request()->cookie('referral_code');

            foreach ($cart as $item) {
                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_qty' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'product_attribute' => $item['attribute'] ?? 'N/A',
                    'product_colour' => $item['color'] ?? 'N/A',
                    'product_referral_code' => $referralCode,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            OrderDetails::insert($orderDetails);

            // ইনকমপ্লিট অর্ডার ডিলিট করা
            $rawPhone = $orderData['phone'] ?? '';
            $digits = preg_replace('/[^0-9]/', '', (string) $rawPhone);
            $cleanPhone = strlen($digits) >= 11 ? substr($digits, -11) : $digits;

            IncompleteOrders::where(function ($q) use ($cleanPhone, $rawPhone, $digits) {
                if (! empty($cleanPhone)) {
                    $q->where('phone', $cleanPhone)
                        ->orWhere('phone', 'like', "%{$cleanPhone}")
                        ->orWhere('phone', '88'.$cleanPhone)
                        ->orWhere('phone', '+88'.$cleanPhone);
                }
                if (! empty($rawPhone)) {
                    $q->orWhere('phone', $rawPhone);
                }
                if (! empty($digits)) {
                    $q->orWhere('phone', $digits);
                }
            })->delete();

            DB::commit();

            $userEmail = auth()->check() ? auth()->user()->email : null;
            CheckCourierHistory::dispatch($order->id, $userEmail, $order->name, $order->grand_total)->afterResponse();

            // Dispatch Meta Conversions API (CAPI) Purchase Event (Server-Side Tracking)
            $capiContext = [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'fbp' => request()->cookie('_fbp'),
                'fbc' => request()->cookie('_fbc'),
            ];
            SendMetaCapiPurchaseJob::dispatchSync($order->id, 'purchase_'.$order->id, $capiContext);

            session()->forget(['cart', 'pending_order_data']);

            return redirect()->route('order.invoice', $order->id)->with('order_placed', 'success');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('checkout')->with('error', 'Critical Error: '.$e->getMessage());
        }
    }

    // ------------- SSL Commerz ----------

    public function othersPayment(Request $request)
    {
        // ১. ফর্ম ডাটা সেশনে রাখা
        session()->put('pending_order_data', $request->all());

        // ২. কার্ট ডাটা নেওয়া
        if (auth()->check()) {
            $dbCart = Cart::where('user_id', auth()->id())->with('product')->get();
            $cart = [];
            foreach ($dbCart as $item) {
                $cart[] = [
                    'id' => $item->product_id,
                    'price' => $item->product->new_price,
                    'quantity' => $item->quantity,
                    'attribute' => $item->attributes ?? 'N/A',
                    'color' => $item->color ?? 'N/A',
                ];
            }
        } else {
            $cart = session()->get('cart', []);
        }

        $freeDelivery = check_free_delivery($cart);
        $deliveryCharge = ($freeDelivery['is_free'] ?? false) ? 0 : (float) $request->delivery_charge;
        $subtotal = (float) $request->total_amount;
        $couponDiscount = (float) ($request->coupon_discount ?? 0);
        $grandTotal = max(0, ($subtotal - $couponDiscount) + $deliveryCharge);

        $post_data = [];
        $post_data['total_amount'] = $grandTotal; // You cant not употребляйте float value here. It must be string or integer.
        $post_data['currency'] = 'BDT';
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        // CUSTOMER INFORMATION
        $post_data['cus_name'] = $request->name;
        $post_data['cus_email'] = 'customer@gmail.com';
        $post_data['cus_add1'] = $request->address;
        $post_data['cus_add2'] = '';
        $post_data['cus_city'] = '';
        $post_data['cus_state'] = '';
        $post_data['cus_postcode'] = '';
        $post_data['cus_country'] = 'Bangladesh';
        $post_data['cus_phone'] = $request->phone;
        $post_data['cus_fax'] = '';

        // SHIPMENT INFORMATION
        $post_data['ship_name'] = 'Store Test';
        $post_data['ship_add1'] = 'Dhaka';
        $post_data['ship_add2'] = 'Dhaka';
        $post_data['ship_city'] = 'Dhaka';
        $post_data['ship_state'] = 'Dhaka';
        $post_data['ship_postcode'] = '1000';
        $post_data['ship_phone'] = '';
        $post_data['ship_country'] = 'Bangladesh';

        $post_data['shipping_method'] = 'NO';
        $post_data['product_name'] = 'Computer';
        $post_data['product_category'] = 'Goods';
        $post_data['product_profile'] = 'physical-goods';

        // OPTIONAL PARAMETERS
        $post_data['value_a'] = 'ref001';
        $post_data['value_b'] = 'ref002';
        $post_data['value_c'] = 'ref003';
        $post_data['value_d'] = 'ref004';

        $sslc = new SslCommerzNotification;
        // initiate(param1, param2, param3)
        // param1 = array of the customer info and product information
        // param2 = true / false; true = json response, false = redirect to sslcommerz gateway
        // param3 = true / false; true = payment will be hosted on sslcommerz gateway, false = payment will be hosted on your server

        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (! is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = [];
        }
    }

    public function sslCommerzSuccess(Request $request)
    {
        try {
            DB::beginTransaction();

            $payment = new Payment;
            $payment->user_id = auth()->id() ?? 0;
            $payment->amount = $request->amount;
            $payment->currency = $request->currency;
            $payment->paymentID = $request->bank_tran_id;
            $payment->trxID = $request->tran_id;
            $payment->merchantInvoiceNumber = $request->tran_id;
            $payment->payerReference = $request->card_brand ?? 'SSLCommerz';
            $payment->save();

            $orderData = session()->get('pending_order_data');
            $cart = session()->get('cart', []);

            $freeDelivery = check_free_delivery($cart);
            $deliveryCharge = ($freeDelivery['is_free'] ?? false) ? 0 : (float) ($orderData['delivery_charge'] ?? 0);
            $subtotal = (float) ($orderData['total_amount'] ?? 0);
            $couponDiscount = (float) ($orderData['coupon_discount'] ?? 0);
            $grandTotal = max(0, ($subtotal - $couponDiscount) + $deliveryCharge);

            $rawPhone = $orderData['phone'] ?? '';
            $digits = preg_replace('/[^0-9]/', '', (string) $rawPhone);
            $cleanPhone = strlen($digits) >= 11 ? substr($digits, -11) : $digits;

            $order = new Orders;
            $order->user_id = auth()->id() ?? 0;
            $order->ip_address = $request->ip();
            $order->name = $orderData['name'];
            $order->phone = $cleanPhone ?: $rawPhone;
            $order->address = $orderData['address'];
            $order->district = District::getNameById($orderData['district_id'] ?? 0);
            $order->thana = Thana::getNameById($orderData['thana_id'] ?? 0);
            $order->total_amount = $subtotal;
            $order->delivery_charge = $deliveryCharge;
            $order->coupon_discount = $couponDiscount;
            $order->grand_total = $grandTotal;
            $order->coupon_code = $orderData['coupon_code'];
            $order->payment_type = $orderData['payment'];
            $order->note = $orderData['note'];
            $order->delivery_status = 'pending';
            $order->payment_type = 'prepaid';
            $order->payment_status = 'partial_paid';
            $order->payment_id = $payment->id;
            $order->save();

            // ৩. Order Details (Bulk Insert)
            $orderDetails = [];
            $now = now();
            $referralCode = request()->cookie('referral_code');

            foreach ($cart as $item) {
                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_attribute' => $item['attribute'] ?? 'N/A',
                    'product_colour' => $item['color'] ?? 'N/A',
                    'product_qty' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'product_referral_code' => $referralCode,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            OrderDetails::insert($orderDetails);
            IncompleteOrders::where('phone', $orderData['phone'])->delete();
            DB::commit();

            $userEmail = auth()->check() ? auth()->user()->email : null;
            CheckCourierHistory::dispatch($order->id, $userEmail, $order->name, $order->grand_total)->afterResponse();

            // Dispatch Meta Conversions API (CAPI) Purchase Event (Server-Side Tracking)
            $capiContext = [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'fbp' => request()->cookie('_fbp'),
                'fbc' => request()->cookie('_fbc'),
            ];
            SendMetaCapiPurchaseJob::dispatchSync($order->id, 'purchase_'.$order->id, $capiContext);

            session()->forget(['cart', 'pending_order_data']);

            return redirect()->route('order.invoice', $order->id)->with('success', 'Order Placed Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('checkout')->with('error', $e->getMessage());
        }
    }

    public function showInvoice($id)
    {
        $order = Orders::where('id', $id)->first();

        return view('Frontend.order.success', compact('order'));
    }

    public function printInvoice($id)
    {
        $order = Orders::where('id', $id)->firstOrFail();
        $webConfig = [];
        if (Schema::hasTable('general_web_settings')) {
            $webConfig = GeneralWebSettings::pluck('value', 'name')->toArray();
        }

        return view('Frontend.order.invoice', compact('order', 'webConfig'));
    }

    public function storeOrderTest(Request $request)
    {
        return $request;
    }
}
