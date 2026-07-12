<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pages;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use Illuminate\Http\Request;

class FrontCategoryController extends Controller
{
    public function catProductView($id, $slug)
    {
        $category = Category::with('subcategories.childcategories')->findOrFail($id);
        $catProducts = Product::where('category_id', $category->id)
            ->where('status', '1')
            ->with('firstImage')
            ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
            ->latest()
            ->paginate(12);
        
        $categoryType = 'category';
        $parentCategory = $category;

        return view('Frontend.category.catProduct', compact('catProducts', 'category', 'categoryType', 'parentCategory'));
    }

    public function subCatProductView($id, $slug)
    {
        $category = SubCategory::with('childcategories', 'category.subcategories')->findOrFail($id);
        $catProducts = Product::where('subcategory_id', $category->id)
            ->where('status', '1')
            ->with('firstImage')
            ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
            ->latest()
            ->paginate(12);
        
        $categoryType = 'subcategory';
        $parentCategory = $category->category;

        return view('Frontend.category.catProduct', compact('catProducts', 'category', 'categoryType', 'parentCategory'));
    }

    public function childCatProductView($id, $slug)
    {
        $category = ChildCategory::with('subcategory.category.subcategories', 'subcategory.childcategories')->findOrFail($id);
        $catProducts = Product::where('childcategory_id', $category->id)
            ->where('status', '1')
            ->with('firstImage')
            ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
            ->latest()
            ->paginate(12);
        
        $categoryType = 'childcategory';
        $parentSubCategory = $category->subcategory;
        $parentCategory = $category->subcategory->category;

        return view('Frontend.category.catProduct', compact('catProducts', 'category', 'categoryType', 'parentCategory', 'parentSubCategory'));
    }

    public function ProductView($id, $slug)
    {
        $singleProduct = Product::with('productImages', 'reviews')->findOrFail($id);
        $relProducts = Product::where('category_id', $singleProduct->category_id)
            ->where('id', '!=', $id)
            ->where('status', '1')
            ->with('firstImage')
            ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
            ->latest()
            ->paginate(5);
        $topSellingProducts = Product::where('status', '1')->where('id', '!=', $id)
            ->with('firstImage')
            ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
            ->withCount('orderDetails')
            ->orderBy('order_details_count', 'desc')
            ->take(5)
            ->get();

        return view('Frontend.productView', compact('singleProduct', 'relProducts', 'topSellingProducts'));
    }

    public function allcategory()
    {
        $allCategories = \Illuminate\Support\Facades\Cache::remember('all_categories_page_cached', 3600, function () {
            return Category::with('subcategories.childcategories')->get();
        });
        if (!($allCategories instanceof \Illuminate\Support\Collection)) {
            \Illuminate\Support\Facades\Cache::forget('all_categories_page_cached');
            $allCategories = Category::with('subcategories.childcategories')->get();
            \Illuminate\Support\Facades\Cache::put('all_categories_page_cached', $allCategories, 3600);
        }

        return view('Frontend.allCategories', compact('allCategories'));
    }

    public function pages($slug)
    {
        $page = Pages::where('slug', $slug)->where('status', 1)->first();

        return view('Frontend.pages.pages', compact('page'));
    }

    public function ProductCompare()
    {
        $compareSession = session()->get('compare', []);
        $productIds = array_keys($compareSession);
        $products = Product::whereIn('id', $productIds)
            ->with('firstImage')
            ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
            ->get();

        return view('Frontend.compare', compact('products'));
    }

    public function filterProducts(Request $request)
    {
        $type = $request->input('type');   // category | subcategory | childcategory
        $id   = (int) $request->input('id');
        $page = (int) $request->input('page', 1);

        $query = Product::with('firstImage')
            ->withAvg(['reviews' => function ($q) { $q->where('status', '1'); }], 'review_star')
            ->where('status', '1');

        if ($type === 'category') {
            $query->where('category_id', $id);
        } elseif ($type === 'subcategory') {
            $query->where('subcategory_id', $id);
        } elseif ($type === 'childcategory') {
            $query->where('childcategory_id', $id);
        }

        // Sort
        match ($request->input('sort_by', 'newest')) {
            'oldest'     => $query->oldest(),
            'price-asc'  => $query->orderBy('new_price', 'asc'),
            'price-desc' => $query->orderBy('new_price', 'desc'),
            default      => $query->latest(),
        };

        // Price range
        if ($request->filled('min_price')) {
            $query->where('new_price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('new_price', '<=', (float) $request->input('max_price'));
        }

        $catProducts = $query->paginate(12, ['*'], 'page', $page);

        $html = view('Frontend.category.partials.cat_product_cards', compact('catProducts'))->render();

        return response()->json([
            'html'         => $html,
            'current_page' => $catProducts->currentPage(),
            'last_page'    => $catProducts->lastPage(),
            'total'        => $catProducts->total(),
            'from'         => $catProducts->firstItem() ?? 0,
            'to'           => $catProducts->lastItem() ?? 0,
        ]);
    }
}
