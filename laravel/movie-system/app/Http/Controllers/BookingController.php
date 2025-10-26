<?php
// Name: Wo Jia Qian
// Student id: 2314023

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Services\Booking\BookingFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route; 
use Illuminate\Http\Client\RequestException;

class BookingController extends Controller
{
    protected BookingFacade $bookingFacade;

    public function __construct(BookingFacade $bookingFacade)
    {
        $this->bookingFacade = $bookingFacade;
    }

    public function getSeats($id)
    {
        // Simply call the facade method and return the result as a JSON response

        $seats = $this->bookingFacade->getSeatsForSchedule($id);
        
        return response()->json($seats);
    }

    public function bookSeats($movieId, string $date, Schedule $selectedSchedule)
    { 
        $movie = $this->bookingFacade->getMovieDetailsForBooking($movieId);

        $schedules = $this->bookingFacade->getSchedulesWithHallData($movie['id']);

        return view('bookings.create', [
            'movie' => $movie,
            'date' => $date,
            'schedule' => $schedules,
            'selected' => $selectedSchedule  // Pass the schedule data of the specific movie to the view
        ]);
    }

    /**
     * Reserve seats and create a pending booking.
     */
    // public function reserve(Request $request)
    // {

    //     // First, let's just see if the request data is coming through at all.
    //     // This will stop the code here and show the raw request data.
    //     // Uncomment this line to test the very first step.

    //     $validated = $request->validate([
    //         'seats' => 'required|array|min:1',
    //         'ticket_types' => 'required|array',
    //         'schedule_id' => 'required|integer|exists:schedules,id', // Added 'exists' rule for safety.
    //     ]);
        
    //     // Test 1: Check if validation passed and the validated data is correct.
    //     // If you see this output, your form is submitting the correct data.
    //     //return response()->json(['validated' => $validated]);

    //     // Retrieve the schedule using the validated ID.
    //     // use the provider to get the schedule
    //     $schedule = \App\Models\Schedule::findOrFail($validated['schedule_id']);
        
    //     // Test 2: Check if the Schedule model was found and is a valid object.
    //     // If you see this output, the schedule_id is correct and the model exists.
        
    //     // Call the facade/service.
    //     $booking = $this->bookingFacade->reserveSeats(
    //         $schedule,
    //         $validated['seats'],
    //         $validated['ticket_types']
    //     );
        
    //     // Test 3: Check if the booking was created and a valid booking object was returned.
    //     // If you see this output, your facade logic is working correctly.
    
    //     //return response()->json(['booking_created' => $booking->toArray()]);

    //     return redirect()->route('bookings.receipt.show', ['booking' => $booking]);

        

    // }

    public function reserve(Request $request)
    {
        // Step 1: Log that the API call has been received.
        Log::info('BookingController: API reserve method called.');
        Log::debug('BookingController: Received request payload: ', $request->all());

        try {
            // Validate the incoming data to ensure it's in the correct format.
            $validated = $request->validate([
                'schedule_id' => 'required|exists:schedules,id',
                'seats' => 'required|array|min:1',
                'seats.*' => 'required|integer',
                'ticket_types' => 'required|array|min:1',
                'ticket_types.*' => 'required|string',
                'account_id' => 'required|exists:accounts,id', // Validate the account ID
            ]);
            Log::info('BookingController: Request validation successful.');

            //Retrieve the schedule and pass it to the facade.
            $schedule = Schedule::findOrFail($validated['schedule_id']);
            $booking = $this->bookingFacade->finalizeBooking(
                $schedule,
                $validated['seats'],
                $validated['ticket_types'],
                $validated['account_id'] // Pass the validated account ID
            );

            // Step 4: Log that the facade completed its task successfully.
            Log::info('BookingController: Facade finalized booking successfully. Preparing success response.');

            //Return a success response with the new booking details.
            return response()->json([
                    'message' => 'Booking finalized successfully!',
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->qr_code,
                ], 201); // 201 Created is the correct status code for a successful creation.

        } catch (RequestException $e) {
                Log::error("BookingController: Booking failed. Error: {$e->getMessage()}. Preparing error response.");
                // Return a detailed error response.
                return response()->json([
                    'message' => 'Booking failed. ' . $e->getMessage(),
                    'error' => $e->getMessage(),
                ], 422); // 422 Unprocessable Entity is a good status code for invalid data.
        }
    }

    public function success()
    {
        // Step 1: Get the authenticated user's ID.
        // This is a much safer way than passing the ID through the URL.
        $accountId = Auth::id();

        // Step 2: Pass the account ID to the facade to get the bookings.
        // This is the "heavy lifting" part.
        $bookings = $this->bookingFacade->getBookingsForUser($accountId);

        return view('bookings.index', ['bookings' => $bookings]);
    }

    public function getBookingsByAccount($account_id){

        $bookings = $this->bookingFacade->getBookingsForUser($account_id);

        return response()->json($bookings);
    }

    public function getConfirmedBookingsByAccount($account_id){

        $bookings = $this->bookingFacade->getConfirmedBookingsForUser($account_id);

        return response()->json($bookings);
    }

    public function isBooked($movieId, $account_id)
    {
        Log::info('BookingController: checkBookedStatus method called.', [
            'movie_id' => $movieId,
            'account_id' => $account_id
        ]);

        //$booked = $this->bookingFacade->isThisMovieBookedByUser($movieId, $account_id);

        try {
            // Use the service facade to perform the check.
            $hasBooked = $this->bookingFacade->isThisMovieBookedByUser($movieId, $account_id);

            Log::debug('BookingController: Check result.', ['has_booked' => $hasBooked]);

            // Return a simple JSON response.
            return response()->json([
                'booked' => $hasBooked
            ]);

        } catch (\Exception $e) {
            Log::error('BookingController: An error occurred while checking booking status.', ['error' => $e->getMessage()]);
            // Return an error response if something goes wrong.
            return response()->json([
                'error' => 'Unable to check booking status.'
            ], 500);
        }
    }

    public function showReceipt(Booking $booking)
    {
        // Eager load the relationships needed for the view.
        // The `load()` method performs a new query to get these.
        $booking->load([
            'schedule.movie',
            'schedule.hall',
            'bookingSeats.seat'
        ]);

        return view('bookings.receipt.show', ['booking' => $booking]);
    }
}
