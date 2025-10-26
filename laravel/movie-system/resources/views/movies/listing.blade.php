{{-- Name: HO YI VON --}}
{{-- Student ID : 23WMR14542 --}}

@extends('layouts.app')

@section('title', 'Movie Listings')

@section('content')
<div class="relative my-40 mb-60 px-6 md:px-16 lg:px-40 xl:px-44 overflow-hidden min-h-[80vh]">
    <x-blur-circle top='150px' left='0'/>
    <x-blur-circle bottom='50px' right='50px'/>

    {{-- Filters --}}
    <div class="flex flex-col md:flex-row md:justify-between items-center mb-6 gap-4">
        {{-- Status Filter --}}
        <div class="flex flex-wrap gap-2">
            @foreach(['now_showing','coming_soon','archived','re_released'] as $status)
                <a href="{{ route('movies.listing', array_merge(request()->all(), ['status' => $status])) }}"
                   class="px-4 py-2 rounded-lg border font-medium
                   {{ request('status') === $status ? 'bg-green-600 text-white border-green-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-green-100' }}">
                    {{ ucwords(str_replace('_', ' ', $status)) }}
                </a>
            @endforeach
            <a href="{{ route('movies.listing') }}"
               class="px-4 py-2 bg-primary hover:bg-primary-dull  text-white rounded-lg hover:bg-gray-400 transition font-medium">
               Reset
            </a>
        </div>

        {{-- Genre & Search Form --}}
        <form method="GET" action="{{ route('movies.listing') }}" class="flex flex-wrap items-center gap-2">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <select name="genre"
                    class="form-select bg-gray-950 text-white border-0 rounded-lg py-3 px-4 hover:ring-1 hover:ring-primary transition"
                    onchange="this.form.submit()">
                <option value="">All Genres</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                        {{ ucfirst($genre) }}
                    </option>
                @endforeach
            </select>

            <div class="flex items-center w-[250px] p-[10px] rounded-lg bg-gray-950 focus-within:ring-1 focus-within:ring-primary transition">
                <input x-data x-model="search" type="text" name="search"
                       value="{{ request('search') }}"
                       class="ml-2 text-gray-300 border-none bg-transparent w-full focus:outline-none"
                       placeholder="Search by title...">
                <button type="submit" class="ml-2 text-gray-400 hover:text-white">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </div>

        </form>
    </div>

    {{-- Movie Grid --}}
    @if($movies->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($movies as $movie)
                {{-- Pass array as prop --}}
                <x-movie-card :movie="$movie" />
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $movies->withQueryString()->links('pagination::tailwind') }}
        </div>
    @else
        <x-loading />
    @endif
</div>
@endsection
