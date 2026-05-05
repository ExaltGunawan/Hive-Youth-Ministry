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
                Schedule::with('divisi')
                    ->where('tanggal', '>=', now()->startOfDay())
                    ->where('tanggal', '<=', now()->addMonth())
                    ->orderBy('tanggal', 'asc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tgl')
                    ->date('d/m')
                    ->description(fn (Schedule $record): string => \Carbon\Carbon::parse($record->jam)->format('H:i'))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('schedule_name')
                    ->label('Kegiatan')
                    ->description(fn (Schedule $record): string => ($record->tempat ?? '-') . ($record->sub_tempat ? " • {$record->sub_tempat}" : ""))
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('divisi.nama_divisi')
                    ->label('Divisi')
                    ->badge()
                    ->color(fn (Schedule $record): string => 'gray')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->extraAttributes(function (Schedule $record) {
                        $color = $record->divisi->color ?? '#3b82f6';
                        return [
                            'style' => "background-color: {$color}22 !important; color: {$color} !important; border: 1px solid {$color}44 !important; font-size: 10px !important; font-weight: 800 !important;",
                        ];
                    }),
            ])
            ->recordUrl(
                fn (Schedule $record): string => \App\Filament\Resources\ScheduleResource::getUrl('view', ['record' => $record]),
            );
    }
}
