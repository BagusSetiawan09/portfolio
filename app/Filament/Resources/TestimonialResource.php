<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                            5 => '5',
                            4 => '4',
                            3 => '3',
                            2 => '2',
                            1 => '1',
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
            ->recordAction(Tables\Actions\ViewAction::class)

            ->defaultSort('sort_order', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role')
                    ->toggleable()
                    ->limit(25),

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
                    }),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
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
                // --- ACTION GROUP ---
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
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('dark')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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