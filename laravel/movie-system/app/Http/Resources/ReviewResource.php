<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'movie_id'       => $this->movie_id,
            'account_id'     => $this->account_id,
            'account' => [
                'username' => $this->is_anonymous
                    ? 'Anonymous'
                    : ($this->account->username ?? 'User')
            ],
            'rating'         => (int) $this->rating,
            'comment'        => $this->comment,
            'is_anonymous'   => (bool) $this->is_anonymous,
            'edited'         => (bool) $this->edited,
            'reviewed_at'    => $this->review_datetime?->toDateTimeString(),
            'created_at'     => $this->created_at->toDateTimeString(),
            'updated_at'     => $this->updated_at->toDateTimeString(),
        ];
    }
}


