<?php
namespace App\Services;

//Name: HO YI VON
//Student ID : 23WMR14542

use App\Models\Movie;
use App\Http\Resources\MovieResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieService
{

    /** Called by MovieController
     * 
     * Get a paginated list of movies based on filter criteria.
     * This method orchestrates the data retrieval from local DB and the Reviews API.
     * movie listing
     */
    public function getMoviesList(array $filters): array
    {
        $query = Movie::query()->orderBy('release_date', 'desc');

        $query->when(isset($filters['genre']), function ($q) use ($filters) {
            $q->whereJsonContains('genres', $filters['genre']);
        });

        $query->when(isset($filters['status']), function ($q) use ($filters) {
            $q->where('status', $filters['status']);
        });
        
        $query->when(isset($filters['search']), function ($q) use ($filters) {
            $q->where('title', 'like', '%' . $filters['search'] . '%');
        });

        $perPage = (int) ($filters['per_page'] ?? 12);

        // 1. Get the paginated collection of movies from the local database
        $movies = $query->paginate($perPage);

        // 2. Collect all the movie IDs from the current page's collection
        $movieIds = $movies->pluck('id')->implode(',');

        // 3. Make the single HTTP call to the Reviews API for all IDs
        $reviewsData = collect();
        if (!empty($movieIds)) {
            try {
                $reviewsApiUrl = env('REVIEW_API_URL') . '/reviews/summary';
                $response = Http::get($reviewsApiUrl, ['ids' => $movieIds]);
                $response->throw();
                $reviewsData = collect($response->json())->keyBy('movie_id');
            } catch (RequestException $e) {
                Log::error("Failed to fetch reviews for movie listing: " . $e->getMessage());
            }
        }
        
        // 4. Attach the review data to each movie item in the paginated collection
        // We use the ->through() method on the paginator to transform the items
        $movies->through(function ($movie) use ($reviewsData) {
            $summary = $reviewsData->get($movie->id);
            $movie->reviews_count = $summary['reviews_count'] ?? 0;
            $movie->reviews_avg_rating = round($summary['reviews_avg_rating'] ?? 0, 1);
            $movie->movie_status = $movie->getState()->displayStatus($movie);
            $movie->booking_allowed = $movie->getState()->isBookingAllowed($movie);
            return $movie;
        });
        
        // 5. Return the final paginated data.
        // We will return an array with the paginator instance so the controller can format it.
        return [
            'movies' => $movies,
            'genres' => Movie::pluck('genre')->flatten()->unique(),
        ];
    }

    /**
     * Get a specific movie with summary review, already review , related movie
     * movie show
     */
    public function getMoviePageData(Movie $movie, int $perPage)
    {
        // 1. Load the related movies
        $relatedMovies = Movie::related($movie)->get();

        // 2. Collect all movie IDs for review summary (main movie + related)
        $allMovieIds = $relatedMovies->pluck('id')->push($movie->id)->unique()->implode(',');

        $reviewsPaginator = new LengthAwarePaginator(collect(), 0, $perPage);
        $alreadyReviewed = false;
        $reviewCount = 0;
        $reviewAvg = 0;
        $movieStatus = $movie->getState()->displayStatus($movie);
        $movieReviewPermission = $movie->getState()->writeReview($movie);
        $movieBookingPermission = $movie->getState()->isBookingAllowed($movie);

        try {
            // 3. API call to get summary for all relevant movies
            $summaryResponse = Http::get(env('REVIEW_API_URL') . '/reviews/summary', ['ids' => $allMovieIds]);
            $summaryResponse->throw();
            $summaries = collect($summaryResponse->json())->keyBy('movie_id');

            // 4. Extract data for the main movie
            $mainMovieSummary = $summaries->get($movie->id);
            if ($mainMovieSummary) {
                $reviewCount = $mainMovieSummary['reviews_count'] ?? 0;
                $reviewAvg = $mainMovieSummary['reviews_avg_rating'] ?? 0;
            }

            // 5. Attach summary data to each related movie
            $relatedMovies->transform(function ($relatedMovie) use ($summaries) {
                $summary = $summaries->get($relatedMovie->id);
                $relatedMovie->reviews_count = $summary['reviews_count'] ?? 0;
                $relatedMovie->reviews_avg_rating = round($summary['reviews_avg_rating'] ?? 0, 1);
                $relatedMovie->movie_status = $relatedMovie->getState()->displayStatus($relatedMovie);
                $relatedMovie->booking_allowed = $relatedMovie->getState()->isBookingAllowed($relatedMovie);
                return $relatedMovie;
            });

            // 6. API call for detailed reviews list for the main movie
            $reviewsResponse = Http::get(env('REVIEW_API_URL') . "/reviews/movies/{$movie->id}", ['per_page' => $perPage]);
            $reviewsResponse->throw();

            $reviewsJson = $reviewsResponse->json();
            $reviews = collect($reviewsJson['data']);

            $reviewsPaginator = new LengthAwarePaginator(
                    $reviews,
                    $reviewsJson['meta']['total'],
                    $reviewsJson['meta']['per_page'],
                    $reviewsJson['meta']['current_page'],
                    ['path' => url()->current()]
            );

            // 7. Check if the user has already reviewed and booked this movie
            if (Auth::check() && Auth::user()->role === 'customer') {
                $checkResponse = Http::get(env('REVIEW_API_URL') . "/reviews/user-has-reviewed/{$movie->id}/" . Auth::id());
                $checkResponse->throw();
                $alreadyReviewed = $checkResponse->json('exists', false);
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("Failed to fetch movie page data: " . $e->getMessage());
        }

        return [
            'movie' => $movie,
            'reviews' => $reviewsPaginator,
            'reviews_count' => $reviewCount,
            'reviews_avg_rating' => $reviewAvg,
            'already_reviewed' => $alreadyReviewed,
            'related_movies' => $relatedMovies,
            'movie_status' => $movieStatus,
            'movie_review_permission' => $movieReviewPermission,
            'movie_booking_permission' => $movieBookingPermission,
        ];
    }

    
    /** Call by HomeController 
     * 
     * Get data for the homepage, including popular and now showing movies with review stats.
     * This method orchestrates the data retrieval from local DB and the Reviews API.
     */
    public function getHomepageData()
    {
        // 1. Get the popular and now-showing movies from the database
        $popularMovies = Movie::where('is_popular', true)
            ->take(5)
            ->get();
        
        $activeListingMovies = Movie::whereIn('status', ['now_showing', 're_released'])
            ->orderBy('release_date', 'desc')
            ->take(6)
            ->get();
        
        // 2. Collect all the movie IDs from active listings
        $activeListingIds = $activeListingMovies->pluck('id')->implode(',');

        // 3. Use the Http facade to call the Reviews API for all IDs
        $reviewsApiUrl = env('REVIEW_API_URL') . '/reviews/summary';
        $reviewsData = collect();

        if (!empty($activeListingIds)) {
            try {
                $response = Http::get($reviewsApiUrl, ['ids' => $activeListingIds]);
                $response->throw();
                $reviewsData = collect($response->json())->keyBy('movie_id');
            } catch (RequestException $e) {
                Log::error("Failed to fetch movie reviews from API: " . $e->getMessage());
            }
        }
        
        // 4. Attach the review data to each movie
        $attachReviews = function ($movie) use ($reviewsData) {
            $summary = $reviewsData->get($movie->id);
            $movie->reviews_count = $summary['reviews_count'] ?? 0;
            $movie->reviews_avg_rating = round($summary['reviews_avg_rating'] ?? 0, 1);
            $movie->booking_allowed = $movie->getState()->isBookingAllowed($movie);
            return $movie;
        };

        $popularMovies->transform($attachReviews);
        $activeListingMovies->transform($attachReviews);

        // 5. Return the final data set
        return [
            'popular' => MovieResource::collection($popularMovies),
            'active_listings' => MovieResource::collection($activeListingMovies),
        ];
    }
}