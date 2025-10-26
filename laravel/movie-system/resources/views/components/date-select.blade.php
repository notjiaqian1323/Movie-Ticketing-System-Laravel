<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
<div x-data="{
    selectedDate:null,
    showToast: false,
    toastMessage: '',
    
    // Mock data for schedules, matching your DB schema
    schedules: {
        '{{ \Carbon\Carbon::today()->toDateString() }}': [
            { id: 101, movie_id: '{{ $movie->id }}', hall_id: 1, show_time: '2025-08-31 10:00:00' },
            { id: 102, movie_id: '{{ $movie->id }}', hall_id: 1, show_time: '2025-08-31 13:30:00' },
            { id: 103, movie_id: '{{ $movie->id }}', hall_id: 1, show_time: '2025-08-31 17:00:00' },
        ],
        '{{ \Carbon\Carbon::tomorrow()->toDateString() }}': [
            { id: 201, movie_id: '{{ $movie->id }}', hall_id: 1, show_time: '2025-09-01 10:00:00' },
            { id: 202, movie_id: '{{ $movie->id }}', hall_id: 1, show_time: '2025-09-01 13:30:00' },
        ],
        '{{ \Carbon\Carbon::tomorrow()->addDay()->toDateString() }}': [
            { id: 301, movie_id: '{{ $movie->id }}', hall_id: 1, show_time: '2025-09-02 10:00:00' },
        ],
    },
    schedulesAndSeatsUrlBase: `{{ route('bookings.seats.show', ['movie' => $movie->id, 'date' => '__DATE_PLACEHOLDER__']) }}`,
    
    onBookHandler() {
        console.log('Activated!');
        if (!this.selectedDate) {
            this.toastMessage = 'Please select a date';
            console.log('Select Date!');
            this.showToast = true;
            setTimeout(() => this.showToast = false, 3000);
            return;
        }

        // Redirect to the correct URL with both movie and schedule IDs
        window.location.href = this.schedulesAndSeatsUrlBase.replace('__DATE_PLACEHOLDER__', this.selectedDate);
    },

    // A helper function to reset the schedule when a new date is picked
    selectDate(date) {
        this.selectedDate = date;
    }
}" class="pt-30">
    <p>Selected Date: <span x-text="selectedDate"></span></p>

    {{-- Date Selection --}}
    {{-- This is an example of how a user would select a date --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-10 relative p-8 bg-primary/10 border border-primary/20 rounded-lg">
        <x-blur-circle top='-100px' left='-100px'/>
        <x-blur-circle top='100px' right='0'/>
        <div>
            <p class='text-lg font-semibold'>
                    Choose Date
            </p>
            <div class="flex items-center gap-6 text-sm mt-5">
                @php
                    $dates = [
                        \Carbon\Carbon::today()->toDateString(),
                        \Carbon\Carbon::tomorrow()->toDateString(),
                        \Carbon\Carbon::tomorrow()->addDay()->toDateString(),
                    ];
                @endphp
                <span class='grid grid-cols-3 md:flex flex-wrap md:max-w-lg gap-4'>
                    @foreach($dates as $date)
                        <button type="button"
                            x-bind:data-date="{{ $date }}"
                            :class="{ 
                                'bg-primary text-white': selectedDate === $el.dataset.date,  
                                'border border-primary/70': selectedDate !== $el.dataset.date 
                            }"                  
                            class="flex flex-col items-center justify-center h-14 w-14 aspect-square rounded cursor-pointer"
                            @click="console.log('dataset.date =', $el.dataset.date); selectedDate = $el.dataset.date"
                        >
                            <span>{{ \Carbon\Carbon::parse($date)->format('M') }}</span>
                            <span>{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                        </button>
                    @endforeach
                </span>

            </div>
        </div>
        {{-- The "Book" button that triggers the handler --}}
        <button type="button"
            @click="onBookHandler"
            class="bg-primary text-white px-8 py-2 mt-6 rounded hover:bg-primary/90 transition-all cursor-pointer"
        >
            Book Now
        </button>
    </div>

</div>