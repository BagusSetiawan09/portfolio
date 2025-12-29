<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditContract extends EditRecord
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate_template')
                ->label('Generate Template')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->action(function () {
                    $contract = $this->record;

                    $contract->refresh();
                    $contract->content = $contract->defaultTemplateHtml();
                    $contract->save();

                    $this->fillForm();
                    
                    Notification::make()
                        ->title('Berhasil')
                        ->body('Template kontrak berhasil dibuat.')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('download_pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $contract = $this->record;
                    $contract->refresh();

                    $pdf = Pdf::loadView('contracts.pdf', [
                        'contract' => $contract,
                    ])
                    ->setPaper('a4')
                    ->setOption('isRemoteEnabled', true)
                    ->setOption('isHtml5ParserEnabled', true);

                    $filename = ($contract->number ?: ('contract-' . $contract->id)) . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $filename
                    );
                }),

            Actions\DeleteAction::make(),
        ];
    }
}
