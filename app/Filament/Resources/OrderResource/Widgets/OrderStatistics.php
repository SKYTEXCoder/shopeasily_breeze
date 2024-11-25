<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStatistics extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Order(s)', Order::query()->where('status', 'new')->count()),
            Stat::make('Order(s) Processing', Order::query()->where('status', 'processing')->count()),
            Stat::make('Order(s) Shipped', Order::query()->where('status', 'shipped')->count()),
            Stat::make('Order(s) Delivered', Order::query()->where('status', 'delivered')->count()),
            Stat::make('Order(s) Cancelled', Order::query()->where('status', 'cancelled')->count()),
            Stat::make('Average Price', Number::currency(Order::query()->avg('grand_total'), 'IDR', 'id'))            
        ];
    }
}
