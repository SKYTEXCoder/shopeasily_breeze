<?php

namespace App\Providers;

use App\Listeners\MergeCartFromCookieToDatabaseOnLogin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Observers\BrandObserver;
use App\Observers\CategoryObserver;
use App\Observers\OrderObserver;
use App\Observers\OrderProductObserver;
use App\Observers\ProductObserver;
use Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Brand::observe(BrandObserver::class);
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
        Order::observe(OrderObserver::class);
        OrderProduct::observe(OrderProductObserver::class);
        Event::listen(
            \Illuminate\Auth\Events\Login::class,
            MergeCartFromCookieToDatabaseOnLogin::class,
        );
    }
}
