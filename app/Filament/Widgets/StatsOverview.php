<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use \BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

    protected static string $view = 'filament.widgets.stats-overview-custom';
    protected static ?int $sort = 0;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Jemaat', Member::count())
                ->description('Total jemaat terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->url(\App\Filament\Resources\MemberResource::getUrl()),
            Stat::make('Total Pengurus', User::whereNotNull('role')->count())
                ->description('Total pengurus aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->url(\App\Filament\Resources\UserResource::getUrl()),
            Stat::make('Pending Approval', WithdrawalRequest::where('status', 'submitted')->count())
                ->description('Pengambilan dana tertunda')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(\App\Filament\Resources\WithdrawalRequestResource::getUrl()),
        ];
    }
}
