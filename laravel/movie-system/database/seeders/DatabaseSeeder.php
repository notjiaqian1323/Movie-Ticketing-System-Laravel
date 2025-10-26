<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AccountSeeder::class,
            MoviesTableSeeder::class,
            ImageSeeder::class,
            HallAndSeatSeeder::class,
            ScheduleSeeder::class,
            HallAndSeatSeeder::class,
            ReviewsTableSeeder::class,
        ]);

    }
}
