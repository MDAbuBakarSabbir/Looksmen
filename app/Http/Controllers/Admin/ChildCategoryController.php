<?php

namespace App\Http\Controllers\admin;
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
        $subcategories = SubCategory::all();
        $categories = Category::all();
        $childcategories = ChildCategory::all();
        return view('adminDash.category.child.index',compact('childcategories','subcategories','categories'));
    }
    public function create()
    {
        return view('adminDash.category.child.create');
    }
    public function store(Request $request)
    {
        $request->validate([

        ]);
        ChildCategory::create([
            'category_id'=>$request->category_id,
            'subcategory_id'=>$request->subCategory_id,
            'name'=>$request->childcategory_name,
            'slug'=>Str::slug($request->childcategory_name),
            'meta_title'=>Str::slug($request->childcategory_name),
            'meta_descritption'=>$request->meta_description ,
        ]);
        return back()->with('success','success');
    }
    public function edit()
    {
        return view('adminDash.category.child.edit');
    }
    public function update()
    {

    }
    public function destroy()
    {

    }
}
