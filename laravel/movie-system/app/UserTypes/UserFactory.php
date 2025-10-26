<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace App\UserTypes;
use App\Services\MovieService;

class UserFactory
{

    private MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function create(?string $role): UserType
    {
        return match (strtolower($role ?? 'guest')) {
            'admin' => new AdminUser(),
            'customer' => new CustomerUser($this->movieService),
            default => new GuestUser($this->movieService),
        };
    }
}
