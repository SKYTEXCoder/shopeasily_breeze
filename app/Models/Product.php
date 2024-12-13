<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'brand_id', 'name', 'slug', 'images', 'description', 'original_price', 'final_price', 'discount_percentage', 'stock_amount', 'sold_amount', 'is_active', 'is_featured', 'in_stock', 'on_sale', 'rating'];

    protected $casts = ['images' => 'array'];

    protected static function booted()
    {
        static::updated(function ($product) {
            if ($product->isDirty('final_price')) {
                Cart::where('product_id', $product->id)->update([
                    'unit_amount' => $product->final_price,
                    'total_amount' => \DB::raw("quantity * {$product->final_price}"),
                ]);
            }

            if ($product->isDirty('name')) {
                Cart::where('product_id', $product->id)->update([
                   'name' => $product->name,
                ]);
            }

            if ($product->isDirty('images')) {
                Cart::where('product_id', $product->id)->update([
                    'image' => $product->images[0] ?? null,
                ]);
            }
        });
    }

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

    public function cart() {
        return $this->hasMany(Cart::class);
    }
}
