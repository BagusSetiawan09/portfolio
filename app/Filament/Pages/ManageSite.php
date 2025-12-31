<?php

namespace App\Filament\Pages;

use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload; 

class ManageSite extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth'; // Icon Gear
    protected static ?string $navigationGroup = 'Settings'; // Masuk grup Settings
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $title = 'Global Site Settings';
    protected static string $view = 'filament.pages.manage-site';
    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function fillForm(): void
    {
        // Buat data default jika belum ada (ID=1)
        $site = Site::firstOrCreate(
            ['id' => 1],
            ['site_name' => 'My Portfolio']
        );

        $this->form->fill($site->attributesToArray());
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Site Tabs')
                    ->tabs([
                        // TAB 1: BRANDING & SEO
                        Tab::make('General & SEO')
                            ->icon('heroicon-m-globe-alt')
                            ->schema([
                                Section::make('Identitas Website')
                                    ->description('Pengaturan dasar yang muncul di tab browser & hasil pencarian Google.')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Nama Website (Meta Title)')
                                            ->required()
                                            ->placeholder('Contoh: Bagus Setiawan - Frontend Developer'),
                                        
                                        Textarea::make('site_description')
                                            ->label('Deskripsi SEO (Meta Description)')
                                            ->rows(3)
                                            ->placeholder('Deskripsi singkat yang muncul di bawah judul saat dicari di Google...')
                                            ->columnSpanFull(),

                                        TextInput::make('keywords')
                                            ->label('Kata Kunci (Keywords)')
                                            ->placeholder('portfolio, web developer, laravel, frontend')
                                            ->helperText('Pisahkan dengan koma (,).')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Aset Gambar (URL)')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('favicon_url')
                                            ->label('Favicon URL (Icon Tab)')
                                            ->url()
                                            ->placeholder('https://imgur.com/icon.png')
                                            ->helperText('Icon kecil di tab browser (Format: PNG/ICO).'),

                                        TextInput::make('logo_url')
                                            ->label('Logo Website URL')
                                            ->url()
                                            ->placeholder('https://imgur.com/logo.png')
                                            ->helperText('Logo utama website (jika template mendukung).'),
                                    ]),
                            ]),

                        // TAB 2: SOCIAL SHARE (OPEN GRAPH)
                        Tab::make('Social Share')
                            ->icon('heroicon-m-share')
                            ->schema([
                                Section::make('Tampilan Link (WhatsApp / LinkedIn)')
                                    ->description('Atur bagaimana link website Anda terlihat saat dibagikan ke sosmed.')
                                    ->schema([
                                        TextInput::make('og_title')
                                            ->label('Judul Share')
                                            ->placeholder('Sama dengan Site Name atau custom.')
                                            ->helperText('Jika kosong, akan menggunakan Site Name.'),
                                        
                                        Textarea::make('og_description')
                                            ->label('Deskripsi Share')
                                            ->rows(2)
                                            ->placeholder('Ringkasan singkat untuk preview link.')
                                            ->columnSpanFull(),

                                        TextInput::make('og_image_url')
                                            ->label('Thumbnail Share URL (Gambar)')
                                            ->url()
                                            ->placeholder('https://imgur.com/preview.jpg')
                                            ->helperText('Gambar besar yang muncul di chat WA/Twitter. (Rekomendasi: 1200x630 px).')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),

            Action::make('reset')
                ->label('Reset Changes')
                ->color('gray')
                ->icon('heroicon-m-arrow-path')
                ->requiresConfirmation()
                ->action(function () {
                    $this->fillForm();
                    Notification::make()->title('Form Reset')->info()->send();
                }),
        ];
    }

    public function save(): void
    {
        $site = Site::first();
        $site->update($this->form->getState());

        Notification::make()->title('Settings Updated')->success()->send();
    }
}