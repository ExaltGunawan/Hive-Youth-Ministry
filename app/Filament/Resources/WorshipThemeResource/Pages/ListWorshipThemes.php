<?php

namespace App\Filament\Resources\WorshipThemeResource\Pages;

use App\Filament\Resources\WorshipThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorshipThemes extends ListRecords
{
    protected static string $resource = WorshipThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
