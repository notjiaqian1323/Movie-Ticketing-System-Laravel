<?php
//Name: Wo Jia Qian
//Student Id: 2314023

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BlurCircle extends Component
{
    /**
     * Create a new component instance.
     */
    public $top;
    public $left;
    public $right;
    public $bottom;

    public function __construct($top = 'auto', $left = 'auto', $right = 'auto', $bottom = 'auto')
    {
        $this->top = $top;
        $this->left = $left;
        $this->right = $right;
        $this->bottom = $bottom;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.blur-circle');
    }
}
