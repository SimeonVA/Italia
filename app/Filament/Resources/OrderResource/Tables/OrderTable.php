<?php

namespace App\Filament\Resources\OrderResource\Tables;

use App\Models\Order;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class OrderTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),

                TextColumn::make('creator.name')  
                    ->label('Besteld door')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status'),

                TextColumn::make('pizzas_list') // Gebruik een unieke naam die niet in je DB voorkomt
    ->label('Bestelde pizza\'s')
    ->state(function (Order $record): string {
        // We halen de data direct uit de relatie
        return $record->pizzas->map(function ($pizza) {
            return "{$pizza->name} ({$pizza->pivot->quantity}x)";
        })->join(', ');
    })
    ->badge() // Optioneel: maakt het visueel iets duidelijker
    ->color('gray')
    ->wrap(),

                TextColumn::make('created_at')
                    ->dateTime('d-m-Y H:i')
                    ->label('Aangemaakt op')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('voltooid')
                        ->label('Markeer als voltooid')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn () => auth()->user()?->is_admin),
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->is_admin),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}