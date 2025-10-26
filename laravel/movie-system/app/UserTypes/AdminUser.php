<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace App\UserTypes;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminUser extends UserType
{

    public function __construct()
    {
        parent::__construct('admin');
    }

    public function getHomePage(): string
    {
        return 'admin.accounts.panel';
    }

    public function getHomeData(): array
    {
        $users = Account::all();
        $totalUsers = $users->count();

        // --- Age groups logic ---
        $ageGroups = [
            '<18' => 0,
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-60' => 0,
            '60+' => 0,
        ];

        foreach ($users as $user) {
            if ($user->date_of_birth) {
                $age = Carbon::parse($user->date_of_birth)->age;
                if ($age < 18) {
                    $ageGroups['<18']++;
                } elseif ($age <= 25) {
                    $ageGroups['18-25']++;
                } elseif ($age <= 35) {
                    $ageGroups['26-35']++;
                } elseif ($age <= 45) {
                    $ageGroups['36-45']++;
                } elseif ($age <= 60) {
                    $ageGroups['46-60']++;
                } else {
                    $ageGroups['60+']++;
                }
            }
        }

        // --- Gender data ---
        $genderDataRaw = [
            'Female' => 0,
            'Male' => 0,
            'Unknown' => 0,
        ];

        foreach ($users as $user) {
            switch ($user->gender) {
                case 'F':
                    $genderDataRaw['Female']++;
                    break;
                case 'M':
                    $genderDataRaw['Male']++;
                    break;
                default:
                    $genderDataRaw['Unknown']++;
                    break;
            }
        }
        $genderLabels = array_keys($genderDataRaw);
        $genderData = array_values($genderDataRaw);


        // --- Status data ---
        $statusDataRaw = [
            'Active' => 0,
            'Inactive' => 0,
        ];

        foreach ($users as $user) {
            if ($user->status === 'active') {
                $statusDataRaw['Active']++;
            } elseif ($user->status === 'inactive') {
                $statusDataRaw['Inactive']++;
            }
        }
        $statusLabels = array_keys($statusDataRaw);
        $statusData = array_values($statusDataRaw);

        // percentage calculation
        if ($totalUsers > 0) {
            $agePercentages = array_map(function ($value) use ($totalUsers) {
                return round(($value / $totalUsers) * 100, 2);
            }, $ageGroups);

            $genderPercentages = array_map(function ($value) use ($totalUsers) {
                return round(($value / $totalUsers) * 100, 2);
            }, $genderDataRaw);

            $statusPercentages = array_map(function ($value) use ($totalUsers) {
                return round(($value / $totalUsers) * 100, 2);
            }, $statusDataRaw);
        } else {
            $agePercentages = array_fill_keys(array_keys($ageGroups), 0);
            $genderPercentages = array_fill_keys(array_keys($genderDataRaw), 0);
            $statusPercentages = array_fill_keys(array_keys($statusDataRaw), 0);
        }

        return [
            'buttons' => [
                ['label' => 'Manage Customers', 'route' => 'admin.accounts.panel'],
                ['label' => 'Manage Movies', 'route' => 'admin.movies.panel'],
                ['label' => 'Manage Schedules', 'route' => 'admin.schedules.panel'],
                ['label' => 'Manage Bookings', 'route' => 'admin.bookings.panel'],
                ['label' => 'Manage Reviews', 'route' => 'admin.reviews.index'],
                ['label' => 'Logout', 'route' => 'logout', 'method' => 'POST'],
            ],
            // Raw data
            'ageLabels' => array_keys($ageGroups),
            'ageGroups' => array_values($ageGroups),
            'genderLabels' => $genderLabels,
            'genderData' => $genderData,
            'statusLabels' => $statusLabels,
            'statusData' => $statusData,
            // Calculated percentages
            'agePercentages' => $agePercentages,
            'genderPercentages' => $genderPercentages,
            'statusPercentages' => $statusPercentages,
        ];
    }

    public function canViewProfile(): bool
    {
        return true;
    }

    public function canAccessAdminPanel(): bool
    {
        return true;
    }
}
