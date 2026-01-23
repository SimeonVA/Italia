<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Collection;

class TodayStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $ordersToday = Order::with('pizzas.ingredients')
            ->whereDate('created_at', $today)
            ->get();

        $totalOrders = $ordersToday->count();

        $runningOrders = $ordersToday
            ->where('status', 'pending')
            ->count();

        $completedOrders = $ordersToday
            ->where('status', 'completed');

        $totalRevenue = $completedOrders->sum->revenue;
        $totalCost    = $completedOrders->sum->cost;
        $totalProfit  = $completedOrders->sum->profit;

        /**
         * --------------------------------
         * Top 3 populairste pizza’s
         * --------------------------------
         */
        $pizzaCounts = collect();

        foreach ($completedOrders as $order) {
            foreach ($order->pizzas as $pizza) {
                $pizzaCounts[$pizza->name] =
                    ($pizzaCounts[$pizza->name] ?? 0)
                    + $pizza->pivot->quantity;
            }
        }

        $topPizzas = $pizzaCounts
            ->sortDesc()
            ->take(3);

        $topPizzaList = $topPizzas
            ->map(fn ($count, $name) =>
                "<li>{$name} <strong>({$count}x)</strong></li>"
            )
            ->implode('');

        /**
         * --------------------------------
         * Gebruikte ingrediënten
         * --------------------------------
         */
        $ingredientCounts = collect();

        foreach ($completedOrders as $order) {
            foreach ($order->pizzas as $pizza) {
                foreach ($pizza->ingredients as $ingredient) {
                    $ingredientCounts[$ingredient->name] =
                        ($ingredientCounts[$ingredient->name] ?? 0)
                        + $pizza->pivot->quantity;
                }
            }
        }

        $ingredientCounts = $ingredientCounts->sortDesc();

        $ingredientList = $ingredientCounts
            ->map(fn ($count, $name) =>
                "<li>{$name} <strong>({$count}x)</strong></li>"
            )
            ->implode('');

        return [
            Stat::make('Aantal bestellingen', $totalOrders),

            Stat::make('Lopende bestellingen', $runningOrders),

            Stat::make(
                'Totale omzet',
                '€ ' . number_format($totalRevenue, 2, ',', '.')
            ),

            Stat::make(
                'Totale kostprijs',
                '€ ' . number_format($totalCost, 2, ',', '.')
            ),

            Stat::make(
                'Totale winst',
                '€ ' . number_format($totalProfit, 2, ',', '.')
            ),

            Stat::make('Top 3 pizza’s vandaag', $topPizzas->sum())
                ->description(
                    $topPizzas->isEmpty()
                        ? 'Geen verkopen vandaag'
                        : new HtmlString(
                            "<ul class='list-disc pl-4'>{$topPizzaList}</ul>"
                        )
                )
                ->extraAttributes(['class' => 'text-left']),

            Stat::make('Gebruikte ingrediënten', $ingredientCounts->count())
                ->description(
                    $ingredientCounts->isEmpty()
                        ? 'Geen ingrediënten gebruikt'
                        : new HtmlString(
                            "<ul class='list-disc pl-4'>{$ingredientList}</ul>"
                        )
                )
                ->extraAttributes(['class' => 'text-left']),
        ];
    }
}
