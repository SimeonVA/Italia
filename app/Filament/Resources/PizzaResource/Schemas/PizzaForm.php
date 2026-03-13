<?php

namespace App\Filament\Resources\PizzaResource\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PizzaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label('Naam'),

            Textarea::make('beschrijving')
                ->rows(3)
                ->label('Beschrijving'),

            TextInput::make('prijs')
                ->numeric()
                ->required()
                ->step(0.01)
                ->label('Verkoopprijs (€)'),

            Select::make('status')
                ->options([
                    'op-voorraad' => 'Op voorraad',
                    'in concept' => 'In concept',
                    'niet-op-voorraad' => 'Niet op voorraad',
                ])
                ->required()
                ->default('op-voorraad')
                ->label('Status'),

            Select::make('ingredients')
                ->multiple()
                ->relationship('ingredients', 'name')
                ->preload()
                ->searchable()
                ->label('Ingrediënten'),
        ]);
    }
}