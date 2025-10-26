<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        Account::create([
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'phone' => '0123456789',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'gender' => 'M', 
            'date_of_birth' => '1990-01-01',
        ]);

        // Customer users
        Account::create([
            'username' => 'customeruser',
            'email' => 'customer@example.com',
            'phone' => '0987654321',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'status' => 'active',
            'gender' => 'M', 
            'date_of_birth' => '1990-01-01',
        ]);

        Account::create([
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'phone' => '01122334455',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'status' => 'active',
            'gender' => 'F', 
            'date_of_birth' => '2000-01-01',
        ]);

        Account::create([
            'username' => 'jane_smith',
            'email' => 'jane@example.com',
            'phone' => '01199887766',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'status' => 'inactive',
            'gender' => 'F', 
            'date_of_birth' => '1987-10-01',
        ]);

    }

}
