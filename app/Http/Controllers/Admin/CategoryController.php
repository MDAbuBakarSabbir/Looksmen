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
        $categories = $maincategorys;
        $subcategories = \App\Models\SubCategory::all();
        $childcategories = \App\Models\ChildCategory::all();
        $activeTab = 'main';
        return view('adminDash.category.index', compact('maincategorys', 'categories', 'subcategories', 'childcategories', 'activeTab'));
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
            $manager = new ImageManager(new Driver());
            $imgName = Str::random(7) . '.' . $request->file('image')->getClientOriginalExtension();
            $image = $manager->decode($request->file('image'));
            $image->save(base_path('public/adminDash/assets/img/category/'.$imgName));
            if (!empty($category->banner) && file_exists(base_path('public/adminDash/assets/img/category/'.$category->banner))) {
                @unlink(base_path('public/adminDash/assets/img/category/'.$category->banner));
            }
        }

        $category->update([
            'name' => $request->category_name,
            'type' => $request->type,
            'commission_rate' => $request->commission_rate,
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
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if (!empty($category->banner) && file_exists(base_path('public/adminDash/assets/img/category/'.$category->banner))) {
            @unlink(base_path('public/adminDash/assets/img/category/'.$category->banner));
        }
        $category->delete();
        return back()->with('success', 'Category Deleted successfully');
    }
}
