<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Services\Schedule\Sorts;

use App\Services\Schedule\Strategy;
use Illuminate\Database\Eloquent\Builder;

class SortByShowTime implements Strategy
{
    protected string $direction;

    public function __construct(string $direction = 'asc')
    {
        $this->direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
    }

    public function apply(Builder $query): Builder
    {
        return $query->orderBy('show_time', $this->direction);
    }
}
