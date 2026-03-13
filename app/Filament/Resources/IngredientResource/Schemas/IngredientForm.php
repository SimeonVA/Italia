<?php

namespace App\Filament\Resources\IngredientResource\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class IngredientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Naam'),

                TextInput::make('inkoopprijs')
                    ->numeric()
                    ->required()
                    ->step(0.01)
                    ->label('Inkoopprijs (€)'),
            ]);
    }
}