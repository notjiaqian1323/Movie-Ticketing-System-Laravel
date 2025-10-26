<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace App\Http\Controllers;

use App\Models\Account;
use App\UserTypes\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    private UserFactory $userFactory;

    public function __construct(UserFactory $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    public function show()
    {

        $user = $this->userFactory->create(Auth::user()->role);

        if (!$user->canViewProfile()) {
            abort(403, 'Unauthorized access.');
        }

        $account = Auth::user();
        return view('profile.show', compact('account'));
    }

    public function edit()
    {
        $user = $this->userFactory->create(Auth::user()->role);

        if (!$user->canViewProfile()) {
            abort(403, 'Unauthorized access.');
        }

        $account = Auth::user();
        return view('profile.edit', compact('account'));
    }

    public function update(Request $request)
    {
        $user = $this->userFactory->create(Auth::user()->role);

        if (!$user->canViewProfile()) {
            abort(403, 'Unauthorized access.');
        }

        $account = Auth::user();

        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:15|unique:accounts,phone,' . $account->id,
            'date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . \Carbon\Carbon::now()->subYears(13)->toDateString(), // must be 13+
            ],
            'username' => 'required|string|max:50|unique:accounts,username,' . $account->id,
            'email' => 'required|email|max:100|unique:accounts,email,' . $account->id,

            // password is optional, but if filled it must follow strong regex
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/', // at least one uppercase
                'regex:/[a-z]/', // at least one lowercase
                'regex:/[0-9]/', // at least one number
                'regex:/[@$!%*#?&]/' // at least one special character
            ],

            'gender' => 'required|in:F,M',
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'date_of_birth.before_or_equal' => 'You must be at least 13 years old.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $account->update([
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $account->password,
            'gender' => $request->gender,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }


    public function delete(Request $request)
    {
        $user = $this->userFactory->create(Auth::user()->role);

        if (!$user->canViewProfile()) {
            abort(403, 'Unauthorized access.');
        }

        $account = Auth::user();
        $account->update(attributes: ['status' => 'inactive']);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $guest = $this->userFactory->create('guest');
        return redirect()->route($guest->getHomePage())->with('success', 'Account deactivated successfully.');
    }
}