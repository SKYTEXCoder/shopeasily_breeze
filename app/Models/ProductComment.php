<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductComment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'parent_comment_id', 'title', 'content'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function replies() {
        return $this->hasMany(ProductComment::class, 'parent_comment_id');
    }

    public function parent() {
        return $this->belongsTo(ProductComment::class, 'parent_comment_id');
    }
}
