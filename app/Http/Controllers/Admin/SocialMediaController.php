<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $socialLinks = SocialMedia::all();
        return view('AdminDash.socialMedia.socialLinks', compact('socialLinks'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'social_icon' => 'required|string|max:50',
            'social_link' => 'required|url|max:255',
            'followers_count' => 'nullable|string|max:50',
            'secondary_count' => 'nullable|string|max:50',
        ]);
        try {
            $social = new SocialMedia();
            $social->social_icon = $request->social_icon;
            $social->social_link = $request->social_link;
            $social->followers_count = $request->followers_count ?? '0';
            $social->secondary_count = $request->secondary_count ?? '0';
            $social->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Social media link added successfully!',
                'data' => $social
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save data. ' . $e->getMessage()
            ], 500);
        }
    }





    public function status(Request $request)
    {
        $socialLinks = SocialMedia::find($request->id);

        if (!$socialLinks) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $socialLinks->status = $request->status == 1 ? 1 : 0;
        $socialLinks->save();

        return response()->json([
            'success' => true,
            'status' => $socialLinks->status
        ]);
    }



    public function destroy($id){
        $socialMedia = SocialMedia::where('id',$id)->first();
        try {
            // পেজ ডিলিট করার চেষ্টা
            $socialMedia->delete();

            // সফলভাবে ডিলিট হলে JSON রেসপন্স পাঠানো
            return response()->json([
                'success' => true,
                'message' => 'পেজটি সফলভাবে ডিলিট করা হয়েছে।'
            ], 200);

        } catch (\Exception $e) {
            // ডিলিট করার সময় কোনো ত্রুটি হলে
            // ত্রুটি বার্তা লগ করতে পারেন: Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'পেজ ডিলিট করার সময় একটি ত্রুটি হয়েছে: ' . $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
}
