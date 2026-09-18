<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::latest()->get();
        $products = Product::with('firstImage')->where('status', '1')->select('id', 'title', 'code', 'old_price', 'new_price')->get();

        $totalCampaigns = $campaigns->count();
        $activeCampaigns = $campaigns->where('status', '1')->filter(function ($c) {
            $now = now();
            return $c->start_date <= $now && $c->end_date >= $now;
        })->count();
        $upcomingCampaigns = $campaigns->where('status', '1')->filter(function ($c) {
            return $c->start_date > now();
        })->count();
        $expiredCampaigns = $campaigns->filter(function ($c) {
            return $c->end_date < now();
        })->count();

        return view('adminDash.promotion&coupons.campain', compact(
            'campaigns',
            'products',
            'totalCampaigns',
            'activeCampaigns',
            'upcomingCampaigns',
            'expiredCampaigns'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'discount_type' => 'nullable|string',
            'discount_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            try {
                $manager = new ImageManager(new Driver);
                $dir = base_path('public/Uploads');
                if (! file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $imageName = 'campaign_'.time().'_'.Str::random(6).'.webp';
                $img = $manager->decode($request->file('image'));
                $img->scaleDown(width: 1200);
                $img->save($dir.'/'.$imageName, quality: 75);
            } catch (\Exception $e) {
                // Fallback standard upload
                $file = $request->file('image');
                $imageName = 'campaign_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
                $file->move(base_path('public/Uploads'), $imageName);
            }
        }

        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug ?: 'campaign';
        $count = 1;
        while (Campaign::where('slug', $slug)->exists()) {
            $slug = ($baseSlug ?: 'campaign').'-'.$count++;
        }

        $productIds = null;
        if ($request->has('products')) {
            $productIds = is_array($request->products) ? json_encode($request->products) : $request->products;
        }

        $campaign = Campaign::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imageName,
            'product_ids' => $productIds,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'discount_type' => $request->discount_type ?? 'percentage',
            'discount_amount' => $request->discount_amount ?? $request->discount ?? 0,
            'description' => $request->description,
            'status' => '1',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Campaign created successfully!',
                'campaign' => $campaign,
            ]);
        }

        return redirect()->route('campaign')->with('success', 'Campaign created successfully!');
    }

    public function status(Request $request)
    {
        $campaign = Campaign::findOrFail($request->id);
        $campaign->status = ($campaign->status == '1' || $campaign->status === 1) ? '0' : '1';
        $campaign->save();

        return response()->json([
            'success' => true,
            'status' => $campaign->status,
            'message' => $campaign->status == '1' ? 'Campaign activated successfully!' : 'Campaign deactivated successfully!',
        ]);
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        $selectedProductIds = [];
        if (! empty($campaign->product_ids)) {
            $decoded = json_decode($campaign->product_ids, true);
            if (is_array($decoded)) {
                $selectedProductIds = $decoded;
            } else {
                $selectedProductIds = array_map('trim', explode(',', $campaign->product_ids));
            }
        }

        return response()->json([
            'success' => true,
            'campaign' => $campaign,
            'selected_products' => $selectedProductIds,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:campains,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'discount_type' => 'nullable|string',
            'discount_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $campaign = Campaign::findOrFail($request->id);

        if ($request->hasFile('image')) {
            $dir = base_path('public/Uploads');
            if ($campaign->image && file_exists($dir.'/'.$campaign->image)) {
                @unlink($dir.'/'.$campaign->image);
            }

            try {
                $manager = new ImageManager(new Driver);
                $imageName = 'campaign_'.time().'_'.Str::random(6).'.webp';
                $img = $manager->decode($request->file('image'));
                $img->scaleDown(width: 1200);
                $img->save($dir.'/'.$imageName, quality: 75);
            } catch (\Exception $e) {
                $file = $request->file('image');
                $imageName = 'campaign_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
                $file->move($dir, $imageName);
            }
            $campaign->image = $imageName;
        }

        if ($campaign->name !== $request->name) {
            $baseSlug = Str::slug($request->name);
            $slug = $baseSlug ?: 'campaign';
            $count = 1;
            while (Campaign::where('slug', $slug)->where('id', '!=', $campaign->id)->exists()) {
                $slug = ($baseSlug ?: 'campaign').'-'.$count++;
            }
            $campaign->slug = $slug;
        }

        $productIds = null;
        if ($request->has('products')) {
            $productIds = is_array($request->products) ? json_encode($request->products) : $request->products;
        }

        $campaign->name = $request->name;
        $campaign->product_ids = $productIds;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->discount_type = $request->discount_type ?? 'percentage';
        $campaign->discount_amount = $request->discount_amount ?? $request->discount ?? 0;
        $campaign->description = $request->description;
        $campaign->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Campaign updated successfully!',
                'campaign' => $campaign,
            ]);
        }

        return redirect()->route('campaign')->with('success', 'Campaign updated successfully!');
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        if ($campaign->image && file_exists(base_path('public/Uploads/'.$campaign->image))) {
            @unlink(base_path('public/Uploads/'.$campaign->image));
        }
        $campaign->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Campaign deleted successfully!',
            ]);
        }

        return redirect()->route('campaign')->with('success', 'Campaign deleted successfully!');
    }
}
