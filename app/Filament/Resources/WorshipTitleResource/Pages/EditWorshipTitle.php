<?php

namespace App\Filament\Resources\WorshipTitleResource\Pages;

use App\Filament\Resources\WorshipTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorshipTitle extends EditRecord
{
    protected static string $resource = WorshipTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
