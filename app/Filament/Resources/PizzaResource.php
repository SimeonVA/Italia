<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Filament\Resources\PizzaResource\Forms\PizzaForm;
use App\Filament\Resources\PizzaResource\Pages;
use App\Filament\Resources\PizzaResource\Tables\PizzaTable;
use App\Models\Pizza;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class PizzaResource extends Resource
{
    protected static ?string $model = Pizza::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cake';
    
    protected static ?string $navigationLabel = 'Pizza\'s';

    public static function form(Schema $schema): Schema
    {
        return PizzaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PizzaTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPizzas::route('/'),
            'create' => Pages\CreatePizza::route('/create'),
            'edit' => Pages\EditPizza::route('/{record}/edit'),
        ];
    }
}
