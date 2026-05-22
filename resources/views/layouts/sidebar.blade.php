<div x-show="sidebarOpen"
     x-transition:enter="transition ease-in-out duration-300 transform"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in-out duration-300 transform"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 h-screen overflow-y-auto transition-all duration-300 lg:static lg:inset-auto">
    <div class="flex items-center px-6 h-20 border-b border-gray-100 dark:border-gray-700/50">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="relative">
                <img src="http://localhost/akaza_topup/asset/img/akazachibi.png" class="w-10 h-10 rounded-xl shadow-lg transition-transform group-hover:scale-110" alt="Logo">
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
            </div>
            <div class="flex flex-col">
                <span class="font-black text-lg text-gray-800 dark:text-white tracking-tighter leading-none">AKAZASTORE</span>
                <span class="text-[10px] text-blue-500 font-bold uppercase tracking-widest mt-1">Admin Panel</span>
            </div>
        </a>
    </div>

    <div class="flex flex-col flex-grow p-4 space-y-2">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Main Menu</div>
        
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
            Dashboard
        </x-sidebar-link>

        <x-sidebar-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" icon="receipt">
            Riwayat Transaksi
        </x-sidebar-link>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-2">Management</div>

        <x-sidebar-link :href="route('games.index')" :active="request()->routeIs('games.*')" icon="gamepad">
            Kelola Game
        </x-sidebar-link>

        <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')" icon="diamond">
            Produk Diamond
        </x-sidebar-link>

        <x-sidebar-link :href="route('payment-methods.index')" :active="request()->routeIs('payment-methods.*')" icon="payment">
            Metode Pembayaran
        </x-sidebar-link>

        <x-sidebar-link :href="route('banners.index')" :active="request()->routeIs('banners.*')" icon="image">
            Kelola Banner
        </x-sidebar-link>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-2">Admin</div>

        <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" icon="user">
            Profil Admin
        </x-sidebar-link>
    </div>

    <div class="p-4 border-t border-gray-100 dark:border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar Panel
            </button>
        </form>
    </div>
</div>
