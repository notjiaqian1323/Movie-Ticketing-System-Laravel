<!--
Name: Wo Jia Qian
Student Id: 2314023
-->

@extends('layouts.admin')

@section('title', 'Bookings')

@section('content')
    <h1 style="margin-bottom: 60px;">Manage Bookings</h1>
    <div class="row min-w-500 mx-auto g-4" style="margin-bottom: 60px;">
        <!-- Total Revenue Box -->
        <div class="col-6">
            <div class="card shadow p-4 mb-60 border-2 border-gray-200" style="border-radius: 12px;">
                <div class="card-body">
                    <h2 class="card-title text-xl font-weight-semibold text-gray-700 mb-2">Total Revenue</h2>
                    <p class="card-text h1 font-weight-extrabold text-success">RM{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Bookings Box -->
        <div class="col-6">
            <div class="card shadow p-4 border-2 border-gray-200" style="border-radius: 12px;">
                <div class="card-body">
                    <h2 class="card-title text-xl font-weight-semibold text-gray-700 mb-2">Total Bookings</h2>
                    <p class="card-text h1 font-weight-extrabold text-indigo-600">{{ number_format($totalBookings) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Sorting --}}
    <form method="GET" action="{{ route('admin.bookings.panel') }}" class="mb-4">
        <div class="row g-2 align-items-end">

            {{-- Movie filter --}}
            <div class="col-md-3">
                <label for="movie_id" class="form-label">Filter by Movie</label>
                <select name="movie_id" id="movie_id" class="form-select">
                    <option value="">All Movies</option>
                    @foreach ($movies as $id => $title)
                        <option value="{{ $id }}" {{ request('movie_id') == $id ? 'selected' : '' }}>
                            {{ $title }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- User ID filter --}}
            <div class="col-md-3">
                <label for="user_id" class="form-label">Filter by User ID</label>
                <input type="text" name="user_id" id="user_id" class="form-control"
                    value="{{ request('user_id') }}" placeholder="Enter User ID">
            </div>

            {{-- Total amount range --}}
            <div class="col-md-2">
                <label for="min_amount" class="form-label">Min Amount</label>
                <input type="number" name="min_amount" id="min_amount" class="form-control"
                    value="{{ request('min_amount') }}" step="0.01" placeholder="RM">
            </div>
            <div class="col-md-2">
                <label for="max_amount" class="form-label">Max Amount</label>
                <input type="number" name="max_amount" id="max_amount" class="form-control"
                    value="{{ request('max_amount') }}" step="0.01" placeholder="RM">
            </div>

            {{-- Sort options --}}
            <div class="col-md-2">
                <label for="sort" class="form-label">Sort By</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="booking_date_desc" {{ request('sort') === 'booking_date_desc' ? 'selected' : '' }}>Booking Date ↓</option>
                    <option value="booking_date_asc" {{ request('sort') === 'booking_date_asc' ? 'selected' : '' }}>Booking Date ↑</option>
                    <option value="total_amount_desc" {{ request('sort') === 'total_amount_desc' ? 'selected' : '' }}>Total Amount ↓</option>
                    <option value="total_amount_asc" {{ request('sort') === 'total_amount_asc' ? 'selected' : '' }}>Total Amount ↑</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Apply</button>
            </div>
            <div class="col-md-2">
                <a href="{{ url()->current() }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </div>
    </form>

    @if($bookings->isEmpty())
        <p class="text-center">No bookings found.</p>
    @else
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>User</th>
                                <th>Movie</th>
                                <th>Showtime</th>
                                <th>Total Price</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                {{-- The entire row is a clickable link to the booking details page --}}
                                <tr style="cursor: pointer;">
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->account->id }}</td>
                                    <td>{{ $booking->schedule->movie->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->schedule->show_time)->format('M j, Y - g:i A') }}</td>
                                    <td>RM{{ number_format($booking->bookingSeats->sum('price'), 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('M j, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Links --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $bookings->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    @endif
@endsection
