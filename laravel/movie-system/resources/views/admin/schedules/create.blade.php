<!-- Author: Cheh Shu Hong -->
<!-- StudentID: 23WMR14515 -->
@extends('layouts.admin')

@section('title', 'Create Schedule')

@section('content')
    <div class="container">
        <h1>Create Schedule</h1>

        <!-- Error message display section -->
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

        <form action="{{ route('admin.schedules.store') }}" method="POST">
            @csrf

            <!-- Movies Dropdown -->
            <div class="mb-3">
                <label for="movie_id" class="form-label">Movie</label>
                <select name="movie_id" id="movie_id" class="form-control" required>
                    @if($movieError)
                        <option value="">{{ $movieError }}</option>
                    @elseif($movies->isEmpty())
                        <option value="">No movies available</option>
                    @else
                        <option value="">Select a movie</option>
                        @foreach($movies as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Show Date Input (Tomorrow onwards only) -->
            <div class="mb-3">
                <label for="show_date" class="form-label">Show Date</label>
                <input type="date" name="show_date" id="show_date" class="form-control" required
                    min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
            </div>

            <!-- Show Time Dropdown (15-Minute Intervals, 12PM–12AM) -->
            <div class="mb-3">
                <label for="show_time_select" class="form-label">Show Time</label>
                <select name="show_time_select" id="show_time_select" class="form-control" required>
                    @php
                        $start = \Carbon\Carbon::createFromTime(12, 0);   // 12:00 PM
                        $end = \Carbon\Carbon::createFromTime(23, 59);   // 11:59 PM
                    @endphp
                    @while ($start <= $end)
                        <option value="{{ $start->format('H:i') }}">{{ $start->format('g:i A') }}</option>
                        @php $start->addMinutes(15); @endphp
                    @endwhile
                </select>
            </div>

            <!-- Halls Dropdown -->
            <div class="mb-3">
                <label for="hall_id" class="form-label">Hall</label>
                <select name="hall_id" id="hall_id" class="form-control" required>
                    @foreach($halls as $hall)
                        <option value="{{ $hall->id }}">{{ $hall->hall_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Form Actions -->
            <button type="submit" class="btn btn-success">Save Schedule</button>
            <a href="{{ route('admin.schedules.panel') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection