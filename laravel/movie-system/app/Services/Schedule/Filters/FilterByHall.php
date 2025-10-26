<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Services\Schedule\Filters;

use App\Services\Schedule\Strategy;
use Illuminate\Database\Eloquent\Builder;

class FilterByHall implements Strategy
{
    protected ?int $hallId;

    public function __construct(?int $hallId)
    {
        $this->hallId = $hallId;
    }

    public function apply(Builder $query): Builder
    {
        if (!$this->hallId) {
            return $query;
        }

        return $query->where('hall_id', $this->hallId);
    }
}
