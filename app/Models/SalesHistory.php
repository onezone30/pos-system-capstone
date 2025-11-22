<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesHistory extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'date',
        'quantity_sold',
        'total_sales',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
