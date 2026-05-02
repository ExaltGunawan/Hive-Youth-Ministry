<?php

namespace App\Filament\Widgets;

use App\Models\WorshipTitle;
use Filament\Widgets\Widget;

class WorshipTitles extends Widget
{
    protected static string $view = 'filament.widgets.worship-titles';
    protected static ?int $sort = 3;
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
