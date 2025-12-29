<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;

class CustomerMapWidget extends Widget
{
    protected static string $view = 'filament.widgets.customer-map-widget';

    // Atur lebar widget agar full (opsional)
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5; // Taruh di paling bawah

    public function getViewData(): array
    {
        // Ambil data order yang punya koordinat (lat/lng tidak null)
        $locations = Order::query()
            ->where('type', 'order')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get(['name', 'city', 'country', 'lat', 'lng']) // Ambil kolom penting saja
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