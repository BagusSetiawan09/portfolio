<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon; // Pastikan import Carbon

class VisitorsChart extends ChartWidget
{
    protected static ?string $heading = 'Visitor Statistics';
    protected static ?int $sort = 5;

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        switch ($activeFilter) {
            case 'today':
                $data = Trend::model(Visitor::class)
                    ->between(now()->startOfDay(), now()->endOfDay())
                    ->perHour()
                    ->count();
                $format = 'H:i'; // Format Jam (13:00)
                break;

            case 'week':
                $data = Trend::model(Visitor::class)
                    ->between(now()->startOfWeek(), now()->endOfWeek())
                    ->perDay()
                    ->count();
                $format = 'D, d M'; // Format Hari (Sen, 30 Des)
                break;
            
            case 'year':
                $data = Trend::model(Visitor::class)
                    ->between(now()->startOfYear(), now()->endOfYear())
                    ->perMonth()
                    ->count();
                $format = 'M Y'; // Format Bulan (Des 2025)
                break;

            case 'month':
            default:
                $data = Trend::model(Visitor::class)
                    ->between(now()->startOfMonth(), now()->endOfMonth())
                    ->perDay()
                    ->count();
                $format = 'd M'; // Format Tanggal (30 Des)
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Visitors',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            // RAHASIA LABEL RAPI DI SINI: Kita format tanggalnya biar pendek
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format($format)),
        ];
    }

    // --- CONFIG CHART.JS ---
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1, // ANGKA BULAT (1, 2, 3...)
                        'precision' => 0,
                    ],
                    'beginAtZero' => true,
                ],
                'x' => [
                    'ticks' => [
                        'maxTicksLimit' => 8, // BATASI JUMLAH 8 LABEL
                    ],
                ],
            ],
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
}