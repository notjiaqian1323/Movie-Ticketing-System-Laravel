<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Movie;
use App\Models\Hall;
use App\Models\Seat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Get all movies and halls
        $movies = Movie::all();
        $halls = Hall::all();

        // Safety check
        if ($movies->isEmpty() || $halls->isEmpty()) {
            $this->command->warn('No movies or halls found. Please seed them first.');
            return;
        }

        $schedules = [
            // Sept 16
            ['movie_id' => 7, 'hall_id' => 1, 'show_time' => '2025-09-16 14:00:00'],
            ['movie_id' => 8, 'hall_id' => 2, 'show_time' => '2025-09-16 14:15:00'],
            ['movie_id' => 2, 'hall_id' => 3, 'show_time' => '2025-09-16 14:30:00'],
            ['movie_id' => 5, 'hall_id' => 1, 'show_time' => '2025-09-16 17:15:00'],
            ['movie_id' => 10, 'hall_id' => 2, 'show_time' => '2025-09-16 17:30:00'],
            ['movie_id' => 6, 'hall_id' => 3, 'show_time' => '2025-09-16 17:45:00'],
            ['movie_id' => 3, 'hall_id' => 1, 'show_time' => '2025-09-16 20:30:00'],
            ['movie_id' => 9, 'hall_id' => 2, 'show_time' => '2025-09-16 20:45:00'],
            ['movie_id' => 4, 'hall_id' => 3, 'show_time' => '2025-09-16 21:00:00'],

            // Sept 17
            ['movie_id' => 7, 'hall_id' => 1, 'show_time' => '2025-09-17 14:00:00'],
            ['movie_id' => 8, 'hall_id' => 2, 'show_time' => '2025-09-17 14:15:00'],
            ['movie_id' => 2, 'hall_id' => 3, 'show_time' => '2025-09-17 14:30:00'],
            ['movie_id' => 5, 'hall_id' => 1, 'show_time' => '2025-09-17 17:15:00'],
            ['movie_id' => 10, 'hall_id' => 2, 'show_time' => '2025-09-17 17:30:00'],
            ['movie_id' => 6, 'hall_id' => 3, 'show_time' => '2025-09-17 17:45:00'],
            ['movie_id' => 3, 'hall_id' => 1, 'show_time' => '2025-09-17 20:30:00'],
            ['movie_id' => 9, 'hall_id' => 2, 'show_time' => '2025-09-17 20:45:00'],
            ['movie_id' => 4, 'hall_id' => 3, 'show_time' => '2025-09-17 21:00:00'],

            // Sept 18
            ['movie_id' => 7, 'hall_id' => 1, 'show_time' => '2025-09-18 14:00:00'],
            ['movie_id' => 8, 'hall_id' => 2, 'show_time' => '2025-09-18 14:15:00'],
            ['movie_id' => 2, 'hall_id' => 3, 'show_time' => '2025-09-18 14:30:00'],
            ['movie_id' => 5, 'hall_id' => 1, 'show_time' => '2025-09-18 17:15:00'],
            ['movie_id' => 10, 'hall_id' => 2, 'show_time' => '2025-09-18 17:30:00'],
            ['movie_id' => 6, 'hall_id' => 3, 'show_time' => '2025-09-18 17:45:00'],
            ['movie_id' => 3, 'hall_id' => 1, 'show_time' => '2025-09-18 20:30:00'],
            ['movie_id' => 9, 'hall_id' => 2, 'show_time' => '2025-09-18 20:45:00'],
            ['movie_id' => 4, 'hall_id' => 3, 'show_time' => '2025-09-18 21:00:00'],

            // Sept 19
            ['movie_id' => 7, 'hall_id' => 1, 'show_time' => '2025-09-19 14:00:00'],
            ['movie_id' => 8, 'hall_id' => 2, 'show_time' => '2025-09-19 14:15:00'],
            ['movie_id' => 2, 'hall_id' => 3, 'show_time' => '2025-09-19 14:30:00'],
            ['movie_id' => 5, 'hall_id' => 1, 'show_time' => '2025-09-19 17:15:00'],
            ['movie_id' => 10, 'hall_id' => 2, 'show_time' => '2025-09-19 17:30:00'],
            ['movie_id' => 6, 'hall_id' => 3, 'show_time' => '2025-09-19 17:45:00'],
            ['movie_id' => 3, 'hall_id' => 1, 'show_time' => '2025-09-19 20:30:00'],
            ['movie_id' => 9, 'hall_id' => 2, 'show_time' => '2025-09-19 20:45:00'],
            ['movie_id' => 4, 'hall_id' => 3, 'show_time' => '2025-09-19 21:00:00'],

            // Sept 20
            ['movie_id' => 7, 'hall_id' => 1, 'show_time' => '2025-09-20 14:00:00'],
            ['movie_id' => 8, 'hall_id' => 2, 'show_time' => '2025-09-20 14:15:00'],
            ['movie_id' => 2, 'hall_id' => 3, 'show_time' => '2025-09-20 14:30:00'],
            ['movie_id' => 5, 'hall_id' => 1, 'show_time' => '2025-09-20 17:15:00'],
            ['movie_id' => 10, 'hall_id' => 2, 'show_time' => '2025-09-20 17:30:00'],
            ['movie_id' => 6, 'hall_id' => 3, 'show_time' => '2025-09-20 17:45:00'],
            ['movie_id' => 3, 'hall_id' => 1, 'show_time' => '2025-09-20 20:30:00'],
            ['movie_id' => 9, 'hall_id' => 2, 'show_time' => '2025-09-20 20:45:00'],
            ['movie_id' => 4, 'hall_id' => 3, 'show_time' => '2025-09-20 21:00:00'],

            // Sept 21
            ['movie_id' => 7, 'hall_id' => 1, 'show_time' => '2025-09-21 14:00:00'],
            ['movie_id' => 8, 'hall_id' => 2, 'show_time' => '2025-09-21 14:15:00'],
            ['movie_id' => 2, 'hall_id' => 3, 'show_time' => '2025-09-21 14:30:00'],
            ['movie_id' => 5, 'hall_id' => 1, 'show_time' => '2025-09-21 17:15:00'],
            ['movie_id' => 10, 'hall_id' => 2, 'show_time' => '2025-09-21 17:30:00'],
            ['movie_id' => 6, 'hall_id' => 3, 'show_time' => '2025-09-21 17:45:00'],
            ['movie_id' => 3, 'hall_id' => 1, 'show_time' => '2025-09-21 20:30:00'],
            ['movie_id' => 9, 'hall_id' => 2, 'show_time' => '2025-09-21 20:45:00'],
            ['movie_id' => 4, 'hall_id' => 3, 'show_time' => '2025-09-21 21:00:00'],

            // Sept 23
            ['movie_id' => 7, 'hall_id' => 1, 'show_time' => '2025-09-23 14:00:00'],
            ['movie_id' => 8, 'hall_id' => 2, 'show_time' => '2025-09-23 14:15:00'],
            ['movie_id' => 2, 'hall_id' => 3, 'show_time' => '2025-09-24 14:30:00'],
            ['movie_id' => 5, 'hall_id' => 1, 'show_time' => '2025-09-23 17:15:00'],
            ['movie_id' => 10, 'hall_id' => 2, 'show_time' => '2025-09-23 17:30:00'],
            ['movie_id' => 6, 'hall_id' => 3, 'show_time' => '2025-09-23 17:45:00'],
            ['movie_id' => 3, 'hall_id' => 1, 'show_time' => '2025-09-23 20:30:00'],
            ['movie_id' => 9, 'hall_id' => 2, 'show_time' => '2025-09-23 20:45:00'],
            ['movie_id' => 4, 'hall_id' => 3, 'show_time' => '2025-09-23 21:00:00'],
        ];

        // Load all seat ids once
        $seatIds = Seat::pluck('id')->toArray();
        if (empty($seatIds)) {
            $this->command->warn('No seats found. Please seed seats first.');
            return;
        }

        foreach ($schedules as $data) {
            $schedule = Schedule::create($data);

            // randomizer
            // $statuses = ['available', 'reserved', 'booked'];

            // prepare pivot rows for schedule_seats
            $now = Carbon::now();
            $rows = [];
            foreach ($seatIds as $seatId) {
                // small randomization: some seats may be occupied in seed
                $status = 'available';
                $rows[] = [
                    'schedule_id' => $schedule->id,
                    'seat_id' => $seatId,
                    'status' => $status,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // bulk insert pivot rows
            DB::table('schedule_seats')->insert($rows);
        }
    }
}
