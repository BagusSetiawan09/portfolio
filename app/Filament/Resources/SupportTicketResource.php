<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Support Ticket';
    protected static ?string $pluralModelLabel = 'Support Tickets';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Client Info')
                ->columns(12)
                ->schema([
                    Forms\Components\Select::make('client_id')
                        ->label('Client (Relasi)')
                        ->relationship('client', 'name')
                        ->searchable()
                        ->preload()
                        ->columnSpan(4)
                        ->visible(fn () => class_exists(\App\Models\Client::class)),

                    Forms\Components\TextInput::make('client_name')
                        ->label('Nama Client')
                        ->columnSpan(4)
                        ->visible(fn () => ! class_exists(\App\Models\Client::class)),

                    Forms\Components\TextInput::make('client_email')
                        ->label('Email')
                        ->email()
                        ->columnSpan(4),

                    Forms\Components\TextInput::make('client_whatsapp')
                        ->label('WhatsApp')
                        ->columnSpan(4),

                    Forms\Components\TextInput::make('website_url')
                        ->label('Website URL')
                        ->url()
                        ->columnSpan(8),
                ]),

            Forms\Components\Section::make('Ticket')
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('subject')
                        ->label('Subject')
                        ->required()
                        ->columnSpan(8),

                    Forms\Components\Select::make('category')
                        ->label('Category')
                        ->options([
                            'maintenance' => 'Maintenance',
                            'bug' => 'Bug',
                            'request' => 'Feature Request',
                            'server' => 'Server / Hosting',
                            'billing' => 'Billing',
                        ])
                        ->default('maintenance')
                        ->required()
                        ->columnSpan(4),

                    Forms\Components\Select::make('priority')
                        ->label('Priority')
                        ->options([
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High',
                            'urgent' => 'Urgent',
                        ])
                        ->default('medium')
                        ->required()
                        ->columnSpan(4),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'open' => 'Open',
                            'in_progress' => 'In Progress',
                            'waiting_client' => 'Waiting Client',
                            'resolved' => 'Resolved',
                            'closed' => 'Closed',
                        ])
                        ->default('open')
                        ->required()
                        ->columnSpan(4),

                    Forms\Components\Select::make('assigned_to')
                        ->label('Assigned To')
                        ->relationship('assignee', 'name')
                        ->searchable()
                        ->preload()
                        ->columnSpan(4),

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(6)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('attachments')
                        ->label('Attachments')
                        ->multiple()
                        ->reorderable()
                        ->directory('tickets')
                        ->disk('public')
                        ->openable()
                        ->downloadable()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // klik row => VIEW slide-over dari kanan
            ->recordUrl(null)
            ->recordAction('view')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('client_display_name')
                    ->label('Client')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->limit(35)
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->label('Category'),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->label('Priority')
                    ->color(fn (string $state) => match ($state) {
                        'low' => 'gray',
                        'medium' => 'primary',
                        'high' => 'warning',
                        'urgent' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->color(fn (string $state) => match ($state) {
                        'open' => 'gray',
                        'in_progress' => 'primary',
                        'waiting_client' => 'warning',
                        'resolved' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->slideOver()
                        ->modalWidth('6xl'),

                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->modalWidth('6xl'),

                    Tables\Actions\Action::make('markResolved')
                        ->label('Mark Resolved')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (SupportTicket $record) => $record->status !== 'resolved')
                        ->action(function (SupportTicket $record) {
                            $record->update([
                                'status' => 'resolved',
                                'resolved_at' => now(),
                            ]);
                        }),

                    Tables\Actions\DeleteAction::make(),
                ])
                    ->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->button()
                    ->color('gray'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Client')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('client_display_name')->label('Nama Client')->placeholder('-'),
                        TextEntry::make('client_email')->label('Email')->placeholder('-')->copyable(),
                        TextEntry::make('client_whatsapp')->label('WhatsApp')->placeholder('-')->copyable(),
                    ]),
                    TextEntry::make('website_url')
                        ->label('Website URL')
                        ->placeholder('-')
                        ->copyable()
                        ->url(fn ($state) => $state ?: null, true),
                ]),

            Section::make('Ticket')
                ->schema([
                    Grid::make(4)->schema([
                        TextEntry::make('category')->label('Category')->badge(),
                        TextEntry::make('priority')->label('Priority')->badge(),
                        TextEntry::make('status')->label('Status')->badge(),
                        TextEntry::make('assignee.name')->label('Assigned To')->placeholder('-'),
                    ]),
                    TextEntry::make('subject')->label('Subject')->columnSpanFull(),
                    TextEntry::make('description')->label('Description')->placeholder('-')->columnSpanFull(),
                ]),

            Section::make('Attachments')
                ->schema([
                    TextEntry::make('attachments')
                        ->label('Files')
                        ->formatStateUsing(function ($state) {
                            if (empty($state) || !is_array($state)) return '-';

                            // tampilkan list path file saja (copyable)
                            return implode("\n", $state);
                        })
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            'create' => Pages\CreateSupportTicket::route('/create'),
            'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
