<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SubCategory extends Model
{
    protected $guarded = [''];
    use HasFactory;
    public function category()
{
    return $this->belongsTo(Category::class,'category_id');
}

public function childcategories()
{
    return $this->hasMany(ChildCategory::class, 'subcategory_id');
}

}
