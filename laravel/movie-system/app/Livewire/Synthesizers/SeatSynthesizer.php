<?php

namespace App\Livewire\Synthesizers;

use App\Models\Seat;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class SeatSynthesizer extends Synth
{
    public static $key = 'seat';

    /**
     * Dehydrate a seat instance to a simple array.
     * We'll also store the pivot data, so we don't lose it.
     */
    public function dehydrate($target, $context)
    {
        $payload = [
            'id' => $target->id,
            'row_char' => $target->row_char,
            'seat_number' => $target->seat_number,
        ];

        // Explicitly check for and include the pivot data.
        if (isset($target->pivot)) {
            $payload['pivot'] = [
                'status' => $target->pivot->status,
            ];
        }

        return [$payload, []];
    }

    /**
     * Rehydrate the seat instance from the simple array.
     * We'll re-attach the pivot data that we saved.
     */
    public function rehydrate($value, $context)
    {
        $seat = new Seat([
            'id' => $value['id'],
            'row_char' => $value['row_char'],
            'seat_number' => $value['seat_number'],
        ]);

        // Re-attach the pivot data as a new object.
        if (isset($value['pivot'])) {
            $seat->setRelation('pivot', new \Illuminate\Database\Eloquent\Relations\Pivot([], null, ''));
            $seat->pivot->status = $value['pivot']['status'];
        }

        return $seat;
    }

    /**
     * This method tells Livewire if this synthesizer can handle the given value.
     * The signature must exactly match the parent class.
     */
    /**
     * This method tells Livewire if this synthesizer can handle the given value.
     * The signature must exactly match the parent class, without a return type.
     */
    /**
     * This method tells Livewire if this synthesizer can handle the given value.
     * We check both the value's class and the property name to be precise.
     */
    /**
     * This method tells Livewire if this synthesizer can handle the given value.
     * We check both the value's class and the property name to be precise.
     */
    public static function match($target)
    {
        return $target instanceof Seat;
    }
}