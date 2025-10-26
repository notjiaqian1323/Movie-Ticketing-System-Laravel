<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace App\UserTypes;

use Illuminate\Support\Facades\Http;
use App\Services\MovieService;
use App\Http\Controllers\Api\MovieApiController;
use Illuminate\Http\Request;

class CustomerUser extends UserType
{

    private MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        parent::__construct('customer');
        $this->movieService = $movieService;
    }

    public function getHomePage(): string
    {
        return 'home'; // Customers also see the main 'home' view
    }

    public function getHomeData(): array
    {
        $data = $this->movieService->getHomepageData();

        return [
            'buttons' => [
                ['label' => 'Home', 'route' => 'home'],
                ['label' => 'Movies', 'route' => 'movies.listing'],
                ['label' => 'View Bookings', 'route' => 'bookings.index'],
                ['label' => 'MyReview', 'route' => 'reviews.history'],
                ['label' => 'View Profile', 'route' => 'profile.show'],
                ['label' => 'Logout', 'route' => 'logout', 'method' => 'POST'],
            ],
            'popularMovies' => $data['popular'],
            'activeMovies' => $data['active_listings'],
        ];
    }


    public function canViewProfile(): bool
    {
        return true;
    }

    public function canAccessAdminPanel(): bool
    {
        return false;
    }
}