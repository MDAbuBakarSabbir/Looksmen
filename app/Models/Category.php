<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [''];
    public function subcategories()
{
    return $this->hasMany(SubCategory::class,'category_id');
}
    public function childcategories()
{
    return $this->hasMany(ChildCategory::class,'category_id');
}
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('global_categories_tree_11');
            \Illuminate\Support\Facades\Cache::forget('all_categories_page_cached');
            \Illuminate\Support\Facades\Cache::forget('home_categories_tree_11');
            \Illuminate\Support\Facades\Cache::forget('home_category_products_v2');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('global_categories_tree_11');
            \Illuminate\Support\Facades\Cache::forget('all_categories_page_cached');
            \Illuminate\Support\Facades\Cache::forget('home_categories_tree_11');
            \Illuminate\Support\Facades\Cache::forget('home_category_products_v2');
        });
    }
}
