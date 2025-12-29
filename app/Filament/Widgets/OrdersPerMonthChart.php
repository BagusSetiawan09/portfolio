<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class OrdersPerMonthChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Orders per month';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $start = $this->filters['startDate'] ? Carbon::parse($this->filters['startDate']) : now()->startOfYear();
        $end = $this->filters['endDate'] ? Carbon::parse($this->filters['endDate']) : now()->endOfYear();

        $data = Trend::query(
                Order::query()->where('type', 'order')
            )
            ->between(
                start: $start,
                end: $end,
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#b7ff00',
                    'fill' => true,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}