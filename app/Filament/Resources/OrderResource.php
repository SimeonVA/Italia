<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Filament\Resources\OrderResource\Schemas\OrderForm;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Tables\OrderTable;
use App\Models\Order;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationLabel = 'Orders';

    /**
     * Zorgt ervoor dat klanten alleen hun eigen orders zien,
     * terwijl de admin alles blijft zien.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(! auth()->user()->is_admin, function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}