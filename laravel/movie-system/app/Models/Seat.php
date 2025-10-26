<?php
//Name: Wo Jia Qian
//Student Id: 2314023

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $hall_id
 * @property string $row_char
 * @property int $seat_number
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookingSeat> $bookingSeats
 * @property-read int|null $booking_seats_count
 * @property-read \App\Models\Hall $hall
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereHallId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereRowChar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereSeatNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Seat extends Model
{
    public function hall() {
        return $this->belongsTo(Hall::class);
    }

    public function bookingSeats() {
        return $this->hasMany(BookingSeat::class);
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_seats')->withPivot('status');
    }

    public function getNameAttribute()
    {
        return "{$this->row_char}{$this->seat_number}";
    }
}
