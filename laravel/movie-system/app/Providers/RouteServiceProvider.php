<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    protected $namespace = 'App\\Http\\Controllers';

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {


            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace) // Use the correct namespace
                ->group(base_path('routes/api.php'));
            
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // This is where you would add your custom route file
            Route::middleware(['web', 'auth'])
                ->prefix('booking')
                ->namespace($this->namespace)
                ->group(base_path('routes/booking.php'));
        });
    }
}