@extends('components.back.layout.back')
@section('content')

<div class="min-h-full bg-gray-50">
    <div class="py-10">
        <header>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold leading-tight text-gray-900 text-center mb-8">
                    Propriétés
                </h1>
            </div>
        </header>
        <main>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="px-4 py-8 sm:px-0">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Profile Card -->
                        <div class="w-full md:w-1/3">
                            <div
                                class="bg-white overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex flex-col items-center">
                                        <div class="relative mb-4">
                                            <img class="h-32 w-32 rounded-full border-4 border-gray-300"
                                                src="https://via.placeholder.com/128" alt="Photo de profil">
                                            <button onclick="document.getElementById('userProfilePictureFile').click()"
                                                class="absolute bottom-0 right-0 bg-slate-300 rounded-full p-2 hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-camera-alt"></i>
                                            </button>
                                            <input type="file" id="userProfilePictureFile" class="hidden">
                                        </div>
                                        <h2 class="text-2xl font-semibold">Nom d'utilisateur</h2>
                                        <p class="text-gray-500">utilisateur@example.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Details -->
                        <div class="w-full md:w-2/3">
                            <div class="bg-white shadow-md overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Profil</h3>
                                    <nav>
                                        <ul class="tabs flex space-x-4 border-b border-gray-200">
                                            <li class="tab active text-primary border-b-2 border-primary py-2 px-4 cursor-pointer"
                                                data-target="personal-details">
                                                Détails Personnels
                                            </li>
                                            <li class="tab text-gray-500 py-2 px-4 cursor-pointer hover:text-primary"
                                                data-target="password-update">
                                                Modifier Mot de passe
                                            </li>
                                            <li class="tab text-gray-500 py-2 px-4 cursor-pointer hover:text-primary"
                                                data-target="preferences">
                                                Préférences
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="border-t border-gray-200">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personal-details">
                                            <form class="p-6 space-y-6">
                                                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                                    <div>
                                                        <label for="name"
                                                            class="block text-sm font-medium text-gray-700">Nom
                                                            Complet</label>
                                                        <input type="text" id="name"
                                                            class="mt-1 block w-full border-gray-300 shadow-sm rounded-md focus:ring-primary focus:border-primary">
                                                    </div>
                                                    <div>
                                                        <label for="email"
                                                            class="block text-sm font-medium text-gray-700">Email</label>
                                                        <input type="email" id="email"
                                                            class="mt-1 block w-full border-gray-300 shadow-sm rounded-md bg-gray-100"
                                                            disabled>
                                                    </div>
                                                    <div>
                                                        <label for="username"
                                                            class="block text-sm font-medium text-gray-700">Nom
                                                            d'utilisateur</label>
                                                        <input type="text" id="username"
                                                            class="mt-1 block w-full border-gray-300 shadow-sm rounded-md focus:ring-primary focus:border-primary">
                                                    </div>
                                                    <div>
                                                        <label for="phone"
                                                            class="block text-sm font-medium text-gray-700">Numéro de
                                                            téléphone</label>
                                                        <input type="tel" id="phone"
                                                            class="mt-1 block w-full border-gray-300 shadow-sm rounded-md focus:ring-primary focus:border-primary">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="address"
                                                        class="block text-sm font-medium text-gray-700">Adresse</label>
                                                    <input type="text" id="address"
                                                        class="mt-1 block w-full border-gray-300 shadow-sm rounded-md focus:ring-primary focus:border-primary">
                                                </div>
                                                <div>
                                                    <button type="submit"
                                                        class="inline-flex justify-center py-2 px-4 text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane hidden" id="password-update">
                                            <form class="p-6 space-y-6">
                                                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
                                                    <div>
                                                        <label for="current_password"
                                                            class="block text-sm font-medium text-gray-700">Mot de passe
                                                            actuel</label>
                                                        <input type="password" id="current_password"
                                                            class="mt-1 block w-full border-gray-300 shadow-sm rounded-md focus:ring-primary focus:border-primary">
                                                    </div>
                                                    <div>
                                                        <label for="new_password"
                                                            class="block text-sm font-medium text-gray-700">Nouveau mot
                                                            de passe</label>
                                                        <input type="password" id="new_password"
                                                            class="mt-1 block w-full border-gray-300 shadow-sm rounded-md focus:ring-primary focus:border-primary">
                                                    </div>
                                                    <div>
                                                        <label for="confirm_password"
                                                            class="block text-sm font-medium text-gray-700">Confirmer le
                                                            nouveau mot de passe</label>
                                                        <input type="password" id="confirm_password"
                                                            class="mt-1 block w-full border-gray-300 shadow-sm rounded-md focus:ring-primary focus:border-primary">
                                                    </div>
                                                </div>
                                                <div>
                                                    <button type="submit"
                                                        class="inline-flex justify-center py-2 px-4 text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                                        Modifier
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane hidden" id="preferences">
                                            <div class="p-6 text-gray-700">
                                                <p>Contenu des préférences ici.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    // Tab system
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.tab');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active state from all tabs
                tabs.forEach(t => t.classList.remove('active', 'text-primary',
                    'border-primary'));
                // Hide all tab panes
                tabPanes.forEach(pane => pane.classList.add('hidden'));

                // Activate clicked tab
                tab.classList.add('active', 'text-primary', 'border-primary');
                // Show corresponding pane
                const target = tab.dataset.target;
                document.getElementById(target).classList.remove('hidden');
            });
        });
    });

    document.getElementById('userProfilePictureFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.rounded-full').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
</script>



@endsection
