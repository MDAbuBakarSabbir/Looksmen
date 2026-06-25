<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAPIS extends Model
{
    public function index (){
        $features = FeatureActivation::all();
        $featuresConfig = $features->pluck('status', 'name')->toArray();
        if ($featuresConfig['payment_api'] == '1') {
            return view('AdminDash.settings.api.payment.index');
        }
        abort(404);
    }
}
