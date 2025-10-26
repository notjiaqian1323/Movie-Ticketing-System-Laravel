<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Services\Schedule\Filters;

use App\Services\Schedule\Strategy;
use Illuminate\Database\Eloquent\Builder;

class FilterByMovie implements Strategy
{
    protected ?int $movieId;
    
    public function __construct(?int $movieId)
    {
        $this->movieId = $movieId;
    }

    public function apply(Builder $query): Builder
    {
        if (!$this->movieId) {
            return $query;
        }

        return $query->where('movie_id', $this->movieId);
    }
}
