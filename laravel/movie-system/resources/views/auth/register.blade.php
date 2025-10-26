{{--
Name: CHONG KA HONG
Student ID: 2314524
--}}
@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="flex items-start justify-center min-h-screen bg-gray-900 px-4 sm:px-6 lg:px-8 pt-20">
        <div class="w-full max-w-md p-8 bg-gray-900 text-white rounded-3xl shadow-lg border border-white">
            <!-- Title -->
            <h2 class="text-3xl font-bold text-center mb-6">Create Account</h2>

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

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Phone -->
                <div>
                    <label for="phone" class="block mb-1 font-semibold">Phone</label>
                    <input type="text" name="phone" id="phone"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border border-red-500 @enderror"
                        value="{{ old('phone') }}" placeholder="Enter your phone" required>
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block mb-1 font-semibold">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_of_birth') border border-red-500 @enderror"
                        value="{{ old('date_of_birth') }}" max="{{ \Carbon\Carbon::now()->subYears(13)->toDateString() }}"
                        <!-- Must be 13+ -->

                </div>


                <!-- Username -->
                <div>
                    <label for="username" class="block mb-1 font-semibold">Username</label>
                    <input type="text" name="username" id="username"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('username') border border-red-500 @enderror"
                        value="{{ old('username') }}" placeholder="Enter username" required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border border-red-500 @enderror"
                        value="{{ old('email') }}" placeholder="Enter your email" required>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-1 font-semibold">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border border-red-500 @enderror"
                        placeholder="Enter password" required>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block mb-1 font-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Confirm password" required>
                </div>

                <!-- Gender -->
                <div class="mb-8"> <!-- Added margin-bottom to increase spacing -->
                    <label for="gender" class="block mb-1 font-semibold">Gender</label>
                    <select name="gender" id="gender"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gender') border border-red-500 @enderror">
                        <option value="" selected>Select Gender</option>
                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                    </select>
                </div>

                <!-- Submit Button with white border and enhanced animation -->
                <button type="submit"
                    class="relative w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold border border-white transition-all duration-300 group overflow-hidden transform hover:scale-105">
                    <span class="relative z-10">Register</span>
                    <span
                        class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-white transition-all duration-500 group-hover:w-full group-hover:left-0"></span>
                    <span
                        class="absolute inset-0 bg-blue-500 opacity-0 group-hover:opacity-20 transition-opacity duration-500"></span>
                </button>
            </form>

            <!-- Additional Links with enhanced underline animation on hover -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-400">
                    Already have an account?
                    <a href="{{ route('login') }}"
                        class="relative inline-block font-semibold text-blue-400 hover:text-blue-300 transition-colors duration-300 group">
                        Sign in
                        <!-- underline span -->
                        <span
                            class="absolute left-0 -bottom-0.5 w-0 h-0.5 bg-blue-300 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </p>
            </div>

        </div>
    </div>
@endsection