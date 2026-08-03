<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentAPIS;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $paymentApis = PaymentAPIS::all()->keyBy('paymentapi_name');

        return view('adminDash.settings.api.payment.index', compact('paymentApis'));
    }

    public function bkash()
    {
        //
    }

    public function nagad()
    {
        //
    }

    public function rocket()
    {
        //
    }

    public function ssl()
    {
        //
    }

    public function update(Request $request)
    {
        $request->validate([
            'paymentapi_name' => 'required',
        ]);

        $api = PaymentAPIS::where('paymentapi_name', $request->paymentapi_name)->first();
        if (! $api) {
            return response()->json(['status' => 'error', 'message' => 'Gateway not found!']);
        }

        $api->update([
            'api_key' => $request->api_key,
            'api_secret' => $request->api_secret,
            'base_url' => $request->base_url,
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Credentials updated successfully!']);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $api = PaymentAPIS::find($request->id);
        if ($api) {
            $api->status = $api->status == '1' ? '0' : '1';
            $api->save();

            return response()->json(['status' => 'success', 'message' => 'Status changed successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Gateway not found!']);
    }
}
