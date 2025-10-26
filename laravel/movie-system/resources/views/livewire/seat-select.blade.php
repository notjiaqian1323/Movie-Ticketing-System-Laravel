<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
<div class="relative mb-60 px-6 md:px-16 lg:px-18 xl:px-25 overflow-hidden min-h-[80vh]">
    <div class="flex w-h-14 text-5xl relative overflow-x-auto no-scrollbar">
        @foreach ($dates as $date)
            <div wire:click="$set('selectedDate', '{{ $date }}')"
                class="flex flex-col max-w-[200px] items-center justify-center cursor-pointer transition-colors duration-300 px-4"
                style="flex: 1 1 0%; width:calc((100%/3)-2px);">

                <span class="text-xl font-medium text-white group-hover:text-gray-900">{{ \Carbon\Carbon::parse($date)->format('D') }}</span>
                <span class="text-4xl font-semibold">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>

                @if ($selectedDate === $date)
                    <div class="relative bottom-0 w-[100%] h-1 bg-primary transition-all duration-500 rounded-t-lg"></div>
                @endif
            </div>
        @endforeach
    </div>
    <div class=" md:flex-row md:px-16 lg:px-10 md:pt-10 gap-8">
        <div class='w-full bg-primary/10 border border-primary/20 rounded-lg py-10 h-max md:sticky md:top-30 px-5'>
            @if ($showtimesForDate->isNotEmpty())
                <div>
                    @foreach ($showtimesForDate as $sche)
                        <a href="{{ route('bookings.create', ['movie' => $movie, 'date' => $selectedDate, 'selected' => $sche->id ])  }}">
                        <button
                            class="mr-5 px-4 bg-[#5C5C5C]/70 font-semibold text-xl text-left text-white w-40 h-20 rounded-lg cursor-pointer transition 
                            {{ $selectedScheduleId === $sche->id ? 'bg-primary text-white' : 'hover:bg-[#5C5C5C]' }}">
                            {{ \Carbon\Carbon::parse($sche->show_time)->format('H:i')}}
                        </button>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 p-4">No showtimes available for this date.</p>
            @endif
        </div>

    </div>
    
    
</div>