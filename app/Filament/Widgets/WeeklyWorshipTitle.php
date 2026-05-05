<?php

namespace App\Filament\Widgets;

use App\Models\WorshipTitle;
use Filament\Widgets\Widget;

class WeeklyWorshipTitle extends Widget
{
    use \BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

    protected static string $view = 'filament.widgets.worship-titles';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    protected function getViewData(): array
    {
        $titles = WorshipTitle::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->orderBy('date', 'asc')
            ->get();

        return [
            'titles' => $titles,
        ];
    }
}
