<?php

namespace App\Filament\Resources\WorshipThemeResource\Pages;

use App\Filament\Resources\WorshipThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWorshipTheme extends CreateRecord
{
    protected static string $resource = WorshipThemeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
