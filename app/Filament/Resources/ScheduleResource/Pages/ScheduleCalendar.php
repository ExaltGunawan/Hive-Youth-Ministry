<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Resources\Pages\Page;

class ScheduleCalendar extends Page
{
    protected static string $resource = ScheduleResource::class;

    protected static string $view = 'filament.resources.schedule-resource.pages.schedule-calendar';

    protected static ?string $title = 'Schedule';

    public function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create')
                ->label('ADD SCHEDULE')
                ->url(ScheduleResource::getUrl('create'))
                ->color('warning')
                ->extraAttributes([
                    'class' => 'rounded-xl font-black tracking-widest text-[10px] shadow-lg shadow-orange-200/50 dark:shadow-none',
                    'style' => 'background-color: #F59E0B !important; color: white !important; border: none !important;'
                ]),
        ];
    }
}
