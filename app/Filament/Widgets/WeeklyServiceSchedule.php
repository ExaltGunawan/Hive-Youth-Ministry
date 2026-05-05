<?php

namespace App\Filament\Widgets;

use App\Models\ServiceSchedule;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class WeeklyServiceSchedule extends Widget
{
    use \BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

    protected static string $view = 'filament.widgets.weekly-service-schedule';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    protected function getViewData(): array
    {
        // Get the nearest upcoming service schedule (this week or next)
        $schedule = ServiceSchedule::with(['assignments.member', 'assignments.serviceRole', 'worshipTitle'])
            ->where('tanggal', '>=', now()->startOfDay())
            ->orderBy('tanggal', 'asc')
            ->first();

        return [
            'schedule' => $schedule,
        ];
    }
}
