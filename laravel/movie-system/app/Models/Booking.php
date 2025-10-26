<?php
//Name: Wo Jia Qian
//Student ID: 2314023

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $account_id
 * @property int $schedule_id
 * @property string $booking_time
 * @property string $total_amount
 * @property string $qr_code
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookingSeat> $bookingSeats
 * @property-read int|null $booking_seats_count
 * @property-read \App\Models\Schedule $schedule
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereBookingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Booking extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_id',
        'schedule_id',
        'booking_time',
        'total_amount',
        'qr_code',
        'status',
    ];

    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }

    public function bookingSeats() {
        return $this->hasMany(BookingSeat::class);
    }

}
