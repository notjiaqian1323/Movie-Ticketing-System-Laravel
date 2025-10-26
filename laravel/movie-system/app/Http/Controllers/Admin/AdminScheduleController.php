<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Http\Controllers\Admin;

use App\Models\Schedule;
use App\Models\Hall;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Services\Schedule\ScheduleQueryContext;
use App\Services\Schedule\Filters\FilterByHall;
use App\Services\Schedule\Filters\FilterByDateRange;
use App\Services\Schedule\Filters\FilterByAvailability;
use App\Services\Schedule\Filters\FilterByMovie;
use App\Services\Schedule\Sorts\SortByShowTime;
use App\Services\Schedule\Sorts\SortByAvailableSeats;
use Illuminate\Http\Client\RequestException;

class AdminScheduleController extends Controller
{
    // Admin: view all schedules with sort and filter using Strategy Design Pattern
    public function index(Request $request)
    {
        $movieTitleMap = collect(); // default empty
        $movieError = null; // store error message for view

        // Fetch movies via API 
        try {
            // Auto-detect: if request has 'use_api' query param,
            $useApi = $request->query('use_api', false);

            if ($useApi) {
                // External API consumption (shared token)
                $response = Http::withToken(config('services.movie_admin.token'))
                    ->timeout(10)
                    ->get(config('services.movie_admin.base_url') . '/movies/titles');

                $response->throw();

                $moviesData = collect($response->json('data') ?? []);
                $movieTitleMap = $moviesData->pluck('title', 'id');
            } else {
                // Internal API consumption
                $responseApi = $this->internalApi('api/admin/movies/titles', 'GET');

                if ($responseApi->getStatusCode() === 200) {
                    $moviesJson = json_decode($responseApi->getContent(), true);
                    $moviesData = collect($moviesJson['data'] ?? []);
                    $movieTitleMap = $moviesData->pluck('title', 'id');
                } else {
                    $movieError = 'Failed to fetch movies list.';
                }
            }
        } catch (RequestException $e) {
            Log::error("Failed to fetch movies from Movie API: " . $e->getMessage());
            $movieError = 'Failed to fetch movies list.';
        } catch (\Throwable $e) {
            $movieError = 'Error fetching movies: ' . $e->getMessage();
        }

        // Fetch halls directly from DB
        $hallMap = Hall::where('status', 'active')->pluck('hall_name', 'id');

        // Validation
        $validated = $request->validate([
            'hall_id' => 'nullable|exists:halls,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'available' => 'nullable|boolean',
            'sort' => 'nullable|in:show_time_asc,show_time_desc,available_desc,available_asc',
            'movie_id' => 'nullable|integer|exists:movies,id',
        ]);

        // Base query (Filter)
        $query = Schedule::with('hall');
        $strategies = [];

        if (!empty($validated['movie_id'])) {
            $strategies[] = new FilterByMovie((int) $validated['movie_id']);
        }

        if (!empty($validated['hall_id'])) {
            $strategies[] = new FilterByHall((int) $validated['hall_id']);
        }

        if (!empty($validated['start_date']) || !empty($validated['end_date'])) {
            $strategies[] = new FilterByDateRange(
                $validated['start_date'] ?? null,
                $validated['end_date'] ?? null
            );
        }

        if (!empty($validated['available'])) {
            $strategies[] = new FilterByAvailability(true);
        }

        // Sorting
        switch ($validated['sort'] ?? 'show_time_asc') {
            case 'show_time_desc':
                $strategies[] = new SortByShowTime('desc');
                break;
            case 'available_desc':
                $strategies[] = new SortByAvailableSeats('desc');
                break;
            case 'available_asc':
                $strategies[] = new SortByAvailableSeats('asc');
                break;
            default:
                $strategies[] = new SortByShowTime('asc');
                break;
        }

        $context = new ScheduleQueryContext($query, $strategies);
        $schedules = $context->apply()->paginate(10);

        // Map movie_id → title (fallback to "Unknown Movie")
        $schedules->getCollection()->transform(function ($schedule) use ($movieTitleMap) {
            $schedule->movie_title = $movieTitleMap[$schedule->movie_id] ?? 'Unknown Movie';
            return $schedule;
        });

        // Pass movieError, hallMap and movieTitleMap to the view
        return view('admin.schedules.panel', compact('schedules', 'movieError', 'movieTitleMap', 'hallMap'));
    }

