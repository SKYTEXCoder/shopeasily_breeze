<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WishlistProduct extends Pivot
{
    use HasFactory;

    protected $fillable = ['wishlist_id', 'product_id', 'priority', 'added_at', 'note'];
}
