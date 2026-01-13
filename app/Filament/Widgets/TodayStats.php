<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodayStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Lopende bestellingen', '0'),
            Stat::make('Aantal bestellingen', '0'),
            Stat::make('Totale omzet', '€ 0'),
            Stat::make('Totale kostprijs', '€ 0'),
            Stat::make('Totale winst', '€ 0'),
        ];
    }
}

?>