<?php

namespace App\Filament\Resources\ServiceRoleResource\Pages;

use App\Filament\Resources\ServiceRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceRole extends EditRecord
{
    protected static string $resource = ServiceRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
