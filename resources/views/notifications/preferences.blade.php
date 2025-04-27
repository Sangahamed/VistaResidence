@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Préférences de notification</h1>
            <p class="text-muted-foreground">
                Personnalisez vos préférences de notification pour rester informé selon vos besoins.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden p-6">
            <form action="{{ route('notifications.preferences.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Canaux de notification</h2>
                        <p class="text-sm text-gray-500">Choisissez comment vous souhaitez recevoir vos notifications.</p>
                        
                        <div class="mt-4 space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="email_notifications" name="email_notifications" type="checkbox" value="1" 
                                        {{ $preferences->email_notifications ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="email_notifications" class="font-medium text-gray-700">Notifications par email</label>
                                    <p class="text-gray-500">Recevez des emails pour les alertes importantes.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="push_notifications" name="push_notifications" type="checkbox" value="1" 
                                        {{ $preferences->push_notifications ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="push_notifications" class="font-medium text-gray-700">Notifications push</label>
                                    <p class="text-gray-500">Recevez des notifications sur votre navigateur.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Types d'alertes</h2>
                        <p class="text-sm text-gray-500">Sélectionnez les types d'alertes que vous souhaitez recevoir.</p>
                        
                        <div class="mt-4 space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="new_property_alerts" name="new_property_alerts" type="checkbox" value="1" 
                                        {{ $preferences->new_property_alerts ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="new_property_alerts" class="font-medium text-gray-700">Nouvelles propriétés</label>
                                    <p class="text-gray-500">Soyez alerté lorsque de nouvelles propriétés correspondent à vos critères de recherche.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="price_change_alerts" name="price_change_alerts" type="checkbox" value="1" 
                                        {{ $preferences->price_change_alerts ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="price_change_alerts" class="font-medium text-gray-700">Changements de prix</label>
                                    <p class="text-gray-500">Soyez alerté lorsque le prix d'une propriété que vous suivez change.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="status_change_alerts" name="status_change_alerts" type="checkbox" value="1" 
                                        {{ $preferences->status_change_alerts ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="status_change_alerts" class="font-medium text-gray-700">Changements de statut</label>
                                    <p class="text-gray-500">Soyez alerté lorsque le statut d'une propriété que vous suivez change (vendu, loué, etc.).</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="saved_search_alerts" name="saved_search_alerts" type="checkbox" value="1" 
                                        {{ $preferences->saved_search_alerts ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="saved_search_alerts" class="font-medium text-gray-700">Recherches sauvegardées</label>
                                    <p class="text-gray-500">Recevez des alertes basées sur vos recherches sauvegardées.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Fréquence des notifications</h2>
                        <p class="text-sm text-gray-500">Choisissez à quelle fréquence vous souhaitez recevoir vos notifications.</p>
                        
                        <div class="mt-4">
                            <select id="notification_frequency" name="notification_frequency" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                                <option value="instant" {{ isset($preferences->notification_frequency['type']) && $preferences->notification_frequency['type'] == 'instant' ? 'selected' : '' }}>
                                    Instantanée
                                </option>
                                <option value="daily" {{ isset($preferences->notification_frequency['type']) && $preferences->notification_frequency['type'] == 'daily' ? 'selected' : '' }}>
                                    Résumé quotidien
                                </option>
                                <option value="weekly" {{ isset($preferences->notification_frequency['type']) && $preferences->notification_frequency['type'] == 'weekly' ? 'selected' : '' }}>
                                    Résumé hebdomadaire
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 mt-6">
                    <div class="flex justify-end">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Enregistrer les préférences
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection