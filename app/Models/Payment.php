<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'order_cart_items',
        'status',
        'final_price',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'checkout_link',
    ];

    protected $casts = [
        'order_cart_items' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
