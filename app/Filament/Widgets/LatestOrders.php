<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->numeric()
                    ->label('Grand Total')
                    ->sortable()
                    ->money('IDR', 0, 'id'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle',
                    })
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->searchable()
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Order Date'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Last Updated'),
            ])
            ->actions([
                Action::make('View Order')
                    ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record->getKey()]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
