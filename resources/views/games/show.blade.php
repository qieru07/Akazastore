<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $game->name }} - AkazaStore</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.getItem('akaza_theme') === 'dark' || (!('akaza_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
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
        .glass-card { @apply bg-white dark:bg-dark-800 border border-slate-200 dark:border-white/5 shadow-xl rounded-2xl; }
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
                    <a href="/" class="flex items-center gap-2">
                        <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tight italic">AKAZA<span class="text-brand-500">STORE</span></span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button @click="dark = !dark; localStorage.setItem('akaza_theme', dark ? 'dark' : 'light'); if(dark) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark')" 
                            x-data="{ dark: document.documentElement.classList.contains('dark') }"
                            class="p-2 rounded-xl bg-slate-100 dark:bg-dark-800 text-slate-600 dark:text-slate-400 hover:text-brand-500 transition-all border border-slate-200 dark:border-dark-700">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="dark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.242 16.242l.707.707M7.758 7.758l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                    </button>
                    <a href="/" class="text-sm font-semibold text-slate-600 dark:text-gray-300 hover:text-brand-500 transition">Beranda</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header / Banner -->
    <div class="relative h-[300px] md:h-[400px] w-full overflow-hidden pt-16">
        <img src="{{ asset('images/games/'.$game->thumbnail) }}" class="w-full h-full object-cover blur-sm scale-110 opacity-30 dark:opacity-20" alt="">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-50 dark:via-dark-900 to-slate-50 dark:to-dark-900"></div>
        
        <div class="absolute inset-0 flex items-center justify-center pt-20">
            <div class="max-w-7xl mx-auto px-4 w-full flex flex-col md:flex-row items-center gap-8">
                <div class="w-48 h-64 md:w-56 md:h-72 rounded-2xl overflow-hidden shadow-2xl border-4 border-white dark:border-dark-800 rotate-[-2deg]">
                    <img src="{{ asset('images/games/'.$game->thumbnail) }}" class="w-full h-full object-cover" alt="{{ $game->name }}">
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white tracking-tight mb-2">{{ $game->name }}</h1>
                    <div class="flex items-center justify-center md:justify-start gap-4">
                        <span class="px-3 py-1 bg-brand-500 text-white text-xs font-bold rounded-full uppercase tracking-widest">Official Reseller</span>
                        <span class="flex items-center gap-1 text-slate-500 dark:text-slate-400 text-sm font-medium">
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            Terpercaya 24 Jam
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 md:-mt-24 pb-24 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Input Details -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Step 1: User ID -->
                <div class="glass-card p-6 md:p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 bg-brand-500 text-white rounded-xl flex items-center justify-center font-black text-xl shadow-lg shadow-brand-500/20">1</div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Masukkan Detail Akun</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">User ID</label>
                            <input type="text" placeholder="Masukkan User ID" class="w-full bg-slate-100 dark:bg-dark-900 border border-slate-200 dark:border-dark-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Zone ID / Server</label>
                            <input type="text" placeholder="(Optional)" class="w-full bg-slate-100 dark:bg-dark-900 border border-slate-200 dark:border-dark-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all dark:text-white">
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-slate-400">Untuk menemukan ID Anda, klik pada ikon profil Anda di sudut kiri atas layar utama game. User ID akan terlihat di bawah Nama Pengguna Anda. Silakan masukkan User ID Anda di sini.</p>
                </div>

                <!-- Step 2: Select Product -->
                <div class="glass-card p-6 md:p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 bg-brand-500 text-white rounded-xl flex items-center justify-center font-black text-xl shadow-lg shadow-brand-500/20">2</div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Pilih Nominal Top Up</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($products as $product)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="product" value="{{ $product->id }}" class="peer sr-only">
                            <div class="h-full p-4 border-2 border-slate-200 dark:border-dark-700 rounded-2xl bg-slate-50 dark:bg-dark-900 peer-checked:border-brand-500 peer-checked:bg-brand-500/5 group-hover:border-brand-500 transition-all">
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-brand-500 transition line-clamp-2">{{ $product->name }}</span>
                                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <div class="w-5 h-5 bg-brand-500 text-white rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endforeach

                        @if($products->count() == 0)
                        <div class="col-span-full py-8 text-center bg-slate-100 dark:bg-dark-900 rounded-2xl border border-dashed border-slate-300 dark:border-dark-700">
                            <p class="text-slate-500 dark:text-slate-400">Belum ada produk untuk game ini.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Step 3: Payment Method -->
                <div class="glass-card p-6 md:p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 bg-brand-500 text-white rounded-xl flex items-center justify-center font-black text-xl shadow-lg shadow-brand-500/20">3</div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Pilih Metode Pembayaran</h2>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($paymentMethods as $pm)
                        <label class="relative cursor-pointer block group">
                            <input type="radio" name="payment_method" value="{{ $pm->id }}" class="peer sr-only">
                            <div class="p-4 border-2 border-slate-200 dark:border-dark-700 rounded-2xl bg-slate-50 dark:bg-dark-900 peer-checked:border-brand-500 peer-checked:bg-brand-500/5 group-hover:border-brand-500 transition-all flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-8 bg-white rounded-lg flex items-center justify-center p-1 border border-slate-100">
                                        <img src="{{ asset('images/payments/'.$pm->image) }}" class="max-w-full max-h-full object-contain" alt="{{ $pm->name }}">
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $pm->name }}</span>
                                        <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ $pm->type }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">Instan</span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Step 4: Contact -->
                <div class="glass-card p-6 md:p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 bg-brand-500 text-white rounded-xl flex items-center justify-center font-black text-xl shadow-lg shadow-brand-500/20">4</div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Nomor WhatsApp</h2>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">WhatsApp</label>
                        <input type="tel" placeholder="08xxxxxxxxxx" class="w-full bg-slate-100 dark:bg-dark-900 border border-slate-200 dark:border-dark-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all dark:text-white">
                        <p class="text-[10px] text-slate-400 italic mt-2">*Bukti pembayaran akan dikirimkan melalui WhatsApp</p>
                    </div>
                    
                    <button class="w-full mt-8 bg-brand-500 hover:bg-brand-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-brand-500/30 transition-all hover:-translate-y-1 active:scale-95 text-lg uppercase tracking-widest">
                        Beli Sekarang
                    </button>
                </div>

            </div>

            <!-- Right Column: Sidebar info -->
            <div class="space-y-8">
                <div class="glass-card p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Butuh Bantuan?</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
                        Jika Anda mengalami kendala saat melakukan top up atau memiliki pertanyaan seputar layanan kami, jangan ragu untuk menghubungi customer service kami.
                    </p>
                    <a href="#" class="flex items-center justify-center gap-2 w-full py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl shadow-lg shadow-green-500/20 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.222-3.815c1.498.889 3.12 1.358 4.788 1.359 5.237 0 9.497-4.259 9.501-9.498.002-2.539-1.002-4.915-2.821-6.736-1.817-1.821-4.181-2.825-6.735-2.827-5.24 0-9.501 4.26-9.504 9.498-.001 1.77.481 3.491 1.393 5.018l-1.035 3.793 3.888-1.007zm10.157-7.89c-.31-.156-1.83-.903-2.11-.1.005-.28-.11-.413-.414-.57s-1.107-.442-1.223-.488c-.117-.045-.203-.069-.289.069-.086.138-.333.414-.407.484-.074.07-.147.078-.458-.078-.31-.156-1.311-.482-2.498-1.54-.924-.825-1.547-1.846-1.728-2.157-.181-.311-.019-.479.136-.633l.453-.526c.06-.07.086-.117.129-.199.043-.082.022-.156-.011-.233-.033-.078-.289-.696-.396-.955-.104-.252-.211-.218-.289-.222-.075-.004-.16-.005-.246-.005s-.223.032-.34.159c-.117.128-.446.436-.446 1.062s.457 1.227.521 1.313c.064.086.899 1.373 2.178 1.927.304.132.541.21.728.27.306.097.584.083.805.05.246-.036.755-.309.86-.607.106-.298.106-.554.074-.607-.032-.053-.117-.086-.428-.242z"></path></svg>
                        Hubungi WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 dark:border-white/5 bg-white dark:bg-dark-900 mt-12 py-12 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center">
            <span class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic mb-4">AKAZA<span class="text-brand-500">STORE</span></span>
            <p class="text-slate-500 dark:text-gray-400 text-sm max-w-md mx-auto mb-6">Platform top up game termurah, tercepat, dan terpercaya di Indonesia. Transaksi otomatis 24 jam nonstop.</p>
            <p class="text-slate-400 dark:text-gray-600 text-xs">&copy; {{ date('Y') }} AkazaStore. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
