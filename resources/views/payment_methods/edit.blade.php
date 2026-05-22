<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Metode Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700 p-8">
                <form action="{{ route('payment-methods.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <x-input-label for="name" :value="__('Nama')" class="text-gray-300" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700" :value="$paymentMethod->name" required />
                        </div>

                        <!-- Kode -->
                        <div>
                            <x-input-label for="code" :value="__('Kode Unik')" class="text-gray-300" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700" :value="$paymentMethod->code" required />
                        </div>

                        <!-- Tipe -->
                        <div>
                            <x-input-label for="type" :value="__('Tipe Pembayaran')" class="text-gray-300" />
                            <select name="type" id="type" class="w-full bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1" required>
                                <option value="bank" {{ $paymentMethod->type == 'bank' ? 'selected' : '' }}>Virtual Account / Bank Transfer</option>
                                <option value="qris" {{ $paymentMethod->type == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="ewallet" {{ $paymentMethod->type == 'ewallet' ? 'selected' : '' }}>E-Wallet (OVO/DANA/GOPAY)</option>
                            </select>
                        </div>

                        <!-- No Rekening -->
                        <div>
                            <x-input-label for="account_number" :value="__('Nomor Rekening / VA (Jika ada)')" class="text-gray-300" />
                            <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700" :value="$paymentMethod->account_number" />
                        </div>

                        <!-- Image -->
                        <div class="md:col-span-2">
                            <x-input-label for="image" :value="__('Update Gambar (QRIS / Logo)')" class="text-gray-300" />
                            @if($paymentMethod->image)
                                <div class="mt-2 mb-4">
                                    <img src="{{ asset('images/payments/' . $paymentMethod->image) }}" class="h-32 w-32 object-contain bg-white rounded-md p-2 border border-gray-600" alt="">
                                    <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition">
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <x-primary-button>{{ __('Perbarui Metode') }}</x-primary-button>
                        <a href="{{ route('payment-methods.index') }}" class="text-gray-400 hover:text-white transition text-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
