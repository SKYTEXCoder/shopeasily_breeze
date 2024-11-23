<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slug', 'image', 'is_active'];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function parent_category() {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function parent_category_recursive() {
        return $this->parent_category()->with('parent_category_recursive');
    }

    public function parent_category_recursive_flatten() {
        $result = collect();
        $item = $this->parent_category_recursive;
        if ($item instanceof User) {
            $result->push($item);
            $result = $result->merge($item->parent_category_recursive_flatten());
        }
        return $result;
    }

    public function children_category() {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function children_category_recursive() {
        return $this->children_category()->with('children_category_recursive');
    }
}
