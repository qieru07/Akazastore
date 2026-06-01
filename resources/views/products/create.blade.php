<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700 p-8">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pilih Game -->
                        <div>
                            <x-input-label for="game_id" :value="__('Pilih Game')" class="text-gray-300" />
                            <select name="game_id" id="game_id" class="w-full bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1" required>
                                <option value="">-- Pilih Game --</option>
                                @foreach($games as $game)
                                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <x-input-label for="category" :value="__('Kategori')" class="text-gray-300" />
                            <select name="category" id="category" class="w-full bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1" required>
                                <option value="Diamonds">Diamonds</option>
                                <option value="Special Items">Special Items</option>
                                <option value="Membership">Membership</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <!-- Nama Produk -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Produk (Misal: 86 Diamonds)')" class="text-gray-300" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700" required />
                        </div>

                        <!-- Harga -->
                        <div>
                            <x-input-label for="price" :value="__('Harga (Rupiah)')" class="text-gray-300" />
                            <x-text-input id="price" name="price" type="number" class="mt-1 block w-full bg-gray-900 border-gray-700" required />
                        </div>

                        <!-- Kode Provider (VIP Reseller) -->
                        <div>
                            <x-input-label for="provider_code" :value="__('Kode Provider (VIP Reseller)')" class="text-gray-300" />
                            <x-text-input id="provider_code" name="provider_code" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700" placeholder="Misal: ML50, FF100 (Kosongkan jika manual)" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <x-primary-button>{{ __('Simpan Produk') }}</x-primary-button>
                        <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white transition text-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>