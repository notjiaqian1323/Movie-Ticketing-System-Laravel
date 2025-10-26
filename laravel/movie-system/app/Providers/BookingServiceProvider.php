<?php
//Name : Wo Jia Qian
//Student Id: 2314023

namespace App\Providers;

use App\Services\Booking\BookingFacade;
use App\Services\Payment\PaymentMediator;
use App\Services\Payment\Interfaces\PaymentGateway;
use App\Services\Payment\Adapters\StripePaymentAdapter;
use App\Services\Payment\DebitCardPaymentGateway;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Synthesizers\SeatSynthesizer;
use App\Models\Seat;


class BookingServiceProvider extends ServiceProvider{
        /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       
    }
}