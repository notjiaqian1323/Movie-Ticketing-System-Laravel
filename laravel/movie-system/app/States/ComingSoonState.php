<?php
//Name: HO YI VON
//Student ID : 23WMR14542

namespace App\States;
use App\Models\Movie;

// Booking Allowed?             False
// Add to Popular List?         True
// Write a Review?              False
// Remove from Active Listing?  False (It's not yet active)
// Add to Active Listing? True (Transitions to Now Showing)

class ComingSoonState implements MovieState
{
    public function displayStatus(Movie $movie): string
    {
        return "Coming Soon";
    }

    public function isBookingAllowed(Movie $movie): bool
    {
        return false;
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
        return false;
    }

    public function removeFromActiveListing(Movie $movie): string
    {
        return "{$movie->title} cannot be removed from active listings; it is not active.";
    }

    public function addToActiveListing(Movie $movie): string
    {
        $movie->updateStatus('now_showing');
        return "{$movie->title} added to active listings.";
    }
}