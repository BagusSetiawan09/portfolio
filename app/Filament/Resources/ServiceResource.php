<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// --- IMPORT INFOLIST COMPONENTS ---
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Group;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\TextEntry\TextEntrySize;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Service')
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(180)
                        ->columnSpan(8),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->columnSpan(2),

                    Forms\Components\Toggle::make('is_published')
                        ->default(true)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('image_url')
                        ->label('Image URL')
                        ->url()
                        ->maxLength(500)
                        ->columnSpan(6)
                        ->helperText('URL Gambar Service'),

                    Forms\Components\TextInput::make('link_url')
                        ->label('Link URL')
                        ->url()
                        ->maxLength(500)
                        ->columnSpan(6),

                    Forms\Components\TagsInput::make('tags')
                        ->columnSpan(12)
                        ->helperText('Contoh: Web Design, UI/UX, Maintenance'),

                    Forms\Components\Textarea::make('excerpt')
                        ->label('Short Description')
                        ->rows(3)
                        ->columnSpan(12),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction('view')

            ->defaultSort('sort_order', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('image_url')
                    ->label('Image')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn ($record) => $record->image_url ?: null, true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published')
                    ->placeholder('All')
                    ->trueLabel('Published')
                    ->falseLabel('Draft'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    
                    // 1. Detail (SlideOver)
                    Tables\Actions\ViewAction::make()
                        ->label('Detail')
                        ->color('info')
                        ->icon('heroicon-m-eye')
                        ->slideOver(),

                    // 2. Edit
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-m-pencil-square'),

                    // 3. Delete
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-m-trash'),

                ])
                ->button()        // <--- Ubah jadi Button Kotak
                ->label('Actions')
                ->color('gray')   // <--- Warna Putih/Netral
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // --- INFOLIST (TAMPILAN DETAIL POP-UP) ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Header
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make(1)->schema([
                                TextEntry::make('title')
                                    ->label('Nama Service')
                                    ->size(TextEntrySize::Large)
                                    ->weight(FontWeight::Bold),
                            ]),
                            Group::make([
                                IconEntry::make('is_published')
                                    ->label('Active')
                                    ->boolean(),
                            ])->grow(false),
                        ])->from('md')
                    ]),

                // Content Split
                Split::make([
                    // KIRI: Detail Info
                    Section::make('Informasi')
                        ->schema([
                            TextEntry::make('link_url')
                                ->label('Service Link')
                                ->url(fn ($record) => $record->link_url)
                                ->openUrlInNewTab()
                                ->color('primary')
                                ->icon('heroicon-m-arrow-top-right-on-square')
                                ->visible(fn ($record) => !empty($record->link_url)),

                            TextEntry::make('sort_order')
                                ->label('Urutan Tampil'),

                            TextEntry::make('tags')
                                ->badge()
                                ->separator(',')
                                ->color('success'),

                            TextEntry::make('excerpt')
                                ->label('Deskripsi')
                                ->markdown()
                                ->prose(),
                        ])->grow(),

                    // KANAN: Gambar Preview
                    Section::make('Preview')
                        ->schema([
                            ImageEntry::make('image_url')
                                ->hiddenLabel()
                                ->height(200)
                                ->extraImgAttributes([
                                    'class' => 'rounded-xl shadow-lg object-cover w-full',
                                ]),
                        ])->grow(false), // Lebar menyesuaikan gambar
                ])->from('md')->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
        ];
    }
}