<?php

namespace App\Filament\Resources\PizzaResource\Tables;

use App\Models\Pizza;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PizzaTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Naam'),

                TextColumn::make('beschrijving')
                    ->limit(40)
                    ->label('Beschrijving'),

                TextColumn::make('prijs')
                    ->money('EUR')
                    ->sortable()
                    ->label('Verkoopprijs'),
                    
                TextColumn::make('kostprijs')
                    ->label('Kostprijs')
                    ->money('EUR')
                    ->getStateUsing(fn (Pizza $record) => $record->kostprijs),

                TextColumn::make('status')
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
                SelectFilter::make('status')
                    ->options([
                        'op-voorraad' => 'Op voorraad',
                        'in concept' => 'In concept',
                        'niet-op-voorraad' => 'Niet op voorraad',
                    ]),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}