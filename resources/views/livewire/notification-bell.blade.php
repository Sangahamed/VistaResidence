<div class="relative" x-data="{ open: false }">
    <!-- Bouton Cloche -->
    <button @click="open = !open" class="p-1 text-gray-400 hover:text-indigo-600 relative">
        <x-heroicon-o-bell class="h-6 w-6" />
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-50 border border-gray-200">
        <div class="px-4 py-2 border-b bg-gray-50">
            <h3 class="text-sm font-medium">Notifications ({{ $unreadCount }})</h3>
        </div>

        <div class="divide-y max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}" 
                   wire:click="markAsRead('{{ $notification->id }}')"
                   class="block px-4 py-3 hover:bg-gray-50 transition {{ $notification->unread() ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <x-notification-icon :type="$notification->type" 
                                class="h-5 w-5 text-indigo-500" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ $notification->unread() ? 'text-gray-900' : 'text-gray-500' }}">
                                {{ $notification->data['title'] }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $notification->data['message'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-3 text-center text-sm text-gray-500">
                    Aucune notification
                </div>
            @endforelse
        </div>

        <div class="px-4 py-2 border-t bg-gray-50 text-center">
            <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                Voir toutes les notifications
            </a>
        </div>
    </div>
</div>