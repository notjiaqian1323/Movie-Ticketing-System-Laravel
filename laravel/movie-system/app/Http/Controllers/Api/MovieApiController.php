<?php

//Name: HO YI VON
//Student ID : 23WMR14542

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Http\Resources\MovieResource;
use App\Services\MovieService; 

class MovieApiController extends Controller
{
    private MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /** API for review module
     * 
     * API Provider: Get movie information for a list of IDs.
     * Used for review history
     */
    public function getMovieInfoForIds(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        
        // This controller's job is to fulfill the request by querying its local database.
        $movies = Movie::whereIn('id', $ids)->get();

        return MovieResource::collection($movies);
    }

    /** API for booking module
     * 
     * API Provider: Get specific movie details by ID.
     * Returns a single movie details that display on schedule
     */
    public function getMovieDetailsById($id)
    {
        $movie = Movie::findOrFail($id, [
            'id',
            'title',
            'genre',
            'duration',
            'language',
            'image_path'
        ]);

        return response()->json([
            'data' => $movie
        ]);
    }
}


