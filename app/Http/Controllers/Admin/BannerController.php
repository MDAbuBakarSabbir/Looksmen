<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();

        return view('adminDash.slider&banner.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('adminDash.category.main.create');
    }

    public function store(Request $request)
    {
        $manager = new ImageManager(new Driver);
        $request->validate([
            'image' => 'required|image',
        ]);
        if ($request->hasFile('image')) {
            $dir = base_path('public/Uploads');
            if (! file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $newname = 'banner_'.time().'_'.Str::random(5).'.webp';
            $image = $manager->decode($request->file('image'));
            $image->save($dir.'/'.$newname, quality: 85);
        }

        $banners = new Banner;
        $banners->image = $newname;
        $banners->url = $request->url;
        $banners->created_at = now();
        $banners->save();

        return response()->json([
            'success' => true,
            'data' => $banners,
            'message' => 'Banner Added successfully!',
        ]);
    }

    public function status(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        if (! $banner) {
            return response()->json(['success' => false]);
        }
        $banner->status = $request->status == 1 ? 1 : 0;
        $banner->save();

        return response()->json([
            'success' => true,
            'status' => $banner->status,
        ]);
    }

    public function edit($id)
    {
        $banner = Banner::where('id', $id)->first();

        return view('adminDash.slider&banner.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $dir = base_path('public/Uploads');
            if (! file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $oldPath = $dir.'/'.$banner->image;
            if ($banner->image && file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            $manager = new ImageManager(new Driver);
            $newname = 'banner_'.$banner->id.'_'.time().'_'.Str::random(5).'.webp';
            $image = $manager->decode($request->file('image'));
            $image->save($dir.'/'.$newname, quality: 85);
            $banner->image = $newname;
        }

        $banner->url = $request->url;
        $banner->save();

        return redirect()->route('banner.index')->with('success', 'Banner Updated successfully!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        $oldPath = base_path('public/Uploads/'.$banner->image);
        if ($banner->image && file_exists($oldPath) && is_file($oldPath)) {
            unlink($oldPath);
        }

        $banner->delete();

        return redirect()->route('banner.index')->with('success', 'Banner Deleted successfully!');
    }

    public function alluploads()
    {
        $uploadPath = public_path('Uploads');
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];

        $files = [];
        foreach ($extensions as $ext) {
            $files = array_merge(
                $files,
                glob($uploadPath.DIRECTORY_SEPARATOR.'*.'.$ext) ?: [],
                glob($uploadPath.DIRECTORY_SEPARATOR.'*.'.strtoupper($ext)) ?: []
            );
        }

        $images = array_map(fn ($path) => basename($path), $files);

        return view('adminDash.allUploads', compact('images'));
    }
}
