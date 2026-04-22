<?php

namespace App\Filament\Resources\RkaResource\Pages;

use App\Filament\Resources\RkaResource;
use Filament\Resources\Pages\ViewRecord;

class ViewRka extends ViewRecord
{
    protected static string $resource = RkaResource::class;

    protected function resolveRecord($key): \Illuminate\Database\Eloquent\Model
    {
        return static::getResource()::getEloquentQuery()
            ->withTrashed()
            ->findOrFail($key);
    }
}
