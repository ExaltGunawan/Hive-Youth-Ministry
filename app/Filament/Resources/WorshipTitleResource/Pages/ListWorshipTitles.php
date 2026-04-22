<?php

namespace App\Filament\Resources\WorshipTitleResource\Pages;

use App\Filament\Resources\WorshipTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorshipTitles extends ListRecords
{
    protected static string $resource = WorshipTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
