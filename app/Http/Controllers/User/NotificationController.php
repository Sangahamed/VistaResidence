<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PropertyNotification;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index()
    {
        $notifications = PropertyNotification::where('user_id', auth()->id())
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $unreadCount = PropertyNotification::where('user_id', auth()->id())
            ->unread()
            ->count();
            
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = PropertyNotification::where('user_id', auth()->id())
            ->findOrFail($id);
            
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        PropertyNotification::where('user_id', auth()->id())
            ->unread()
            ->update(['read_at' => now()]);
            
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Show the notification preferences form.
     */
    public function showPreferences()
    {
        $preferences = NotificationPreference::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'email_notifications' => true,
                'push_notifications' => true,
                'new_property_alerts' => true,
                'price_change_alerts' => true,
                'status_change_alerts' => true,
                'saved_search_alerts' => true,
                'notification_frequency' => ['type' => 'instant'],
            ]
        );
        
        return view('notifications.preferences', compact('preferences'));
    }

    /**
     * Update the notification preferences.
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'new_property_alerts' => 'boolean',
            'price_change_alerts' => 'boolean',
            'status_change_alerts' => 'boolean',
            'saved_search_alerts' => 'boolean',
            'notification_frequency' => 'required|in:instant,daily,weekly',
        ]);
        
        $preferences = NotificationPreference::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'email_notifications' => $request->boolean('email_notifications'),
                'push_notifications' => $request->boolean('push_notifications'),
                'new_property_alerts' => $request->boolean('new_property_alerts'),
                'price_change_alerts' => $request->boolean('price_change_alerts'),
                'status_change_alerts' => $request->boolean('status_change_alerts'),
                'saved_search_alerts' => $request->boolean('saved_search_alerts'),
                'notification_frequency' => ['type' => $validated['notification_frequency']],
            ]
        );
        
        return redirect()->back()->with('success', 'Préférences de notification mises à jour avec succès.');
    }
}
