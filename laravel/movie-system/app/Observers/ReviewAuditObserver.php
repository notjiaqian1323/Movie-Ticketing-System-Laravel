<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Observers;

use App\Models\Review;
use Illuminate\Support\Facades\Log;

class ReviewAuditObserver
{
    public function created(Review $r): void
    {
        Log::info('review.audit.created', [
            'movie_id' => $r->movie_id,
            'user_id'  => $r->account_id,
        ]);
    }

    public function updated(Review $r): void
    {
        Log::info('review.audit.updated', [
            'review_id' => $r->id,
        ]);
    }

    public function deleted(Review $r): void
    {
        Log::warning('review.audit.deleted', [
            'review_id' => $r->id,
        ]);
    }
}
