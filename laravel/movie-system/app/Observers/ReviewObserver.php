<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Observers;

use App\Models\Review;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ReviewObserver
{
    public function created(Review $review): void
    {
        $this->refreshCaches($review);
        Log::info('review.created', [
            'review_id'  => $review->id,
            'movie_id'   => $review->movie_id,
            'account_id' => $review->account_id,
        ]);
    }

    public function updating(Review $review): void
    {
        // Mark edited if rating or comment are changing, but don't trigger extra saves
        if (!$review->edited && $review->isDirty(['rating', 'comment'])) {
            $review->edited = true;
        }
    }

    public function updated(Review $review): void
    {
        // If any user-visible field changed, bump the "last activity" timestamp
        if ($review->wasChanged(['rating', 'comment', 'is_anonymous'])) {
            $review->forceFill([
                'edited'          => true,
                'review_datetime' => now(),
            ])->saveQuietly(); // avoids infinite observer loops
        }

        $this->refreshCaches($review);
        Log::info('review.updated', [
            'review_id' => $review->id,
            'movie_id'  => $review->movie_id,
        ]);
    }

    public function deleted(Review $review): void
    {
        $this->refreshCaches($review);
        Log::warning('review.deleted', [
            'review_id' => $review->id,
            'movie_id'  => $review->movie_id,
        ]);
    }

    private function refreshCaches(Review $review): void
    {
        Cache::forget("movie:{$review->movie_id}:reviews:summary");
    }
}
