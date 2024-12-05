<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $originalImages = $product->getOriginal('images');

        if ($product->isDirty('images') && is_array($originalImages)) {
            foreach ($originalImages as $image) {
                Storage::disk('public')->delete($image);
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $images = $product->images;

        if (is_array($images)) {
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->deleted($product);
    }
}
