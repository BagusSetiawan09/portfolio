<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'tags'];
    }

    // --- FORM (INPUT DATA) ---
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Project')
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(180)
                        ->live(onBlur: true)
                        ->columnSpan(8)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if (blank($get('slug')) && filled($state)) {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(200)
                        ->unique(ignoreRecord: true)
                        ->columnSpan(4),

                    Forms\Components\TextInput::make('image_url')
                        ->label('Image URL')
                        ->url()
                        ->maxLength(500)
                        ->columnSpan(6)
                        ->helperText('Simpan URL gambar (Unsplash/Cloudinary/S3/dll).'),

                    Forms\Components\TextInput::make('link_url')
                        ->label('Project Link URL')
                        ->url()
                        ->maxLength(500)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('year')
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue((int) now()->format('Y') + 1)
                        ->columnSpan(3),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->columnSpan(3),

                    Forms\Components\Toggle::make('is_published')
                        ->default(true)
                        ->columnSpan(3),

                    Forms\Components\Toggle::make('show_in_latest')
                        ->label('Show in Latest Project')
                        ->default(true)
                        ->columnSpan(3),

                    Forms\Components\Toggle::make('show_in_portfolio')
                        ->label('Show in Portfolio Section')
                        ->default(true)
                        ->columnSpan(3),
                        
                    Forms\Components\TagsInput::make('tags')
                        ->columnSpan(12)
                        ->helperText('Contoh: Laravel, Landing Page, API'),

                    Forms\Components\Textarea::make('excerpt')
                        ->rows(3)
                        ->columnSpan(12),
                ]),
        ]);
    }

    // --- TABLE (LIST DATA) ---
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
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('year')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('image_url')
                    ->label('Image')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn ($record) => $record->image_url ?: null, true),

                Tables\Columns\TextColumn::make('link_url')
                    ->label('Link')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn ($record) => $record->link_url ?: null, true),

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
                    Tables\Actions\ViewAction::make()
                        ->label('Detail')
                        ->color('info')
                        ->icon('heroicon-m-eye')
                        ->slideOver(),

                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-m-pencil-square'),

                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-m-trash'),
                ])
                ->button()
                ->label('Actions')
                ->color('gray')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // --- INFOLIST (TAMPILAN DETAIL POP-UP/PAGE) ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // BAGIAN 1: HEADER UTAMA (LAYOUT DIPERBAIKI)
                Section::make()
                    ->schema([
                        Split::make([
                            // KIRI: Judul & Metadata Dasar
                            Group::make([
                                TextEntry::make('title')
                                    ->label('Nama Project')
                                    ->size(TextEntrySize::Large)
                                    ->weight(FontWeight::Bold),
                                
                                TextEntry::make('slug')
                                    ->icon('heroicon-m-link')
                                    ->color('gray')
                                    ->copyable(),

                                // -- PINDAHKAN TANGGAL KESINI SUPAYA RAPI DI BAWAH JUDUL --
                                TextEntry::make('created_at')
                                    ->label('Dibuat pada')
                                    ->dateTime('d M Y')
                                    ->icon('heroicon-m-calendar')
                                    ->color('gray'),
                            ]),

                            // KANAN: Status Badges (Dibuat Vertical Group supaya rapi)
                            Section::make('Status Penayangan')
                                ->schema([
                                    Grid::make(2)->schema([ // Grid 2 Kolom untuk status
                                        TextEntry::make('is_published')
                                            ->label('Status')
                                            ->badge()
                                            ->formatStateUsing(fn (bool $state) => $state ? 'Published' : 'Draft')
                                            ->color(fn (bool $state) => $state ? 'success' : 'gray'),
                                        
                                        TextEntry::make('show_in_latest')
                                            ->label('Latest Project')
                                            ->badge()
                                            ->formatStateUsing(fn (bool $state) => $state ? 'Yes' : 'No')
                                            ->color(fn (bool $state) => $state ? 'info' : 'gray'),

                                        TextEntry::make('show_in_portfolio')
                                            ->label('Portfolio Page')
                                            ->badge()
                                            ->formatStateUsing(fn (bool $state) => $state ? 'Yes' : 'No')
                                            ->color(fn (bool $state) => $state ? 'info' : 'gray'),
                                    ]),
                                ])->grow(false), 
                        ])->from('md'),
                    ]),

                // BAGIAN 2: SPLIT CONTENT (KIRI TEKS, KANAN GAMBAR)
                Split::make([
                    // KIRI: Detail Project
                    Section::make('Informasi Detail')
                        ->schema([
                            TextEntry::make('year')
                                ->label('Tahun')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('link_url')
                                ->label('Visit Website')
                                ->url(fn ($record) => $record->link_url)
                                ->openUrlInNewTab()
                                ->color('primary')
                                ->icon('heroicon-m-arrow-top-right-on-square')
                                ->visible(fn ($record) => !empty($record->link_url)),

                            TextEntry::make('sort_order')
                                ->label('Urutan'),

                            TextEntry::make('tags')
                                ->label('Teknologi / Tags')
                                ->badge()
                                ->separator(',')
                                ->color('warning'),
                                
                            TextEntry::make('excerpt')
                                ->label('Deskripsi Singkat')
                                ->markdown()
                                ->columnSpanFull(),
                        ])->grow(), // Mengambil ruang sisa

                    // KANAN: Gambar Preview
                    Section::make('Preview')
                        ->schema([
                            ImageEntry::make('image_url')
                                ->hiddenLabel()
                                ->height(250)
                                ->extraImgAttributes([
                                    'class' => 'rounded-xl shadow-lg object-cover w-full',
                                    'style' => 'object-position: center top;',
                                ]),
                        ])->grow(false), // Ukuran pas sesuai gambar
                ])->from('md')->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit'   => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}