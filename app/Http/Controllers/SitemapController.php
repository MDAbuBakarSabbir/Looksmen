<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\Pages;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::where('status', '1')->orderBy('updated_at', 'desc')->get();
        $categories = Category::where('status', '1')->orderBy('updated_at', 'desc')->get();
        $subcategories = SubCategory::with('category')->where('status', '1')->orderBy('updated_at', 'desc')->get();
        $childcategories = ChildCategory::with('subcategory.category')->where('status', '1')->orderBy('updated_at', 'desc')->get();
        $pages = Pages::where('status', 1)->orderBy('updated_at', 'desc')->get();

        return response()->view('sitemap', [
            'products' => $products,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'childcategories' => $childcategories,
            'pages' => $pages,
        ])->header('Content-Type', 'text/xml');
    }
}
