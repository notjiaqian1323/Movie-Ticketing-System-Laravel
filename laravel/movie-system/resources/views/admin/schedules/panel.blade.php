<!-- Author: Cheh Shu Hong -->
<!-- StudentID: 23WMR14515 -->
@extends('layouts.admin')

@section('title', 'Schedule')

@section('content')
    <div class="container">
        <h1>Schedule Management</h1>

        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary mb-3">+ Create Schedule</a>

        {{-- Filters & Sorting --}}
        <form method="GET" action="{{ route('admin.schedules.panel') }}" class="mb-3">
            <div class="row g-2 align-items-end">

                {{-- Movie filter --}}
                <div class="col-md-3">
                    <label for="movie_id" class="form-label">Filter by Movie</label>
                    <select name="movie_id" id="movie_id" class="form-select">
                        <option value="">All Movies</option>
                        @foreach ($movieTitleMap ?? [] as $id => $title)
                            <option value="{{ $id }}" {{ request('movie_id') == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Hall filter --}}
                <div class="col-md-3">
                    <label for="hall_id" class="form-label">Filter by Hall</label>
                    <select name="hall_id" id="hall_id" class="form-select">
                        <option value="">All Halls</option>
                        @foreach ($hallMap ?? [] as $id => $name)
                            <option value="{{ $id }}" {{ request('hall_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date range --}}
                <div class="col-md-2 relative">
                    <label for="start_date" class="form-label">Start Date</label>
                    <div class="input-group">
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                </div>

                <div class="col-md-2 relative">
                    <label for="end_date" class="form-label">End Date</label>
                    <div class="input-group">
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>
                </div>

                {{-- Sort options --}}
                <div class="col-md-2">
                    <label for="sort" class="form-label">Sort By</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="show_time_asc" {{ request('sort') === 'show_time_asc' ? 'selected' : '' }}>Show Time ↑
                        </option>
                        <option value="show_time_desc" {{ request('sort') === 'show_time_desc' ? 'selected' : '' }}>Show Time
                            ↓</option>
                        <option value="available_desc" {{ request('sort') === 'available_desc' ? 'selected' : '' }}>Seats
                            Available ↓</option>
                        <option value="available_asc" {{ request('sort') === 'available_asc' ? 'selected' : '' }}>Seats
                            Available ↑</option>
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

        @if ($movieError)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $movieError }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Table View --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Movie</th>
                    <th>Show Date</th>
                    <th>Show Time</th>
                    <th>Hall</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->id }}</td>
                        <td>{{ $schedule->movie_title }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->show_time)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->show_time)->format('g:i A') }}</td>
                        <td>{{ $schedule->hall->hall_name ?? 'Unknown Hall' }}</td>
                        <td>
                            <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No schedules found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($schedules->hasPages())
        {{ $schedules->appends(request()->query())->links() }}
    @endif
@endsection