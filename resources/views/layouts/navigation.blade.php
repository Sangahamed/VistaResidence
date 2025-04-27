<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                {{-- <div class="shrink-0 flex items-center">
                    <a href="">
                        <div
                            class="block h-10 w-auto fill-current text-gray-600"
                        />
                    </a>
                </div> --}}

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a
                        href=""
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                        Accueil
                    </a>

                    @auth
                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out"
                    >
                        Tableau de bord
                    </a>

                    <a
                        href="{{ route('properties.index') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                        Mes propriétés
                    </a>

                    @if(Auth::user()->isIndividual() ||
                    Auth::user()->isCompany())
                    <a
                        href="{{ route('properties.create') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                        Ajouter une propriété
                    </a>
                    @endif @if(Auth::user()->isCompany())
                    <a
                        href="{{ route('company.edit') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                        Mon entreprise
                    </a>
                    @endif @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button
                            @click="open = !open"
                            class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                        >
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg
                                    class="fill-current h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <div
                        x-show="open"
                        @click.away="open = false"
                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="user-menu"
                    >
                        <a
                            href="{{ route('dashboard') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            role="menuitem"
                            >Tableau de bord</a
                        >

                        @if(Auth::user()->isClient())
                        <form
                            method="POST"
                            action="{{ route('switch.to.individual') }}"
                        >
                            @csrf
                            <button
                                type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem"
                            >
                                Devenir particulier
                            </button>
                        </form>
                        @endif @if(!Auth::user()->isCompany())
                        <a
                            href="{{ route('company.create') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            role="menuitem"
                            >Créer une entreprise</a
                        >
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem"
                            >
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a
                    href="{{ route('login') }}"
                    class="text-sm text-gray-700 underline"
                    >Se connecter</a
                >
                <a
                    href="{{ route('register') }}"
                    class="ml-4 text-sm text-gray-700 underline"
                    >S'inscrire</a
                >
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button
                    @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                >
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            :class="{'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a
                href=""
                class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
            >
                Accueil
            </a>

            @auth
            <a
                href="{{ route('dashboard') }}"
                class="block pl-3 pr-4 py-2 border-l-4 border-indigo-500 text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out"
            >
                Tableau de bord
            </a>

            <a
                href="{{ route('properties.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
            >
                Mes propriétés
            </a>

            @if(Auth::user()->isIndividual() || Auth::user()->isCompany())
            <a
                href="{{ route('properties.create') }}"
                class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
            >
                Ajouter une propriété
            </a>
            @endif @if(Auth::user()->isCompany())
            <a
                href="{{ route('company.edit') }}"
                class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
            >
                Mon entreprise
            </a>
            @endif @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <a
                    href="{{ route('dashboard') }}"
                    class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                >
                    Tableau de bord
                </a>

                @if(Auth::user()->isClient())
                <form
                    method="POST"
                    action="{{ route('switch.to.individual') }}"
                >
                    @csrf
                    <button
                        type="submit"
                        class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                        Devenir particulier
                    </button>
                </form>
                @endif @if(!Auth::user()->isCompany())
                <a
                    href="{{ route('company.create') }}"
                    class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                >
                    Créer une entreprise
                </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                        Se déconnecter
                    </button>
                </form>
            </div>
            @else
            <div class="space-y-1">
                <a
                    href="{{ route('login') }}"
                    class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                >
                    Se connecter
                </a>
                <a
                    href="{{ route('register') }}"
                    class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                >
                    S'inscrire
                </a>
            </div>
            @endauth
        </div>
    </div>
</nav>
