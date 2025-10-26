<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Services;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

class ReviewService
{

    /**
     * Get all reviews for a specific movie, with pagination and user data.
     * for movie.show
     */
    public function getReviewsForMovie(int $movieId, int $perPage)
    {
        $reviews = Review::where('movie_id', $movieId)
            ->latest('review_datetime')
            ->paginate($perPage);

        // --- usernames via Account API (best-effort) ---
        $accountIds = $reviews->pluck('account_id')->filter()->unique()->implode(',');
        $usernames = collect();
        if (!empty($accountIds)) {
            try {
                $response = Http::get(
                    rtrim((string) env('ACCOUNT_API_URL'), '/') . '/usernames',
                    ['ids' => $accountIds]
                );
                $response->throw();
                $usernames = collect($response->json());
            } catch (RequestException $e) {
                Log::error('account_api.usernames.fail', [
                    'ids' => $accountIds,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // attach username (fallback)
        $reviews->getCollection()->transform(function ($review) use ($usernames) {
            $accountData = $usernames->get((string) $review->account_id);
            $review->username = $accountData['username'] ?? 'User';
            return $review;
        });

        // --- TRUE GLOBAL SUMMARY (not page) ---
        $summary = Review::selectRaw('COUNT(*) AS reviews_count, AVG(rating) AS reviews_avg_rating')
            ->where('movie_id', $movieId)
            ->first();

        return [
            'reviews' => $reviews,
            'reviews_count' => (int) ($summary->reviews_count ?? 0),
            'reviews_avg_rating' => $summary && $summary->reviews_avg_rating !== null
                ? round((float) $summary->reviews_avg_rating, 1)
                : 0.0,
        ];
    }

        /**
     * Get all reviews for a specific user, with eager-loaded movie details from the Movie API.
     * review history
     */
    public function getUserReviews(int $accountId, string $sort, int $perPage)
    {
        $dateCol = 'review_datetime';

        $query = Review::where('account_id', $accountId);

        switch ($sort) {
            case 'old':
                $query->orderBy($dateCol, 'asc');
                break;
            case 'high':
                $query->orderBy('rating', 'desc')->orderBy($dateCol, 'desc');
                break;
            case 'low':
                $query->orderBy('rating', 'asc')->orderBy($dateCol, 'desc');
                break;
            case 'new':
            default:
                $query->orderBy($dateCol, 'desc');
                break;
        }

        $reviews = $query->paginate($perPage);
        $movieIds = $reviews->pluck('movie_id')->unique()->all();

        $moviesData = $this->getMoviesByIds($movieIds);

        return [
            'reviews' => $reviews,
            'movies' => $moviesData, 
        ];

    }

    /**
     * Get data for a list of movies from the Movie API using HTTP.
     * This method is now part of the ReviewService, encapsulating the external API call.
     */
    protected function getMoviesByIds(array $movieIds): Collection
    {
        if (empty($movieIds)) {
            return collect();
        }

        $ids = implode(',', $movieIds);
        $movieApiUrl = env('MOVIE_API_URL') . "/movies/info-for-ids"; 

        try {
            $response = Http::get($movieApiUrl, ['ids' => $ids]);
            $response->throw();
            // Ensure the response is a collection keyed by ID.
            return collect($response->json('data'))->keyBy('id');
        } catch (RequestException $e) {
            Log::error("Failed to fetch movie data from Movie API for IDs {$ids}: " . $e->getMessage());
            return collect();
        }
    }

    
    /**
     * Get review summaries for a collection of movies in a single query.
     * movie card data - avg_rating and count
     */
    public function getMovieReviewsSummary(array $movieIds): Collection
    {
        return Review::selectRaw('movie_id, count(*) as reviews_count, avg(rating) as reviews_avg_rating')
            ->whereIn('movie_id', $movieIds)
            ->groupBy('movie_id')
            ->get()
            ->keyBy('movie_id');
    }

    /**
     * Check if a user has already reviewed a specific movie.
     */
    public function userHasReviewed(int $movieId, int $accountId): bool
    {
        return Review::where('movie_id', $movieId)
            ->where('account_id', $accountId)
            ->exists();
    }

    public function userHasBooked(int $movieId, int $accountId): bool
    {
        Log::info('ReviewService.userHasBooked.check', [
            'movie_id' => $movieId,
            'account_id' => $accountId,
        ]);

        $base = rtrim((string) config('services.booking.url', env('BOOKING_API_URL', '')), '/');

        if ($base !== '') {
            $url = "{$base}/booking/isbooked/{$movieId}/{$accountId}";
            try {
                $res = Http::timeout(3)->retry(1, 150)->get($url);
                $res->throw(); // non-2xx -> exception
                $val = $res->json('booked');
                if ($val === null) {
                    foreach (['is_booked', 'eligible', 'exists'] as $k) {
                        $tmp = $res->json($k);
                        if ($tmp !== null) {
                            $val = $tmp;
                            break;
                        }
                    }
                }

                $isBooked = (bool) $val;
                Log::info('ReviewService.userHasBooked.api_ok', ['url' => $url, 'booked' => $isBooked]);
                return $isBooked;
            } catch (RequestException $e) {
                Log::warning('ReviewService.userHasBooked.api_fail', [
                    'url' => $url,
                    'status' => $e->response?->status(),
                    'err' => $e->getMessage(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('ReviewService.userHasBooked.api_error', ['url' => $url, 'err' => $e->getMessage()]);
            }
        }

        try {
            $q = Booking::query()
                ->where('account_id', $accountId)
                ->whereHas('schedule', fn($qq) => $qq->where('movie_id', $movieId))
                ->whereHas('bookingSeats', fn($qq) => $qq->whereNotNull('seat_id'));

            if (Schema::hasColumn('bookings', 'status')) {
                $q->whereIn('status', ['confirmed', 'booked']); // exclude pending/cancelled
            }

            if ($q->exists()) {
                return true;
            }

            // Stronger guard: ensure seat belongs to that schedule and is reserved/booked
            return Booking::query()
                ->where('account_id', $accountId)
                ->whereHas('schedule', fn($qq) => $qq->where('movie_id', $movieId))
                ->whereHas('bookingSeats', fn($qq) => $qq->whereNotNull('seat_id'))
                ->whereExists(function ($qq) {
                    $qq->select(DB::raw(1))
                        ->from('booking_seats as bs')
                        ->join('bookings as b', 'b.id', '=', 'bs.booking_id')
                        ->join('schedule_seats as ss', function ($join) {
                            $join->on('ss.seat_id', '=', 'bs.seat_id')
                                ->on('ss.schedule_id', '=', 'b.schedule_id');
                        })
                        ->whereColumn('b.id', 'bookings.id')
                        ->whereIn('ss.status', ['reserved', 'booked']);
                })
                ->exists();
        } catch (\Throwable $e) {
            Log::error('ReviewService.userHasBooked.db_error', ['err' => $e->getMessage()]);
            return false;
        }
    }
}