<?php

namespace App\Providers;
use App\UserTypes\UserFactory;
use App\Services\MovieService;

use Illuminate\Support\ServiceProvider;
use App\Models\Review;
use App\Observers\ReviewObserver;
use App\Observers\ReviewAuditObserver;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(UserFactory::class, function ($app) {
            return new UserFactory($app->make(MovieService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Review::observe(ReviewObserver::class);
        Review::observe(ReviewAuditObserver::class);
        Paginator::useBootstrapFive();
    }
}
