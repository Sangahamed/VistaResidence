<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $showDropdown = false;
    
    protected function getListeners()
    {
        $userId = Auth::id();
        
        return [
            "echo:notifications.{$userId},NotificationReceived" => 'refreshNotifications',
            'refreshNotifications' => 'refreshNotifications',
        ];
    }
    
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
        
        $this->unreadCount = $user->unreadNotifications->count();
        $this->notifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'] ?? 'Notification',
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => $notification->read_at !== null,
                    'url' => $this->getNotificationUrl($notification),
                ];
            });
    }
    
    public function getNotificationUrl($notification)
    {
        $data = $notification->data;
        
        switch ($data['type'] ?? '') {
            case 'new_property':
                return route('properties.show', $data['property_id'] ?? 0);
            case 'lead_assigned':
                return route('leads.show', $data['lead_id'] ?? 0);
            case 'visit_requested':
                return route('visits.show', $data['visit_id'] ?? 0);
            default:
                return route('notifications.index');
        }
    }
    
    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }
    
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            $this->refreshNotifications();
        }
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->refreshNotifications();
    }
    
    public function render()
    {
        return view('livewire.notification-bell');
    }
}