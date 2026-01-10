<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages;
use App\Models\Proposal;
use App\Models\ClientProject; // <-- IMPORT MODEL PROJECT
use App\Filament\Resources\ClientProjectResource; // <-- IMPORT RESOURCE PROJECT
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

// IMPORT TAMBAHAN
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as InfoSection;

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
                // --- BAGIAN 1: DETAIL UTAMA ---
                Section::make('Detail Proposal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('client_name')->label('Nama Klien / Perusahaan')->required(),
                        TextInput::make('project_title')->label('Judul Projek')->required(),
                        DatePicker::make('proposal_date')->label('Tanggal Kirim')->default(now())->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Draft / Pending',
                                'sent' => 'Sent (Terkirim)',
                                'accepted' => 'Accepted (Deal)',
                                'rejected' => 'Rejected (Ditolak)',
                            ])
                            ->default('pending')->required(),
                    ]),

                // --- BAGIAN 2: TABS ---
                Section::make('Metode & Konten')
                    ->schema([
                        Tabs::make('Metode Proposal')
                            ->tabs([
                                // TAB A: UPLOAD PDF
                                Tabs\Tab::make('Upload PDF')
                                    ->schema([
                                        FileUpload::make('file_path')
                                            ->label('File Proposal (PDF)')
                                            ->directory('proposals')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->downloadable()->openable(),
                                        Textarea::make('notes')->label('Catatan Internal')->rows(3),
                                    ]),

                                // TAB B: AI GENERATOR
                                Tabs\Tab::make('Create with AI')
                                    ->schema([
                                        // 1. INPUT INSTRUKSI
                                        Textarea::make('ai_prompt')
                                            ->label('Instruksi untuk AI')
                                            ->placeholder('Contoh: Buatkan proposal website company profile, budget 5 juta...')
                                            ->rows(3)
                                            ->dehydrated(false),
                                        
                                        // 2. TOMBOL AKSI
                                        Actions::make([
                                            Action::make('check_models')
                                                ->label('Cek Model Tersedia')
                                                ->color('gray')
                                                ->action(function () {
                                                    $apiKey = env('GEMINI_API_KEY');
                                                    if(empty($apiKey)){
                                                        Notification::make()->title('API Key kosong di .env')->danger()->send();
                                                        return;
                                                    }
                                                    /** @var \Illuminate\Http\Client\Response $response */
                                                    $response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
                                                    $res = json_decode($response->body(), true);
                                                    if(isset($res['error'])) {
                                                        Notification::make()->title('Error Google: '.$res['error']['message'])->danger()->send();
                                                        return;
                                                    }
                                                    $models = collect($res['models'] ?? [])->pluck('name')->filter(fn($n) => str_contains($n, 'gemini'))->implode(', ');
                                                    Notification::make()->title('Model Aktif:')->body($models ?: 'Tidak ada model Gemini aktif.')->success()->persistent()->send();
                                                }),

                                            Action::make('generate_ai')
                                                ->label('Generate Proposal')
                                                ->color('primary')
                                                ->action(function (Set $set, Get $get) {
                                                    $prompt = $get('ai_prompt');
                                                    $client = $get('client_name');
                                                    $project = $get('project_title');

                                                    if (!$prompt) {
                                                        Notification::make()->title('Isi instruksi dulu!')->warning()->send();
                                                        return;
                                                    }
                                                    Notification::make()->title('Sedang berpikir...')->info()->send();
                                                    $finalPrompt = "Buatkan proposal proyek format HTML (h3, p, ul, li). Klien: $client. Proyek: $project. Detail: $prompt. Bahasa Indonesia formal.";

                                                    try {
                                                        $apiKey = env('GEMINI_API_KEY');
                                                        /** @var \Illuminate\Http\Client\Response $response */
                                                        $response = Http::withHeaders(['Content-Type' => 'application/json'])
                                                            ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                                                                'contents' => [['parts' => [['text' => $finalPrompt]]]]
                                                            ]);
                                                        $result = json_decode($response->body(), true);
                                                        if (isset($result['error'])) {
                                                            Notification::make()->title('Gagal:')->body($result['error']['message'])->danger()->persistent()->send();
                                                            return;
                                                        }
                                                        $generatedText = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
                                                        if($generatedText) {
                                                            $set('content', $generatedText);
                                                            Notification::make()->title('Berhasil!')->success()->send();
                                                        } else {
                                                            Notification::make()->title('API merespon tapi tidak ada teks.')->warning()->send();
                                                        }
                                                    } catch (\Exception $e) {
                                                        Notification::make()->title('Error Sistem: ' . $e->getMessage())->danger()->send();
                                                    }
                                                }),
                                        ]),

                                        // 3. EDITOR HASIL
                                        RichEditor::make('content')
                                            ->label('Isi Proposal (Generated)'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Informasi Utama')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('client_name')->label('Nama Klien'),
                        TextEntry::make('project_title')->label('Judul Projek'),
                        TextEntry::make('proposal_date')->date('d F Y')->label('Tanggal'),
                        TextEntry::make('status')->badge()->color('info'),
                    ]),
                
                InfoSection::make('Isi Proposal')
                    ->schema([
                        TextEntry::make('content')->html()->visible(fn ($record) => !empty($record->content)),
                        TextEntry::make('file_path')->label('File PDF')
                            ->formatStateUsing(fn ($state) => $state ? 'Download PDF' : '-')
                            ->url(fn (Proposal $record) => $record->file_path ? asset('storage/'.$record->file_path) : null, true)
                            ->color('primary')
                            ->visible(fn ($record) => !empty($record->file_path)),
                        TextEntry::make('notes')->label('Catatan'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client_name')->label('Klien')->searchable()->weight('bold'),
                TextColumn::make('project_title')->label('Judul Projek')->searchable()->limit(30),
                TextColumn::make('proposal_date')->date('d M Y')->sortable(),
                TextColumn::make('status')->badge(),
            ])
            ->defaultSort('proposal_date', 'desc')
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->color('info'), 
                    Tables\Actions\EditAction::make()->color('warning'),
                    
                    // 1. TOMBOL DOWNLOAD PDF
                    Tables\Actions\Action::make('download_pdf')
                        ->label('Download PDF')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->url(fn (Proposal $record) => url('/admin/documents/proposal/'.$record->id.'/download')) // Update URL sesuai route baru
                        ->openUrlInNewTab(), 

                    // 2. TOMBOL CONVERT TO PROJECT (BARU)
                    Tables\Actions\Action::make('convert_project')
                        ->label('Convert to Project')
                        ->icon('heroicon-o-rocket-launch')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Konversi ke Project?')
                        ->modalDescription('Sistem akan membuat Project baru otomatis dari data proposal ini.')
                        ->modalSubmitActionLabel('Ya, Buat Project')
                        ->action(function (Proposal $record) {
                            // --- CEK KOLOM DB 'client_projects' KAMU DI SINI ---
                            $newProject = ClientProject::create([
                                // 1. Nama Klien
                                'client_name'   => $record->client_name,
                                
                                // 2. Judul Projek (Masuk ke kolom 'project_type')
                                'project_type'  => $record->project_title, 
                                
                                // 3. Isi Proposal (Masuk ke kolom 'notes')
                                // Kita bersihkan HTML tags biar rapi saat masuk ke Textarea notes
                                'notes'         => strip_tags($record->content), 
                                
                                // 4. Default Values (Wajib ada biar gak error)
                                'order_status'  => 'new', 
                                'website_status'=> 'draft',
                                'server_type'   => 'none',
                                'is_active'     => true,
                            ]);

                            // Update Status Proposal jadi Accepted
                            $record->update(['status' => 'accepted']);

                            Notification::make()->title('Project Berhasil Dibuat!')->success()->send();

                            // Redirect ke halaman Edit Project Baru
                            return redirect()->to(ClientProjectResource::getUrl('edit', ['record' => $newProject]));
                        }),

                    Tables\Actions\DeleteAction::make(),
                ])->button()->label('Actions')
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }
}