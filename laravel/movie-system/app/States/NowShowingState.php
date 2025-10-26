<?php
//Name: HO YI VON
//Student ID : 23WMR14542
namespace App\States;

use App\Models\Movie;

// Booking Allowed? True
// Add to Popular List?             True
// Write a Review?                  True
// Remove from Active Listing?      True (Transitions to Archived)
// Add to Active Listing?           False (It's already active)

class NowShowingState implements MovieState
{
    public function displayStatus(Movie $movie): string
    {
        return "Now Showing";
    }

    public function isBookingAllowed(Movie $movie): bool
    {
        return true;
    }

    public function addToPopularList(Movie $movie): string
    {

        if($movie->is_popular){
            return "{$movie->title} is already in the popular list.";
        }

        $movie->is_popular = true;
        $movie->save();
        return "{$movie->title} added to popular list.";
    }

    public function removeFromPopularList(Movie $movie): string
    {

        if(!$movie->is_popular){
            return "{$movie->title} is not on the popular list.";
        }

        $movie->is_popular = false;
        $movie->save();
        return "{$movie->title} is removed from the popular list.";
    }

    public function writeReview(Movie $movie): bool
    {
        return true;
    }

    public function removeFromActiveListing(Movie $movie): string
    {
        $movie->updateStatus('archived');
        $movie->is_popular = false;
        $movie->save();
        return "{$movie->title} removed from active listings.";
    }

    public function addToActiveListing(Movie $movie): string
    {
        return "{$movie->title} is already in active listings.";
    }
}