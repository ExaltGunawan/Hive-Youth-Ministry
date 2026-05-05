<?php

namespace App\Filament\Widgets;

use App\Models\Note;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Widget as BaseWidget;

class LatestNotes extends BaseWidget
{
    use \BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

    protected static string $view = 'filament.widgets.latest-notes';
    protected static ?int $sort = 10;
    protected int | string | array $columnSpan = 1;

    protected function getViewData(): array
    {
        return [
            'notes' => Note::latest()->limit(3)->get(),
        ];
    }
}
