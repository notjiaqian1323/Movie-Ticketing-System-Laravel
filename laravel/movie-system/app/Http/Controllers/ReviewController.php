<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ReviewService;

use Illuminate\Database\QueryException;
use App\Http\Requests\ReviewStoreRequest;
use App\Http\Requests\ReviewUpdateRequest;
use App\Http\Resources\ReviewResource;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as BaseController; 

/**
 * @mixin BaseController
 */
class ReviewController extends Controller
{
    /**
     * How long after creation the user can edit (in hours)
     */
    private int $editWindowHours = 24;
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        // Require authentication for all methods in this controller
        $this->middleware('auth');
        $this->reviewService = $reviewService;
    }

/**
     * Show all reviews for a movie.
     */
    public function index(Movie $movie, Request $request)
    {
        $perPage = (int) $request->input('per_page', 8);
        $reviewsData = $this->reviewService->getReviewsForMovie($movie->id, $perPage);

        return response()->json([
            'success' => true,
            'movie_id' => $movie->id,
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
     * Store a new review (POST /movies/{movie}/reviews)
     * No change here, as it's direct database interaction for a create action.
     */
    public function store(ReviewStoreRequest $request, Movie $movie)
    {
        $accountId = Auth::id();
        $data = $request->validated();

        if (!$this->userWatched($movie->id, $accountId)) {
            return redirect()
                ->route('movies.show', $movie->id)
                ->with('error', 'You can only review movies you have watched.');
        }

        try {
            $review = Review::create([
                'movie_id' => $movie->id,
                'account_id' => $accountId,
                'rating' => (int) $data['rating'],
                'comment' => $data['comment'] ?? null,
                'is_anonymous' => (bool) ($data['is_anonymous'] ?? false),
                'edited' => false,
                'review_datetime' => now(),
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()
                    ->route('movies.show', $movie->id)
                    ->with('error', 'You have already reviewed this movie.');
            }
            return redirect()
                ->route('movies.show', $movie->id)
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('movies.show', $movie->id)
            ->with('success', 'Thanks for your review!');
    }

    /**
     * Edit form
     */
    public function edit(Review $review)
    {
        $this->authorizeOwnerOrAbort($review);
        $this->authorizeEditWindowOrAbort($review);
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update an existing review (PUT /reviews/{review})
     */
    public function update(ReviewUpdateRequest $request, Review $review)
    {
        $this->authorizeOwnerOrAbort($review);
        $this->authorizeEditWindowOrAbort($review);

        $data = $request->validated();

        $review->update([
            'rating' => (int) $data['rating'],
            'comment' => $data['comment'] ?? null,
            'is_anonymous' => $request->boolean('is_anonymous'),
        ]);

        $returnTo = $request->input('return_to', route('reviews.history'));
        return redirect($returnTo)->with('success', 'Review updated.');
    }

    /**
     * Delete a review (DELETE /reviews/{review})
     */
    public function destroy(Request $request, Review $review)
    {
        $this->authorizeOwnerOrAbort($review);

        $review->delete();

        $returnTo = $request->input('return_to', route('reviews.history'));
        return redirect($returnTo)->with('success', 'Review deleted.');
    }

    /**
     * "MyReview" history page
     */
    public function myReviews(Request $request)
    {
        $sort = $request->query('sort', 'new');
        $accountId = Auth::id();
        $perPage = 10;
        
        $data = $this->reviewService->getUserReviews($accountId, $sort, $perPage);
        
        return view('reviews.history', [
            'reviews' => $data['reviews'],
            'movies' => $data['movies'], 
            'sort' => $sort,
        ]);
    }

    // -----------------------
    // Helpers
    // -----------------------

    private function authorizeOwnerOrAbort(Review $review): void
    {
        abort_unless(Auth::check() && (int)$review->account_id === (int)Auth::id(), 403);
    }

    private function authorizeEditWindowOrAbort(Review $review): void
    {
        $base = $review->review_datetime ?? $review->created_at ?? now();
        abort_unless(now()->diffInHours($base) <= $this->editWindowHours, 403);
    }

    /**
     * This logic should also ideally be in a service.
     */

    private function userWatched(int $movieId, int $accountId): bool
    {
        return $this->reviewService->userHasBooked($movieId, $accountId);
    }
}