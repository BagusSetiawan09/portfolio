<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order / Consultation')
                ->columns(12)
                ->schema([
                    Forms\Components\Select::make('type')
                        ->required()
                        ->default('order')
                        ->options([
                            'order' => 'Order',
                            'consultation' => 'Consultation',
                        ])
                        ->columnSpan(6),

                    Forms\Components\Select::make('status')
                        ->required()
                        ->default('new')
                        ->options([
                            'new' => 'New',
                            'in_progress' => 'In Progress',
                            'done' => 'Done',
                            'cancelled' => 'Cancelled',
                        ])
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(120)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(180)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('whatsapp')
                        ->label('WhatsApp')
                        ->maxLength(40)
                        ->helperText('Contoh: 62895628894070')
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('service')
                        ->maxLength(120)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('topic')
                        ->maxLength(160)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('budget_range')
                        ->label('Budget Range')
                        ->maxLength(120)
                        ->placeholder('ex: 1–3jt / 3–5jt / 5jt+')
                        ->columnSpan(6),

                    Forms\Components\DatePicker::make('deadline')
                        ->label('Deadline')
                        ->native(false)
                        ->columnSpan(6),

                    Forms\Components\Select::make('preferred_channel')
                        ->label('Preferred Channel')
                        ->options([
                            'whatsapp' => 'WhatsApp',
                            'email' => 'Email',
                            'meet' => 'Google Meet',
                        ])
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('preferred_time')
                        ->label('Preferred Time')
                        ->maxLength(80)
                        ->placeholder('ex: 19:00–21:00 WIB')
                        ->columnSpan(6),

                    Forms\Components\Textarea::make('message')
                        ->rows(6)
                        ->columnSpan(12),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'order' => 'success',
                        'consultation' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('service')
                    ->toggleable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'new'         => 'New',
                        'in_progress' => 'In Progress',
                        'done'        => 'Done',
                        'cancelled'   => 'Cancelled',
                        default       => ucfirst(str_replace('_', ' ', (string) $state)),
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'new'         => 'warning',
                        'in_progress' => 'info',
                        'done'        => 'success',
                        'cancelled'   => 'danger',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->toggleable()
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('email')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('topic')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(30),

                Tables\Columns\TextColumn::make('budget_range')
                    ->label('Budget')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(20),

                Tables\Columns\TextColumn::make('deadline')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date(),

                Tables\Columns\TextColumn::make('message')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(60)
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'order' => 'Order',
                        'consultation' => 'Consultation',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_in_progress')
                    ->label('In Progress')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'new')
                    ->action(fn ($record) => $record->update(['status' => 'in_progress'])),

                Tables\Actions\Action::make('mark_done')
                    ->label('Done')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['new','in_progress'], true))
                    ->action(fn ($record) => $record->update(['status' => 'done'])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
