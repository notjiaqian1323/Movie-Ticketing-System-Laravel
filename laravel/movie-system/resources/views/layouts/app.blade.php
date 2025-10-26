<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Movie Ticketing System'))</title>

    <!-- Custom favicon -->
    <link rel="icon" href="{{ asset('storage/logos/logo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @yield('head')
</head>
<body>
<nav x-data="{ isOpen: false }"  class="fixed top-0 left-0 z-50 w-full flex items-center justify-between px-6 md:px-16 lg:px-36 py-5">
        <a class="max-md:flex-1" href="{{ route('home') }}">
            <img src="{{ asset('storage/logos/movie-logos.svg') }}" alt="Movie System Logo" class="w-50 h-auto">
        </a>
        <div x-bind:class="{ 'max-md:w-full': isOpen, 'max-md:w-0': !isOpen }"
        class="max-md:absolute max-md:top-0 max-md:left-0 max-md:font-medium
        max-md:text-lg z-50 flex flex-col md:flex-row items-center
        max-md:justify-center gap-8 min-md:px-8 py-3 max-md:h-screen
        min-md:rounded-full backdrop-blur bg-black/70 md:bg-white/10 md:border
        border-gray-300/20 overflow-hidden transition-[width] duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="md:hidden absolute top-6 right-6 w-6 h-6 cursor-pointer" x-on:click="isOpen = false">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
            
                <!--
                Name: Chong Ka Hong
                Student Id: 2314523
                -->
                {{-- Dynamic buttons based on user role --}}
                @php
                    use App\UserTypes\UserFactory;
                    use Illuminate\Support\Facades\Auth;
                    use Illuminate\Support\Facades\Route;

                    $userFactory = app(\App\UserTypes\UserFactory::class);
                    $role = Auth::check() ? Auth::user()->role : 'guest';
                    $navUser = $userFactory->create($role);
                    $buttons = $navUser->getHomeData()['buttons'] ?? []; // Get buttons from UserType
                @endphp

                @foreach ($buttons as $button)
                    @if (Route::has($button['route']))
                        
                            @if (($button['method'] ?? 'GET') === 'POST')
                                <form method="POST" action="{{ route($button['route']) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="text-white">
                                        {{ $button['label'] }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route($button['route']) }}" class="text-white" x-on:click="isOpen = false">
                                    {{ $button['label'] }}
                                </a>
                            @endif
                    @endif
                @endforeach

        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="max-md:ml-4 md:hidden w-8 h-8 cursor-pointer" x-on:click="isOpen = true">
            <line x1="4" y1="12" x2="20" y2="12"></line>
            <line x1="4" y1="6" x2="20" y2="6"></line>
            <line x1="4" y1="18" x2="20" y2="18"></line>
        </svg>
</nav>

{{-- Toast / flash messages --}}
@include('components.flash')

{{-- Push page content below the fixed header (adjust the padding to your header height) --}}
<main>
    @yield('content')
</main>

<x-footer />

@livewireScripts
</body>
</html>