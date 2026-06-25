<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::all();
        $categories = Category::all();

        return view('adminDash.category.sub.index',compact('subcategories','categories'));
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
    public function edit()
    {
        return view('adminDash.category.sub.edit');
    }
    public function update()
    {

    }
    public function destroy()
    {

    }
}
