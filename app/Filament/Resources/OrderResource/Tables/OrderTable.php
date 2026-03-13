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

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status'),

                TextColumn::make('bestelde_pizzas')
                    ->label('Bestelde pizzas')
                    ->getStateUsing(function (Order $record) {
                        return $record->pizzas
                            ->map(fn ($pizza) => $pizza->name . ' (' . $pizza->pivot->quantity . 'x)')
                            ->join(', ');
                    })
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
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'completed'])),
                        
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}