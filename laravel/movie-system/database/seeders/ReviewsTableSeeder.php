<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reviews')->insert([
            [
                'movie_id' => 6,  // make sure movie with ID 1 exists
                'account_id' => 1, // make sure account with ID 1 exists
                'rating' => 5,
                'comment' => 'Amazing movie! Highly recommend.',
                'is_anonymous' => false,
                'edited' => false,
                'review_datetime' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'movie_id' => 6,
                'account_id' => 2,
                'rating' => 3,
                'comment' => 'It was okay, not great but not bad either.',
                'is_anonymous' => true,
                'edited' => false,
                'review_datetime' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'movie_id' => 2,
                'account_id' => 1,
                'rating' => 4,
                'comment' => 'Pretty good overall, enjoyed the acting.',
                'is_anonymous' => false,
                'edited' => false,
                'review_datetime' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'movie_id' => 4,
                'account_id' => 2,
                'rating' => 1,
                'comment' => 'Bad Movie.',
                'is_anonymous' => false,
                'edited' => false,
                'review_datetime' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
