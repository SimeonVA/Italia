<?php

namespace App\Filament\Resources\OrderResource\Tables;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                TextColumn::make('customer.name')
                    ->label('Klant')
                    ->sortable()
                    ->searchable()
                    ->default('-'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status'),

                TextColumn::make('order_items_list')
                    ->label('Bestelde pizza\'s')
                    ->state(function (Order $record): string {
                        if ($record->orderItems->isEmpty()) return '-';
                        return $record->orderItems->map(function ($item) {
                            return "{$item->pizza->name} ({$item->quantity}x)";
                        })->join(', ');
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
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->is_admin),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}