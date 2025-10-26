<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
@if ($movie)
<div class='flex flex-col items-start justify-center gap-4 px-6 md:px-16 lg:px-36
    bg-cover bg-center h-screen relative'
    style="background-size: 35% 100%;
           background-position: right;
           background-repeat: no-repeat;">
    
<div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>

    <div class="z-50 flex-col items-center justify-center">
        <h1 class='text-5xl md:text-[65px] md:leading-18 font-semibold max-w-180'>
            {{ $movie->title }}
        </h1>

        <div class='text-l mt-8 mb-5 flex items-center gap-4 text-gray-300'>
            <div class='flex items-center gap-1'>
                <span class="mr-3">
                    {{ $movie->release_date 
                        ? \Carbon\Carbon::parse($movie->release_date)->format('d F Y') 
                        : 'Unknown Date' }}
                </span> | 
                <span class="text-primary ml-3 mr-3">{{ $movie->language ?? 'N/A' }}</span> | 
            </div>
            <div class='flex items-center gap-1'>
                <span>{{ $movie->duration ?? 'N/A' }} min</span>
            </div>
        </div>

        <p class='text-xl max-w-[35vw] text-gray-400 leading-tight mb-8'>
            {{ $movie->synopsis ?? 'No synopsis available' }}
        </p>
        
         <button class='flex items-center gap-1 px-6 py-3 text-xl bg-primary
        hover:bg-primary-dull transition rounded-md font-medium cursor-pointer mt-5'
        >
            <a href="{{ route('movies.listing') }}">Explore Movies</a>
        </button>
    </div>

</div>
@endif
