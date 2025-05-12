<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavLink extends Component
{
    public $href;
    public $active;

    public function __construct($href, $active = false)
    {
        $this->href = $href;
        $this->active = $active;
    }

    public function render()
    {
        return view('components.nav-link');
    }
}