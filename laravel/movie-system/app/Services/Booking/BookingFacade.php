<?php
//Name: Wo Jia Qian
//Student Id: 2314023

namespace App\Services\Booking;
use Illuminate\Support\Facades\Log;

use App\Models\Movie;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB; // For database transactions
use Exception; // For error handling
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use Illuminate\Database\Eloquent\Builder;

class BookingFacade
{

    public function getSchedulesWithHallData($movieId)
    {
        // Create a new Movie object (not from DB)
        $movie = Movie::findOrFail($movieId);

        return $movie->schedules()
                        ->with('hall')
                        ->orderBy('show_time')
                        ->get();
        // return $movie;
    }

    public function getMovieDetailsForBooking($movieId){
        // Call API internally to fetch movie details
        $requestApi = Request::create("/api/movies/{$movieId}/details", 'GET');

        $requestApi->setUserResolver(function () {
            return auth()->user();
        });

        $responseApi = Route::dispatch($requestApi);

        if ($responseApi->getStatusCode() !== 200) {
            abort(404, 'Movie not found.');
        }

        $json = json_decode($responseApi->getContent(), true);
        $movieData = $json['data'] ?? null;

        if (!$movieData) {
            abort(404, 'Movie data unavailable.');
        }

        return $movieData;
    }

    /**
     * Finalizes the booking process by reserving seats and creating a new booking record.
     * This operation is atomic: either all seats are reserved and booking created, or none.
     *
     * @param Schedule $schedule The schedule for which to reserve seats (already retrieved).
     * @param array $seatIds An array of seat IDs selected by the user.
     * @param array $ticketTypes An associative array mapping seat ID to ticket type (e.g., [1 => 'ADULT']).
     * @return Booking The newly created Booking model instance.
     * @throws Exception If seats are unavailable or a transaction fails.
     */
    public function finalizeBooking(Schedule $schedule, array $seatIds, array $ticketTypes, int $accountId)
    {
        Log::info('BookingFacade: finalizeBooking method called. Starting database transaction.');
        Log::debug('BookingFacade: Seat IDs to book: ', $seatIds);
        Log::debug('BookingFacade: Account ID to associate: ' . $accountId);

        DB::beginTransaction();

        try {
            // Step 1: Check seat availability and lock the rows to prevent race conditions.
            // --- FIX: Query the pivot table directly using the IDs sent from the front end.

            // Find and lock the selected seats for update
            $availableSeats = $schedule->seats()
                ->whereIn('seats.id', $seatIds)
                ->wherePivot('status', 'available')
                ->lockForUpdate()
                ->get();
            
            Log::debug('BookingFacade: Found ' . $availableSeats->count() . ' available seats.');

            
            
            //dd($availableSeats, $seatIds);
            //dd($availableSeats->pluck('pivot.seat_id'), $seatIds);

            if ($availableSeats->count() !== count($seatIds)) {
                Log::error('BookingFacade: Mismatch in seat count. Requested: ' . count($seatIds) . ', Found Available: ' . $availableSeats->count());
                throw new Exception('One or more selected seats are no longer available.');
            }

            // Step 2: Calculate the total price based on the ticket types.
            $totalAmount = 0;
            $ticketPrices = [
                'adult' => 18.00,
                'child' => 12.00,
                'oku' => 10.00,
            ];

            foreach ($ticketTypes as $seatId => $type) {
                $totalAmount += $ticketPrices[strtolower($type)] ?? 0;
            }
            Log::debug('BookingFacade: Calculated total amount: ' . $totalAmount);

            // Step 3: Create the main booking record with a 'confirmed' status.
            $booking = Booking::create([
                'account_id' => $accountId, // Use the provided account ID
                'schedule_id' => $schedule->id,
                'booking_time' => now(),
                'total_amount' => $totalAmount,
                'qr_code' => uniqid('QR_'),
                'status' => 'confirmed',
            ]);
            Log::debug('BookingFacade: Booking record created with ID: ' . $booking->id);

            // Step 4: Attach each seat to the booking and update the pivot status.
            // --- FIX: Iterate over the $availableSeats collection to get the correct Seat ID.
            foreach ($availableSeats as $seat) {

                $ticketType = $ticketTypes[$seat->id]; // Get the ticket type using the Seat ID.

                $booking->bookingSeats()->create([
                    'seat_id' => $seat->id,
                    'ticket_type' => $ticketType,
                    'price' => $ticketPrices[strtolower($ticketType)] ?? 0,
                ]);
                Log::debug('BookingFacade: Booking seat record created for seat ID: ' . $seat->id);

                $schedule->seats()->updateExistingPivot($seat->id, ['status' => 'booked']);

                Log::debug('BookingFacade: Updated pivot status for seat ID: ' . $seat->id . ' to "booked".');

            }

            DB::commit();
            Log::info('BookingFacade: Database transaction committed. Booking finalized.');

            Log::info("Booking confirmed and finalized.", [
                'bookingId' => $booking->id,
                'scheduleId' => $schedule->id,
            ]);

            return $booking;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("BookingFacade: Database transaction rolled back. Error: {$e->getMessage()}");
            throw $e;
        }
    }

    public function getBookingsForUser($account_id){
        return Booking::where('account_id', $account_id)
                ->with(['schedule.movie', 'bookingSeats.seat'])
                ->get();
    }

    public function getConfirmedBookingsForUser($account_id){
        return Booking::where('account_id', $account_id)
            ->where('status', 'confirmed') // This is the new line.
            ->with(['schedule.movie', 'bookingSeats.seat'])
            ->get();
    }

    public function getTotalRevenueForAllBookings(){
        return Booking::sum('total_amount');
    }

    public function getTotalNumberOfBookings(){
        return Booking::count();
    }

    public function getAllBookings(){
        return Booking::with('account', 'schedule.movie');
    }

    public function filterAndSortingBookings(Request $request, $allBookings){

        if ($request->filled('movie_id')) {
            $allBookings->whereHas('schedule', function ($q) use ($request) {
                $q->where('movie_id', $request->movie_id);
            });
        }

        // Filter by specific user ID
        if ($request->filled('user_id')) {
            $allBookings->where('account_id', $request->user_id);
        }

        // Filter and sort by total amount, which requires a join and group by.
        // We only do this if a price filter or a price sort is requested.
        $totalAmountFilterOrSort = $request->filled('min_amount') ||
                                   $request->filled('max_amount') ||
                                   in_array($request->get('sort'), ['total_amount_asc', 'total_amount_desc']);

        if ($totalAmountFilterOrSort) {
            // Use the bookings table to get the IDs and total amounts.
            // This avoids the 'ONLY_FULL_GROUP_BY' error.
            $subQuery = DB::table('bookings')
                ->select('bookings.id')
                ->selectRaw('SUM(booking_seats.price) as total_amount')
                ->join('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id')
                ->groupBy('bookings.id');

            // Apply filters to the subquery
            if ($request->filled('min_amount')) {
                $subQuery->having('total_amount', '>=', $request->min_amount);
            }
            if ($request->filled('max_amount')) {
                $subQuery->having('total_amount', '<=', $request->max_amount);
            }

            // Apply sort to the subquery
            if ($request->get('sort') === 'total_amount_asc') {
                $subQuery->orderBy('total_amount', 'asc');
            } elseif ($request->get('sort') === 'total_amount_desc') {
                $subQuery->orderBy('total_amount', 'desc');
            }

            // Get the ordered list of IDs
            $bookingIds = $subQuery->pluck('id')->toArray();
            $idString = implode(',', $bookingIds);

            // Fetch the full booking models using the ordered IDs
            $allBookings->whereIn('id', $bookingIds)
                  ->orderByRaw("FIELD(id, $idString)");

        } else {
            // Standard filtering when not sorting by total amount
            if ($request->filled('movie_id')) {
                $allBookings->whereHas('schedule', function ($q) use ($request) {
                    $q->where('movie_id', $request->movie_id);
                });
            }
            if ($request->filled('user_id')) {
                $allBookings->where('account_id', $request->user_id);
            }
            
            // Apply standard sorting
            switch ($request->get('sort')) {
                case 'booking_date_asc':
                    $allBookings->orderBy('created_at', 'asc');
                    break;
                case 'booking_date_desc':
                    $allBookings->orderBy('created_at', 'desc');
                    break;
                // Default sort by latest booking if no sort option is selected
                default:
                    $allBookings->orderBy('created_at', 'desc');
                    break;
            }
        }

        return $allBookings;

    }

    public function isThisMovieBookedByUser($movieId, $account_id):bool
    {
        // We use whereHas to check for a relationship constraint.
        // In this case, we check the 'schedule' relationship
        // to see if the related schedule's 'movie_id' matches our target.
        return Booking::where('account_id', $account_id)
            ->where('status', 'confirmed')
            ->whereHas('schedule', function (Builder $query) use ($movieId) {
                $query->where('movie_id', $movieId);
            })
            ->exists(); // The exists() method is very efficient, returning true or false without loading any models.
    }

    /**
     * Cancels a booking, releasing the seats.
     * This operation must be atomic: booking cancelled AND seats released, or none.
     *
     * @param Booking $booking The booking to cancel.
     * @return bool True if cancellation was successful, false otherwise.
     */
    public function cancelBooking(Booking $booking): bool
    {
        // Only allow cancellation if the booking is confirmed, pending, or failed.
        // Once refunded or already cancelled, no further action is needed.
        if (!in_array($booking->status, ['confirmed', 'pending', 'failed'])) {
            echo "Facade: Booking " . $booking->id . " is already in a final state (" . $booking->status . ") and cannot be cancelled.\n";
            return false;
        }

        DB::beginTransaction();

        try {
            // 1. Change the status of the Booking to 'cancelled'.
            $booking->status = 'cancelled';
            $booking->save();

            // 2. Change the status of the related seats back to 'available' for that schedule.
            foreach ($booking->bookingSeats as $bookingSeat) {
                // Ensure the seat's status for THIS schedule is updated.
                $booking->schedule->seats()->updateExistingPivot($bookingSeat->seat_id, ['status' => 'available']);
            }

            DB::commit();
            echo "Facade: Booking " . $booking->id . " cancelled and seats released.\n";
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            echo "Facade: Error cancelling booking " . $booking->id . ": " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}