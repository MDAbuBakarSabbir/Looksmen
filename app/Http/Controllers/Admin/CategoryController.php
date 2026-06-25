<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index()
    {
        $maincategorys = Category::all();
        return view('adminDash.category.main.index', compact('maincategorys'));
    }

    public function store(Request $request)
    {
        $manager = new ImageManager(new Driver());

        $request->validate([
            'category_name' => 'required',
            'type' => 'required',
            'commission_rate' => 'required',
            'image' => 'required|image',
            'icon' => 'required',
        ]);
        if ($request->hasFile('image')) {
            $imgName = Str::random(7) . '.' . $request->file('image')->getClientOriginalExtension();
            $image = $manager->decode($request->file('image'));
            $image->save(base_path('public/adminDash/assets/img/category/'.$imgName));
        }
        Category::create([
                'name' => $request->category_name,
                'type' => $request->type,
                'commission_rate' => $request->commission_rate,
                'banner' => $imgName,
                'icon' => $request->icon,
                'slug' => Str::slug($request->name),
                'meta_title' => $request->meta_title,
                'meta_descritption' => $request->meta_description,
                'created_at' => now(),
            ]);

        return back()->with('success', 'created success');
    }
    public function edit()
    {
        return view('adminDash.category.main.edit');
    }
    public function update() {}


    public function status(Request $request)
    {
        $category = Category::find($request->id);

        if (!$category) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $category->status = $request->status == 1 ? 1 : 0;
        $category->save();

        return response()->json([
            'success' => true,
            'status' => $category->status
        ]);
    }
    public function destroy() {}
}
