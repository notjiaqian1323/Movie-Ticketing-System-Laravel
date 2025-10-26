<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $movie_id
 * @property int $hall_id
 * @property string $show_time
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \App\Models\Hall $hall
 * @property-read \App\Models\Movie $movie
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Seat> $seats
 * @property-read int|null $seats_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereHallId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereMovieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereShowTime($value)
 * @mixin \Eloquent
 */
class Schedule extends Model
{
    public $timestamps = false;

    // Added for mass assignment
    protected $fillable = [
        'movie_id',
        'hall_id',
        'show_time',
    ];

    protected $casts = [
        'show_time' => 'datetime',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * A schedule has many seats.
     * This relationship uses the `schedule_seats` pivot table
     * to get the status of each seat for this specific showtime.
     */
    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'schedule_seats')->withPivot('status');
    }

}
