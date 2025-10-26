<?php
//Name: Wo Jia Qian
// Student ID: 2314023

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Log; // Already imported
use Illuminate\Support\Facades\Storage; // Already imported
use Illuminate\Support\Facades\Auth; // Already imported
use App\Http\Controllers\Controller;
use App\Models\Booking; // The Booking Eloquent Model
use App\Services\Booking\BookingFacade; // BookingFacade import is correct
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\URL;

class AdminBookingController extends Controller
{
    protected BookingFacade $bookingFacade;

    public function __construct(BookingFacade $bookingFacade)
    {
        $this->bookingFacade = $bookingFacade;
    }

    /**
     * Display a listing of all bookings for the admin panel.
     * Admins need to see all bookings, filterable and searchable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */

    public function panel(Request $request){
        // Calculate total revenue from all bookings.
        // The `sum` method is a quick way to get the sum of a column.
        $totalRevenue = $this->bookingFacade->getTotalRevenueForAllBookings();

        // Get the total count of all bookings.
        // The `count` method is highly optimized for this purpose.
        $totalBookings = $this->bookingFacade->getTotalNumberOfBookings();

        // Get all bookings of the system
        $allBookings = $this->bookingFacade->getAllBookings();

        // Process all bookings based on request
        $updatedBookings = $this->bookingFacade->filterAndSortingBookings($request, $allBookings);

        $bookings = $updatedBookings->paginate(10)->withQueryString();

        // Pass a map of movie titles for the filter dropdown
        $movies = Movie::pluck('title', 'id');

        // Pass the data to the dashboard view.
        return view('admin.bookings.panel', compact('totalRevenue', 'totalBookings', 'bookings', 'movies'));
    }

    public function generateReport(Request $request){

        $query = $this->bookingFacade->getAllBookings();

        $updatedBookings = $this->bookingFacade->filterAndSortingBookings($request, $query);

        $bookings = $updatedBookings->with(['account', 'schedule.movie', 'bookingSeats'])->get();

        // Pass the data to a new view that will serve as our PDF template
        $html = view('admin.bookings.report', compact('bookings'))->render();

        // Generate the PDF from the HTML using Browsershot
        $pdf = Browsershot::html($html)
            ->setNodeBinary('C:\Program Files\nodejs\node.exe') // CHANGE THIS
            ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')   // CHANGE THIS
            ->showBackground()
            ->format('A4')
            ->landscape() // Use landscape for wider tables
            ->noSandbox() // Required for some environments
            ->pdf();

        // Return the PDF as a download
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="bookings-report-' . now()->format('Y-m-d') . '.pdf"');

    }
}
