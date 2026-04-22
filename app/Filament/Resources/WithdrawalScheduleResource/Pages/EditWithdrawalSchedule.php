<?php

namespace App\Filament\Resources\WithdrawalScheduleResource\Pages;

use App\Filament\Resources\WithdrawalScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawalSchedule extends EditRecord
{
    protected static string $resource = WithdrawalScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
