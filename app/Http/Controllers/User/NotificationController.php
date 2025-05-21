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
    $notifications = auth()->user()
        ->notifications()
        ->paginate(20);

    return view('notifications.index', [
        'notifications' => $notifications,
        'unreadCount' => auth()->user()->unreadNotifications()->count()
    ]);
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

        public function getUnreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Show the notification preferences form.
     */
    public function showPreferences()
   {
    $categories = array_keys(config('notification.types'));
    $preferences = auth()->user()->notificationPreference()->firstOrCreate(
        ['user_id' => auth()->id()],
        ['preferences' => config('notification.default_preferences.alerts')]
    );

    return view('notifications.preferences', [
        'preferences' => $preferences,
        'categories' => $categories
    ]);
}

    /**
     * Update the notification preferences.
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email_enabled' => 'boolean',
            'push_enabled' => 'boolean',
            'sms_enabled' => 'boolean',
            'frequency' => 'in:instant,daily,weekly',
            'preferences' => 'array'
        ]);

         auth()->user()->notificationPreference()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return back()->with('success', 'Préférences mises à jour');
    }

    public function loadMore(Request $request)
{
    $notifications = auth()->user()
        ->notifications()
        ->paginate(10, ['*'], 'page', $request->page);
        
    return view('partials.notifications-list', ['notifications' => $notifications]);
}
}
