<?php

namespace App\Observers;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     */
    public function created(Brand $brand): void
    {
        //
    }

    /**
     * Handle the Brand "updated" event.
     */
    public function updated(Brand $brand): void
    {
        $originalImage = $brand->getOriginal('image');

        if ($brand->isDirty('image') && !is_null($originalImage)) {
            Storage::disk('public')->delete($brand->getOriginal('image'));
        }
    }

    /**
     * Handle the Brand "deleted" event.
     */
    public function deleted(Brand $brand): void
    {
        if (! is_null($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }
    }

    /**
     * Handle the Brand "restored" event.
     */
    public function restored(Brand $brand): void
    {
        //
    }

    /**
     * Handle the Brand "force deleted" event.
     */
    public function forceDeleted(Brand $brand): void
    {
        if (! is_null($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }
    }
}
