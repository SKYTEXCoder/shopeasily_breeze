<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        $originalImage = $category->getOriginal('image');

        if ($category->isDirty('image') && !is_null($originalImage)) {
            Storage::disk('public')->delete($category->getOriginal('image'));
        }
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        if (! is_null($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        if (! is_null($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
    }
}
