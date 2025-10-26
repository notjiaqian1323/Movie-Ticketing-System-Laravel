@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="relative mb-60 overflow-hidden min-h-[80vh]">

    <div class='flex flex-col relative items-start justify-center gap-4 px-6 md:px-16 lg:px-36
        bg-[url("{{ asset("storage/movies/omniscent.jpg") }}")] bg-contain bg-center h-[60vh]'
        style="background-image: url('{{ asset('storage/logos/logo-bg.png') }}');
            background-size: 100%;
            background-position: center;
            background-repeat:no-repeat;">

            <img src="{{ asset('storage/logos/logo-bg.png') }}" 
            alt="" 
            class="absolute inset-0 w-full h-full object-cover object-right z-0" {{-- z-0 to put it behind content --}}>

            <div class="absolute inset-0 bg-gradient-to-b from-black via-black/30 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#090908] via-black/30 to-transparent"></div>
            {{-- Black overlay from left (50%) --}}
            
            <div class="absolute inset-0 bg-gradient-to-r from-[#090908] via-[#090908]/40 to-transparent"></div>
            {{-- Black shadow overlay from bottom (20%) --}}
            

            <div class="relative bottom-5 top-[200px]">
            <h1 class='text-2xl md:text-[50px] md:leading-18 mb-5 font-semibold max-w-150'>MY ORDERS</h1>
            
        </div>
    </div>

    
    <div class="relative mt-30 mb-50 h-[30vh] w-full px-70 h-full">
        <x-blur-circle top='150px' left='0'/>
        <x-blur-circle bottom='30px' right='50px'/>


        @if($bookings->isEmpty())
            <p class="text-center text-gray-500 text-lg">You have no bookings yet.</p>
        @else
            <ul class="space-y-6">
                @foreach($bookings as $booking)
                    <li class="flex gap-8 justify-between p-6 bg-primary/10 border border-primary/20 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer">
                        
                        <div class=" h-full">
                            <img src="{{ asset('storage/movies/' . $booking->schedule->movie->image_path) }}" 
                                    class="max-md:mx-auto rounded-xl h-70 max-w-60 object-cover" 
                                    alt="{{ $booking->schedule->movie->title }}" 
                                    style="height: 220px; object-fit: cover;">
                        </div>

                        <div class="w-full">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                                <div class="mb-4 md:mb-0">
                                    <p class="text-2xl uppercase font-semibold text-primary">{{ $booking->schedule->movie->title }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="uppercase bg-green-600 text-white text-sm font-medium px-2 py-1 rounded-md">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="">
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class=""><span class="text-XL uppercase text-white">CCCWH THEATRES</span></p>
                                    <p>|</p>
                                    <p class="text-white"><span class="text-XL uppercase text-white">{{ $booking->schedule->hall->hall_name }}</span></p>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-white"><span class="text-XL uppercase text-white">{{ \Carbon\Carbon::parse($booking->schedule->show_time)->format('d M Y, g:i A') }}</span></p>
                                    <p>|</p>
                                    <p class="">
                                        <span class="text-XL uppercase text-white">
                                            {{ $booking->bookingSeats->pluck('seat.name')->implode(', ') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6 flex flex-col gap-4">
                                <p class="text-white"><span class="font-bold text-lg text-primary-dull">RM{{ number_format($booking->total_amount, 2) }}</span></p>
                                <a href="{{ route('bookings.receipt.show', ['booking' => $booking]) }}" class="w-fit bg-primary text-white px-6 py-2 rounded-md shadow-lg hover:bg-indigo-700 transition-colors duration-300 font-semibold">
                                    View Receipt
                                </a>
                            </div>
                        </div>

                    </li>
                @endforeach
            </ul>
            <!-- This is the single line to add for pagination -->
        @endif
    </div>
</div>


@endsection