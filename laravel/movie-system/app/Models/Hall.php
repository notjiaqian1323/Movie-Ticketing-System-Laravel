<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $hall_name
 * @property int $total_rows
 * @property int $total_columns
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Seat> $seats
 * @property-read int|null $seats_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall whereHallName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall whereTotalColumns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall whereTotalRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hall whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Hall extends Model
{
    public function seats() {
        return $this->hasMany(Seat::class);
    }

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }

}
