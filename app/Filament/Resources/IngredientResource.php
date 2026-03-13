<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Filament\Resources\IngredientResource\Forms\IngredientForm;
use App\Filament\Resources\IngredientResource\Pages;
use App\Filament\Resources\IngredientResource\Tables\IngredientTable;
use App\Models\Ingredient;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class IngredientResource extends Resource
{
    protected static ?string $model = Ingredient::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-beaker';
    
    protected static ?string $navigationLabel = 'Ingrediënten';

    public static function form(Schema $schema): Schema
    {
        return IngredientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IngredientTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngredients::route('/'),
            'create' => Pages\CreateIngredient::route('/create'),
            'edit' => Pages\EditIngredient::route('/{record}/edit'),
        ];
    }
}
