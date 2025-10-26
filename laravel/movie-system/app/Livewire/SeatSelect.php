<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Support\Collection;
use Carbon\Carbon;


class SeatSelect extends Component
{
    /** @var \Illuminate\Support\Collection */
    public $schedules;

    public $movie;

    /** @var \Illuminate\Support\Collection */
    public $dates = [];

    //schedule related data
    public $selectedDate = null;
    public $showtimesForDate = [];
    public $selectedScheduleId = null;

    public function mount($schedules, $movie){

        // === FIX 1: Explicitly cast the schedules to a Collection ===
        $this->schedules = Collection::make($schedules);
        $this->movie = $movie;
        $this->dates = $this->schedules
            ->groupBy(function ($schedule) {
                return Carbon::parse($schedule->show_time)->format('Y-m-d');
            })
            ->keys();
        // Set the default selected date
        if ($this->dates->isNotEmpty()) {
            $this->selectedDate = $this->dates->first();
            $this->updatedSelectedDate($this->selectedDate);
        }
    }

    public function updatedSelectedDate($value){
        $this->showtimesForDate = $this->schedules
            ->filter(function ($schedule) use ($value) {
                return Carbon::parse($schedule->show_time)->format('Y-m-d') === $value;
            })
            ->sortBy('show_time')
            ->values();
        
        $this->selectedScheduleId = null;
    }

    public function selectShowtime($scheduleId)
    {
        $this->selectedScheduleId = $scheduleId;
        //$this->fetchSeats();
    }

        /**
     * Get the formatted show time for the view.
     */
    public function getFormattedShowTimeProperty()
    {
        if ($this->selectedSchedule) {
            // Re-parse the string as a Carbon instance before formatting
            return Carbon::parse($this->selectedSchedule->show_time)->format('h:i A');
        }
        return null;
    }

    /**
     * Get the formatted show date for the view.
     */
    public function getFormattedShowDateProperty()
    {
        if ($this->selectedSchedule) {
            // Re-parse the string as a Carbon instance before formatting
            return Carbon::parse($this->selectedSchedule->show_time)->format('F d, Y');
        }
        return null;
    }

    public function getSelectedScheduleProperty()
    {
        return Schedule::find($this->selectedScheduleId);
    }

    public function render()
    {
        return view('livewire.seat-select');
    }
}
