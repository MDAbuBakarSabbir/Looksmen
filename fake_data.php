<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

$categories = [
    'Electronics',
    'Clothing',
    'Home & Garden',
];

foreach ($categories as $catName) {
    // Create category
    $category = Category::create([
        'level' => 1,
        'name' => $catName,
        'type' => 1,
        'commission_rate' => 0,
        'icon' => 'fa fa-cube',
        'banner' => 'https://picsum.photos/seed/cat/800/200',
        'slug' => Str::slug($catName),
        'status' => 1,
        'featured' => 1,
    ]);

    // Create 5 products for each category
    for ($i = 1; $i <= 5; $i++) {
        $productName = "Fake " . $catName . " Product " . $i;
        $product = Product::create([
            'title' => $productName,
            'slug' => Str::slug($productName) . '-' . rand(1000, 9999),
            'category_id' => $category->id,
            'description' => 'This is a fake product generated for testing purposes. It has a nice description.',
            'code' => 'PROD-' . rand(10000, 99999),
            'old_price' => rand(500, 1000),
            'new_price' => rand(100, 499),
            'stock' => rand(10, 100),
            'status' => 1,
            'created_by' => 1,
        ]);

        // Add 2 images for each product
        for ($j = 1; $j <= 2; $j++) {
            $imageUrl = "https://picsum.photos/seed/" . rand(1, 10000) . "/800/800";
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $imageUrl,
            ]);
        }
    }
}

echo "Successfully added fake categories and products with images!\n";
