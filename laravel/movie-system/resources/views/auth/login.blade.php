{{--
Name: CHONG KA HONG
Student ID: 2314524
--}}
@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-900 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md p-8 bg-gray-900 text-white rounded-3xl shadow-lg border border-white">
            <!-- Title -->
            <h2 class="text-3xl font-bold text-center mb-6">Login</h2>

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

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border border-red-500 @enderror"
                        value="{{ old('email') }}" placeholder="Enter your email" required>
                </div>

                <!-- Password -->
                <div class="mb-8"> <!-- Added margin-bottom to increase spacing -->
                    <label for="password" class="block mb-1 font-semibold">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border border-red-500 @enderror"
                        placeholder="Enter your password" required>
                </div>

                <!-- Submit Button with white border and enhanced animation -->
                <button type="submit"
                    class="relative w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold border border-white transition-all duration-300 group overflow-hidden transform hover:scale-105">
                    <span class="relative z-10">Sign In</span>
                    <span
                        class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-white transition-all duration-500 group-hover:w-full group-hover:left-0"></span>
                    <span
                        class="absolute inset-0 bg-blue-500 opacity-0 group-hover:opacity-20 transition-opacity duration-500"></span>
                </button>
            </form>

            <!-- Additional Links with enhanced underline animation -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-400">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                        class="relative text-blue-400 font-semibold transition-all duration-300 group inline-block hover:text-blue-300 no-underline hover:no-underline">
                        <span class="relative z-10">Sign up</span>
                        <span
                            class="absolute bottom-0 left-0 w-0 h-1 bg-blue-300 transition-all duration-300 ease-in-out group-hover:w-full"></span>
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection