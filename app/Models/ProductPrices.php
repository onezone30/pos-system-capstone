<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrices extends Model
{

    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'price',
        'reorder_level',
        'quantity_stock'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
