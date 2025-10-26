<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace App\UserTypes;

abstract class UserType
{
    protected string $roleName; // Property to store the role name

    public function __construct(string $roleName)
    {
        $this->roleName = $roleName;
    }

    // New method to get the role name
    public function getRole(): string
    {
        return $this->roleName;
    }

    abstract public function getHomePage(): string;
    abstract public function getHomeData(): array;
    abstract public function canViewProfile(): bool;
    abstract public function canAccessAdminPanel(): bool;
}