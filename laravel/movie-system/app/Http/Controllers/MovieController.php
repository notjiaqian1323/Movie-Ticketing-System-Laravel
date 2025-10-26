<?php
namespace App\Http\Controllers;

//Name: HO YI VON
//Student ID : 23WMR14542

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Services\MovieService;

class MovieController extends Controller
{
    private MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Display a listing of movies for the public website with filters.
     * Delegates data fetching to the MovieService.
     */
    public function listing(Request $request)
    {
        // 1. Delegate the entire data-fetching task to the MovieService.
        // The service will handle the filtering, pagination, and fetching of review summaries.
        $data = $this->movieService->getMoviesList($request->all());

        // 2. pass the data to the view.
        return view('movies.listing', [
            'movies' => $data['movies'],
            'genres' => $data['genres'],
        ]);
    }

    /**
     * Display a specific movie's details page.
     * Fetches movie details, reviews, and related movies using the MovieService.
     */
    public function show(Movie $movie, Request $request)
    {
        // Delegate all data fetching for the single movie page to the MovieService
        $data = $this->movieService->getMoviePageData($movie, $request->input('per_page', 8));

        return view('movies.show', [
            'movie' => $data['movie'],
            'reviewsWithUsers' => $data['reviews'],
            'reviews_count' => $data['reviews_count'],
            'reviews_avg_rating' => round($data['reviews_avg_rating'], 1),
            'already' => $data['already_reviewed'],
            'relatedMovies' => $data['related_movies'],
            'movieStatus' => $data['movie_status'],
            'movieReviewPermission' => $data['movie_review_permission'],
            'movieBookingPermission' => $data['movie_booking_permission'],
        ]);
    }

}