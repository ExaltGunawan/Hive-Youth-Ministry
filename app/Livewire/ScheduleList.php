<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleList extends Component
{
    public $selectedDate;
    public $selectedDivisions = [];

    protected $listeners = [
        'dateSelected' => 'handleDateSelected',
        'updateFilters' => 'handleFilterUpdate'
    ];

    public function mount()
    {
        $this->selectedDate = now()->toDateString();
    }

    public function handleDateSelected($date)
    {
        $this->selectedDate = $date;
    }

    public function handleFilterUpdate($divisions)
    {
        $this->selectedDivisions = $divisions;
    }

    public function render()
    {
        $date = Carbon::parse($this->selectedDate);
        
        $schedules = Schedule::with('divisi')
            ->whereDate('tanggal', '>=', $date->copy()->startOfMonth())
            ->whereDate('tanggal', '<=', $date->copy()->endOfMonth())
            ->when(!empty($this->selectedDivisions), function ($query) {
                return $query->where(function ($q) {
                    $q->whereIn('divisi_id', $this->selectedDivisions)
                      ->orWhereNull('divisi_id');
                });
            })
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get()
            ->groupBy(fn($item) => $item->tanggal->toDateString());

        return view('livewire.schedule-list', [
            'schedules' => $schedules,
            'selectedDate' => $this->selectedDate,
        ]);
    }
}
