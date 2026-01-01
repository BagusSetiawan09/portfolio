<?php

namespace App\Filament\Resources\ClientProjectResource\Pages;

use App\Filament\Resources\ClientProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientProject extends EditRecord
{
    protected static string $resource = ClientProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
