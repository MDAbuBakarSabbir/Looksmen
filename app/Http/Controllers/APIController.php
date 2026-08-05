<?php

namespace App\Http\Controllers;

use App\Models\FeatureActivation;
use App\Models\FraudCheck;
use App\Models\GeneralWebSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class APIController extends Controller
{
    public function froudCheck()
    {
        $providers = FraudCheck::all()->keyBy('name');
        $featuresConfig = FeatureActivation::pluck('status', 'name')->toArray();
        return view('adminDash.settings.api.fraudCheck', compact('providers', 'featuresConfig'));
    }

    public function updateFraudCheck(Request $request)
    {
        $validated = $request->validate([
            'provider_name' => 'required|string|in:bdcourier,zachaikori,fraudshield',
            'api_key' => 'required|string|max:255',
            'base_url' => 'nullable|string|url|max:255',
            'status' => 'nullable|boolean',
        ]);

        $provider = $validated['provider_name'];
        $defaultUrls = [
            'bdcourier' => 'https://bdcourier.com/api/courier-check',
            'zachaikori' => 'https://api.zachaikori.com/v1/check',
            'fraudshield' => 'https://api.fraudshieldbd.com/v1/check',
        ];

        $baseUrl = $validated['base_url'] ?: ($defaultUrls[$provider] ?? '');
        $status = isset($validated['status']) ? ($validated['status'] ? '1' : '0') : '1';

        // 1. Update or create in fraud_checks table
        $fraudChecker = FraudCheck::updateOrCreate(
            ['name' => $provider],
            [
                'api_key' => $validated['api_key'],
                'base_url' => $baseUrl,
                'status' => $status,
            ]
        );

        // 2. Update or create in general_web_settings table
        GeneralWebSettings::updateOrCreate(
            ['name' => 'fraud_check_api_key'],
            ['value' => $validated['api_key'], 'status' => 1]
        );

        GeneralWebSettings::updateOrCreate(
            ['name' => 'fraud_check_api_url'],
            ['value' => $baseUrl, 'status' => 1]
        );

        GeneralWebSettings::updateOrCreate(
            ['name' => 'fraud_check_active_provider'],
            ['value' => $provider, 'status' => 1]
        );

        Cache::forget('fraud_check_settings_first');

        return response()->json([
            'success' => true,
            'message' => ucfirst($provider) . ' Fraud Check API credentials updated successfully!',
            'provider' => $fraudChecker,
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $validated = $request->validate([
            'provider_name' => 'required|string|in:bdcourier,zachaikori,fraudshield',
            'status' => 'required|boolean',
        ]);

        $provider = $validated['provider_name'];
        $statusStr = $validated['status'] ? '1' : '0';

        // Update status for this provider
        $fraudChecker = FraudCheck::updateOrCreate(
            ['name' => $provider],
            ['status' => $statusStr]
        );

        // Also sync active status in general_web_settings
        GeneralWebSettings::updateOrCreate(
            ['name' => 'fraud_check_status_' . $provider],
            ['value' => $statusStr, 'status' => 1]
        );

        Cache::forget('fraud_check_settings_first');

        $statusText = $validated['status'] ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => ucfirst($provider) . ' fraud check status has been ' . $statusText . ' successfully!',
            'status' => $statusStr,
        ]);
    }

    public function toggleFeature(Request $request)
    {
        $validated = $request->validate([
            'feature_name' => 'required|string|in:fraud_check_frontend,fraud_check_order,fraud_check_incomplete_order,fraud_check_api',
            'status' => 'required|boolean',
        ]);

        $featureName = $validated['feature_name'];
        $statusStr = $validated['status'] ? '1' : '0';

        // Rule: To activate any fraud check feature option, minimum 1 provider must be active!
        if ($validated['status']) {
            $activeProvidersCount = FraudCheck::where('status', '1')->count();
            if ($activeProvidersCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'To activate this option, at least 1 Fraud Check API Provider (BD Courier, Zachaikori, or FraudShield BD) must be active!',
                ]);
            }
        }

        // Update in FeatureActivation table
        FeatureActivation::updateOrCreate(
            ['name' => $featureName],
            ['status' => $statusStr]
        );

        Cache::forget('feature_activations_map');

        $featureLabels = [
            'fraud_check_frontend' => 'Frontend Check',
            'fraud_check_order' => 'Order Check',
            'fraud_check_incomplete_order' => 'Incomplete Order Check',
        ];

        $label = $featureLabels[$featureName] ?? $featureName;
        $statusText = $validated['status'] ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "{$label} option has been {$statusText} successfully!",
            'status' => $statusStr,
        ]);
    }
}
