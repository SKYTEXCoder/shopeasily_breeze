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
        if ($product->isDirty('images')) {
            $originalImages = $product->getOriginal('images') ?? [];
            $currentImages = $product->images ?? [];
            $originalImages = is_array($originalImages) ? $originalImages : [];
            $currentImages = is_array($currentImages) ? $currentImages : [];
            $removedImages = array_diff($originalImages, $currentImages);
            foreach ($removedImages as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
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
