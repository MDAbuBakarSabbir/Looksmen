<?php

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

$availableImages = [
    '1CNiwPIk5edQzG3t4VYFGSxWOeKMgdbiSTPxwaz7.jpg',
    '1FUuLWjyNYP50Sdpy3vqZqEcV8uOlvihArEWi4lK.jpg',
    '6EdCDN6AOtid20Qk8y6NCcoXbht0EOMVpgh3llor.jpg',
    '7Cl9NjrVWAp8sxHBZoKrxHQo4zxspQ57nFQ0MUN6.jpg',
    'EczPhxzfY9zoCRC89dEtpFVPLa8GodYLWxEYwjxw.jpg',
    'J0oo9a0joIoh2L5whQP6HRvR36OnAvDKT1TAExie.jpg',
    'Mrx9iUVttbKayRPqdbIL7j9xUQAElL7uZEQFzYTf.jpg',
    'NpqtjsmM4mfBU7yje2KMwlu2GNXIifeDaSg7NtaJ.jpg',
    'P1img1.jpg',
    'P1img2.jpg',
    'iBKu7Nt8idX0emLB1faPoh4QciTBPmNlS9qgLlGG.jpg',
    'j6QrRsvz11h1ebJf6scDfwKA8SLvEOScQUlq5O3m.jpg',
    'li18sFvwqFW9df5Pt46VYHfXIT8w1HPLACLZk8F2.jpg',
    'nIWZ9SKCmi5ZPu3o22Ld8cPqjDHu1aLYXBLFV2vk.jpg',
    'x81HKT6lrE2dqi2qTfzJIh4XsqvwncqHFwCMDSZO.jpg'
];

// Helper to create products
function createDummyProduct($catId, $subCatId, $childCatId, $namePrefix, $index, $availableImages) {
    $title = $namePrefix . " Premium Item " . $index;
    $slug = Str::slug($title) . '-' . rand(100000, 999999);
    $code = 'PRD-' . strtoupper(Str::random(4)) . rand(1000, 9999);

    $product = Product::create([
        'title' => $title,
        'slug' => $slug,
        'category_id' => $catId,
        'subcategory_id' => $subCatId,
        'childcategory_id' => $childCatId,
        'description' => 'This is a premium high-quality product curated carefully for Looksmen customers. Highly durable and stylish.',
        'code' => $code,
        'old_price' => rand(1200, 2500),
        'new_price' => rand(600, 1199),
        'stock' => rand(20, 100),
        'status' => 1,
        'created_by' => 1,
    ]);

    // Add 2 random images
    $images = (array) array_rand(array_flip($availableImages), 2);
    foreach ($images as $img) {
        ProductImage::create([
            'product_id' => $product->id,
            'image' => $img,
        ]);
    }
}

