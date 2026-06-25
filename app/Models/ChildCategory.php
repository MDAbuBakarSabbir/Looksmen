<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    protected $guarded = [''];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function subcategory()
{
    return $this->belongsTo(SubCategory::class , 'subcategory_id');
}

}
