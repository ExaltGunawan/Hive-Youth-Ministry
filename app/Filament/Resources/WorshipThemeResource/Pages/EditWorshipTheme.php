<?php

namespace App\Filament\Resources\WorshipThemeResource\Pages;

use App\Filament\Resources\WorshipThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorshipTheme extends EditRecord
{
    protected static string $resource = WorshipThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
