@extends('layouts.app')

@section('content')
    <div class="flex items-start justify-center min-h-screen bg-gray-900 px-4 sm:px-6 lg:px-8 pt-20">
        <div class="w-full max-w-md p-8 bg-gray-900 text-white rounded-3xl shadow-lg border border-white">
            <!-- Title -->
            <h2 class="text-3xl font-bold text-center mb-6">Edit Profile</h2>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-600 text-white rounded-md shadow">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-600 rounded-md shadow">
                    <ul class="list-disc list-inside text-red-500">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Username -->
                <div>
                    <label for="username" class="block mb-1 font-semibold">Username</label>
                    <input type="text" name="username" id="username"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('username') border border-red-500 @enderror"
                        value="{{ old('username', $account->username) }}" placeholder="Enter username" required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border border-red-500 @enderror"
                        value="{{ old('email', $account->email) }}" placeholder="Enter your email" required>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block mb-1 font-semibold">Phone</label>
                    <input type="text" name="phone" id="phone"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border border-red-500 @enderror"
                        value="{{ old('phone', $account->phone) }}" placeholder="Enter your phone" required>
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block mb-1 font-semibold">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_of_birth') border border-red-500 @enderror"
                        value="{{ $account->date_of_birth ? \Carbon\Carbon::parse($account->date_of_birth)->format('Y-m-d') : old('date_of_birth') }}"
                        max="{{ \Carbon\Carbon::now()->subYears(13)->toDateString() }}">
                </div>

                <!-- Gender -->
                <div class="mb-8">
                    <label for="gender" class="block mb-1 font-semibold">Gender</label>
                    <select name="gender" id="gender"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gender') border border-red-500 @enderror">
                        <option value="" disabled {{ old('gender', $account->gender) == '' ? 'selected' : '' }}>
                            Select Gender
                        </option>
                        <option value="M" {{ old('gender', $account->gender) == 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ old('gender', $account->gender) == 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-1 font-semibold">New Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border border-red-500 @enderror"
                        placeholder="Enter new password">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block mb-1 font-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Confirm new password">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="relative w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold border border-white transition-all duration-300 group overflow-hidden transform hover:scale-105">
                    <span class="relative z-10">Save Changes</span>
                    <span
                        class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-white transition-all duration-500 group-hover:w-full group-hover:left-0"></span>
                    <span
                        class="absolute inset-0 bg-blue-500 opacity-0 group-hover:opacity-20 transition-opacity duration-500"></span>
                </button>
            </form>

            <!-- Deactivate Account -->
            <form action="{{ route('profile.delete') }}" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit"
                    onclick="return confirm('Are you sure you want to deactivate your account? This action cannot be undone.')"
                    class="relative w-full px-6 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-semibold border border-white transition-all duration-300 group overflow-hidden transform hover:scale-105">
                    <span class="relative z-10">Deactivate Account</span>
                    <span
                        class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-white transition-all duration-500 group-hover:w-full group-hover:left-0"></span>
                    <span
                        class="absolute inset-0 bg-red-500 opacity-0 group-hover:opacity-20 transition-opacity duration-500"></span>
                </button>
            </form>
        </div>
    </div>
@endsection