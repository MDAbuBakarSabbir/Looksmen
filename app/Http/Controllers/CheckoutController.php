<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupons;
use App\Models\District;
use App\Models\FeatureActivation;
use App\Models\FraudCheck;
use App\Models\IncompleteOrders;
use App\Models\Logs;
use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;





class CheckoutController extends Controller
{
    public function checkout()
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->with('product')->get();
        } else {
            $cart = session()->get('cart', []);
        }
        $districts = District::where('status', '1')->get();
        $addresses = Address::where('user_id',auth()->id())->get();
        $features = FeatureActivation::all();
        $featuresConfig = $features->pluck('status', 'name')->toArray();
        if (empty($cart)) {
            return redirect()->route('cartView')->with('error', 'Your cart is empty! Please add products first.');
        }
        return view('Frontend.checkout', compact('districts', 'cart','addresses','featuresConfig'));
    }

    public function checkFraud(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:11',
        ]);



        // $apiKey   = config('courierCheck.api_key');
        // $endpoint = config('courierCheck.endpoint');
        $fraudCheck = FraudCheck::first();
        $apiKey = $fraudCheck->api_key;
        $endpoint = $fraudCheck->base_url;

        if (!$apiKey || !$endpoint) {
            return response()->json([
                'success' => false,
                'message' => 'Courier API configuration missing.'
            ], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->timeout(10)->post($endpoint, [
                'phone' => $request->phone,
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Courier API failed.',
                ], 400);
            }

            $data = $response->json();
            $summary = $data['courierData']['summary'] ?? null;

            if (!$summary) {
                return response()->json([
                    'success' => false,
                    'message' => 'No courier history found.'
                ]);
            }

            $minRate = 60;

            return response()->json([
                'success' => true,
                'data' => [
                    'total'        => (int) $summary['total_parcel'],
                    'delivered'    => (int) $summary['success_parcel'],
                    'cancelled'    => (int) $summary['cancelled_parcel'],
                    'success_rate' => (int) $summary['success_ratio'],
                ],
                'min_rate' => $minRate
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while checking courier.',
            ], 500);
        }
    }


    public function testrun(Request $request)
    {
        $courierApi = FraudCheck::where('status', '1')->first();
        $api_key = $courierApi->pluck('api_key', 'name')->toArray();
        return $api_key['api_key'];
    }

    public function applyCoupon(Request $request)
    {
        $today = date('Y-m-d');

        // ১. প্রথমে চেক করুন কোডটি ডাটাবেসে আছে কি না
        $coupon = Coupons::where('code', $request->code)->where('status', 1)->first();

        if (!$coupon) {
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
            return response()->json(['status' => 'error', 'message' => 'Min. order amount ৳' . $coupon->min_cart_amount . ' required']);
        }
        $discount = 0;
        if ($coupon->discount_type == 'percent') {
            $discount = ($request->subtotal * (float)$coupon->discount) / 100;
        } else {
            $discount = (float)$coupon->discount;
        }
        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $discount
        ]);
        // ... আপনার আগের লজিক এখানে থাকবে ...

        return response()->json([
            'status' => 'success',
            'message' => 'Congrats! Coupon applied successfully.',
            'discount' => $discount
        ]);
    }


    public function storeIncompleteOrder(Request $request)
    {
        $request->validate([
            'phone' => 'required|min_digits:11',
        ]);
        $cart = session()->get('cart', []);

        // কার্ট থেকে শুধু প্রোডাক্ট আইডিগুলো সংগ্রহ করা
        $productCodes = [];
        foreach ($cart as $item) {
            $productCodes[] = $item['code'] ?? 'N/A'; // আপনি চাইলে আইডি বা নাম রাখতে পারেন
        }

        // ফোন নম্বর দিয়ে আপডেট বা নতুন তৈরি
        IncompleteOrders::updateOrCreate(
            ['phone' => $request->phone], // যদি এই ফোন অলরেডি থাকে তবে আপডেট হবে
            [
                'name'         => $request->name ?? 'Customer',
                'address'      => $request->address ?? 'N/A',
                'district'     => $request->district,
                'product_id'   => json_encode($productCodes), // Array হিসেবে সেভ
                'subtotal'     => $request->subtotal,
                'grand_total'  => $request->grand_total,
                'status'       => 'incomplete'
            ]
        );

        return response()->json(['status' => 'success']);
    }


    public function storeOrder(Request $request)
    {
        // ১. ভ্যালিডেশন (প্রয়োজনীয় ফিল্ড চেক)
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        try {
            DB::beginTransaction();

            // ২. Orders টেবিলে ডাটা ইনসার্ট
            $order = new Orders();
            $order->user_id = auth()->id() ?? 0;
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->district = $request->district; // আপনার ফর্ম থেকে আসা ডিস্ট্রিক্ট নাম
            $order->address = $request->address;
            $order->total_amount = $request->total_amount; // Subtotal
            $order->coupon_discount = $request->coupon_discount ?? 0;
            $order->delivery_charge = $request->delivery_charge;
            $order->grand_total = $request->grand_total;
            $order->coupon_code = $request->coupon_code;
            $order->payment_type = $request->payment;
            $order->note = $request->note;
            $order->payment_status = 'pending';
            $order->delivery_status = 'pending';
            $order->save();

            // Wallet Payment Process
            if ($request->payment === 'wallet') {
                if (!auth()->check()) {
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
                \App\Models\WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $grandTotal,
                    'payment_method' => 'wallet',
                    'type' => 'debit',
                    'status' => 'approved',
                    'payment_details' => json_encode(['order_id' => $order->id])
                ]);

                // Update order payment status
                $order->payment_status = 'paid';
                $order->save();
            }

            // ৩. OrderDetails টেবিলে লুপ চালিয়ে প্রোডাক্ট সেভ
            foreach ($cart as $item) {
                $orderDetail = new OrderDetails();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $item['id'];
                $orderDetail->product_attribute = $item['attribute'] ?? 'N/A';
                $orderDetail->product_colour = $item['color'] ?? 'N/A';
                $orderDetail->unit_price = (float)$item['price'];
                $orderDetail->product_qty = $item['quantity'];
                $orderDetail->total_price = (float)$item['price'] * (int)$item['quantity'];
                if (request()->hasCookie('referral_code')) {
                    $orderDetail->product_referral_code = request()->cookie('referral_code');
                }
                $orderDetail->save();
            }

            // ৪. ইনকমপ্লিট অর্ডার ডিলিট করা (ফোন নম্বর দিয়ে ম্যাচ করে)
            IncompleteOrders::where('phone', $request->phone)->delete();
            if ($request->coupon_code) {
                $coupon = Coupons::where('code', $request->coupon_code)->first();
                if ($coupon) {
                    $coupon->increment('used'); // used কলামের মান ১ বাড়বে
                }
            }

            DB::commit();

            // ৫. কার্ট ক্লিয়ার করা
            session()->forget('cart');

            return redirect()->route('order.invoice', $order->id)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
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
            ->post(env('BKASH_BASE_URL') . "/tokenized/checkout/token/grant", [
                'app_key'    => env('BKASH_APP_KEY'),
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
        ])->post(env('BKASH_BASE_URL') . "/tokenized/checkout/create", [
            'amount' => $payableAmount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => 'INV-' . time(),
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
            ])->post(env('BKASH_BASE_URL') . "/tokenized/checkout/execute", [
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
            $payment = new Payment();
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

            // গ. Orders টেবিলে ডাটা ইনসার্ট
            $order = new Orders();
            $order->user_id = auth()->id() ?? 0;
            $order->name = $orderData['name'];
            $order->phone = $orderData['phone'];
            $order->address = $orderData['address'];
            $order->district = $orderData['district']; // অথবা আইডি থেকে নাম
            $order->total_amount = $orderData['total_amount'];
            $order->grand_total = $orderData['grand_total'];
            $order->payment_type = 'prepaid';
            $order->payment_status = 'partial_paid';
            $order->payment_id = $payment->id; // Payments টেবিলের আইডি লিঙ্কিং
            $order->delivery_status = 'new';
            $order->save();

            // ঘ. Order Details সেভ
            foreach ($cart as $item) {
                OrderDetails::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_qty' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'product_attribute' => $item['attribute'] ?? 'N/A',
                    'product_colour' => $item['color'] ?? 'N/A',
                    'product_referral_code' => request()->cookie('referral_code'),
                ]);
            }

            DB::commit();
            session()->forget(['cart', 'pending_order_data']);

            return redirect()->route('order.invoice', $order->id)->with('order_placed', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout')->with('error', 'Critical Error: ' . $e->getMessage());
        }
    }





    // ------------- SSL Commerz ----------


    public function othersPayment(Request $request)
    {
        // ১. ফর্ম ডাটা সেশনে রাখা
        session()->put('pending_order_data', $request->all());

        $district = District::find($request->district_id);
        $payableAmount = $district->delivery_charge; // শুধুমাত্র ডেলিভারি চার্জ

        $post_data = [
            'store_id' => env('SSLC_STORE_ID'),
            'store_passwd' => env('SSLC_STORE_PASSWORD'),
            'total_amount' => $payableAmount,
            'currency' => "BDT",
            'tran_id' => "SSLC_" . uniqid(),
            'success_url' => route('ssl.success'),
            'fail_url' => route('ssl.fail'),
            'cancel_url' => route('ssl.cancel'),
            'cus_email' => $request->email ?? 'customer@mail.com',
            'cus_name' => $request->name,
            'cus_phone' => $request->phone,
            'cus_add1' => $request->address,
            'cus_city' => $district->name,
            'cus_country' => "Bangladesh",
            'shipping_method' => "NO",
            'product_name' => "Delivery Charge",
            'product_category' => "Ecommerce",
            'product_profile' => "general",
        ];

        $url = env('SSLC_BASE_URL') . "/gwprocess/v4/api.php";

        $response = Http::asForm()->withoutVerifying()->post($url, $post_data);
        $result = $response->json();

        if ($result['status'] === 'SUCCESS') {
            return redirect($result['GatewayPageURL']);
        }

        return redirect()->back()->with('error', 'SSLCommerz Error: ' . $result['failedreason']);
    }

    public function success(Request $request)
    {
        // SSL থেকে আসা ডাটা
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $orderData = session()->get('pending_order_data');


        try {
            DB::beginTransaction();

            // ১. Payment টেবিলে ডাটা রাখা
            $payment = new Payment();
            $payment->user_id = auth()->id() ?? 0;
            $payment->amount = $amount;
            $payment->paymentID = $tran_id;
            $payment->merchantInvoiceNumber = $request->input('bank_tran_id');
            $payment->trxID = $request->input('val_id');
            $payment->save();

            // ২. Order সেভ করা (সেশন থেকে ডাটা নিয়ে)
            $cart = session()->get('cart', []);
            if (!$orderData) {
                // যদি সেশন না পাওয়া যায়, তবে ডাটাবেসে পেমেন্ট লগ করে রাখুন (ডিব্যাগিং এর জন্য)
                \Log::error('SSL Success: Session Data Lost', ['request' => $request->all()]);
                return redirect()->route('checkout')->with('error', 'সেশন টাইমআউট হয়ে গেছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
            }

            $order = new Orders();
            $order->user_id = auth()->id() ?? 0;
            $order->name = $orderData['name'];
            $order->phone = $orderData['phone'];
            // $order->district = $orderData['district'];
            $order->address = $orderData['address'];
            $order->district = District::find($orderData['district_id'])->name;
            $order->total_amount = $orderData['total_amount'];
            $order->delivery_charge = $orderData['delivery_charge'];
            $order->coupon_discount = $orderData['coupon_discount'] ?? 0;
            $order->grand_total = $orderData['grand_total'];
            $order->coupon_code = $orderData['coupon_code'];
            $order->payment_type = $orderData['payment'];
            $order->note = $orderData['note'];
            $order->delivery_status = 'new';
            $order->payment_type = 'prepaid';
            $order->payment_status = 'partial_paid';
            $order->payment_id = $payment->id;
            $order->save();

            // ৩. Order Details
            foreach ($cart as $item) {
                OrderDetails::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_attribute' => $item['attribute'] ?? 'N/A',
                    'product_colour' => $item['color'] ?? 'N/A',
                    'product_qty' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'product_referral_code' => request()->cookie('referral_code'),
                ]);
            }
            IncompleteOrders::where('phone', $orderData['phone'])->delete();
            DB::commit();
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




    public function storeOrderTest(Request $request)
    {
        return $request;
    }
}
