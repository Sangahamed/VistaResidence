<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    
    protected $listeners = ['echo:user.{userId},notification' => 'refreshNotifications'];
    
    public function mount()
    {
        $this->refreshNotifications();
    }
    
    public function refreshNotifications()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }
        
        $this->unreadCount = $user->unreadNotifications()->count();
        $this->notifications = $user->unreadNotifications()->take(5)->get()->toArray();
    }
    
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            $this->refreshNotifications();
        }
    }
    
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        $this->refreshNotifications();
    }
    
    public function render()
    {
        return view('livewire.notification-bell');
    }
}