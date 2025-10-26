<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Hall;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Services\Schedule\ScheduleQueryContext;
use App\Services\Schedule\Filters\FilterByHall;
use App\Services\Schedule\Filters\FilterByDateRange;
use App\Services\Schedule\Filters\FilterByAvailability;
use App\Services\Schedule\Sorts\SortByShowTime;
use App\Services\Schedule\Sorts\SortByAvailableSeats;

class ScheduleController extends Controller
{
    /**
     * Display the schedule of a movie, with the option to filter and sort by various criteria.
     */
    public function index(Request $request, $movieId)
    {
        // Fetch movie details through API calls
        try {
            $useApi = $request->query('use_api', false);

            if ($useApi) {
                // External HTTP call (to API routes)
                $response = Http::timeout(10)
                    ->get(config('services.movie.url') . "/{$movieId}/details");

                $response->throw(); // throws on 4xx/5xx
                $json = $response->json();
            } else {
                // Internal dispatch (to the same API route)
                $requestApi = Request::create("/api/movies/{$movieId}/details", 'GET');
                $requestApi->setUserResolver(fn() => auth()->user());

                $responseApi = Route::dispatch($requestApi);

                if ($responseApi->getStatusCode() !== 200) {
                    abort(404, 'Movie not found (internal).');
                }

                $json = json_decode($responseApi->getContent(), true);
            }

            // Attach the json data to movieData for new Movie object creation
            $movieData = $json['data'] ?? null;
            if (!$movieData) {
                abort(404, 'Movie data unavailable.');
            }

            // Create temporary Movie object
            $movie = new Movie($movieData);
            // Manually set ID since not from DB
            $movie->id = $movieId;
        } catch (\Throwable $e) {
            logger()->error("Failed to fetch movie {$movieId}: " . $e->getMessage());
            abort(404, 'Unable to fetch movie.');
        }

        // Existing validation + filtering logic
        $validated = $request->validate([
            'hall_id' => 'nullable|exists:halls,id',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'available' => 'nullable|boolean',
            'sort' => 'nullable|in:show_time_asc,show_time_desc,available_desc,available_asc,popular_desc',
            'date' => 'nullable|date|after_or_equal:today',
            'schedule_id' => 'nullable|exists:schedules,id',
        ]);

        // Base query (use movieId instead of $movie->id)
        $query = Schedule::with('hall')
            ->where('movie_id', $movieId)
            ->where('show_time', '>=', Carbon::today());

        // Apply filters
        $strategies = [];

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
        $schedules = $context->apply()->get();

        $dates = $schedules->groupBy(fn($s) => Carbon::parse($s->show_time)->format('Y-m-d'))->keys();
        $selectedDate = $validated['date'] ?? $dates->first() ?? Carbon::today()->format('Y-m-d');

        $showtimesForDate = $schedules
            ->filter(fn($s) => Carbon::parse($s->show_time)->format('Y-m-d') === $selectedDate)
            ->sortBy('show_time')
            ->values();

        $selectedScheduleId = $validated['schedule_id'] ?? null;
        $selectedSchedule = $selectedScheduleId ? Schedule::find($selectedScheduleId) : null;

        $halls = Hall::all();

        $filters = collect($validated)->only([
            'hall_id',
            'start_date',
            'end_date',
            'available',
            'sort',
        ])->toArray();

        // Return with Movie object built from API
        return view('schedules.index', compact(
            'movie',
            'dates',
            'selectedDate',
            'showtimesForDate',
            'selectedScheduleId',
            'selectedSchedule',
            'halls',
            'filters'
        ));
    }

    // Provider: Return all schedules (basic fields only)
    public function getAllSchedules(Request $request)
    {
        $schedules = Schedule::query()
            ->orderBy('show_time')
            ->get(['id', 'movie_id', 'hall_id', 'show_time']);

        return response()->json([
            'data' => $schedules
        ]);
    }

    // Provider: Return a single schedule by ID
    public function getScheduleById($id)
    {
        $schedule = Schedule::findOrFail($id, ['id', 'movie_id', 'hall_id', 'show_time']);

        return response()->json($schedule);
    }

    // Provider: Return seats for a given schedule
    public function getSeatsBySchedule($scheduleId)
    {
        $seats = DB::table('schedule_seats')
            ->join('seats', 'schedule_seats.seat_id', '=', 'seats.id')
            ->where('schedule_id', $scheduleId)
            // Select the seat ID and alias the pivot ID.
            ->select(
                'seats.id', // This is the real seat ID.
                'schedule_seats.id as pivot_id', // This is the ID for the schedule pivot table.
                'schedule_seats.status',
                'seats.row_char',
                'seats.seat_number'
            )
            ->get();

        // Manually create the 'name' attribute for each seat object
        $seatsWithNames = $seats->map(function ($seat) {
            $seat->name = "{$seat->row_char}{$seat->seat_number}";
            return $seat;
        });

        return response()->json(['data' => $seatsWithNames]);
    }

}
