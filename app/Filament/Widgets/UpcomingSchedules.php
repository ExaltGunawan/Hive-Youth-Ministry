<?php

namespace App\Filament\Widgets;

use App\Models\Schedule;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Widget as BaseWidget;

class UpcomingSchedules extends BaseWidget
{
    use \BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

    protected static string $view = 'filament.widgets.upcoming-schedules';
    protected static ?int $sort = 11;
    protected int | string | array $columnSpan = 1;

    protected function getViewData(): array
    {
        $schedules = Schedule::with('divisi')
            ->where('tanggal', '>=', now()->startOfDay())
            ->where('tanggal', '<=', now()->addMonth())
            ->orderBy('tanggal', 'asc')
            ->limit(5)
            ->get();

        return [
            'schedules' => $schedules,
        ];
    }
}
