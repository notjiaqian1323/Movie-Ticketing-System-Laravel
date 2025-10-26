<?php
//Name: Wo Jia Qian
//Student ID: 2314023

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $booking_id
 * @property int|null $seat_id
 * @property string $ticket_type
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @property-read \App\Models\Seat|null $seat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat whereSeatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat whereTicketType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingSeat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BookingSeat extends Model
{

    protected $fillable = [
        'booking_id',
        'seat_id',
        'ticket_type',
        'price',
    ];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function seat() {
        return $this->belongsTo(Seat::class);
    }

}
