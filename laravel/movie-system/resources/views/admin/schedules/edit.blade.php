<!-- Author: Cheh Shu Hong -->
<!-- StudentID: 23WMR14515 -->
@extends('layouts.admin')

@section('title', 'Edit Schedule')

@section('content')
    <div class="container">
        <h1>Edit Schedule</h1>

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

        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Movie (Read-only) -->
            <div class="mb-3">
                <label class="form-label">Movie</label>
                <input type="text" class="form-control" value="{{ $movieTitle }}" disabled>
                <input type="hidden" name="movie_id" value="{{ $schedule->movie_id }}">
            </div>

            <!-- Show Date (Editable, Tomorrow onwards only) -->
            <div class="mb-3">
                <label for="show_date" class="form-label">Show Date</label>
                <input type="date" name="show_date" id="show_date" class="form-control"
                    value="{{ \Carbon\Carbon::parse($schedule->show_time)->format('Y-m-d') }}"
                    min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
            </div>

            <!-- Show Time Dropdown (15-Minute Intervals, 12PM–12AM) -->
            <div class="mb-3">
                <label for="show_time_select" class="form-label">Show Time</label>
                <select name="show_time_select" id="show_time_select" class="form-control" required>
                    @php
                        // Extract the original schedule time (e.g. "14:30")
                        $selectedTime = \Carbon\Carbon::parse($schedule->show_time)->format('H:i');
                        $start = \Carbon\Carbon::createFromTime(12, 0);   // 12:00 PM
                        $end = \Carbon\Carbon::createFromTime(23, 59);   // 11:59 PM
                    @endphp
                    @while ($start <= $end)
                        <option value="{{ $start->format('H:i') }}" {{ $selectedTime === $start->format('H:i') ? 'selected' : '' }}>
                            {{ $start->format('g:i A') }}
                        </option>
                        @php $start->addMinutes(15); @endphp
                    @endwhile
                </select>
            </div>

            <!-- Halls Dropdown -->
            <div class="mb-3">
                <label for="hall_id" class="form-label">Hall</label>
                <select name="hall_id" id="hall_id" class="form-control" required>
                    @foreach($halls as $hall)
                        <option value="{{ $hall->id }}" {{ $schedule->hall_id == $hall->id ? 'selected' : '' }}>
                            {{ $hall->hall_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Form Actions -->
            <button type="submit" class="btn btn-primary">Update Schedule</button>
            <a href="{{ route('admin.schedules.panel') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let dateInput = document.getElementById('show_date');

                // Set min date to tomorrow
                let today = new Date();
                today.setDate(today.getDate() + 1);
                let minDate = today.toISOString().split('T')[0];
                dateInput.setAttribute('min', minDate);

                // Only auto-correct AFTER user interacts
                dateInput.addEventListener('change', function () {
                    if (dateInput.value < minDate) {
                        alert("The selected date is expired. It will be reset to tomorrow.");
                        dateInput.value = minDate;
                    }
                });
            });
        </script>
    @endpush
@endsection