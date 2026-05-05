<?php

namespace App\Filament\Resources\RkaResource\Pages;

use App\Filament\Resources\RkaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;

class ListRkas extends ListRecords
{
    protected static string $resource = RkaResource::class;

    public $activeYear;

    public function mount(): void
    {
        parent::mount();
        $this->activeYear = request()->query('year', date('Y'));
    }

    protected function getHeaderActions(): array
    {
        $currentYear = session('rka_fiscal_year', date('Y'));
        
        $rkas = \App\Models\Rka::where('fiscal_year', $currentYear)
            ->with('items.withdrawalItems.withdrawalRequest')
            ->get();
            
        // Calculate total for the selected year
        $totalAmount = $rkas->sum(fn ($rka) => $rka->items->sum(fn ($item) => $item->price * $item->quantity));
        $totalRemaining = $rkas->sum(fn ($rka) => $rka->items->sum(fn ($item) => $item->remaining_balance));

        return [
            Actions\Action::make('year_nav')
                ->label('')
                ->view('filament.resources.rka.year-nav-buttons', [
                    'currentYear' => $currentYear,
                    'totalAmount' => $totalAmount,
                    'totalRemaining' => $totalRemaining,
                ]),
            Actions\CreateAction::make()
                ->label('Add Item')
                ->icon('heroicon-m-plus')
                ->color('warning'),
        ];
    }

    protected function applyFiltersToTableQuery(Builder $query): Builder
    {
        $currentYear = session('rka_fiscal_year', date('Y'));
        return $query->where('fiscal_year', $currentYear);
    }

    public function previousYear()
    {
        $year = session('rka_fiscal_year', date('Y'));
        session(['rka_fiscal_year' => $year - 1]);
        $this->redirect(static::getResource()::getUrl('index'));
    }

    public function nextYear()
    {
        $year = session('rka_fiscal_year', date('Y'));
        session(['rka_fiscal_year' => $year + 1]);
        $this->redirect(static::getResource()::getUrl('index'));
    }
}
