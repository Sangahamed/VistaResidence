@extends('components.back.layout.back')

@section('content')

<div class="container-fluid relative px-3 mx-auto">
    <div class="layout-specing">
        <!-- Start Content -->
        <div class="md:flex justify-between items-center">
            <h5 class="text-lg font-semibold">Ajouter propriete</h5>

            <ul class="tracking-[0.5px] inline-block sm:mt-0 mt-3">
                <li
                    class="inline-block capitalize text-[16px] font-medium duration-500 dark:text-white/70 hover:text-green-600 dark:hover:text-white">
                    <a href="index.html">Viscaresidence</a>/</li>
                <li class="inline-block text-base text-slate-950 dark:text-white/70 mx-0.5 ltr:rotate-0 rtl:rotate-180">
                    <i class="mdi mdi-chevron-right"></i></li>
                <li class="inline-block capitalize text-[16px] font-medium text-orange-600 dark:text-white"
                    aria-current="page">Ajouter propriete</li>
            </ul>
        </div>

        <div class="container relative">
            <div class="grid md:grid-cols-2 grid-cols-1 gap-6 mt-6">
                <div class="rounded-md shadow dark:shadow-gray-700 p-6 bg-slate-200 dark:bg-slate-900 max-h-fit">
                    <div class="space-y-4">
                        <!-- name -->
                        <div>
                            <label for="name" class="block text-lg font-medium text-gray-700">Nom <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" placeholder="Nom de la propriété" required
                                class="mt-2 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                        </div>
                        <!-- type  -->
                        <div>
                            <label for="type" class="block text-lg font-medium text-gray-700">Type <span
                                    class="text-red-500">*</span></label>
                            <select id="type" name="type"
                                class="w-full p-2 mt-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Sélectionnez un usage</option>
                                <option value="vente">Vente</option>
                                <option value="location">Location</option>
                            </select>
                        </div>

                        <div class="card meta-boxes">
                            <!-- Catégorie -->
                            <div>
                                <label for="category" class="block text-lg font-medium text-gray-700">Catégorie
                                    <span class="text-red-500">*</span></label>
                                <select id="category" name="category" required
                                    class="w-full p-2 mt-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option value="hotel">Hôtel</option>
                                    <option value="residence">Résidence</option>
                                    <option value="apartment">Appartement</option>
                                    <option value="villa">Villa</option>
                                    <option value="office">Bureau</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>

                            <label for="description" class="block text-lg font-medium text-gray-700">Description
                                <span class="text-red-500">*</span></label>
                            <textarea type="text" id="description" name="name"
                                placeholder="veuillez entrez une description" required
                                class="w-full p-2 mt-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <!-- images preview -->


                        <div class="rounded-md shadow-lg p-6 bg-white dark:bg-gray-900 h-fit">
                            <div>
                                <label for="image"
                                    class="block text-lg font-medium text-gray-700 dark:text-gray-300">Images
                                    (maximum 20 images)</label>
                                <div class="border-2 border-dashed rounded-lg p-6 text-center relative">
                                    <span class="text-gray-500 dark:text-gray-400">Déposez des fichiers ici
                                        ou cliquez pour les télécharger.</span>
                                    <input type="file" id="image" name="image" multiple accept="image/*,video/*"
                                        onchange="handleChange()"
                                        class="absolute opacity-0 top-0 left-0 w-full h-full cursor-pointer">
                                </div>
                                <div
                                    class="preview-box flex justify-center rounded-md shadow overflow-hidden bg-gray-50 dark:bg-gray-800 text-gray-400 p-2 text-center small w-auto max-h-60">
                                    Supports JPG, PNG and MP4 videos. Max file size : 20MB.
                                </div>
                                <div id="preview-container" class="mt-4"></div>
                            </div>
                        </div>

                        <!-- situation geographique -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="country_id" class="block text-lg font-medium text-gray-700">Pays
                                    <span class="text-red-500">*</span></label>
                                <select
                                    class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    id="country_id" name="country_id">
                                    <option value="">Sélectionnez le pays...</option>
                                    <option value="1">France</option>
                                    <option value="2">Angleterre</option>
                                    <option value="3">ÉTATS-UNIS</option>
                                </select>
                            </div>
                            <div>
                                <label for="state_id" class="block text-lg font-medium text-gray-700">État
                                    <span class="text-red-500">*</span></label>
                                <select
                                    class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    id="state_id" name="state_id">
                                    <option value="">Sélectionnez l’état...</option>
                                    <option value="1">France</option>
                                    <option value="3">New York</option>
                                    <option value="6">Allemagne</option>
                                </select>
                            </div>
                            <div>
                                <label for="city_id" class="block text-lg font-medium text-gray-700">Ville
                                    <span class="text-red-500">*</span></label>
                                <select
                                    class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    id="city_id" name="city_id">
                                    <option value="">Sélectionnez la ville...</option>
                                </select>
                            </div>
                        </div>


                        <div>
                            <label for="address" class="block text-lg font-medium text-gray-700">Adresse
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="address" name="address"
                                class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                required placeholder="Ex : Yopougon, Banco2">

                            <button id="get-address"
                                class="bg-orange-600 hover:bg-orange-700 text-white rounded-md mt-2 px-4 py-2 text-sm lg:text-base flex items-center">
                                <span class="icon-tabler-wrapper icon-left mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="2"></circle>
                                    </svg>
                                </span>
                                Récupérer l'adresse
                            </button>
                        </div>

                        <div class="h-40 w-auto mx-auto" id="map" style=""></div>

                    </div>
                </div>

                <div class="rounded-md shadow dark:shadow-gray-700 p-6 bg-slate-200 dark:bg-slate-900 max-h-fit w-fit">
                    <div class="space-y-4">


                        <!-- detail propriete -->

                        <h5 class="text-lg font-semibold">Détails de la propriété <span class="text-red-500">*</span>
                        </h5>

                        <!-- Modification de la structure de la grille pour améliorer la lisibilité -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Nombre de pièces -->
                            <div>
                                <label for="number_pieces" class="block text-sm font-medium text-gray-700">Pièces <span
                                        class="text-red-500">*</span></label>
                                <input
                                    class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nombre de pièces" name="number_pieces" type="number" min="0"
                                    id="number_pieces">
                            </div>

                            <!-- Nombre de chambres -->
                            <div>
                                <label for="number_bedroom" class="block text-sm font-medium text-gray-700 ">Chambres
                                    <span class="text-red-500">*</span></label>
                                <input
                                    class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nombre de chambres" name="number_bedroom" type="number" min="0"
                                    id="number_bedroom">
                            </div>
                            <!-- Nombre de salles de bains -->
                            <div>
                                <label for="number_bathroom"
                                    class="block text-sm font-medium text-gray-700 whitespace-nowrap">Salles
                                    de bains<span class="text-red-500">*</span></label>
                                <input
                                    class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nombre de salles de bains" name="number_bathroom" type="number" min="0"
                                    id="number_bathroom">
                            </div>
                            <!-- Nombre d'étages -->
                            <div>
                                <label for="number_floor" class="block text-sm font-medium text-gray-700">Étages <span
                                        class="text-red-500">*</span></label>
                                <input
                                    class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nombre d’étages" name="number_floor" type="number" min="0"
                                    id="number_floor">
                            </div>
                            <!-- Superficie en m² -->
                            <div>
                                <label for="square" class="block text-sm font-medium text-gray-700" id="square-label"
                                    style="display: none;">Superficie (m²) <span class="text-red-500">*</span></label>
                                <input
                                    class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Superficie (m²)" name="square" type="number" min="0" id="square"
                                    style="display: none;">
                            </div>

                        </div>


                        <!-- prix -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price" class="block text-lg font-medium text-gray-700">Prix
                                    <span class="text-red-500">*</span></label>
                                <input
                                    class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    id="price" placeholder="Prix" name="price" type="text">
                            </div>
                            <div>
                                <label for="currency_id" class="block text-lg font-medium text-gray-700">Periode <span
                                        class="text-red-500">*</span></label>
                                <select
                                    class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    id="currency_id" name="currency_id">
                                    <option value="1">Nuitee</option>
                                    <option value="2">Jours</option>
                                    <option value="3">Mois</option>
                                </select>
                            </div>
                        </div>

                        <!-- fonctionnalite -->
                        <div class="card mb-3 mt-5">
                            <div class="card-header">
                                <label class="block text-lg font-medium text-gray-700">Fonctionnalités <span
                                        class="text-red-500">*</span></label>
                            </div>
                            <div class="card-body mt-2">
                                <div class="grid grid-cols-4 gap-4">
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="1">
                                        <span class="form-check-label">Wi-Fi</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="2">
                                        <span class="form-check-label">Parking gratuit</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="3">
                                        <span class="form-check-label">Piscine</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="4">
                                        <span class="form-check-label">Climatisation</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="5">
                                        <span class="form-check-label">Chauffage</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="6">
                                        <span class="form-check-label">Ascenseur</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="7">
                                        <span class="form-check-label">Salle de sport</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="8">
                                        <span class="form-check-label">Salle de jeux</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="9">
                                        <span class="form-check-label">Piste de danse</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="10">
                                        <span class="form-check-label">Salle de réunion</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="11">
                                        <span class="form-check-label">Salle de conférence</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="12">
                                        <span class="form-check-label">Restaurant</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="13">
                                        <span class="form-check-label">Bar</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="14">
                                        <span class="form-check-label">Salle de télévision</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="15">
                                        <span class="form-check-label">Bibliothèque</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="16">
                                        <span class="form-check-label">Jardin d'enfants</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="17">
                                        <span class="form-check-label">Salle de jeux pour enfants</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="18">
                                        <span class="form-check-label">Service de concierge</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="19">
                                        <span class="form-check-label">Service de blanchisserie</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="checkbox" name="features[]" class="form-check-input" value="20">
                                        <span class="form-check-label">Service de nettoyage</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Usage -->
                        <div class="card meta-boxes">
                            <div class="card-body">
                                <label for="usage" class="block text-lg font-medium text-gray-700">Usage
                                    <span class="text-red-500">*</span></label>
                                <select id="usage" name="usage"
                                    class="w-full p-2 mt-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Sélectionnez un usage</option>
                                    <option value="personal">Personnel</option>
                                    <option value="professional">Professionnel</option>
                                    <option value="commercial">Commercial</option>
                                </select>
                            </div>
                        </div>

                        <div class="card meta-boxes mt-3">
                            <div class="card-header">
                                <label for="status" class="block text-lg font-medium text-gray-700"
                                    aria-required="true">Statut <span class="text-red-500">*</span></label>

                            </div>

                            <select
                                class="w-full p-2 mt-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                required="" data-placeholder="Select an option" id="status" name="status"
                                aria-required="true" aria-invalid="false" aria-describedby="status-error">
                                <option value="not_available">Non disponible
                                </option>
                                <option value="pre_sale">Préparation de la vente
                                </option>
                                <option value="selling">Vente
                                </option>
                                <option value="sold">Vendu</option>
                                <option value="renting">Location
                                </option>
                                <option value="rented">Loué</option>
                                <option value="building">hotel
                                </option>
                            </select>

                        </div>

                        <div class="grid grid-cols-2 gap-3 card meta-boxes mt-5">
                            <button
                                class="btn bg-orange-600 hover:bg-orange-700 text-white rounded-md px-6 py-2 text-sm lg:text-base flex items-center"
                                type="submit" name="submitter" value="apply">
                                <span class="icon-tabler-wrapper icon-left mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2">
                                        </path>
                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M14 4l0 4l-6 0l0 -4"></path>
                                    </svg>
                                </span>
                                <span>Sauvegarder</span>
                            </button>
                            <button
                                class="btn bg-orange-600 hover:bg-orange-700 text-white rounded-md px-6 py-2 text-sm lg:text-base flex items-center"
                                type="submit" name="submitter" value="save">
                                <span class="icon-tabler-wrapper icon-left mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-transfer-in" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 18v3h16v-14l-8 -4l-8 4v3"></path>
                                        <path d="M4 14h9"></path>
                                        <path d="M10 11l3 3l-3 3"></path>
                                    </svg>
                                </span>
                                <span>Enregistrer et quitter</span>
                            </button>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection
