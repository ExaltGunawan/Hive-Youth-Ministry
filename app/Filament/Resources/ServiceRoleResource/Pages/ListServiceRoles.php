<?php

namespace App\Filament\Resources\ServiceRoleResource\Pages;

use App\Filament\Resources\ServiceRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceRoles extends ListRecords
{
    protected static string $resource = ServiceRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
