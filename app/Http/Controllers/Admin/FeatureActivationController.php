<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\FeatureActivation;
use Illuminate\Http\Request;

class FeatureActivationController extends Controller
{
    public function index(){
        $features = FeatureActivation::all();
        $featuresConfig = $features->pluck('status', 'name')->toArray();
        return view('adminDash.settings.feature',compact('featuresConfig'));
    }

    public function status(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        $featureName = $request->name;
        $newStatus = $request->status;

        // 2. Find and Update the Feature (or create if not found)
        $setting = FeatureActivation::updateOrCreate(
            ['name' => $featureName],
            ['status' => $newStatus]
        );

        // 3. Success Response (সফল উত্তর)
        $action = $newStatus == 1 ? 'Activated' : 'Deactivated';
        return response()->json([
            'success' => true,
            'message' => "{$featureName} feature has been {$action} successfully!",
        ]);
    }
}
