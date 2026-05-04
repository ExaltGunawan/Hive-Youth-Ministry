<?php

namespace App\Filament\Widgets;

use App\Models\Schedule;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingSchedules extends BaseWidget
{
    protected static ?int $sort = 11;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(
                Schedule::where('tanggal', '>=', now())
                    ->where('tanggal', '<=', now()->addMonth())
                    ->orderBy('tanggal', 'asc')
                    ->limit(3)
            )
            ->columns([
                Tables\Columns\TextColumn::make('schedule_name')
                    ->label('Kegiatan')
                    ->limit(15),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tgl')
                    ->date('d/m'),
            ])
            ->recordUrl(
                fn (Schedule $record): string => \App\Filament\Resources\ScheduleResource::getUrl('view', ['record' => $record]),
            );
    }
}
