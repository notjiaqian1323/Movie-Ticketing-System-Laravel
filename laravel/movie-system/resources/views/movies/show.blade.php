{{-- Name: HO YI VON --}}
{{-- Student ID : 23WMR14542 --}}

@extends('layouts.app')

@section('title', $movie['title'])

@section('content')

@if ($movie)
<div class="px-6 md:px-16 lg:px-40 pt-30 md:pt-50">
    <div class="flex flex-col md:flex-row gap-8 max-w-6xl">
        @if ($movie['image_path'])
            <img src="{{ asset('storage/movies/' . $movie['image_path']) }}" 
                class="max-md:mx-auto rounded-xl h-104 max-w-100 object-cover" 
                alt="{{ $movie['title'] }}" 
                style="height: 100%; object-fit: cover;">
        @else
            <div class="max-md:mx-auto rounded-xl h-104 max-w-70">
                <x-loading />
            </div>
        @endif
        <div class='relative flex flex-col gap-3'>
            <x-blur-circle top='-100px' left='-100px'/>
            <p class="text-xl"><span class="mr-3">{{ \Carbon\Carbon::parse($movie['release_date'])->format('d F Y') }}</span> | <span class="text-primary ml-3">{{ $movie['language'] }}</span></p>
            <h1 class='text-4xl font-semibold max-w-130 text-balance'>{{ $movie['title'] }}</h1>
            <div class="mb-3">
                @if ($movieStatus)
                    <span class="text-lg font-bold text-green-300">{{ $movieStatus }}</span>
                @endif
            </div>
            <div class='flex items-center gap-2 text-gray-300'>
                    <img src="{{ asset('storage/icons/star.svg') }}" alt="" class="w-5 h-5">
                    <span class="fs-5 text-warning">{{ number_format($reviews_avg_rating ?? 0, 1) }}/5</span>
                    <!-- <span class="ml-2">User Rating</span> -->
            </div>
            <p class='text-gray-400 mt-2 text-xl leading-tight max-w-xl'>{{ $movie['synopsis'] }}</p>
            <p class="mt-2 mb-3"><span class="text-xl font-semibold">Genre: </span><br/><span>{{ $movie['genre'] }}</span></p>
            <p class="mb-3"><span class="text-xl font-semibold">Director: </span><br/><span>{{ $movie['director'] }}</span></p>
            <p class="mb-3"><span class="text-xl font-semibold">Rating: </span><br/><span>{{ $movie['age_rating'] }}</span></p>
            <p class="mb-3"><span class="text-xl font-semibold">Duration: </span><br/><span>{{ $movie['duration'] }} min</span></p>
            <p class="mb-3"><span class="text-xl font-semibold">Subtitles: </span><br/><span>{{ $movie['subtitles'] ?: 'N/A' }}</span></p>
        </div>

    </div>

    <p class='text-xl font-medium mt-20'>Your Favourite Cast</p>
    <div class="overflow-x-auto no-scrollbar mt-8 pb-4">
        <div class="flex items-center gap-4 w-max px-4">
            @foreach(explode(',', $movie['cast']) as $cast)
                <div class="flex flex-col items-center text-center flex-shrink-0">
                    <img src="{{ asset('storage/icons/female.png') }}" alt="{{ trim($cast) }}" class="rounded-full h-20 md:h-20 aspect-square object-cover" />
                    <p class="font-medium mt-3 text-xs text-white">{{ trim($cast) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div>



<div class="mt-16">

    {{-- Handle Coming Soon separately (no review list at all) --}}
    @if ($movieStatus === 'Coming Soon')
        <div class="alert alert-info mt-3">
            Booking is not allowed. It is {{ $movieStatus }}.
        </div>
        
    @else
        {{-- All other states (archived, now_showing, re_released) show the list --}}
        @if ($movieBookingPermission)
            @auth
                @if (auth()->user()->role === 'customer')
                    @if ($movieReviewPermission)
                        @if (!$already)
                            {{-- Review form --}}
                            <div class="review-wrapper mb-8">
                                @include('reviews._form', ['movie' => $movie])
                            </div>
                        @else
                            {{-- Already reviewed --}}
                            <div class="alert alert-secondary mt-3">
                                You already reviewed this movie. Manage it in
                                <a href="{{ route('reviews.history') }}" class="alert-link text-primary text-decoration-underline">
                                    My Reviews
                                </a>.
                            </div>
                        @endif
                    @endif
                @elseif (auth()->user()->role === 'admin')
                    {{-- Admin cannot review --}}
                    <div class="alert alert-info mt-3">
                        Admins can’t write reviews. Go to
                        <a href="{{ route('admin.reviews.index') }}" class="alert-link text-primary text-decoration-underline">
                            Manage Reviews
                        </a>.
                    </div>
                @endif
            @else
                {{-- Guest user --}}
                <div class="alert alert-info mt-3">
                    Please <a href="{{ route('login') }}" class="alert-link text-primary text-decoration-underline">log in</a> to write a review.
                </div>
            @endauth

        @else
            {{-- Booking not allowed (archived, ended, etc) --}}
            <div class="alert alert-info mt-3">
                Booking is not allowed. It is {{ $movieStatus }}.
            </div>
        @endif

        {{-- Always show review list (except Coming Soon) --}}
        <div class="review-wrapper mt-8">
            @include('reviews._list', [
                'reviews' => $reviewsWithUsers,
                'returnTo' => route('movies.show', $movie) . '#reviews'
            ])
        </div>
    @endif
</div>




        <style>
            /* Adjust review card backgrounds so they show on black page */
            .review-wrapper .review-card-dark {
                background-color: #ffffffff !important; /* slightly lighter than pure black */
                color: #282828ff;
            }
            .review-wrapper .review-card-dark .card-header {
                /* background-color: #d70000ff !important; */
                border: 1px solid white;
            }
            .review-wrapper .review-card-dark textarea,

        </style>


<p class='text-xl font-medium mt-20 mb-8'>You May Also Like</p>
@if($relatedMovies->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        {{-- ✅ Use a new variable name like $relatedMovie --}}
        @foreach ($relatedMovies as $relatedMovie)
            <x-movie-card :movie="$relatedMovie"/>
        @endforeach
    </div>
@else
    <div class="p-6 bg-gray-800 rounded text-center text-white border border-white">
        <p class="text-lg font-semibold">No recommendations yet.</p>
        <p class="text-gray-400 mt-1">We'll find some great movies for you soon!</p>
    </div>
@endif
</div>
@else
    <x-loading />
@endif

@if ($movieBookingPermission && $movieStatus !== 'Coming Soon')
    <div class="fixed bottom-8 right-8 z-50">
        <a href="{{ route('schedules.index', ['movieId' => $movie['id']]) }}"
           class="inline-flex items-center gap-3 px-6 py-2 bg-primary hover:bg-primary-dull 
                  transition rounded-lg font-semibold cursor-pointer shadow-xl">
            <img src="{{ asset('storage/icons/buy_ticket.png') }}" 
                 alt="Buy Ticket" 
                 class="w-14 h-14 object-contain"
                 style="filter: drop-shadow(0 0 0 white) drop-shadow(0 0 4px black);">
            <span class="text-lg">Buy Tickets</span>
        </a>
    </div>
@endif



@endsection
