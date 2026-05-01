<?php

namespace App\Filament\Resources\ServiceScheduleResource\Pages;

use App\Filament\Resources\ServiceScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;

class ListServiceSchedules extends ListRecords
{
    protected static string $resource = ServiceScheduleResource::class;

    #[Url]
    public ?string $activeMonth = null;

    public function mount(): void
    {
        parent::mount();

        if (! $this->activeMonth) {
            $this->activeMonth = Carbon::now()->format('Y-m');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('selectMonth')
                ->label(fn () => Carbon::parse($this->activeMonth)->format('F Y'))
                ->icon('heroicon-m-calendar')
                ->color('gray')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('month')
                        ->label('Pilih Bulan')
                        ->native(false)
                        ->displayFormat('F Y')
                        ->format('Y-m')
                        ->default(fn () => Carbon::parse($this->activeMonth)->startOfMonth()),
                ])
                ->action(function (array $data) {
                    $this->activeMonth = Carbon::parse($data['month'])->format('Y-m');
                }),
            Actions\Action::make('prevMonth')
                ->label('')
                ->icon('heroicon-m-chevron-left')
                ->color('gray')
                ->action(function () {
                    $this->activeMonth = Carbon::parse($this->activeMonth)->subMonth()->format('Y-m');
                }),
            Actions\Action::make('nextMonth')
                ->label('')
                ->icon('heroicon-m-chevron-right')
                ->color('gray')
                ->action(function () {
                    $this->activeMonth = Carbon::parse($this->activeMonth)->addMonth()->format('Y-m');
                }),
            Actions\CreateAction::make(),
        ];
    }

    protected function applyFiltersToTableQuery(Builder $query): Builder
    {
        $date = Carbon::parse($this->activeMonth)->startOfMonth();
        
        return $query
            ->whereBetween('tanggal', [
                $date->copy()->startOfMonth()->toDateString(),
                $date->copy()->endOfMonth()->toDateString(),
            ]);
    }
}
