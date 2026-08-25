<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
    public function index()
    {
        $maincategorys = Category::all();
        $categories = $maincategorys;
        $subcategories = SubCategory::all();
        $childcategories = ChildCategory::all();
        $activeTab = 'main';

        return view('adminDash.category.index', compact('maincategorys', 'categories', 'subcategories', 'childcategories', 'activeTab'));
    }

    public function store(Request $request)
    {
        $manager = new ImageManager(new Driver);

        $request->validate([
            'category_name' => 'required',
            'type' => 'required',
            'commission_rate' => 'required',
            'image' => 'required|image',
            'icon' => 'required',
        ]);
        if ($request->hasFile('image')) {
            $imgName = 'category_'.time().'_'.Str::random(5).'.webp';
            $image = $manager->decode($request->file('image'));
            $image->scaleDown(width: 800);
            $image->save(base_path('public/Uploads/'.$imgName), quality: 60);
        }
        Category::create([
            'name' => $request->category_name,
            'type' => $request->type,
            'commission_rate' => $request->commission_rate,
            'free_delivery_qty' => ($request->filled('free_delivery_qty') && (int)$request->free_delivery_qty >= 2) ? (int)$request->free_delivery_qty : null,
            'banner' => $imgName,
            'icon' => $request->icon,
            'slug' => Str::slug($request->category_name),
            'meta_title' => $request->meta_title,
            'meta_descritption' => $request->meta_description,
            'created_at' => now(),
        ]);

        return back()->with('success', 'created success');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('adminDash.category.main.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required',
            'type' => 'required',
            'commission_rate' => 'required',
            'icon' => 'required',
        ]);

        $imgName = $category->banner;
        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver);
            $imgName = 'category_'.$category->id.'_'.time().'_'.Str::random(5).'.webp';
            $image = $manager->decode($request->file('image'));
            $image->scaleDown(width: 800);
            $image->save(base_path('public/Uploads/'.$imgName), quality: 60);
            if (! empty($category->banner) && file_exists(base_path('public/Uploads/'.$category->banner))) {
                @unlink(base_path('public/Uploads/'.$category->banner));
            }
        }

        $category->update([
            'name' => $request->category_name,
            'type' => $request->type,
            'commission_rate' => $request->commission_rate,
            'free_delivery_qty' => ($request->filled('free_delivery_qty') && (int)$request->free_delivery_qty >= 2) ? (int)$request->free_delivery_qty : null,
            'banner' => $imgName,
            'icon' => $request->icon,
            'slug' => Str::slug($request->category_name),
            'meta_title' => $request->meta_title,
            'meta_descritption' => $request->meta_description,
        ]);

        return redirect()->route('category.index')->with('success', 'Category Updated successfully');
    }

    public function status(Request $request)
    {
        $category = Category::find($request->id);

        if (! $category) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $category->status = $request->status == 1 ? 1 : 0;
        $category->save();

        return response()->json([
            'success' => true,
            'status' => $category->status,
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if (! empty($category->banner) && file_exists(base_path('public/Uploads/'.$category->banner))) {
            @unlink(base_path('public/Uploads/'.$category->banner));
        }
        $category->delete();

        return back()->with('success', 'Category Deleted successfully');
    }
}
