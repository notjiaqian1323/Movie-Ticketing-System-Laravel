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
    <link rel="icon" href="{{ asset('storage/logos//logo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @yield('head')
</head>
<body>
    <div>
        @if (session()->has('error'))
            <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 p-4 rounded-lg bg-red-500 text-white font-semibold shadow-xl">
                {{ session('error') }}
            </div>
        @endif

        @if (session()->has('success'))
            <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 p-4 rounded-lg bg-green-500 text-white font-semibold shadow-xl">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </div>

@livewireScripts
</body>
</html>