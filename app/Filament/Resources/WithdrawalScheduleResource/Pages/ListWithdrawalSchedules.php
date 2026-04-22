<?php

namespace App\Filament\Resources\WithdrawalScheduleResource\Pages;

use App\Filament\Resources\WithdrawalScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWithdrawalSchedules extends ListRecords
{
    protected static string $resource = WithdrawalScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
