@extends('components.back.layout.back')

@section('content')
    <div class="container mx-auto py-10 px-4">

        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-2 text-sm text-blue-500">
                <a href="{{ route('dashboard') }}" class="hover:underline">DASHBOARD</a>
                <span>/</span>
                <span class="font-medium">Notification</span>
            </div>

            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                <a href="{{ route('notifications.preferences') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all transform hover:scale-[1.02]">
                    <i class="fas fa-bell mr-2"></i>
                    Préférences de notification
                </a>

            </div>
        </div>
        <div class="max-w-4xl mx-auto space-y-8">

            <!-- En-tête -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800">Notifications</h1>
                    <p class="text-gray-600 mt-1">
                        Restez informé des dernières mises à jour concernant vos propriétés et recherches.
                    </p>
                </div>

                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm text-indigo-600 hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                            Marquer tout comme lu
                        </button>
                    </form>
                @endif
            </div>

            <!-- Liste des notifications -->
            <div class="bg-white shadow rounded-lg divide-y divide-gray-100 overflow-hidden">
                @forelse ($notifications as $notification)
                    <a href="{{ $notification->data['url'] ?? '#' }}"
                        class="flex px-6 py-4 gap-4 hover:bg-gray-50 transition-all duration-200 ease-in-out {{ $notification->unread() ? 'bg-blue-50' : '' }}">
                        <!-- Icône -->
                        <div class="mt-1 flex-shrink-0">
                            @include('notifications.partials.icon', ['type' => $notification->type])
                        </div>

                        <!-- Contenu -->
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <p
                                    class="text-sm font-semibold {{ $notification->unread() ? 'text-gray-900' : 'text-gray-500' }}">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>
                                <span class="text-xs text-gray-400">
                                    {{ $notification->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $notification->data['message']  ?? 'message' }}
                            </p>
                            @if (!empty($notification->data['action']))
                                <div class="mt-2">
                                    <span
                                        class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded">
                                        {{ $notification->data['action'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        Vous n'avez aucune notification pour le moment.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>

        </div>
    </div>
@endsection
