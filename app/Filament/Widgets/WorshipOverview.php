<?php

namespace App\Filament\Widgets;

use App\Models\WorshipTheme;
use App\Models\WorshipTitle;
use Filament\Widgets\Widget;

class WorshipOverview extends Widget
{
    protected static string $view = 'filament.widgets.worship-overview';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 1;

    protected function getViewData(): array
    {
        $theme = WorshipTheme::whereMonth('month', now()->month)
            ->whereYear('month', now()->year)
            ->first();

        return [
            'theme' => $theme,
        ];
    }
}
