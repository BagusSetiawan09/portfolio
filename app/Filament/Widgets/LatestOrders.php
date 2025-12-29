<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where('type', 'order')
                    ->when(
                        $this->filters['startDate'] ?? null,
                        fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date)
                    )
                    ->when(
                        $this->filters['endDate'] ?? null,
                        fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date)
                    )
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order date')
                    ->date('M d, Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('id')
                    ->label('Number')
                    ->formatStateUsing(fn ($state) => 'OR'.$state)
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Customer')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'in_progress' => 'warning',
                        'processing' => 'warning',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (?string $state): ?string => match ($state) {
                        'new' => 'heroicon-m-sparkles',
                        'in_progress' => 'heroicon-m-arrow-path',
                        'processing' => 'heroicon-m-arrow-path',
                        'done' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle',
                        default => null,
                    }),

                Tables\Columns\TextColumn::make('budget_range')
                    ->label('Budget')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('open_action')
                    ->label('')
                    ->default('Open')
                    ->color('primary')
                    ->url(fn (Order $record): string => route('filament.admin.resources.orders.edit', $record)),
            ])
            ->paginated(false);
    }
}