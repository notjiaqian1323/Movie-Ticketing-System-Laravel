<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
<div class="flex flex-col justify-between p-3 bg-gray-700 rounded-2xl hover:-translate-y-1 transition duration-300 w-full">

    {{-- Movie Image --}}
    <a href="{{ route('movies.show', $movie['id']) }}">
        <div class="relative w-full pb-[140%] overflow-hidden rounded-lg">
            <img src="{{ $movie['image_url'] ?? asset('storage/icons/default.jpg') }}"
                 alt="{{ $movie['title'] }}"
                 class="absolute top-0 left-0 w-full h-full object-cover object-center cursor-pointer">
        </div>
    </a>

    {{-- Title --}}
    <p class="text-lg font-semibold mt-3 truncate">{{ $movie['title'] }}</p>

    {{-- Year • Genre • Duration + Rating --}}
    <div class="flex items-center justify-between mt-1 mb-1 text-sm text-gray-400">
        <p class="truncate">
            {{ $movie['release_date'] ? date('Y', strtotime($movie['release_date'])) : 'N/A' }} •
            {{ $movie['genre'] ?? 'N/A' }} •
            @if ($movie['duration'])
                {{ floor($movie['duration'] / 60) }}h {{ $movie['duration'] % 60 }}m
            @else
                N/A
            @endif
        </p>

        <div class="flex items-center">
            <span class="text-yellow-400">⭐ {{ number_format($movie['reviews_avg_rating'] ?? 0, 1) }}</span>
            <span class="text-white ml-1">({{ $movie['reviews_count'] ?? 0 }})</span>
        </div>
    </div>

    {{-- Bottom row: Buy Tickets --}}
    @if ($movie['booking_allowed'])
        {{-- Active Buy Button --}}
        <a href="{{ route('schedules.index', ['movieId' => $movie['id']]) }}"
        class="w-full block text-center px-2 py-2 text-[14px] bg-primary hover:bg-primary-dull 
                transition rounded-md font-medium cursor-pointer mt-1">
            Buy Tickets
        </a>
    @else
        {{-- Disabled / Coming Soon --}}
        <span class="w-full block text-center px-2 py-2 text-[14px] bg-gray-500 
                    rounded-md font-medium cursor-not-allowed mt-1 opacity-70">
            {{ $movie['movie_status']}}
        </span>
    @endif
</div>