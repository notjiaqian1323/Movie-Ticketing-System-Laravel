<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingMultiStep extends Component
{
    //multi-step setup
    public $currentStep = 1;
    public $totalSteps = 2;


    public function nextStep(){

        // --- NEW VALIDATION FOR TICKET COUNTS ---
        if ($this->currentStep === 1) {
            $totalTickets = array_sum($this->ticketCounts);
            $totalSeats = count($this->selectedSeats);

            if ($totalTickets !== $totalSeats) {
                session()->flash('error', 'Please ensure the number of tickets matches the number of seats you have selected.');
                return; // Stop execution if validation fails
            }
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->dispatch('next-step-scrolled');
        }
        $this->showModal=false;
    }

    public function previousStep(){
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }
    



    /** @var \Illuminate\Support\Collection */
    public $schedules;

    public $movie;

    public $date;

    //schedule related data
    public $selectedDate = null;
    public $showtimesForDate = [];
    public $selectedScheduleId = null;
    public $selectedShowtime = null;

    /** @var \Illuminate\Support\Collection */
    public $seatData = [];

    public $loadingSeats = false;
    public $selectedSeats = [];
    public $maxSeats = 5;
    public $showModal = false;
    public $ticketCounts = [
        'adult' => 0,
        'child' => 0,
        'oku' => 0,
    ];
    public $ticketPrices = [
        'adult' => 18.00,
        'child' => 12.00,
        'oku' => 10.00,
    ];

    protected $listeners = ['scheduleSelected' => 'selectShowtime'];

    public function mount($schedules, $movie , $date){
        $this->schedules = Collection::make($schedules);
        $this->movie = $movie;
        $this->date = $date;

        if ($this->date) {
            $this->selectedDate = $this->date;
            $this->updatedSelectedDate($this->selectedDate);
        }

        $this->fetchSeats();
    }

    public function updatedSelectedDate($value){
        $this->showtimesForDate = $this->schedules
            ->filter(function ($schedule) use ($value) {
                return Carbon::parse($schedule->show_time)->format('Y-m-d') === $value;
            })
            ->sortBy('show_time')
            ->values();
        
        $this->selectedScheduleId = null;
    }

    public function selectShowtime($scheduleId)
    {
        // Now, we get the request here, since this is the method called by the event.
        $request = request();
        $useApi = $request->query('use_api', false);

        $this->selectedScheduleId = $scheduleId;
        $this->selectedSeats = [];
        $this->fetchSeats($useApi);
    }

    public function fetchSeats($useApi = false)
    {
        
        if (!$this->selectedScheduleId) {
            $this->seatData = [];
            return;
        }

        $this->loadingSeats = true;
        
        // // Now, instead of querying the database, we emit an event
        // // to trigger the JavaScript on the client side.
        // $this->dispatch('fetchSeats', id: $this->getId(), scheduleId: $this->selectedScheduleId);


        try {

            if ($useApi) {
                // === External API Consumption ===
                $response = Http::timeout(10)
                    ->get(config('services.schedule.url') . "/schedules/{$this->selectedScheduleId}/seats");
                
                $response->throw(); // Throws an exception on HTTP errors

                $seatsData = $response->json('data') ?? [];

                // Add this line to log the data from the external API
                Log::info('External API Seat Data:', ['data' => $seatsData]);

                $this->seatData = $seatsData;

                
            } else {
                // === Internal API Consumption ===
                $requestApi = Request::create("/api/schedules/{$this->selectedScheduleId}/seats", 'GET');
                
                // Set the user to ensure any authentication middleware works.
                $requestApi->setUserResolver(function () {
                    return auth()->user();
                });

                $responseApi = Route::dispatch($requestApi);

                if ($responseApi->getStatusCode() === 200) {
                    $seatsJson = json_decode($responseApi->getContent(), true);

                    $this->seatData = $seatsJson['data'] ?? [];

                    // Add this line to log the data from the internal API
                    Log::info('Internal API Seat Data:', ['data' => $this->seatData]);

                } else {
                    // Handle internal API failure
                    Log::error("Failed to fetch seats from internal API: " . $responseApi->getStatusCode());
                    $this->seatData = [];
                }
            }
        } catch (RequestException $e) {
            // Handle external API failure
            Log::error("Failed to fetch seats from Movie API: " . $e->getMessage());
            $this->seatData = [];
        } catch (\Throwable $e) {
            // Handle any other unexpected errors
            Log::error("Error fetching seats: " . $e->getMessage());
            $this->seatData = [];
        }
    }

        /**
     * Get the formatted show time for the view.
     */
    public function getFormattedShowTimeProperty()
    {
        if ($this->selectedSchedule) {
            // Re-parse the string as a Carbon instance before formatting
            return Carbon::parse($this->selectedSchedule->show_time)->format('h:i A');
        }
        return null;
    }

    public function handleSeatClick($seatId)
    {
        // Find the seat directly from the component's already-loaded data.
        $seat = collect($this->seatData)->firstWhere('id', $seatId);

        // If the seat isn't found in our component's data, something is wrong.
        if (!$seat) {
            logger()->error("Seat with ID {$seatId} not found in the component's data for schedule {$this->selectedScheduleId}.");
            return session()->flash('error', 'The seat was not found. Please try again.');
        }
        
        // Now, check the status from the object we found.
        if ($seat['status'] !== 'available') {
            return session()->flash('error', 'This seat is not available.');
        }

        if (in_array($seatId, $this->selectedSeats)) {
            $this->selectedSeats = array_diff($this->selectedSeats, [$seatId]);
        } else {
            if (count($this->selectedSeats) >= $this->maxSeats) {
                return session()->flash('error', "You can only select at most {$this->maxSeats} seats.");
            }
            $this->selectedSeats[] = $seatId;
        }

        $this->initializeTicketCounts();
    }

    public function openCheckoutModal(){
        $this->showModal = true;
    }

    //set default ticket type to adult tickets
    public function initializeTicketCounts()
    {
        $this->ticketCounts = [
            'adult' => count($this->selectedSeats),
            'child' => 0,
            'oku' => 0,
        ];
    }

    //handles if have other than adult tickets
    public function updateTicketCount($type, $delta)
    {
        if ($delta > 0 && array_sum($this->ticketCounts) < count($this->selectedSeats)) {
            $this->ticketCounts[$type]++;
        } elseif ($delta < 0 && $this->ticketCounts[$type] > 0) {
            $this->ticketCounts[$type]--;
        }
    }

    public function getSelectedScheduleProperty()
    {
        return Schedule::find($this->selectedScheduleId);
        //change to using shu hong api
    }

    public function getSelectedSeatsNamesProperty()
    {
        // Use the existing seatData array to find the names of the selected seats.
        return collect($this->seatData)
            ->whereIn('id', $this->selectedSeats)
            ->pluck('name')
            ->implode(', ');
    }

    public function getHallProperty()
    {
        // Check if a schedule has been selected
        if ($this->selectedSchedule) {
            // Access the hall data through the 'hall' relationship on the schedule model
            return $this->selectedSchedule->hall;
        }

        // Return null or an empty array if no schedule is selected
        return null;
    }

    public function getTicketTypesForBackendProperty()
    {
        // FIX: First, get the mapping from the pivot ID to the actual seat ID.
        $seatIdMap = DB::table('schedule_seats')
            ->whereIn('id', $this->selectedSeats)
            ->pluck('seat_id', 'id');

        $types = [];
        $seatsRemaining = collect($this->selectedSeats);

        foreach (['adult', 'child', 'oku'] as $type) {
            for ($i = 0; $i < $this->ticketCounts[$type]; $i++) {
                if ($seatsRemaining->isNotEmpty()) {
                    $pivotId = $seatsRemaining->shift();
                    $seatId = $seatIdMap[$pivotId];
                    $types[$seatId] = strtoupper($type);
                }
            }
        }
        return $types;
    }

    public function getTotalPriceProperty()
    {

        $totalPrice = 0;

        // Loop through the ticket counts
        foreach ($this->ticketCounts as $type => $count) {
            // Ensure the ticket type exists in our prices array
            if (isset($ticketPrices[$type])) {
                $totalPrice += $count * $ticketPrices[$type];
            }
            $totalPrice += $count * $this->ticketPrices[$type];
        }

        return $totalPrice;
    }


    public function finalizeBooking()
    {

        Log::debug('Livewire: finalizeBooking method called.');
        
        $payload = [
            'schedule_id' => $this->selectedScheduleId,
            'seats' => $this->selectedSeats,
            'ticket_types' => $this->getTicketTypesForBackendProperty(),
            'account_id' => Auth::id() // Pass the authenticated user's ID
        ];

        Log::debug('Livewire: Sending API request with payload: ', $payload);

        try {
            // Your existing logic to finalize booking.
            $response = Http::post(
                config('services.booking.url') . '/booking/reserve', $payload
            );

            Log::debug('Livewire: Received API response with status code: ' . $response->status());

            if ($response->successful()) {
                Log::info('Livewire: Booking finalized successfully! Refreshing seat data.');
                session()->flash('success', 'Booking finalized successfully!');
                // === FIX: Re-fetch seat data to reflect the new status in the UI.
                $this->fetchSeats();
                // You can also reset state and move to the next step if needed.
                $this->currentStep = 3;
                $this->dispatch('next-step-scrolled');
            } else {
                Log::error('Livewire: Booking failed with API error. Response body: ' . $response->body());
                session()->flash('error', 'Booking failed. Please try again.');
            }
        } catch (RequestException $e) {
            session()->flash('error', 'Booking failed: ' . $e->getMessage());
            Log::error("Booking failed: {$e->getMessage()}");
        }
    }


    public function render()
    {
        return view('livewire.booking-multi-step');
    }
}
