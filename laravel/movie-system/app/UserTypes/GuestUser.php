<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace App\UserTypes;

use Illuminate\Support\Facades\Http;
use App\Services\MovieService;

use App\Http\Controllers\Api\MovieApiController;
use Illuminate\Http\Request;

class GuestUser extends UserType
{

    private MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        parent::__construct('guest');
        $this->movieService = $movieService;
    }

    public function getHomePage(): string
    {
        return 'home'; // Main public homepage view
    }

    public function getHomeData(): array
    {
        $data = $this->movieService->getHomepageData();

        return [
            'buttons' => [
                ['label' => 'Home', 'route' => 'home'],
                ['label' => 'Movies', 'route' => 'movies.listing'],
                ['label' => 'Login', 'route' => 'login'],
                ['label' => 'Register', 'route' => 'register'],
            ],
            'popularMovies' => $data['popular'],
            'activeMovies' => $data['active_listings'],
        ];
    }

    public function canViewProfile(): bool
    {
        return false;
    }

    public function canAccessAdminPanel(): bool
    {
        return false;
    }
}