<?php

namespace App\Filament\Resources\BastResource\Pages;

use App\Filament\Resources\BastResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBasts extends ListRecords
{
    protected static string $resource = BastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
