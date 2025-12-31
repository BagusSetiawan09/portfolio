<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterResource\Pages;
use App\Models\Letter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Group;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;
use App\Mail\LetterEmail;
use Filament\Notifications\Notification;

class LetterResource extends Resource
{
    protected static ?string $model = Letter::class;
    protected static ?string $modelLabel = 'Surat Keluar';
    protected static ?string $pluralModelLabel = 'Arsip Surat';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Documents';
    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'number';

    // --- TEMPLATE GENERATOR ---
    public static function getTemplate($type, $data = [])
    {
        $date = now()->locale('id')->isoFormat('D MMMM Y');
        $recipient = $data['recipient_name'] ?? '...';

        switch ($type) {
            case 'penawaran':
                return <<<HTML
                <p>Kepada Yth,<br><strong>{$recipient}</strong><br>Di Tempat</p>
                <p>Dengan hormat,</p>
                <p>Perkenalkan kami dari <strong>CODEXLY</strong> yang bergerak di bidang Jasa Pengembangan Website dan Aplikasi. Bermaksud mengajukan penawaran kerjasama pembuatan website untuk instansi/perusahaan Bapak/Ibu.</p>
                <p>Adapun rincian paket yang kami tawarkan adalah sebagai berikut:</p>
                <ul>
                    <li>Pembuatan Website Company Profile</li>
                    <li>Gratis Domain & Hosting 1 Tahun</li>
                    <li>Maintenance & Support Bulanan</li>
                </ul>
                <p>Besar harapan kami agar penawaran ini dapat diterima. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
                HTML;

            case 'tugas':
                return <<<HTML
                <div style="text-align: center; margin-bottom: 20px;">
                    <h3 style="text-decoration: underline; margin: 0;">SURAT TUGAS</h3>
                </div>
                <p>Saya yang bertanda tangan di bawah ini:</p>
                <table style="width: 100%; border: none;">
                    <tr><td style="width: 100px;">Nama</td><td>: <strong>Bagus Setiawan</strong></td></tr>
                    <tr><td>Jabatan</td><td>: Fullstack Developer</td></tr>
                </table>
                <p>Memberikan tugas kepada:</p>
                <table style="width: 100%; border: none;">
                    <tr><td style="width: 100px;">Nama</td><td>: <strong>{$recipient}</strong></td></tr>
                    <tr><td>Posisi</td><td>: Staff IT Support</td></tr>
                </table>
                <p>Untuk melaksanakan pekerjaan maintenance server dan update sistem di lokasi klien pada tanggal <strong>{$date}</strong>.</p>
                <p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>
                HTML;

            case 'tagihan':
                return <<<HTML
                <p>Kepada Yth,<br><strong>{$recipient}</strong><br>Di Tempat</p>
                <p>Dengan hormat,</p>
                <p>Bersama surat ini kami sampaikan tagihan (Invoice) untuk pekerjaan Development Website yang telah diselesaikan. Mohon untuk melakukan pembayaran sebelum tanggal jatuh tempo.</p>
                <p>Adapun rincian pembayaran dapat ditransfer ke rekening:</p>
                <p><strong>BCA : 1234567890 a.n Bagus Setiawan</strong></p>
                <p>Jika sudah melakukan pembayaran, mohon konfirmasi kembali kepada kami.</p>
                <p>Terima kasih atas kerjasamanya.</p>
                HTML;

            default:
                return "<p>Isi surat belum diatur.</p>";
        }
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Metadata Surat')
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('number')
                        ->label('Nomor Surat')
                        ->default(function () {
                            $year = now()->format('Y');
                            $count = Letter::whereYear('created_at', $year)->count() + 1;
                            return 'SRT/' . $year . '/' . str_pad($count, 3, '0', STR_PAD_LEFT);
                        })
                        ->disabled()->dehydrated()->required()->unique(ignoreRecord: true)->columnSpan(4),

                    Forms\Components\DatePicker::make('letter_date')
                        ->label('Tanggal Surat')->required()->default(now())->columnSpan(4),

                    Forms\Components\Select::make('status')
                        ->options(['draft' => 'Draft', 'sent' => 'Sent (Dikirim)', 'archived' => 'Arsip'])
                        ->default('draft')->required()->columnSpan(4),

                    Forms\Components\TextInput::make('recipient_name')
                        ->label('Nama Penerima')->required()->maxLength(180)->live(onBlur: true)->columnSpan(4),

                    Forms\Components\TextInput::make('client_email')
                        ->label('Email Penerima (Untuk Kirim Email)')
                        ->email()->required()->columnSpan(4),

                    Forms\Components\TextInput::make('recipient_company')
                        ->label('Instansi / Perusahaan')->maxLength(180)->columnSpan(4),

                    Forms\Components\Textarea::make('recipient_address')
                        ->label('Alamat Penerima')->rows(2)->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Isi Dokumen')
                ->schema([
                    Forms\Components\Grid::make(12)->schema([
                        Forms\Components\Select::make('type')
                            ->label('Jenis Template')
                            ->options([
                                'general' => 'Surat Umum (Kosong)',
                                'penawaran' => 'Surat Penawaran Jasa',
                                'tugas' => 'Surat Tugas',
                                'tagihan' => 'Surat Tagihan (Invoice Reminder)',
                            ])
                            ->default('general')->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state !== 'general') {
                                    $data = ['recipient_name' => $get('recipient_name')];
                                    $set('content', self::getTemplate($state, $data));
                                    $subjects = [
                                        'penawaran' => 'Penawaran Kerjasama Jasa Website',
                                        'tugas' => 'Surat Tugas Lapangan',
                                        'tagihan' => 'Pemberitahuan Tagihan Pembayaran',
                                    ];
                                    if (isset($subjects[$state])) {
                                        $set('subject', $subjects[$state]);
                                    }
                                }
                            })->columnSpan(4),

                        Forms\Components\TextInput::make('subject')
                            ->label('Perihal / Subject')->required()->columnSpan(8),
                    ]),

