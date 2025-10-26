<?php
// Author: Cheh Shu Hong
// StudentID: 23WMR14515

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\Schedule;

class HallAndSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks for seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data to prevent duplicates on each run
        Hall::truncate();
        Seat::truncate();
        
        // Truncate the schedule_seats pivot table directly
        DB::table('schedule_seats')->truncate();

        // Define hall data
        $hallsData = [
            [
                'hall_name' => 'Hall 1 (Standard)',
                'total_rows' => 10,
                'total_columns' => 12,
                'status' => 'active',
            ],
            [
                'hall_name' => 'Hall 2 (Standard)',
                'total_rows' => 10,
                'total_columns' => 12,
                'status' => 'active',
            ],
            [
                'hall_name' => 'Hall 3 (Standard)',
                'total_rows' => 10,
                'total_columns' => 12,
                'status' => 'active',
            ],
        ];
        
        // Create halls and their seats
        foreach ($hallsData as $hallData) {
            $hall = Hall::create($hallData);
            $this->createSeatsForHall($hall);
        }

        // --- CORRECTED SECTION: Seeding the pivot table using model relationships ---
        
        // Get all schedules that have been created by other seeders
        $schedules = Schedule::all();
        
        // Loop through each schedule
        foreach ($schedules as $schedule) {
            // Find all seats that belong to this schedule's hall
            $seatsInHall = $schedule->hall->seats;

            // Prepare the data to be attached to the pivot table
            // This is an array where the key is the seat ID and the value is the pivot data
            $pivotData = $seatsInHall->mapWithKeys(function ($seat) {
                return [$seat->id => ['status' => 'available']];
            })->toArray();
            
            // Use the sync method to attach all seats with the pivot data
            $schedule->seats()->sync($pivotData);
        }
        
        // --- END OF CORRECTED SECTION ---

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Helper method to create all seats for a given hall.
     *
     * @param \App\Models\Hall $hall
     */
    protected function createSeatsForHall(Hall $hall)
    {
        for ($row = 1; $row <= $hall->total_rows; $row++) {
            // Convert row number (1, 2, 3...) to a character ('A', 'B', 'C'...)
            $rowChar = chr(64 + $row);
            for ($col = 1; $col <= $hall->total_columns; $col++) {
                Seat::create([
                    'hall_id' => $hall->id,
                    'row_char' => $rowChar,
                    'seat_number' => $col,
                    'status' => 'active', // All seats are initially active
                ]);
            }
        }
    }
}