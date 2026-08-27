<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\OrdersFactory> */
    use HasFactory;
    protected $guarded = [];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function firstImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->oldestOfMany();
    }
    public function productAttributes()
    {
        return $this->hasMany(ProductAttributes::class, 'product_id');
    }
    public function productColors()
    {
        return $this->hasMany(ProductColors::class, 'product_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'product_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'product_id')->where('status', '1')->latest();
    }
    public function getAverageRating()
    {
        if (isset($this->reviews_avg_review_star)) {
            return (float) $this->reviews_avg_review_star;
        }
        if ($this->relationLoaded('reviews')) {
            return (float) $this->reviews->where('status', '1')->avg('review_star') ?: 0;
        }
        return (float) $this->reviews()->where('status', '1')->avg('review_star') ?: 0;
    }
    public function getDiscountPercentageAttribute()
    {
        if ($this->old_price > 0) {
            $discount = (($this->old_price - $this->new_price) / $this->old_price) * 100;
            return round($discount);
        }

        return 0;
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