// 1. Automatically seed subcategories and childcategories if sub_categories table is empty
if (SubCategory::count() === 0) {
    echo "Subcategories empty. Populating a rich category tree...\n";
    $cats = Category::all();

    foreach ($cats as $cat) {
        $catName = strtolower($cat->name);
        if (strpos($catName, 'tshirt') !== false) {
            $sub1 = SubCategory::create(['category_id' => $cat->id, 'name' => 'Polo Shirt', 'slug' => 'polo-shirt', 'level' => 1, 'status' => 1]);
            ChildCategory::create(['category_id' => $cat->id, 'subcategory_id' => $sub1->id, 'name' => 'Full Sleeve Polo', 'slug' => 'full-sleeve-polo', 'level' => 1, 'status' => 1]);
            ChildCategory::create(['category_id' => $cat->id, 'subcategory_id' => $sub1->id, 'name' => 'Short Sleeve Polo', 'slug' => 'short-sleeve-polo', 'level' => 1, 'status' => 1]);
            
            SubCategory::create(['category_id' => $cat->id, 'name' => 'Casual Tshirt', 'slug' => 'casual-tshirt', 'level' => 1, 'status' => 1]);
        } elseif (strpos($catName, 'ssd') !== false) {
            $sub1 = SubCategory::create(['category_id' => $cat->id, 'name' => 'NVMe SSD', 'slug' => 'nvme-ssd', 'level' => 1, 'status' => 1]);
            ChildCategory::create(['category_id' => $cat->id, 'subcategory_id' => $sub1->id, 'name' => '1TB NVMe', 'slug' => '1tb-nvme', 'level' => 1, 'status' => 1]);
            ChildCategory::create(['category_id' => $cat->id, 'subcategory_id' => $sub1->id, 'name' => '2TB NVMe', 'slug' => '2tb-nvme', 'level' => 1, 'status' => 1]);
            
            SubCategory::create(['category_id' => $cat->id, 'name' => 'SATA SSD', 'slug' => 'sata-ssd', 'level' => 1, 'status' => 1]);
        } elseif (strpos($catName, 'ram') !== false) {
            SubCategory::create(['category_id' => $cat->id, 'name' => 'DDR4 RAM', 'slug' => 'ddr4-ram', 'level' => 1, 'status' => 1]);
            SubCategory::create(['category_id' => $cat->id, 'name' => 'DDR5 RAM', 'slug' => 'ddr5-ram', 'level' => 1, 'status' => 1]);
        } elseif (strpos($catName, 'electronics') !== false) {
            SubCategory::create(['category_id' => $cat->id, 'name' => 'Gadgets', 'slug' => 'gadgets', 'level' => 1, 'status' => 1]);
            SubCategory::create(['category_id' => $cat->id, 'name' => 'Accessories', 'slug' => 'accessories', 'level' => 1, 'status' => 1]);
        } elseif (strpos($catName, 'clothing') !== false) {
            SubCategory::create(['category_id' => $cat->id, 'name' => 'Mens Clothing', 'slug' => 'mens-clothing', 'level' => 1, 'status' => 1]);
            SubCategory::create(['category_id' => $cat->id, 'name' => 'Womens Clothing', 'slug' => 'womens-clothing', 'level' => 1, 'status' => 1]);
        } elseif (strpos($catName, 'garden') !== false) {
            SubCategory::create(['category_id' => $cat->id, 'name' => 'Kitchen Tools', 'slug' => 'kitchen-tools', 'level' => 1, 'status' => 1]);
            SubCategory::create(['category_id' => $cat->id, 'name' => 'Home Decor', 'slug' => 'home-decor', 'level' => 1, 'status' => 1]);
        } else {
            // Default subcategory
            $sub = SubCategory::create(['category_id' => $cat->id, 'name' => $cat->name . ' Sub', 'slug' => Str::slug($cat->name . '-sub'), 'level' => 1, 'status' => 1]);
            ChildCategory::create(['category_id' => $cat->id, 'subcategory_id' => $sub->id, 'name' => $cat->name . ' Child', 'slug' => Str::slug($cat->name . '-child'), 'level' => 1, 'status' => 1]);
        }
    }
}

// 2. Process Child Categories
$childCategories = ChildCategory::all();
echo "Processing " . $childCategories->count() . " Child Categories...\n";
foreach ($childCategories as $childCat) {
    $existingCount = Product::where('childcategory_id', $childCat->id)->count();
    if ($existingCount < 10) {
        $toCreate = 10 - $existingCount;
        echo "Child Category [{$childCat->name}] has {$existingCount} products. Seeding {$toCreate} products...\n";
        for ($i = 1; $i <= $toCreate; $i++) {
            createDummyProduct($childCat->category_id, $childCat->subcategory_id, $childCat->id, $childCat->name, $i, $availableImages);
        }
    }
}

// 3. Process Sub Categories
$subCategories = SubCategory::all();
echo "Processing " . $subCategories->count() . " Sub Categories...\n";
foreach ($subCategories as $subCat) {
    $existingCount = Product::where('subcategory_id', $subCat->id)->count();
    if ($existingCount < 10) {
        $toCreate = 10 - $existingCount;
        echo "Sub Category [{$subCat->name}] has {$existingCount} products. Seeding {$toCreate} products...\n";
        for ($i = 1; $i <= $toCreate; $i++) {
            createDummyProduct($subCat->category_id, $subCat->id, null, $subCat->name, $i, $availableImages);
        }
    }
}

// 4. Process Categories
$categories = Category::all();
echo "Processing " . $categories->count() . " Categories...\n";
foreach ($categories as $cat) {
    $existingCount = Product::where('category_id', $cat->id)->count();
    if ($existingCount < 10) {
        $toCreate = 10 - $existingCount;
        echo "Category [{$cat->name}] has {$existingCount} products. Seeding {$toCreate} products...\n";
        for ($i = 1; $i <= $toCreate; $i++) {
            createDummyProduct($cat->id, null, null, $cat->name, $i, $availableImages);
        }
    }
}

echo "All Categories, Sub Categories, and Child Categories now have at least 10 products!\n";
