<?php

namespace App\Filament\Resources\WorshipTitleResource\Pages;

use App\Filament\Resources\WorshipTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWorshipTitle extends CreateRecord
{
    protected static string $resource = WorshipTitleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
