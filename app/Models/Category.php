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
            \Illuminate\Support\Facades\Cache::flush();
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::flush();
        });
    }
}
