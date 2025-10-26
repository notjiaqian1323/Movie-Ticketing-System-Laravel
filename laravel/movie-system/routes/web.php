<?php
// Name: CHONG KA HONG
// Student ID: 2314524

use Illuminate\Support\Facades\Route;


// // Public and authenticated controllers (guest before login + customer)
use App\Http\Controllers\{
    AuthController,
    HomeController,
    ProfileController,
    MovieController,
    ReviewController,
    ScheduleController,
// 
};

// Admin  panel controllers
use App\Http\Controllers\Admin\{
    AdminAccountController,
    AdminMovieController,
    AdminBookingController,
    AdminScheduleController,
    AdminReviewController,
    AdminSeatController
};

Route::get('/test-route', function () {
    return "Hello World! The router is working.";
});
// Public routes
// guest, customer, admin go to this page when open website
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Movie Routes (accessible to all, including guests)
Route::get('/movies/listing', [MovieController::class, 'listing'])->name('movies.listing');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

// Auth routes (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register'); // Added from friend's routes
    Route::post('/register', [AuthController::class, 'register']); // Added from friend's routes
});

// Unauthorized Access Page
Route::get('/unauthorized', function () {
    return view('auth.unauthorized'); })->name('unauthorized');

// Customer + admin routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Management (accessible to any logged-in user)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'delete'])->name('profile.delete');
});

// Admin routes
Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->group(function () {

    // admin account management
    Route::get('/customers/panel', [AdminAccountController::class, 'index'])->name('admin.accounts.panel');
    Route::get('/customers/create', [AdminAccountController::class, 'create'])->name('admin.accounts.create');
    Route::post('/customers', [AdminAccountController::class, 'store'])->name('admin.accounts.store');
    Route::get('/customers/{account}/edit', [AdminAccountController::class, 'edit'])->name('admin.accounts.edit');
    Route::put('/customers/{account}', [AdminAccountController::class, 'update'])->name('admin.accounts.update');
    Route::delete('/customers/{account}', [AdminAccountController::class, 'destroy'])->name('admin.accounts.destroy');
    Route::put('/customers/{account}/toggle-status', [AdminAccountController::class, 'toggleStatus'])->name('admin.accounts.toggleStatus');

    // admin movie management
    Route::get('/movies/panel', [AdminMovieController::class, 'index'])->name('admin.movies.panel');
    Route::get('/movies/create', [AdminMovieController::class, 'create'])->name('admin.movies.create');
    Route::post('/movies', [AdminMovieController::class, 'store'])->name('admin.movies.store');
    Route::get('/movies/{movie}/edit', [AdminMovieController::class, 'edit'])->name('admin.movies.edit');
    Route::put('/movies/{movie}', [AdminMovieController::class, 'update'])->name('admin.movies.update');
    Route::post('/movies/{movie}/activate', [AdminMovieController::class, 'activate'])->name('admin.movies.activate');
    Route::post('/movies/{movie}/deactivate', [AdminMovieController::class, 'deactivate'])->name('admin.movies.deactivate');
    Route::post('/movies/{movie}/popular/add', [AdminMovieController::class, 'addToPopular'])->name('admin.movies.popular.add');
    Route::post('/movies/{movie}/popular/remove', [AdminMovieController::class, 'removeFromPopular'])->name('admin.movies.popular.remove');

    // admin schedule management
    Route::controller(AdminScheduleController::class)->prefix('schedules')->name('admin.schedules.')->group(function () {
        Route::get('/panel', 'index')->name('panel');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{schedule}/edit', 'edit')->name('edit');
        Route::put('/{schedule}', 'update')->name('update');
        // Route::delete('/{schedule}', 'destroy')->name('destroy');
    });

    // admin booking management
    Route::controller(AdminBookingController::class)->group(function () {
        // Show a list of ALL bookings in the system
        Route::get('/bookings/panel', 'panel')->name('admin.bookings.panel');

        // Generate Report for Bookings
        Route::get('/bookings/report', 'generateReport')->name('admin.bookings.report');
    });

    // Admin review management
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');
});

// Customer
Route::middleware(['auth', 'checkRole:customer'])->group(function () {
    //  Review CRUD (moved here from the generic auth group)
    Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->middleware('throttle:6,1')->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // MyReview (history)
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.history');

    // Customer Schedule Routes (accessible to authenticated users)
    Route::get('/movies/{movieId}/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
});
