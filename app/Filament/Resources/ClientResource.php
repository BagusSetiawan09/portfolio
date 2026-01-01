<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; 
    protected static ?string $navigationLabel = 'Clients';
    protected static ?string $modelLabel = 'Klien';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Orders';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Input Data Klien')
                ->description('Masukkan data lengkap klien untuk keperluan surat-menyurat.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Klien / PIC')
                        ->required()
                        ->placeholder('Contoh: Budi Santoso')
                        ->maxLength(255),
                        
                    Forms\Components\TextInput::make('company')
                        ->label('Nama Perusahaan / Instansi')
                        ->placeholder('Contoh: PT. Maju Mundur')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->label('Alamat Email')
                        ->placeholder('email@klien.com')
                        ->prefixIcon('heroicon-m-envelope')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->label('No. Handphone / WA')
                        ->placeholder('0812xxxx')
                        ->prefixIcon('heroicon-m-phone')
                        ->maxLength(20),

                    Forms\Components\Textarea::make('address')
                        ->label('Alamat Lengkap')
                        ->placeholder('Alamat lengkap untuk tujuan surat...')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // --- BAGIAN INI YANG MEMBUAT KLIK ROW JADI VIEW ---
            ->recordUrl(null)       // 1. Matikan link ke Edit
            ->recordAction('view')  // 2. Aktifkan aksi View saat diklik
            // --------------------------------------------------
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('company')
                    ->label('Perusahaan')
                    ->searchable()
                    ->description(fn (Client $record): string => $record->email ?? '-'), 

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->icon('heroicon-m-phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // --- TOMBOL ACTIONS (BUTTON STYLE) ---
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->button()              
                ->label('Actions')      
                ->color('gray')         
                ->icon('heroicon-m-chevron-down'), 
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // --- TAMPILAN VIEW (DETAIL POP-UP) ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Detail Klien')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('name')
                                ->label('Nama Klien')
                                ->weight('bold')
                                ->icon('heroicon-m-user'),
                            
                            TextEntry::make('company')
                                ->label('Perusahaan')
                                ->icon('heroicon-m-building-office'),

                            TextEntry::make('email')
                                ->label('Email')
                                ->icon('heroicon-m-envelope')
                                ->copyable(),

                            TextEntry::make('phone')
                                ->label('Telepon')
                                ->icon('heroicon-m-phone')
                                ->copyable(),

                            TextEntry::make('address')
                                ->label('Alamat Lengkap')
                                ->icon('heroicon-m-map-pin')
                                ->columnSpanFull(),
                        ])
                    ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}