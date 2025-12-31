<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
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

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Testimonial')
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Title (optional)')
                        ->maxLength(180)
                        ->columnSpan(6),

                    Forms\Components\Select::make('rating')
                        ->options([
                            5 => '5 - Excellent',
                            4 => '4 - Good',
                            3 => '3 - Average',
                            2 => '2 - Poor',
                            1 => '1 - Terrible',
                        ])
                        ->default(5)
                        ->required()
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->columnSpan(2),

                    Forms\Components\Toggle::make('is_published')
                        ->default(true)
                        ->columnSpan(2),

                    Forms\Components\Textarea::make('text')
                        ->required()
                        ->rows(4)
                        ->columnSpan(12),

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(120)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('role')
                        ->maxLength(120)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('avatar_url')
                        ->label('Avatar URL')
                        ->url()
                        ->maxLength(500)
                        ->columnSpan(12)
                        ->helperText('Contoh: https://i.pravatar.cc/120?img=45'),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('role')
                    ->toggleable()
                    ->limit(25)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('rating')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match ((int) $state) {
                        5 => 'success',
                        4 => 'warning',
                        3 => 'warning',
                        2 => 'danger',
                        1 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state . ' ★'),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('avatar_url')
                    ->label('Avatar')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn ($record) => $record->avatar_url ?: null, true),

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
                // Header: Nama & Role
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make(1)->schema([
                                TextEntry::make('name')
                                    ->label('Client Name')
                                    ->size(TextEntrySize::Large)
                                    ->weight(FontWeight::Bold),
                                
                                TextEntry::make('role')
                                    ->icon('heroicon-m-briefcase')
                                    ->color('gray'),
                            ]),

                            Group::make([
                                TextEntry::make('rating')
                                    ->badge()
                                    ->color(fn ($state) => match ((int) $state) {
                                        5 => 'success',
                                        4, 3 => 'warning',
                                        default => 'danger',
                                    })
                                    ->formatStateUsing(fn ($state) => str_repeat('★', $state) . " ($state/5)"),
                                
                                IconEntry::make('is_published')
                                    ->label('Active')
                                    ->boolean(),
                            ])->grow(false),
                        ])->from('md'),
                    ]),

                // Content Split
                Split::make([
                    // KIRI: Pesan Testimonial
                    Section::make('Ulasan')
                        ->schema([
                            TextEntry::make('title')
                                ->label('Judul Ulasan')
                                ->weight('bold')
                                ->visible(fn ($record) => !empty($record->title)),

                            TextEntry::make('text')
                                ->hiddenLabel()
                                ->markdown()
                                ->prose(),
                        ])->grow(),

                    // KANAN: Avatar
                    Section::make('Avatar')
                        ->schema([
                            ImageEntry::make('avatar_url')
                                ->hiddenLabel()
                                ->circular() // Biar bulat seperti foto profil
                                ->height(150)
                                ->defaultImageUrl('https://ui-avatars.com/api/?name=User'), // Fallback jika kosong
                        ])->grow(false),
                ])->from('md')->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit'   => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}