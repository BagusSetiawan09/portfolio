<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientProjectResource\Pages;
use App\Models\ClientProject;
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

class ClientProjectResource extends Resource
{
    protected static ?string $model = ClientProject::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Client Project';
    protected static ?string $pluralModelLabel = 'Client Projects';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Client & Project')
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
                        ->label('Nama Client (Manual)')
                        ->placeholder('Isi kalau tidak pakai relasi client')
                        ->columnSpan(4)
                        ->visible(fn () => ! class_exists(\App\Models\Client::class)),

                    Forms\Components\TextInput::make('project_type')
                        ->label('Jenis Order / Project')
                        ->placeholder('Website Company Profile / Maintenance / Web App / dll')
                        ->columnSpan(4),

                    Forms\Components\Select::make('order_status')
                        ->label('Status Order')
                        ->options([
                            'new' => 'New',
                            'progress' => 'Progress',
                            'waiting' => 'Waiting Client',
                            'pending_payment' => 'Pending Payment',
                            'done' => 'Done',
                            'cancel' => 'Cancel',
                        ])
                        ->default('new')
                        ->columnSpan(4),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true)
                        ->columnSpan(4),
                ]),

            Forms\Components\Section::make('Website')
                ->columns(12)
                ->schema([
                    Forms\Components\Select::make('website_status')
                        ->label('Status Website')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'maintenance' => 'Maintenance',
                            'offline' => 'Offline',
                        ])
                        ->default('draft')
                        ->columnSpan(4),

                    Forms\Components\TextInput::make('domain')
                        ->label('Domain')
                        ->placeholder('codexly.site')
                        ->columnSpan(4),

                    Forms\Components\TextInput::make('website_url')
                        ->label('URL Website')
                        ->placeholder('https://codexly.site')
                        ->url()
                        ->columnSpan(4),
                ]),

            Forms\Components\Section::make('Server (Jika kamu pegang server mereka)')
                ->columns(12)
                ->schema([
                    Forms\Components\Select::make('server_type')
                        ->label('Jenis Server')
                        ->options([
                            'none' => 'Tidak dipegang',
                            'cpanel' => 'cPanel',
                            'vps' => 'VPS',
                            'shared' => 'Shared Hosting',
                            'cloud' => 'Cloud (AWS/GCP/etc)',
                        ])
                        ->default('none')
                        ->columnSpan(4),

                    Forms\Components\TextInput::make('server_provider')
                        ->label('Provider')
                        ->placeholder('Niagahoster / AWS / dll')
                        ->columnSpan(4),

                    Forms\Components\TextInput::make('server_ip')
                        ->label('IP Server')
                        ->placeholder('123.123.123.123')
                        ->columnSpan(4),

                    Forms\Components\TextInput::make('server_panel_url')
                        ->label('Panel URL / Login')
                        ->placeholder('https://domain.com:2083')
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('server_username')
                        ->label('Username')
                        ->columnSpan(3),

                    Forms\Components\TextInput::make('server_password')
                        ->label('Password (Encrypted / Plain)')
                        ->password()
                        ->revealable()
                        ->columnSpan(3),

                    Forms\Components\Textarea::make('server_notes')
                        ->label('Catatan akses (opsional)')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Renewal & Maintenance Dates')
                ->columns(12)
                ->schema([
                    Forms\Components\DatePicker::make('hosting_expires_at')
                        ->label('Hosting Expired')
                        ->columnSpan(4),

                    Forms\Components\DatePicker::make('ssl_expires_at')
                        ->label('SSL Expired')
                        ->columnSpan(4),

                    Forms\Components\DateTimePicker::make('last_backup_at')
                        ->label('Last Backup')
                        ->seconds(false)
                        ->columnSpan(4),

                    Forms\Components\DateTimePicker::make('last_deploy_at')
                        ->label('Last Deploy')
                        ->seconds(false)
                        ->columnSpan(4),
                ]),

            Forms\Components\Section::make('Notes / Next Action')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan internal + Next action')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    /**
     * VIEW slide-over dari kanan (seperti Orders).
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Informasi Client')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('client_display_name')
                            ->label('Nama Client')
                            ->placeholder('-'),

                        TextEntry::make('project_type')
                            ->label('Jenis Project')
                            ->placeholder('-'),

                        TextEntry::make('order_status')
                            ->label('Status Order')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ?: '-'),
                    ]),
                ]),

            Section::make('Website')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('website_status')
                            ->label('Status Web')
                            ->badge()
                            ->placeholder('-'),

                        TextEntry::make('domain')
                            ->label('Domain')
                            ->placeholder('-'),

                        TextEntry::make('website_url')
                            ->label('URL Website')
                            ->placeholder('-')
                            ->copyable()
                            ->url(fn ($state) => $state ?: null, true),
                    ]),
                ]),

            // ✅ SERVER (dirapikan + password ditampilkan)
            Section::make('Server')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('server_type')
                            ->label('Jenis Server')
                            ->formatStateUsing(fn ($state) => $state ? Str::upper($state) : '-'),

                        TextEntry::make('server_provider')
                            ->label('Provider')
                            ->placeholder('-'),

                        TextEntry::make('server_ip')
                            ->label('IP Server')
                            ->placeholder('-')
                            ->copyable(),
                    ]),

                    Grid::make(3)->schema([
                        TextEntry::make('server_panel_url')
                            ->label('Panel URL')
                            ->placeholder('-')
                            ->copyable()
                            ->url(fn ($state) => $state ?: null, true),

                        TextEntry::make('server_username')
                            ->label('Username')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('server_password')
                            ->label('Password')
                            ->placeholder('-')
                            ->copyable(),
                    ]),

                    TextEntry::make('server_notes')
                        ->label('Catatan Server')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ]),

            Section::make('Renewal / Maintenance')
                ->schema([
                    Grid::make(4)->schema([
                        TextEntry::make('hosting_expires_at')
                            ->label('Hosting Expired')
                            ->date('d M Y')
                            ->placeholder('-'),

                        TextEntry::make('ssl_expires_at')
                            ->label('SSL Expired')
                            ->date('d M Y')
                            ->placeholder('-'),

                        TextEntry::make('last_backup_at')
                            ->label('Last Backup')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-'),

                        TextEntry::make('last_deploy_at')
                            ->label('Last Deploy')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-'),
                    ]),
                ]),

            Section::make('Notes')
                ->schema([
                    TextEntry::make('notes')
                        ->label('Catatan / Next Action')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction('view')
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('client_display_name')
                    ->label('Client')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('project_type')
                    ->label('Project')
                    ->limit(28)
                    ->searchable(),

                Tables\Columns\TextColumn::make('order_status')
                    ->badge()
                    ->label('Order')
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'gray',
                        'progress' => 'primary',
                        'waiting' => 'warning',
                        'pending_payment' => 'danger',
                        'done' => 'success',
                        'cancel' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('website_status')
                    ->badge()
                    ->label('Web')
                    ->color(fn (string $state) => match ($state) {
                        'published' => 'success',
                        'maintenance' => 'warning',
                        'offline' => 'danger',
                        'draft' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('server_type')
                    ->label('Server')
                    ->formatStateUsing(fn ($state) => $state ? Str::upper($state) : '-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('domain')
                    ->label('Domain')
                    ->toggleable(),
            ])
            ->actions([
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->modalWidth('6xl'),

                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalWidth('6xl'),

                Tables\Actions\Action::make('openWebsite')
                    ->label('Open Web')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (ClientProject $record) => $record->website_url ?: null)
                    ->openUrlInNewTab()
                    ->visible(fn (ClientProject $record) => ! empty($record->website_url)),

                Tables\Actions\DeleteAction::make(),
            ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button()
                ->color('gray'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientProjects::route('/'),
            'create' => Pages\CreateClientProject::route('/create'),
            'edit' => Pages\EditClientProject::route('/{record}/edit'),
        ];
    }
}
