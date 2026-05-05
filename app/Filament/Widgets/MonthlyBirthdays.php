<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Widget as BaseWidget;
use Illuminate\Support\Facades\DB;

class MonthlyBirthdays extends BaseWidget
{
    use \BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

    protected static string $view = 'filament.widgets.monthly-birthdays';
    protected static ?int $sort = 13;
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $birthdays = Member::query()
            ->whereMonth('tanggal_lahir', now()->month)
            ->orderByRaw('EXTRACT(DAY FROM tanggal_lahir) ASC')
            ->limit(5)
            ->get();

        return [
            'birthdays' => $birthdays,
        ];
    }
}
