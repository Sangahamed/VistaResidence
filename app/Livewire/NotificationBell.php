<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadCount;
    public $notifications;

    protected $listeners = ['notificationReceived' => 'refreshNotifications'];

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $this->unreadCount = Auth::user()->unreadNotifications()->count();
        $this->notifications = Auth::user()->notifications()->take(5)->get();
    }

    public function markAsRead($id)
    {
        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}