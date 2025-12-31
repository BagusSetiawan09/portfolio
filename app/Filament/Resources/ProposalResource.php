<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages;
use App\Models\Proposal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

// IMPORT BARU UNTUK VIEW (INFOLIST)
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\IconEntry;

class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Documents';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Proposal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('client_name')
                            ->label('Nama Klien / Perusahaan')
                            ->required(),

                        TextInput::make('project_title')
                            ->label('Judul Projek')
                            ->required(),

                        DatePicker::make('proposal_date')
                            ->label('Tanggal Kirim')
                            ->default(now())
                            ->required(),

                        Select::make('status')
                            ->options([
                                'pending' => 'Draft / Pending',
                                'sent' => 'Sent (Terkirim)',
                                'accepted' => 'Accepted (Deal)',
                                'rejected' => 'Rejected (Ditolak)',
                            ])
                            ->default('pending')
                            ->required(),
                    ]),

                Section::make('File & Catatan')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('File Proposal (PDF)')
                            ->directory('proposals')
                            ->acceptedFileTypes(['application/pdf'])
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Catatan Internal')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // --- TAMPILAN VIEW DETAILS (INFOLIST) ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Informasi Utama')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('client_name')->label('Nama Klien')->weight('bold'),
                        TextEntry::make('project_title')->label('Judul Projek'),
                        TextEntry::make('proposal_date')->date('d F Y')->label('Tanggal'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'gray',
                                'sent' => 'info',
                                'accepted' => 'success',
                                'rejected' => 'danger',
                            }),
                    ]),
                
                InfoSection::make('Dokumen & Catatan')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('Catatan Internal')
                            ->placeholder('Tidak ada catatan.'),
                        
                        TextEntry::make('file_path')
                            ->label('File Proposal')
                            ->formatStateUsing(fn ($state) => $state ? 'Download / View PDF' : 'Tidak ada file')
                            ->icon('heroicon-o-document-arrow-down')
                            ->url(fn ($record) => $record->file_path ? asset('storage/' . $record->file_path) : null, true)
                            ->color('primary'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client_name')->label('Klien')->searchable()->weight('bold'),
                TextColumn::make('project_title')->label('Judul Projek')->searchable()->limit(30),
                TextColumn::make('proposal_date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'sent' => 'info',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->defaultSort('proposal_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Sent',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
            ])
            
            // --- GROUP ACTION (TITIK TIGA) ---
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'), 
                    
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    
                    Tables\Actions\DeleteAction::make(),
                ])
                // --- PERUBAHAN DISINI: JADI BUTTON ---
                ->button()       // <--- Ubah jadi bentuk Tombol
                ->label('Actions')
                ->color('gray')  // <--- Warna Tombol (Gray/Info/Primary)
                // Kita tidak butuh style aneh-aneh lagi karena Button sudah pasti rapi
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null)
            ->recordAction('view');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }
}