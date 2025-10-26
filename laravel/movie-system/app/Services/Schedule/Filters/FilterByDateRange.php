<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Services\Schedule\Filters;

use App\Services\Schedule\Strategy;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class FilterByDateRange implements Strategy
{
    protected ?string $start;
    protected ?string $end;

    /**
     * Provide ISO dates or datetimes as strings. Either may be null.
     */
    public function __construct(?string $start, ?string $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function apply(Builder $query): Builder
    {
        // if both present, whereBetween
        if ($this->start && $this->end) {
            return $query->whereBetween('show_time', [
                Carbon::parse($this->start)->startOfDay(),
                Carbon::parse($this->end)->endOfDay(),
            ]);
        }

        if ($this->start) {
            return $query->where('show_time', '>=', Carbon::parse($this->start)->startOfDay());
        }

        if ($this->end) {
            return $query->where('show_time', '<=', Carbon::parse($this->end)->endOfDay());
        }

        return $query;
    }
}