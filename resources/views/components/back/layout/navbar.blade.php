<aside class="w-80 bg-white shadow-md lg:block hidden">
    <div class="p-4">
        <img src="/placeholder.svg?height=50&width=150" alt="Logo" class="mb-4">
        <div class="flex items-center mb-4">
            <img src="/placeholder.svg?height=40&width=40" alt="User Avatar" class="w-10 h-10 rounded-full mr-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-500 whitespace-nowrap">Joined on Nov 14, 2024</p>
                </div>
                <button class="hidden lg:flex items-center text-gray-500 hover:text-orange-600 ml-16"
                    onclick="event.preventDefault();document.getElementById('adminLogoutForm').submit();">
                    <i class="ri-logout-box-r-line text-2xl"></i>
                    <form action="{{ route('admin.logout_handler') }}" id="adminLogoutForm" method="POST">@csrf</form>
                </button>
            </div>

        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Credits</p>
            <p class="text-4xl font-bold">10</p>

            <a href="#" class="text-blue-500 text-sm">Buy credits</a>
        </div>
    </div>
    <nav>
        <a href="#"
            class="nav-link flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-500 text-white rounded-md mb-1"
            id="dashboardLink">
            <i class="ri-home-line mr-3"></i>
            Dashboard
        </a>
        <a href="#" class="nav-link flex items-center px-4 py-2 text-gray-700 hover:bg-blue-500 rounded-md mb-1"
            id="buyCreditsLink">
            <i class="ri-bank-card-line mr-3"></i>
            Buy credits
        </a>
        <a href="#" class="nav-link flex items-center px-4 py-2 text-gray-700 hover:bg-blue-500 rounded-md mb-1"
            id="invoicesLink">
            <i class="ri-bill-line mr-3"></i>
            Invoices
        </a>
        <a href="#" class="nav-link flex items-center px-4 py-2 text-gray-700 hover:bg-blue-500 rounded-md"
            id="settingsLink">
            <i class="ri-settings-line mr-3"></i>
            Settings
        </a>


        <div class="mt-4">
            <form method="POST" action="{{ route('switch.to.individual') }}">
                @csrf
                <button type="submit" class="btn-primary">
                    Devenir particulier pour publier des annonces
                </button>
            </form>
        </div>
    </nav>
</aside>
