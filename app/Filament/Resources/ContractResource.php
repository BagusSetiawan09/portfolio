<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Models\Contract;
use App\Models\Order;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Documents';
    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'number';

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'client_name', 'project_title'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Client' => $record->client_name,
            'Project' => $record->project_title,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Metadata')
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('number')
                        ->label('Contract Number')
                        ->disabled()
                        ->dehydrated()
                        ->placeholder('Auto')
                        ->columnSpan(4),

                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'sent' => 'Sent',
                            'signed' => 'Signed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('draft')
                        ->required()
                        ->columnSpan(4),

                    Forms\Components\DateTimePicker::make('signed_at')
                        ->label('Signed At')
                        ->seconds(false)
                        ->columnSpan(4),

                    Forms\Components\Select::make('order_id')
                        ->label('Related Order (optional)')
                        ->options(fn () => Order::query()
                            ->latest()
                            ->limit(200)
                            ->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->columnSpan(6),

                    Forms\Components\Select::make('project_id')
                        ->label('Related Project (optional)')
                        ->options(fn () => Project::query()
                            ->latest()
                            ->limit(200)
                            ->pluck('title', 'id'))
                        ->searchable()
                        ->preload()
                        ->columnSpan(6),
                ]),

            Forms\Components\Section::make('Client & Project')
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('client_name')
                        ->required()
                        ->maxLength(180)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('client_email')
                        ->email()
                        ->maxLength(180)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('client_whatsapp')
                        ->maxLength(60)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('project_title')
                        ->maxLength(200)
                        ->columnSpan(6),

                    Forms\Components\Textarea::make('scope')
                        ->rows(4)
                        ->columnSpan(12)
                        ->helperText('Opsional. Kalau kosong, default template akan pakai placeholder.'),

                    Forms\Components\TextInput::make('price')
                        ->maxLength(120)
                        ->columnSpan(4),

                    Forms\Components\DatePicker::make('start_date')
                        ->columnSpan(4),

                    Forms\Components\DatePicker::make('end_date')
                        ->columnSpan(4),

                    Forms\Components\Textarea::make('payment_terms')
                        ->rows(3)
                        ->columnSpan(12),

                    Forms\Components\Textarea::make('notes')
                        ->rows(3)
                        ->columnSpan(12),
                ]),

            Forms\Components\Section::make('Contract Content')
                ->description('Konten ini bisa kamu edit bebas. Tombol “Generate Template” akan mengisi default template.')
                ->schema([
                    Forms\Components\RichEditor::make('content')
                        ->toolbarButtons([
                            'bold','italic','underline','strike',
                            'h2','h3','bulletList','orderedList',
                            'blockquote','link','undo','redo',
                        ])
                        ->columnSpanFull()
                        ->nullable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(Tables\Actions\ViewAction::class)

            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('number')
                    ->label('Number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('client_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('project_title')
                    ->label('Project')
                    ->toggleable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'signed' => 'Signed',
                        'cancelled' => 'Cancelled',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'signed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->actions([
                // --- ACTION GROUP ---
                Tables\Actions\ActionGroup::make([
                    
                    // 1. Detail
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
                ->button()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit'   => Pages\EditContract::route('/{record}/edit'),
        ];
    }
}