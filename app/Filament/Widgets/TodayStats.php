<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodayStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $ordersToday = Order::with('pizzas.ingredients')
            ->whereDate('created_at', $today)
            ->get();

        $completedOrders = $ordersToday->where('status', 'completed');

        // Top 3 pizza's
        $pizzaCounts = [];
        foreach ($completedOrders as $order) {
            foreach ($order->pizzas as $pizza) {
                $pizzaCounts[$pizza->name] = ($pizzaCounts[$pizza->name] ?? 0) + $pizza->pivot->quantity;
            }
        }
        arsort($pizzaCounts);
        $top3 = array_slice(array_keys($pizzaCounts), 0, 3);

        // Ingrediënten
        $ingredientCounts = [];
        foreach ($completedOrders as $order) {
            foreach ($order->pizzas as $pizza) {
                foreach ($pizza->ingredients as $ingredient) {
                    $ingredientCounts[$ingredient->name] = ($ingredientCounts[$ingredient->name] ?? 0) + $pizza->pivot->quantity;
                }
            }
        }

        return [
            Stat::make('Bestellingen', $ordersToday->count()),
            
            Stat::make('Lopend', $ordersToday->where('status', 'pending')->count()),
            
            Stat::make('Omzet', '€ ' . number_format($completedOrders->sum->revenue, 2, ',', '.')),
            
            Stat::make('Top 3', implode(', ', $top3) ?: 'Geen'),
            
            Stat::make('Ingrediënten', count($ingredientCounts)),
        ];
    }
}