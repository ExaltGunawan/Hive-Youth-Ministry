<?php

namespace App\Filament\Resources\WithdrawalScheduleResource\Pages;

use App\Filament\Resources\WithdrawalScheduleResource;
use Filament\Resources\Pages\ViewRecord;

class ViewWithdrawalSchedule extends ViewRecord
{
    protected static string $resource = WithdrawalScheduleResource::class;

    protected function resolveRecord($key): \Illuminate\Database\Eloquent\Model
    {
        return static::getResource()::getEloquentQuery()
            ->withTrashed()
            ->findOrFail($key);
    }
}
