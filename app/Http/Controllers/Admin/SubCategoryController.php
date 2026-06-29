<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class SubCategoryController extends Controller
{
    public function index()
    {
        $maincategorys = Category::all();
        $categories = $maincategorys;
        $subcategories = SubCategory::all();
        $childcategories = \App\Models\ChildCategory::all();
        $activeTab = 'sub';
        return view('adminDash.category.index', compact('maincategorys', 'categories', 'subcategories', 'childcategories', 'activeTab'));
    }
    public function create()
    {
        return view('adminDash.category.sub.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'category_id'=>'required',
            'subcategory_name'=>'required',
        ]);
        SubCategory::create([
            'category_id'=>$request->category_id ,
            'name'=>$request->subcategory_name ,
            'slug'=>Str::slug($request->subcategory_name)  ,
            'meta_title'=>$request->meta_title ,
            'meta_descritption'=>$request->meta_description ,
        ]);
        return back()->with('success','success');
    }
    public function edit($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $categories = Category::all();
        return view('adminDash.category.sub.edit', compact('subcategory', 'categories'));
    }
    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $request->validate([
            'category_id' => 'required',
            'subcategory_name' => 'required',
        ]);

        $subcategory->update([
            'category_id' => $request->category_id,
            'name' => $request->subcategory_name,
            'slug' => Str::slug($request->subcategory_name),
            'meta_title' => $request->meta_title,
            'meta_descritption' => $request->meta_description,
        ]);

        return redirect()->route('sub-category.index')->with('success', 'Subcategory Updated successfully');
    }
    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();
        return back()->with('success', 'Subcategory Deleted successfully');
    }
    public function status(Request $request)
    {
        $subcategory = SubCategory::find($request->id);

        if (!$subcategory) {
            return response()->json(['success' => false]);
        }

        $subcategory->status = $request->status == 1 ? 1 : 0;
        $subcategory->save();

        return response()->json([
            'success' => true,
            'status' => $subcategory->status
        ]);
    }
}
