<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AkazaStore - Top Up Game Termurah & Terpercaya</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('akaza_theme') !== 'light') {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        dark: { 900: '#0f172a', 800: '#1e293b', 700: '#334155' },
                        brand: { 500: '#ef4444', 600: '#dc2626' }
                    }
                }
            }
        }
    </script>
    <style>
        body { @apply bg-slate-50 text-slate-900 dark:bg-dark-900 dark:text-slate-100 transition-colors duration-300; font-family: 'Inter', sans-serif; }
        .glass-nav { @apply bg-white/80 dark:bg-dark-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-white/5; }
        [x-cloak] { display: none !important; }
    </style>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav top-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tight italic">AKAZA<span class="text-brand-500">STORE</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button @click="dark = !dark; localStorage.setItem('akaza_theme', dark ? 'dark' : 'light'); if(dark) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark')" 
                            x-data="{ dark: document.documentElement.classList.contains('dark') }"
                            class="p-2 rounded-xl bg-slate-100 dark:bg-dark-800 text-slate-600 dark:text-slate-400 hover:text-brand-500 transition-all border border-slate-200 dark:border-dark-700">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="dark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.242 16.242l.707.707M7.758 7.758l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                    </button>

                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-900 dark:text-white bg-slate-100 dark:bg-dark-800 px-4 py-2 rounded-lg border border-slate-200 dark:border-dark-700 hover:bg-slate-200 dark:hover:bg-dark-700 transition shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 dark:text-gray-300 hover:text-brand-500 transition">Log in</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Banner Carousel -->
        <div x-data="{ activeSlide: 0, slides: {{ $banners->count() }}, timer: null }"
             x-init="timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 4000)"
             class="relative w-full rounded-2xl overflow-hidden shadow-2xl mb-12 bg-white dark:bg-dark-800 border border-slate-200 dark:border-dark-700 ring-1 ring-black/5 dark:ring-white/10 group">
            @if($banners->count() > 0)
                <div class="relative h-48 sm:h-64 md:h-80 lg:h-[400px] w-full flex transition-transform duration-700 ease-out" :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                    @foreach($banners as $index => $banner)
                        <div class="w-full h-full flex-shrink-0 relative">
                            <img src="{{ asset('images/banners/'.$banner->image) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                            <!-- Overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-transparent to-transparent opacity-80"></div>
                            <div class="absolute bottom-0 left-0 p-6 md:p-10 w-full">
                                <h2 class="text-white text-2xl md:text-4xl font-bold drop-shadow-xl translate-y-4 group-hover:translate-y-0 transition-transform duration-300">{{ $banner->title }}</h2>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Controls -->
                <template x-if="slides > 1">
                    <div>
                        <button @click="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1; clearInterval(timer); timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 4000)" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-brand-500 text-white p-3 rounded-full backdrop-blur-md transition opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button @click="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1; clearInterval(timer); timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 4000)" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-brand-500 text-white p-3 rounded-full backdrop-blur-md transition opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <!-- Indicators -->
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2">
                            @foreach($banners as $index => $banner)
                                <button @click="activeSlide = {{ $index }}; clearInterval(timer); timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 4000)" :class="{'bg-brand-500 w-8': activeSlide === {{ $index }}, 'bg-white/50 w-2 hover:bg-white': activeSlide !== {{ $index }}}" class="h-2 rounded-full transition-all duration-300"></button>
                            @endforeach
                        </div>
                    </div>
                </template>
            @else
                <div class="h-48 sm:h-64 flex flex-col items-center justify-center bg-white dark:bg-dark-800 text-center p-6">
                    <svg class="w-12 h-12 text-dark-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-gray-400 font-medium">Belum ada banner promo aktif.</p>
                    <p class="text-gray-500 text-sm mt-1">Silakan tambahkan melalui menu admin.</p>
                </div>
            @endif
        </div>

        <!-- Section: Game Populer -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                    <div class="p-2 bg-brand-500/10 rounded-lg">
                        <svg class="w-6 h-6 text-brand-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                    </div>
                    Game Populer
                </h3>
                <a href="#" class="text-sm font-medium text-brand-500 hover:text-brand-600 transition">Lihat Semua</a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach($games as $game)
                <a href="{{ route('game.show', $game->slug) }}" class="group relative rounded-2xl overflow-hidden bg-white dark:bg-dark-800 border border-slate-200 dark:border-dark-700 hover:border-brand-500 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_40px_-15px_rgba(239,68,68,0.3)]">
                    <div class="aspect-[4/5] w-full relative">
                        <img src="{{ str_starts_with($game->thumbnail, 'http') ? $game->thumbnail : asset('images/games/'.$game->thumbnail) }}" class="w-full h-full object-cover bg-slate-200 dark:bg-dark-900" alt="{{ $game->name }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-80 group-hover:opacity-60 transition-opacity"></div>
                    </div>
                    <div class="absolute bottom-0 w-full p-4 transform transition-transform">
                        <h4 class="font-bold text-white text-lg leading-tight group-hover:text-brand-500 transition">{{ $game->name }}</h4>
                        <p class="text-xs text-brand-500 mt-1 font-medium bg-brand-500/10 inline-block px-2 py-0.5 rounded">Top Up</p>
                    </div>
                </a>
                @endforeach

                @if($games->count() == 0)
                <div class="col-span-full py-12 text-center">
                    <p class="text-slate-500 dark:text-gray-400">Belum ada game yang aktif.</p>
                </div>
                @endif
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 dark:border-white/5 bg-white dark:bg-dark-900 mt-12 py-12 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center">
            <span class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic mb-4">AKAZA<span class="text-brand-500">STORE</span></span>
            <p class="text-slate-500 dark:text-gray-400 text-sm max-w-md mx-auto mb-6">Platform top up game termurah, tercepat, dan terpercaya di Indonesia. Transaksi otomatis 24 jam nonstop.</p>
            <div class="flex space-x-6 mb-8">
                <a href="#" class="text-slate-400 hover:text-brand-500 dark:hover:text-white transition"><span class="sr-only">Instagram</span><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg></a>
                <a href="#" class="text-slate-400 hover:text-brand-500 dark:hover:text-white transition"><span class="sr-only">Facebook</span><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg></a>
            </div>
            <p class="text-slate-400 dark:text-gray-600 text-xs">&copy; {{ date('Y') }} AkazaStore. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
