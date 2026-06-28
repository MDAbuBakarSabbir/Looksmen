<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    public function froudCheck(){
        return view('adminDash.settings.api.fraudCheck');
    }

    public function updateFraudCheck(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'required|string|max:255',
            'base_url' => 'nullable|string|url|max:255',
        ]);

        $baseUrl = $validated['base_url'] ?? 'https://api.bdcourier.com/courier-check';

        // 1. Update or create in fraud_checks table
        \App\Models\FraudCheck::updateOrCreate(
            ['name' => 'bdcourier'],
            [
                'api_key' => $validated['api_key'],
                'base_url' => $baseUrl,
            ]
        );

        // 2. Update or create in general_web_settings table
        \App\Models\GeneralWebSettings::updateOrCreate(
            ['name' => 'fraud_check_api_key'],
            ['value' => $validated['api_key'], 'status' => 1]
        );

        \App\Models\GeneralWebSettings::updateOrCreate(
            ['name' => 'fraud_check_api_url'],
            ['value' => $baseUrl, 'status' => 1]
        );

        return response()->json([
            'success' => true,
            'message' => 'Fraud check API credentials updated successfully!'
        ]);
    }
}
