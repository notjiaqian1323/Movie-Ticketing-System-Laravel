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

class AuthController extends Controller
{

    private UserFactory $userFactory;

    public function __construct(UserFactory $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',

        ]);

        $account = Account::where('email', $credentials['email'])->first();

        // Case 1: Account not found
        if (!$account) {
            return back()->withErrors([
                'email' => 'This email is not registered. Please sign up first.',
            ])->withInput();
        }

        // Case 2: Account inactive
        if ($account->status !== 'active') {
            return back()->withErrors([
                'email' => 'Your account is not active. Please contact support.',
            ])->withInput();
        }

        // Case 3: Wrong password
        if (!Hash::check($credentials['password'], $account->password)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect.',
            ])->withInput();
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = $this->userFactory->create(Auth::user()->role);
            return redirect()->route($user->getHomePage());

        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|unique:accounts,phone|max:15',
            'date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . \Carbon\Carbon::now()->subYears(13)->toDateString(),
            ],
            'username' => 'required|string|unique:accounts,username|max:50',
            'email' => 'required|email|unique:accounts,email|max:100',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // at least one uppercase
                'regex:/[a-z]/',      // at least one lowercase
                'regex:/[0-9]/',      // at least one number
                'regex:/[@$!%*#?&]/', // at least one special char
            ],
            'gender' => 'required|in:F,M',
        ], [
            // Custom messages
            'phone.required' => 'Please provide your phone number.',
            'phone.unique' => 'This phone number is already registered.',
            'phone.max' => 'Phone number cannot exceed 15 characters.',

            'username.required' => 'Please enter a username.',
            'username.unique' => 'This username is already taken, please choose another.',
            'username.max' => 'Username cannot exceed 50 characters.',

            'email.required' => 'We need your email to create an account.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered. Try logging in instead.',
            'email.max' => 'Email cannot exceed 100 characters.',

            'password.required' => 'You must set a password.',
            'password.min' => 'Password should be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase, one lowercase, one number, and one special character.',

            'date_of_birth.date' => 'Please provide a valid date of birth.',

            'gender.in' => 'Gender must be either Male (M) or Female (F).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $account = Account::create([
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($account);

        $user = $this->userFactory->create('customer');

        return redirect()->route($user->getHomePage());
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $guest = $this->userFactory->create('guest');

        return redirect()->route($guest->getHomePage());


    }
}
