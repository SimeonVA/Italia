<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PizzaResource\Pages;
use App\Models\Pizza;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PizzaResource extends Resource
{
    protected static ?string $model = Pizza::class;

    protected static ?string $navigationLabel = 'Pizza\'s';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Naam'),

                Forms\Components\Textarea::make('beschrijving')
                    ->rows(3)
                    ->label('Beschrijving'),

                Forms\Components\TextInput::make('prijs')
                    ->numeric()
                    ->required()
                    ->step(0.01)
                    ->label('Prijs (€)'),

                Forms\Components\Select::make('status')
                    ->options([
                        'op-voorraad' => 'Op voorraad',
                        'in concept' => 'In concept',
                        'niet-op-voorraad' => 'Niet op voorraad',
                    ])
                    ->required()
                    ->default('op-voorraad')
                    ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Naam'),

                Tables\Columns\TextColumn::make('beschrijving')
                    ->limit(50)
                    ->label('Beschrijving'),

                Tables\Columns\TextColumn::make('prijs')
                    ->sortable()
                    ->label('Prijs (€)'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'op-voorraad' => 'success',
                        'in concept' => 'warning',
                        'niet-op-voorraad' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'op-voorraad' => 'Op voorraad',
                        'in concept' => 'In concept',
                        'niet-op-voorraad' => 'Niet op voorraad',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
