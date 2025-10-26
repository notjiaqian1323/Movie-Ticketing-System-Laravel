<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
<div>
    <div class="">
        <div class="max-w-full mx-auto">
            <div class="bg-transparent dark:bg-transparent overflow-hidden sm:rounded-lg">
                <div class="p-6 text-white dark:text-gray-100 ">
                    <div class="flex justify-between bg-transparent">
                    <div class="px-40 mr-8">
                            <img src="{{ asset('storage/logos/movie-logos.svg') }}" alt="Movie System Logo" class="w-28 h-auto">
                    </div>
                    <!-- Stepper -->
                    <ul class="relative flex flex-col md:flex-row gap-2 sticky top-0 w-[40%] justify-center ">
                    <!-- Item -->
                        
                        <li class="md:shrink md:basis-0 flex-1 group flex gap-x-2 md:block">
                            <div class="min-w-7 min-h-7 flex flex-col items-center md:w-full md:inline-flex md:flex-wrap md:flex-row text-xs align-middle">
                            <span class="size-7 flex justify-center items-center shrink-0 bg-primary-100 font-medium text-gray-800 rounded-full dark:bg-primary dark:text-white text-white bg-primary">
                                1
                            </span>
                            <div class="mt-2 w-px h-full md:mt-0 md:ms-2 md:w-full md:h-1 md:flex-1 {{ $this->currentStep <= 1 ? 'bg-neutral-700' : 'bg-primary' }} group-last:hidden rounded-full"></div>
                            </div>
                            <div class="grow md:grow-0 md:mt-3 pb-5">
                            <span class="block text-sm font-medium text-gray-800 dark:text-white">
                                Step
                            </span>
                            <p class="text-sm text-gray-500 dark:text-neutral-500">
                                Select Seats
                            </p>
                            </div>
                        </li>
                        <!-- End Item -->

                        <li class="md:shrink md:basis-0 flex-1 group flex gap-x-2 md:block">
                            <div class="min-w-7 min-h-7 flex flex-col items-center md:w-full md:inline-flex md:flex-wrap md:flex-row text-xs align-middle">
                            <span class="size-7 flex justify-center items-center shrink-0 bg-primary-100 font-medium text-gray-800 rounded-full {{ $currentStep < 2 ? 'text-white bg-neutral-700' : 'text-white bg-primary' }}">
                                2
                            </span>
                            <div class="mt-2 w-px h-full md:mt-0 md:ms-2 md:w-full md:h-1 md:flex-1 {{ $this->currentStep <= 1 ? 'bg-neutral-700' : 'bg-primary' }} group-last:hidden rounded-full"></div>
                            </div>
                            <div class="grow md:grow-0 md:mt-3 pb-5">
                            <span class="block text-sm font-medium text-gray-800 dark:text-white">
                                Step
                            </span>
                            <p class="text-sm text-gray-500 dark:text-neutral-500">
                                Checkout
                            </p>
                            </div>
                        </li>
                        <!-- End Item -->
                        <!-- Item -->
                        <li class="md:shrink md:basis-0 flex-1 group flex gap-x-2 md:block">
                            <div class="min-w-7 min-h-7 flex flex-col items-center md:w-full md:inline-flex md:flex-wrap md:flex-row text-xs align-middle">
                            <span class="size-7 flex justify-center items-center shrink-0 {{ $currentStep < 3 ? 'text-white bg-neutral-700' : 'text-white bg-primary' }} font-medium rounded-full">
                                3
                            </span>
                            <div class="mt-2 w-px h-full md:mt-0 md:ms-2 md:w-full md:h-1 md:flex-1 {{ $this->currentStep <= 3 ? 'bg-neutral-700' : 'bg-primary' }} group-last:hidden rounded-full"></div>
                            </div>
                            <div class="grow md:grow-0 md:mt-3 pb-5">
                            <span class="block text-sm font-medium text-gray-800 dark:text-white">
                                Step
                            </span>
                            <p class="text-sm text-gray-500 dark:text-neutral-500">
                                Success
                            </p>
                            </div>
                        </li>
                        <!-- End Item -->
                    </ul>
                    <div class="text-3xl font-bold px-8">
                            <a href="{{ route('schedules.index', ['movieId' => $movie['id']]) }}" class="hover:text-primary transition">X</a>
                    </div>
                    </div>
                    <!-- End Stepper -->

                    <!-- Stepper Content -->
                    <div class="mb-6 sm:mb-5">
                    <!-- First Content -->
                        <div class="{{ $currentStep == 1 ? 'block' : 'hidden' }}">
                            <div class="">
                                <div class='flex flex-col relative items-start justify-center gap-4 px-6 md:px-16 lg:px-36
                                    bg-[url("{{ asset("storage/movies/omniscent.jpg") }}")] bg-contain bg-center h-[30vh]'
                                    style="background-image: url('{{ asset('storage/movies/' . $movie['image_path']) }}');
                                        background-size: 30% 100%;
                                        background-position: right;
                                        background-repeat:no-repeat;">

                                        <img src="{{ asset('storage/movies/' . $movie['image_path']) }}" 
                                        alt="{{ $movie['title'] }}" 
                                        class="absolute inset-0 w-full h-full object-cover object-right z-0" {{-- z-0 to put it behind content --}}>

                                        <div class="absolute inset-0 bg-gradient-to-b from-black via-black/30 to-transparent"></div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#090908] via-black/30 to-transparent"></div>
                                        {{-- Black overlay from left (50%) --}}
                                        
                                        <div class="absolute inset-0 bg-gradient-to-r from-[#090908] via-[#090908]/40 to-transparent"></div>
                                        {{-- Black shadow overlay from bottom (20%) --}}
                                        

                                        <div class="relative bottom-5 top-[100px]">
                                        <h1 class='text-5xl md:text-[70px] md:leading-18 mb-5 font-semibold max-w-150'>{{$movie['title']}}</h1>

                                        <div class='flex items-center gap-4 text-xl mb-5'>
                                            <span>{{ $movie['genre'] }}</span> |
                                            <div class='flex items-center gap-1'>{{ $movie['language'] }}</div> |
                                            <div class='flex items-center gap-1'>
                                                @if ($movie['duration'])
                                                    <span class="mr-1">{{ floor($movie['duration'] / 60) }} hr</span>  <span class="mr-1">{{ $movie['duration'] % 60 }} mins</span> 
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class=" md:flex-row md:px-13 md:ml-25 lg:px-10 md:pt-10 gap-8 mt-50">
                                    <div class='w-full bg-primary/10 border border-primary/20 rounded-lg py-10 h-max md:sticky px-5'>
                                        <p class="text-xl text-primary-dull font-semibold pb-8 text-center">AVAILABLE <span class="">SHOWTIMES</span></p>
                                        @if ($showtimesForDate->isNotEmpty())
                                            <div>
                                                @foreach ($showtimesForDate as $sche)
                                                    
                                                    <button
                                                        wire:click="selectShowtime({{ $sche->id }})"
                                                        class="mr-5 px-4 bg-[#5C5C5C]/70 font-semibold text-xl text-left text-white w-40 h-20 rounded-lg cursor-pointer transition 
                                                        {{ $selectedScheduleId === $sche->id ? 'bg-primary text-white' : 'hover:bg-[#5C5C5C]' }}">
                                                        {{ \Carbon\Carbon::parse($sche->show_time)->format('H:i')}}
                                                    </button>
                                                
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-center text-gray-500 p-4">No showtimes available for this date.</p>
                                        @endif
                                </div>

                                <div class="relative flex-1 mt-40 flex flex-col items-center max-md:mt-40">
                                    
                                        <x-blur-circle top="-100px"  left="-100px"/>
                                        <x-blur-circle bottom="0px"  right="0px"/>
                                        <h1 class="text-2xl text-white font-semibold mb-4">Select Your Seat</h1>
                                        <img src="{{ asset('storage/icons/screenImage.svg') }}" class="w-[70%]" alt="screen" />
                                        <p class="text-gray-400 text-sm mb-6">SCREEN SIDE</p>
                                        
                                        
                                        <div wire:loading.remove wire:target="selectShowtime" class="flex flex-col items-center mt-10 text-xs text-gray-300 pb-40">
                                            @if ($seatData)
                                                <p class="mb-4">
                                                    <span>{{ count($selectedSeats) }}</span> / <span>{{ $maxSeats }}</span> seats selected
                                                </p>
                                                <div class="grid gap-2" style="grid-template-columns: repeat({{ $this->hall->total_columns }}, minmax(0, 1fr));">
                                                    @foreach ($seatData as $seat)
                                                        <button wire:click="handleSeatClick({{ $seat['id'] }})" wire:key="seat-{{ $seat['id'] }}"
                                                        
                                                        @class([
                                                            'h-12 w-12 mt-8 rounded transition-all duration-200 ease-in-out font-semibold text-xl relative cursor-pointer',
                                                            'bg-primary text-white shadow-lg' => in_array($seat['id'], $selectedSeats),
                                                            'bg-gray-400 text-gray-800 cursor-not-allowed' => isset($seat['status']) && $seat['status'] !== 'available',
                                                            'bg-gray-200 text-gray-800 cursor-pointer' => !in_array($seat['id'], $selectedSeats) && isset($seat['status']) && $seat['status'] === 'available',
                                                            'hover:bg-primary-dull cursor-pointer' => isset($seat['status']) && $seat['status'] === 'available',
                                                        ])
                                                        
                                                        @disabled(isset($seat->pivot) && $seat['status'] !== 'available') class="h-20 w-20 rounded transition-all duration-200 ease-in-out font-semibold text-xs relative cursor-pointer">
                                                            {{ $seat['name'] }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div wire:loading wire:target="selectShowtime" class="flex flex-col items-center mt-10 text-gray-400">
                                            <p>Loading seats...</p>
                                        </div>
                                    </div>
                                </div>
                                @if (count($selectedSeats) > 0)
                                    <div
                                        x-transition:enter="transition ease-out duration-300 transform"
                                        x-transition:enter-start="opacity-0 translate-y-full"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-200 transform"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 translate-y-full"
                                        class="fixed bottom-0 left-0 right-0 bg-secondary-900/90 backdrop-blur-md p-4 text-white flex justify-center items-center gap-4 border-t-2 border-primary/10"
                                        x-cloak
                                    >
                                        <p class="text-lg font-bold">Your Seats:</p>
                                        <p>{{ $this->getSelectedSeatsNamesProperty() }}</p>

                                        <button
                                            wire:click="openCheckoutModal"
                                            class="bg-primary text-white font-bold py-3 px-6 rounded-full transition hover:bg-primary/80"
                                        >
                                            Book Seat(s)
                                        </button>
                                    </div>
                                @endif

                                @if ($showModal)
                                <section 
                                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                    x-cloak
                                >
                                    {{-- The overlay --}}
                                    <div wire:click="$set('showModal', false)" class="absolute inset-0 bg-black/90 backdrop-blur-sm"></div>

                                    {{-- The modal box --}}
                                    <div class="relative bg-secondary-900 text-white rounded-lg shadow-xl p-8 max-w-lg w-full">
                                        <h2 class="text-2xl font-bold mb-4">Confirm Your Booking</h2>
                                        
                                        <div class="space-y-4 text-lg mb-6">
                                            <p><strong>Movie:</strong> <span>{{ $movie['title'] }}</span></p>
                                            <p><strong>Date:</strong> <span>{{ $this->selectedDate }}</span></p>
                                            <p><strong>Showtime:</strong> <span>{{ $this->selectedSchedule->show_time }}</span></p>
                                            <p>
                                                <strong>Selected Seats:</strong>
                                                <span>{{ $this->getSelectedSeatsNamesProperty() }}</span>
                                            </p>
                                        </div>
                                        
                                        {{-- Ticket Type Selection UI --}}
                                        <div class="space-y-4 text-lg">
                                            @foreach ($ticketCounts as $type => $count)
                                                <div class="flex items-center justify-between">
                                                    <p class="capitalize">{{ $type }}</p>
                                                    <div class="flex items-center space-x-2">
                                                        <button
                                                            type="button"
                                                            wire:click="updateTicketCount('{{ $type }}', -1)"
                                                            class="w-8 h-8 rounded-full flex items-center justify-center bg-gray-700 text-white"
                                                            @disabled($count === 0)
                                                        >
                                                            -
                                                        </button>
                                                        <span class="w-6 text-center">{{ $count }}</span>
                                                        <button
                                                            type="button"
                                                            wire:click="updateTicketCount('{{ $type }}', 1)"
                                                            class="w-8 h-8 rounded-full flex items-center justify-center bg-gray-700 text-white"
                                                        >
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                    </div>
                                    
                                    {{-- Total Count Display --}}
                                    <div class="mt-6 border-t border-gray-700 pt-4 flex justify-between items-center text-lg font-bold">
                                        <p>Total Tickets:</p>
                                        <p><span>{{ array_sum($this->ticketCounts) }}</span> / <span>{{ count($selectedSeats) }}</span> seats</p>
                                    </div>

                                    {{-- Error Message Display --}}
                                    @if (session()->has('error'))
                                        <div class="mt-4 p-3 bg-red-800 text-white rounded text-sm text-center">
                                            <p>{{ session('error') }}</p>
                                        </div>
                                    @endif
                                
                                    <div class="flex justify-between items-center mt-8 space-x-4">
                                                <button
                                                    type="button"
                                                    wire:click="$set('showModal', false)"
                                                    class="flex-1 py-3 px-6 rounded-full font-bold text-center border border-primary text-primary transition hover:bg-primary/20"
                                                >
                                                    Edit Seats
                                                </button>
                                                <button
                                                    wire:click="nextStep"
                                                    class="flex-1 py-3 px-6 rounded-full font-bold text-center bg-primary text-white transition hover:bg-primary/80"
                                                >
                                                                        
                                                    Go to Checkout
                                                </button>
                                            </div>
                                        </div>
                            </section>
                        @endif 
                    </div>
                    </div>
                        
                    <!-- End First Content -->

                    <!-- Second Content -->
                        <div class="{{ $currentStep == 2 ? 'block' : 'hidden' }}">
                            <div class='flex flex-col relative items-start justify-center gap-4 px-6 md:px-16 lg:px-36
                                    bg-[url("{{ asset("storage/movies/omniscent.jpg") }}")] bg-contain bg-center h-[30vh]'
                                    style="background-image: url('{{ asset('storage/movies/' . $movie['image_path']) }}');
                                        background-size: 30% 100%;
                                        background-position: right;
                                        background-repeat:no-repeat;">

                                        <img src="{{ asset('storage/movies/' . $movie['image_path']) }}" 
                                        alt="{{ $movie['title'] }}" 
                                        class="absolute inset-0 w-full h-full object-cover object-right z-0" {{-- z-0 to put it behind content --}}>

                                        <div class="absolute inset-0 bg-gradient-to-b from-black via-black/30 to-transparent"></div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#090908] via-black/30 to-transparent"></div>
                                        {{-- Black overlay from left (50%) --}}
                                        
                                        <div class="absolute inset-0 bg-gradient-to-r from-[#090908] via-[#090908]/40 to-transparent"></div>
                                        {{-- Black shadow overlay from bottom (20%) --}}
                                        

                                        <div class="relative bottom-5 top-[100px]">
                                        <h1 class='text-5xl md:text-[70px] md:leading-18 mb-5 font-semibold max-w-150'>{{ $movie['title'] }}</h1>

                                        <div class='flex items-center gap-4 text-xl mb-1'>
                                            @if ($this->selectedSchedule)
                                                    <span class="mr-1">{{ $this->getSelectedScheduleProperty()->hall->hall_name }}</span>
                                                @else
                                                    N/A
                                            @endif |
                                            <div class='flex items-center gap-1'>{{ $date }}, {{ \Carbon\Carbon::parse($this->getSelectedScheduleProperty()?->show_time)->format('h:i A')  }}</div> |
                                            <div class='flex items-center gap-1'>
                                                @if (count($selectedSeats) > 0)
                                                    <span class="mr-1">{{ $this->getSelectedSeatsNamesProperty() }}</span>
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class=" md:flex-row md:px-13 md:ml-25 lg:px-10 md:pt-10 gap-8 mt-30 mb-40">
                                    <div class='w-full bg-primary/10 border border-primary/20 rounded-lg py-10 h-max md:sticky md:top-30 px-5'>
                                        <p class="text-xl text-primary-dull font-semibold pb-8 text-center">
                                            Order <span class="">Details</span>
                                        </p>
                                        <div class="flex justify-between text-white">
                                            <p>SEATS</p>
                                            <p>{{ $this->getSelectedSeatsNamesProperty() }}</p>
                                        </div>
                                        
                                        
                                        @foreach ($this->ticketCounts as $type => $count)
                                            @if ($count > 0)
                                                <div class="flex justify-between mt-4">
                                                    <div class="flex flex-col justify-center items-start w-[40%]">
                                                        <span class="text-xl capitalize text-white">{{ $type }}</span>
                                                        <span class="text-gray-500">RM{{ number_format($this->ticketPrices[$type], 2) }}</span>
                                                    </div>
                                                    <p>{{ $count }}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                        
                                        <button wire:click="previousStep" class="flex items-center text-primary mt-5 text-xl cursor-pointer">
                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m15 18-6-6 6-6"></path>
                                            </svg>
                                            Edit Seats
                                        </button>

                                        <div class="flex justify-between mt-8 pt-4 border-t border-gray-700 text-white">
                                            <p class="font-bold text-lg">TOTAL</p>
                                            <p class="font-bold text-lg">RM{{ number_format($this->getTotalPriceProperty(), 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                        x-transition:enter="transition ease-out duration-300 transform"
                                        x-transition:enter-start="opacity-0 translate-y-full"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-200 transform"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 translate-y-full"
                                        class="fixed bottom-0 left-0 right-0 bg-secondary-900/90 backdrop-blur-md p-8 text-white flex justify-center items-center gap-4"
                                        x-cloak
                                    >
                                        <button
                                            type="button"
                                            wire:click="finalizeBooking"
                                            class="bg-primary text-white text-2xl font-semibold py-3 px-8 rounded-full transition hover:bg-primary/80 cursor-pointer"
                                        >
                                            CHECKOUT
                                            <span class="absolute inset-0 rounded-full bg-gradient-to-t from-black/40 via-black/20 to-transparent"></span>
                                        </button>
                                    </div>
                            </div>
                    <!-- End Second Content -->

                            <div class="{{ $currentStep == 3 ? 'block' : 'hidden' }}">
                                <div class="h-[30vh] bg-red">
                                    <div class="max-w-[75%] h-[70vh] mx-auto mt-35">
                                        <div class="flex items-center gap-10">
                                            <img src="{{ asset("storage/icons/success.svg") }}" class="w-25 h-auto" alt="">
                                            <span class="text-5xl font-semibold">ORDER SUCCESSFUL</span>
                                        </div>
                                        <div class="w-full px-8 mt-10 flex items-center">
                                            <span class="text-xl text-white">Congratulations! You have successfully booked your seats to watch <span class="text-primary-dull">{{ $movie['title'] }}</span> at CCCWH Theatres<br/>
                                            You can go to <a href="{{ route('bookings.index') }}" class="text-primary hover:text-primary-dull transition">My Bookings</a> to view your ticket!</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                    </div>

                     </div>
                </div>
            </div>
        </div>
    </div>                          
</div>
<!-- In your Blade file -->
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('next-step-scrolled', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
