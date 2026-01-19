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
    protected static ?string $navigationIcon = 'heroicon-o-cake';

    public static function form(Form $form): Form
    {
        return $form->schema([
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
                ->label('Verkoopprijs (€)'),

            Forms\Components\Select::make('status')
                ->options([
                    'op-voorraad' => 'Op voorraad',
                    'in concept' => 'In concept',
                    'niet-op-voorraad' => 'Niet op voorraad',
                ])
                ->required()
                ->default('op-voorraad')
                ->label('Status'),

            Forms\Components\Select::make('ingredients')
                ->multiple()
                ->relationship('ingredients', 'name')
                ->preload()
                ->searchable()
                ->label('Ingrediënten'),
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
                    ->limit(40)
                    ->label('Beschrijving'),

                Tables\Columns\TextColumn::make('prijs')
                    ->money('EUR')
                    ->sortable()
                    ->label('Verkoopprijs'),
                    
                Tables\Columns\TextColumn::make('kostprijs')
                    ->label('Kostprijs')
                    ->money('EUR')
                    ->getStateUsing(fn (Pizza $record) => $record->kostprijs),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
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
