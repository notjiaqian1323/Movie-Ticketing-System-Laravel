{{-- Name: HO YI VON --}}
{{-- Student ID : 23WMR14542 --}}

@extends('layouts.app')

@section('title', 'Home')

@section('head')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')

@if (!empty($homeData['popularMovies']))

<div
    x-data="{ index: 0, slides: {{ json_encode($homeData['popularMovies']) }} }"
    x-on:set-index.window="index = $event.detail"
    class="popular-section relative w-full h-screen overflow-hidden rounded-2xl shadow-lg"
>
    {{-- Background Container --}}
    <div class="popular-bg absolute inset-0 bg-cover bg-center" id="popularBg"
         style="background-image: url('{{ $homeData['popularMovies'][0]['image_url'] ?? asset('storage/icons/default.jpg') }}');">
    </div>

<div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/30 to-transparent"></div>


    {{-- Hero slides --}}
        @foreach ($homeData['popularMovies'] as $i => $slide)
            <div
                x-show="index === {{ $i }}"
                x-transition.opacity.duration.1000ms
                class="absolute inset-0"
            >
                <div class="absolute inset-0 flex items-center px-16">
                    <div class="w-3/5 pr-8">
                        {{-- ✅ Pass one movie to HeroSection --}}
                        <x-hero-section :movie="$slide" />
                    </div>
                    <div class="w-2/5"></div>
                </div>
            </div>
        @endforeach

    {{-- Poster carousel --}}
    <div class="absolute inset-0 flex items-center px-16 pointer-events-none">
        <div class="w-2/5 ml-auto pointer-events-auto">
            <div class="poster-carousel" id="posterCarousel">
                @foreach ($homeData['popularMovies'] as $index => $movie)
                    <div class="poster-card {{ $index === 0 ? 'active' : '' }}"  
                        data-index="{{ $index }}"
                        data-title="{{ $movie['title'] }}"
                        data-genre="{{ $movie['genre'] }}"
                        data-link="{{ route('movies.show', $movie['id']) }}"
                        data-bg="{{ $movie['image_url'] }}">
                        <img src="{{ $movie['image_url'] }}" alt="{{ $movie['title'] }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@else
    <div class="text-gray-400 text-center p-6">
        No popular movies available at the moment.
    </div>
@endif

<div class="px-6 md:px-16 lg:px-24 xl:px-44 overflow-hidden">
    <div class="relative flex items-center justify-between pt-20 pb-10">
        <x-blur-circle top="0" right="-80px" />
        <p class="text-gray-300 font-medium text-2xl">Now Showing</p>

        <a href="{{ route('movies.listing') }}"
        class="group flex items-center gap-2 text-2xl text-gray-300 cursor-pointer">
            View All
            <svg xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="group-hover:translate-x-0.5 transition w-7.5 h-7.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
            </svg>
        </a>
    </div>

    @if (empty($homeData['activeMovies']))
        <p class="text-gray-400">No movies currently showing.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($homeData['activeMovies']->take(6) as $movie)
                <x-movie-card :movie="$movie" />
            @endforeach
        </div>
        
        <div class="flex justify-center mt-20">
            <a href="{{ route('movies.listing') }}"
            class="px-10 py-3 text-sm bg-primary hover:bg-primary-dull
                transition rounded-md font-medium cursor-pointer">
            Show More
            </a>
        </div>
    @endif

</div>
@endsection