@extends('components.back.layout.back')

@section('content')
<h1 class="text-3xl font-bold mb-6">Propriétés</h1>

<nav>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="p-4 border-b">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <!-- Bulk Actions and Filters -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative">
                        <button
                            class="bg-gray-100 border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-gray-200">
                            <span>Actions en masse</span>
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <ul class="absolute hidden bg-white border border-gray-200 shadow-md rounded-md mt-1 w-full">
                            <li>
                                <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md" href="#">
                                    Modifications en masse
                                </a>
                            </li>
                        </ul>
                    </div>
                    <button
                        class="bg-gray-100 border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg hover:bg-gray-200">
                        Filtres
                    </button>
                    <div class="relative">
                        <i class="ri-search-line absolute left-3 top-3 text-gray-400"></i>
                        <input
                            class="bg-gray-100 border border-gray-300 text-gray-700 font-medium py-2 pl-10 pr-4 rounded-lg focus:ring-2 focus:ring-blue-500"
                            type="search" placeholder="Rechercher...">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 mt-4 md:mt-0">
                    <button
                        class="bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <i class="ri-add-line"></i> Créer
                    </button>
                    <button
                        class="bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg hover:bg-gray-400 flex items-center gap-2">
                        <i class="ri-refresh-line"></i> Recharger
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4"><input type="checkbox" class="form-checkbox"></th>
                        <th class="p-4">ID</th>
                        <th class="p-4">Image</th>
                        <th class="p-4">Nom</th>
                        <th class="p-4">Vues</th>
                        <th class="p-4">ID Unique</th>
                        <th class="p-4">Date d'expiration</th>
                        <th class="p-4">Date de création</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4">Modération</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4"><input type="checkbox" class="form-checkbox"></td>
                        <td class="p-4">22</td>
                        <td class="p-4">
                            <img src="/placeholder.svg?height=50&width=50" alt="Property Image"
                                class="w-12 h-12 object-cover rounded">
                        </td>
                        <td class="p-4">
                            <a href="#" class="text-blue-600 hover:underline">pablo machado</a>
                        </td>
                        <td class="p-4">2</td>
                        <td class="p-4">1</td>
                        <td class="p-4">2024-12-29</td>
                        <td class="p-4">2024-11-14</td>
                        <td class="p-4">
                            <span
                                class="whitespace-nowrap px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                En vente
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                Approuvé
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Renouveler">
                                    <i class="ri-refresh-line"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Modifier">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Supprimer">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer Section -->
        <div class="p-4 border-t">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-600">
                    Affichage de 1 à 1 sur 1 entrées
                </div>
                <nav class="inline-flex items-center gap-1">
                    <a href="#" class="px-3 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                        <i class="ri-arrow-left-s-line"></i>
                    </a>
                    <a href="#" class="px-3 py-2 text-blue-600 bg-blue-100 border border-blue-300 rounded-lg">
                        1
                    </a>
                    <a href="#" class="px-3 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                        <i class="ri-arrow-right-s-line"></i>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</nav>


@endsection
