<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\PointTransaction;
use App\Models\GeneralWebSettings;
use App\Models\FeatureActivation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletPointController extends Controller
{
    public function myWallet()
    {
        $features = FeatureActivation::pluck('status', 'name')->toArray();
        if (!isset($features['wallet_system']) || $features['wallet_system'] !== '1') {
            abort(404, 'Wallet system is not activated.');
        }

        $user = Auth::user();
        $wallet_transactions = WalletTransaction::where('user_id', $user->id)->latest()->paginate(10, ['*'], 'wallet_page');
        $point_transactions = PointTransaction::where('user_id', $user->id)->latest()->paginate(10, ['*'], 'points_page');

        $point_conversion_rate = (int) (GeneralWebSettings::where('name', 'point_conversion_rate')->first()->value ?? 100);

        return view('Frontend.dashboard.myWallet', compact('user', 'wallet_transactions', 'point_transactions', 'point_conversion_rate', 'features'));
    }

    public function recharge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:bkash,manual_deposit',
            'trx_id' => 'required_if:payment_method,manual_deposit|nullable|string|max:100',
            'bank_info' => 'required_if:payment_method,manual_deposit|nullable|string|max:500',
        ]);

        $user = Auth::user();
        $amount = (float) $request->amount;

        if ($request->payment_method === 'manual_deposit') {
            // Save manual recharge transaction with status 'pending'
            $details = [
                'trx_id' => $request->trx_id,
                'bank_info' => $request->bank_info,
                'note' => $request->note ?? ''
            ];

            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'payment_method' => 'manual_deposit',
                'type' => 'credit',
                'status' => 'pending',
                'payment_details' => json_encode($details),
            ]);

            flash('Your manual deposit request has been submitted. Admin will approve it shortly.')->success();
            return redirect()->route('myWallet');
        }

        // For bKash online gateway recharge
        return $this->startBkashRecharge($amount);
    }

    private function getBkashToken()
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

        $message = $result['statusMessage'] ?? 'Unknown Error';
        $code = $result['statusCode'] ?? '9999';
        throw new \Exception("bKash Error ($code): $message");
    }

    private function startBkashRecharge($amount)
    {
        try {
            session()->put('pending_recharge_amount', $amount);

            $token = $this->getBkashToken();

            $response = Http::withHeaders([
                'Authorization' => $token,
                'X-APP-Key' => env('BKASH_APP_KEY'),
            ])->post(env('BKASH_BASE_URL') . "/tokenized/checkout/create", [
                'amount' => $amount,
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => 'REC-' . time(),
                'callbackURL' => route('wallet.recharge.bkash.callback'),
            ]);

            $resData = $response->json();
            if (isset($resData['bkashURL'])) {
                return redirect($resData['bkashURL']);
            }

            flash('Failed to initiate bKash payment gateway.')->error();
            return redirect()->route('myWallet');
        } catch (\Exception $e) {
            Log::error('Wallet Recharge bKash Error: ' . $e->getMessage());
            flash('bKash gateway initiation error: ' . $e->getMessage())->error();
            return redirect()->route('myWallet');
        }
    }

    public function bkashCallback(Request $request)
    {
        if ($request->status === 'success') {
            try {
                $token = $this->getBkashToken();
                $response = Http::withHeaders([
                    'Authorization' => $token,
                    'X-APP-Key' => env('BKASH_APP_KEY'),
                ])->post(env('BKASH_BASE_URL') . "/tokenized/checkout/execute", [
                    'paymentID' => $request->paymentID,
                ]);

                $result = $response->json();

                if (isset($result['transactionStatus']) && $result['transactionStatus'] === 'Completed') {
                    $amount = (float) session()->get('pending_recharge_amount', 0);
                    $user = Auth::user();

                    DB::beginTransaction();

                    // Credit user balance
                    $user->wallet_balance += $amount;
                    $user->save();

                    // Create approved transaction log
                    WalletTransaction::create([
                        'user_id' => $user->id,
                        'amount' => $amount,
                        'payment_method' => 'bkash',
                        'type' => 'credit',
                        'status' => 'approved',
                        'payment_details' => json_encode([
                            'paymentID' => $result['paymentID'],
                            'trxID' => $result['trxID'],
                            'customerMsisdn' => $result['customerMsisdn'] ?? 'N/A',
                        ]),
                    ]);

                    DB::commit();

                    session()->forget('pending_recharge_amount');
                    flash('Wallet recharged successfully via bKash!')->success();
                    return redirect()->route('myWallet');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Wallet Recharge execution error: ' . $e->getMessage());
                flash('Recharge callback verification failed.')->error();
                return redirect()->route('myWallet');
            }
        }

        flash('Wallet recharge was cancelled or failed.')->error();
        return redirect()->route('myWallet');
    }

    public function convertPoints(Request $request)
    {
        $features = FeatureActivation::pluck('status', 'name')->toArray();
        if (!isset($features['point_system']) || $features['point_system'] !== '1') {
            abort(404, 'Point system is not activated.');
        }

        $request->validate([
            'points' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $points_to_convert = (int) $request->points;

        if ($user->points < $points_to_convert) {
            flash('You do not have enough points.')->error();
            return redirect()->route('myWallet');
        }

        $point_conversion_rate = (int) (GeneralWebSettings::where('name', 'point_conversion_rate')->first()->value ?? 100);

        if ($points_to_convert < $point_conversion_rate) {
            flash("Minimum conversion threshold is {$point_conversion_rate} points.")->error();
            return redirect()->route('myWallet');
        }

        // Calculate credit amount
        $credit_amount = floor($points_to_convert / $point_conversion_rate);
        $points_used = $credit_amount * $point_conversion_rate; // exact points converted

        if ($credit_amount <= 0) {
            flash('Conversion resulted in 0 credit amount.')->error();
            return redirect()->route('myWallet');
        }

        try {
            DB::beginTransaction();

            // 1. Deduct points from user
            $user->points -= $points_used;
            // 2. Credit wallet balance
            $user->wallet_balance += $credit_amount;
            $user->save();

            // 3. Record point transaction log
            PointTransaction::create([
                'user_id' => $user->id,
                'points' => -$points_used,
                'type' => 'convert',
                'details' => "Converted {$points_used} points to wallet balance.",
            ]);

            // 4. Record wallet transaction log
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $credit_amount,
                'payment_method' => 'points_conversion',
                'type' => 'credit',
                'status' => 'approved',
                'payment_details' => json_encode(['points_converted' => $points_used]),
            ]);

            DB::commit();
            flash("Successfully converted {$points_used} points to ৳{$credit_amount} wallet balance!")->success();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Points conversion error: ' . $e->getMessage());
            flash('Something went wrong during points conversion.')->error();
        }

        return redirect()->route('myWallet');
    }
}
