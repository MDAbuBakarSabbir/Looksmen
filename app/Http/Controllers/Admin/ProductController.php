<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValues;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductColors;
use App\Models\ProductImage;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::where('status', '1')->get();
        $attributes = Attribute::where('status', '1')->get();

        $query = Product::query();

        // Title / Code / Slug search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id') && $request->category_id != 'Select Category') {
            $query->where('category_id', $request->category_id);
        }

        // Subcategory filter
        if ($request->filled('subcategory_id') && $request->subcategory_id != 'Select Sub Category') {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        // Child category filter
        if ($request->filled('childcategory_id') && $request->childcategory_id != 'Select Child Category') {
            $query->where('childcategory_id', $request->childcategory_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Todays Deal filter
        if ($request->filled('todays_deal')) {
            $query->where('todays_deal', $request->todays_deal);
        }

        // Stock Status filter
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status === 'low_stock') {
                $query->whereBetween('stock', [1, 9]);
            }
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('new_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('new_price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'latest');
        if ($sortBy === 'price_asc') {
            $query->orderBy('new_price', 'asc');
        } elseif ($sortBy === 'price_desc') {
            $query->orderBy('new_price', 'desc');
        } elseif ($sortBy === 'stock_asc') {
            $query->orderBy('stock', 'asc');
        } elseif ($sortBy === 'stock_desc') {
            $query->orderBy('stock', 'desc');
        } else {
            $query->latest(); // Default: latest
        }

        $products = $query->get();

        if ($request->ajax()) {
            return view('adminDash.products.extends.product_rows', compact('products'));
        }

        return view('adminDash.products.index', compact('categories', 'products', 'attributes'));
    }

    public function bulk()
    {
        return view('adminDash.products.bulk_create');
    }

    public function downloadSampleCSV()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=products_import_sample.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['title', 'category', 'subcategory', 'childcategory', 'old_price', 'new_price', 'stock', 'description', 'video'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Add sample row 1
            fputcsv($file, [
                'Premium Leather Jacket',
                'Fashion',
                'Mens Clothing',
                'Jackets',
                '4500',
                '3999',
                '50',
                '<p>This is a premium mens leather jacket made of 100% sheepskin leather.</p>',
                'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ]);

            // Add sample row 2
            fputcsv($file, [
                'Wireless Bluetooth Headphones',
                'Electronics',
                'Audio',
                'Headphones',
                '2500',
                '1990',
                '120',
                '<p>High quality noise canceling bluetooth wireless headphones with 40h battery life.</p>',
                '',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        if (($handle = fopen($filePath, 'r')) === false) {
            return redirect()->back()->with('error', 'Unable to open the CSV file.');
        }

        $header = fgetcsv($handle, 1000, ',');
        if (! $header) {
            fclose($handle);

            return redirect()->back()->with('error', 'The CSV file is empty or has invalid headers.');
        }

        $header = array_map(function ($h) {
            return strtolower(trim($h));
        }, $header);

        $requiredFields = ['title', 'category', 'old_price', 'new_price', 'stock', 'description'];
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (! in_array($field, $header)) {
                $missingFields[] = $field;
            }
        }

        if (! empty($missingFields)) {
            fclose($handle);

            return redirect()->back()->with('error', 'Missing required CSV columns: '.implode(', ', $missingFields));
        }

        $importedCount = 0;
        $failedCount = 0;
        $rowNumber = 1;
        $errors = [];

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $rowNumber++;

            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            } elseif (count($row) > count($header)) {
                $row = array_slice($row, 0, count($header));
            }

            $data = array_combine($header, $row);

            $title = trim($data['title'] ?? '');
            $categoryName = trim($data['category'] ?? '');
            $oldPrice = trim($data['old_price'] ?? '');
            $newPrice = trim($data['new_price'] ?? '');
            $stock = trim($data['stock'] ?? '');
            $description = trim($data['description'] ?? '');
            $video = trim($data['video'] ?? '');

            if (empty($title) || empty($categoryName) || $oldPrice === '' || $newPrice === '' || $stock === '' || empty($description)) {
                $failedCount++;
                $errors[] = "Row {$rowNumber}: Missing required fields.";

                continue;
            }

            try {
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    ['slug' => Str::slug($categoryName), 'status' => '1']
                );

                $subcategoryID = null;
                $subcategoryName = trim($data['subcategory'] ?? '');
                if ($subcategoryName !== '') {
                    $subcategory = SubCategory::firstOrCreate(
                        ['name' => $subcategoryName, 'category_id' => $category->id],
                        ['slug' => Str::slug($subcategoryName), 'status' => '1']
                    );
                    $subcategoryID = $subcategory->id;
                }

                $childcategoryID = null;
                $childcategoryName = trim($data['childcategory'] ?? '');
                if ($childcategoryName !== '' && $subcategoryID) {
                    $childcategory = ChildCategory::firstOrCreate(
                        ['name' => $childcategoryName, 'subcategory_id' => $subcategoryID],
                        ['slug' => Str::slug($childcategoryName), 'status' => '1']
                    );
                    $childcategoryID = $childcategory->id;
                }

                $productCount = Product::count() + 1;
                $currentYear = date('Y');
                $code = $currentYear.$productCount;

                Product::create([
                    'title' => $title,
                    'slug' => Str::slug($title).'-'.uniqid(),
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategoryID,
                    'childcategory_id' => $childcategoryID,
                    'brand_id' => null,
                    'old_price' => floatval($oldPrice),
                    'new_price' => floatval($newPrice),
                    'stock' => intval($stock),
                    'code' => $code,
                    'description' => $description,
                    'video' => $video !== '' ? $video : null,
                    'cod' => 1,
                    'advance_amount' => null,
                    'status' => 1,
                    'created_at' => now(),
                ]);

                $importedCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = "Row {$rowNumber}: ".$e->getMessage();
            }
        }

        fclose($handle);

        if ($failedCount > 0) {
            session()->flash('warning', "Imported {$importedCount} products, {$failedCount} rows failed. Errors: ".implode(' | ', array_slice($errors, 0, 5)));
        } else {
            session()->flash('success', "Successfully imported all {$importedCount} products!");
        }

        return redirect()->route('product.index');
    }

    public function image(Request $request)
    {
        return $request;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', '1')->get();
        $colors = Color::where('status', '1')->get();
        $attributes = Attribute::where('status', '1')->get();

        return view('adminDash.products.create', compact('categories', 'colors', 'attributes'));
    }

    public function getSubcategories($category_id)
    {
        $subcategories = SubCategory::where('category_id', $category_id)
            ->where('status', '1')
            ->pluck('name', 'id');

        return response()->json($subcategories);
    }

    public function getChildCategory($subcategory_id)
    {
        $childcategories = ChildCategory::where('subcategory_id', $subcategory_id)
            ->where('status', '1')
            ->pluck('name', 'id');

        return response()->json($childcategories);
    }

    public function getAttributeValues($attribute_id)
    {
        $attributeValues = AttributeValues::where('attribute_id', $attribute_id)
            ->pluck('value', 'id');

        return response()->json($attributeValues);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'childcategory_id' => 'required',
            'description' => 'required',
            'old_price' => 'required',
            'new_price' => 'required',
            'stock' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $productcournt = Product::all()->count();
        if ($productcournt != 1) {
            $procount = 1;
        } else {
            $procount = $productcournt;
        }
        $currentYear = Carbon::now()->year;
        $product = Product::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id ?? null,
            'childcategory_id' => $request->childcategory_id ?? null,
            'brand_id' => $request->brand_id ?? null,
            'old_price' => $request->old_price,
            'new_price' => $request->new_price,
            'stock' => $request->stock,
            'code' => $currentYear.$procount + 1,
            'description' => $request->description,
            'video' => $request->video ?? null,
            'cod' => $request->cod ?? 1,
            'advance_amount' => $request->advance_amount ?? null,
            'points' => $request->points ?? 0,
            'created_at' => now(),
        ]);
        $product->save();

        // Store Images
        $manager = new ImageManager(new Driver);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $proimage) {

                $imgName = $product->id.'-'.uniqid().Str::random(7).'.'.$proimage->getClientOriginalExtension();
                $image = $manager->decode($proimage);
                $image->save(base_path('public/adminDash/images/product/'.$imgName));
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imgName,
                ]);
            }
        }

        // Attributes
        if ($request->attributeValue) {
            foreach ($request->attributeValue as $value) {
                ProductAttributes::create([
                    'product_id' => $product->id,
                    'attribute_id' => $request->attribute_id,
                    'attribute_value' => $value,
                    'created_at' => now(),
                ]);
            }
        }
        // Colors
        if ($request->color) {
            foreach ($request->color as $color) {
                ProductColors::create([
                    'product_id' => $product->id,
                    'color_id' => $color,
                    'created_at' => now(),
                ]);
            }
        }
        // return redirect(route('product.index'))->with('success', 'Product Added Successfully!');
        session()->flash('success', 'Product created successfully!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function view(Product $Product, $id)
    {
        $product = Product::findOrFail($id);
        $proImages = ProductImage::where('product_id', $id)->get();

        $categoryName = $product->category?->name;
        $subcategoryName = SubCategory::find($product->subcategory_id)?->name;
        $childcategoryName = ChildCategory::find($product->childcategory_id)?->name;

        $colors = Color::whereIn('id', ProductColors::where('product_id', $id)->pluck('color_id'))->get();

        $selectedAttribute = ProductAttributes::where('product_id', $id)
            ->join('attributes', 'product_attributes.attribute_id', '=', 'attributes.id')
            ->select('attributes.name as attribute_name')
            ->first()?->attribute_name;

        $selectedAttributeValues = ProductAttributes::where('product_id', $id)
            ->join('attribute_values', 'product_attributes.attribute_value', '=', 'attribute_values.id')
            ->pluck('attribute_values.value')
            ->toArray();

        return view('adminDash.products.info', compact(
            'product', 'proImages', 'categoryName', 'subcategoryName',
            'childcategoryName', 'colors', 'selectedAttribute', 'selectedAttributeValues'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('status', '1')->get();
        $colors = Color::where('status', '1')->get();
        $attributes = Attribute::where('status', '1')->get();

        $subcategories = SubCategory::where('category_id', $product->category_id)->where('status', '1')->get();
        $childcategories = ChildCategory::where('subcategory_id', $product->subcategory_id)->where('status', '1')->get();

        $selectedColors = ProductColors::where('product_id', $id)->pluck('color_id')->toArray();
        $selectedAttributes = ProductAttributes::where('product_id', $id)->pluck('attribute_value')->toArray();

        $selectedAttributeId = ProductAttributes::where('product_id', $id)->first()?->attribute_id;
        $attributeValues = $selectedAttributeId ? AttributeValues::where('attribute_id', $selectedAttributeId)->get() : collect();

        $productImages = ProductImage::where('product_id', $id)->get();

        return view('adminDash.products.edit', compact(
            'product', 'categories', 'colors', 'attributes',
            'subcategories', 'childcategories', 'selectedColors',
            'selectedAttributes', 'selectedAttributeId', 'attributeValues', 'productImages'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'childcategory_id' => 'required',
            'description' => 'required',
            'old_price' => 'required',
            'new_price' => 'required',
            'stock' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id ?? null,
            'childcategory_id' => $request->childcategory_id ?? null,
            'brand_id' => $request->brand_id ?? null,
            'old_price' => $request->old_price,
            'new_price' => $request->new_price,
            'stock' => $request->stock,
            'description' => $request->description,
            'video' => $request->video ?? null,
            'cod' => $request->cod ?? 1,
            'advance_amount' => $request->advance_amount ?? null,
            'points' => $request->points ?? 0,
            'updated_at' => now(),
        ]);

        // Sync Colors (delete old and insert new)
        ProductColors::where('product_id', $product->id)->delete();
        if ($request->color) {
            foreach ($request->color as $color) {
                ProductColors::create([
                    'product_id' => $product->id,
                    'color_id' => $color,
                    'created_at' => now(),
                ]);
            }
        }

        // Sync Attributes (delete old and insert new)
        ProductAttributes::where('product_id', $product->id)->delete();
        if ($request->attributeValue) {
            foreach ($request->attributeValue as $value) {
                ProductAttributes::create([
                    'product_id' => $product->id,
                    'attribute_id' => $request->attribute_id,
                    'attribute_value' => $value,
                    'created_at' => now(),
                ]);
            }
        }

        // Store new uploaded images
        $manager = new ImageManager(new Driver);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $proimage) {
                $imgName = $product->id.'-'.uniqid().Str::random(7).'.'.$proimage->getClientOriginalExtension();
                $image = $manager->decode($proimage);
                $image->save(base_path('public/adminDash/images/product/'.$imgName));
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imgName,
                ]);
            }
        }

        session()->flash('success', 'Product updated successfully!');

        return redirect()->route('product.index');
    }

    /**
     * Delete product image via AJAX.
     */
    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);

        $filePath = base_path('public/adminDash/images/product/'.$image->image);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully!']);
    }

    public function copy(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'copies' => 'required|integer|min:1|max:20',
        ]);

        $original = Product::find($request->product_id);

        if (! $original) {
            return response()->json(['message' => 'Original product not found'], 404);
        }

        for ($i = 1; $i <= $request->copies; $i++) {

            // Total Product Count
            $total = Product::count() + 1;

            // New Product Code = Year.TotalProductCount
            $newCode = date('Y').$total;

            // Duplicate Product
            $newProduct = $original->replicate();
            $newProduct->code = $newCode;
            $newProduct->created_at = now();
            $newProduct->save();
        }

        return response()->json([
            'message' => "Successfully copied {$request->copies} items!",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $proImages = ProductImage::where('product_id', $id)->get();
        $proAttributes = ProductAttributes::where('product_id', $id)->get();
        $proColors = ProductColors::where('product_id', $id)->get();

        foreach ($proImages as $img) {
            if (file_exists(public_path('public/adminDash/images/product/'.$img->image))) {
                unlink(public_path('public/adminDash/images/product/'.$img->image));
            }
            $img->delete();
        }
        if ($proAttributes) {
            foreach ($proAttributes as $attr) {
                $attr->delete();
            }
        }
        if ($proColors) {
            foreach ($proColors as $color) {
                $color->delete();
            }
        }
        $product->delete();
        // $product->destroy();

        session()->flash('success', 'Product deleted successfully!');

        return redirect()->back();
    }

    public function todays_deal_status(Request $request)
    {
        $product = Product::find($request->id);

        if (! $product) {
            return response()->json(['success' => false]);
        }

        $product->todays_deal = $request->status == 1 ? 1 : 0;
        $product->save();

        return response()->json([
            'success' => true,
            'status' => $product->status,
        ]);
    }

    public function status(Request $request)
    {
        $product = Product::find($request->id);

        if (! $product) {
            return response()->json(['success' => false]);
        }

        $product->status = $request->status == 1 ? 1 : 0;
        $product->save();

        return response()->json([
            'success' => true,
            'status' => $product->status,
        ]);
    }
}
