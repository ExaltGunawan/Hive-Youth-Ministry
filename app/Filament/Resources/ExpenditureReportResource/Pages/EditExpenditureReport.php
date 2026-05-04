<?php

namespace App\Filament\Resources\ExpenditureReportResource\Pages;

use App\Filament\Resources\ExpenditureReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpenditureReport extends EditRecord
{
    protected static string $resource = ExpenditureReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
