<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductComment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'parent_comment_id', 'title', 'content', 'is_approved'];

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

    public function parentRecursive() {
        return $this->parent()->with('parentRecurisve');
    }

    public function parentRecursiveFlatten() {
        $result = collect();
        $item = $this->parentRecursive;
        if ($item instanceof ProductComment) {
            $result->push($item);
            $result = $result->merge($item->parentRecursiveFlatten());
        }
        return $result;
    }

    public function repliesRecursive() {
        return $this->replies()->with('repliesRecursive');
    }
}
