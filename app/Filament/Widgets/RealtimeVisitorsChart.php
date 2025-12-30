<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon; // Import Carbon

class RealtimeVisitorsChart extends ChartWidget
{
    protected static ?string $heading = 'Last 1 Hour Activity';
    protected static ?int $sort = 6;
    protected static ?string $pollingInterval = '15s'; 

    protected function getData(): array
    {
        $data = Trend::model(Visitor::class)
            ->between(
                start: now()->subHour(),
                end: now(),
            )
            ->dateColumn('updated_at')
            ->perMinute()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Hits / Activities',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
            ],

            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('H:i')),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                    'beginAtZero' => true,
                ],
                'x' => [
                    'ticks' => [
                        'maxTicksLimit' => 5,
                        'autoSkip' => true,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}