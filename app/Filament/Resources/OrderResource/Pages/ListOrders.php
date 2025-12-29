<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // 1. Panggil Widget Statistik di Header Halaman
    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    // 2. Buat Tabs Filter Status di bawah Statistik
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Orders')
                ->icon('heroicon-m-list-bullet'),

            'new' => Tab::make('New')
                ->icon('heroicon-m-sparkles')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new'))
                ->badge(resource_path('status') === 'new' ? null : \App\Models\Order::where('status', 'new')->count())
                ->badgeColor('warning'),

            'in_progress' => Tab::make('In Progress')
                ->icon('heroicon-m-arrow-path')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_progress'))
                ->badge(\App\Models\Order::where('status', 'in_progress')->count())
                ->badgeColor('info'),

            'done' => Tab::make('Done')
                ->icon('heroicon-m-check-badge')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'done'))
                ->badgeColor('success'),

            'cancelled' => Tab::make('Cancelled')
                ->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled'))
                ->badgeColor('danger'),
        ];
    }
}