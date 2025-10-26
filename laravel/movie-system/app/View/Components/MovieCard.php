<?php
//Name: Wo Jia Qian
//Student Id: 2314023

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MovieCard extends Component
{
    /**
     * Create a new component instance.
     */
    public $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.movie-card');
    }
}
