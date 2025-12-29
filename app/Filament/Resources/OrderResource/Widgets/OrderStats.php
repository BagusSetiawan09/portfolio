<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', Order::count())
                ->description('All time orders')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

            Stat::make('Open Orders', Order::whereIn('status', ['new', 'in_progress'])->count())
                ->description('New & In Progress')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('warning'),

            Stat::make('Completed Orders', Order::where('status', 'done')->count())
                ->description('Successfully delivered')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([3, 5, 12, 15, 18, 20, 25])
                ->color('success'),
        ];
    }
}