    // Go to create schedule form
    public function create(Request $request)
    {
        // Fetch halls directly from DB (no API)
        $halls = Hall::where('status', 'active')->get();

        // Fetch movies via API (backend)
        $movies = collect();
        $movieError = null;

        try {
            // Auto-detect: if request has 'use_api' query param
            $useApi = $request->query('use_api', false);

            if ($useApi) {
                // External API consumption
                $response = Http::withToken(config('services.movie_admin.token'))
                    ->timeout(10)
                    ->get(config('services.movie_admin.base_url') . '/movies');

                $response->throw();

                $moviesData = collect($response->json('data') ?? []);
                $movies = $moviesData->pluck('title', 'id'); // id => title
            } else {
                // Internal API consumption
                $responseApi = $this->internalApi('api/admin/movies', 'GET');

                if ($responseApi->getStatusCode() === 200) {
                    $moviesJson = json_decode($responseApi->getContent(), true);
                    $moviesData = collect($moviesJson['data'] ?? []);
                    $movies = $moviesData->pluck('title', 'id');
                } else {
                    $movieError = 'Failed to fetch movies list.';
                }
            }
        } catch (RequestException $e) {
            Log::error("Failed to fetch movies from Movie API: " . $e->getMessage());
            $movieError = 'Failed to fetch movies list.';
        } catch (\Throwable $e) {
            $movieError = 'Error fetching movies: ' . $e->getMessage();
        }

        return view('admin.schedules.create', compact('halls', 'movies', 'movieError'));
    }

