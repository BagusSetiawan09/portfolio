<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $query = Order::query()->where('type', 'order');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $totalOrders = (clone $query)->count();
        $newCustomers = (clone $query)->distinct('email')->count();
        $newOrders = (clone $query)->where('status', 'new')->count();

        return [
            Stat::make('Total Orders', $totalOrders)
                ->description('Total incoming orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart([7, 2, 10, 3, 15, 4, 17]) 
                ->color('success'),

            Stat::make('New customers', $newCustomers)
                ->description('Customers in this period')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('danger'),

            Stat::make('New orders', $newOrders)
                ->description('Order new status')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];
    }
}