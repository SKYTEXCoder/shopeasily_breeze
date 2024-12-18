<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('payment_method')
                            ->options([
                                'midtrans' => 'MidTrans',
                                'stripe' => 'Stripe',
                                'COD' => 'Cash on Delivery (COD)'
                            ])
                            ->required(),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required(),

                        ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->required()
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'success',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle',
                            ]),

                            Select::make('currency')
                                ->options([
                                    'idr' => 'IDR (Rp)',
                                    'usd' => 'USD ($)',
                                    'eur' => 'EUR (€)',
                                    'gbp' => 'GBP (£)',
                                ])
                                ->default('idr')
                                ->required()
                                //->live()
                                //->afterStateUpdated()
                                //->disabled()
                                //->dehydrated()
                                ,

                            Select::make('shipping_method')
                                ->options([
                                    'fedex' => 'FedEx',
                                    'ups' => 'UPS',
                                    'dhl' => 'DHL',
                                    'usps' => 'USPS',
                                    'jne' => 'JNE',
                                    'jnt' => 'J&T Express',
                                    'anteraja' => 'AnterAja',
                                    'sicepat' => 'SiCepat',
                                    'ninja' => 'Ninja Xpress',
                                    'lion_parcel' => 'Lion Parcel',
                                    'gosend' => 'Go-Send',
                                    'grabexpress' => 'Grab Express',
                                ]),

                            Textarea::make('notes')
                                ->columnSpanFull(),
                    ])->columns(2),

                    Section::make('Order Items')->schema([
                        Repeater::make('products')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(4)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount', Number::currency(Product::find($state)?->final_price ?? 0, 'IDR', 'id')))
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('total_amount', Number::currency(Product::find($state)?->final_price ?? 0, 'IDR', 'id')))
                                    ,

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->columnSpan(2)
                                    ->reactive()
                                    //->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total_amount', Number::currency($state * Product::find($get('product_id'))?->final_price ?? 0, 'IDR', 'id'))),

                                TextInput::make('unit_amount')
                                    //->numeric()
                                    //->prefix()
                                    //->suffix()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->dehydrateStateUsing(fn (Get $get) => Product::find($get('product_id'))?->final_price ?? 0) // Store raw value
                                    //->formatStateUsing(fn (Get $get) => Number::currency(Product::find($get('product_id'))?->final_price ?? 0, 'IDR', 'id')) // Display formatted value
                                    ->columnSpan(3),

                                TextInput::make('total_amount')
                                    //->numeric()
                                    //->prefix()
                                    //->suffix()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->dehydrateStateUsing(fn (Get $get) => $get('quantity') * Product::find($get('product_id'))?->final_price ?? 0) // Store raw value
                                    //->formatStateUsing(fn (Get $get) => Number::currency($get('quantity') * Product::find($get('product_id'))?->final_price ?? 0, 'IDR', 'id')) // Display formatted value
                                    ->columnSpan(3)
                            ])->columns(12),

                            Placeholder::make('grand_total_placeholder')
                                ->label('Grand Total')
                                ->content(function (Get $get, Set $set) {
                                    $total = 0;
                                    if (!$repeaters = $get('products')) {
                                        return $total;
                                    }
                                    foreach($repeaters as $key => $repeater) {
                                        $total += $get("products.{$key}.quantity") * Product::find($get("products.{$key}.product_id"))?->final_price ?? 0;
                                    }
                                    $set('grand_total', $total);
                                    return Number::currency($total, 'IDR', 'id');
                                }),

                                Hidden::make('grand_total')
                                    ->default(0)
                    ])

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('Order ID')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('user.name')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->numeric()
                    ->label('Grand Total')
                    ->sortable()
                    ->money('IDR', 0, 'id')
                    //->prefix('Rp')
                    ,

                TextColumn::make('payment_method')
                    ->searchable()
                    ->sortable()
                    ->label('Payment Method'),

                TextColumn::make('payment_status')
                    ->searchable()
                    ->sortable()
                    ->label('Payment Status'),

                TextColumn::make('currency')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('shipping_method')
                    ->sortable()
                    ->searchable()
                    ->label('Shipping Method'),

                SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->searchable()
                    ->sortable()
                    ->label('Order Status'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }

    public static function getNavigationBadge(): ?string {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null {
        return static::getModel()::count() > 10 ? 'success': 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array {
        return ['id', 'user_id', 'grand_total', 'notes'];
    }
}
