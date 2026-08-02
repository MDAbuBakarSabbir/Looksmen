<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Pages;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FrontCategoryController extends Controller
{
    public function catProductView($id, $slug)
    {
        $page = (int) request('page', 1);
        $category = Cache::remember('cat_view_model_'.$id, 3600, function () use ($id) {
            return Category::with('subcategories.childcategories')->findOrFail($id);
        });
        if (! ($category instanceof Category)) {
            Cache::forget('cat_view_model_'.$id);
            $category = Category::with('subcategories.childcategories')->findOrFail($id);
            Cache::put('cat_view_model_'.$id, $category, 3600);
        }
        $catProducts = Cache::remember('cat_products_list_'.$id.'_page_'.$page, 600, function () use ($category) {
            return Product::where('category_id', $category->id)
                ->where('status', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->latest()
                ->paginate(12);
        });
        if (! ($catProducts instanceof LengthAwarePaginator)) {
            Cache::forget('cat_products_list_'.$id.'_page_'.$page);
            $catProducts = Product::where('category_id', $category->id)
                ->where('status', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->latest()
                ->paginate(12);
            Cache::put('cat_products_list_'.$id.'_page_'.$page, $catProducts, 600);
        }

        $categoryType = 'category';
        $parentCategory = $category;

        return view('Frontend.category.catProduct', compact('catProducts', 'category', 'categoryType', 'parentCategory'));
    }

    public function subCatProductView($id, $slug)
    {
        $page = (int) request('page', 1);
        $category = Cache::remember('subcat_view_model_'.$id, 3600, function () use ($id) {
            return SubCategory::with('childcategories', 'category.subcategories')->findOrFail($id);
        });
        if (! ($category instanceof SubCategory)) {
            Cache::forget('subcat_view_model_'.$id);
            $category = SubCategory::with('childcategories', 'category.subcategories')->findOrFail($id);
            Cache::put('subcat_view_model_'.$id, $category, 3600);
        }
        $catProducts = Cache::remember('subcat_products_list_'.$id.'_page_'.$page, 600, function () use ($category) {
            return Product::where('subcategory_id', $category->id)
                ->where('status', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->latest()
                ->paginate(12);
        });
        if (! ($catProducts instanceof LengthAwarePaginator)) {
            Cache::forget('subcat_products_list_'.$id.'_page_'.$page);
            $catProducts = Product::where('subcategory_id', $category->id)
                ->where('status', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->latest()
                ->paginate(12);
            Cache::put('subcat_products_list_'.$id.'_page_'.$page, $catProducts, 600);
        }

        $categoryType = 'subcategory';
        $parentCategory = $category->category;

        return view('Frontend.category.catProduct', compact('catProducts', 'category', 'categoryType', 'parentCategory'));
    }

    public function childCatProductView($id, $slug)
    {
        $page = (int) request('page', 1);
        $category = Cache::remember('childcat_view_model_'.$id, 3600, function () use ($id) {
            return ChildCategory::with('subcategory.category.subcategories', 'subcategory.childcategories')->findOrFail($id);
        });
        if (! ($category instanceof ChildCategory)) {
            Cache::forget('childcat_view_model_'.$id);
            $category = ChildCategory::with('subcategory.category.subcategories', 'subcategory.childcategories')->findOrFail($id);
            Cache::put('childcat_view_model_'.$id, $category, 3600);
        }
        $catProducts = Cache::remember('childcat_products_list_'.$id.'_page_'.$page, 600, function () use ($category) {
            return Product::where('childcategory_id', $category->id)
                ->where('status', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->latest()
                ->paginate(12);
        });
        if (! ($catProducts instanceof LengthAwarePaginator)) {
            Cache::forget('childcat_products_list_'.$id.'_page_'.$page);
            $catProducts = Product::where('childcategory_id', $category->id)
                ->where('status', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->latest()
                ->paginate(12);
            Cache::put('childcat_products_list_'.$id.'_page_'.$page, $catProducts, 600);
        }

        $categoryType = 'childcategory';
        $parentSubCategory = $category->subcategory;
        $parentCategory = $category->subcategory->category;

        return view('Frontend.category.catProduct', compact('catProducts', 'category', 'categoryType', 'parentCategory', 'parentSubCategory'));
    }

    public function ProductView($id, $slug)
    {
        $singleProduct = Cache::remember('product_view_full_'.$id, 600, function () use ($id) {
            return Product::with('productImages', 'reviews', 'category')->findOrFail($id);
        });
        if (! ($singleProduct instanceof Product)) {
            Cache::forget('product_view_full_'.$id);
            $singleProduct = Product::with('productImages', 'reviews', 'category')->findOrFail($id);
            Cache::put('product_view_full_'.$id, $singleProduct, 600);
        }
        $relProducts = Cache::remember('product_rel_list_'.$singleProduct->category_id.'_ex_'.$id, 600, function () use ($singleProduct, $id) {
            return Product::where('category_id', $singleProduct->category_id)
                ->where('id', '!=', $id)
                ->where('status', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->latest()
                ->take(6)
                ->get();
        });
        if (! ($relProducts instanceof Collection)) {
            Cache::forget('product_rel_list_'.$singleProduct->category_id.'_ex_'.$id);
            $relProducts = Product::where('category_id', $singleProduct->category_id)
                ->where('id', '!=', $id)
                ->where('status', '1')
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->latest()
                ->take(6)
                ->get();
            Cache::put('product_rel_list_'.$singleProduct->category_id.'_ex_'.$id, $relProducts, 600);
        }
        $topSellingProducts = Cache::remember('product_top_selling_ex_'.$id, 600, function () use ($id) {
            return Product::where('status', '1')->where('id', '!=', $id)
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->withCount('orderDetails')
                ->orderBy('order_details_count', 'desc')
                ->take(5)
                ->get();
        });
        if (! ($topSellingProducts instanceof Collection)) {
            Cache::forget('product_top_selling_ex_'.$id);
            $topSellingProducts = Product::where('status', '1')->where('id', '!=', $id)
                ->with('firstImage')
                ->withAvg(['reviews' => function ($q) {
                    $q->where('status', '1');
                }], 'review_star')
                ->withCount('orderDetails')
                ->orderBy('order_details_count', 'desc')
                ->take(5)
                ->get();
            Cache::put('product_top_selling_ex_'.$id, $topSellingProducts, 600);
        }

        return view('Frontend.productView', compact('singleProduct', 'relProducts', 'topSellingProducts'));
    }

    public function allcategory()
    {
        $allCategories = Cache::remember('all_categories_page_cached', 3600, function () {
            return Category::with('subcategories.childcategories')->get();
        });
        if (! ($allCategories instanceof Collection)) {
            Cache::forget('all_categories_page_cached');
            $allCategories = Category::with('subcategories.childcategories')->get();
            Cache::put('all_categories_page_cached', $allCategories, 3600);
        }

        return view('Frontend.allCategories', compact('allCategories'));
    }

    public function pages($slug)
    {
        // Cache only the page ID to avoid Eloquent model unserialize issues
        $pageId = Cache::remember('page_id_slug_' . $slug, 3600, function () use ($slug) {
            $p = Pages::where('slug', $slug)->where('status', 1)->first();
            return $p ? $p->id : null;
        });

        $page = $pageId ? Pages::find($pageId) : null;

        if (!$page) {
            abort(404);
        }

        return view('Frontend.pages.pages', compact('page'));
    }


    public function ProductCompare()
    {
        $compareSession = session()->get('compare', []);
        $productIds = array_keys($compareSession);
        $products = Product::whereIn('id', $productIds)
            ->with('firstImage')
            ->withAvg(['reviews' => function ($q) {
                $q->where('status', '1');
            }], 'review_star')
            ->get();

        return view('Frontend.compare', compact('products'));
    }

    public function filterProducts(Request $request)
    {
        $type = $request->input('type');   // category | subcategory | childcategory
        $id = (int) $request->input('id');
        $page = (int) $request->input('page', 1);

        $query = Product::with('firstImage')
            ->withAvg(['reviews' => function ($q) {
                $q->where('status', '1');
            }], 'review_star')
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
            'oldest' => $query->oldest(),
            'price-asc' => $query->orderBy('new_price', 'asc'),
            'price-desc' => $query->orderBy('new_price', 'desc'),
            default => $query->latest(),
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
            'html' => $html,
            'current_page' => $catProducts->currentPage(),
            'last_page' => $catProducts->lastPage(),
            'total' => $catProducts->total(),
            'from' => $catProducts->firstItem() ?? 0,
            'to' => $catProducts->lastItem() ?? 0,
        ]);
    }
}
