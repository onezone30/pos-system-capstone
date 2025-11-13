<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItems;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    const PAYMENT_METHOD = [
        'cash',
        'gcash',
        'maya'
    ];

    protected $fillable = [
        'user_id',
        'total_amount',
        'amount_paid',
        'customer_name',
        'change',
        'payment_method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function getPaymentColorAttribute()
{
    return match (strtolower($this->payment_method)) {
        'cash'  => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 border border-green-300',
        'gcash' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 border border-blue-300',
        'maya'  => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300 border border-emerald-300',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-400',
    };
}

}
