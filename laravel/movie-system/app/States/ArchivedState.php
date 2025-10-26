<?php
namespace App\States;

//Name: HO YI VON
//Student ID : 23WMR14542

use App\Models\Movie;

// Booking Allowed?             False
// Add to Popular List?         False (It's already archived)
// Write a Review? True
// Remove from Active Listing?  False (It's already archived)
// Add to Active Listing?       True (Transitions to Re-Released)

class ArchivedState implements MovieState
{
    public function displayStatus(Movie $movie): string
    {
        return "Archived";
    }

    public function isBookingAllowed(Movie $movie): bool
    {
        return false;
    }

    public function addToPopularList(Movie $movie): string
    {
        return "{$movie->title} cannot be added to popular list; it is archived.";
    }

    public function removeFromPopularList(Movie $movie): string
    {
        return "{$movie->title} is not on the popular list.";
    }

    public function writeReview(Movie $movie): bool
    {
        return true;
    }

    public function removeFromActiveListing(Movie $movie): string
    {
        return "{$movie->title} cannot be removed from active listings; it is already archived.";
    }

    public function addToActiveListing(Movie $movie): string
    {
        $movie->updateStatus('re_released');
        return "{$movie->title} added to active listings for re-release.";
    }
}