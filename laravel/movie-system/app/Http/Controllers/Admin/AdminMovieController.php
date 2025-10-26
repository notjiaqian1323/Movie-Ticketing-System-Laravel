<?php
namespace App\Http\Controllers\Admin;

//Name: HO YI VON
//Student ID : 23WMR14542

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AdminMovieController extends Controller
{

    /**
     * Display a listing of movies with filters (title, genre, status, popular).
     */
    public function index(Request $request)
    {
        // Ensure only admin can access
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $userRole = Auth::user()->role;

        // Start query builder
        $query = Movie::query();

        // Apply filters
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('popular')) {
            $query->where('is_popular', $request->popular);
        }

        if ($request->filled('sort')) {
            $query->orderBy('title', $request->sort);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $movies = $query->paginate(10)->withQueryString();

        return view('admin.movies.panel', compact('movies', 'userRole'));
    }

    /**
     * Show the form for creating a new movie.
     */
    public function create()
    {
        $userRole = Auth::user()->role;
        return view('admin.movies.create', compact('userRole'));
    }

    /**
     * Store a newly created movie in the database.
     * Handles validation and optional image upload.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'genre' => 'required|string|max:50',
            'director' => 'required|string|max:255',
            'cast' => 'required|string|max:1000',
            'synopsis' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:1|max:500',
            'language' => 'nullable|string|max:50',
            'subtitles' => 'nullable|string|max:50',
            'age_rating' => 'nullable|string|max:10',
            'status' => 'required|in:coming_soon,now_showing,archived,re_released',
            'release_date' => 'required|date',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2mb
        ]);

        try {
            if ($request->hasFile('image_path')) {
                $validated['image_path'] = $this->uploadImage($request->file('image_path'), 'movies');
            }

            $validated['is_popular'] = $request->has('is_popular');

            Movie::create($validated);
            return redirect()->route('admin.movies.panel')->with('success', 'Movie added successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating movie: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add movie. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified movie.
     */
    public function edit(Movie $movie)
    {
        $userRole = Auth::user()->role;
        return view('admin.movies.edit', compact('movie', 'userRole'));
    }

    /**
     * Update the specified movie in the database.
     * Handles validation and optional image replacement.
     */
    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'genre' => 'required|string|max:50',
            'director' => 'required|string|max:255',
            'cast' => 'required|string|max:1000',
            'synopsis' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:1|max:500',
            'language' => 'nullable|string|max:50',
            'subtitles' => 'nullable|string|max:50',
            'age_rating' => 'nullable|string|max:10',
            'status' => 'required|in:coming_soon,now_showing,archived,re_released',
            'release_date' => 'required|date',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            if ($request->hasFile('image_path')) {
                if ($movie->image_path && Storage::disk('public')->exists('movies/' . $movie->image_path)) {
                    Storage::disk('public')->delete('movies/' . $movie->image_path);
                }
                $validated['image_path'] = $this->uploadImage($request->file('image_path'), 'movies');
            }

            $validated['is_popular'] = $request->has('is_popular');

            $movie->update($validated);
            return redirect()->route('admin.movies.panel')->with('success', 'Movie updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating movie: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update movie. Please try again.');
        }
    }

    // public function destroy(Movie $movie)
    // {
    //     try {
    //         if ($movie->image_path && Storage::disk('public')->exists('movies/' . $movie->image_path)) {
    //             Storage::disk('public')->delete('movies/' . $movie->image_path);
    //         }
    //         $movie->delete();
    //         return redirect()->route('admin.movies.panel')->with('success', 'Movie deleted successfully.');
    //     } catch (\Exception $e) {
    //         Log::error('Error deleting movie: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Failed to delete movie. Please try again.');
    //     }
    // }

    /**
     * Mark a movie as active (add to active listing).
     */
    public function activate(Movie $movie)
    {
        try {
            $message = $movie->addToActiveListing();
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark a movie as inactive (remove from active listing).
     */
    public function deactivate(Movie $movie)
    {
        try {
            $message = $movie->removeFromActiveListing();
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Add a movie to the popular list.
     */
    public function addToPopular(Movie $movie)
    {
        try {
            $message = $movie->addToPopularList();
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove a movie from the popular list.
     */
    public function removeFromPopular(Movie $movie)
    {
        try {
            $message = $movie->removeFromPopularList();
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle image upload and store in the given folder.
     * Returns the stored filename.
     */
    private function uploadImage($file, $folder = 'movies')
    {
        Log::info('Uploaded file mime type: ' . $file->getClientMimeType());

        if ($file->isValid()) {
            // Save to storage/app/public/movies (or other folder)
            $path = $file->store($folder, 'public');
            $fullPath = storage_path('app/public/' . $path);
            Log::info('Image uploaded to: ' . $fullPath);

            if (!file_exists($fullPath)) {
                Log::error('File not found after upload at: ' . $fullPath);
            }

            return basename($path); // returns only the filename
        } else {
            Log::error('Invalid image file: ' . $file->getErrorMessage());
            throw new \Exception('Invalid image file.');
        }
    }

    /**
     * API for schedule module
     * 
     * API Provider: Return all now_showing movies (id + title).
     * Supports optional search by title.
     */
    public function getNowShowingMoviesTitle(Request $request)
    {
    $movies = Movie::query()
        ->whereIn('status', ['now_showing', 're_released']) // allow both statuses
        ->orderBy('title')
        ->get(['id', 'title', 'status']); // include status in the result

    return response()->json([
        'data' => $movies
        ]);
    }


    /**
     * API Provider: Return movie duration by movie ID.
     */
    public function getMovieDurationById($id)
    {
        $movie = Movie::findOrFail($id, ['id', 'duration']);

        return response()->json($movie);
    }
    
    /**
     * API Provider: Return all movies with id + title only.
     */
    public function getAllTitles()
    {
        $movies = Movie::all(['id', 'title']);
        return response()->json([
            'data' => $movies
        ]);
    }
}