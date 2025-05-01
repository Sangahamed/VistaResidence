<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PropertyNotification;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    /**
     * Afficher toutes les notifications de l'utilisateur.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->paginate(10);
        
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Marquer une notification comme lue.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Marquer toutes les notifications comme lues.
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Supprimer une notification.
     */
    public function destroy(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Afficher les préférences de notification de l'utilisateur.
     */
    public function preferences(Request $request)
    {
        $user = $request->user();
        $preferences = $user->notificationPreferences ?? new NotificationPreference();
        
        return view('notifications.preferences', compact('preferences'));
    }
    
    /**
     * Mettre à jour les préférences de notification de l'utilisateur.
     */
    public function updatePreferences(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'email_new_property' => 'boolean',
            'email_property_status' => 'boolean',
            'email_new_lead' => 'boolean',
            'email_lead_assigned' => 'boolean',
            'email_visit_requested' => 'boolean',
            'email_visit_status' => 'boolean',
            'push_new_property' => 'boolean',
            'push_property_status' => 'boolean',
            'push_new_lead' => 'boolean',
            'push_lead_assigned' => 'boolean',
            'push_visit_requested' => 'boolean',
            'push_visit_status' => 'boolean',
            'sms_new_property' => 'boolean',
            'sms_property_status' => 'boolean',
            'sms_new_lead' => 'boolean',
            'sms_lead_assigned' => 'boolean',
            'sms_visit_requested' => 'boolean',
            'sms_visit_status' => 'boolean',
        ]);
        
        $preferences = $user->notificationPreferences ?? new NotificationPreference(['user_id' => $user->id]);
        $preferences->fill($validated);
        $preferences->save();
        
        return redirect()->route('notifications.preferences')->with('success', 'Préférences de notification mises à jour avec succès.');
    }
}
