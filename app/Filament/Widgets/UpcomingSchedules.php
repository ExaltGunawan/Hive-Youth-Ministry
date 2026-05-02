<?php

namespace App\Filament\Widgets;

use App\Models\Schedule;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingSchedules extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(
                Schedule::where('tanggal', '>=', now())
                    ->where('tanggal', '<=', now()->addMonth())
                    ->orderBy('tanggal', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('schedule_name')
                    ->label('Kegiatan'),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('jam')
                    ->label('Waktu'),
                Tables\Columns\TextColumn::make('tempat')
                    ->label('Lokasi'),
            ])
            ->recordUrl(
                fn (Schedule $record): string => \App\Filament\Resources\ScheduleResource::getUrl('view', ['record' => $record]),
            );
    }
}
