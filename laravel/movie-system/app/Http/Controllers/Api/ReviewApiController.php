<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use App\Services\ReviewService; 


class ReviewApiController extends Controller
{
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * API: Get all reviews for a specific movie (show).
     */
    public function getMovieReviews(Request $request, int $movieId)
    {
        $perPage = (int) $request->input('per_page', 8);
        $reviewsData = $this->reviewService->getReviewsForMovie($movieId, $perPage);

        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($reviewsData['reviews']),
            'meta' => [
                'total' => $reviewsData['reviews']->total(),
                'per_page' => $reviewsData['reviews']->perPage(),
                'current_page' => $reviewsData['reviews']->currentPage(),
                'reviews_count' => $reviewsData['reviews_count'],
                'reviews_avg_rating' => $reviewsData['reviews_avg_rating'],
            ],
        ]);
    }


    /**
     * API: Get review summaries for multiple movies based on a list of IDs.
     * This is the endpoint that the MovieService will call.
     */
    public function getMovieReviewsSummaryForIds(Request $request)
    {
        // Get the movie IDs from the query string (e.g., ?ids=1,2,3)
        $movieIds = explode(',', $request->query('ids', ''));

        // Use the service to get the data for the given IDs
        $summaries = $this->reviewService->getMovieReviewsSummary($movieIds);

        // Return a JSON response with the collection of summaries
        return response()->json($summaries->values()->toArray());
    }

    /**
     * API: Check if a user has reviewed a movie.
     * This is a new, dedicated endpoint for the check.
     */
    public function userHasReviewed(Request $request, int $movieId, int $accountId)
    {
        $hasReviewed = $this->reviewService->userHasReviewed($movieId, $accountId);

        return response()->json([
            'success' => true,
            'exists' => $hasReviewed,
        ]);
    }
}