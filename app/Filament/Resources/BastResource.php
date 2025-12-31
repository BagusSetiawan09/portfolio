<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BastResource\Pages;
use App\Models\Bast;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// --- IMPORT LAIN ---
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Group;
use Filament\Support\Enums\FontWeight;

class BastResource extends Resource
{
    protected static ?string $model = Bast::class;
    protected static ?string $modelLabel = 'BAST';
    protected static ?string $pluralModelLabel = 'BAST';
    
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Documents';
    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'number';

    // --- TEMPLATE BAST (HTML) ---
    public static function getDefaultContent($clientName = '[NAMA KLIEN]', $projectTitle = '[JUDUL PROJEK]', $bastNumber = '[AUTO]')
    {
        $date = now()->locale('id')->isoFormat('dddd, D MMMM Y');
        
        // HTML ini disesuaikan agar rapi di dalam wrapper 'prose'
        return <<<HTML
            <div style="text-align: center; margin-bottom: 2rem;">
                <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase; font-weight: bold; font-size: 1.25em;">BERITA ACARA SERAH TERIMA PEKERJAAN</h3>
                <p style="margin: 0;">NOMOR: {$bastNumber}</p>
            </div>

            <p>Pada hari ini, <strong>{$date}</strong>, kami yang bertanda tangan di bawah ini:</p>

            <table style="width: 100%; margin-bottom: 1rem; border: none;">
                <tr>
                    <td style="width: 20px; vertical-align: top; padding: 2px;">1.</td>
                    <td style="width: 100px; vertical-align: top; padding: 2px;">Nama</td>
                    <td style="vertical-align: top; padding: 2px;">: <strong>Bagus Setiawan</strong></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="vertical-align: top; padding: 2px;">Jabatan</td>
                    <td style="vertical-align: top; padding: 2px;">: Freelance Fullstack Developer</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" style="padding: 2px;">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.</td>
                </tr>
            </table>

            <table style="width: 100%; margin-bottom: 2rem; border: none;">
                <tr>
                    <td style="width: 20px; vertical-align: top; padding: 2px;">2.</td>
                    <td style="width: 100px; vertical-align: top; padding: 2px;">Nama</td>
                    <td style="vertical-align: top; padding: 2px;">: <strong>{$clientName}</strong></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="vertical-align: top; padding: 2px;">Jabatan</td>
                    <td style="vertical-align: top; padding: 2px;">: Owner / Penanggung Jawab</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" style="padding: 2px;">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.</td>
                </tr>
            </table>

            <p>Selanjutnya secara bersama-sama PIHAK PERTAMA dan PIHAK KEDUA dalam hal ini disebut <strong>PARA PIHAK</strong>.</p>

            <p>PARA PIHAK sepakat melaksanakan serah terima pekerjaan <strong>Development Website ({$projectTitle})</strong> dengan ketentuan sebagai berikut:</p>

            <div style="text-align: center; font-weight: bold; margin-top: 1.5rem; margin-bottom: 0.5rem;">Pasal 1<br>Serah Terima Pekerjaan</div>
            <p>PIHAK PERTAMA menyerahkan kepada PIHAK KEDUA, dan PIHAK KEDUA menerima penyerahan dari PIHAK PERTAMA berupa hasil pekerjaan Website dengan rincian akses dan file sebagai berikut:</p>
            <ul>
                <li>Source Code Website (Full)</li>
                <li>Akses Login Administrator (Dashboard)</li>
                <li>Akses Hosting / CPanel / Server (Jika ada)</li>
                <li>Dokumentasi Penggunaan / Manual</li>
            </ul>
            <p><em>(Rincian kredensial lengkap terlampir terpisah demi keamanan).</em></p>

            <div style="text-align: center; font-weight: bold; margin-top: 1.5rem; margin-bottom: 0.5rem;">Pasal 2<br>Garansi dan Pemeliharaan</div>
            <ol>
                <li>Sejak penandatanganan Berita Acara ini, maka seluruh tanggung jawab pengelolaan konten dan operasional website berpindah dari PIHAK PERTAMA kepada PIHAK KEDUA.</li>
                <li>PIHAK PERTAMA memberikan masa <strong>Garansi (Maintenance) selama 30 (Tiga Puluh) Hari</strong> kalender terhitung sejak tanggal surat ini diterbitkan.</li>
                <li>Garansi meliputi perbaikan <em>bug</em> atau <em>error</em> teknis. Penambahan fitur baru di luar kesepakatan awal akan dikenakan biaya tambahan.</li>
            </ol>

            <div style="text-align: center; font-weight: bold; margin-top: 1.5rem; margin-bottom: 0.5rem;">Pasal 3<br>Penutup</div>
            <p>Demikian Berita Acara Serah Terima ini dibuat dengan sebenarnya dalam rangkap secukupnya untuk dipergunakan sebagaimana mestinya.</p>

            <br>
            <table style="width: 100%; text-align: center; margin-top: 2rem; border: none;">
                <tr>
                    <td style="width: 50%; padding: 2px;">PIHAK KEDUA</td>
                    <td style="width: 50%; padding: 2px;">PIHAK PERTAMA</td>
                </tr>
                <tr>
                    <td style="height: 80px;"></td> 
                    <td style="height: 80px;"></td>
                </tr>
                <tr>
                    <td style="padding: 2px;"><strong>( {$clientName} )</strong></td>
                    <td style="padding: 2px;"><strong>( Bagus Setiawan )</strong></td>
                </tr>
            </table>
        HTML;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Metadata BAST')
                ->columns(12)
                ->schema([
                    // --- NOMOR OTOMATIS ---
                    Forms\Components\TextInput::make('number')
                        ->label('Nomor BAST')
                        ->default(function () {
                            $year = now()->format('Y');
                            $count = Bast::whereYear('created_at', $year)->count() + 1;
                            return 'BAST/' . $year . '/' . str_pad($count, 3, '0', STR_PAD_LEFT);
                        })
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100)
                        ->columnSpan(6),

                    Forms\Components\DatePicker::make('handover_date')
                        ->label('Tanggal Serah Terima')
                        ->required()
                        ->default(now())
                        ->columnSpan(6),

                    // PILIH PROJECT (AUTO GENERATE TEMPLATE)
                    Forms\Components\Select::make('project_id')
                        ->label('Pilih Project')
                        ->relationship('project', 'title')
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $project = Project::find($state);
                            if ($project) {
                                $set('project_title', $project->title);
                                $set('client_name', 'Klien ' . $project->title);
                                $currentNumber = $get('number') ?? '[AUTO]';
                                $template = self::getDefaultContent($get('client_name'), $project->title, $currentNumber);
                                $set('content', $template);
                            }
                        })
                        ->columnSpan(12)
                        ->helperText('Memilih project akan mereset isi Editor BAST di bawah.'),

