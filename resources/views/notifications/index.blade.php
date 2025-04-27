@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Notifications</h1>
                <p class="text-muted-foreground">
                    Restez informé des dernières mises à jour concernant vos propriétés et recherches.
                </p>
            </div>
            <div>
                @if($unreadCount > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-primary hover:underline">
                        Marquer tout comme lu
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($notifications->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <li class="p-4 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    @if($notification->type == 'new_property')
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-home text-green-600"></i>
                                        </div>
                                    @elseif($notification->type == 'price_change')
                                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <i class="fas fa-tag text-yellow-600"></i>
                                        </div>
                                    @elseif($notification->type == 'status_change')
                                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-exchange-alt text-purple-600"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-bell text-blue-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <p class="text-sm font-medium {{ $notification->read_at ? 'text-gray-900' : 'text-blue-900' }}">
                                            {{ $notification->message }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if($notification->property)
                                        <a href="{{ route('properties.show', $notification->property) }}" class="text-sm text-primary hover:underline">
                                            Voir la propriété
                                        </a>
                                    @endif
                                </div>
                                @if(!$notification->read_at)
                                    <div>
                                        <form action="{{ route('notifications.mark-read', $notification) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-gray-500 hover:text-gray-700">
                                                Marquer comme lu
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Aucune notification</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Vous n'avez pas encore reçu de notifications. Elles apparaîtront ici lorsque vous en recevrez.
                    </p>
                </div>
            @endif
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection