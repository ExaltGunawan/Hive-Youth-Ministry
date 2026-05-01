<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Schedule;
use App\Models\Divisi;
use Carbon\Carbon;

class CalendarWidget extends Component
{
    public $currentMonth;
    public $currentYear;
    public $selectedDate;
    public $selectedDivisions = [];

    protected $listeners = ['updateFilters' => 'handleFilterUpdate'];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->selectedDate = now()->toDateString();
    }

    public function handleFilterUpdate($divisions)
    {
        $this->selectedDivisions = $divisions;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->selectedDate = $date->copy()->startOfMonth()->toDateString();
        $this->dispatch('dateSelected', $this->selectedDate);
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->selectedDate = $date->copy()->startOfMonth()->toDateString();
        $this->dispatch('dateSelected', $this->selectedDate);
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->dispatch('dateSelected', $date);
    }

    public function toggleDivision($divisionId)
    {
        if (in_array($divisionId, $this->selectedDivisions)) {
            $this->selectedDivisions = array_diff($this->selectedDivisions, [$divisionId]);
        } else {
            $this->selectedDivisions[] = $divisionId;
        }
        $this->dispatch('updateFilters', $this->selectedDivisions);
    }

    public function render()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $firstDayOfMonth = $date->dayOfWeek;

        $prevDate = (clone $date)->subMonth();
        $daysInPrevMonth = $prevDate->daysInMonth;

        $calendar = [];
        
        // Previous month days
        for ($i = $firstDayOfMonth - 1; $i >= 0; $i--) {
            $day = $daysInPrevMonth - $i;
            $calendar[] = [
                'day' => $day,
                'currentMonth' => false,
                'date' => $prevDate->copy()->day($day)->toDateString(),
            ];
        }

        // Current month days
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $calendar[] = [
                'day' => $i,
                'currentMonth' => true,
                'date' => $date->copy()->day($i)->toDateString(),
            ];
        }

        // Next month days
        $nextDate = (clone $date)->addMonth();
        $remainingDays = 42 - count($calendar);
        for ($i = 1; $i <= $remainingDays; $i++) {
            $calendar[] = [
                'day' => $i,
                'currentMonth' => false,
                'date' => $nextDate->copy()->day($i)->toDateString(),
            ];
        }

        $allMonthSchedules = Schedule::with('divisi')
            ->whereYear('tanggal', $this->currentYear)
            ->whereMonth('tanggal', $this->currentMonth)
            ->get()
            ->groupBy(fn($item) => $item->tanggal->toDateString());

        $divisi = Divisi::all();

        return view('livewire.calendar-widget', [
            'calendar' => $calendar,
            'monthName' => $date->format('F Y'),
            'scheduleDates' => $allMonthSchedules,
            'divisions' => $divisi,
        ]);
    }
}
