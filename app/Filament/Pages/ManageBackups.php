<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ManageBackups extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'System Backup';
    protected static ?string $title = 'Backup Database & Files';
    protected static ?string $navigationGroup = 'Settings';

    protected static string $view = 'filament.pages.manage-backups';

    public function getBackups()
    {
        // Ambil semua file di local storage
        $files = Storage::disk('local')->allFiles(); 
        
        $backups = [];
        foreach ($files as $file) {
            // Hanya ambil file .zip
            if (str_ends_with($file, '.zip')) {
                $backups[] = [
                    'path' => $file,
                    'name' => basename($file),
                    'size' => $this->formatSize(Storage::disk('local')->size($file)),
                    'date' => date('d M Y, H:i:s', Storage::disk('local')->lastModified($file)),
                    'timestamp' => Storage::disk('local')->lastModified($file),
                ];
            }
        }
        
        // Urutkan file terbaru paling atas
        return collect($backups)->sortByDesc('timestamp')->values()->all();
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' bytes';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_backup')
                ->label('Create Backup Now')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->action(function () {
                    
                    // 1. Beritahu user proses sedang berjalan
                    Notification::make()
                        ->title('Sedang memproses backup...')
                        ->body('Mohon tunggu sebentar, jangan tutup halaman ini.')
                        ->info()
                        ->send();
                    
                    // 2. JALANKAN BACKUP MENGGUNAKAN 'EXEC' (Solusi Anti-Gagal)
                    // Ini meniru cara kerja terminal Anda yang tadi berhasil.
                    
                    // Masuk ke folder project, lalu jalankan perintah backup
                    // Tanda kutip "" pada base_path() menjaga agar aman jika ada spasi di nama folder
                    $command = 'cd "' . base_path() . '" && php artisan backup:run --only-db --disable-notifications';
                    
                    $output = [];
                    $returnVar = 0;

                    // Eksekusi perintah
                    exec($command, $output, $returnVar);

                    // 3. Cek Hasilnya (0 = Sukses, Angka lain = Gagal)
                    if ($returnVar === 0) {
                        Notification::make()
                            ->title('Backup Berhasil Disimpan!')
                            ->success()
                            ->send();
                            
                        // Refresh halaman agar tabel terupdate
                        return redirect()->to(static::getUrl());
                    } else {
                        // 4. Jika Gagal, Tampilkan Pesan Error dari Terminal
                        Notification::make()
                            ->title('Gagal Melakukan Backup')
                            ->body('Error Terminal: ' . implode("\n", $output))
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                })
        ];
    }

    public function downloadBackup($path)
    {
        if (!Storage::disk('local')->exists($path)) {
            Notification::make()->title('File fisik tidak ditemukan')->danger()->send();
            return;
        }
        return Storage::disk('local')->download($path);
    }
    
    public function deleteBackup($path)
    {
        Storage::disk('local')->delete($path);
        Notification::make()->title('File dihapus')->success()->send();
        return redirect()->to(static::getUrl());
    }
}