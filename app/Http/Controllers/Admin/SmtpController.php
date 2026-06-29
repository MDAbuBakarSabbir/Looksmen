<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureActivation;
use App\Models\GeneralWebSettings;
use Illuminate\Http\Request;

class SmtpController extends Controller
{
    public function index(){
        $features = FeatureActivation::all();
        $featuresConfig = $features->pluck('status', 'name')->toArray();
        if($featuresConfig['email_verification'] == '1' || $featuresConfig['sms_verification'] == '1'){
            $smtpSettings = GeneralWebSettings::whereIn('name', [
                'mailhost', 'mailport', 'mailusername', 'mailpassword', 'mailaddress', 'mailencription',
                'sms_gateway_provider', 'sms_api_key', 'sms_sender_id', 'sms_api_url'
            ])->pluck('value', 'name')->toArray();
            return view('adminDash.settings.smtp', compact('featuresConfig', 'smtpSettings'));
        }
        abort(404);
    }

    public function store(Request $request)
    {
        $smtpDetails = [
            'mailhost'        => $request->mailhost,
            'mailport'        => $request->mailport,
            'mailusername'    => $request->mailusername,
            'mailpassword'    => $request->mailpassword,
            'mailaddress'     => $request->mailaddress,
            'mailencription'  => $request->mailencription,
        ];

        foreach ($smtpDetails as $name => $value) {
            GeneralWebSettings::updateOrCreate(
                ['name' => $name],
                ['value' => $value ?? '', 'status' => 1]
            );
        }

        return back()->with('success', 'SMTP Settings Updated Successfully!');
    }

    public function smsStore(Request $request)
    {
        $smsDetails = [
            'sms_gateway_provider' => $request->sms_gateway_provider,
            'sms_api_key'          => $request->sms_api_key,
            'sms_sender_id'        => $request->sms_sender_id,
            'sms_api_url'          => $request->sms_api_url,
        ];

        foreach ($smsDetails as $name => $value) {
            GeneralWebSettings::updateOrCreate(
                ['name' => $name],
                ['value' => $value ?? '', 'status' => 1]
            );
        }

        return back()->with('success', 'SMS Settings Updated Successfully!');
    }
}
