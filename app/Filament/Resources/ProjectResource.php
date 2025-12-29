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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'tags']; // Bisa cari berdasarkan tags juga
    }

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

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(Tables\Actions\ViewAction::class)

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
            'index'  => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit'   => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}