<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Pages;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class FrontCategoryController extends Controller
{
    public function catProductView($slug, $id)
    {
        $category = Category::with('subcategories.childcategories')->findOrFail($id);
        $catProducts = Product::where('category_id', $category->id)->where('status', '1')->latest()->paginate(12);
        return view('Frontend.category.catProduct', compact('catProducts', 'category'));

    }
    public function subCatProductView($slug, $id)
    {
        $subcategory = Category::with('subcategories.childcategories')->findOrFail($id);
        $subcatProducts = Product::where('subcategory_id', $subcategory->subcategories->id)->where('status', '1')->latest()->paginate(12);
        return view('Frontend.category.subcatProduct', compact('subcatProducts', 'subcategory'));

    }

    public function ProductView($slug, $id)
    {
        $singleProduct = Product::with('productImages', 'reviews')->findOrFail($id);
        $relProducts = Product::where('category_id', $singleProduct->category_id)->where('id', '!=', $id)->where('status', '1')->with('firstImage')->latest()->paginate(5);
        $topSellingProducts = Product::where('status', '1')->where('id', '!=', $id)
        ->with('firstImage')->withCount('orderDetails')->orderBy('order_details_count', 'desc')->take(5)->get();

        return view('Frontend.productView', compact('singleProduct', 'relProducts','topSellingProducts'));
    }


    public function allcategory()
    {
        $allCategories = Category::with('subcategories.childcategories')->get();
        return view('Frontend.allCategories', compact('allCategories'));
    }

    public function pages($slug){
        $page = Pages::where('slug',$slug)->where('status',1)->first();
        return view('Frontend.pages.pages',compact('page'));
    }

    public function ProductCompare(){
        $compareSession = session()->get('compare', []);
        $productIds = array_keys($compareSession);
        $products = Product::whereIn('id', $productIds)->with('firstImage')->get();
        return view('Frontend.compare', compact('products'));
    }
}