                    Forms\Components\TextInput::make('client_name')
                        ->label('Nama Klien')
                        ->required()
                        ->maxLength(180)
                        ->columnSpan(6),

                    Forms\Components\TextInput::make('project_title')
                        ->label('Judul Project')
                        ->required()
                        ->maxLength(180)
                        ->columnSpan(6),

                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'sent' => 'Sent (Dikirim)',
                            'signed' => 'Signed (Selesai)',
                        ])
                        ->default('draft')
                        ->required()
                        ->columnSpan(12),
                ]),

            Forms\Components\Section::make('Rincian Teknis')
                ->schema([
                    Forms\Components\RichEditor::make('items_list')
                        ->label('Detail Credential / Link (Internal Record)')
                        ->helperText('Isi dengan link repo, password admin, dll.')
                        ->required() 
                        ->default("<ul><li>URL: ...</li><li>Admin User: ...</li><li>Admin Pass: ...</li></ul>")
                        ->toolbarButtons(['bulletList', 'orderedList', 'bold', 'italic'])
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Isi Dokumen (Surat)')
                ->schema([
                    Forms\Components\RichEditor::make('content')
                        ->label('Preview Surat BAST')
                        ->default(fn () => self::getDefaultContent())
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Arsip Dokumen')
                ->schema([
                    Forms\Components\FileUpload::make('file_path')
                        ->label('Upload Scan BAST (PDF)')
                        ->directory('basts')
                        ->acceptedFileTypes(['application/pdf'])
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan Tambahan')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction('view')
            ->defaultSort('handover_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('No. BAST')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),

                Tables\Columns\TextColumn::make('client_name')
                    ->label('Klien')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('project_title')
                    ->label('Project')
                    ->searchable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('handover_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'signed' => 'success',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('download_pdf')
                        ->label('Cetak / Download PDF')
                        ->icon('heroicon-m-printer')
                        ->color('success')
                        ->url(fn (Bast $record) => route('bast.print', $record)) 
                        ->openUrlInNewTab(),

                    Tables\Actions\ViewAction::make()
                        ->label('Detail')
                        ->color('info')
                        ->slideOver(),

                    Tables\Actions\EditAction::make()
                        ->color('warning'),

                    Tables\Actions\DeleteAction::make(),
                ])
                ->button()
                ->label('Actions')
                ->color('gray')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // --- BAGIAN KUNCI: SUKSES SEPERTI CONTRACT RESOURCE ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // HEADER
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make(1)->schema([
                                TextEntry::make('number')->weight(FontWeight::Bold),
                                TextEntry::make('project_title')->label('Project'),
                            ]),
                            Group::make([
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn ($state) => $state === 'signed' ? 'success' : 'gray'),
                            ])->grow(false),
                        ])->from('md')
                    ]),

                // RINCIAN INTERNAL
                Section::make('Rincian Internal')
                    ->schema([
                         TextEntry::make('items_list')
                            ->label('Detail Credential')
                            ->markdown()
                            ->prose(),
                    ]),

                // === TAMPILAN VIEW BAST YANG RAPI ===
                Section::make('Isi BAST (Surat)')
                    ->schema([
                         TextEntry::make('content')
                            ->hiddenLabel()
                            ->html()
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => <<<HTML
                                <div class="bg-white text-black p-10 md:p-14 rounded-xl shadow-lg border border-gray-300 w-full max-w-4xl">
                                    
                                    <style>
                                        .bast-view table, .bast-view th, .bast-view td {
                                            border: none !important;
                                            padding: 4px 0 !important;
                                        }
                                        .bast-view ul, .bast-view ol {
                                            margin-top: 0.5rem !important;
                                            margin-bottom: 0.5rem !important;
                                        }
                                    </style>

                                    <div class="prose max-w-none text-black bast-view font-serif">
                                        {$state}
                                    </div>
                                </div>
                            HTML),
                    ]),

                // ARSIP FINAL
                Section::make('Arsip Final')
                    ->schema([
                        TextEntry::make('file_path')
                            ->label('File Scan')
                            ->formatStateUsing(fn ($state) => $state ? 'Download' : '-')
                            ->url(fn ($record) => $record->file_path ? asset('storage/' . $record->file_path) : null, true)
                            ->color('primary'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBasts::route('/'),
            'create' => Pages\CreateBast::route('/create'),
            'edit' => Pages\EditBast::route('/{record}/edit'),
        ];
    }
}