    // Store new schedule
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|integer',
            'hall_id' => 'required|exists:halls,id',
            'show_date' => 'required|date',
            'show_time_select' => 'required|date_format:H:i',
        ]);

        // Fetch movie info (external or internal)
        try {
            $useApi = $request->query('use_api', false);

            if ($useApi) {
                // External API consumption
                $response = Http::withToken(config('services.movie_admin.token'))
                    ->timeout(10)
                    ->get(config('services.movie_admin.base_url') . "/movies/{$validated['movie_id']}");

                $response->throw();

                $movie = $response->json();
            } else {
                // Internal API consumption
                $responseApi = $this->internalApi("api/admin/movies/{$validated['movie_id']}", 'GET');

                if ($responseApi->getStatusCode() === 200) {
                    $movie = json_decode($responseApi->getContent(), true);
                } else {
                    return back()->withErrors(['movie_id' => 'Failed to fetch movie details.']);
                }
            }
        } catch (RequestException $e) {
            Log::error("Failed to fetch movie details from Movie API: " . $e->getMessage());
            return back()->withErrors(['movie_id' => 'Failed to fetch movie details.']);
        } catch (\Throwable $e) {
            return back()->withErrors(['movie_id' => 'Error fetching movie: ' . $e->getMessage()]);
        }

        if (empty($movie['duration'])) {
            return back()->withInput()->with('error', 'Movie duration not available for validation.');
        }

        $durationMinutes = (int) $movie['duration'];

        // Build new schedule start + end
        $showStart = Carbon::createFromFormat('Y-m-d H:i', $validated['show_date'] . ' ' . $validated['show_time_select']);
        $showEnd = (clone $showStart)->addMinutes($durationMinutes + 60); // movie duration + 1h buffer (60 minutes)

        if ($this->hasScheduleConflict($validated['hall_id'], $showStart, $showEnd)) {
            return back()
                ->withInput()
                ->with('error', 'This hall already has a schedule during that time. Please leave at least 1 hour gap after the last movie.');
        }

        // No conflict, create schedule
        $schedule = Schedule::create([
            'movie_id' => $validated['movie_id'],
            'hall_id' => $validated['hall_id'],
            'show_time' => $showStart,
        ]);

        // Create schedule_seats rows
        $hall = Hall::with('seats')->find($validated['hall_id']);
        $now = Carbon::now();
        $rows = [];

        foreach ($hall->seats as $seat) {
            $rows[] = [
                'schedule_id' => $schedule->id,
                'seat_id' => $seat->id,
                'status' => 'available', // set all seats to available initially
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('schedule_seats')->insert($rows);

        return redirect()->route('admin.schedules.panel')->with('success', 'Schedule created successfully.');
    }

    // Go to edit schedule form
    public function edit(Request $request, Schedule $schedule)
    {
        // Fetch halls directly from DB
        $halls = Hall::where('status', 'active')->get();

        // Fetch movie title (internal or external)
        $movieTitle = 'Unknown Movie';

        try {
            $useApi = $request->query('use_api', false); // auto-detect query param

            if ($useApi) {
                // External API consumption
                $response = Http::withToken(config('services.movie_admin.token'))
                    ->timeout(10)
                    ->get(config('services.movie_admin.base_url') . "/movies/titles");

                $response->throw();

                $moviesData = collect($response->json('data') ?? []);
            } else {
                // Internal API consumption
                $responseApi = $this->internalApi("api/admin/movies/titles", 'GET');

                if ($responseApi->getStatusCode() === 200) {
                    $moviesJson = json_decode($responseApi->getContent(), true);
                    $moviesData = collect($moviesJson['data'] ?? []);
                } else {
                    $moviesData = collect();
                }
            }

            // Find the matching movie by ID
            $movie = $moviesData->firstWhere('id', $schedule->movie_id);
            if (!empty($movie['title'])) {
                $movieTitle = $movie['title'];
            }
        } catch (\Throwable $e) {
            Log::error("Failed to fetch movie title for schedule {$schedule->id}: " . $e->getMessage());
        }

        return view('admin.schedules.edit', compact('schedule', 'halls', 'movieTitle'));
    }

    // Update existing schedule 
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'movie_id' => 'required|integer',
            'hall_id' => 'required|exists:halls,id',
            'show_date' => 'required|date',
            'show_time_select' => 'required|date_format:H:i',
        ]);

        // Enforce date >= tomorrow
        $tomorrow = Carbon::tomorrow();
        if (Carbon::parse($validated['show_date'])->lt($tomorrow)) {
            return back()
                ->withInput()
                ->with('error', 'Show date must be tomorrow or later.');
        }

        // Fetch movie info (internal or external)
        try {
            $useApi = $request->query('use_api', false); // auto-detect query param
            $movie = [];

            if ($useApi) {
                // External API consumption
                $response = Http::withToken(config('services.movie_admin.token'))
                    ->timeout(10)
                    ->get(config('services.movie_admin.base_url') . "/movies/{$validated['movie_id']}");

                $response->throw();

                $movie = $response->json();
            } else {
                // Internal API consumption
                $responseApi = $this->internalApi("api/admin/movies/{$validated['movie_id']}", 'GET');

                if ($responseApi->getStatusCode() === 200) {
                    $movie = json_decode($responseApi->getContent(), true);
                } else {
                    return back()->withErrors(['movie_id' => 'Failed to fetch movie details.']);
                }
            }
        } catch (RequestException $e) {
            Log::error("Failed to fetch movie details: " . $e->getMessage());
            return back()->withErrors(['movie_id' => 'Failed to fetch movie details.']);
        } catch (\Throwable $e) {
            return back()->withErrors(['movie_id' => 'Error fetching movie: ' . $e->getMessage()]);
        }

        if (empty($movie['duration'])) {
            return back()->withInput()->with('error', 'Movie duration not available for validation.');
        }

        $durationMinutes = (int) $movie['duration'];

        // Build new schedule start + end
        $showStart = Carbon::createFromFormat('Y-m-d H:i', $validated['show_date'] . ' ' . $validated['show_time_select']);
        $showEnd = (clone $showStart)->addMinutes($durationMinutes + 60); // movie duration + 1h buffer (60 minutes)

        // Check for conflicts, excluding the current schedule
        if ($this->hasScheduleConflict($validated['hall_id'], $showStart, $showEnd, $schedule->id)) {
            return back()
                ->withInput()
                ->with('error', 'This hall already has a schedule during that time. Please leave at least 1 hour gap after the last movie.');
        }

        // Update schedule
        $schedule->update([
            'movie_id' => $validated['movie_id'],
            'hall_id' => $validated['hall_id'],
            'show_time' => $showStart,
        ]);

        return redirect()->route('admin.schedules.panel')->with('success', 'Schedule updated successfully.');
    }

    // Validation function for schedule conflicts
    private function hasScheduleConflict($hallId, Carbon $start, Carbon $end, $ignoreId = null)
    {
        $query = Schedule::where('hall_id', $hallId)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('show_time', [$start, $end])
                    ->orWhereRaw('? BETWEEN show_time AND DATE_ADD(show_time, INTERVAL 3 HOUR)', [$start]);
            });

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    // Helper: internal API call with Sanctum user
    private function internalApi(string $uri, string $method = 'GET')
    {
        $requestApi = Request::create($uri, $method);

        // Attach Sanctum Bearer token so auth:sanctum works
        $requestApi->headers->set('Authorization', 'Bearer ' . env('INTERNAL_API_TOKEN'));

        return Route::dispatch($requestApi);
    }
}
