<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Services\Schedule\Filters;

use App\Services\Schedule\Strategy;
use Illuminate\Database\Eloquent\Builder;

class FilterByAvailability implements Strategy
{
    /**
     * If true, filter schedules with at least one available seat.
     */
    protected bool $requireAvailable;

    public function __construct(bool $requireAvailable = true)
    {
        $this->requireAvailable = $requireAvailable;
    }

    public function apply(Builder $query): Builder
    {
        if (!$this->requireAvailable) {
            return $query;
        }

        // Use whereHas on seats pivot to check for available seats.
        return $query->whereHas('seats', function ($q) {
            // pivot table is schedule_seats
            $q->where('schedule_seats.status', 'available');
        });
    }
}
