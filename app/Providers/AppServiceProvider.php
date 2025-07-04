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
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
        Request::setTrustedProxies(['*'], Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_AWS_ELB
            | Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_PREFIX
            | Request::HEADER_X_FORWARDED_TRAEFIK
        );

        if (config('app.env') !== 'local') {
            Filament::serving(function () {
                URL::forceRootUrl(config('app.url'));
            });
        }
    }
}
