<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

// Group all the authenticated booking routes together.
Route::middleware('auth', 'checkRole:customer')->group(function () {

    // 1. Show a list of all of the authenticated user's bookings.
    Route::get('/index', [BookingController::class, 'success'])->name('bookings.index');

    // 3. Booking the seats and checking out
    Route::get('/movies/{movie}/schedules/{date}/booking', [BookingController::class, 'bookSeats'])->name('bookings.create');

    // 4. Processing the payments and redirect to success
    //api route for process booking

    // 5. Display the receipt when the user want to show to counter
    Route::get('/schedules/checkout/{booking}', [BookingController::class, 'showReceipt'])->name('bookings.receipt.show');

    // 6. Cancel a booking.
    Route::delete('/{booking}', [BookingController::class, 'cancelBooking'])->name('bookings.cancel');

});