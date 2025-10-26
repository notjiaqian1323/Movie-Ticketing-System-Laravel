<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Services\Schedule\Sorts;

use App\Services\Schedule\Strategy;
use Illuminate\Database\Eloquent\Builder;

class SortByAvailableSeats implements Strategy
{
    protected string $direction;

    public function __construct(string $direction = 'desc')
    {
        $this->direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
    }

    public function apply(Builder $query): Builder
    {
        // Add a computed available_seats_count and sort by it
        return $query->withCount([
            'seats as available_seats_count' => function ($q) {
                $q->where('schedule_seats.status', 'available');
            }
        ])->orderBy('available_seats_count', $this->direction);
    }
}
