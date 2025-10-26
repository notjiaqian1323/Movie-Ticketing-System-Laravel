<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace App\Http\Controllers\Admin;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AdminAccountController extends Controller
{
    // Display a listing of accounts/customers with filters and sorting
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $query = Account::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('username')) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }

        if (in_array($request->get('sort'), ['asc', 'desc'])) {
            $query->orderBy('username', $request->get('sort'));
        } else {
            $query->orderBy('id', 'desc');
        }

        $accounts = $query->paginate(10)->withQueryString();
        $userRole = Auth::user()->role;
        return view('admin.accounts.panel', compact('accounts', 'userRole'));
    }

    // Show create account form
    public function create()
    {
        $userRole = Auth::user()->role;
        return view('admin.accounts.create', compact('userRole'));
    }

    // Store new account
    public function store(Request $request)
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
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'gender' => 'required|in:F,M',
            'role' => 'required|in:customer,admin',
            'status' => 'required|in:active,inactive',
        ], [
            'phone.required' => 'Please provide a phone number.',
            'phone.unique' => 'This phone number is already registered.',
            'phone.max' => 'Phone number cannot exceed 15 characters.',
            'date_of_birth.required' => 'Please provide a date of birth.',
            'date_of_birth.date' => 'Please provide a valid date of birth.',
            'date_of_birth.before_or_equal' => 'You must be at least 13 years old.',
            'username.required' => 'Please enter a username.',
            'username.unique' => 'This username is already taken, please choose another.',
            'username.max' => 'Username cannot exceed 50 characters.',
            'email.required' => 'Please provide an email address.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'email.max' => 'Email cannot exceed 100 characters.',
            'password.required' => 'You must set a password.',
            'password.min' => 'Password should be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Gender must be either Male (M) or Female (F).',
            'role.required' => 'Please select a role.',
            'role.in' => 'Role must be either Customer or Admin.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Status must be either Active or Inactive.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Account::create([
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.accounts.panel')->with('success', 'User created successfully.');
    }

    // Show edit account form
    public function edit(Account $account)
    {
        $userRole = Auth::user()->role;
        return view('admin.accounts.edit', compact('account', 'userRole'));
    }

    // Update account
    public function update(Request $request, Account $account)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:15|unique:accounts,phone,' . $account->id,
            'date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . \Carbon\Carbon::now()->subYears(13)->toDateString(),
            ],
            'username' => 'required|string|max:50|unique:accounts,username,' . $account->id,
            'email' => 'required|email|max:100|unique:accounts,email,' . $account->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'gender' => 'required|in:F,M',
            'status' => 'required|in:active,inactive',
        ], [
            'phone.required' => 'Please provide a phone number.',
            'phone.unique' => 'This phone number is already registered.',
            'phone.max' => 'Phone number cannot exceed 15 characters.',
            'date_of_birth.required' => 'Please provide a date of birth.',
            'date_of_birth.date' => 'Please provide a valid date of birth.',
            'date_of_birth.before_or_equal' => 'You must be at least 13 years old.',
            'username.required' => 'Please enter a username.',
            'username.unique' => 'This username is already taken, please choose another.',
            'username.max' => 'Username cannot exceed 50 characters.',
            'email.required' => 'Please provide an email address.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'email.max' => 'Email cannot exceed 100 characters.',
            'password.min' => 'Password should be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Gender must be either Male (M) or Female (F).',
            'status.required' => 'Please select a status.',
            'status.in' => 'Status must be either Active or Inactive.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->filled('role') && $request->role !== $account->role) {
            return back()->withErrors(['role' => 'Changing role between Admin and Customer is not allowed.'])->withInput();
        }

        $data = $request->only([
            'phone',
            'date_of_birth',
            'username',
            'email',
            'gender',
            'status',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $data['role'] = $account->role;

        $account->update($data);

        return redirect()->route('admin.accounts.panel')->with('success', 'User updated successfully.');
    }

    // Toggle active/inactive status for an account
    public function toggleStatus(Account $account)
    {
        if (Auth::id() === $account->id) {
            return redirect()->route('admin.accounts.panel')->with('error', 'You cannot change your own status.');
        }

        $newStatus = $account->status === 'active' ? 'inactive' : 'active';
        $account->update(['status' => $newStatus]);

        return redirect()->route('admin.accounts.panel')->with('success', 'User status updated successfully.');
    }

    // Soft-delete / deactivate (sets status to inactive, keeps record)
    public function destroy(Account $account)
    {
        if (Auth::id() === $account->id) {
            return redirect()->route('admin.accounts.panel')->with('error', 'You cannot deactivate your own account.');
        }

        $account->update(['status' => 'inactive']);
        return redirect()->route('admin.accounts.panel')->with('success', 'User deactivated successfully.');
    }
}