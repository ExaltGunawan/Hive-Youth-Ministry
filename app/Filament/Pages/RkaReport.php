<?php

namespace App\Filament\Pages;

use App\Models\Rka;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Actions\Action;
use Illuminate\Support\Collection;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use App\Models\User;

class RkaReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Laporan RKA';
    protected static ?string $title = 'Laporan Penggunaan Anggaran (RKA)';

    protected static string $view = 'filament.pages.rka-report';

    public ?array $data = [];
    public ?Collection $rkaData = null;
    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?string $ketuaName = null;
    public ?string $bendaharaName = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Laporan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('rka_ids')
                                    ->label('Pilih RKA / Program')
                                    ->multiple()
                                    ->options(Rka::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->columnSpan(1),
                                DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->placeholder('Pilih tanggal mulai')
                                    ->columnSpan(1),
                                DatePicker::make('end_date')
                                    ->label('Tanggal Selesai')
                                    ->placeholder('Pilih tanggal selesai')
                                    ->columnSpan(1),
                            ])
                    ])
            ])
            ->statePath('data');
    }

    public function generateReport()
    {
        $this->validate();
        
        $selectedIds = $this->data['rka_ids'];
        $this->startDate = $this->data['start_date'] ?? null;
        $this->endDate = $this->data['end_date'] ?? null;
        
        $this->rkaData = Rka::with(['items.withdrawalItems' => function($query) {
                if ($this->startDate) {
                    $query->whereHas('withdrawalRequest', fn($q) => $q->whereDate('withdrawal_date', '>=', $this->startDate));
                }
                if ($this->endDate) {
                    $query->whereHas('withdrawalRequest', fn($q) => $q->whereDate('withdrawal_date', '<=', $this->endDate));
                }
            }, 'items.withdrawalItems.withdrawalRequest'])
            ->whereIn('id', $selectedIds)
            ->get();

        // Get names for signatures based on Division instead of Role
        $this->ketuaName = User::whereHas('divisi', fn($q) => $q->where('nama_divisi', 'like', '%Ketua%'))
            ->first()?->name ?? '...........................';
            
        $this->bendaharaName = User::whereHas('divisi', fn($q) => $q->where('nama_divisi', 'like', '%Bendahara%'))
            ->first()?->name ?? '...........................';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn () => $this->rkaData !== null && $this->rkaData->count() > 0)
                ->action(function () {
                    $this->dispatch('print-report');
                }),
        ];
    }
}
