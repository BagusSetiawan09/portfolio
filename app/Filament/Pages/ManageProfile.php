<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class ManageProfile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Edit Profile';
    protected static string $view = 'filament.pages.manage-profile';

    public ?array $data = [];

    public function fillForm(): void
    {
        $profile = Profile::firstOrCreate(
            ['id' => 1],
            ['name' => 'Your Name', 'email' => 'youremail@example.com']
        );

        $this->form->fill($profile->attributesToArray());
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Profile Tabs')
                    ->tabs([
                        // TAB 1: IDENTITY & CONTENT
                        Tab::make('Identity & Content')
                            ->icon('heroicon-m-identification')
                            ->schema([
                                // Hero Section
                                Section::make('Hero Section (Halaman Depan)')
                                    ->description('Pengaturan tampilan paling atas website.')
                                    ->schema([
                                        TextInput::make('name')->label('Nama Lengkap (Headline)')->required(),
                                        TextInput::make('role')->label('Role / Pekerjaan')->placeholder('ex: Frontend Developer'),
                                        Textarea::make('hero_description')->label('Deskripsi Hero')->rows(3)->columnSpanFull(),
                                        Repeater::make('marquee_texts')
                                            ->label('Running Text (Marquee Atas)')
                                            ->schema([TextInput::make('text')->hiddenLabel()->required()])
                                            ->defaultItems(3)->grid(3)->columnSpanFull()->collapsed(),
                                    ])->collapsed(),

                                // Stats Banner
                                Section::make('Stats Banner (Wrap Banner)')
                                    ->description('Banner hitam berisi statistik & icon berjalan.')
                                    ->schema([
                                        Repeater::make('banner_row_1')->label('Baris 1 (Gerak ke Kiri)')
                                            ->schema([
                                                Select::make('type')->options(['text' => 'Teks', 'img' => 'Gambar/Icon URL'])->default('text')->reactive()->required(),
                                                TextInput::make('value')->label('Isi Konten')->required(),
                                                Select::make('class')->options(['text_white' => 'Putih Solid', 'text-border' => 'Transparan (Outline)'])->default('text_white')->visible(fn ($get) => $get('type') === 'text'),
                                            ])->columns(3)->defaultItems(4)->collapsed(),
                                        Repeater::make('banner_row_2')->label('Baris 2 (Gerak ke Kanan)')
                                            ->schema([
                                                Select::make('type')->options(['text' => 'Teks', 'img' => 'Gambar/Icon URL'])->default('text')->reactive(),
                                                TextInput::make('value')->label('Isi Konten')->required(),
                                                Select::make('class')->options(['text_white' => 'Putih Solid', 'text-border' => 'Transparan (Outline)'])->default('text_border')->visible(fn ($get) => $get('type') === 'text'),
                                            ])->columns(3)->defaultItems(4)->collapsed(),
                                    ])->collapsed(),

                                // Media & CV
                                Section::make('Media & CV')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('avatar_url')->label('Avatar URL')->url(),
                                        TextInput::make('cv_url')->label('CV URL')->url(),
                                    ])->collapsed(),
                                
                                // Footer
                                Section::make('Footer Content Management')
                                    ->schema([
                                        Textarea::make('footer_quote')->label('Quote Footer')->rows(2),
                                        Section::make('Label Teks')->columns(2)->schema([
                                            TextInput::make('email_label'), TextInput::make('phone_label'),
                                            TextInput::make('quick_link_title'), TextInput::make('social_title'),
                                        ])->collapsed(),
                                        Repeater::make('footer_links')->schema([TextInput::make('label'), TextInput::make('url')])->columns(2),
                                        TextInput::make('copyright_text'),
                                    ])->collapsed(),
                            ]),

                        // TAB 2: ABOUT ME
                        Tab::make('About Me')
                            ->icon('heroicon-m-user')
                            ->schema([
                                Section::make('Konten About')
                                    ->schema([
                                        Textarea::make('bio_summary')->label('Headline Besar About')->rows(3),
                                        RichEditor::make('bio_details')->label('Deskripsi Detail')->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                    ]),
                            ]),

                        // TAB 3: CONTACTS
                        Tab::make('Contacts')
                            ->icon('heroicon-m-share')
                            ->schema([
                                Section::make('Contact Info')->columns(2)->schema([
                                    TextInput::make('email')->email(), TextInput::make('whatsapp')->label('WhatsApp'),
                                ]),
                                Section::make('Social Media')->columns(3)->schema([
                                    TextInput::make('linkedin'), TextInput::make('github'), TextInput::make('instagram'),
                                ]),
                            ]),

                        // TAB 4: STATUS
                        Tab::make('Status')
                            ->icon('heroicon-m-signal')
                            ->schema([
                                Toggle::make('is_available')->label('Available for Work?')->onColor('success')->offColor('danger'),
                            ]),
                    ])->columnSpanFull(),

                Placeholder::make('spacer')
                    ->hiddenLabel()
                    ->content(new HtmlString('<div style="height: 1px;">&nbsp;</div>'))
                    ->columnSpanFull(),
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
                ->modalHeading('Reset Formulir?')
                ->modalDescription('Apakah Anda yakin ingin mengembalikan data seperti semula? Perubahan yang belum disimpan akan hilang.')
                ->action(function () {
                    $this->fillForm();
                    Notification::make()->title('Form Reset')->info()->send();
                }),
        ];
    }

    public function save(): void
    {
        $profile = Profile::first();
        $profile->update($this->form->getState());

        Notification::make()->title('Profile Updated')->success()->send();
    }
}