<!-- Author: Cheh Shu Hong -->
<!-- StudentID: 23WMR14515 -->
 @extends('layouts.app')

@section('title', $movie->title . '| Schedules')

@section('content')
    <style>
        /* Keep the picker clickable but invisible */
        input[type="date"]::-webkit-calendar-picker-indicator {
            opacity: 0;
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.5em;
            height: 1.5em;
            cursor: pointer;
            z-index: 2;
        }
    </style>

    @if ($movie)
        <div class='flex flex-col relative items-start justify-center gap-4 px-6 md:px-16 lg:px-36
                                    bg-[url("{{ asset("storage/movies/omniscent.jpg") }}")] bg-contain bg-center h-[60vh]'
            style="background-image: url('{{ asset('storage/movies/' . $movie->image_path) }}');
                                        background-size: 30% 100%;
                                        background-position: right;
                                        background-repeat:no-repeat;">

            <img src="{{ asset('storage/movies/' . $movie->image_path) }}" alt="{{ $movie->title }}"
                class="absolute inset-0 w-full h-full object-cover object-right z-0" {{-- z-0 to put it behind content --}}>

            <div class="absolute inset-0 bg-gradient-to-b from-black via-black/30 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#090908] via-black/30 to-transparent"></div>
            {{-- Black overlay from left (50%) --}}

            <div class="absolute inset-0 bg-gradient-to-r from-[#090908] via-[#090908]/40 to-transparent"></div>
            {{-- Black shadow overlay from bottom (20%) --}}


            <div class="relative bottom-5 top-[100px]">
                <h1 class='text-5xl md:text-[70px] md:leading-18 mb-5 font-semibold max-w-150'>{{$movie->title}}</h1>

                <div class='flex items-center gap-4 text-xl mb-5'>
                    <span>{{ $movie->genre }}</span> |
                    <div class='flex items-center gap-1'>{{ $movie->language }}</div> |
                    <div class='flex items-center gap-1'>
                        @if ($movie->duration)
                            <span class="mr-1">{{ floor($movie->duration / 60) }} hr</span> <span
                                class="mr-1">{{ $movie->duration % 60 }} mins</span>
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-10">
            <div class="relative mb-60 mt-24 px-6 md:px-16 lg:px-18 xl:px-25 overflow-hidden min-h-[80vh]">
                {{-- === Schedule Filter / Sort Bar === --}}
                <form method="GET" action="{{ route('schedules.index', ['movieId' => $movie->id]) }}"
                    class="flex flex-col md:flex-row md:justify-between items-center mb-6 gap-4 text-white">
                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">

                    {{-- Hall, Dates, Availability --}}
                    <div class="flex flex-wrap gap-3 items-center">

                        {{-- Hall --}}
                        <div>
                            <label for="hall_id" class="block text-sm text-gray-300 mb-1">Hall</label>
                            <select id="hall_id" name="hall_id"
                                class="form-select w-56 bg-gray-950 text-white border-gray-700 rounded-lg py-3 px-4 hover:ring-1 hover:ring-primary transition">
                                <option value="" class="bg-gray-950 text-white">All Halls</option>
                                @foreach ($halls as $hall)
                                    <option value="{{ $hall->id }}" {{ ($filters['hall_id'] ?? '') == $hall->id ? 'selected' : '' }}
                                        class="bg-gray-950 text-white">
                                        {{ $hall->hall_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Start Date --}}
                        <div class="date-wrapper relative">
                            <label for="start_date" class="block text-sm text-gray-300 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] ?? '' }}"
                                min="{{ now()->toDateString() }}"
                                class="bg-gray-950 text-gray-200 border border-gray-700 rounded-lg py-3 pl-4 pr-10 w-44"
                                onchange="document.getElementById('end_date').min = this.value">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 absolute right-3 top-[2.6rem] text-white pointer-events-none" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 11h14m-14 8h14a2 2 0 002-2V7a2 2 0 
                            00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>

                        {{-- End Date --}}
                        <div class="date-wrapper relative">
                            <label for="end_date" class="block text-sm text-gray-300 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] ?? '' }}"
                                min="{{ $filters['start_date'] ?? now()->toDateString() }}"
                                class="bg-gray-950 text-gray-200 border border-gray-700 rounded-lg py-3 pl-4 pr-10 w-44">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 absolute right-3 top-[2.6rem] text-white pointer-events-none" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 11h14m-14 8h14a2 2 0 002-2V7a2 2 0 
                            00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>

                        {{-- Availability --}}
                        <div>
                            <label for="available" class="block text-sm text-gray-300 mb-1">Availability</label>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" id="available" name="available" value="1" class="peer hidden" {{ isset($filters['available']) ? 'checked' : '' }}>
                                <span
                                    class="w-5 h-5 rounded border border-gray-500 flex items-center justify-center
                                                                                                     peer-checked:bg-primary peer-checked:border-primary transition">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <span class="text-gray-300">Available Only</span>
                            </label>
                        </div>

                    </div>

                    {{-- Sorting & Apply --}}
                    <div class="flex flex-wrap gap-2 items-end">
                        <div>
                            <label for="sort" class="block text-sm text-gray-300 mb-1">Sort By</label>
                            <select id="sort" name="sort"
                                class="form-select bg-gray-950 text-white border-0 rounded-lg py-3 px-4 hover:ring-1 hover:ring-primary transition">
                                <option value="show_time_asc" {{ ($filters['sort'] ?? '') == 'show_time_asc' ? 'selected' : '' }}>
                                    Show Time (Earliest)
                                </option>
                                <option value="show_time_desc" {{ ($filters['sort'] ?? '') == 'show_time_desc' ? 'selected' : '' }}>
                                    Show Time (Latest)
                                </option>
                                <option value="available_desc" {{ ($filters['sort'] ?? '') == 'available_desc' ? 'selected' : '' }}>
                                    Available Seats (High → Low)
                                </option>
                                <option value="available_asc" {{ ($filters['sort'] ?? '') == 'available_asc' ? 'selected' : '' }}>
                                    Available Seats (Low → High)
                                </option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="h-[46px] px-4 bg-primary hover:bg-primary-dull text-white rounded-lg transition font-medium self-end">
                                Apply
                            </button>

                            <a href="{{ route('schedules.index', ['movieId' => $movie->id]) }}"
                                class="h-[46px] px-4 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition font-medium flex items-center justify-center">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>


                {{-- === Date Tabs (Top) === --}}
                <div class="flex w-h-14 text-5xl relative overflow-x-auto no-scrollbar">
                    @foreach ($dates as $date)
                        @php
                            $carbonDate = \Carbon\Carbon::parse($date);
                            $isToday = $carbonDate->isToday();
                        @endphp

                        <a href="{{ route('schedules.index', ['movieId' => $movie->id]) . '?' . http_build_query(array_merge(request()->except('date'), ['date' => $date])) }}"
                            class="flex flex-col max-w-[200px] items-center justify-center cursor-pointer transition-colors duration-300 px-4"
                            style="flex: 1 1 0%; width:calc((100%/3)-2px);">

                            <span class="text-xl font-medium {{ $isToday ? 'text-primary font-bold' : 'text-white' }}">
                                {{ $isToday ? 'Today' : $carbonDate->format('D') }}
                            </span>

                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-semibold text-white">
                                    {{ $carbonDate->format('d') }}
                                </span>
                                <span class="text-4xl font-semibold {{ $isToday ? 'text-primary' : 'text-gray-300' }}">
                                    {{ strtoupper($carbonDate->format('M')) }}
                                </span>
                            </div>

                            @if ($selectedDate === $date)
                                <div class="relative bottom-0 w-[100%] h-1 bg-primary transition-all duration-500 rounded-t-lg"></div>
                            @endif
                        </a>
                    @endforeach
                </div>

                {{-- === Time Slots for Selected Date === --}}
                <div class="md:flex-row md:px-16 lg:px-10 md:pt-10 gap-8">
                    <div class="w-full bg-primary/10 border border-primary/20 rounded-lg py-10 h-max md:sticky md:top-30 px-5">
                        @if ($showtimesForDate->isNotEmpty())
                            <div class="flex flex-wrap gap-4">
                                @foreach ($showtimesForDate as $sche)
                                        <a href="{{ route('bookings.create', ['movie' => $movie, 'date' => $selectedDate, 'id' => $sche->id ])  }}">
                                            <button class="px-4 font-semibold text-xl text-white w-40 h-20 rounded-lg cursor-pointer transition 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   {{ $selectedScheduleId === $sche->id
                                    ? 'bg-primary text-white'
                                    : 'bg-[#5C5C5C]/70 hover:bg-[#5C5C5C]' }}">
                                                {{ \Carbon\Carbon::parse($sche->show_time)->format('H:i') }}
                                            </button>
                                        </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-400 p-4">No showtimes available for this date.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @else
        <x-loading />
    @endif


@endsection