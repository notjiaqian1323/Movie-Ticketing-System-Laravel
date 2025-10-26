<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Services\Schedule;

use Illuminate\Database\Eloquent\Builder;

interface Strategy
{
    /**
     * Modify the given Eloquent query builder and return it.
     */
    public function apply(Builder $query): Builder;
}
