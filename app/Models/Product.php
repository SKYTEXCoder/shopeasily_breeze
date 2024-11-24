<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'brand_id', 'name', 'slug', 'images', 'description', 'original_price', 'final_price', 'discount_percentage', 'stock_amount', 'sold_amount', 'is_active', 'is_featured', 'in_stock', 'on_sale', 'rating'];

    protected $casts = ['images' => 'array'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function orderProducts() {
        return $this->hasMany(OrderProduct::class);
    }

    public function reviews() {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlists() {
        return $this->belongsToMany(Wishlist::class, 'wishlist_product')->withPivot('priority', 'added_at', 'note')->withTimestamps();
    }

    public function comments() {
        return $this->hasMany(ProductComment::class, 'product_id');
    }
}
