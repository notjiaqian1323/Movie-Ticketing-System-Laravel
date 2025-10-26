<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #343a40;
            min-height: 100vh;
            color: white;
            flex-shrink: 0; /* Prevent sidebar from shrinking */
        }
        .sidebar a, .sidebar button {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            background: none;
            border: none;
            text-align: left;
            width: 100%;
        }
        .sidebar a:hover, .sidebar button:hover {
            background: #495057;
        }
        .content {
            flex-grow: 1; /* Allow content to fill the remaining space */
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h4 class="p-3">Admin Panel</h4>
        <hr style="border-color: white;">
        
        @php
            use App\UserTypes\UserFactory;
            use Illuminate\Support\Facades\Auth;
            use Illuminate\Support\Facades\Route;

            $userFactory = app(\App\UserTypes\UserFactory::class);
            $role = Auth::check() ? Auth::user()->role : 'guest';
            $navUser = $userFactory->create($role);
            $buttons = $navUser->getHomeData()['buttons'] ?? [];
        @endphp

        @foreach ($buttons as $button)
            @if (Route::has($button['route']))
                @if (($button['method'] ?? 'GET') === 'POST')
                    <form method="POST" action="{{ route($button['route']) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-link">
                            {{ $button['label'] }}
                        </button>
                    </form>
                @else
                    <a href="{{ route($button['route']) }}">
                        {{ $button['label'] }}
                    </a>
                @endif
            @endif
        @endforeach
        
    </div>

    <div class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>