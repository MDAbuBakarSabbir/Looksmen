<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('adminDash.slider&banner.banner.index',compact('banners'));
    }
    public function create()
    {
        return view('adminDash.category.main.create');
    }
    public function store(Request $request)
    {
        $manager = new ImageManager(new Driver());
        $request->validate([
            'image' => 'required|image'
        ]);
        if ($request->hasFile('image')) {
            $newname = Str::random(5) . '.' . $request->file('image')->getClientOriginalExtension();
            $image = $manager->decode($request->file('image'));
            $image->save(base_path('public/adminDash/uploads/slider&banner/' . $newname));
        }

        $banners = new Banner();
        $banners->image = $newname;
        $banners->url = $request->url;
        $banners->created_at = now();
        $banners->save();

        return response()->json([
            'success'=> true,
            'data'=>$banners,
            'message' => 'Banner Added successfully!'
        ]);
    }
    public function status(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        if (!$banner) {
            return response()->json(['success' => false]);
        }
        $banner->status = $request->status == 1 ? 1 : 0;
        $banner->save();

        return response()->json([
            'success' => true,
            'status' => $banner->status
        ]);
    }
    public function edit($id)
    {
        $banner = Banner::where('id',$id)->first();
        return view('adminDash.slider&banner.banner.edit',compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {
            $oldPath = base_path('public/adminDash/uploads/slider&banner/' . $banner->image);
            if ($banner->image && file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            $manager = new ImageManager(new Driver());
            $newname = Str::random(5) . '.' . $request->file('image')->getClientOriginalExtension();
            $image = $manager->decode($request->file('image'));
            $image->save(base_path('public/adminDash/uploads/slider&banner/' . $newname));
            $banner->image = $newname;
        }

        $banner->url = $request->url;
        $banner->save();

        return redirect()->route('banner.index')->with('success', 'Banner Updated successfully!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        
        $oldPath = base_path('public/adminDash/uploads/slider&banner/' . $banner->image);
        if ($banner->image && file_exists($oldPath) && is_file($oldPath)) {
            unlink($oldPath);
        }
        
        $banner->delete();
        return redirect()->route('banner.index')->with('success', 'Banner Deleted successfully!');
    }
}
