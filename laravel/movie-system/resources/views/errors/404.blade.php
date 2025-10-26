<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 flex items-center justify-center min-h-screen text-center p-4">
    <div class="max-w-xl p-8 bg-gray-800 rounded-2xl shadow-lg border border-gray-700">
        <h1 class="text-6xl font-extrabold text-red-500 mb-4 animate-bounce">404</h1>
        <p class="text-xl text-gray-400 mb-6">Oops! The page you are looking for does not exist or may have been moved.</p>
        <p class="text-lg text-gray-500 mb-8">
            Redirecting you to the movies listing page...
            <span id="countdown" class="font-bold text-gray-300">5</span>
        </p>
        <a href="{{ route('movies.listing') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white font-semibold rounded-full shadow-md hover:bg-indigo-700 transition-colors duration-300 transform hover:scale-105">
            Go to Movies Listing
        </a>
    </div>

    <script>
        const countdownElement = document.getElementById('countdown');
        let countdown = 5;
    </script>
</body>
</html>