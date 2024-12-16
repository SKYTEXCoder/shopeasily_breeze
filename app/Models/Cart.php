<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'name',
        'image',
        'slug',
        'quantity',
        'unit_amount',
        'total_amount',
    ];

    protected $casts = [
        'unit_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
