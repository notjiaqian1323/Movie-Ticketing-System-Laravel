<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminMovieController;
use App\Http\Controllers\Api\MovieApiController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\AccountApiController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ScheduleController;

Route::prefix('reviews')->group(function () {
    Route::get('/summary', [ReviewApiController::class, 'getMovieReviewsSummaryForIds']); // summary for avg_rating and count
    Route::get('/movies/{id}', [ReviewApiController::class, 'getMovieReviews']); // review for movie.show
    Route::get('/user-has-reviewed/{movieId}/{userId}', [ReviewApiController::class, 'userHasReviewed']);
});

// Public API routes
Route::prefix('movies')->group(function () {
    Route::get('/info-for-ids', [MovieApiController::class, 'getMovieInfoForIds']); // used by review module
    Route::get('/{id}/details', [MovieApiController::class, 'getMovieDetailsById']); // use by booking module
});

Route::prefix('accounts')->group(function () {
    Route::get('/usernames', [AccountApiController::class, 'getUsernamesByIds']);
});

Route::middleware(['auth:sanctum'])->group(function () {
});

// Admin Movie API routes
Route::prefix('admin')->middleware(['auth:sanctum', 'checkRole:admin'])->group(function () {
    // Get all movie titles
    Route::get('/movies/titles', [AdminMovieController::class, 'getAllTitles']);

    // Get now showing movies (id + title)
    Route::get('/movies', [AdminMovieController::class, 'getNowShowingMoviesTitle']);

    // Get duration of a movie by ID
    Route::get('/movies/{id}', [AdminMovieController::class, 'getMovieDurationById']);
});

// Schedule API routes
Route::prefix('schedules')->group(function () {
    Route::get('/', [ScheduleController::class, 'getAllSchedules']);           // GET all schedules
    Route::get('/{id}', [ScheduleController::class, 'getScheduleById']);       // GET single schedule
    Route::get('/{scheduleId}/seats', [ScheduleController::class, 'getSeatsBySchedule']); // GET seats for a schedule
});


// Booking API routes
Route::prefix('booking')->group(function () {
    Route::get('/bookings/{id}', [BookingController::class, 'getBookingsByAccount']); // GET all bookings for current user
    Route::post('/reserve', [BookingController::class, 'reserve'])->name('bookings.reserve');
    Route::get('/isbooked/{movieId}/{accountId}', [BookingController::class, 'isBooked']);
});