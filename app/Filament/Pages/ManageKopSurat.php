<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageKopSurat extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Atur Kop Surat';
    protected static string $view = 'filament.pages.manage-kop-surat';

    // Variabel-variabel ini harus public agar Livewire bisa menangkap datanya secara real-time
    public $company_logo;
    public $company_signature; 
    public $company_name;
    public $company_subtext;
    public $company_website;
    public $company_address;
    public $company_email;
    public $company_phone;

    public static function getFilePath()
    {
        return storage_path('app/company_settings.json');
    }

    public function mount(): void
    {
        // Ambil data lama dari JSON dan masukkan ke variabel class
        $settings = self::getSettings();
        $this->form->fill($settings);
        
        // Pastikan variabel signature juga terisi saat halaman pertama kali dimuat
        $this->company_signature = $settings['company_signature'] ?? null;
    }

    public static function getSettings(): array
    {
        $path = self::getFilePath();
        if (file_exists($path)) {
            $content = file_get_contents($path);
            return json_decode($content, true) ?? [];
        }
        return [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Perusahaan')
                    ->description('Gunakan Logo URL dan coret langsung tanda tangan Anda di kotak yang disediakan.')
                    ->columns(2)
                    ->schema([
                        // Input Logo via URL
                        TextInput::make('company_logo')
                            ->label('URL Logo Perusahaan')
                            ->placeholder('https://website-anda.com/logo.png')
                            ->url()
                            ->columnSpanFull(),

                        // Hidden input ini menjaga state signature agar sinkron dengan form
                        Hidden::make('company_signature'),

                        TextInput::make('company_name')
                            ->label('Nama Besar Perusahaan')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('company_subtext')
                            ->label('Sub-Judul / Slogan'),

                        TextInput::make('company_website')
                            ->label('Website')
                            ->prefix('https://'),

                        Textarea::make('company_address')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('company_email')
                            ->label('Email Resmi')
                            ->email(),

                        TextInput::make('company_phone')
                            ->label('No. Telepon / WA'),
                    ])
            ]);
    }

    public function submit(): void
    {
        // 1. Ambil data asli dari state form
        $data = $this->form->getState();

        // 2. PAKSA isi company_signature dari variabel class ($this->company_signature)
        // Ini memastikan data base64 dari canvas JS tidak bernilai NULL saat disimpan
        $data['company_signature'] = $this->company_signature;

        // 3. Simpan seluruh data ke file JSON
        file_put_contents(self::getFilePath(), json_encode($data, JSON_PRETTY_PRINT));

        Notification::make()
            ->title('Pengaturan & Tanda Tangan Berhasil Disimpan!')
            ->success()
            ->send();
    }
}