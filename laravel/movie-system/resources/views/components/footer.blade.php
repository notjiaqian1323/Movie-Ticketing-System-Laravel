<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
<footer class="px-6 md:px-16 lg:px-36 mt-20 w-full text-gray-300">
    <div class="flex flex-col md:flex-row justify-between w-full gap-6 border-b border-gray-500 pb-8">

        {{-- Left Section: Logo + Text + Store Badges --}}
        <div class="md:max-w-96">
            <img alt="logo" class="w-36 h-auto" src="{{ asset('storage/logos/movie-logos.svg') }}">

            <p class="mt-4 text-sm">
                CCCWH movie-system brings you the latest movies and streaming experiences, all in one place.
            </p>

            <div class="flex items-center gap-2 mt-3">
                <img src="{{ asset('storage/logos/googlePlay.svg') }}" alt="google play" class="h-8 w-auto">
                <img src="{{ asset('storage/logos/appStore.svg') }}" alt="app store" class="h-8 w-auto">
            </div>
        </div>

        {{-- Right Section: Links + Contact --}}
        <div class="flex-1 flex items-start md:justify-end gap-10 md:gap-20">
            <div>
                <h2 class="font-semibold mb-3">Company</h2>
                <ul class="text-sm space-y-1">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/') }}">About us</a></li>
                    <li><a href="{{ url('/') }}">Contact us</a></li>
                    <li><a href="{{ url('/') }}">Privacy policy</a></li>
                </ul>
            </div>

            <div>
                <h2 class="font-semibold mb-3">Get in touch</h2>
                <div class="text-sm space-y-1">
                    <p>+1-234-567-890</p>
                    <p>contact@example.com</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer bottom text --}}
    <p class="pt-3 text-center text-sm pb-4">
        © {{ date('Y') }} CCCWH. All Rights Reserved.
    </p>
</footer>