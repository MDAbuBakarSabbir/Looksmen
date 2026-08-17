<?php

namespace App\Models;

use Database\Factories\OrdersFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    /** @use HasFactory<OrdersFactory> */
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

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