                    Forms\Components\RichEditor::make('content')
                        ->label('Editor Surat')
                        ->toolbarButtons(['bold', 'italic', 'underline', 'bulletList', 'orderedList', 'undo', 'redo'])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)->recordAction('view')->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('number')->label('No. Surat')->searchable()->weight('bold')->sortable(),
                Tables\Columns\TextColumn::make('letter_date')->label('Tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('subject')->label('Perihal')->limit(30)->searchable(),
                Tables\Columns\TextColumn::make('recipient_name')->label('Penerima')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'success',
                        'archived' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    // ACTION KIRIM EMAIL
                    Action::make('sendEmail')
                        ->label('Kirim Email')
                        ->icon('heroicon-m-envelope')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->action(function (Letter $record) {
                            if (blank($record->client_email)) {
                                Notification::make()
                                    ->title('Email penerima belum diisi.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            try {
                                // ✅ Langsung kirim (tanpa deadlock karena PDF sudah tidak pakai route())
                                Mail::to($record->client_email)->send(new LetterEmail($record));

                                $record->update(['status' => 'sent']);

                                Notification::make()
                                    ->title('Email Berhasil Dikirim!')
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                report($e);

                                Notification::make()
                                    ->title('Gagal mengirim email')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    // ACTION CETAK PDF
                    Tables\Actions\Action::make('print')
                        ->label('Cetak PDF')
                        ->icon('heroicon-m-printer')
                        ->color('success')
                        ->url(fn (Letter $record) => route('letter.print', $record))
                        ->openUrlInNewTab(),

                    Tables\Actions\ViewAction::make()->color('info'),
                    Tables\Actions\EditAction::make()->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                ])->button()->label('Aksi Surat')->color('gray')
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make(1)->schema([
                                TextEntry::make('number')->weight(FontWeight::Bold),
                                TextEntry::make('subject')->label('Perihal'),
                            ]),
                            Group::make([
                                TextEntry::make('status')->badge(),
                                TextEntry::make('letter_date')->date('d M Y'),
                            ])->grow(false),
                        ])->from('md')
                    ]),

                Section::make('Preview Dokumen')
                    ->schema([
                        TextEntry::make('content')
                            ->hiddenLabel()->columnSpanFull()->html()
                            ->formatStateUsing(function ($state, $record) {
                                $settings = \App\Filament\Pages\ManageKopSurat::getSettings();
                                $logoUrl   = $settings['company_logo'] ?? null;
                                $signUrl   = $settings['company_signature'] ?? null;
                                $compName  = $settings['company_name'] ?? 'CODEXLY';
                                $compSub   = $settings['company_subtext'] ?? '';
                                $compAddr  = $settings['company_address'] ?? '';
                                $compEmail = $settings['company_email'] ?? '';
                                $compPhone = $settings['company_phone'] ?? '';
                                $formattedDate = $record->letter_date->locale('id')->isoFormat('D MMMM Y');

                                $logoHtml = $logoUrl ? "<img src='{$logoUrl}' style='height: 70px;'>" : "";
                                $signHtml = $signUrl
                                    ? "<img src='{$signUrl}' style='height: 80px;'>"
                                    : "<div style='height: 80px;'></div>";

                                return <<<HTML
                                <div style="background: white; color: black; padding: 30px; border: 1px solid #ddd; font-family: 'Times New Roman';">
                                    <div style="border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px;">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 60%;">{$logoHtml}<br><strong>{$compName}</strong><br><small>{$compSub}</small></td>
                                                <td style="text-align: right; font-size: 9pt;">{$compPhone}<br>{$compEmail}<br>{$compAddr}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <p>Nomor: {$record->number}<br>Perihal: {$record->subject}</p>
                                    <div style="margin: 20px 0; min-height: 150px;">{$state}</div>
                                    <div>Hormat Kami,<br>{$signHtml}<strong>Bagus Setiawan</strong></div>
                                </div>
                                HTML;
                            }),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLetters::route('/'),
            'create' => Pages\CreateLetter::route('/create'),
            'edit' => Pages\EditLetter::route('/{record}/edit'),
        ];
    }
}
