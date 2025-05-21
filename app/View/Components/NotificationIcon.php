<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationIcon extends Component
{
    public $type;

    public function __construct($type)
    {
        $this->type = $this->normalizeType($type);
    }

    protected function normalizeType($type)
    {
        // Convertit "App\Notifications\PropertyFavorited" en "property-favorited"
        return str($type)
            ->afterLast('\\')
            ->kebab()
            ->toString();
    }

    public function render()
    {
        return view('components.notification-icon');
    }
}
