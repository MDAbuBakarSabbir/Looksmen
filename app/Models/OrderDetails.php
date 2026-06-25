<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    /** @use HasFactory<\Database\Factories\OrdersFactory> */
    use HasFactory;
    protected $guarded = [''];
    public function orderDetails()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }


    public function orderProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
