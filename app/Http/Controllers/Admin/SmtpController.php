<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureActivation;
use Illuminate\Http\Request;

class SmtpController extends Controller
{
    public function index(){
        $features = FeatureActivation::all();
        $featuresConfig = $features->pluck('status', 'name')->toArray();
        if($featuresConfig['email_verification'] == '1' || $featuresConfig['sms_verification'] == '1'){
            return view('adminDash.settings.smtp',compact('featuresConfig'));
        }
        abort(404);
    }
}
