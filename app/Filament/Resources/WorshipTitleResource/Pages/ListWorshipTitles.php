<?php

namespace App\Filament\Resources\WorshipTitleResource\Pages;

use App\Filament\Resources\WorshipTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Livewire\Attributes\Url;

class ListWorshipTitles extends ListRecords
{
    protected static string $resource = WorshipTitleResource::class;

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

    public function getTabs(): array
    {
        return [];
    }

    protected function applyFiltersToTableQuery(Builder $query): Builder
    {
        $date = Carbon::parse($this->activeMonth)->startOfMonth();
        
        return $query
            ->whereBetween('date', [
                $date->copy()->startOfMonth()->toDateString(),
                $date->copy()->endOfMonth()->toDateString(),
            ]);
    }
}
