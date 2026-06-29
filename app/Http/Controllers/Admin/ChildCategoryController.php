<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ChildCategoryController extends Controller
{
    public function index()
    {
        $maincategorys = Category::all();
        $categories = $maincategorys;
        $subcategories = SubCategory::all();
        $childcategories = ChildCategory::all();
        $activeTab = 'child';
        return view('adminDash.category.index', compact('maincategorys', 'categories', 'subcategories', 'childcategories', 'activeTab'));
    }
    public function create()
    {
        return view('adminDash.category.child.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'subCategory_id' => 'required',
            'childcategory_name' => 'required',
        ]);
        ChildCategory::create([
            'category_id'=>$request->category_id,
            'subcategory_id'=>$request->subCategory_id,
            'name'=>$request->childcategory_name,
            'slug'=>Str::slug($request->childcategory_name),
            'meta_title'=>$request->meta_title ?? Str::slug($request->childcategory_name),
            'meta_descritption'=>$request->meta_description ,
        ]);
        return back()->with('success','success');
    }
    public function edit($id)
    {
        $childcategory = ChildCategory::findOrFail($id);
        $categories = Category::all();
        $subcategories = SubCategory::all();
        return view('adminDash.category.child.edit', compact('childcategory', 'categories', 'subcategories'));
    }
    public function update(Request $request, $id)
    {
        $childcategory = ChildCategory::findOrFail($id);
        $request->validate([
            'category_id' => 'required',
            'subCategory_id' => 'required',
            'childcategory_name' => 'required',
        ]);

        $childcategory->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subCategory_id,
            'name' => $request->childcategory_name,
            'slug' => Str::slug($request->childcategory_name),
            'meta_title' => $request->meta_title,
            'meta_descritption' => $request->meta_description,
        ]);

        return redirect()->route('child-category.index')->with('success', 'Child category Updated successfully');
    }
    public function destroy($id)
    {
        $childcategory = ChildCategory::findOrFail($id);
        $childcategory->delete();
        return back()->with('success', 'Child category Deleted successfully');
    }
    public function status(Request $request)
    {
        $childcategory = ChildCategory::find($request->id);

        if (!$childcategory) {
            return response()->json(['success' => false]);
        }

        $childcategory->status = $request->status == 1 ? 1 : 0;
        $childcategory->save();

        return response()->json([
            'success' => true,
            'status' => $childcategory->status
        ]);
    }
}
