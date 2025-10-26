<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
@extends('layouts.booking')

@section('title', 'Receipt')

@section('content')

<div class="relative h-[30vh] w-full px-5 pt-5 mb-40 h-screen bg-transparent">
    <x-blur-circle top='150px' left='0'/>
    <x-blur-circle bottom='30px' right='50px'/>

    <div class="flex items-center">
        <a href="{{ route('bookings.index') }}" class="hover:transform:scale-[110%] hover:text-primary transition font-semibold">
            <span class="px-5 text-xl"><</span>Go To My Bookings
        </a>
    </div>

    <img src="{{ asset('storage/logos/movie-logo.svg' ) }}" class="w-80 h-20 object-cover object-center mx-auto" alt="">
    
    <p class="text-center text-primary text-3xl mt-10">Your Ticket is Ready!</p>
    <p class="text-sm text-gray-500 mt-2 text-center">Confirmation for Booking #{{ $booking->id }}</p>
    <div class=" mx-auto p-4 md:p-8 rounded-xl shadow-lg">
        <div class=" md:flex-row md:px-16 lg:px-10  gap-8">
            <div class='flex w-full bg-primary/10 border border-primary/50 rounded-lg py-10 h-max md:sticky md:top-10 px-5'>
                <div class=" w-full mx-3">
                    <div class="flex flex-col md:flex-row gap-8">
                            
                            <img src="{{ asset('storage/movies/' . $booking->schedule->movie->image_path) }}" 
                                    class="max-md:mx-auto rounded-xl h-104 max-w-60 object-cover" 
                                    alt="Super Mario Bros" 
                                    style="height: 100%; object-fit: cover;">
                            <div class="w-full mr-8">
                                <div class='relative flex flex-col gap-3'>
                                    <x-blur-circle top='-100px' left='-100px'/>
                                    <p class="text-xl"><span class="mr-3">{{ \Carbon\Carbon::parse($booking->schedule->show_time)->format('M j, Y') }}</span> | <span class="text-primary ml-3">Eng</span></p>
                                    <h1 class='text-4xl font-semibold max-w-130 text-balance'>{{$booking->schedule->movie->title}}</h1>
                                    <div class="mt-3 flex flex-col">
                                        <span class="">Seats: {{ $booking->bookingSeats->pluck('seat.name')->implode(', ') }}</span>
                                    </div>
                                </div>
                                @php
                                    $ticketCounts = $booking->bookingSeats->groupBy('ticket_type')
                                    ->map(function ($items) {
                                    return [
                                    'count' => $items->count(),
                                    'price' => $items->first()->price,
                                    ];
                                    });
                                    @endphp

                                    @foreach ($ticketCounts as $type => $details)
                                    <div class="flex justify-between mt-4">
                                        <div class="flex flex-col justify-center items-start w-[100%]">
                                            <span class="text-xl capitalize">{{ strtolower($type) }}</span>
                                            <span class="text-gray-500">RM{{ number_format($details['price'], 2) }}</span>
                                        </div>
                                        <p>{{ $details['count'] }}</p>

                                    </div>
                                    @endforeach

                                    <div class="flex justify-between mt-4 pt-2">
                                        <p class="font-bold text-lg">TOTAL</p>
                                        <p class="font-bold text-lg">RM{{ number_format($booking->bookingSeats->sum('price'), 2) }}</p>
                                    </div>
                            </div>

                        </div>

                </div>
                <div class=" w-fit border-dashed border-l-2 h-full px-4 flex-col flex items-center justify-center pb-12">
                    <div>
                        <p class="text-center text-xl pb-20 pt-10">Scan QR Code</p>
                    </div>
                    <div class="bg-white w-fit p-1 ml-8">
                        <img class="w-50" src="data:image/png;base64,{{ DNS2D::getBarcodePNG($booking->qr_code, 'QRCODE') }}" alt="qrcode" />
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<x-footer />

@endsection