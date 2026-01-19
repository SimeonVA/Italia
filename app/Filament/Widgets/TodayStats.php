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

        $ordersToday = Order::with('pizzas')
            ->whereDate('created_at', $today)
            ->get();

        $totalOrders = $ordersToday->count();

        $runningOrders = $ordersToday->where('status', 'pending')->count();

        $completedOrders = $ordersToday->where('status', 'completed');

        $totalRevenue = $completedOrders->sum(function ($order) {
            return $order->pizzas->sum(fn($pizza) => $pizza->price * $pizza->pivot->quantity);
        });

        $totalCost = $completedOrders->sum(function ($order) {
            return $order->pizzas->sum(fn($pizza) => $pizza->cost_price * $pizza->pivot->quantity);
        });

        $totalProfit = $totalRevenue - $totalCost;

        return [
            Stat::make('Aantal bestellingen', $totalOrders),
            Stat::make('Lopende bestellingen', $runningOrders),
            Stat::make('Totale omzet', '€ ' . number_format($totalRevenue, 2, ',', '.')),
            Stat::make('Totale kostprijs', '€ ' . number_format($totalCost, 2, ',', '.')),
            Stat::make('Totale winst', '€ ' . number_format($totalProfit, 2, ',', '.')),
        ];
    }
}
