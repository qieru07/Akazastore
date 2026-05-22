<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Akazastore') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabel Transaksi -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Riwayat Transaksi Terbaru</h3>
                        <a href="{{ route('transactions.index') }}" class="text-sm text-blue-500 hover:underline">Lihat Semua &rarr;</a>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-6 py-3">ID TRX</th>
                                        <th class="px-6 py-3">Username</th>
                                        <th class="px-6 py-3">Game / Item</th>
                                        <th class="px-6 py-3">UID & Server</th>
                                        <th class="px-6 py-3">Total Harga</th>
                                        <th class="px-6 py-3">Metode</th>
                                        <th class="px-6 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($riwayat_transaksi as $trx)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-bold text-blue-600">#{{ $trx->id }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $trx->username }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold">{{ $trx->game }}</div>
                                            <div class="text-xs text-gray-400">{{ $trx->item }}</div>
                                        </td>
                                        <td class="px-6 py-4">{{ $trx->user_id }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 uppercase text-xs">{{ $trx->metode }}</td>
                                        <td class="px-6 py-4">
                                            @if(strtolower($trx->status) == 'sukses')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Sukses</span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-400">{{ $trx->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada data transaksi yang masuk.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>