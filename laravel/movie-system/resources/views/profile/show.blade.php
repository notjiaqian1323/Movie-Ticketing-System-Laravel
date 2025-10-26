@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-900 px-4 sm:px-6 lg:px-8">


        <div class="w-full max-w-md p-8 bg-gray-900 text-white rounded-3xl shadow-lg border border-white">
            <div class="flex flex-col items-center gap-6">
                {{-- Profile Icon --}}
                <div class="flex-shrink-0">
                    <div
                        class="w-32 h-32 rounded-full bg-gray-700 flex items-center justify-center text-4xl font-bold text-gray-400">
                        {{ strtoupper(substr($account->username, 0, 1)) }}
                    </div>
                </div>

                {{-- Profile Details --}}
                <div class="w-full space-y-4 text-center">
                    <h1 class="text-3xl font-bold">{{ $account->username }}</h1>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-600 text-white rounded-lg shadow">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-2 text-gray-300">
                        <div><span class="font-semibold">Phone:</span> {{ $account->phone }}</div>
                        <div><span class="font-semibold">Email:</span> {{ $account->email }}</div>
                        <div><span class="font-semibold">Date of Birth:</span>
                            {{ $account->date_of_birth ? \Carbon\Carbon::parse($account->date_of_birth)->format('d F Y') : 'N/A' }}
                        </div>
                        <div><span class="font-semibold">Gender:</span>
                            {{ $account->gender == 'M' ? 'Male' : ($account->gender == 'F' ? 'Female' : 'N/A') }}</div>

                    </div>

                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('profile.edit') }}"
                            class="relative px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold border border-white transition-all duration-300 group overflow-hidden transform hover:scale-105">
                            <span class="relative z-10">Edit Profile</span>
                            <span
                                class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-white transition-all duration-500 group-hover:w-full group-hover:left-0"></span>
                            <span
                                class="absolute inset-0 bg-blue-500 opacity-0 group-hover:opacity-20 transition-opacity duration-500"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection