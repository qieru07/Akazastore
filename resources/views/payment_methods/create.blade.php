<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Metode Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700 p-8">
                <form action="{{ route('payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <x-input-label for="name" :value="__('Nama (Misal: BCA, QRIS)')" class="text-gray-300" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700" required />
                        </div>

                        <!-- Kode -->
                        <div>
                            <x-input-label for="code" :value="__('Kode Unik (Misal: bca, qris)')" class="text-gray-300" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700" required />
                        </div>

                        <!-- Tipe -->
                        <div>
                            <x-input-label for="type" :value="__('Tipe Pembayaran')" class="text-gray-300" />
                            <select name="type" id="type" class="w-full bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1" required>
                                <option value="bank">Virtual Account / Bank Transfer</option>
                                <option value="qris">QRIS</option>
                                <option value="ewallet">E-Wallet (OVO/DANA/GOPAY)</option>
                            </select>
                        </div>

                        <!-- No Rekening -->
                        <div>
                            <x-input-label for="account_number" :value="__('Nomor Rekening / VA (Jika ada)')" class="text-gray-300" />
                            <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700" />
                        </div>

                        <!-- Image -->
                        <div class="md:col-span-2">
                            <x-input-label for="image" :value="__('Upload QRIS / Logo Bank')" class="text-gray-300" />
                            <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition">
                            <p class="mt-2 text-xs text-gray-500 italic">*Jika QRIS, upload gambar QR Code lu disini.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <x-primary-button>{{ __('Simpan Metode') }}</x-primary-button>
                        <a href="{{ route('payment-methods.index') }}" class="text-gray-400 hover:text-white transition text-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
