<?php

namespace App\Filament\Resources\WorshipThemeResource\Pages;

use App\Filament\Resources\WorshipThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;

class ListWorshipThemes extends ListRecords
{
    protected static string $resource = WorshipThemeResource::class;

    #[Url]
    public ?string $activeYear = null;

    public function mount(): void
    {
        parent::mount();

        if (! $this->activeYear) {
            $this->activeYear = Carbon::now()->format('Y');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('selectYear')
                ->label(fn () => $this->activeYear)
                ->icon('heroicon-m-calendar')
                ->color('gray')
                ->form([
                    \Filament\Forms\Components\Select::make('year')
                        ->label('Pilih Tahun')
                        ->options(function () {
                            $years = range(Carbon::now()->year - 5, Carbon::now()->year + 5);
                            return array_combine($years, $years);
                        })
                        ->default($this->activeYear),
                ])
                ->action(function (array $data) {
                    $this->activeYear = $data['year'];
                }),
            Actions\Action::make('prevYear')
                ->label('')
                ->icon('heroicon-m-chevron-left')
                ->color('gray')
                ->action(function () {
                    $this->activeYear = (int)$this->activeYear - 1;
                }),
            Actions\Action::make('nextYear')
                ->label('')
                ->icon('heroicon-m-chevron-right')
                ->color('gray')
                ->action(function () {
                    $this->activeYear = (int)$this->activeYear + 1;
                }),
            Actions\CreateAction::make(),
        ];
    }

    protected function applyFiltersToTableQuery(Builder $query): Builder
    {
        return $query
            ->whereYear('month', $this->activeYear);
    }
}
