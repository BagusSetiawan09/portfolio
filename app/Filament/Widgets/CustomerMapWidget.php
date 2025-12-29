<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;

class CustomerMapWidget extends Widget
{
    protected static string $view = 'filament.widgets.customer-map-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function getViewData(): array
    {
        $locations = Order::query()
            ->where('type', 'order')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get(['name', 'city', 'country', 'lat', 'lng'])
            ->map(fn ($order) => [
                'lat' => (float) $order->lat,
                'lng' => (float) $order->lng,
                'popup' => "<b>{$order->name}</b><br>{$order->city}, {$order->country}",
            ]);

        return [
            'locations' => $locations,
        ];
    }
}