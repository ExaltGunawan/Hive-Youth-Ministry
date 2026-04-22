<?php

namespace App\Filament\Resources\RkaResource\Pages;

use App\Filament\Resources\RkaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRkas extends ListRecords
{
    protected static string $resource = RkaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
