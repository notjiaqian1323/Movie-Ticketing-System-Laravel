<?php

namespace App\Http\Controllers;
use App\UserTypes\UserFactory;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Illuminate\Support\Facades\Auth;
// No need to use App\UserTypes\UserFactory directly here anymore

class HomeController extends Controller
{
    private UserFactory $userFactory;

    /**
     * @param UserFactory $userFactory
     */
    public function __construct(UserFactory $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index()
    {
        $loggedInUser = Auth::user();
        $userTypeInstance = $loggedInUser
        ? $this->userFactory->create($loggedInUser->role)
        : $this->userFactory->create(null);
        $homeData = $userTypeInstance->getHomeData();

        return view($userTypeInstance->getHomePage(), [
            'role' => $userTypeInstance->getRole(),
            'homeData' => $homeData,
        ]);
    }
}