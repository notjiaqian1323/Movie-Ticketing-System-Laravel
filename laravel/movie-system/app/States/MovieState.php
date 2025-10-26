<?php
//Name: HO YI VON
//Student ID : 23WMR14542
namespace App\States;

use App\Models\Movie;

interface MovieState
{
    public function displayStatus(Movie $movie): string;
    public function isBookingAllowed(Movie $movie): bool;
    public function addToPopularList(Movie $movie): string;
    public function removeFromPopularList(Movie $movie): string;
    public function writeReview(Movie $movie): bool;
    public function removeFromActiveListing(Movie $movie): string;
    public function addToActiveListing(Movie $movie): string;
}