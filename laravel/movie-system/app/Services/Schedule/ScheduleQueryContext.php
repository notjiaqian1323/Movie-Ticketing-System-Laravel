<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Services\Schedule;

use Illuminate\Database\Eloquent\Builder;

class ScheduleQueryContext
{
    protected Builder $query;
    /** @var Strategy[] */
    protected array $strategies = [];

    public function __construct(Builder $query, array $strategies = [])
    {
        $this->query = $query;
        $this->strategies = $strategies;
    }

    public function addStrategy(Strategy $strategy): self
    {
        $this->strategies[] = $strategy;
        return $this;
    }

    /**
     * Apply strategies in order and return the final query builder.
     */
    public function apply(): Builder
    {
        foreach ($this->strategies as $strategy) {
            $this->query = $strategy->apply($this->query);
        }

        return $this->query;
    }
}
